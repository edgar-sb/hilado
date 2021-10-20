<div class="modal fade" id="modal-comentarios" tabindex="-1" role="dialog" aria-labelledby="modal-comentarios-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-comentarios-title">Motivos de rechazo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{ Form::hidden('rechazar', false, ['id' => 'rechazar']) }}
                <textarea name="comentarios" rows="10" class="form-control" id="comentarios"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary" id="btn-rechazar">Rechazar</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $modal_comentarios =  $('#modal-comentarios');
        $modal_comentarios.on('hidden.bs.modal', function () {
            $('#comentarios').val(null);
            $('#rechazar').val(false);
            $('#comentarios_parcial').val(null);
            $('#parcial').val(false);
        });
        $modal_comentarios.on('show.bs.modal', function () {
            $('#rechazar').val(true);
            $('#comentarios_parcial').val(null);
            $('#parcial').val(false);
        });
        $(document).on('click', '#btn-rechazar', function() {
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
