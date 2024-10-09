@extends('layouts.tabler')
@section('content')
<div class="page-body">

    <div class="container-xl">
        @if (session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            <h3 class="mb-1">Success</h3>
            <p>{{ session('success') }}</p>
            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
        </div>
        @endif
        <div>
            
            <div class="text-secondary flex justify-between w-full mb-2" style="display: flex">
                <h3 style="flex:1;">{{ $warehouse['name'] }}'s Items</h3>
                
                <div class="ms-2 d-inline-block">
                    Search:<input type="text" id="search-detail" class="form-control form-control-sm" aria-label="Search invoice">
                </div>
            </div>
            
            <a href="{{ route('w_create', $warehouse['id']) }}" class="btn btn-primary mb-2">Add New Item </a>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Created</th>
                        <th>Item</th>
                        <th>Pieces</th>
                        <th>Unit</th>
                        <th>Measurements</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @include('warehouses.detail_table')
                </tbody>
            </table>

            <div class="flex justify-center">
                {{ $warehouseData->links() }}
            </div>
        </div>
    </div>

    
</div>
@endsection
