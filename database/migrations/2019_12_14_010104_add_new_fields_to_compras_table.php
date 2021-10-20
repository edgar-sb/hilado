<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsToComprasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('compras', function (Blueprint $table) {
            $table->string('pedido_sae')->nullable()->after('no_pedido');
            $table->string('almacen')->nullable()->after('direccion');
            $table->date('fecha_flete')->nullable()->after('fecha_envio');
            $table->text('factura')->nullable()->after('estadia');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('compras', function (Blueprint $table) {
            $table->dropColumn(['pedido_sae', 'almacen', 'fecha_flete', 'factura']);
        });
    }
}
