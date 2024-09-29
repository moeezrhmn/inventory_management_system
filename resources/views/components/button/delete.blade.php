@props([
    'route'
])

<form action="{{ $route }}" onsubmit="return confirm(' Are you sure you want to delete this? ') ?  true : event.preventDefault();" method="POST" class="d-inline-block">
    @csrf
    @method('delete')
    <x-button type="submit" {{ $attributes->class(['btn btn-outline-danger']) }}>
        <x-icon.trash/>
        {{ $slot }}
    </x-button>
</form>
