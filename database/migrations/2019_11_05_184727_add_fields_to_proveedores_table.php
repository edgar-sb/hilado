<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToProveedoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('proveedores', function (Blueprint $table) {
            $table->dropColumn('telefono');
            $table->string('contacto_email')->after('contacto_puesto')->nullable();
            $table->string('contacto_telefono')->after('contacto_puesto')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('proveedores', function (Blueprint $table) {
            $table->string('telefono')->after('contacto_puesto')->nullable();
            $table->dropColumn('contacto_email');
            $table->dropColumn('contacto_telefono');
        });
    }
}
