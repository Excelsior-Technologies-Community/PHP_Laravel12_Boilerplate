@extends('boilerplate::layout.index', [
    'title' => __('File Manager'),
    'subtitle' => 'Manage your files',
    'breadcrumb' => [
        __('File Manager')
    ]
])

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">All Files</h3>
                <div class="card-tools">
                    <a href="{{ route('boilerplate.file-manager.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-upload"></i> Upload New File
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if(session('growl'))
                    <div class="alert alert-{{ session('growl.type') }} alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-check"></i> {{ session('growl.title') }}</h5>
                        {{ session('growl.message') }}
                    </div>
                @endif
                
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>File Name</th>
                                <th>Type</th>
                                <th>Size</th>
                                <th>Uploaded By</th>
                                <th>Uploaded Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($files as $file)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <i class="fas fa-{{ $file->file_type }} mr-2"></i>
                                        {{ Str::limit($file->original_name, 50) }}
                                        @if($file->description)
                                            <br><small class="text-muted">{{ Str::limit($file->description, 100) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ ucfirst($file->file_type) }}</span>
                                    </td>
                                    <td>{{ $file->formatted_size }}</td>
                                    <td>{{ $file->user->name }}</td>
                                    <td>{{ $file->created_at->format('Y-m-d H:i:s') }}</td>
                                    <td>
                                        <div class="btn-group">
                                            @if($file->isImage())
                                                <a href="{{ route('boilerplate.file-manager.preview', $file) }}" 
                                                   target="_blank" 
                                                   class="btn btn-sm btn-info" 
                                                   title="Preview">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @endif
                                            <a href="{{ route('boilerplate.file-manager.download', $file) }}" 
                                               class="btn btn-sm btn-success" 
                                               title="Download">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            <form action="{{ route('boilerplate.file-manager.destroy', $file) }}" 
                                                  method="POST" 
                                                  style="display: inline-block;"
                                                  onsubmit="return confirm('Are you sure you want to delete this file?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No files found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-3">
                    {{ $files->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
    .table td {
        vertical-align: middle;
    }
    .badge {
        font-size: 12px;
        padding: 5px 10px;
    }
</style>
@endpush