<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('pagos_citas', function (Blueprint $table) {
        $table->id();

        $table->foreignId('cita_id')->constrained()->onDelete('cascade');

        $table->decimal('monto', 10, 2);
        $table->string('metodo')->nullable(); // efectivo, tarjeta, transferencia
        $table->string('estado')->default('pendiente'); 
        // pendiente | pagado | anulado

        $table->dateTime('fecha_pago')->nullable();
        $table->text('nota')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos_citas');
    }
};
