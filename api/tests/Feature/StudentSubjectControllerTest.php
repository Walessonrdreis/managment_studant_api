<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\StudentSubject;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StudentSubjectControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_enroll_student_in_subject()
    {
        $response = $this->postJson('/api/student-subjects', [
            'student_id' => 1,
            'subject_id' => 1,
        ]);

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
        $studentSubject = StudentSubject::factory()->create();

        $response = $this->putJson("/api/student-subjects/{$studentSubject->id}", [
            'subject_id' => 2,
        ]);

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
}
