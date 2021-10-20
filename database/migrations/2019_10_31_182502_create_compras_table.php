<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComprasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compras', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('proveedor_id');
            $table->unsignedBigInteger('estatus_id');
            $table->string('no_pedido')->unique();
            $table->date('fecha_envio');
            $table->integer('monto');
            $table->text('direccion');
            $table->string('tarifa')->nullable();
            $table->string('maniobras')->nullable();
            $table->string('estadia')->nullable();


            $table->foreign('proveedor_id')
                ->references('id')
                ->on('proveedores')
                ->onDelete('cascade');
            $table->foreign('estatus_id')
                ->references('id')
                ->on('compras_estatus')
                ->onDelete('cascade');

            $table->index('no_pedido');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('compras');
    }
}
