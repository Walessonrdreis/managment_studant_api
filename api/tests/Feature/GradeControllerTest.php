<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Grade;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GradeControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_grade()
    {
        $response = $this->postJson('/api/grades', [
            'student_id' => 1,
            'subject_id' => 1,
            'grade' => 90,
        ]);

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
