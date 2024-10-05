@extends('layouts.tabler')

@section('content')
<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-header">
                <div>
                    <h3 class="card-title">
                        {{ __('Edit Warehouse') }}
                    </h3>
                </div>

                <div class="card-actions">
                    <x-action.close route="{{ route('warehouses.index') }}" />
                </div>
            </div>
            <form action="{{ route('warehouses.update', $warehouse->id) }}" method="POST">
                @csrf
                @method('put')
                <div class="card-body">
                    <x-input
                        label="{{ __('Name') }}"
                        id="name"
                        name="name"
                        :value="old('name', $warehouse->name)"
                        required />

                    <x-input
                        label="{{ __('Location') }}"
                        id="Location"
                        name="location"
                        :value="old('Location', $warehouse->location)"/>
                    <x-input
                        label="{{ __('Description') }}"
                        id="description"
                        name="description"
                        :value="old('description', $warehouse->description)"/>
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