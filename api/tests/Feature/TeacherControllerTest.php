<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Role;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;

class TeacherControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_create_a_teacher()
    {
        // Crie um papel específico para o professor
        $role = Role::firstOrCreate(['name' => 'teacher']);

        // Crie um usuário e atribua o papel de professor
        $user = User::factory()->create(['role_id' => $role->id]); // Atribua o ID do papel ao usuário

        // Autentique o usuário
        $this->actingAs($user);

        // Dados do professor
        $teacherData = [
            'name' => 'John Doe',
            'subject' => 'Mathematics',
            'user_id' => $user->id, // Use o ID do usuário criado
        ];

        // Faça a requisição para criar o professor
        $response = $this->postJson('/api/teachers', $teacherData);

        // Verifique se a resposta está correta
        $response->assertStatus(201)
                 ->assertJson(['success' => true, 'message' => 'Professor criado com sucesso']);
    }

    #[Test]
    public function it_can_list_teachers()
    {
        Teacher::factory()->count(3)->create();

        $response = $this->getJson('/api/teachers');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    #[Test]
    public function it_can_show_a_teacher()
    {
        $teacher = Teacher::factory()->create();

        $response = $this->getJson("/api/teachers/{$teacher->id}");

        $response->assertStatus(200)
                 ->assertJson(['id' => $teacher->id]);
    }

    #[Test]
    public function it_can_update_a_teacher()
    {
        // Crie um papel específico para o professor
        $role = Role::firstOrCreate(['name' => 'teacher']);

        // Crie um usuário e atribua o papel de professor
        $user = User::factory()->create(['role_id' => $role->id]);

        // Crie um professor associado ao usuário
        $teacher = Teacher::factory()->create(['user_id' => $user->id]);

        // Dados atualizados do professor
        $updatedData = [
            'name' => 'Updated Teacher',
            'subject' => 'Physics',
            'user_id' => $user->id, // Inclua o user_id
        ];

        // Faça a requisição para atualizar o professor
        $response = $this->putJson("/api/teachers/{$teacher->id}", $updatedData);

        // Verifique se a resposta está correta
        $response->assertStatus(200)
                 ->assertJson(['success' => true, 'message' => 'Professor atualizado com sucesso']);
    }

    #[Test]
    public function it_can_delete_a_teacher()
    {
        $teacher = Teacher::factory()->create();

        $response = $this->deleteJson("/api/teachers/{$teacher->id}");

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Professor excluído com sucesso']);
    }
}
