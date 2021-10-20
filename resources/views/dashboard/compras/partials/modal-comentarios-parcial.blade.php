<div class="modal fade" id="modal-comentarios-parcial" tabindex="-1" role="dialog" aria-labelledby="modal-comentarios-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-comentarios-title">Comentarios</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{ Form::hidden('parcial', false, ['id' => 'parcial']) }}
                <textarea name="comentarios_parcial" rows="10" class="form-control" id="comentarios_parcial"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary" id="btn-parcial">Aprobar parcialmente</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $modal_comentarios_parcial =  $('#modal-comentarios-parcial');
        $modal_comentarios_parcial.on('hidden.bs.modal', function () {
            $('#comentarios_parcial').val(null);
            $('#parcial').val(false);
            $('#comentarios').val(null);
            $('#rechazar').val(false);
        });
        $modal_comentarios_parcial.on('show.bs.modal', function () {
            $('#parcial').val(true);
            $('#comentarios').val(null);
            $('#rechazar').val(false);
        });
        $(document).on('click', '#btn-parcial', function() {
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
