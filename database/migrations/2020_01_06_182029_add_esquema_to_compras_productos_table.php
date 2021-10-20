<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEsquemaToComprasProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('compras_productos', function (Blueprint $table) {
            $table->integer('ret_iva')->nullable()->after('importe');
            $table->integer('iva')->nullable()->after('importe');
            $table->string('esquema')->nullable()->after('importe');
            $table->string('observaciones')->nullable()->after('importe');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('compras_productos', function (Blueprint $table) {
            $table->dropColumn(['observaciones', 'esquema', 'ret_iva', 'iva']);
        });
    }
}
