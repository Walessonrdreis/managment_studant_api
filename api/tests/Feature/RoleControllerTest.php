<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use PHPUnit\Framework\Attributes\Test;

class RoleControllerTest extends TestCase
{
    use DatabaseTransactions;

    #[Test]
    public function it_can_create_a_role()
    {
        $roleData = [
            'name' => 'Test Role',
        ];

        $response = $this->postJson('/api/roles', $roleData);

        $response->assertStatus(201)
                 ->assertJson(['success' => true, 'message' => 'Role criada com sucesso']);
    }

    #[Test]
    public function it_can_list_roles()
    {
        // Cria papéis adicionais se necessário, mas não remove os existentes
        Role::factory()->create(['name' => 'Test Role']); // Exemplo de criação de um papel adicional

        $response = $this->getJson('/api/roles');

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Admin'])
                 ->assertJsonFragment(['name' => 'Teacher'])
                 ->assertJsonFragment(['name' => 'Student'])
                 ->assertJsonFragment(['name' => 'Test Role']); // Verifica se o papel adicional está presente
    }

    #[Test]
    public function it_can_show_a_role()
    {
        $role = Role::factory()->create();

        $response = $this->getJson("/api/roles/{$role->id}");

        $response->assertStatus(200)
                 ->assertJson(['id' => $role->id]);
    }

    #[Test]
    public function it_can_update_a_role()
    {
        $role = Role::factory()->create();

        $response = $this->putJson("/api/roles/{$role->id}", [
            'name' => 'Updated Role',
        ]);

        $response->assertStatus(200)
                 ->assertJson(['success' => true, 'message' => 'Role atualizada com sucesso']);
    }

    #[Test]
    public function it_can_delete_a_role()
    {
        $role = Role::factory()->create();

        $response = $this->deleteJson("/api/roles/{$role->id}");

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Role excluída com sucesso']);
    }
}
