<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscrito extends Model
{
    protected $fillable = ['name', 'cpf', 'email'];

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_inscrito', 'inscrito_id', 'event_id');
    }


    public function temConflitoDeHorario($startDate, $endDate)
    {
        $eventos = $this->events()->get();

        foreach ($eventos as $evento) {
            if ($startDate < $evento->end_date && $evento->start_date < $endDate) {
                return "Você já tem um evento estabelecido para essa data: $evento->name $evento->start_date até $evento->end_date";
            }
        }
        return false;
    }


    public function inscreverEmEvento($eventId)
    {
        $evento = Event::find($eventId);
        if(!$evento) {
            return "O evento não foi encontrado.";
        }
        if(!$evento->status) {
            return "O evento está inativo e, portanto, não é possível realizar a inscrição.";
        }

        $resultado = $this->temConflitoDeHorario($evento->start_date, $evento->end_date);
        if ($resultado === false) {
            $this->events()->attach($eventId);
            return "Inscrição realizada com sucesso!";
        } else {
            return $resultado;
        }
    }


    protected static function newFactory()
    {
        return \Database\Factories\InscritoFactory::new();
    }

}
