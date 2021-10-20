<?php

use Illuminate\Database\Seeder;
use App\Entities\Compras\CompraEstatus;
use Illuminate\Support\Str;

class EstatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $nombre = "Venta";
        if(is_null(CompraEstatus::where('clave', Str::slug($nombre))->first())) {
            $estatus = CompraEstatus::firstOrCreate([
                'orden' => 1,
                'clave' => Str::slug($nombre),
                'nombre' => $nombre,
            ]);
        }

        $nombre = "Factura";
        if(is_null(CompraEstatus::where('clave', Str::slug($nombre))->first())) {
            $estatus = CompraEstatus::firstOrCreate([
                'orden' => 2,
                'clave' => Str::slug($nombre),
                'nombre' => $nombre,
            ]);
        }

        $nombre = "Acuse y carta porte";
        if(is_null(CompraEstatus::where('clave', Str::slug($nombre))->first())) {
            $estatus = CompraEstatus::firstOrCreate([
                'orden' => 3,
                'clave' => Str::slug($nombre),
                'nombre' => $nombre,
            ]);
        }

        $nombre = "Validación";
        if(is_null(CompraEstatus::where('clave', Str::slug($nombre))->first())) {
            $estatus = CompraEstatus::firstOrCreate([
                'orden' => 4,
                'clave' => Str::slug($nombre),
                'nombre' => $nombre,
            ]);
        }

        $nombre = "Pago";
        if(is_null(CompraEstatus::where('clave', Str::slug($nombre))->first())) {
            $estatus = CompraEstatus::firstOrCreate([
                'orden' => 5,
                'clave' => Str::slug($nombre),
                'nombre' => $nombre,
            ]);
        }

        $nombre = "Complemento";
        if(is_null(CompraEstatus::where('clave', Str::slug($nombre))->first())) {
            $estatus = CompraEstatus::firstOrCreate([
                'orden' => 6,
                'clave' => Str::slug($nombre),
                'nombre' => $nombre,
            ]);
        }
    }
}
