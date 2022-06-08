<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials/fonts')
    <link rel="stylesheet" href="{{ resource_path('css/qrCode.css') }}" type='text/css'>
</head>
<body>

<main>
    <div class="container d-block">
        <div class="empty-block"></div>
        <div class="qr-code" style="background-image: url({{ resource_path('images/qr_cutbackground.png') }});">
            <div class="main-block">
                <div class="left-side font-regular font-size-8pt">
                    <div class="font-bold font-size-11pt">
                        {{ __('qrcode.receipt') }}
                    </div>
                    <div class="font-bold font-size-6pt mt-3">
                        {{ __('qrcode.account') }}
                    </div>
                    <div>
                        <p>{{chunk_split($data->iban, 4, ' ')}}</p>
                        <p>{{ $data->creditor_name }}</p>
                        <p>{{ $data->creditor_street }} {{ $data->creditor_street_number }}</p>
                        <p>{{ $data->creditor_post_code }}  {{ $data->creditor_city }}</p>
                    </div>
                    @if ($data->type->value === $qrr)
                        <div class="mt-3 font-bold font-size-6pt">
                            {{ __('qrcode.reference') }}
                        </div>
                        <div>
                            <p>{{ $qrReference }}</p>
                        </div>
                    @endif
                    <div class="font-bold font-size-6pt mt-3">
                        {{ __('qrcode.payableby') }}
                    </div>
                    <div>
                        <p>{{ $data->debitor_name }}</p>
                        <p>{{ $data->debitor_street }} {{ $data->debitor_street_number }}</p>
                        <p>{{ $data->debitor_post_code }} {{ $data->debitor_city }}</p>
                    </div>
                    @if ($data->type->value === $non)
                        <div class="mt-3 font-bold font-size-6pt d-hide">
                            .
                        </div>
                        <div>
                            <p class="d-hide">.</p>
                        </div>
                    @endif
                    <div class="summary d-block">
                        <div class="currency d-inline">
                            <p class="font-bold font-size-6pt">{{ __('qrcode.currency') }}</p>
                            <p class="pt-1 line-height-7">{{ $data->currency->value }}</p>
                        </div>
                        <div class="amount d-inline">
                            <p class="font-bold font-size-6pt">{{ __('qrcode.amount') }}</p>
                            <p class="pt-1 line-height-7">{{ $amount }}</p>
                        </div>
                    </div>
                    <div class="acceptance font-bold font-size-6pt right d-block">
                        {{ __('qrcode.acceptancepoint') }}
                    </div>
                </div>
                <div class="right-side">
                    <div class="qr-block">
                        <div class="ml-3 font-bold font-size-11pt">
                            {{ __('qrcode.paymentpart') }}
                        </div>
                        <div class="qr-code-img">
                            <img src="data:image/png;base64, {{ $qrCode }}" />
                        </div>
                        <div class="ml-3 summary d-block">
                            <div class="currency d-inline">
                                <p class="font-bold font-size-8pt">{{ __('qrcode.currency') }}</p>
                                <p class="font-regular font-size-10pt pt-1 line-height-7">{{ $data->currency->value }}</p>
                            </div>
                            <div class="amount d-inline">
                                <p class="font-bold font-size-8pt">{{ __('qrcode.amount') }}</p>
                                <p class="font-regular font-size-10pt pt-1 line-height-7">{{ $amount }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="information-block d-inline font-regular font-size-10pt">
                        <div class="font-bold font-size-8pt">
                            {{ __('qrcode.account') }}
                        </div>
                        <div>
                            <p>{{ chunk_split($data->iban, 4, ' ') }}</p>
                            <p>{{ $data->creditor_name }}</p>
                            <p>{{ $data->creditor_street }} {{ $data->creditor_street_number }}</p>
                            <p>{{ $data->creditor_post_code }}  {{ $data->creditor_city }}</p>
                        </div>
                        @if ($data->type->value === $qrr)
                            <div class="mt-3 font-bold font-size-8pt">
                                {{ __('qrcode.reference') }}
                            </div>
                            <div>
                                <p>{{ $qrReference }}</p>
                            </div>
                        @endif
                        <div class="mt-3 font-bold font-size-8pt">
                            {{ __('qrcode.additionalinformation') }}
                        </div>
                        <div>
                            <p>{{ $data->additional_information }}</p>
                        </div>
                        <div class="font-bold font-size-8pt mt-3">
                            {{ __('qrcode.payableby') }}
                        </div>
                        <div>
                            <p>{{ $data->debitor_name }}</p>
                            <p>{{ $data->debitor_street }} {{ $data->debitor_street_number }}</p>
                            <p>{{ $data->debitor_post_code }} {{ $data->debitor_city }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
</body>
</html>
