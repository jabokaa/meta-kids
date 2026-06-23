<?php

namespace App\Http\Controllers;

use App\Models\Crianca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CriancaController extends Controller
{
    public function index()
    {
        return Auth::user()->criancas()->latest()->get();
    }

    public function show(Crianca $crianca)
    {
        abort_if($crianca->user_id !== Auth::id(), 403);

        return $crianca;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome'            => ['required', 'string', 'max:100'],
            'data_nascimento' => ['required', 'date'],
            'imagem'          => ['nullable', 'string', 'max:500'],
            'estilo'          => ['nullable', 'integer', 'min:1', 'max:5'],
        ]);

        $data['estilo'] = $data['estilo'] ?? 1;

        return Auth::user()->criancas()->create($data);
    }

    public function update(Request $request, Crianca $crianca)
    {
        abort_if($crianca->user_id !== Auth::id(), 403);

        $data = $request->validate([
            'nome'            => ['required', 'string', 'max:100'],
            'data_nascimento' => ['required', 'date'],
            'imagem'          => ['nullable', 'string', 'max:500'],
            'estilo'          => ['nullable', 'integer', 'min:1', 'max:5'],
        ]);

        $crianca->update($data);

        return $crianca->fresh();
    }

    public function destroy(Crianca $crianca)
    {
        abort_if($crianca->user_id !== Auth::id(), 403);

        $crianca->delete();

        return response()->noContent();
    }
}
