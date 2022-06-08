<div class="row col-6 me-2">
    <div class="p-2 fs-5 mb-2 fw-normal color-gray-800">{{ __('index.creditor_info') }}</div>

    <div class="d-flex flex-wrap">
        <div class="d-flex flex-column mb-4 col-12">
            <div class="text-field {{ $errors->has('creditor_name') ? 'invalid' : '' }}">
                <input
                    name="creditor_name"
                    class="text-field-input"
                    value="{{ (!$errors->has('creditor_name') && $errors->any()) ? old('creditor_name') : '' }}"
                    type="text"
                    required
                    {{ !$errors->has('creditor_name') ? 'autofocus' : '' }}
                >
                <label class="text-field-label">{{ __('index.creditor_name') }}</label>
            </div>
            @if($errors->has('creditor_name'))
                <div class="text-field-validation">
                    {{ $errors->first('creditor_name') }}
                </div>
            @endif
        </div>

        <div class="d-flex flex-column mb-4 col-9 pe-2">
            <div class="text-field {{ $errors->has('creditor_street') ? 'invalid' : '' }}">
                <input
                    name="creditor_street"
                    class="text-field-input"
                    value="{{ (!$errors->has('creditor_street') && $errors->any()) ? old('creditor_street') : '' }}"
                    type="text"
                    required
                    {{ !$errors->has('creditor_street') ? 'autofocus' : '' }}
                >
                <label class="text-field-label">{{ __('index.creditor_street') }}</label>
            </div>
            @if($errors->has('creditor_street'))
                <div class="text-field-validation">
                    {{ $errors->first('creditor_street') }}
                </div>
            @endif
        </div>

        <div class="d-flex flex-column mb-4 col-3 ps-2">
            <div class="text-field {{ $errors->has('creditor_street_number') ? 'invalid' : '' }}">
                <input
                    name="creditor_street_number"
                    class="text-field-input"
                    value="{{ (!$errors->has('creditor_street_number') && $errors->any()) ? old('creditor_street_number') : '' }}"
                    type="text"
                    required
                    {{ !$errors->has('creditor_street_number') ? 'autofocus' : '' }}
                >
                <label class="text-field-label">{{ __('index.creditor_street_number') }}</label>
            </div>
            @if($errors->has('creditor_street_number'))
                <div class="text-field-validation">
                    {{ $errors->first('creditor_street_number') }}
                </div>
            @endif
        </div>

        <div class="d-flex flex-column mb-4 col-2 pe-2">
            <div class="text-field {{ $errors->has('creditor_post_code') ? 'invalid' : '' }}">
                <input
                    name="creditor_post_code"
                    class="text-field-input"
                    value="{{ (!$errors->has('creditor_post_code') && $errors->any()) ? old('creditor_post_code') : '' }}"
                    type="text"
                    required
                    {{ !$errors->has('creditor_post_code') ? 'autofocus' : '' }}
                >
                <label class="text-field-label">{{ __('index.creditor_post_code') }}</label>
            </div>
            @if($errors->has('creditor_post_code'))
                <div class="text-field-validation">
                    {{ $errors->first('creditor_post_code') }}
                </div>
            @endif
        </div>

        <div class="d-flex flex-column mb-4 col-7 pe-2 ps-2">
            <div class="text-field {{ $errors->has('creditor_city') ? 'invalid' : '' }}">
                <input
                    name="creditor_city"
                    class="text-field-input"
                    value="{{ (!$errors->has('creditor_city') && $errors->any()) ? old('creditor_city') : '' }}"
                    type="text"
                    required
                    {{ !$errors->has('creditor_city') ? 'autofocus' : '' }}
                >
                <label class="text-field-label">{{ __('index.creditor_city') }}</label>
            </div>
            @if($errors->has('creditor_city'))
                <div class="text-field-validation">
                    {{ $errors->first('creditor_city') }}
                </div>
            @endif
        </div>

        @include('partials/country', ['name' => 'creditor_country', 'countries' => $countries])
    </div>
</div>
