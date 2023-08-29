<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TesteSalvamentoEvento extends TestCase
{
    /**
     * Testa se um evento é salvo corretamente no banco de dados.
     *
     * @return void
     */
    public function testaSalvamentoEventoNoBanco()
    {
        $dadosEvento = [
            'name' => 'Evento de Teste',
            'start_date' => '2023-09-01',
            'end_date' => '2023-09-05',
            'status' => true
        ];

        $evento = Event::create($dadosEvento);

        $this->assertDatabaseHas('events', $dadosEvento);
    }
}

