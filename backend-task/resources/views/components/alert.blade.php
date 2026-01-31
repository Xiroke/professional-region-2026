@props(['name', 'value' => null])

<div class="">
    <input
            value="{{old($name, $value)}}"
            name="{{ $name }}"
            {{ $attributes->class([
            'form-control',
            'is-invalid' => $errors->has($name)
    ]) }}>
    @error($name)
    <div class="invalid-feedback">
        {{ $message }}
    </div>
    @enderror
</div>
