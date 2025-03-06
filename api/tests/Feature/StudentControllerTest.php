<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class StudentControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_student()
    {
        $user = User::factory()->create();

        $studentData = [
            'name' => 'Nome do Estudante',
            'email' => 'estudante@example.com',
            'user_id' => $user->id,
            'date_of_birth' => '2000-01-01',
        ];

        $response = $this->postJson('/api/students', $studentData);

        $response->assertStatus(201)
                 ->assertJson(['success' => true, 'message' => 'Estudante criado com sucesso']);
    }
    
    // ... outros métodos de teste ...

    /** @test */
    public function it_can_list_students()
    {
        Student::factory()->count(3)->create();

        $response = $this->getJson('/api/students');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    /** @test */
    public function it_can_show_a_student()
    {
        $student = Student::factory()->create();

        $response = $this->getJson("/api/students/{$student->id}");

        $response->assertStatus(200)
                 ->assertJson(['id' => $student->id]);
    }

    /** @test */
    public function it_can_update_a_student()
    {
        $student = Student::factory()->create();

        $response = $this->putJson("/api/students/{$student->id}", [
            'name' => 'Updated Student',
            'email' => 'updated_student@example.com',
        ]);

        $response->assertStatus(200)
                 ->assertJson(['success' => true, 'message' => 'Estudante atualizado com sucesso']);
    }

    /** @test */
    public function it_can_delete_a_student()
    {
        $student = Student::factory()->create();

        $response = $this->deleteJson("/api/students/{$student->id}");

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Estudante excluído com sucesso']);
    }
}
