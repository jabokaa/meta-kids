<?php

namespace App\Http\Controllers;

use App\Models\Crianca;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->route('perfis');
        }

        return back()->withErrors(['email' => 'E-mail ou senha incorretos.'])->onlyInput('email');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $criancas = json_decode($request->input('criancas', '[]'), true) ?? [];

        foreach ($criancas as $c) {
            if (empty($c['nome'])) continue;

            Crianca::create([
                'user_id'         => $user->id,
                'nome'            => $c['nome'],
                'data_nascimento' => $c['dataNascimento'] ?: null,
                'imagem'          => $c['imagem'] ?: null,
                'estilo'          => (int) ($c['estilo'] ?? 1),
            ]);
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('perfis');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
