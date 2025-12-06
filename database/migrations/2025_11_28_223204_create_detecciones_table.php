<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detecciones', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_archivo');
            $table->string('categoria'); 
            $table->integer('cantidad')->default(1);
            $table->date('fecha_deteccion');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detecciones');
    }
};
