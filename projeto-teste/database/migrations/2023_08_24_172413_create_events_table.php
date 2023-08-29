<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id(); // ID do evento
            $table->string('name'); // Nome do evento
            $table->dateTime('start_date'); // Data de início
            $table->dateTime('end_date'); // Data de fim
            $table->boolean('status')->default(true); // Status do evento (ativo/inativo)
            $table->timestamps(); // Timestamps (created_at e updated_at)
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
