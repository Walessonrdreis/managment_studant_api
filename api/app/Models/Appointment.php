<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Appointment extends Model
{
    use HasFactory;
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
