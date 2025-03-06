<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Enrollment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EnrollmentControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_an_enrollment()
    {
        $response = $this->postJson('/api/enrollments', [
            'student_id' => 1,
            'subject_id' => 1,
        ]);

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
        $enrollment = Enrollment::factory()->create();

        $response = $this->putJson("/api/enrollments/{$enrollment->id}", [
            'student_id' => 1,
            'subject_id' => 2,
        ]);

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
