<div class="modal fade" id="modal-compras" tabindex="-1" role="dialog" aria-labelledby="modal-compras-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-compras-title">Compras seleccionadas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {!! Form::open(['route' => 'compras.update.multiple', 'method' => 'PUT', 'autocomplete' => 'off']) !!}
                <div class="modal-body" id="modal-body">
                    {{-- Contenido cargado desde JS --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" id="btn-guardar">Guardar</button>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $modal_compras = $('#modal-compras');
        $modal_body = $('#modal-body');
        $(document).on('click', '.modal-compras-show', function () {
            let estatus = $(this).data('estatus');
            if(compras_ids.length > 0) {
                mApp.block("#modal-compras", {});
                $modal_body.html('Cargando órdenes de compra...');
                $modal_body.load("{{ route('compras.load') }}", {
                        compras_ids: compras_ids,
                        estatus: estatus,
                    }, function() {
                        mApp.unblock("#modal-compras");
                        $('.m-date').datepicker({
                            todayHighlight: !0,
                            orientation: "bottom left",
                            format: 'yyyy/mm/dd',
                            language: "es",
                            clearBtn: true,
                            templates: {
                                leftArrow: '<i class="la la-angle-left"></i>',
                                rightArrow: '<i class="la la-angle-right"></i>'
                            }
                        });
                        Dropzone.options.mDropzoneXML = {
                            paramName: "file-xml",
                            maxFilesize: 10, // MB
                            acceptedFiles: ".xml",
                            maxFiles: 50,
                            headers: {
                                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                            },
                            init: function() {
                                this.on("success", function(file, response) {
                                    if(response.success) {
                                        $("#estatus").text(response.success);
                                        $("#estatus_msg").text(response.msg);
                                        $("#estatus-div").addClass('m--font-success');
                                        $("#estatus-div").removeClass('m--font-danger');
                                    } else {
                                        $("#estatus").text(response.error);
                                        $("#estatus_msg").text(response.msg);
                                        $("#estatus-div").removeClass('m--font-success');
                                        $("#estatus-div").addClass('m--font-danger');
                                    }
                                });
                                this.on("maxfilesexceeded", function(file) {
                                    this.removeAllFiles()
                                });
                                this.on("error", function(file) {
                                    this.removeAllFiles()
                                });
                            }
                        };
                        Dropzone.options.mDropzonePDF = {
                            paramName: "file-pdf",
                            maxFilesize: 10, // MB
                            acceptedFiles: ".pdf",
                            maxFiles: 50,
                            headers: {
                                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                            },
                            init: function() {
                                this.on("success", function(file, response) {
                                    if(response.success) {
                                        $("#estatus").text(response.success);
                                        $("#estatus_msg").text(response.msg);
                                        $("#estatus-div").addClass('m--font-success');
                                        $("#estatus-div").removeClass('m--font-danger');
                                    } else {
                                        $("#estatus").text(response.error);
                                        $("#estatus_msg").text(response.msg);
                                        $("#estatus-div").removeClass('m--font-success');
                                        $("#estatus-div").addClass('m--font-danger');
                                    }
                                });
                                this.on("maxfilesexceeded", function(file) {
                                    this.removeAllFiles()
                                });
                                this.on("error", function(file) {
                                    this.removeAllFiles()
                                });
                            }
                        };
                        Dropzone.options.comprobante = {
                            paramName: "file-comprobante",
                            maxFilesize: 10, // MB
                            acceptedFiles: ".pdf,.png,.jpg,.jpeg,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip",
                            maxFiles: 50,
                            headers: {
                                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                            },
                            init: function() {
                                this.on("success", function(file, response) {
                                    if(response.success) {
                                        $("#estatus").text(response.success);
                                        $("#estatus_msg").text(response.msg);
                                        $("#estatus-div").addClass('m--font-success');
                                        $("#estatus-div").removeClass('m--font-danger');
                                    } else {
                                        $("#estatus").text(response.error);
                                        $("#estatus_msg").text(response.msg);
                                        $("#estatus-div").removeClass('m--font-success');
                                        $("#estatus-div").addClass('m--font-danger');
                                    }
                                });
                                this.on("maxfilesexceeded", function(file) {
                                    this.removeAllFiles()
                                });
                                this.on("error", function(file) {
                                    this.removeAllFiles()
                                });
                            }
                        };
                        $("#mDropzoneXML").dropzone();
                        $("#mDropzonePDF").dropzone();
                        $("#comprobante").dropzone();
                    }
                );
            } else {
                $modal_body.html('No se encontraron órdenes de compras.');
            }
        });
        $modal_compras.on('hidden.bs.modal', function () {
            $modal_body.html('No se encontraron órdenes de compras.');
        });

        $(document).on('click', '#btn-guardar', function() {
            swal({
                title: "¡Guardando información!",
                imageUrl: "{{ asset('images/spinner.gif') }}",
                text: 'No cierres ni actualices la página, esto puede tomar unos minutos.',
                showCancelButton: false,
                showConfirmButton: false,
                allowOutsideClick: false
            });
        });
    </script>
@endpush
