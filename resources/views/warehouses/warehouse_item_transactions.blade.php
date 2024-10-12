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
                <h3 style="flex:1;">{{ $warehouseItem->item_name }}'s transactions</h3>
                
                <div class="ms-2 d-inline-block">
                    Search:<input type="text" id="search-detail" class="form-control form-control-sm" aria-label="Search invoice">
                </div>
            </div>
            
            <button data-bs-toggle="modal" data-bs-target="#modal" class="btn btn-primary mb-2">Add New transaction </button>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Created</th>
                        <th>Quantity</th>
                        <th>Reference</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach($warehouseItemTransactions as $transaction )
                        <tr>
                            <td> {{ \Carbon\Carbon::parse($transaction['created_at'])->format('d M Y')  }} </td>
                            <td> {{$transaction['quantity']  }} </td>
                            <td> {{$transaction['reference']  }} </td>
                            <td> 
                                <form onsubmit="confirm('Do your really want to delete?') ? true : event.preventDefault()" action="{{ route('warehouse.transactions.delete', $transaction['id']) }}" method="post">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" data-route="{{route('warehouse.transactions.delete', $transaction['id'])}}" class="btn-icon btn btn-primary btn btn-outline-danger btn-icon" data-id="{{ $transaction['id'] }}">
                                        <x-icon.trash/>
                                    </button>
                                </form>
                            </td>
                        </tr>

                    @endforeach
                </tbody>
            </table>

            <div class="flex justify-center">
                {{ $warehouseItemTransactions->links() }}
            </div>
        </div>
    </div>

    
<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title text-center mx-auto" id="modalCenterTitle"> Add Item Transaction </h3>
            </div>

            <form action="{{ route('warehouse.transactions.create') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="warehouse_item_id" value="{{ $warehouseItem->id }}"  >
                    <div class="mb-3">
                        <label> Quantity:</label>
                        <input type="number" class="form-control" name="quantity"  required >
                    </div>
                    <div class="mb-3">
                        <label> Reference (optional):</label>
                        <textarea name="reference" rows="3" class="form-control"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn  btn-danger" type="button" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn  btn-primary" type="submit">Add transaction</button>
                </div>
            </form>
        </div>
    </div>
</div>

    
</div>
@endsection
