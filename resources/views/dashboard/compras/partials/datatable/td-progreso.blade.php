<div class="row bs-wizard" style="border-bottom:0;">
    <div class="col-4 col-sm-2 bs-wizard-step {{ $compra->progreso('venta') }}">
        <div class="text-center bs-wizard-stepnum"></div>
        <div class="progress"><div class="progress-bar"></div></div>
        <a href="{{ $compra->progresoUrl('venta') }}" class="bs-wizard-dot">
            <div>
                <i class="fas fa-file-invoice"></i>
            </div>
        </a>
        <div class="bs-wizard-info text-center">Venta </div>
    </div>

    <div class="col-4 col-sm-2 bs-wizard-step {{ $compra->progreso('factura') }}">
        <div class="text-center bs-wizard-stepnum"></div>
        <div class="progress"><div class="progress-bar"></div></div>
        <a href="{{ $compra->progresoUrl('factura') }}" class="bs-wizard-dot">
            <div>
                <i class="fas fa-file-invoice-dollar"></i>
            </div>
        </a>
        <div class="bs-wizard-info text-center">Factura </div>
    </div>

    <div class="col-4 col-sm-2 bs-wizard-step {{ $compra->progreso('acuse-y-carta-porte') }}">
        <div class="text-center bs-wizard-stepnum"></div>
        <div class="progress"><div class="progress-bar"></div></div>
        <a href="{{ $compra->progresoUrl('acuse-y-carta-porte') }}" class="bs-wizard-dot">
            <div>
                <i class="fas fa-file-invoice-dollar"></i>
            </div>
        </a>
        <div class="bs-wizard-info text-center">Acuse y carta porte</div>
    </div>

    <div class="col-4 col-sm-2 bs-wizard-step {{ $compra->progreso('validacion') }}">
        <div class="text-center bs-wizard-stepnum"></div>
        <div class="progress"><div class="progress-bar"></div></div>
        <a href="{{ $compra->progresoUrl('validacion') }}" class="bs-wizard-dot">
            <div>
                <i class="far fa-check-circle"></i>
            </div>
        </a>
        <div class="bs-wizard-info text-center">Validación </div>
    </div>

    <div class="col-4 col-sm-2 bs-wizard-step {{ $compra->progreso('pago') }}">
        <div class="text-center bs-wizard-stepnum"></div>
        <div class="progress"><div class="progress-bar"></div></div>
        <a href="{{ $compra->progresoUrl('pago') }}" class="bs-wizard-dot">
            <div>
                <i class="fas fa-money-check-alt"></i>
            </div>
        </a>
        <div class="bs-wizard-info text-center">Pago</div>
    </div>

    <div class="col-4 col-sm-2 bs-wizard-step {{ $compra->progreso('complemento') }}">
        <div class="text-center bs-wizard-stepnum"></div>
        <div class="progress"><div class="progress-bar"></div></div>
        <a href="{{ $compra->progresoUrl('complemento') }}" class="bs-wizard-dot">
            <div>
                <i class="fas fa-file-invoice-dollar"></i>
            </div>
        </a>
        <div class="bs-wizard-info text-center">Complemento </div>
    </div>
</div>
