@aware(['sliders'])
<!-- Indicators -->
<ol class="carousel-indicators">
    @foreach($sliders->all() as $slider)
        @if($loop->index===0)
        <x-carousel.indicators.indicator-item :data-target="'#'.$attributes->get('id')" data-slide-to="{!! $loop->index !!}" class="active"/>
        @else
            <x-carousel.indicators.indicator-item :data-target="'#'.$attributes->get('id')" data-slide-to="{!! $loop->index !!}" class=""/>
        @endif
    @endforeach

   {{-- <li data-target="#carousel-example-1z" data-slide-to="0" class="active"></li>

    <li data-target="#carousel-example-1z" data-slide-to="1" class=""></li>

    <li data-target="#carousel-example-1z" data-slide-to="2" class=""></li>--}}
</ol>
<!-- Indicators -->
