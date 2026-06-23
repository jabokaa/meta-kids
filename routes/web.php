<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CriancaController;
use App\Http\Controllers\MetaController;
use App\Http\Controllers\RegistroMetaController;
use App\Http\Controllers\UploadController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect('/login'));

// Auth
Route::get('/login',     fn() => view('auth.login'))->name('login');
Route::post('/login',    [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout',   [AuthController::class, 'logout'])->name('logout');

// Upload (acessível sem auth para uso no cadastro)
Route::post('/upload/imagem', [UploadController::class, 'imagem']);

// Área autenticada
Route::middleware('auth')->group(function () {
    Route::get('/perfis',   fn() => view('perfis.index'))->name('perfis');
    Route::get('/metricas', fn() => view('metricas.familia'))->name('metricas');

    Route::get('/crianca/{id}', fn($id) => view('crianca.dashboard', ['criancaId' => $id]))
        ->where('id', '[0-9]+');

    Route::get('/crianca/{criancaId}/meta/{metaId}', function ($criancaId, $metaId) {
        return view('crianca.meta-calendario', compact('criancaId', 'metaId'));
    })->where(['criancaId' => '[0-9]+', 'metaId' => '[0-9]+']);

    // API
    Route::prefix('api')->group(function () {
        Route::get('/criancas',                  [CriancaController::class, 'index']);
        Route::post('/criancas',                 [CriancaController::class, 'store']);
        Route::get('/criancas/{crianca}',        [CriancaController::class, 'show']);
        Route::put('/criancas/{crianca}',        [CriancaController::class, 'update']);
        Route::delete('/criancas/{crianca}',     [CriancaController::class, 'destroy']);

        Route::get('/criancas/{crianca}/metas',  [MetaController::class, 'index']);
        Route::post('/criancas/{crianca}/metas', [MetaController::class, 'store']);
        Route::put('/metas/{meta}',              [MetaController::class, 'update']);
        Route::delete('/metas/{meta}',           [MetaController::class, 'destroy']);

        Route::get('/metas/{meta}/registros',    [RegistroMetaController::class, 'index']);
        Route::post('/metas/{meta}/registros',   [RegistroMetaController::class, 'store']);
        Route::put('/registros/{registro}',      [RegistroMetaController::class, 'update']);
        Route::delete('/registros/{registro}',   [RegistroMetaController::class, 'destroy']);
        Route::get('/metas/{meta}/periodos',     [RegistroMetaController::class, 'periodos']);
    });
});
