<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Appointment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AppointmentControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_an_appointment()
    {
        $response = $this->postJson('/api/appointments', [
            'student_id' => 1,
            'teacher_id' => 1,
            'date' => '2023-01-01',
        ]);

        $response->assertStatus(201)
                 ->assertJson(['success' => true, 'message' => 'Agendamento criado com sucesso']);
    }

    /** @test */
    public function it_can_list_appointments()
    {
        Appointment::factory()->count(3)->create();

        $response = $this->getJson('/api/appointments');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    /** @test */
    public function it_can_show_an_appointment()
    {
        $appointment = Appointment::factory()->create();

        $response = $this->getJson("/api/appointments/{$appointment->id}");

        $response->assertStatus(200)
                 ->assertJson(['id' => $appointment->id]);
    }

    /** @test */
    public function it_can_update_an_appointment()
    {
        $appointment = Appointment::factory()->create();

        $response = $this->putJson("/api/appointments/{$appointment->id}", [
            'date' => '2023-01-02',
        ]);

        $response->assertStatus(200)
                 ->assertJson(['success' => true, 'message' => 'Agendamento atualizado com sucesso']);
    }

    /** @test */
    public function it_can_delete_an_appointment()
    {
        $appointment = Appointment::factory()->create();

        $response = $this->deleteJson("/api/appointments/{$appointment->id}");

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Agendamento excluído com sucesso']);
    }
}
