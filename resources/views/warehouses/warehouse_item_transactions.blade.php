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
        @if ($errors->any())
        <div class="alert alert-error alert-dismissible" role="alert">
            <h3 class="mb-1">Error</h3>
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
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

            <button data-bs-toggle="modal" data-bs-target="#modal" class="btn btn-primary mb-2">Remove stock </button>
            <button data-bs-toggle="modal" data-bs-target="#add_purchase_modal" class="btn btn-primary mb-2"> Add Purchase </button>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Created</th>
                        <th>Supplier</th>
                        <th>Quantity</th>
                        <th>Per piece price</th>
                        <th>Total Payment</th>
                        <th>Total Paid</th>
                        <th>Reference</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach($warehouseItemTransactions as $transaction )
                    <tr>
                        <td> {{ \Carbon\Carbon::parse($transaction['created_at'])->format('d M Y')  }} </td>
                        <td>
                            {{ $transaction->supplier->name ?? 'NA' }} 
                            <div>
                                <small>
                                    {{ $transaction->supplier->phone ?? '' }}
                                </small>
                            </div>
                        </td>
                        <td> {{$transaction['quantity'] }} </td>
                        <td> {{$transaction['per_piece_price'] ?? 'NA'  }} </td>
                        <td> {{$transaction['total_payment'] ?? 'NA'  }} </td>
                        <td>
                            {{$transaction['total_paid'] }}
                            @if($transaction->supplier)
                            <a href="javascript:void(0)" class="change-paid-amount" data-id="{{ $transaction->id }}" data-paid="{{$transaction['total_paid']}}" data-max-paid="{{ $transaction->total_payment }}">change</a>
                            @endif
                        </td>
                        <td> {{$transaction['reference'] }} </td>
                        <td>
                            <form onsubmit="confirm('Do your really want to delete?') ? true : event.preventDefault()" action="{{ route('warehouse.transactions.delete', $transaction['id']) }}" method="post">
                                @csrf
                                @method('delete')
                                <button type="submit" data-route="{{route('warehouse.transactions.delete', $transaction['id'])}}" class="btn-icon btn btn-primary btn btn-outline-danger btn-icon" data-id="{{ $transaction['id'] }}">
                                    <x-icon.trash />
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
                    <h3 class="modal-title text-center mx-auto" id="modalCenterTitle"> Remove Stock </h3>
                </div>

                <form action="{{ route('warehouse.transactions.create') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="warehouse_item_id" value="{{ $warehouseItem->id }}">
                        <div class="mb-3">
                            <label> Quantity:</label>
                            <input type="number" class="form-control" max='-1' name="quantity" required>
                        </div>
                        <div class="mb-3">
                            <label> Reference (optional):</label>
                            <textarea name="reference" rows="3" class="form-control"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn  btn-danger" type="button" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn  btn-primary" type="submit">Remove stock</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="add_purchase_modal" tabindex="-1" role="dialog" aria-labelledby="add_purchase_modal_CenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title text-center mx-auto" id="add_purchase_modal_CenterTitle"> Add Purchase </h3>
                </div>

                <form action="{{ route('warehouse.transactions.purchase') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="warehouse_item_id" value="{{ $warehouseItem->id }}">
                        <div class="mb-3">
                            <select name="supplier_id" required class="form-control" id="supplier_id">
                                <option value=""> Select Supllier </option>
                                @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}"> {{ $supplier->name }} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label> Quantity:</label>
                            <input type="number" class="form-control" min='1' name="quantity" required>
                        </div>
                        <div class="mb-3">
                            <label> Per Piece price:</label>
                            <input type="number" class="form-control" min='1' name="per_piece_price" required>
                        </div>
                        <div class="mb-3">
                            <label> Total Payment:</label>
                            <input type="number" readonly class="form-control" name="total_payment">
                        </div>
                        <div class="mb-3">
                            <label>Total Paid:</label>
                            <input type="number" class="form-control" max='0' name="total_paid">
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

    <script>
        $(document).ready(function() {

            $('#add_purchase_modal input[name=per_piece_price], #add_purchase_modal input[name=quantity] ').on('change', function() {
                let perPiecePrice = parseFloat($('#add_purchase_modal input[name=per_piece_price]').val()) || 0;
                let quantity = parseInt($('#add_purchase_modal input[name=quantity]').val()) || 0;

                let totalPayment = perPiecePrice * quantity;
                $('#add_purchase_modal input[name=total_payment]').val(totalPayment.toFixed(2));
                $('#add_purchase_modal input[name=total_paid]').attr('max', totalPayment.toFixed(2));
            })

            $('.change-paid-amount').on('click', function() {
                let transaction_id = $(this).data('id')
                let max_paid = $(this).data('max-paid')
                let paid = $(this).data('paid')

                console.log(transaction_id)
                console.log(max_paid)
                console.log(paid)

                let total_paid;

                do {
                    total_paid = prompt(`Please enter total paid amount! Max can be (${max_paid})`, paid);

                    if (!total_paid) {
                        alert('Amount cannot be empty.');
                    } else if (isNaN(total_paid)) {
                        alert('Please enter a numeric value.');
                    } else if (Number(total_paid) > max_paid) {
                        alert('Max paid amount can only be ' + max_paid);
                    } else {
                        break;
                    }
                } while (true);

                total_paid = Number(total_paid); 
                console.log("Validated Total Paid:", total_paid);

                $.ajax({
                    url:'{{ route("warehouse.transactions.change_total_paid") }}',
                    type:'POST',
                    headers: {
                        'X-CSRF_TOKEN' : '{{ csrf_token() }}'
                    },
                    data: {
                        transaction_id, total_paid
                    },
                    success: (res) => {
                        console.log(res)
                        if(res?.message){
                            alert(res?.message);
                            window.location.reload();
                        }
                    }
                })
            })

        })
    </script>

</div>
@endsection