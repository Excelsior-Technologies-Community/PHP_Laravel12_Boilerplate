@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('Users List') }}</h3>
                <div class="card-tools">
                    <div class="btn-group">
                        <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown">
                            <i class="fas fa-download"></i> Export
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="{{ route('boilerplate.users.export', ['format' => 'csv']) }}">
                                <i class="fas fa-file-csv"></i> Export as CSV
                            </a>
                            <a class="dropdown-item" href="{{ route('boilerplate.users.export', ['format' => 'excel']) }}">
                                <i class="fas fa-file-excel"></i> Export as Excel
                            </a>
                            <a class="dropdown-item" href="{{ route('boilerplate.users.export', ['format' => 'json']) }}">
                                <i class="fas fa-file-code"></i> Export as JSON
                            </a>
                        </div>
                    </div>
                    @can('create_users')
                        <a href="{{ route('boilerplate.users.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-user-plus"></i> {{ __('New User') }}
                        </a>
                    @endcan
                </div>
            </div>
            <!-- Rest of the table code remains the same -->
        </div>
    </div>
</div>
@endsection