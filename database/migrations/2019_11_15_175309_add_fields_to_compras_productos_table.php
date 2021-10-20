<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToComprasProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('compras_productos', function (Blueprint $table) {
            $table->integer('importe')->after('precio');
            $table->integer('impuesto')->after('precio');
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
            $table->dropColumn(['importe', 'impuesto']);
        });
    }
}
