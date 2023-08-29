<?php

namespace App\Http\Controllers;

use App\Models\Inscrito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;



class InscritoController extends Controller
{
    public function index(Request $request)
    {
        $query = Inscrito::query();

        $nameFilter = $request->input('name');

        if ($nameFilter) {
            $query->where('name', 'like', '%' . $nameFilter . '%');
        }

        $inscritos = $query->paginate(10);

        return response()->json($inscritos, 200, [], JSON_UNESCAPED_UNICODE);
    }


    public function show($id)
    {
        $inscrito = Inscrito::find($id);
        if (!$inscrito) {
            return response()->json(['message' => 'Inscrito não encontrado'], 404);
        }
        return response()->json($inscrito, 200, [], JSON_UNESCAPED_UNICODE);
    }

    public function store(Request $request)
    {
        try {
            $messages = [
                'required' => 'O campo :attribute é obrigatório.',
                'cpf.unique' => 'O CPF informado já está cadastrado.',
                'email.unique' => 'O e-mail informado já está cadastrado.',
                'string' => 'O campo :attribute deve ser uma string.',
                'max' => 'O campo :attribute não deve ter mais que :max caracteres.',
                'size' => 'O campo :attribute deve ter exatamente :size caracteres.'
            ];

            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'cpf' => 'required|string|size:11|unique:inscritos,cpf',
                'email' => 'required|email|unique:inscritos,email'
            ], $messages);

            $inscrito = new Inscrito();
            $inscrito->name = $validatedData['name'];
            $inscrito->cpf = $validatedData['cpf'];
            $inscrito->email = $validatedData['email'];
            $inscrito->save();

            return response()->json(['message' => 'Inscrito criado com sucesso!', 'data' => $inscrito], 201, [], JSON_UNESCAPED_UNICODE);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Erro de validação', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro inesperado. Por favor, tente novamente.'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $inscrito = Inscrito::find($id);
        if (!$inscrito) {
            return response()->json(['message' => 'Inscrito não encontrado'], 404);
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'cpf' => ['required', 'string', 'size:11', Rule::unique('inscritos')->ignore($inscrito->id)],
            'email' => ['required', 'email', Rule::unique('inscritos')->ignore($inscrito->id)]
        ]);

        $inscrito->fill($validatedData);
        $inscrito->save();

        return response()->json($inscrito, 200, [], JSON_UNESCAPED_UNICODE);
    }

    public function destroy($id)
    {
        $inscrito = Inscrito::find($id);
        if (!$inscrito) {
            return response()->json(['message' => 'Inscrito não encontrado'], 404);
        }
        $inscrito->delete();
        return response()->json(['message' => 'Inscrito deletado com sucesso'], 200, [], JSON_UNESCAPED_UNICODE);
    }
}
