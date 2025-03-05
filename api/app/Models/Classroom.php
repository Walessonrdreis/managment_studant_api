<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    /**
     * Modelo para gerenciar salas de aula.
     * Este modelo representa a tabela de salas de aula no banco de dados.
     */

    protected $fillable = ['name', 'capacity', 'school_id'];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
