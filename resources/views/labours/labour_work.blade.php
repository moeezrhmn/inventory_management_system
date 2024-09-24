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
        <div class="card">
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
                    <div class="mb-3">
                        <label for="name" class="form-label">
                            {{ __('Labours') }}
                        </label>
                        <select class="form-control" name="labourID">
                            <option value="">Select Labour</option>
                            @foreach($labours as $labour)
                            <option value="{{$labour->id}}">{{$labour->name}}</option>
                            @endforeach
                        </select>
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