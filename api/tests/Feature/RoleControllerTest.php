<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_role()
    {
        $response = $this->postJson('/api/roles', [
            'name' => 'Test Role',
        ]);

        $response->assertStatus(201)
                 ->assertJson(['success' => true, 'message' => 'Role criada com sucesso']);
    }

    /** @test */
    public function it_can_list_roles()
    {
        Role::factory()->count(3)->create();

        $response = $this->getJson('/api/roles');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    /** @test */
    public function it_can_show_a_role()
    {
        $role = Role::factory()->create();

        $response = $this->getJson("/api/roles/{$role->id}");

        $response->assertStatus(200)
                 ->assertJson(['id' => $role->id]);
    }

    /** @test */
    public function it_can_update_a_role()
    {
        $role = Role::factory()->create();

        $response = $this->putJson("/api/roles/{$role->id}", [
            'name' => 'Updated Role',
        ]);

        $response->assertStatus(200)
                 ->assertJson(['success' => true, 'message' => 'Role atualizada com sucesso']);
    }

    /** @test */
    public function it_can_delete_a_role()
    {
        $role = Role::factory()->create();

        $response = $this->deleteJson("/api/roles/{$role->id}");

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Role excluída com sucesso']);
    }
}
