<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Teacher extends Model
{
    use HasFactory;
    /**
     * Modelo para gerenciar professores.
     * Este modelo representa a tabela de professores no banco de dados.
     */

    protected $fillable = ['name', 'subject', 'user_id', 'school_id'];

    /**
     * Define a relação com o modelo User.
     * Um professor pertence a um usuário.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Define a relação com o modelo School.
     * Um professor pertence a uma escola.
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Define a relação com o modelo Subject.
     * Um professor pode lecionar várias disciplinas.
     */
    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class);
    }

    /**
     * Define a relação com o modelo Appointment.
     * Um professor pode ter vários agendamentos.
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }
}
