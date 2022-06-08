<div class="col-3 ps-2 mt-2">
    <div class="{{ $errors->has($name) ? 'invalid' : '' }}">
        <input
            class="form-control"
            list="countryList"
            name="{{ $name }}"
            id="{{ $name }}"
            value="{{ (!$errors->has($name) && $errors->any()) ? old($name) : 'CH' }}"
            required placeholder="Type ...">
        <datalist id="countryList">
            @foreach($countries as $country)
                <option value="{{ $country['alpha2'] }}">{{ $country['name'] }}</option>
            @endforeach
        </datalist>
    </div>
    @if($errors->has($name))
        <div class="text-field-validation">
            {{ $errors->first($name) }}
        </div>
    @endif
</div>
