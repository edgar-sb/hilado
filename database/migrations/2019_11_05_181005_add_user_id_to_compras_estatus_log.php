<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserIdToComprasEstatusLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('compras_estatus_log', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->after('estatus_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('compras_estatus_log', function (Blueprint $table) {
            $table->dropForeign('compras_estatus_log_user_id_foreign');
            $table->dropColumn('user_id');
        });
    }
}
