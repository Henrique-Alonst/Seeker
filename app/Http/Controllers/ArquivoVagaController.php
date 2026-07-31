<?php

namespace App\Http\Controllers;

use App\Models\ArquivoVaga;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ArquivoVagaController extends Controller
{

    public function index()
    {
        $vagas = ArquivoVaga::latest()->get(); // Busca do banco (da mais recente para a mais antiga)
        return view('home', compact('vagas')); // Envia a variável $vagas para a view
    }

    public function salvarVaga(Request $request)
    {

        $dadosValidados = $request->validate([
            'cargo' => ['required', 'string', 'max:255'],
            'empresa' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'link' => ['nullable', 'string'],
            'notas' => ['nullable', 'string'],
            'data' => ['required', 'date'],
        ]);

        ArquivoVaga::create($dadosValidados);

        // $nomeCargo = $request->input('cargo');
        // $nomeEmpresa = $request->input('empresa');
        // $nomeStatus = $request->input('status');
        // $nomeLink = $request->input('link');
        // $nomeNotas = $request->input('notas');
        // $data = $request->input('data');

        // $arquivoVaga = new ArquivoVaga;
        // $arquivoVaga->save();

        return redirect()->back()->with('success', 'Vaga registrada com sucesso!!');
    }
}
