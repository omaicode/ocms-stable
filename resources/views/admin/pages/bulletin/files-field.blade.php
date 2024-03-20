@php
    $editing = false;
    $files = [];

    if($model) {
        $editing = true;
        $files = $model->files->toArray();
        $files = collect($files)->map(function($file) {
            return Arr::only($file, ['id', 'file_name', 'size', 'type', 'url']);
        })
        ->toArray();
    }
@endphp

<div class="mb-3">
    <label for="files" class="form-label">{{__('messages.form.attachment_files')}}</label>
    <input class="form-control" type="file" name="files[]" id="files" multiple>
    <input type="hidden" name="current_files[]" id="current_files" value="{!! implode(',', array_column($files, 'id')) !!}">
</div>

<div id="files-preview" class="d-flex flex-wrap gap-2"></div>

<script type="text/javascript" defer>
    window.onload = function() {
        let listFiles = {!! json_encode($files) !!};

        const updateFiles = function(file) {
            if(file instanceof File) {
                const container = new DataTransfer();
                listFiles.forEach(function (file) {
                    if(file instanceof File) {
                        container.items.add(file);
                    }
                });

                $('#files')[0].files = container.files;
            } else {
                if($('#current_files').val()) {
                    const hiddenVal = $('#current_files').val().split(',');
                    $('#current_files').val(hiddenVal.filter(function(x) {
                        return x != file.id;
                    }))
                }
            }
        };

        const renderFilesPreview = function(files = []) {
            listFiles = [...listFiles, ...files];
            const el = $('#files-preview');

            el.html('');

            for(let i = 0; i < listFiles.length; i++) {
                const file = listFiles[i];
                const { name, file_name, size, type, id, url } = file;
                let preview = 'data:image/jpeg;base64,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';

                // Check if the file is an image
                if(['image/jpg', 'image/jpeg', 'image/png', 'image/gif', 'image/bmp'].includes(type)) {
                    if(file instanceof File) {
                        preview = URL.createObjectURL(file);
                    } else {
                        preview = url;
                    }
                }

                let html = '<div class="card position-relative" style="width: 150px;">';

                if(id && url) {
                    html += '<div class="position-absolute top-0 start py-1 px-2"><a href="'+url+'" target="_blank"><i class="fas fa-download text-success"></i></a></div>';
                }

                html += '<div class="position-absolute top-0 end-0 py-1 px-2" style="cursor: pointer" data-action="remove"><i class="fas fa-times-circle text-danger"></i></div>';
                html += '<div class="card-img-top text-center overflow-hidden" style="width: 100%; height: 90px;"><img src="' + preview + '" style="width: 100%; height: 100%; object-fit: cover; display: inline-block"></div>';
                html += '<div class="card-body p-2">';
                html += '<div class="card-title fw-bold small text-truncate">'+ (name || file_name) +'</div>';
                html += '<div class="small text-muted">'+ Math.round(size / 965) +'KB</div>';
                html += '<div class="small text-muted">'+ type +'</div>';
                html += '</div></a>';
                html = $(html);

                html.find('[data-action="remove"]').on('click', function () {
                    listFiles.splice(i, 1);
                    updateFiles(file);
                    renderFilesPreview([], id);
                });

                el.append(html);
            }
        };

        $('#files').on('change', function(e) {
            listFiles = listFiles.filter(function(x) {
                return !(x instanceof File);
            });

            renderFilesPreview(e.target.files);
        });

        renderFilesPreview([])
    };
</script>
