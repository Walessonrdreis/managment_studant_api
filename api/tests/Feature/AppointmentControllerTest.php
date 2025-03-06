<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Appointment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\Role;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;

class AppointmentControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_create_an_appointment()
    {
        // Crie um papel específico para o estudante
        $roleStudent = Role::firstOrCreate(['name' => 'student']);
        $roleTeacher = Role::firstOrCreate(['name' => 'teacher']);

        // Crie um usuário e atribua o papel de estudante
        $studentUser = User::factory()->create(['role_id' => $roleStudent->id]);
        $student = Student::factory()->create(['user_id' => $studentUser->id]);

        // Crie um usuário e atribua o papel de professor
        $teacherUser = User::factory()->create(['role_id' => $roleTeacher->id]);
        $teacher = Teacher::factory()->create(['user_id' => $teacherUser->id]);

        // Dados para a requisição
        $response = $this->postJson('/api/appointments', [
            'student_id' => $student->id, // Use o ID do estudante criado
            'teacher_id' => $teacher->id, // Use o ID do professor criado
            'date' => '2023-01-01',
            'time' => '10:00:00', // Adicione o campo de tempo
        ]);

        $response->assertStatus(201)
                 ->assertJson(['success' => true, 'message' => 'Agendamento criado com sucesso']);
    }

    #[Test]
    public function it_can_list_appointments()
    {
        Appointment::factory()->count(3)->create();

        $response = $this->getJson('/api/appointments');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    #[Test]
    public function it_can_show_an_appointment()
    {
        $appointment = Appointment::factory()->create();

        $response = $this->getJson("/api/appointments/{$appointment->id}");

        $response->assertStatus(200)
                 ->assertJson(['id' => $appointment->id]);
    }

    #[Test]
    public function it_can_update_an_appointment()
    {
        $appointment = Appointment::factory()->create();

        $response = $this->putJson("/api/appointments/{$appointment->id}", [
            'date' => '2023-01-02',
        ]);

        $response->assertStatus(200)
                 ->assertJson(['success' => true, 'message' => 'Agendamento atualizado com sucesso']);
    }

    #[Test]
    public function it_can_delete_an_appointment()
    {
        $appointment = Appointment::factory()->create();

        $response = $this->deleteJson("/api/appointments/{$appointment->id}");

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Agendamento excluído com sucesso']);
    }
}
