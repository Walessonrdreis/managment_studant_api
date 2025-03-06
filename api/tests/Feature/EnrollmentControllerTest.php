<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Enrollment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Role;
use App\Models\User;
use App\Models\Student;
use App\Models\Subject;
use PHPUnit\Framework\Attributes\Test;

class EnrollmentControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_create_an_enrollment()
    {
        $role = Role::firstOrCreate(['name' => 'student']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $student = Student::factory()->create(['user_id' => $user->id]);
        $subject = Subject::factory()->create();

        $data = [
            'student_id' => $student->id,
            'subject_id' => $subject->id,
        ];

        $response = $this->postJson('/api/enrollments', $data);

        $response->assertStatus(201)
                 ->assertJson(['success' => true, 'message' => 'Matrícula criada com sucesso']);
    }

    #[Test]
    public function it_can_list_enrollments()
    {
        Enrollment::factory()->count(3)->create();

        $response = $this->getJson('/api/enrollments');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    #[Test]
    public function it_can_show_an_enrollment()
    {
        $enrollment = Enrollment::factory()->create();

        $response = $this->getJson("/api/enrollments/{$enrollment->id}");

        $response->assertStatus(200)
                 ->assertJson(['id' => $enrollment->id]);
    }

    #[Test]
    public function it_can_update_an_enrollment()
    {
        $role = Role::firstOrCreate(['name' => 'student']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $student = Student::factory()->create(['user_id' => $user->id]);
        $subject = Subject::factory()->create();
        $enrollment = Enrollment::factory()->create([
            'student_id' => $student->id,
            'subject_id' => $subject->id,
        ]);

        $response = $this->putJson("/api/enrollments/{$enrollment->id}", [
            'student_id' => $student->id,
            'subject_id' => $subject->id,
        ]);

        $response->assertStatus(200)
                 ->assertJson(['success' => true, 'message' => 'Matrícula atualizada com sucesso']);
    }

    #[Test]
    public function it_can_delete_an_enrollment()
    {
        $enrollment = Enrollment::factory()->create();

        $response = $this->deleteJson("/api/enrollments/{$enrollment->id}");

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Matrícula excluída com sucesso']);
    }
}
