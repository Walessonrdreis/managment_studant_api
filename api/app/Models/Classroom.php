<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Classroom extends Model
{
    use HasFactory;
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
