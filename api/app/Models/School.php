<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class School extends Model
{
    use HasFactory;
    /**
     * Modelo para gerenciar escolas.
     * Este modelo representa a tabela de escolas no banco de dados.
     */

    protected $fillable = ['name'];

    /**
     * Define a relação com o modelo User.
     * Uma escola pode ter muitos usuários (exceto administradores).
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Define a relação com o modelo Student.
     * Uma escola pode ter muitos estudantes.
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    /**
     * Define a relação com o modelo Teacher.
     * Uma escola pode ter muitos professores.
     */
    public function teachers(): HasMany
    {
        return $this->hasMany(Teacher::class);
    }

    /**
     * Define a relação com o modelo Classroom.
     * Uma escola pode ter muitas salas de aula.
     */
    public function classrooms(): HasMany
    {
        return $this->hasMany(Classroom::class);
    }

    /**
     * Define a relação com o modelo Subject.
     * Uma escola pode ter muitas disciplinas.
     */
    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class);
    }

    /**
     * Define a relação com o modelo Appointment.
     * Uma escola pode ter muitos agendamentos.
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Define a relação com o modelo Grade.
     * Uma escola pode ter muitas notas.
     */
    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }
}
