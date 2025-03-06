<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Role;
use PHPUnit\Framework\Attributes\Test;

class StudentControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_create_a_student()
    {
        // Crie um papel específico para o estudante
        $role = Role::firstOrCreate(['name' => 'student']);

        // Crie um usuário e atribua o papel de estudante
        $user = User::factory()->create(['role_id' => $role->id]);

        // Autentique o usuário
        $this->actingAs($user);

        // Dados do estudante
        $studentData = [
            'name' => 'Jane Doe',
            'email' => 'jane.doe@example.com',
            'date_of_birth' => '2000-01-01',
            'user_id' => $user->id, // Use o ID do usuário criado
        ];

        // Faça a requisição para criar o estudante
        $response = $this->postJson('/api/students', $studentData);

        // Verifique se a resposta está correta
        $response->assertStatus(201)
                 ->assertJson(['success' => true, 'message' => 'Estudante criado com sucesso']);
    }
    
    // ... outros métodos de teste ...

    #[Test]
    public function it_can_list_students()
    {
        Student::factory()->count(3)->create();

        $response = $this->getJson('/api/students');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    #[Test]
    public function it_can_show_a_student()
    {
        $student = Student::factory()->create();

        $response = $this->getJson("/api/students/{$student->id}");

        $response->assertStatus(200)
                 ->assertJson(['id' => $student->id]);
    }

    #[Test]
    public function it_can_update_a_student()
    {
        // Crie um papel específico para o estudante
        $role = Role::firstOrCreate(['name' => 'student']);

        // Crie um usuário e atribua o papel de estudante
        $user = User::factory()->create(['role_id' => $role->id]);

        // Crie um estudante associado ao usuário
        $student = Student::factory()->create(['user_id' => $user->id]);

        // Dados atualizados do estudante
        $updatedData = [
            'name' => 'Updated Student',
            'email' => 'updated_student@example.com',
            'date_of_birth' => '2001-01-01',
            'user_id' => $user->id, // Inclua o user_id
        ];

        // Faça a requisição para atualizar o estudante
        $response = $this->putJson("/api/students/{$student->id}", $updatedData);

        // Verifique se a resposta está correta
        $response->assertStatus(200)
                 ->assertJson(['success' => true, 'message' => 'Estudante atualizado com sucesso']);
    }

    #[Test]
    public function it_can_delete_a_student()
    {
        $student = Student::factory()->create();

        $response = $this->deleteJson("/api/students/{$student->id}");

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Estudante excluído com sucesso']);
    }
}
