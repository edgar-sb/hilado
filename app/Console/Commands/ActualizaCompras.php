<?php

namespace App\Console\Commands;

use App\Entities\Compras\Compra;
use App\Entities\Compras\CompraEstatus;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ActualizaCompras extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'actualiza:compras';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualiza fecha y folio de factura en las compras
        que ya tengan cargada la factura';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Actualización de compras');
        try {
            DB::beginTransaction();
            $estatus_factura = CompraEstatus::where('clave', 'factura')->firstOrFail();
            $compras_con_factura = Compra::whereHas('estatusLog', function(Builder $query) use ($estatus_factura) {
                $query->where('estatus_id', $estatus_factura->id)
                    ->where('estatus', 'aprobado');
            })->get();

            foreach($compras_con_factura as $compra) {
                $estatus_log = $compra->estatusLog()
                    ->where('estatus_id', $estatus_factura->id)
                    ->where('estatus', 'aprobado')->firstorFail();

                $fecha_factura = null;
                $no_factura = null;
                foreach ($estatus_log->getMedia('factura-xml') as $factura_xml) {
                    $xml = simplexml_load_file($factura_xml->getPath());
                    $ns = $xml->getNamespaces(true);
                    $xml->registerXPathNamespace('c', $ns['cfdi']);
                    $xml->registerXPathNamespace('t', $ns['tfd']);
                    foreach ($xml->xpath('//c:Comprobante') as $cfdiComprobante){
                        if(isset($no_factura)) {
                            $no_factura = $no_factura.','.$cfdiComprobante['Folio'];
                        } else {
                            $no_factura = $cfdiComprobante['Folio'];
                        }
                    }
                    foreach ($xml->xpath('//t:TimbreFiscalDigital') as $tfd) {
                        if(is_null($fecha_factura)) {
                            $fecha_factura = Carbon::parse($tfd['FechaTimbrado'])->startOfDay();
                        }
                    }
                }

                $compra->no_factura = $no_factura;
                $compra->fecha_factura = $fecha_factura;
                $compra->update();
                $this->line('Compra #'.$compra->id.' actualizada: Fecha '.$fecha_factura.' No '.$no_factura);
            }

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            $this->error($ex->getMessage());
        }
    }
}
