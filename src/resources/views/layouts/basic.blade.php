<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>@yield('title')</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700&display=swap" rel="stylesheet">
        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="stylesheet" href="/css/app.css">
    </head>

    <body>
        <div class="d-flex flex-column align-items-center w-90">
            <div class="color-gray-700 text-center p-4">
                <span class="fs-1 fw-normal">
                    @yield('text-medium')
                </span>
            </div>
            <div class="shadow p-4 bg-body rounded-3 width-1020">
                @yield('content')
            </div>
        </div>
        <div class="position-absolute bottom-0 end-0 me-4 mb-2">
            <a class="fs-6 fw-normal" href="{{ __('index.footer_link') }}" target="_blank">
                {{ __('index.footer_info') }}
            </a>
        </div>
        <script type="text/javascript" src="/js/app.js"></script>
    </body>
</html>
