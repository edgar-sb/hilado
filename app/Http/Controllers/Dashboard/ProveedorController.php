<?php

namespace App\Http\Controllers\Dashboard;

use App\Entities\Catalogos\Colonia;
use App\Entities\Catalogos\Municipio;
use App\Http\Controllers\Controller;
use App\Notifications\Proveedores\Accesos;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\ViewModels\ProveedorViewModel;
use App\Http\Requests\Dashboard\ProveedorRequest;
use App\Entities\User;
use App\Entities\Proveedores\Proveedor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ProveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $viewModel = new ProveedorViewModel();
        return view('dashboard.proveedores.index', $viewModel);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $viewModel = new ProveedorViewModel();
        return view('dashboard.proveedores.create', $viewModel);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProveedorRequest $request)
    {
        try {
            DB::beginTransaction();
            $password = Str::random(8);
            $user = User::create([
                'nombre'=> $request->nombre,
                'password' => bcrypt($password),
                'email' => $request->email,
            ]);
            $user->assignRole('proveedor');

            $proveedor = $user->proveedor()->create($request->except('bloqueado'));
            if ($request->filled('bloqueado')) {
                $proveedor->bloqueado = true;
            } else {
                $proveedor->bloqueado = false;
            }
            $proveedor->update();

            $user->notify(new Accesos($user, $password));


            DB::commit();
            return redirect()->route('usuarios.proveedores.index')->with('success', 'El proveedor se agregó correctamente');
        } catch (\Exception $ex) {
            DB::rollBack();
            Log::alert($ex);
            return redirect()->route('usuarios.proveedores.index')->with('error', 'Ocurrió un error');;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Proveedor $proveedor)
    {
        $viewModel = new ProveedorViewModel($proveedor);
        return view('dashboard.proveedores.edit', $viewModel);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProveedorRequest $request, Proveedor $proveedor)
    {
        try {
            DB::beginTransaction();
            $proveedor->user->fill($request->all());
            $proveedor->user->update();
            $proveedor->fill($request->except(['estado_id', 'municipio_id', 'colonia_id', 'bloqueado']));
            if ($request->filled('estado_id')) {
                $proveedor->estado_id = $request->input('estado_id');
            }
            if ($request->filled('municipio_id')) {
                $proveedor->municipio_id = $request->input('municipio_id');
            }
            if ($request->filled('colonia_id')) {
                $proveedor->colonia_id = $request->input('colonia_id');
            }
            if ($request->filled('bloqueado')) {
                $proveedor->bloqueado = true;
            } else {
                $proveedor->bloqueado = false;
            }
            $proveedor->update();

            DB::commit();
            return redirect()->route('usuarios.proveedores.index')
                ->with('success', 'El proveedor se actualizó correctamente');
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->route('usuarios.proveedores.index')
                ->with('error', 'Ocurrió un error');
        }
    }

    /**
     * @param Proveedor $proveedor
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Proveedor $proveedor)
    {
        try {
            DB::beginTransaction();
            foreach ($proveedor->compras as $compra) {
                $compra->eliminar();
            }
            $proveedor->rfc = $proveedor->rfc.'-DELETED-'.Carbon::now();
            $proveedor->update();
            $proveedor->user->email = $proveedor->user->email.'-DELETED-'.Carbon::now();
            $proveedor->user->update();

            $proveedor->user()->delete();
            $proveedor->delete();

            DB::commit();
            return redirect()->route('usuarios.proveedores.index')
                ->with('success', 'El proveedor se eliminó correctamente');
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->route('usuarios.proveedores.index')
                ->with('error', 'Ocurrió un error');
        }
    }


    public function loadUbicacion(Request $request) {
        if ($request->filled('estado_id')) {
            $select_municipios = Municipio::where('estado_id', $request->input('estado_id'))->pluck('nombre', 'id');
            return view('dashboard.proveedores.partials.select-municipios', compact('select_municipios'));
        }
        if ($request->filled('municipio_id')) {
            $select_colonias = Colonia::where('municipio_id', $request->input('municipio_id'))->pluck('nombre', 'id');
            return view('dashboard.proveedores.partials.select-colonias', compact('select_colonias'));
        }
    }
}
