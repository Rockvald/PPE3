<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" />
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="icon" sizes="144x144" href="{{ asset('storage/app/public/CCI.png') }}" />
        <link rel="stylesheet" href="{{ asset('resources/css/'.$css.'.css') }}" />
        <script src="{{ asset('resources/js/fonctions.js') }}" type="text/javascript"></script>
        <title>{{ $title_h1 ?? 'CCI' }}</title>
    </head>
    <body>
