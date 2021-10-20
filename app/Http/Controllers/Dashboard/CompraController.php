<?php

namespace App\Http\Controllers\Dashboard;

use App\Entities\Compras\Compra;
use App\Entities\Compras\CompraAgrupacion;
use App\Entities\Compras\CompraEstatus;
use App\Entities\Compras\CompraEstatusLog;
use App\Entities\User;
use App\Http\Controllers\Dashboard\Traits\ArchivosZip;
use App\Http\Controllers\Dashboard\Traits\CambiaEstatus;
use App\Http\Controllers\Dashboard\Traits\SincronizaSAE;
use App\Http\Controllers\Dashboard\Traits\ValidaArchivos;
use App\Http\Requests\Dashboard\CompraRequest;
use App\Notifications\Contabilidad\ComplementoPago;
use App\Notifications\Contabilidad\ComplementoPagoMultiple;
use App\Notifications\Contabilidad\FacturaAprobada;
use App\Notifications\Contabilidad\FacturaAprobadaMultiple;
use App\Notifications\Proveedores\AcusePendiente;
use App\Notifications\Proveedores\AcusePendienteMultiple;
use App\Notifications\Proveedores\ComplementoPendiente;
use App\Notifications\Proveedores\ComplementoPendienteMultiple;
use App\Notifications\Proveedores\CompraFinalizada;
use App\Notifications\Proveedores\CompraFinalizadaMultiple;
use App\Notifications\Proveedores\FacturaPendiente;
use App\SAE\SAERepository;
use App\ViewModels\CompraViewModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\MediaLibrary\MediaStream;

class CompraController extends Controller
{
    use ValidaArchivos;
    use CambiaEstatus;
    use SincronizaSAE;
    use ArchivosZip;

    public $SAERepository;

    public function __construct(SAERepository $SAERepository)
    {
        $this->SAERepository = $SAERepository;
    }

    public function index(Request $request) {
        $viewModel = new CompraViewModel($request);
        return view('dashboard.compras.index', $viewModel);
    }

    public function create() {
        $ordenes_compras = $this->getODCNoVinculados();
        $select_ordenes_compras = $this->select_ordenes_compras($ordenes_compras);
        return view('dashboard.compras.create', compact('ordenes_compras', 'select_ordenes_compras'));
    }

    public function store(CompraRequest $request) {
        try {
            DB::beginTransaction();
            $this->guardaCompra($request);
            DB::commit();
            return response()->json(['success' => 'Se registró correctamente la compra y el proveedor'], 200);
        } catch (\Exception $ex) {
            DB::rollBack();
            Log::alert($ex);
            return response()->json(['error' => 'Ocurrió un error'], 200);
        }
    }

    public function show(Compra $compra, Request $request) {
        $this->limpiaArchivos($compra);
        $viewModel = new CompraViewModel($request, $compra);
        return view('dashboard.compras.show', $viewModel);
    }

    public function edit(Compra $compra) {
        $this->limpiaArchivos($compra);
        $viewModel = new CompraViewModel(null, $compra);
        return view('dashboard.compras.edit', $viewModel);
    }

    public function upload(Compra $compra, Request $request) {
        try {
            DB::beginTransaction();
            /*if ($compra->bloqueado) {
                return response()->json(
                    [
                        'error'=> 'Las evidencias de la factura han sido rechazadas.',
                        'msg' => 'Esta compra está bloqueada ya que se no se subieron los documentos en el tiempo correspondiente. Comunicate con nosotros para resolver este asunto.',
                    ],
                200);
            }*/
            $response = $this->uploadArchivos($compra, $request);
            DB::commit();
            return response()->json($response, 200);
        } catch (\Exception $ex) {
            DB::rollBack();
            Log::alert($ex);
            return response()->json(['error' => 'Ocurrió un error'], 200);
        }
    }

    public function uploadMultiple(Request $request) {
        try {
            DB::beginTransaction();
            /*$compras_bloqueadas = Compra::whereIn('id', $request->input('compras_ids'))
                ->where('bloqueado', true)
                ->get();
            if ($compras_bloqueadas->count() > 0) {
                return response()->json(
                    [
                        'error'=> 'Las evidencias de la factura han sido rechazadas.',
                        'msg' => 'Una o más de estas compras está bloqueada ya que se no se subieron los documentos en el tiempo correspondiente. Comunicate con nosotros para resolver este asunto.',
                    ],
                    200);
            }*/
            $response = $this->uploadArchivosMultiple($request);
            DB::commit();
            return response()->json($response, 200);
        } catch (\Exception $ex) {
            DB::rollBack();
            Log::alert($ex);
            return response()->json(['error' => 'Ocurrió un error'], 200);
        }
    }

    public function update(Compra $compra, CompraRequest $request) {
        $url_success = redirect()->route('compras.index');
        $url_error = redirect()->route('compras.edit', $compra);
        if($request->filled('path')) {
            $url_success = redirect()->route('logistica');
            $url_error = redirect()->route('logistica');
        }

       /* if ($compra->bloqueado) {
            return $url_error
                ->with('info',
                    'Esta compra está bloqueada ya que se no se subieron los documentos en el tiempo correspondiente. Comunicate con nosotros para resolver este asunto.');
        }*/

        try {
            DB::beginTransaction();
            DB::connection('firebird')->beginTransaction();
            $response = $this->updateCompra($compra, $request);
            if(is_null($response)) {
                $compra->bloqueado = false;
                $compra->update();
                DB::commit();
                DB::connection('firebird')->commit();
                return $url_success
                    ->with('success', 'Se actualizó correctamente la compra');
            } else {
                if(isset($response['success'])) {
                    $compra->bloqueado = false;
                    $compra->update();
                    DB::commit();
                    DB::connection('firebird')->commit();
                    return $url_success
                        ->with('success', $response['success']);
                } else {
                    DB::rollBack();
                    DB::connection('firebird')->rollBack();
                    return $url_error
                        ->with('warning', $response['error']);
                }
            }
        } catch (\Exception $ex) {
            DB::rollBack();
            DB::connection('firebird')->rollBack();
            Log::alert($ex);
            return $url_error
                ->with('error', 'Ocurrió un error');
        }
    }

    public function updateMultiple(CompraRequest $request) {
        $url_success = redirect()->route('compras.index');
        $url_error = redirect()->route('compras.index');
        if($request->filled('path')) {
            $url_success = redirect()->route('logistica');
            $url_error = redirect()->route('logistica');
        }

        /*$compras_bloqueadas = Compra::whereIn('id', $request->input('compras_ids'))
            ->where('bloqueado', true)
            ->get();
        if ($compras_bloqueadas->count() > 0) {
            return $url_error
                ->with('info',
                    'Una o más de estas compras está bloqueada ya que se no se subieron los documentos en el tiempo correspondiente. Comunicate con nosotros para resolver este asunto.');
        }*/

        try {
            DB::beginTransaction();
            DB::connection('firebird')->beginTransaction();
            $compras = Compra::whereIn('id', $request->input('compras_ids'))->get();
            $estatus = CompraEstatus::where('clave', $request->input('estatus'))->first();
            $agrupacion_ids = [];
            foreach($compras as $compra) {
                $agrupacion_ids[] = $compra->id;
                if(!is_null($compra->agrupacion())) {
                    $compra->agrupacion()->delete();
                }
                $response = $this->updateCompra($compra, $request, false);
                if (isset($response)) {
                    if (isset($response['error'])) {
                        DB::rollBack();
                        DB::connection('firebird')->rollBack();
                        return $url_error
                            ->with('warning', $response['error']);
                    }
                }
            }
            CompraAgrupacion::create([
                'compras' => json_encode($agrupacion_ids)
            ]);
            //Enviar mails para compras múltiples
            $compra = $compras->first();
            $estatus_log = $compra->estatusLog()->where('estatus_id', $estatus->id)->first();
            $proveedor = $compra->proveedor;
            $delay = 0;
            switch ($estatus->clave) {
                case 'factura':
                    if ($estatus_log->estatus == "aprobado") {
                        $delay++;
                        $proveedor->user->notify((new AcusePendienteMultiple($proveedor->user, $compras, $compra))->delay(now()->addMinutes($delay)));
                        $path = $this->createFacturaZip($estatus_log);
                        if (isset($path)) {
                            foreach (User::role('contabilidad')->get() as $contabilidad) {
                                $delay++;
                                $contabilidad->notify((new FacturaAprobadaMultiple($contabilidad, $compras, $compra, $estatus_log, $path))->delay(now()->addMinutes($delay)));;
                            }
                        } else {
                            DB::rollBack();
                            return $url_error
                                ->with('error', 'Ocurrió un error al enviar el email.');
                        }
                    }
                    break;
                case 'pago':
                    if ($estatus_log->estatus == "aprobado") {
                        $proveedor->user->notify((new ComplementoPendienteMultiple($proveedor->user, $compras, $compra))->delay(now()->addMinutes($delay)));
                    }
                    break;
                case 'complemento':
                    if ($estatus_log->estatus == "aprobado") {
                        $delay++;
                        $proveedor->user->notify((new CompraFinalizadaMultiple($proveedor->user, $compras, $compra))->delay(now()->addMinutes($delay)));;
                        $path = $this->createComplementoZip($estatus_log);
                        if (isset($path)) {
                            foreach (User::role('contabilidad')->get() as $contabilidad) {
                                $delay++;
                                $contabilidad->notify((new ComplementoPagoMultiple($contabilidad, $compras, $compra, $estatus_log, $path))->delay(now()->addMinutes($delay)));;
                            }
                        } else {
                            $response = ['error' => 'No se pudo enviar el correo electrónico correctamente.'];
                        }
                    }
                    break;
            }
            DB::commit();
            DB::connection('firebird')->commit();
            return $url_success
                ->with('success', 'Se actualizaron correctamente las compras');
        } catch (\Exception $ex) {
            DB::rollBack();
            DB::connection('firebird')->rollBack();
            Log::alert($ex);
            return $url_error
                ->with('error', 'Ocurrió un error');
        }
    }

    public function updateVenta(Compra $compra, Request $request) {
        try {
            DB::beginTransaction();
            $compra = $this->syncManiobras($compra, $request);
            $compra = $this->syncEstadia($compra, $request);
            $compra->proveedor->user->notify((new FacturaPendiente($compra->proveedor->user, $compra))->delay(now()->addMinutes(1)));
            DB::commit();
            return redirect()->route('compras.index')->with('success', 'Se actualizó correctamente la compra');
        } catch (\Exception $ex) {
            DB::rollBack();
            Log::alert($ex);
            return redirect()->route('compras.index')->with('error', 'Ocurrió un error');
        }
    }


    public function sincronizar() {
        try {
            DB::beginTransaction();
            $response = $this->sincroniza();
            DB::commit();
            return redirect()->route('compras.index')
                ->with('success', '<strong>Resumen de sincronización:</strong> <br>Órdenes decubiertas: '. $response['total'].' <br>Órdenes sincronizadas: '.$response['sincronizadas'].' <br>Órdenes repetidas: '.$response['repetidas']);
        } catch (\Exception $ex) {
            DB::rollBack();
            Log::alert($ex);
            return redirect()->route('compras.index')
                ->with('error', 'Ocurrió un error');
        }
    }

    public function download(CompraEstatusLog $compra_estatus_log, Request $request) {
        $collection = $request->collection;

        $downloads = $compra_estatus_log->getMedia($collection);
        $name = $collection.'_'.Carbon::now()->format('Y_m_d_h_i_s').'.zip';

        return MediaStream::create($name)->addMedia($downloads);
    }

    public function loadCompras(Request $request) {
        $estatus = $request->input('estatus');
        $compras_ids = $request->input('compras_ids');
        $compras = Compra::whereHas('estatusActual', function (Builder $query) use ($estatus) {
            $query->where('clave', $estatus);
        })
            ->whereHas('estatusLog', function (Builder $query) use ($compras_ids) {
                $query->where('compras_estatus_log.estatus', 'pendiente');
            })
            ->whereIn('id', $compras_ids)
            ->get();
        $total = 0;
        foreach ($compras as $compra) {
            $this->limpiaArchivos($compra);
            $total = $total + $compra->importe;
        }
        return view('dashboard.compras.partials.compras-multiples.compras', compact('compras', 'estatus', 'total', 'compras_ids'));
    }

    public function destroy(Compra $compra) {
        try {
            DB::beginTransaction();
            $compra->eliminar();
            DB::commit();
            return redirect()->route('compras.index')->with('success', 'Se eliminó correctamente la compra. Por favor eliminala también manualmente dentro del SAE.');
        } catch (\Exception $ex) {
            DB::rollBack();
            Log::alert($ex);
            return redirect()->route('compras.index')
                ->with('error', 'Ocurrió un error');
        }
    }

    public function desbloquear(Compra $compra) {
        try {
            DB::beginTransaction();
            $compra->bloqueado = false;
            $compra->update();
            DB::commit();
            return redirect()->back()
                ->with('success', 'Se desbloqueó correctamente la compra.');
        } catch (\Exception $ex) {
            DB::rollBack();
            Log::alert($ex);
            return redirect()->back()
                ->with('error', 'Ocurrió un error');
        }

    }
}
