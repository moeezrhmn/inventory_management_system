@extends('layouts.tabler')
@section('content')
<div class="page-body">
    <div class="container-xl">
        @if ($errors->any())
        <div class="alert alert-danger alert-dismissible" role="alert">
            <h3 class="mb-1">Error</h3>
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
        </div>
        @endif
        <div class="card">
            <div class="card-header">
                <div>
                    <h3 class="card-title">
                        {{ __('Create Warehouse') }}
                    </h3>
                </div>
                <div class="card-actions">
                    <x-action.close route="{{ route('warehouses.index') }}" />
                </div>
            </div>
            <form method="POST" action="{{ route('warehouses.store') }}">
                @csrf
                <div class="card-body">
                    <livewire:name />
                    <x-input
                        label="{{ __('Location') }}"
                        id="location"
                        name="location"/>
                    <x-input
                        label="{{ __('Description') }}"
                        id="description"
                        name="description"/>
                </div>
                <div class="card-footer text-end">
                    <x-button type="submit">
                        {{ __('Create') }}
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection