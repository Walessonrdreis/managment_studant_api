<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\StudentSubject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Role;
use App\Models\User;
use App\Models\Student;
use App\Models\Subject;

class StudentSubjectControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_enroll_student_in_subject()
    {
        // Crie um papel específico para o estudante
        $role = Role::firstOrCreate(['name' => 'student']);

        // Crie um usuário e atribua o papel de estudante
        $user = User::factory()->create(['role_id' => $role->id]);

        // Crie um estudante associado ao usuário
        $student = Student::factory()->create(['user_id' => $user->id]);

        // Crie uma disciplina
        $subject = Subject::factory()->create();

        // Dados para a requisição
        $data = [
            'student_id' => $student->id, // Use o ID do estudante criado
            'subject_id' => $subject->id, // Use o ID da disciplina criada
        ];

        // Faça a requisição para matricular o estudante
        $response = $this->postJson('/api/student-subjects', $data);

        // Verifique se a resposta está correta
        $response->assertStatus(201)
                 ->assertJson(['success' => true, 'message' => 'Estudante matriculado na disciplina com sucesso']);
    }

    /** @test */
    public function it_can_list_student_subjects()
    {
        StudentSubject::factory()->count(3)->create();

        $response = $this->getJson('/api/student-subjects');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    /** @test */
    public function it_can_show_a_student_subject()
    {
        $studentSubject = StudentSubject::factory()->create();

        $response = $this->getJson("/api/student-subjects/{$studentSubject->id}");

        $response->assertStatus(200)
                 ->assertJson(['id' => $studentSubject->id]);
    }

    /** @test */
    public function it_can_update_a_student_subject()
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
        $studentSubject = StudentSubject::factory()->create([
            'student_id' => $student->id,
            'subject_id' => $subject->id, // Use o ID da disciplina criada
        ]);

        // Dados para a atualização
        $response = $this->putJson("/api/student-subjects/{$studentSubject->id}", [
            'subject_id' => $subject->id, // Use o ID da disciplina existente
        ]);

        // Verifique se a resposta está correta
        $response->assertStatus(200)
                 ->assertJson(['success' => true, 'message' => 'Matrícula atualizada com sucesso']);
    }

    /** @test */
    public function it_can_delete_a_student_subject()
    {
        $studentSubject = StudentSubject::factory()->create();

        $response = $this->deleteJson("/api/student-subjects/{$studentSubject->id}");

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Matrícula excluída com sucesso']);
    }

    /** @test */
    public function it_cannot_enroll_student_in_nonexistent_subject()
    {
        // Crie um papel específico para o estudante
        $role = Role::firstOrCreate(['name' => 'student']);

        // Crie um usuário e atribua o papel de estudante
        $user = User::factory()->create(['role_id' => $role->id]);

        // Crie um estudante associado ao usuário
        $student = Student::factory()->create(['user_id' => $user->id]);

        // Tente matricular o estudante em uma disciplina que não existe
        $response = $this->postJson('/api/student-subjects', [
            'student_id' => $student->id,
            'subject_id' => 9999, // ID que não existe
        ]);

        // Verifique se a resposta está correta
        $response->assertStatus(404)
                 ->assertJson(['success' => false, 'message' => 'Não há disciplina para registrar. Aguarde a adição de uma nova disciplina.']);
    }
}
