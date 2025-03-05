<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    /**
     * Modelo para gerenciar disciplinas.
     * Este modelo representa a tabela de disciplinas no banco de dados.
     */

    protected $fillable = ['name', 'description', 'school_id'];

    /**
     * Define a relação com o modelo School.
     * Uma disciplina pertence a uma escola.
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Define a relação com o modelo Grade.
     * Uma disciplina pode ter muitas notas.
     */
    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }

    /**
     * Define a relação com o modelo StudentSubject.
     * Uma disciplina pode ter muitas associações de estudantes.
     */
    public function studentSubjects(): HasMany
    {
        return $this->hasMany(StudentSubject::class);
    }
}
