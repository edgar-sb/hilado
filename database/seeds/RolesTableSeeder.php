<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('model_has_permissions')->truncate();
        DB::table('permissions')->truncate();
        Schema::enableForeignKeyConstraints();

        //Roles
        $administador = Role::firstOrCreate(['name' => 'administrador']);
        $logistica    = Role::firstOrCreate(['name' => 'logistica']);
        $contabilidad = Role::firstOrCreate(['name' => 'contabilidad']);
        $proveedor    = Role::firstOrCreate(['name' => 'proveedor']);

        //Permisos
        Permission::firstOrCreate(['name' => 'ver compras']);
        Permission::firstOrCreate(['name' => 'agregar factura']);
        Permission::firstOrCreate(['name' => 'agregar acuse']);
        Permission::firstOrCreate(['name' => 'validar acuse']);
        Permission::firstOrCreate(['name' => 'agregar comprobante']);
        Permission::firstOrCreate(['name' => 'agregar complemento']);

        Permission::firstOrCreate(['name' => 'ver reporte logistica']);
        Permission::firstOrCreate(['name' => 'exportar reporte logistica']);

        Permission::firstOrCreate(['name' => 'ver reporte finanzas']);
        Permission::firstOrCreate(['name' => 'exportar reporte finanzas']);

        Permission::firstOrCreate(['name' => 'ver usuarios logistica']);
        Permission::firstOrCreate(['name' => 'gestionar usuarios logistica']);
        Permission::firstOrCreate(['name' => 'ver usuarios contabilidad']);
        Permission::firstOrCreate(['name' => 'gestionar usuarios contabilidad']);
        Permission::firstOrCreate(['name' => 'ver usuarios administrador']);
        Permission::firstOrCreate(['name' => 'gestionar usuarios administrador']);
        Permission::firstOrCreate(['name' => 'ver usuarios proveedores']);
        Permission::firstOrCreate(['name' => 'gestionar usuarios proveedores']);

        Permission::firstOrCreate(['name' => 'sincronizar odc']);
        Permission::firstOrCreate(['name' => 'agregar compra']);


        //Asignación de permisos
        $administador->syncPermissions(Permission::pluck('name'));
        $logistica->syncPermissions([
            'ver compras',
            'sincronizar odc',
            'agregar compra',
            'validar acuse',
            'ver reporte logistica',
            'exportar reporte logistica',
        ]);
        $contabilidad->syncPermissions([
            'ver compras',
            'agregar comprobante',
            'ver reporte finanzas',
            'exportar reporte finanzas',
        ]);
        $proveedor->syncPermissions([
            'ver compras',
            'agregar factura',
            'agregar acuse',
            'agregar complemento',
        ]);
    }
}
