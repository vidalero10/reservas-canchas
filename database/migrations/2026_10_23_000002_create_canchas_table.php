<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('canchas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deporte_id')->constrained()->restrictOnDelete();
            $table->string('nombre')->unique();
            $table->decimal('tarifa_diurna', 8, 2);
            $table->decimal('tarifa_nocturna', 8, 2);
            $table->boolean('activa')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('canchas'); }
};
