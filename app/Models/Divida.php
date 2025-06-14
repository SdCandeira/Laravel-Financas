<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Divida extends Model
{
    protected $table = 'tab_dividas';
    protected $primaryKey = 'id_divida';

    protected $fillable = [
        'divida', 'valor', 'mes', 'parcelas',
        'tipo', 'vencimento', 'ano', 'em_vigor', 'status', 'recorrente'
    ];

    public $timestamps = false; // já que não tem created_at / updated_at
}
