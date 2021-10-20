<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDiasVencerFacturaDiasVencerPagoToProveedoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('proveedores', function (Blueprint $table) {
            $table->integer('dias_vencer_pago')->default(7)->after('dias_credito');
            $table->integer('dias_vencer_factura')->default(7)->after('dias_credito');
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
            $table->dropColumn([
                'dias_vencer_pago',
                'dias_vencer_factura'
            ]);
        });
    }
}
