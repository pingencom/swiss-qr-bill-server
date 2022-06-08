<div class="row col-6 me-2">
    <div class="p-2 fs-5 mb-2 fw-normal color-gray-800">{{ __('index.payment_details') }}</div>

    <div class="d-flex flex-wrap">
        <div class="d-flex flex-column mb-4 col-6 pe-2">
            <div class="text-field {{ $errors->has('iban') ? 'invalid' : '' }}">
                <input
                    name="iban"
                    class="text-field-input"
                    value="{{ (!$errors->has('iban') && $errors->any()) ? old('iban') : '' }}"
                    type="text"
                    required
                    {{ !$errors->has('iban') ? 'autofocus' : '' }}
                >
                <label class="text-field-label">{{ __('index.iban') }}</label>
            </div>
            @if($errors->has('iban'))
                <div class="text-field-validation">
                    {{ $errors->first('iban') }}
                </div>
            @endif
        </div>

        <div class="d-flex flex-column mb-4 col-3 pe-2 ps-2">
            <div class="text-field {{ $errors->has('total_amount') ? 'invalid' : '' }}">
                <input
                    name="total_amount"
                    class="text-field-input"
                    value="{{ (!$errors->has('total_amount') && $errors->any()) ? old('total_amount') : '' }}"
                    type="text"
                    required
                    {{ !$errors->has('total_amount') ? 'autofocus' : '' }}
                >
                <label class="text-field-label">{{ __('index.total_amount') }}</label>
            </div>
            @if($errors->has('total_amount'))
                <div class="text-field-validation">
                    {{ $errors->first('total_amount') }}
                </div>
            @endif
        </div>

        <div class="d-flex flex-column mb-2 col-3 ps-2 mt-2">
            <label class="position-relative" for="currency">
                <select class="form-select" name="currency" required>
                    @foreach($currencies as $currency)
                        <option
                            @if ($errors->any() && $currency->value === old('currency'))
                                selected="selected"
                            @elseif ($currency->value === $defaultCurrency->value)
                                selected="selected"
                            @endif
                            value="{{ $currency->value }}">{{ $currency->name }}
                        </option>
                    @endforeach
                </select>
            </label>
        </div>

        <div class="d-flex flex-column mb-4 col-9">
            <div class="text-field {{ $errors->has('additional_information') ? 'invalid' : '' }}">
                <input
                    name="additional_information"
                    class="text-field-input"
                    value="{{ (!$errors->has('additional_information') && $errors->any()) ? old('additional_information') : '' }}"
                    type="text"
                    {{ !$errors->has('additional_information') ? 'autofocus' : '' }}
                >
                <label class="text-field-label">{{ __('index.additional_information') }}</label>
            </div>
            @if($errors->has('additional_information'))
                <div class="text-field-validation">
                    {{ $errors->first('additional_information') }}
                </div>
            @endif
        </div>

        <div class="d-flex flex-column mb-2 col-3 ps-2 mt-2">
            <label class="position-relative" for="type">
                <select class="form-select" name="language" id="language" required>
                    @foreach($languages as $language)
                        <option
                            @if ($errors->any() && $language->value === old('language'))
                                selected="selected"
                            @elseif ($language->value === $defaultLanguage->value)
                                selected="selected"
                            @endif
                            value="{{ $language->value }}">{{ $language->name }}
                        </option>
                    @endforeach
                </select>
            </label>
        </div>

        <div class="d-flex flex-column mb-2 col-4 mt-2">
            <label class="position-relative" for="type">
                <select class="form-select" name="type" id="type" required>
                    @foreach($types as $type)
                        <option
                            @if ($errors->any() && $type->value === old('type'))
                                selected="selected"
                            @elseif ($type->value === $defaultType->value)
                                selected="selected"
                            @endif
                            value="{{ $type->value }}">{{ __('index.type.' . $type->name) }}
                        </option>
                    @endforeach
                </select>
            </label>
        </div>

        <div id="paymentReference" class="d-flex flex-column mb-4 ps-4 col-8">
            <div class="text-field {{ $errors->has('reference') ? 'invalid' : '' }}">
                <input
                    name="reference"
                    class="text-field-input"
                    value="{{ (!$errors->has('reference') && $errors->any()) ? old('reference') : '' }}"
                    type="number"
                    {{ !$errors->has('reference') ? 'autofocus' : '' }}
                >
                <label class="text-field-label">{{ __('index.reference') }}</label>
            </div>
            @if($errors->has('reference'))
                <div class="text-field-validation">
                    {{ $errors->first('reference') }}
                </div>
            @endif
        </div>
    </div>
</div>
