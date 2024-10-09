@extends('layouts.tabler')

@section('content')
<div class="page-body">
    <div class="container-xl">
        <h3>Edit Labour Work</h3>
        <form action="{{ route('labourwork.update', $labourWork->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Product</label>
                <select name="p_id" class="form-select">
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" {{ $labourWork->p_id == $product->id ? 'selected' : '' }}>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Pieces</label>
                <input type="number" name="pieces" class="form-control" value="{{ $labourWork->pieces }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Payment</label>
                <input type="number" name="payment" class="form-control" value="{{ $labourWork->payment }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" required>{{ $labourWork->description }}</textarea>
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Update Labour Work</button>
            </div>
        </form>
    </div>
</div>
@endsection
