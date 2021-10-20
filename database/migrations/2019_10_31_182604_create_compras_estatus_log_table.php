<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComprasEstatusLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compras_estatus_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('compra_id');
            $table->unsignedBigInteger('estatus_id');
            $table->string('estatus')->default('pendiente'); // pendiente - rechazado - aceptado
            $table->text('comentarios')->nullable();

            $table->foreign('compra_id')
                ->references('id')
                ->on('compras')
                ->onDelete('cascade');
            $table->foreign('estatus_id')
                ->references('id')
                ->on('compras_estatus')
                ->onDelete('cascade');

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
        Schema::dropIfExists('compras_estatus_log');
    }
}
