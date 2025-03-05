<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    /**
     * Modelo para gerenciar estudantes.
     * Este modelo representa a tabela de estudantes no banco de dados.
     */

    protected $fillable = ['name', 'email', 'date_of_birth', 'user_id', 'school_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function studentSubjects(): HasMany
    {
        return $this->hasMany(StudentSubject::class);
    }
}
