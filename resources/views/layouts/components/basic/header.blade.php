<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        @section('title')
            Nerd Panda
        @show
    </title>

    <!-- Css Start -->

@section('css')
@show
@stack('css')

<!-- Css End -->

    <!-- JavaScript Header Start -->
@section('jsHeader')
@show
@stack('jsHeader')
<!-- JavaScript Header End -->
</head>
<body @stack('bodyAttrs')>
