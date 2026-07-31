<?php

use App\Http\Controllers\ArquivoVagaController;
use Illuminate\Support\Facades\Route;

// Rota para exibir a página com a lista de vagas
Route::get('/', [ArquivoVagaController::class, 'index'])->name('vagas.index');

// Rota para salvar a nova vaga enviada pelo formulário
Route::post('/vagas', [ArquivoVagaController::class, 'salvarVaga'])->name('vagas.store');
