<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historial_ejecuciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tarea_id')->constrained('tareas_programadas')->onDelete('cascade');
            $table->enum('estado', ['completada', 'fallida', 'en_proceso']);
            $table->text('mensaje')->nullable();
            $table->timestamp('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historial_ejecuciones');
    }
};
