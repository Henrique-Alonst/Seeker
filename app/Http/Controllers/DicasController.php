<?php

namespace App\Http\Controllers;

use App\Models\Dicas;
use Illuminate\Http\Request;

class DicasController extends Controller
{

    public function salvarDica(Request $request)
    {
        $dadosValidados = $request->validate([
            'descricao' => ['required', 'string', 'max:255'],
        ]);

        Dicas::create($dadosValidados);
        return redirect()->back()->with('success', 'Dica registrada com sucesso!!');
    }

    public function excluirDica($id)
    {
        $dica = Dicas::findOrFail($id);
        $dica->delete();

        return redirect()->back()->with('success', 'Dica excluída.');
    }

    public function editarDica(Request $request, $id)
    {
        $dica = Dicas::findOrFail($id);
        $dadosValidados = $request->validate([
            'descricao' => ['required', 'string', 'max:255'],
        ]);

        $dica->update($dadosValidados);

        return redirect()->back()->with('success', 'Dica atualizada com sucesso!');
    }
}
