@extends('layouts.tabler')

@section('content')
<div class="page-body">
    @if (!$labours)
    <x-empty title="No labours found"
        message="Try adjusting your search or filter to find what you're looking for."
        button_label="{{ __('Add your first labour') }}" button_route="{{ route('labours.create') }}" />
    @else

    <div class="container-xl">
        @if (session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            <h3 class="mb-1">Success</h3>
            <p>{{ session('success') }}</p>

            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
        </div>
        @endif
        @livewire('tables.labour-table')
    </div>
    @endif
</div>
@endsection