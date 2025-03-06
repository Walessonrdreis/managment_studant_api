<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubjectControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_subject()
    {
        $response = $this->postJson('/api/subjects', [
            'name' => 'Test Subject',
        ]);

        $response->assertStatus(201)
                 ->assertJson(['success' => true, 'message' => 'Disciplina criada com sucesso']);
    }

    /** @test */
    public function it_can_list_subjects()
    {
        Subject::factory()->count(3)->create();

        $response = $this->getJson('/api/subjects');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    /** @test */
    public function it_can_show_a_subject()
    {
        $subject = Subject::factory()->create();

        $response = $this->getJson("/api/subjects/{$subject->id}");

        $response->assertStatus(200)
                 ->assertJson(['id' => $subject->id]);
    }

    /** @test */
    public function it_can_update_a_subject()
    {
        $subject = Subject::factory()->create();

        $response = $this->putJson("/api/subjects/{$subject->id}", [
            'name' => 'Updated Subject',
        ]);

        $response->assertStatus(200)
                 ->assertJson(['success' => true, 'message' => 'Disciplina atualizada com sucesso']);
    }

    /** @test */
    public function it_can_delete_a_subject()
    {
        $subject = Subject::factory()->create();

        $response = $this->deleteJson("/api/subjects/{$subject->id}");

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Disciplina excluída com sucesso']);
    }
}
