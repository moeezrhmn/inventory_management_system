@extends('layouts.tabler')
@section('content')
@php 
@endphp
<div class="page-body">
    <div class="container">
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
        <div class="card max-w-[500px]">
            <div class="card-header">
                <div>
                    <h3 class="card-title">
                        {{ __('Warehouse Detail') }}
                    </h3>
                </div>
                <div class="card-actions">
                    <x-action.close route="{{ route('warehouses.index') }}" />
                </div>
            </div>

            <form method="POST" action="{{ route('w_store') }}">
                @csrf
                <div class="card-body">
                    <input type="hidden" name="warehouse_id" value="{{$warehouse_id}}">
                    <div class="flex flex-row w-full gap-2 " style="display: flex;">
                        <div class="mb-3 flex-1 w-full">
                            <label for="name" class="form-label">
                                Item Name
                            </label>
                            <input type="text" class="border border-[#FCFCFD] p-2 w-full" name="item" required>
                        </div>
                        <div class="mb-3 flex-1 w-full">
                            <label for="name" class="form-label">
                                Units
                            </label>
                            <select class="border border-[#FCFCFD] p-2 w-full" name="unit_id">
                                <option value="">Select Unit</option>
                                
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="flex w-full gap-2" style="display: flex;">
                        <div class="mb-3 flex-1 w-full">
                            <label for="name" class="form-label">Pieces</label>
                            <input type="number" value="0" disabled class="border border-[#FCFCFD] p-2 w-full" name="pieces">
                        </div>
                        <div class="mb-3 flex-1 w-full">
                            <label for="name" class="form-label">Measurements</label>
                            <input type="text" class="border border-[#FCFCFD] p-2 w-full" name="measurements">
                        </div>
                    </div>
                   
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