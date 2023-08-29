<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Inscrito;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $events = Event::paginate(10);

        return response()->json([
            'data' => $events->items(),
            'current_page' => $events->currentPage(),
            'total_pages' => $events->lastPage(),
            'total_items' => $events->total(),
        ], 200, [], JSON_UNESCAPED_UNICODE);

    }


    public function show($id)
    {
        $event = Event::find($id);
        if (!$event) {
            return response()->json(['message' => 'Evento não encontrado'], 404);
        }
        return response()->json($event, 200, [], JSON_UNESCAPED_UNICODE);
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|boolean'
        ]);

        $event = Event::create($validatedData);
        return response()->json($event, 201, [], JSON_UNESCAPED_UNICODE);

    }

    public function update(Request $request, $id)
    {
        $event = Event::find($id);
        if (!$event) {
            return response()->json(['message' => 'Evento não encontrado'], 404);
        }

        $validatedData = $request->validate([
            'name' => 'string|max:255',
            'start_date' => 'date|before_or_equal:end_date',
            'end_date' => 'date|after_or_equal:start_date',
            'status' => 'boolean'
        ]);

        $event->update($validatedData);
        return response()->json($event, 200, [], JSON_UNESCAPED_UNICODE);

    }

    public function destroy($id)
    {
        $event = Event::find($id);
        if (!$event) {
            return response()->json(['message' => 'Evento não encontrado'], 404);
        }
        $event->delete();
        return response()->json(['message' => 'Evento deletado com sucesso']);
    }

    public function inscreverInscrito($evento_id, $inscrito_id) {
        $evento = Event::findOrFail($evento_id);
        $inscrito = Inscrito::findOrFail($inscrito_id);

        if(!$evento->status) {
            return response()->json(['message' => 'Não é possível inscrever-se em um evento inativo!'], 400);
        }

        $eventosConflitantes = $inscrito->events()
            ->where('start_date', '<=', $evento->end_date)
            ->where('end_date', '>=', $evento->start_date)
            ->count();

        if($eventosConflitantes > 0) {
            return response()->json(['message' => 'Inscrito já está associado a outro evento no mesmo período!'], 400);
        }

        $evento->inscritos()->attach($inscrito_id);

        return response()->json(['message' => 'Inscrito associado ao evento com sucesso!']);
    }




    public function visualizarInscrito($evento_id, $inscrito_id) {
        $evento = Event::find($evento_id);
        $inscrito = Inscrito::find($inscrito_id);

        if (!$evento || !$inscrito) {
            return response()->json(['message' => 'Evento ou inscrito não encontrado'], 404);
        }

        if (!$evento->inscritos->contains($inscrito_id)) {
            return response()->json(['message' => 'O inscrito não está associado a este evento'], 404);
        }

        return response()->json($inscrito);
    }

    public function listarInscritos($evento_id, Request $request) {
        $evento = Event::find($evento_id);

        if (!$evento) {
            return response()->json(['message' => 'Evento não encontrado'], 404);
        }

        $inscritos = $evento->inscritos()->paginate(10);

        return response()->json([
            'data' => $inscritos->items(),
            'current_page' => $inscritos->currentPage(),
            'total_pages' => $inscritos->lastPage(),
            'total_items' => $inscritos->total(),
        ], 200);
    }

    public function listInscritosByEvent(Request $request, $evento_id)
    {
        $event = Event::findOrFail($evento_id);

        $query = $event->inscritos();

        if ($request->has('name')) {
            $name = $request->get('name');
            $query->where('name', 'like', '%' . $name . '%');
        }

        $inscritos = $query->paginate(10);

        return response()->json([
            'data' => $inscritos->items(),
            'current_page' => $inscritos->currentPage(),
            'total_pages' => $inscritos->lastPage(),
            'total_items' => $inscritos->total(),
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }


}

