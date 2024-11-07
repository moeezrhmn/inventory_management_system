@extends('layouts.tabler')

@section('content')
<div class="page-body">
    @if(!$customers)
    <x-empty
        title="No customer found ( You have to add customer then you see his report )"
        message="Try adjusting your search or filter to find what you're looking for."
        button_label="{{ __('Add your first Customer') }}"
        button_route="{{ route('customers.create') }}" />
    @else
    <div class="container-xl">
        @if (session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            <h3 class="mb-1">Success</h3>
            <p>{{ session('success') }}</p>

            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
        </div>
        @elseif ( session('error') )
        <div class="alert alert-error alert-dismissible" role="alert">
            <h3 class="mb-1">Error</h3>
            <p>{{ session('error') }}</p>

            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
        </div>
        @endif
        <!-- Report work -->
        <div class="row card mb-3 ">
            <div class="card-body">
                <h2> Order's Report </h2>
                <form action="{{ route('statements.report_pdf') }}" class="d-flex align-items-end justify-content-between " method="post" >
                    @csrf
                    <div>
                        <label for="customer">Select Customer:</label>
                        <select name="customer_id" class="form-control"  id="customer" >
                            <option value="">Select Customer</option>
                            @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="startDate">Start Date:</label>
                        <input class="form-control" type="date" id="startDate" name="start_date"  >
                    </div>

                    <div>
                        <label for="endDate">End Date:</label>
                        <input class="form-control" type="date" id="endDate" name="end_date" >
                    </div>

                    <button class="btn btn-primary"  type="submit">Show Report</button>
                </form>

            </div>
        </div>
   
        <!-- Warehouse Item Purchase work -->
        <div class="row card mb-3 ">
            <div class="card-body">
                <h2> Warehouse Item Purchase Report </h2>
                <form action="{{ route('statements.warehouse_item_purchase_pdf') }}" class="d-flex align-items-end justify-content-between " method="post" >
                    @csrf
                    <div>
                        <label for="supplier">Select Supllier:</label>
                        <select name="supplier_id" class="form-control"  id="supplier" >
                            <option value="">Select</option>
                            @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="suppstartDate">Start Date:</label>
                        <input class="form-control" type="date" id="suppstartDate" name="start_date"  >
                    </div>

                    <div>
                        <label for="suppendDate">End Date:</label>
                        <input class="form-control" type="date" id="suppendDate" name="end_date" >
                    </div>

                    <button class="btn btn-primary"  type="submit">Show Report</button>
                </form>

            </div>
        </div>
    </div>
    @endif
</div>
@endsection