<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained()->restrictOnDelete();
            $table->foreignId('cancha_id')->constrained()->restrictOnDelete();
            $table->date('fecha');
            $table->time('hora_inicio');
            $table->unsignedTinyInteger('duracion_horas');
            $table->time('hora_fin');
            $table->decimal('monto', 8, 2);
            $table->string('estado', 15)->default('confirmada');
            $table->timestamp('cancelada_en')->nullable();
            $table->timestamps();
            $table->index(['cancha_id', 'fecha']);
        });
    }
    public function down(): void { Schema::dropIfExists('reservas'); }
};
