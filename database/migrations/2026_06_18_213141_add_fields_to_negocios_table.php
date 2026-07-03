<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/YYYY_MM_DD_HHMMSS_add_fields_to_negocios_table.php
public function up()
{
    Schema::table('negocios', function (Blueprint $table) {
        // Agregar campos que faltan
        $table->string('nombre')->after('id');
        $table->string('direccion')->nullable()->after('nombre');
        $table->string('telefono')->nullable()->after('direccion');
        $table->string('email')->nullable()->after('telefono');
        $table->time('hora_apertura')->default('08:00:00')->after('email');
        $table->time('hora_cierre')->default('20:00:00')->after('hora_apertura');
        $table->integer('duracion_descanso_entre_citas')->default(0)->after('hora_cierre');
        $table->boolean('activo')->default(true)->after('duracion_descanso_entre_citas');
    });
}

public function down()
{
    Schema::table('negocios', function (Blueprint $table) {
        $table->dropColumn([
            'nombre', 'direccion', 'telefono', 'email', 'activo'
        ]);
    });
}
};
