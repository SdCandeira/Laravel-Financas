<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Historico extends Model
{
    protected $table = 'tab_historico';
    protected $primaryKey = 'id_historico';

    protected $fillable = [
        'divida', 'valor', 'mes', 'parcelas',
        'tipo', 'vencimento', 'ano', 'em_vigor', 'status', 'recorrente'
    ];

    public $timestamps = false; // já que não tem created_at / updated_at
}