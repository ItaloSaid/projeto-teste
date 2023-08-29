<?php

namespace Tests\Unit;

use App\Models\Inscrito;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Validation\ValidationException;

class TesteListaInscritos extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     */
    /** @test */
    /** @test */
    /** @test */
    /** @test */
    public function pode_obter_lista_paginada_de_inscritos()
    {
        for ($i = 0; $i < 50; $i++) {
            Inscrito::create([
                'name' => "Inscrito $i",
                'cpf' => "123.456.789-$i",
                'email' => "inscrito$i@example.com",
            ]);
        }

        $response = $this->getJson(route('inscritos.index'));

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'name', 'cpf', 'email']
            ],
            'links' => [
                '*' => ['url', 'label', 'active']
            ],
            'current_page',
            'first_page_url',
            'from',
            'last_page',
            'last_page_url',
            'next_page_url',
            'path',
            'per_page',
            'prev_page_url',
            'to',
            'total'
        ]);

        $this->assertCount(10, $response->json('data'));
    }
    /** @test */
    public function pode_filtrar_inscritos_pelo_nome()
    {
        for ($i = 0; $i < 5; $i++) {
            Inscrito::create([
                'name' => 'Inscrito Especial',
                'cpf' => '1234567890' . $i,
                'email' => 'especial' . $i . '@email.com'
            ]);
        }

        for ($i = 5; $i < 10; $i++) {
            Inscrito::create([
                'name' => 'Inscrito Normal',
                'cpf' => '1234567890' . $i,
                'email' => 'normal' . $i . '@email.com'
            ]);
        }

        $response = $this->getJson(route('inscritos.index', ['name' => 'Inscrito Especial']));

        $response->assertStatus(200);

        $inscritos = $response->json('data');
        $this->assertCount(5, $inscritos);
        foreach ($inscritos as $inscrito) {
            $this->assertEquals('Inscrito Especial', $inscrito['name']);
        }
    }




}
