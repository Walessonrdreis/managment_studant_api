<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    /**
     * Modelo para gerenciar agendamentos.
     * Este modelo representa a tabela de agendamentos no banco de dados.
     */

    protected $fillable = ['student_id', 'teacher_id', 'date', 'school_id'];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
