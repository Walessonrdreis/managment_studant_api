<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Attendance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Role;
use App\Models\User;
use App\Models\Student;

class AttendanceControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_record_attendance()
    {
        // Crie um papel específico para o estudante
        $role = Role::firstOrCreate(['name' => 'student']);

        // Crie um usuário e atribua o papel de estudante
        $user = User::factory()->create(['role_id' => $role->id]);

        // Crie um estudante associado ao usuário
        $student = Student::factory()->create(['user_id' => $user->id]);

        // Dados para a requisição
        $response = $this->postJson('/api/attendances', [
            'student_id' => $student->id, // Use o ID do estudante criado
            'date' => '2023-01-01',
            'status' => 'present',
        ]);

        $response->assertStatus(201)
                 ->assertJson(['success' => true, 'message' => 'Presença registrada com sucesso']);
    }

    /** @test */
    public function it_can_list_attendances()
    {
        Attendance::factory()->count(3)->create();

        $response = $this->getJson('/api/attendances');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    /** @test */
    public function it_can_show_an_attendance()
    {
        $attendance = Attendance::factory()->create();

        $response = $this->getJson("/api/attendances/{$attendance->id}");

        $response->assertStatus(200)
                 ->assertJson(['id' => $attendance->id]);
    }

    /** @test */
    public function it_can_update_an_attendance()
    {
        $attendance = Attendance::factory()->create();

        $response = $this->putJson("/api/attendances/{$attendance->id}", [
            'status' => 'absent',
        ]);

        $response->assertStatus(200)
                 ->assertJson(['success' => true, 'message' => 'Presença atualizada com sucesso']);
    }

    /** @test */
    public function it_can_delete_an_attendance()
    {
        $attendance = Attendance::factory()->create();

        $response = $this->deleteJson("/api/attendances/{$attendance->id}");

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Presença excluída com sucesso']);
    }
}
