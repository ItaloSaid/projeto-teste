<?php

namespace Tests\Unit;

use App\Models\Event;
use App\Models\Inscrito;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Validation\ValidationException;

class TesteInscricaoEvento extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function inscrito_pode_ser_inscrito_em_evento()
    {
        $evento = Event::create([
            'name' => 'Conferência de Tecnologia 2023',
            'start_date' => now()->addDays(10),
            'end_date' => now()->addDays(13),
            'status' => 'true'
        ]);


        $inscrito = Inscrito::create([
            'name' => 'John Doe',
            'cpf' => '12345678910',
            'email' => 'john@emaill.com'
        ]);

        $response = $this->postJson(route('evento.inscrever', ['evento_id' => $evento->id, 'inscrito_id' => $inscrito->id]));

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Inscrito associado ao evento com sucesso!']);

        $this->assertDatabaseHas('event_inscrito', [
            'event_id' => $evento->id,
            'inscrito_id' => $inscrito->id
        ]);
    }

    /** @test */
    public function inscrito_nao_pode_ser_inscrito_em_evento_inativo()
    {
        $evento = Event::create([
            'name' => 'Conferência Cancelada 2023',
            'start_date' => now()->addDays(10),
            'end_date' => now()->addDays(13),
            'status' => false
        ]);

        $inscrito = Inscrito::create([
            'name' => 'Jane Doe',
            'cpf' => '12345678911',
            'email' => 'jane@emaill.com'
        ]);

        $response = $this->postJson(route('evento.inscrever', ['evento_id' => $evento->id, 'inscrito_id' => $inscrito->id]));

        $response->assertStatus(400);
        $response->assertJson(['message' => 'Não é possível inscrever-se em um evento inativo!']);
    }

    /** @test */
    public function inscrito_nao_pode_se_increver_em_eventos_com_conflito_de_horario()
    {
        $evento1 = Event::create([
            'name' => 'Conferência 2023 - Parte 1',
            'start_date' => now()->addDays(10),
            'end_date' => now()->addDays(13),
            'status' => true
        ]);

        $evento2 = Event::create([
            'name' => 'Conferência 2023 - Parte 2',
            'start_date' => now()->addDays(12),
            'end_date' => now()->addDays(15),
            'status' => true
        ]);

        $inscrito = Inscrito::create([
            'name' => 'John Doe',
            'cpf' => '98765432199',
            'email' => 'john@emaill.com'
        ]);

        $this->postJson(route('evento.inscrever', ['evento_id' => $evento1->id, 'inscrito_id' => $inscrito->id]));

        $response = $this->postJson(route('evento.inscrever', ['evento_id' => $evento2->id, 'inscrito_id' => $inscrito->id]));

        $response->assertStatus(400);
        $response->assertJson(['message' => 'Inscrito já está associado a outro evento no mesmo período!']);
    }
}
