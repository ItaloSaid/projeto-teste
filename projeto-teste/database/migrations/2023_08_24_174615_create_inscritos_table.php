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
        Schema::create('inscritos', function (Blueprint $table) {
            $table->id(); // ID do inscrito
            $table->string('name'); // Nome
            $table->string('cpf')->unique(); // CPF (único)
            $table->string('email')->unique(); // Email (único)
            $table->timestamps(); // Timestamps (created_at e updated_at)
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inscritos');
    }
};
