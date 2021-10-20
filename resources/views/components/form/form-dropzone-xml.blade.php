<label class="col-form-label">XML</label>
<div class="w-100">
    <div class="m-dropzone dropzone" action="{{ $action }}" id="mDropzoneXML">
        <div class="m-dropzone__msg dz-message needsclick">
            <h3 class="m-dropzone__msg-title">Arrastra los archivos aquí o da clic para seleccionarlos.</h3>
            <span class="m-dropzone__msg-desc">
                Se aceptan máximo <strong> 50 archivo </strong>
                <br>
                Tamaño máximo de archivos: <strong> 10 MB</strong>
                <br>
                Formatos aceptados:
                <strong>
                    XML
                </strong>
                .
            </span>
        </div>
    </div>
</div>

@push('scripts')
    <script>
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
    </script>
@endpush
