<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function imagem(Request $request)
    {
        $request->validate([
            'imagem' => ['required', 'image', 'mimes:jpeg,png,webp,gif', 'max:3072'],
        ]);

        $path = $request->file('imagem')->store('uploads/criancas', 'public');

        return response()->json(['url' => Storage::url($path)]);
    }
}
