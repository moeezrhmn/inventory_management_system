<div class="mb-3">
    <label for="phone" class="form-label required">
        {{ __('phone') }}
    </label>

    <input type="text"
        id="phone"
        name="phone"
        wire:model.blur="phone"
        wire:keyup="selectedphone"
        placeholder="Enter phone"
        class="form-control @error('phone') is-invalid @enderror" />

    @error('phone')
    <div class="invalid-feedback">
        {{ $message }}
    </div>
    @enderror
</div>