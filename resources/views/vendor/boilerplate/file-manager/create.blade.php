@extends('boilerplate::layout.index', [
    'title' => __('Upload File'),
    'subtitle' => 'Upload new file to manager',
    'breadcrumb' => [
        __('File Manager') => route('boilerplate.file-manager.index'),
        __('Upload File')
    ]
])

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Upload New File</h3>
                <div class="card-tools">
                    <a href="{{ route('boilerplate.file-manager.index') }}" class="btn btn-default btn-sm">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
            <form action="{{ route('boilerplate.file-manager.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="file">Select File <span class="text-danger">*</span></label>
                        <div class="custom-file">
                            <input type="file" 
                                   class="custom-file-input @error('file') is-invalid @enderror" 
                                   id="file" 
                                   name="file" 
                                   required>
                            <label class="custom-file-label" for="file">Choose file</label>
                        </div>
                        <small class="form-text text-muted">Max file size: 100MB. Supported formats: Images, PDFs, Documents, etc.</small>
                        @error('file')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description (Optional)</label>
                        <textarea name="description" 
                                  id="description" 
                                  class="form-control @error('description') is-invalid @enderror" 
                                  rows="3"
                                  placeholder="Enter file description...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <div id="file-preview" class="mt-3 text-center" style="display: none;">
                            <img id="preview-image" src="#" alt="Preview" style="max-width: 300px; max-height: 200px;">
                            <div id="preview-pdf" style="display: none;">
                                <i class="fas fa-file-pdf fa-4x text-danger"></i>
                                <p>PDF Document</p>
                            </div>
                            <div id="preview-document" style="display: none;">
                                <i class="fas fa-file-alt fa-4x text-primary"></i>
                                <p>Document File</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload"></i> Upload File
                    </button>
                    <button type="reset" class="btn btn-default">
                        <i class="fas fa-undo"></i> Reset
                    </button>
                </div>
            </form>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Upload Guidelines</h3>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li><i class="fas fa-check-circle text-success"></i> Maximum file size: 100MB</li>
                    <li><i class="fas fa-check-circle text-success"></i> Allowed formats: Images (JPG, PNG, GIF, WEBP), PDF, DOC, DOCX, XLS, XLSX</li>
                    <li><i class="fas fa-check-circle text-success"></i> Files are stored securely in the cloud/storage</li>
                    <li><i class="fas fa-check-circle text-success"></i> You can preview images before uploading</li>
                    <li><i class="fas fa-check-circle text-success"></i> All uploaded files are tracked with user information</li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Recent Uploads</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>File</th>
                                <th>Size</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $recentFiles = \App\Models\FileManager::latest()->take(5)->get();
                            @endphp
                            @forelse($recentFiles as $recent)
                                <tr>
                                    <td>
                                        <i class="fas fa-{{ $recent->file_type }}"></i>
                                        {{ Str::limit($recent->original_name, 30) }}
                                    </td>
                                    <td>{{ $recent->formatted_size }}</td>
                                    <td>{{ $recent->created_at->diffForHumans() }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">No recent uploads</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    document.getElementById('file').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('file-preview');
        const previewImage = document.getElementById('preview-image');
        const previewPdf = document.getElementById('preview-pdf');
        const previewDocument = document.getElementById('preview-document');
        
        // Hide all previews
        previewImage.style.display = 'none';
        previewPdf.style.display = 'none';
        previewDocument.style.display = 'none';
        
        if (file) {
            const fileType = file.type;
            preview.style.display = 'block';
            
            if (fileType.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewImage.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else if (fileType === 'application/pdf') {
                previewPdf.style.display = 'block';
            } else {
                previewDocument.style.display = 'block';
            }
            
            // Update file input label
            const label = document.querySelector('.custom-file-label');
            label.innerHTML = file.name;
        } else {
            preview.style.display = 'none';
            const label = document.querySelector('.custom-file-label');
            label.innerHTML = 'Choose file';
        }
    });
</script>
@endpush

@push('css')
<style>
    .custom-file-label::after {
        content: "Browse";
    }
    #file-preview {
        padding: 20px;
        background: #f8f9fa;
        border-radius: 5px;
    }
</style>
@endpush