<?php

namespace App\Http\Controllers;

use App\Models\Crianca;
use App\Models\CriancaMeta;
use App\Services\MetaPeriodoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MetaController extends Controller
{
    public function index(Crianca $crianca)
    {
        abort_if($crianca->user_id !== Auth::id(), 403);

        return $crianca->metas()->with('periodos')->latest()->get();
    }

    public function store(Request $request, Crianca $crianca)
    {
        abort_if($crianca->user_id !== Auth::id(), 403);

        $data = $request->validate([
            'descricao'      => ['required', 'string', 'max:200'],
            'metas'          => ['required', 'string', 'max:200'],
            'data_inicio'    => ['required', 'date'],
            'data_fim'       => ['nullable', 'date', 'after_or_equal:data_inicio'],
            'tipo'           => ['required', 'in:semanal,mensal'],
            'valor_meta'     => ['required', 'integer', 'min:1'],
            'maximo_por_dia'          => ['nullable', 'integer', 'min:1', 'max:20'],
            'bloquear_dias_anteriores' => ['boolean'],
        ]);

        $meta = $crianca->metas()->create($data);
        MetaPeriodoService::gerar($meta);

        return $meta;
    }

    public function update(Request $request, CriancaMeta $meta)
    {
        $crianca = $meta->crianca;
        abort_if($crianca->user_id !== Auth::id(), 403);

        $data = $request->validate([
            'descricao'      => ['required', 'string', 'max:200'],
            'metas'          => ['required', 'string', 'max:200'],
            'data_inicio'    => ['required', 'date'],
            'data_fim'       => ['nullable', 'date', 'after_or_equal:data_inicio'],
            'tipo'           => ['required', 'in:semanal,mensal'],
            'valor_meta'     => ['required', 'integer', 'min:1'],
            'maximo_por_dia'          => ['nullable', 'integer', 'min:1', 'max:20'],
            'bloquear_dias_anteriores' => ['boolean'],
        ]);

        $meta->update($data);
        MetaPeriodoService::gerar($meta->fresh());

        return $meta->fresh();
    }

    public function destroy(CriancaMeta $meta)
    {
        $crianca = $meta->crianca;
        abort_if($crianca->user_id !== Auth::id(), 403);

        $meta->delete();

        return response()->noContent();
    }
}
