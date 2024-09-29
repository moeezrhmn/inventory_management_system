@extends('layouts.tabler')
@section('content')
<div class="page-body">
    @if (!$labour)
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
        <div>
            <div class="text-secondary flex justify-between w-full mb-2" style="display: flex">
                <h3 style="flex:1;">{{ $labour['name'] }}'s History</h3>
                <div class="ms-2 d-inline-block">
                    Search:<input type="text" id="search-invoice" class="form-control form-control-sm" aria-label="Search invoice">
                </div>
            </div>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#No</th>
                        <th>Product</th>
                        <th>Pieces</th>
                        <th>Payment</th>
                        <th>Description</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @include('labours.work_table')
                </tbody>
            </table>

            <div class="flex justify-center">
                {{ $labourWorks->links() }}
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
