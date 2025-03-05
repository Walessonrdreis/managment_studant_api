<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable; // Importando a classe base para autenticação
use Illuminate\Notifications\Notifiable; // Para notificações
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use Notifiable; // Usando o trait Notifiable para enviar notificações

    /**
     * Modelo para gerenciar usuários.
     * Este modelo representa a tabela de usuários no banco de dados.
     */

    // Campos que podem ser preenchidos em massa
    protected $fillable = ['name', 'email', 'password', 'role_id', 'school_id'];

    // Campos que devem ser ocultados em arrays (ex: ao retornar JSON)
    protected $hidden = ['password', 'remember_token'];

    /**
     * Método que é chamado ao criar um novo usuário.
     * Aqui, a senha é criptografada antes de ser salva no banco de dados.
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->password = bcrypt($user->password); // Hash da senha
        });
    }

    // Relacionamento: Um usuário pode ter muitos agendamentos
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    // Relacionamento: Um usuário pode ter muitas presenças registradas
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function teachers(): HasMany
    {
        return $this->hasMany(Teacher::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    // Outros relacionamentos podem ser adicionados conforme necessário
}
