@props(['sliders'])
@php($attributes = $attributes->class(['carousel slide carousel-fade']))
<div  {!! $attributes->toHtml() !!} data-ride="carousel">

    <x-carousel.indicators.indicatorsWrapper :id="$attributes->get('id')"/>

    <x-carousel.sliders.sliderWrapper />

    <x-carousel.controls.controlWrapper />
</div>
@section('css')
    @parent
    <style>
        .carousel-inner
        {
            height: 666px;
        }
        .carousel-inner img
        {
          width: 100%;
            height: 100%;
        }
        .carousel-item
        {
            height: inherit !important;
        }
    </style>
@endsection
