<div class="row col-6 ms-2 flex-column">
    <div class="p-2 fs-5 mb-2 fw-normal color-gray-800">{{ __('index.pdf_mange') }}</div>

    <div class="d-flex flex-wrap">
        <div class="form-group col-12">
            <div class="dropzone-wrapper">
                <div class="d-flex flex-column align-items-center p-4 fs-12">
                    <svg class="w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    <p id="fileName" class="mt-2">{{ __('index.upload_file_description') }}</p>
                </div>
            </div>
            <input id="file" name="file" class="text-field-input d-none" type="file" value="upload" />
        </div>

        <div class="row col-12 mt-4">
            <div id="mode" class="d-none col-6 flex-column">
                <label class="text-field-label">{{ __('index.file_mode') }}</label>
                <label class="position-relative" for="file_mode">
                    <select id="selected-mode" class="form-select" name="file_mode">
                        @foreach($fileModes as $fileMode)
                            <option
                                @if ($errors->any() && $fileMode->value === old('file_mode'))
                                    selected="selected"
                                @elseif ($fileMode->value === $defaultFileMode->value)
                                    selected="selected"
                                @endif
                                value="{{ $fileMode->value }}">{{ __('index.file_mode.' . $fileMode->name) }}
                            </option>
                        @endforeach
                    </select>
                </label>
            </div>

            <div class="col-2">
                <div id="whichPage" class="d-none mt-3">
                    <div class="text-field">
                        <input
                            name="file_overlay_page"
                            class="text-field-input"
                            type="text"
                        >
                        <label class="text-field-label">{{ __('index.file_overlay_page') }}</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="pdf-info {{ $errors->has('file') || $errors->has('file_mode') || $errors->has('file_overlay_page') ? 'error' : '' }}">
        @if($errors->has('file'))
            <div class="text-field-validation">
                {{ $errors->first('file') }}
            </div>
        @endif
        @if($errors->has('file_mode'))
            <div class="text-field-validation">
                {{ $errors->first('file_mode') }}
            </div>
        @endif
        @if($errors->has('file_overlay_page'))
            <div class="text-field-validation">
                {{ $errors->first('file_overlay_page') }}
            </div>
        @endif
    </div>
</div>
