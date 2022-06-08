@extends('layouts.basic')

@section('title', __('index.title'))

@section('text-medium', __('index.form.info'))

@section('content')

    <form method="post" class="p-4" action="{{ route('sendRequest') }}" enctype="multipart/form-data">
        <div class="d-flex">
            @include('partials/creditor')
            @include('partials/debitor')
        </div>
        <div class="d-flex mt-3">
            @include('partials/paymentsDetails')
            @include('partials/pdfManage')
        </div>
        <div class="d-flex justify-content-center">
            <button class="button submit form-button" type="submit">{{__('index.generate')}}</button>
        </div>
    </form>
@endsection
