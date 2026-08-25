<?php

use App\Http\Controllers\ArquivoVagaController;
use App\Http\Controllers\DicasController;
use Illuminate\Support\Facades\Route;

// Rota para exibir a página com a lista de vagas
Route::get('/', [ArquivoVagaController::class, 'index'])->name('vagas.index');
// Rota para salvar a nova vaga enviada pelo formulário
Route::post('/vagas', [ArquivoVagaController::class, 'salvarVaga'])->name('vagas.store');
//Rota para deletar vaga
Route::delete('/vagas/{vaga}', [ArquivoVagaController::class, 'excluirVaga'])->name('vagas.excluir');
//Rota para abrir modal de editar vagas
Route::get('/vagas/{vaga}/editar', [ArquivoVagaController::class, 'editarVaga'])->name('vagas.editar');
//Rota para salvar edição de vaga
Route::put('/vagas/{vaga}', [ArquivoVagaController::class, 'editarVaga'])->name('vagas.update');
// Rota para salvar a nova dica enviada pelo formulário
Route::post('/dicas', [DicasController::class, 'salvarDica'])->name('dicas.salvar');
//Rota para deletar dica
Route::delete('/dicas/{dica}', [DicasController::class, 'excluirDica'])->name('dicas.excluir');
//Rota para editar dica
Route::put('/dicas/{dica}', [DicasController::class, 'editarDica'])->name('dicas.editar');


