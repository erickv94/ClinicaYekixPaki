<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistoriaMedica extends Model
{
    protected $fillable = [
        'descripcion','paciente_id'
    ];
}
