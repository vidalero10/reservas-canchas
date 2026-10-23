<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        if (Schema::hasTable('clientes')) return;
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('ci', 15)->unique();
            $table->string('nombre');
            $table->string('telefono', 20)->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('clientes'); }
};
