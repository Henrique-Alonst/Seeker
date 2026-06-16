<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class AutomacaoVagasController extends Controller
{
    public function executar()
    {
        // Executa o comando do robô em segundo plano através da rota
        Artisan::call('app:buscar-vagas');

        // Pega o texto que o comando cuspiu no terminal
        $resultadoTerminal = Artisan::output();

        // Retorna uma resposta simples e profissional na tela do navegador
        return response()->json([
            'sucesso' => true,
            'mensagem' => 'Robô de automação executado com sucesso via Web!',
            'log_execucao' => explode("\n", trim($resultadoTerminal))
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }
}
