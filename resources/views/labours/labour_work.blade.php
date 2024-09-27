@extends('layouts.tabler')
@section('content')
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
                        {{ __('Labour Work') }}
                    </h3>
                </div>
                <div class="card-actions">
                    <x-action.close route="{{ route('labours.index') }}" />
                </div>
            </div>
            <form method="POST" action="{{ route('labours.addwork') }}">
                @csrf
                <div class="card-body">
                    <div class="flex flex-row w-full gap-2 " style="display: flex;">
                        <div class="mb-3 flex-1 w-full">
                            <label for="name" class="form-label">
                                Labours
                            </label>
                            <select class="border border-[#FCFCFD] p-2 w-full" name="labourID">
                                <option value="">Select Labour</option>
                                <option value="2">Haseeb Zafar</option>
                                <option value="4"></option>
                            </select>
                        </div>
                        <div class="mb-3 flex-1 w-full">
                            <label for="name" class="form-label">
                                Products
                            </label>
                            <select class="border border-[#FCFCFD] p-2 w-full" name="pID">
                                <option value="">Select Product</option>
                                <option value="1">iPhone 14 Pro</option>
                                <option value="2">ASUS Laptop</option>
                                <option value="3">Logitech Keyboard</option>
                                <option value="4">Logitech Speakers</option>
                                <option value="5">AutoCAD v7.0</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex w-full gap-2" style="display: flex;">
                        <div class="mb-3 flex-1 w-full">
                            <label for="name" class="form-label">Pieces</label>
                            <input type="text" class="border border-[#FCFCFD] p-2 w-full" name="pieces">
                        </div>
                        <div class="mb-3 flex-1 w-full">
                            <label for="name" class="form-label">Payment</label>
                            <input type="text" class="border border-[#FCFCFD] p-2 w-full" name="payment">
                        </div>
                    </div>
                    <div class="mb-3 flex-1 w-full">
                        <label for="name" class="form-label">Description</label>
                        <textarea class="border border-[#FCFCFD] p-2 w-full h-[200px]" name="description"></textarea>
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