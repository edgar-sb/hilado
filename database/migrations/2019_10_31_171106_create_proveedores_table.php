<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProveedoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proveedores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->boolean('bloqueado')->default(false);
            $table->string('razon_social');
            $table->string('rfc')->unique();
            $table->string('nombre')->nullable();
            $table->string('comentarios')->nullable();

            $table->string('calle')->nullable();
            $table->unsignedBigInteger('colonia_id')->nullable();
            $table->unsignedBigInteger('municipio_id')->nullable();
            $table->unsignedBigInteger('estado_id')->nullable();

            $table->string('contacto_nombre')->nullable();
            $table->string('contacto_puesto')->nullable();
            $table->string('telefono')->nullable();


            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('colonia_id')
                ->references('id')
                ->on('catalogo_colonias')
                ->onDelete('set null');

            $table->foreign('municipio_id')
                ->references('id')
                ->on('catalogo_municipios')
                ->onDelete('set null');

            $table->foreign('estado_id')
                ->references('id')
                ->on('catalogo_estados')
                ->onDelete('set null');

            $table->index('rfc');

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
        Schema::dropIfExists('proveedores');
    }
}
