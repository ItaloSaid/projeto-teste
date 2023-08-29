<?php
namespace Tests\Unit;

use App\Models\Event;
use App\Models\Inscrito;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Validation\ValidationException;

class TesteCriacaoInscrito extends TestCase
{
    use RefreshDatabase;

    public function testCriacaoInscritoComCamposObrigatorios()
    {
        $dadosInscrito = [
            'name' => 'John Doe',
            'cpf' => '123.456.789-00',
            'email' => 'john@example.com'
        ];

        $inscrito = Inscrito::create($dadosInscrito);

        $this->assertDatabaseHas('inscritos', $dadosInscrito);

        $this->assertEquals($dadosInscrito['name'], $inscrito->name);
        $this->assertEquals($dadosInscrito['cpf'], $inscrito->cpf);
        $this->assertEquals($dadosInscrito['email'], $inscrito->email);
    }

    /** @test */
    /** @test */
    public function inscrito_nao_e_criado_sem_nome()
    {
        $response = $this->postJson(route('inscritos.store'), [
            'cpf' => '12345678901',
            'email' => 'teste@email.com',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
    }

    /** @test */
    public function inscrito_nao_e_criado_sem_cpf()
    {
        $response = $this->postJson(route('inscritos.store'), [
            'name' => 'Nome Teste',
            'email' => 'teste@email.com',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('cpf');
    }

    /** @test */
    public function inscrito_nao_e_criado_sem_email()
    {
        $response = $this->postJson(route('inscritos.store'), [
            'name' => 'Nome Teste',
            'cpf' => '12345678901',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email');
    }

}
