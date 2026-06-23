(function () {
  'use strict';

  // Hide x-dc until processed
  const hs = document.createElement('style');
  hs.textContent = 'x-dc{visibility:hidden}';
  document.head.appendChild(hs);

  // ── DCLogic base class ────────────────────────────────────
  class DCLogic {
    constructor(props) {
      this.props = props || {};
      this.state = {};
      this._render = () => {};
    }
    setState(upd) {
      this.state = Object.assign({}, this.state,
        typeof upd === 'function' ? upd(this.state) : upd);
      this._render();
    }
    renderVals() { return {}; }
  }
  window.DCLogic = DCLogic;

  // ── Utilities ─────────────────────────────────────────────
  function toStyle(v) {
    if (typeof v === 'string') return v;
    if (v && typeof v === 'object')
      return Object.entries(v)
        .map(([k, val]) => k.replace(/[A-Z]/g, m => '-' + m.toLowerCase()) + ':' + val)
        .join(';');
    return '';
  }

  function evalX(expr, ctx) {
    try {
      const ks = Object.keys(ctx), vs = ks.map(k => ctx[k]);
      return (new Function(...ks, 'return(' + expr + ')'))(...vs);
    } catch { return undefined; }
  }

  function interp(str, ctx) {
    return str.replace(/\{\{\s*([\s\S]+?)\s*\}\}/g, (_, e) => {
      const v = evalX(e.trim(), ctx);
      return v == null ? '' : String(v);
    });
  }

  function pureExpr(str) {
    const m = str.match(/^\{\{\s*([\s\S]+?)\s*\}\}$/);
    return m ? m[1].trim() : null;
  }

  // ── Virtual node builder ──────────────────────────────────
  function buildVNodes(nodes, ctx) {
    return nodes.flatMap(n => buildVNode(n, ctx));
  }

  function buildVNode(node, ctx) {
    if (node.nodeType === 3) {
      return [{ type: 't', text: interp(node.textContent, ctx) }];
    }
    if (node.nodeType !== 1) return [];

    const tag = node.tagName.toLowerCase();

    if (tag === 'sc-if') {
      const ex = pureExpr(node.getAttribute('value') || '');
      const show = ex ? !!evalX(ex, ctx) : true;
      return show ? buildVNodes(Array.from(node.childNodes), ctx) : [];
    }

    if (tag === 'sc-for') {
      const lex = pureExpr(node.getAttribute('list') || '');
      const as  = node.getAttribute('as') || 'item';
      const list = (lex ? evalX(lex, ctx) : []) || [];
      const kids = Array.from(node.childNodes);
      return (Array.isArray(list) ? list : []).flatMap(item =>
        buildVNodes(kids, Object.assign({}, ctx, { [as]: item }))
      );
    }

    const attrs = {}, events = {};

    Array.from(node.attributes).forEach(attr => {
      const name = attr.name, raw = attr.value;

      // on* with DC expr → event handler
      if (/^on[a-z]/i.test(name) && raw.includes('{{')) {
        const ex = pureExpr(raw);
        if (ex) {
          const fn = evalX(ex, ctx);
          if (typeof fn === 'function') { events[name.slice(2).toLowerCase()] = fn; return; }
        }
      }

      // style
      if (name === 'style') {
        const ex = pureExpr(raw);
        attrs.style = ex ? toStyle(evalX(ex, ctx)) : (raw.includes('{{') ? interp(raw, ctx) : raw);
        return;
      }

      // general attribute
      const ex = pureExpr(raw);
      if (ex) {
        const v = evalX(ex, ctx);
        if (v !== false && v !== null && v !== undefined)
          attrs[name] = v === true ? '' : String(v);
      } else {
        attrs[name] = raw.includes('{{') ? interp(raw, ctx) : raw;
      }
    });

    return [{ type: 'e', tag, attrs, events,
      children: buildVNodes(Array.from(node.childNodes), ctx) }];
  }

  // ── DOM proxy ─────────────────────────────────────────────
  const EV_NAMES = 'click,input,submit,change,mousedown,mouseup,mouseenter,mouseleave,focus,blur'.split(',');

  function proxy(el) {
    if (el._dcP) return;
    el._dcP = true;
    el._dcH = {};
    EV_NAMES.forEach(ev => el.addEventListener(ev, e => el._dcH[ev] && el._dcH[ev](e)));
  }

  // ── DOM create / patch ────────────────────────────────────
  function createElement(vn) {
    if (vn.type === 't') return document.createTextNode(vn.text);
    const el = document.createElement(vn.tag);
    Object.entries(vn.attrs).forEach(([k, v]) => el.setAttribute(k, v));
    proxy(el);
    Object.assign(el._dcH, vn.events);
    const t = el.tagName;
    if ((t === 'INPUT' || t === 'TEXTAREA') && 'value' in vn.attrs) el.value = vn.attrs.value;
    if (t === 'SELECT' && 'value' in vn.attrs) el.value = vn.attrs.value;
    vn.children.forEach(cv => el.appendChild(createElement(cv)));
    return el;
  }

  function patchEl(old, vn) {
    // sync attributes
    const kept = new Set(Object.keys(vn.attrs));
    Object.entries(vn.attrs).forEach(([k, v]) => { if (old.getAttribute(k) !== v) old.setAttribute(k, v); });
    Array.from(old.attributes).forEach(a => { if (!kept.has(a.name)) old.removeAttribute(a.name); });

    // sync input value (skip if focused)
    const t = old.tagName;
    if ((t === 'INPUT' || t === 'TEXTAREA') && 'value' in vn.attrs && old !== document.activeElement) {
      if (old.value !== vn.attrs.value) old.value = vn.attrs.value;
    }
    if (t === 'SELECT' && 'value' in vn.attrs) old.value = vn.attrs.value;

    // sync event handlers
    proxy(old);
    old._dcH = Object.assign({}, vn.events);

    patchChildren(old, vn.children);
  }

  function patchChildren(parent, vnodes) {
    const olds = Array.from(parent.childNodes);
    for (let i = 0; i < vnodes.length; i++) {
      const vn = vnodes[i];
      if (i < olds.length) {
        const old = olds[i];
        if (vn.type === 't' && old.nodeType === 3) {
          if (old.textContent !== vn.text) old.textContent = vn.text;
        } else if (vn.type === 'e' && old.nodeType === 1 && old.tagName.toLowerCase() === vn.tag) {
          patchEl(old, vn);
        } else {
          parent.replaceChild(createElement(vn), old);
        }
      } else {
        parent.appendChild(createElement(vn));
      }
    }
    while (parent.childNodes.length > vnodes.length) parent.removeChild(parent.lastChild);
  }

  // ── Runtime ───────────────────────────────────────────────
  class DCRuntime {
    constructor(host, sc) {
      this.host = host;

      try { this.props = JSON.parse(sc.getAttribute('data-props') || '{}'); }
      catch { this.props = {}; }

      sc.remove();

      // Move helmet children into <head>
      const tmp = document.createElement('div');
      tmp.innerHTML = host.innerHTML;
      const helm = tmp.querySelector('helmet');
      if (helm) {
        Array.from(helm.childNodes).forEach(n => document.head.appendChild(n.cloneNode(true)));
        helm.remove();
      }
      this.tpl = tmp.innerHTML;
      host.innerHTML = '';

      // Instantiate component
      const Cls = (new Function('DCLogic', sc.textContent + '\nreturn Component;'))(DCLogic);
      this.inst = new Cls(this.props);
      this.inst._render = () => this.render();

      this.render();
      host.style.visibility = 'visible';
    }

    tplNodes() {
      const d = document.createElement('div');
      d.innerHTML = this.tpl;
      return Array.from(d.childNodes);
    }

    render() {
      const vals = this.inst.renderVals();
      patchChildren(this.host, buildVNodes(this.tplNodes(), vals));
    }
  }

  // ── Bootstrap ─────────────────────────────────────────────
  function init() {
    document.querySelectorAll('x-dc').forEach(host => {
      const sc = host.querySelector('[data-dc-script]');
      if (!sc) return;
      try { new DCRuntime(host, sc); }
      catch (e) { console.error('[DC]', e); host.style.visibility = 'visible'; }
    });
  }

  document.readyState === 'loading'
    ? document.addEventListener('DOMContentLoaded', init)
    : init();
})();
