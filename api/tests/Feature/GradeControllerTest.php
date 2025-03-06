<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Grade;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Role;
use App\Models\User;
use App\Models\Student;
use App\Models\Subject;

class GradeControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_grade()
    {
        // Crie um papel específico para o estudante
        $role = Role::firstOrCreate(['name' => 'student']);

        // Crie um usuário e atribua o papel de estudante
        $user = User::factory()->create(['role_id' => $role->id]);

        // Crie um estudante associado ao usuário
        $student = Student::factory()->create(['user_id' => $user->id]);

        // Crie uma disciplina
        $subject = Subject::factory()->create(); // Cria uma disciplina e obtém o ID

        // Dados para a requisição
        $data = [
            'student_id' => $student->id, // Use o ID do estudante criado
            'subject_id' => $subject->id, // Use o ID da disciplina criada
            'grade' => 90,
        ];

        // Faça a requisição para criar a nota
        $response = $this->postJson('/api/grades', $data);

        // Verifique se a resposta está correta
        $response->assertStatus(201)
                 ->assertJson(['success' => true, 'message' => 'Nota criada com sucesso']);
    }

    /** @test */
    public function it_can_list_grades()
    {
        Grade::factory()->count(3)->create();

        $response = $this->getJson('/api/grades');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    /** @test */
    public function it_can_show_a_grade()
    {
        $grade = Grade::factory()->create();

        $response = $this->getJson("/api/grades/{$grade->id}");

        $response->assertStatus(200)
                 ->assertJson(['id' => $grade->id]);
    }

    /** @test */
    public function it_can_update_a_grade()
    {
        $grade = Grade::factory()->create();

        $response = $this->putJson("/api/grades/{$grade->id}", [
            'grade' => 95,
        ]);

        $response->assertStatus(200)
                 ->assertJson(['success' => true, 'message' => 'Nota atualizada com sucesso']);
    }

    /** @test */
    public function it_can_delete_a_grade()
    {
        $grade = Grade::factory()->create();

        $response = $this->deleteJson("/api/grades/{$grade->id}");

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Nota excluída com sucesso']);
    }
}
