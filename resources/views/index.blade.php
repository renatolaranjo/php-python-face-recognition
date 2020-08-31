<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="{{ mix('css/app.css') }}" rel="stylesheet">
        <title>Face Recognition</title>
    </head>
    <body>
        <div id="app">
            <app-component></app-component>
        </div>
    </body>
</html>
<script src="{{ mix('js/app.js') }}"></script>
