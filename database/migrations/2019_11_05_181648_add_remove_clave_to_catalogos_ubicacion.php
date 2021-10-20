<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRemoveClaveToCatalogosUbicacion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('catalogo_colonias', function (Blueprint $table) {
            $table->dropColumn('clave');
        });
        Schema::table('catalogo_municipios', function (Blueprint $table) {
            $table->dropColumn('clave');
        });
        Schema::table('catalogo_estados', function (Blueprint $table) {
            $table->dropColumn('clave');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('catalogo_colonias', function (Blueprint $table) {
            $table->string('clave')->after('nombre');
        });
        Schema::table('catalogo_municipios', function (Blueprint $table) {
            $table->string('clave')->after('nombre');
        });
        Schema::table('catalogo_estados', function (Blueprint $table) {
            $table->string('clave')->after('nombre');
        });
    }
}
