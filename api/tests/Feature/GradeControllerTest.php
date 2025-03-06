<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Grade;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Role;
use App\Models\User;
use App\Models\Student;
use App\Models\Subject;
use PHPUnit\Framework\Attributes\Test;

class GradeControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_create_a_grade()
    {
        $role = Role::firstOrCreate(['name' => 'student']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $student = Student::factory()->create(['user_id' => $user->id]);
        $subject = Subject::factory()->create();

        $data = [
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'grade' => 90,
        ];

        $response = $this->postJson('/api/grades', $data);

        $response->assertStatus(201)
                 ->assertJson(['success' => true, 'message' => 'Nota criada com sucesso']);
    }

    #[Test]
    public function it_can_list_grades()
    {
        Grade::factory()->count(3)->create();

        $response = $this->getJson('/api/grades');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    #[Test]
    public function it_can_show_a_grade()
    {
        $grade = Grade::factory()->create();

        $response = $this->getJson("/api/grades/{$grade->id}");

        $response->assertStatus(200)
                 ->assertJson(['id' => $grade->id]);
    }

    #[Test]
    public function it_can_update_a_grade()
    {
        $grade = Grade::factory()->create();

        $response = $this->putJson("/api/grades/{$grade->id}", [
            'grade' => 95,
        ]);

        $response->assertStatus(200)
                 ->assertJson(['success' => true, 'message' => 'Nota atualizada com sucesso']);
    }

    #[Test]
    public function it_can_delete_a_grade()
    {
        $grade = Grade::factory()->create();

        $response = $this->deleteJson("/api/grades/{$grade->id}");

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Nota excluída com sucesso']);
    }
}
