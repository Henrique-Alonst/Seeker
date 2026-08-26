<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'descricao',
])]
class Dicas extends Model
{
    protected $table = 'dicas';
}
