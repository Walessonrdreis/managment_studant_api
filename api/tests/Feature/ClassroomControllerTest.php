<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Classroom;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClassroomControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_classroom()
    {
        $response = $this->postJson('/api/classrooms', [
            'name' => 'Test Classroom',
        ]);

        $response->assertStatus(201)
                 ->assertJson(['success' => true, 'message' => 'Sala de aula criada com sucesso']);
    }

    /** @test */
    public function it_can_list_classrooms()
    {
        Classroom::factory()->count(3)->create();

        $response = $this->getJson('/api/classrooms');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    /** @test */
    public function it_can_show_a_classroom()
    {
        $classroom = Classroom::factory()->create();

        $response = $this->getJson("/api/classrooms/{$classroom->id}");

        $response->assertStatus(200)
                 ->assertJson(['id' => $classroom->id]);
    }

    /** @test */
    public function it_can_update_a_classroom()
    {
        $classroom = Classroom::factory()->create();

        $response = $this->putJson("/api/classrooms/{$classroom->id}", [
            'name' => 'Updated Classroom',
        ]);

        $response->assertStatus(200)
                 ->assertJson(['success' => true, 'message' => 'Sala de aula atualizada com sucesso']);
    }

    /** @test */
    public function it_can_delete_a_classroom()
    {
        $classroom = Classroom::factory()->create();

        $response = $this->deleteJson("/api/classrooms/{$classroom->id}");

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Sala de aula excluída com sucesso']);
    }
}
