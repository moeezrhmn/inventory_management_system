@extends('layouts.tabler')

@section('content')
<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-header">
                <div>
                    <h3 class="card-title">
                        {{ __('Create Labour') }}
                    </h3>
                </div>
                <div class="card-actions">
                    <x-action.close route="{{ route('labours.index') }}" />
                </div>
            </div>
            <form method="POST" action="{{ route('labours.store') }}">
                @csrf
                <div class="card-body">
                    <livewire:name />
                    <livewire:email />
                    <livewire:phone />
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