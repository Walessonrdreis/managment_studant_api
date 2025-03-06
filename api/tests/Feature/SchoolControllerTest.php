<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\School;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class SchoolControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_create_a_school()
    {
        $response = $this->postJson('/api/schools', [
            'name' => 'Test School',
        ]);

        $response->assertStatus(201)
                 ->assertJson(['success' => true, 'message' => 'Escola cadastrada com sucesso']);
    }

    #[Test]
    public function it_can_list_schools()
    {
        School::factory()->count(3)->create();

        $response = $this->getJson('/api/schools');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    #[Test]
    public function it_can_show_a_school()
    {
        $school = School::factory()->create();

        $response = $this->getJson("/api/schools/{$school->id}");

        $response->assertStatus(200)
                 ->assertJson(['id' => $school->id]);
    }

    #[Test]
    public function it_can_update_a_school()
    {
        $school = School::factory()->create();

        $response = $this->putJson("/api/schools/{$school->id}", [
            'name' => 'Updated School',
        ]);

        $response->assertStatus(200)
                 ->assertJson(['success' => true, 'message' => 'Escola atualizada com sucesso']);
    }

    #[Test]
    public function it_can_delete_a_school()
    {
        $school = School::factory()->create();

        $response = $this->deleteJson("/api/schools/{$school->id}");

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Escola excluída com sucesso']);
    }
}
