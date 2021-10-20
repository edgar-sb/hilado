<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\UserRequest;
use App\Entities\User;
use App\Notifications\Accesos;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\ViewModels\UserViewModel;

class UserController extends Controller
{
    public function indexAdministrador(){
        $viewModel = new UserViewModel();
        return view('dashboard.usuarios.administradores.index', $viewModel);
    }

    public function createAdministrador(){
        $viewModel = new UserViewModel();
        return view('dashboard.usuarios.administradores.create', $viewModel);
    }

    public function indexContabilidad(){
        $viewModel = new UserViewModel();
        return view('dashboard.usuarios.contabilidad.index', $viewModel);
    }

    public function createContabilidad(){
        $viewModel = new UserViewModel();
        return view('dashboard.usuarios.contabilidad.create', $viewModel);
    }

    public function indexLogistica(){
        $viewModel = new UserViewModel();
        return view('dashboard.usuarios.logistica.index', $viewModel);
    }

    public function createLogistica(){
        $viewModel = new UserViewModel();
        return view('dashboard.usuarios.logistica.create', $viewModel);
    }

    public function store(UserRequest $request){
        try {
            DB::beginTransaction();
            $password = $request->rol == 'administrador' ? $request->password : Str::random(8);
            $user = User::create([
                'nombre' => $request->nombre,
                'email' => $request->email,
                'password' => bcrypt($password)
            ]);
            $user->assignRole($request->rol);

            $user->notify(new Accesos($user, $password));

            DB::commit();
            return redirect()->route('usuarios.'.$request->rol.'.index')->with('success', 'El usuario se agregó correctamente');

        } catch (\Exception $ex) {
            DB::rollBack();
            Log::alert($ex);
            return redirect()->route('usuarios.'.$request->rol.'.index')->with('error', 'Ocurrió un error');;
        }
    }

    public function editAdministrador(User $user)
    {
        $viewModel = new UserViewModel($user);
        return view('dashboard.usuarios.administradores.edit', $viewModel);
    }

    public function editContabilidad(User $user)
    {
        $viewModel = new UserViewModel($user);
        return view('dashboard.usuarios.contabilidad.edit', $viewModel);
    }

    public function editLogistica(User $user)
    {
        $viewModel = new UserViewModel($user);
        return view('dashboard.usuarios.logistica.edit', $viewModel);
    }

    public function update(UserRequest $request,User $user)
    {
        try {
            DB::beginTransaction();
            $user->fill([
                'name' => $request->nombre,
                'email' => $request->email
            ]);
            if($request->filled('password')){
                $user->password = bcrypt($request->password);
            }
            $user->save();
            DB::commit();
            return redirect()->route('usuarios.'.$request->rol.'.index')->with('success', 'El usuario se editó correctamente');

        } catch (\Exception $ex) {
            DB::rollBack();
            Log::alert($ex);
            return redirect()->route('usuarios.'.$request->rol.'.index')->with('error', 'Ocurrió un error');
        }
    }

    public function destroy(User $user)
    {
        $route = 'usuarios.'.$user->roles()->first()->name.'.index';
        try {
            DB::beginTransaction();
            if ($user->roles()->first()->name == "administrador") {
                if(User::role('administrador')->count() == 1) {
                    DB::rollBack();
                    return redirect()->route($route)->with('warning', 'Al menos debe haber un administrador en el sistema.');
                }
            }
            $user->email = $user->email.'-DELETED-'.Carbon::now();
            $user->update();
            $user->delete();
            DB::commit();
            return redirect()->route($route)->with('success', 'El usuario se eliminó correctamente');

        } catch (\Exception $ex) {
            DB::rollBack();
            Log::alert($ex);
            return redirect()->route($route)->with('error', 'Ocurrió un error');
        }
    }
}
