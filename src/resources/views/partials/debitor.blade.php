<div class="row col-6 ms-2">
    <div class="p-2 fs-5 mb-2 fw-normal color-gray-800">{{ __('index.debitor_information') }}</div>

    <div class="d-flex flex-wrap">
        <div class="d-flex flex-column mb-4 col-12">
            <div class="text-field {{ $errors->has('debitor_name') ? 'invalid' : '' }}">
                <input
                    name="debitor_name"
                    class="text-field-input"
                    value="{{ (!$errors->has('debitor_name') && $errors->any()) ? old('debitor_name') : '' }}"
                    type="text"
                    required
                    {{ !$errors->has('debitor_name') ? 'autofocus' : '' }}
                >
                <label class="text-field-label">{{ __('index.debitor_name') }}</label>
            </div>
            @if($errors->has('debitor_name'))
                <div class="text-field-validation">
                    {{ $errors->first('debitor_name') }}
                </div>
            @endif
        </div>

        <div class="d-flex flex-column mb-4 col-9 pe-2">
            <div class="text-field {{ $errors->has('debitor_street') ? 'invalid' : '' }}">
                <input
                    name="debitor_street"
                    class="text-field-input"
                    value="{{ (!$errors->has('debitor_street') && $errors->any()) ? old('debitor_street') : '' }}"
                    type="text"
                    required
                    {{ !$errors->has('debitor_street') ? 'autofocus' : '' }}
                >
                <label class="text-field-label">{{ __('index.debitor_street') }}</label>
            </div>
            @if($errors->has('debitor_street'))
                <div class="text-field-validation">
                    {{ $errors->first('debitor_street') }}
                </div>
            @endif
        </div>

        <div class="d-flex flex-column mb-4 col-3 ps-2">
            <div class="text-field {{ $errors->has('debitor_street_number') ? 'invalid' : '' }}">
                <input
                    name="debitor_street_number"
                    class="text-field-input"
                    value="{{ (!$errors->has('debitor_street_number') && $errors->any()) ? old('debitor_street_number') : '' }}"
                    type="text"
                    required
                    {{ !$errors->has('debitor_street_number') ? 'autofocus' : '' }}
                >
                <label class="text-field-label">{{ __('index.debitor_street_number') }}</label>
            </div>
            @if($errors->has('debitor_street_number'))
                <div class="text-field-validation">
                    {{ $errors->first('debitor_street_number') }}
                </div>
            @endif
        </div>

        <div class="d-flex flex-column mb-4 col-2 pe-2">
            <div class="text-field {{ $errors->has('debitor_post_code') ? 'invalid' : '' }}">
                <input
                    name="debitor_post_code"
                    class="text-field-input"
                    value="{{ (!$errors->has('debitor_post_code') && $errors->any()) ? old('debitor_post_code') : '' }}"
                    type="text"
                    required
                    {{ !$errors->has('debitor_post_code') ? 'autofocus' : '' }}
                >
                <label class="text-field-label">{{ __('index.debitor_post_code') }}</label>
            </div>
            @if($errors->has('debitor_post_code'))
                <div class="text-field-validation">
                    {{ $errors->first('debitor_post_code') }}
                </div>
            @endif
        </div>

        <div class="d-flex flex-column mb-4 col-7 pe-2 ps-2">
            <div class="text-field {{ $errors->has('debitor_city') ? 'invalid' : '' }}">
                <input
                    name="debitor_city"
                    class="text-field-input"
                    value="{{ (!$errors->has('debitor_city') && $errors->any()) ? old('debitor_city') : '' }}"
                    type="text"
                    required
                    {{ !$errors->has('debitor_city') ? 'autofocus' : '' }}
                >
                <label class="text-field-label">{{ __('index.debitor_city') }}</label>
            </div>
            @if($errors->has('debitor_city'))
                <div class="text-field-validation">
                    {{ $errors->first('debitor_city') }}
                </div>
            @endif
        </div>

        @include('partials/country', ['name' => 'debitor_country', 'countries' => $countries])
    </div>
</div>
