@extends('boilerplate::layout.index', [
    'title' => 'File Manager',
    'subtitle' => 'Upload file',
    'breadcrumb' => [
        'File Manager' => 'boilerplate.file-manager.index',
        'Upload file'
    ]
])

@section('content')

<div class="row">
    <div class="col-lg-5">
        @component('boilerplate::card', ['title' => 'Upload File', 'color' => 'primary'])
            <form method="POST" action="{{ route('boilerplate.file-manager.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="file">Choose file</label>
                    <input type="file" name="file" id="file" class="form-control-file @error('file') is-invalid @enderror" required>
                    @error('file')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Any file allowed. Max 50MB.</small>
                </div>

                <div class="d-flex">
                    <button type="submit" class="btn btn-primary mr-2">
                        <i class="fa-solid fa-upload mr-1"></i> Upload
                    </button>
                    <a href="{{ route('boilerplate.file-manager.index') }}" class="btn btn-default">
                        <i class="fa-solid fa-arrow-left mr-1"></i> Back
                    </a>
                </div>
            </form>
        @endcomponent
    </div>

    <div class="col-lg-7">
        @component('boilerplate::card', ['title' => 'Preview'])
            <div id="upload-preview" class="upload-preview">
                <span class="upload-preview-placeholder">
                    <i class="fa-regular fa-file"></i>
                    <span>Select a file to preview</span>
                </span>
            </div>
            <div id="upload-file-info" class="upload-file-info text-muted mt-3 d-none"></div>
        @endcomponent
    </div>
</div>

@endsection

@push('css')
<style>
    .upload-preview {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 380px;
        border: 1px dashed rgba(0, 0, 0, .25);
        border-radius: 6px;
        background: #f8f9fa;
        overflow: hidden;
    }

    .dark-mode .upload-preview {
        background: #2f3439;
        border-color: rgba(255, 255, 255, .25);
    }

    .upload-preview img,
    .upload-preview iframe,
    .upload-preview video,
    .upload-preview audio {
        max-width: 100%;
        width: 100%;
        border: 0;
    }

    .upload-preview img,
    .upload-preview iframe,
    .upload-preview video {
        height: 380px;
        object-fit: contain;
    }

    .upload-preview audio {
        width: 80%;
    }

    .upload-preview-placeholder {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        color: #6c757d;
    }

    .upload-preview-placeholder i {
        font-size: 56px;
    }

    .upload-file-info {
        word-break: break-word;
    }
</style>
@endpush

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('file');
        const preview = document.getElementById('upload-preview');
        const info = document.getElementById('upload-file-info');
        let objectUrl = null;

        function formatSize(bytes) {
            const units = ['B', 'KB', 'MB', 'GB'];
            let size = bytes;
            let unit = 0;

            while (size >= 1024 && unit < units.length - 1) {
                size = size / 1024;
                unit++;
            }

            return `${size.toFixed(unit === 0 ? 0 : 2)} ${units[unit]}`;
        }

        function setFallback(file) {
            const extension = file.name.includes('.') ? file.name.split('.').pop().toUpperCase() : 'FILE';
            preview.innerHTML = `
                <span class="upload-preview-placeholder">
                    <i class="fa-regular fa-file"></i>
                    <span>${extension}</span>
                </span>
            `;
        }

        input.addEventListener('change', function () {
            const file = input.files[0];

            if (objectUrl) {
                URL.revokeObjectURL(objectUrl);
                objectUrl = null;
            }

            if (!file) {
                preview.innerHTML = `
                    <span class="upload-preview-placeholder">
                        <i class="fa-regular fa-file"></i>
                        <span>Select a file to preview</span>
                    </span>
                `;
                info.classList.add('d-none');
                info.textContent = '';
                return;
            }

            objectUrl = URL.createObjectURL(file);
            info.classList.remove('d-none');
            info.textContent = `${file.name} | ${file.type || 'Unknown type'} | ${formatSize(file.size)}`;

            if (file.type.startsWith('image/')) {
                preview.innerHTML = `<img src="${objectUrl}" alt="">`;
                return;
            }

            if (file.type === 'application/pdf' || file.type.startsWith('text/')) {
                preview.innerHTML = `<iframe src="${objectUrl}" title="File preview"></iframe>`;
                return;
            }

            if (file.type.startsWith('video/')) {
                preview.innerHTML = `<video src="${objectUrl}" controls></video>`;
                return;
            }

            if (file.type.startsWith('audio/')) {
                preview.innerHTML = `<audio src="${objectUrl}" controls></audio>`;
                return;
            }

            setFallback(file);
        });
    });
</script>
@endpush
