<?php

namespace App\Http\Controllers;

use App\Models\ArquivoVaga;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ArquivoVagaController extends Controller
{

    public function index(Request $request)
    {
        $query = ArquivoVaga::query();

        // Aplica o filtro de status na Query caso a URL contenha ?status=valor
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Busca as vagas filtradas (ou todas) da mais recente para a mais antiga
        $vagas = $query->latest()->get();

        // Busca a contagem total de vagas independentemente do filtro aplicado
        $vagasCountTotal = ArquivoVaga::count();

        return view('home', compact('vagas', 'vagasCountTotal'));
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
            'salario' => ['nullable', 'decimal:0,2'],
        ]);

        ArquivoVaga::create($dadosValidados);
        return redirect()->back()->with('success', 'Vaga registrada com sucesso!!');
    }

    public function excluirVaga($id)
    {
        $vaga = ArquivoVaga::findOrFail($id);
        $vaga->delete();

        return redirect()->back()->with('success', 'Vaga excluída.');
    }

    public function editarVaga(Request $request, $id)
    {
        $vaga = ArquivoVaga::findOrFail($id);

        $dadosValidados = $request->validate([
            'cargo' => ['required', 'string', 'max:255'],
            'empresa' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'link' => ['nullable', 'string'],
            'notas' => ['nullable', 'string'],
            'data' => ['required', 'date'],
            'salario' => ['nullable', 'decimal:0,2'],
        ]);

        $vaga->update($dadosValidados);

        return redirect()->back()->with('success', 'Vaga atualizada com sucesso!');
    }
}
