<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class EstadosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('catalogo_colonias')->truncate();
        DB::table('catalogo_municipios')->truncate();
        DB::table('catalogo_estados')->truncate();
        Schema::enableForeignKeyConstraints();
        $sql = file_get_contents(database_path() . '/seeders/sql/estados.sql');
        DB::statement($sql);

        $sql = file_get_contents(database_path() . '/seeders/sql/municipios.sql');
        DB::statement($sql);

        for ($i = 1; $i<=20; $i++) {
            $sql = file_get_contents(database_path() . '/seeders/sql/colonias_'.$i.'.sql');
            DB::statement($sql);
        }
    }
}
