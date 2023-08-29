<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\InscritoController;
use Illuminate\Support\Facades\Route;

Route::resource('inscritos', InscritoController::class);
Route::resource('events', EventController::class);



Route::post('/evento/{evento_id}/inscrever/{inscrito_id}', [EventController::class, 'inscreverInscrito'])->name('evento.inscrever');
Route::get('/evento/{evento_id}/inscrever/{inscrito_id}', [EventController::class, 'visualizarInscrito']);
Route::get('/evento/{evento_id}/listar', [EventController::class, 'listarInscritos']);
Route::get('/evento/{evento_id}/inscritos', [EventController::class, 'listInscritosByEvent']);






