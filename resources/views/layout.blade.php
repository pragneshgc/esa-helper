<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') ? config('app.name') : '' }}</title>

    <link rel="stylesheet" href="{{ url('vendor/fontawesome/css/all.min.css') }}" />
    <link rel="stylesheet" href="{{ url('vendor/esa/style.css') }}" />
</head>

<body>
    <div id="esa-helper" v-cloak>
        <esa-helper />
    </div>

    <script type="module" src="{{ url('vendor/esa/app.js') }}"></script>
</body>

</html>
