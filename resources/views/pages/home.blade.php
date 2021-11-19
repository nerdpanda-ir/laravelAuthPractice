@extends('layouts.mdb')
@section('body')
    @printSystemMessages
    @if($sliders->isNotEmpty())
        <x-carousel.carousel :sliders="$sliders" id="carousel-example-1z"/>
    @else
        <section class="alert alert-danger text-center rtl" role="alert">هیچ اسلایدی موجود نیست !! </section>
    @endif
@endsection
