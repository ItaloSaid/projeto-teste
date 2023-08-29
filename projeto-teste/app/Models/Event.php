<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['name', 'start_date', 'end_date', 'status'];


    public function inscritos()
    {
        return $this->belongsToMany(Inscrito::class, 'event_inscrito', 'event_id', 'inscrito_id');
    }


}
