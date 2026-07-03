<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/YYYY_MM_DD_HHMMSS_add_fields_to_usuarios_table.php
public function up()
{
    Schema::table('usuarios', function (Blueprint $table) {
        // Agregar campos que faltan
        $table->string('name')->after('id');
        $table->string('email')->unique()->after('name');
        $table->string('password')->after('email');
        $table->string('telefono')->nullable()->after('password');
        $table->string('direccion')->nullable()->after('telefono');
        $table->enum('rol', ['admin', 'empleado', 'cliente'])->default('cliente')->after('direccion');
        $table->rememberToken()->after('rol');
    });
}

public function down()
{
    Schema::table('usuarios', function (Blueprint $table) {
        $table->dropColumn([
            'name', 'email', 'password',
            'telefono', 'direccion', 'rol', 'remember_token'
        ]);
    });
}
};
