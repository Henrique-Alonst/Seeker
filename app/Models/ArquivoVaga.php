<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'cargo', 'empresa', 'status', 'link', 'notas', 'data',
])]
class ArquivoVaga extends Model
{
    protected $table = 'arquivo_vagas';

    // Converte a coluna 'data' automaticamente para um objeto Carbon
    protected $casts = [
        'data' => 'date',
    ];
}
