<?php

use Illuminate\Database\Seeder;
use App\Entities\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(is_null(User::where('email', 'admin@hilados.com')->first())) {
            $user = User::firstOrCreate([
                'nombre' => 'Administrador',
                'email' => 'admin@hilados.com',
                'password' => bcrypt('secret'),
            ]);
            $user->assignRole('administrador');
        }

        if(is_null(User::where('email', 'logistica@hilados.com')->first())) {
            $user = User::firstOrCreate([
                'nombre' => 'Logística',
                'email' => 'logistica@hilados.com',
                'password' => bcrypt('secret'),
            ]);
            $user->assignRole('logistica');
        }

        if(is_null(User::where('email', 'contabilidad@hilados.com')->first())) {
            $user = User::firstOrCreate([
                'nombre' => 'Contabilidad',
                'email' => 'contabilidad@hilados.com',
                'password' => bcrypt('secret'),
            ]);
            $user->assignRole('contabilidad');
        }

        if(is_null(User::where('email', 'proveedor@hilados.com')->first())) {
            $user = User::firstOrCreate([
                'nombre' => 'Proveedor',
                'email' => 'proveedor@hilados.com',
                'password' => bcrypt('secret'),
            ]);
            $user->assignRole('proveedor');
        }
    }
}
