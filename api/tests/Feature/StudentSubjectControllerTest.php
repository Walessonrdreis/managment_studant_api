<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\StudentSubject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Role;
use App\Models\User;
use App\Models\Student;
use App\Models\Subject;
use PHPUnit\Framework\Attributes\Test;

class StudentSubjectControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_enroll_student_in_subject()
    {
        $role = Role::firstOrCreate(['name' => 'student']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $student = Student::factory()->create(['user_id' => $user->id]);
        $subject = Subject::factory()->create();

        $data = [
            'student_id' => $student->id,
            'subject_id' => $subject->id,
        ];

        $response = $this->postJson('/api/student-subjects', $data);

        $response->assertStatus(201)
                 ->assertJson(['success' => true, 'message' => 'Estudante matriculado na disciplina com sucesso']);
    }

    #[Test]
    public function it_can_list_student_subjects()
    {
        StudentSubject::factory()->count(3)->create();

        $response = $this->getJson('/api/student-subjects');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    #[Test]
    public function it_can_show_a_student_subject()
    {
        $studentSubject = StudentSubject::factory()->create();

        $response = $this->getJson("/api/student-subjects/{$studentSubject->id}");

        $response->assertStatus(200)
                 ->assertJson(['id' => $studentSubject->id]);
    }

    #[Test]
    public function it_can_update_a_student_subject()
    {
        $role = Role::firstOrCreate(['name' => 'student']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $student = Student::factory()->create(['user_id' => $user->id]);
        $subject = Subject::factory()->create();
        $studentSubject = StudentSubject::factory()->create([
            'student_id' => $student->id,
            'subject_id' => $subject->id,
        ]);

        $response = $this->putJson("/api/student-subjects/{$studentSubject->id}", [
            'subject_id' => $subject->id,
        ]);

        $response->assertStatus(200)
                 ->assertJson(['success' => true, 'message' => 'Matrícula atualizada com sucesso']);
    }

    #[Test]
    public function it_can_delete_a_student_subject()
    {
        $studentSubject = StudentSubject::factory()->create();

        $response = $this->deleteJson("/api/student-subjects/{$studentSubject->id}");

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Matrícula excluída com sucesso']);
    }

    #[Test]
    public function it_cannot_enroll_student_in_nonexistent_subject()
    {
        $role = Role::firstOrCreate(['name' => 'student']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $student = Student::factory()->create(['user_id' => $user->id]);

        $response = $this->postJson('/api/student-subjects', [
            'student_id' => $student->id,
            'subject_id' => 9999, // ID que não existe
        ]);

        $response->assertStatus(404)
                 ->assertJson(['success' => false, 'message' => 'Não há disciplina para registrar. Aguarde a adição de uma nova disciplina.']);
    }
}
