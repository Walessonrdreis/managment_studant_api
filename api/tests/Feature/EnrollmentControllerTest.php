<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Enrollment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Role;
use App\Models\User;
use App\Models\Student;
use App\Models\Subject;

class EnrollmentControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_an_enrollment()
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
        ];

        // Faça a requisição para criar a matrícula
        $response = $this->postJson('/api/enrollments', $data);

        // Verifique se a resposta está correta
        $response->assertStatus(201)
                 ->assertJson(['success' => true, 'message' => 'Matrícula criada com sucesso']);
    }

    /** @test */
    public function it_can_list_enrollments()
    {
        Enrollment::factory()->count(3)->create();

        $response = $this->getJson('/api/enrollments');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    /** @test */
    public function it_can_show_an_enrollment()
    {
        $enrollment = Enrollment::factory()->create();

        $response = $this->getJson("/api/enrollments/{$enrollment->id}");

        $response->assertStatus(200)
                 ->assertJson(['id' => $enrollment->id]);
    }

    /** @test */
    public function it_can_update_an_enrollment()
    {
        // Crie um papel específico para o estudante
        $role = Role::firstOrCreate(['name' => 'student']);

        // Crie um usuário e atribua o papel de estudante
        $user = User::factory()->create(['role_id' => $role->id]);

        // Crie um estudante associado ao usuário
        $student = Student::factory()->create(['user_id' => $user->id]);

        // Crie uma disciplina
        $subject = Subject::factory()->create(); // Cria uma disciplina e obtém o ID

        // Crie a matrícula do estudante na disciplina
        $enrollment = Enrollment::factory()->create([
            'student_id' => $student->id,
            'subject_id' => $subject->id,
        ]);

        // Dados para a atualização
        $response = $this->putJson("/api/enrollments/{$enrollment->id}", [
            'student_id' => $student->id, // Use o ID do estudante criado
            'subject_id' => $subject->id, // Use o ID da disciplina criada
        ]);

        // Verifique se a resposta está correta
        $response->assertStatus(200)
                 ->assertJson(['success' => true, 'message' => 'Matrícula atualizada com sucesso']);
    }

    /** @test */
    public function it_can_delete_an_enrollment()
    {
        $enrollment = Enrollment::factory()->create();

        $response = $this->deleteJson("/api/enrollments/{$enrollment->id}");

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Matrícula excluída com sucesso']);
    }
}
