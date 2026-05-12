@extends('boilerplate::layout.index', [
    'title' => 'File Manager',
    'subtitle' => 'Manage files',
    'breadcrumb' => [
        'File Manager'
    ]
])

@section('content')

<div class="row">
    <div class="col-12 mb-3">
        <a href="{{ route('boilerplate.file-manager.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-upload mr-1"></i> Upload File
        </a>
    </div>

    <div class="col-12">
        @component('boilerplate::card', ['title' => 'Files'])
            @if($files->count())
                <div class="file-manager-grid">
                    @foreach($files as $file)
                        <div class="file-manager-item">
                            <a href="{{ route('boilerplate.file-manager.preview', $file) }}" target="_blank" class="file-manager-preview">
                                @if($file->is_image)
                                    <img src="{{ route('boilerplate.file-manager.preview', $file) }}" alt="{{ $file->original_name }}">
                                @elseif($file->is_pdf)
                                    <iframe src="{{ route('boilerplate.file-manager.preview', $file) }}#toolbar=0" title="{{ $file->original_name }}"></iframe>
                                @elseif($file->is_text && $file->size <= 1048576)
                                    <iframe src="{{ route('boilerplate.file-manager.preview', $file) }}" title="{{ $file->original_name }}"></iframe>
                                @else
                                    <span class="file-manager-file-icon">
                                        <i class="fa-regular {{ $file->icon }}"></i>
                                        <small>{{ $file->extension }}</small>
                                    </span>
                                @endif
                            </a>

                            <div class="file-manager-meta">
                                <div class="font-weight-bold text-truncate" title="{{ $file->original_name }}">{{ $file->original_name }}</div>
                                <div class="small text-muted text-truncate">{{ $file->mime_type ?? 'Unknown type' }}</div>
                                <div class="small text-muted">{{ $file->human_size }} | {{ $file->created_at->format('d M Y, h:i A') }}</div>
                                <div class="small text-muted text-truncate">By {{ $file->uploader->name ?? 'System' }}</div>
                            </div>

                            <div class="file-manager-actions">
                                <a href="{{ route('boilerplate.file-manager.preview', $file) }}" target="_blank" class="btn btn-sm btn-info" title="Preview">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="{{ route('boilerplate.file-manager.download', $file) }}" class="btn btn-sm btn-success" title="Download">
                                    <i class="fa-solid fa-download"></i>
                                </a>
                                <form action="{{ route('boilerplate.file-manager.destroy', $file) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this file?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center text-muted py-4">No files uploaded yet.</div>
            @endif

            <div class="mt-3">
                {{ $files->links() }}
            </div>
        @endcomponent
    </div>
</div>

@endsection

@push('css')
<style>
    .file-manager-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 16px;
    }

    .file-manager-item {
        border: 1px solid rgba(0, 0, 0, .12);
        border-radius: 6px;
        overflow: hidden;
        background: #fff;
    }

    .dark-mode .file-manager-item {
        background: #343a40;
        border-color: rgba(255, 255, 255, .12);
    }

    .file-manager-preview {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 170px;
        background: #f8f9fa;
        overflow: hidden;
    }

    .dark-mode .file-manager-preview {
        background: #2f3439;
    }

    .file-manager-preview img,
    .file-manager-preview iframe {
        width: 100%;
        height: 100%;
        border: 0;
        object-fit: cover;
    }

    .file-manager-file-icon {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 8px;
        color: #6c757d;
    }

    .file-manager-file-icon i {
        font-size: 48px;
    }

    .file-manager-file-icon small {
        max-width: 120px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-weight: 600;
    }

    .file-manager-meta {
        padding: 10px 12px 8px;
        min-height: 96px;
    }

    .file-manager-actions {
        display: flex;
        justify-content: flex-end;
        gap: 6px;
        padding: 0 12px 12px;
    }
</style>
@endpush
