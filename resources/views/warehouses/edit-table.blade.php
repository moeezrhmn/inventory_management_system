@extends('layouts.tabler')

@section('content')
<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-header">
                <div>
                    <h3 class="card-title">
                        {{ __('Edit Warehouse Detail') }}
                    </h3>
                </div>

                <div class="card-actions">
                    <x-action.close route="{{ route('warehouses.index') }}" />
                </div>
            </div>
            <form method="POST" action="{{ route('w_update', $warehouseItem->id) }}">
                @csrf
                @method('put')
                <div class="card-body">
                    <div class="mb-3 flex-1 w-full">
                        <label for="item_name" class="form-label">Item Name</label>
                        <input type="text" class="border border-[#FCFCFD] p-2 w-full" name="item_name" value="{{ $warehouseItem->item_name }}" required>
                    </div>
                    <div class="mb-3 flex-1 w-full">
                        <label for="unit_id" class="form-label">Units</label>
                        <select class="border border-[#FCFCFD] p-2 w-full" name="unit_id" required>
                            <option value="">Select Unit</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}" {{ $unit->id == $warehouseItem->unit_id ? 'selected' : '' }}>
                                    {{ $unit->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 flex-1 w-full">
                        <label for="pieces" class="form-label">Pieces</label>
                        <input type="number" class="border border-[#FCFCFD] p-2 w-full" name="pieces" value="{{ $warehouseItem->pieces }}" required>
                    </div>
                    <div class="mb-3 flex-1 w-full">
                        <label for="measurements" class="form-label">Measurements</label>
                        <input type="text" class="border border-[#FCFCFD] p-2 w-full" name="measurements" value="{{ $warehouseItem->measurements }}" required>
                    </div>
                    
                </div>

                <div class="card-footer text-end">
                    <x-button type="submit">
                        {{ __('Update') }}
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@pushonce('page-scripts')
<script>
    // Slug Generator
    const title = document.querySelector("#name");
    const slug = document.querySelector("#slug");
    title.addEventListener("keyup", function() {
        let preslug = title.value;
        preslug = preslug.replace(/ /g, "-");
        slug.value = preslug.toLowerCase();
    });
</script>
@endpushonce