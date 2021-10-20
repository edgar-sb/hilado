@push('styles')
    <style>
        .custom .m-card-profile__pic .m-card-profile__pic-wrapper {
            margin: 0 !important;
            border-radius: 0 !important;
        }
        .custom .m-card-profile__pic img {
            width: 100% !important;
            border-radius: 0 !important;
            max-width: 100% !important;
            height: 100% !important;
        }
        .custom .m-card-profile__pic video {
            width: 100% !important;
            border-radius: 0 !important;
            max-width: 100% !important;
        }
    </style>
@endpush

<!--begin::Modal-->
<div class="modal fade" id="modal-file" tabindex="-1" role="dialog" aria-labelledby="modalFileLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalFileLabel">
                    Previsualización
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">
                        &times;
                    </span>
                </button>
            </div>
            <div class="modal-body">
                <div class="custom m-card-profile">
                    <div class="m-card-profile__pic">
                        <div class="m-card-profile__pic-wrapper" style="width: 100%" id="file-preview">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="m-form__actions m--align-right" id="downloads">
                </div>
            </div>
        </div>
    </div>
</div>
<!--end::Modal-->

@push('scripts')
    <script>
        $(document).on('click', '.btn-modal-file', function() {
            $btn = $(this);
            let length = $btn.data('length');
            let preview = document.getElementById('file-preview');
            preview.innerHTML = '';
            let downloads = document.getElementById('downloads');
            downloads.innerHTML = '';
            for(let i = 0; i < length; i++) {
                let name = $btn.data(`names[${i}]`);
                let files = $btn.data(`files[${i}]`);
                let download_url = $btn.data(`downloads[${i}]`);
                files.forEach(function (item, index) {
                    let mime = item.mime;
                    let url = item.url;
                    switch (mime) {
                        case "audio/x-wav":
                        case "audio/mpeg":
                            audio = document.createElement('audio');
                            audio.src = url;
                            audio.controls = true;
                            preview.append(audio);
                            break;
                        case "video/mp4":
                        case "video/ogg":
                            video = document.createElement('video');
                            video.src = url;
                            video.controls = true;
                            preview.append(video);
                            break;
                        case "image/png":
                        case "image/jpg":
                        case "image/jpeg":
                            image = document.createElement('img');
                            image.src = url;
                            preview.append(image);
                            break;
                        case "application/pdf":
                            pdf = document.createElement('embed');
                            pdf.src = url;
                            pdf.type = "application/pdf";
                            pdf.width = "100%";
                            pdf.height = "500px";
                            preview.append(pdf);
                            break;
                    }
                });
                let button = document.createElement('a');
                button.setAttribute('class', 'btn btn-primary mx-1');
                button.setAttribute('href', download_url);
                button.textContent = `Descargar ${name}`;
                downloads.append(button);
            }
        })
    </script>
@endpush


