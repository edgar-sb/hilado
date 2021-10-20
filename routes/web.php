<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Notifications\Proveedores\Pendientes as NorificacionPendientes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::prefix('site')->group(function() {
    Auth::routes();
    Route::get('/', 'Auth\LoginController@showLoginForm');
    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
    Route::middleware(['auth', 'unblocked'])->prefix('dashboard')->namespace('Dashboard')->group(function () {
        /**
         * Load
         */
        Route::get('proveedor/load/ubicacion', 'ProveedorController@loadUbicacion')->name('proveedor.load.ubicacion');
        Route::post('compras/load', 'CompraController@loadCompras')->name('compras.load');

        Route::middleware('permission:ver compras')->group(function () {
            Route::get('/', 'CompraController@index')->name('inicio');
            Route::get('compras/datatable', 'DatatableController@getCompras')
                ->name('compras.datatable');
            Route::get('compras/sincronizar', 'CompraController@sincronizar')
                ->name('compras.sincroinzar')->middleware('permission:sincronizar odc');
            Route::post('compras/{compra}/upload', 'CompraController@upload')
                ->name('compras.upload');
            Route::post('compras/upload/multiple', 'CompraController@uploadMultiple')
                ->name('compras.upload.multiple');
            Route::get('compras/{compra_estatus_log}/download', 'CompraController@download')
                ->name('compras.download');
            Route::put('compras/update/multiple', 'CompraController@updateMultiple')
                ->name('compras.update.multiple');
            Route::put('compras/{compra}/update/venta', 'CompraController@updateVenta')
                ->name('compras.update.venta');
            Route::get('compras/{compra}/desbloquear', 'CompraController@desbloquear')
                ->name('compras.desbloquear');
            Route::resource('compras', 'CompraController');
        });


        Route::middleware('permission:ver reporte logistica')->group(function () {
            Route::get('logistica/datatable', 'DatatableController@getLogistica')
                ->name('logistica.datatable');
            Route::get('/logistica','LogisticaController@index')->name('logistica');
            Route::post('/logistica/filtrar','LogisticaController@index')->name('logistica.filtro');
            Route::get('/logistica/export', 'LogisticaController@downloadLogistica')->name('logistica.reporte.descargar');
        });

        Route::middleware('permission:ver reporte finanzas')->group(function () {
            Route::get('finanzas/datatable', 'DatatableController@getFinanzas')
                ->name('finanzas.datatable');
            Route::get('/finanzas','FinanzaController@index')->name('finanzas');
            Route::post('/finanzas/filtrar','FinanzaController@index')->name('finanzas.filtro');
            Route::get('/finanzas/export', 'FinanzaController@downloadFinanzas')->name('finanzas.reporte.descargar');
        });

        Route::prefix('usuarios')->name('usuarios.')->group(function () {

            Route::resource('proveedores', 'ProveedorController')
                ->parameter('proveedores', 'proveedor');

            Route::get('/administrador', 'UserController@indexAdministrador')->name('administrador.index');
            Route::get('/administrador/create', 'UserController@createAdministrador')->name('administrador.create');
            Route::get('/administrador/edit/{user}', 'UserController@editAdministrador')->name('administrador.edit');

            Route::get('/contabilidad', 'UserController@indexContabilidad')->name('contabilidad.index');
            Route::get('/contabilidad/create', 'UserController@createContabilidad')->name('contabilidad.create');
            Route::get('/contabilidad/edit/{user}', 'UserController@editContabilidad')->name('contabilidad.edit');

            Route::get('/logistica', 'UserController@indexLogistica')->name('logistica.index');
            Route::get('/logistica/create', 'UserController@createLogistica')->name('logistica.create');
            Route::get('/logistica/edit/{user}', 'UserController@editLogistica')->name('logistica.edit');

            Route::post('/store', 'UserController@store')->name('store');
            Route::put('/update/{user}', 'UserController@update')->name('update');
            Route::delete('/destroy/{user}', 'UserController@destroy')->name('destroy');

        });
    });
});
