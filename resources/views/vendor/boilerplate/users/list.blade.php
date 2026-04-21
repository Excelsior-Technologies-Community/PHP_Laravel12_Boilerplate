@extends('boilerplate::layout.index', [
    'title' => __('boilerplate::users.title'),
    'subtitle' => __('boilerplate::users.list.title'),
    'breadcrumb' => [
        __('boilerplate::users.title') => 'boilerplate.users.index'
    ]
])

@section('content')

<div class="row">
    <div class="col-12 mb-3 d-flex justify-content-between">

        <!-- Create User Button -->
        <a href="{{ route('boilerplate.users.create') }}" class="btn btn-primary">
            @lang('boilerplate::users.create.title')
        </a>

        <!-- Export Users Button -->
        <a href="{{ route('boilerplate.users.export') }}" class="btn btn-success">
            <i class="fas fa-file-excel"></i> Export Users
        </a>

    </div>
</div>

<!-- Users Table -->
@component('boilerplate::card')
    @component('boilerplate::datatable', ['name' => 'users'])
    @endcomponent
@endcomponent

@endsection

{{-- Optional CSS (SAFE) --}}
@push('css')
<style>
    .img-circle {
        border: 1px solid #ccc;
    }
</style>
@endpush