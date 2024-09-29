<div class="mb-3">
    <label for="email" class="form-label required">
        {{ __('Email') }}
    </label>

    <input type="text"
        id="email"
        name="email"
        wire:model.blur="email"
        wire:keyup="selectedemail"
        placeholder="Enter email"
        class="form-control @error('email') is-invalid @enderror" />

    @error('email')
    <div class="invalid-feedback">
        {{ $message }}
    </div>
    @enderror
</div>