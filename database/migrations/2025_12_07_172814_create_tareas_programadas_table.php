<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tareas_programadas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('comando');
            $table->enum('frecuencia', ['diario', 'semanal', 'mensual']);
            $table->time('hora');
            $table->enum('prioridad', ['alta', 'media', 'baja']);
            $table->enum('estado', ['activa', 'pausada', 'completada'])->default('activa');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tareas_programadas');
    }
};
