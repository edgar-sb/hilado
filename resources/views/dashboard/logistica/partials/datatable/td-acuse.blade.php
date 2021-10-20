<td>
    @if($compra->acuse_log == 'Si')
        <button type="button" data-length="2" data-names[0]="Acuse" data-names[1]="Carta porte" data-files[0]="{{ $compra->acuse_log_files  }}" data-files[1]="{{ $compra->carta_log_files  }}" data-downloads[0]="{{ $compra->acuse_log_files_download }}" data-downloads[1]="{{ $compra->carta_log_files_download }}" data-toggle="modal" data-target="#modal-file" class="btn btn-sm btn-info btn-modal-file"> Consultar</button>
    @else
        <button type="button" class="btn btn-sm btn-metal" disabled> Consultar</button>
    @endif
</td>
