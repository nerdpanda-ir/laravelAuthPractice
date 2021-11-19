@aware(['sliders'])
@php($sliders = $sliders->all())

@foreach($sliders as $slider)
    <div {!! 'class="carousel-item '.(($loop->index===0) ? 'active':'').'"'  !!} >

        <div class="view h-100">

            <img class="d-block h-100 w-lg-100" src="{!! asset($slider->image) !!}" alt="First slide">

            <div class="mask rgba-black-light">

                <!-- Caption -->
                <div class="full-bg-img flex-center white-text">

                    <ul class="list-unstyled animated fadeIn col-10">

                        <li>

                            <h1 class="h1-responsive font-weight-bold">
                                {!! $slider->title !!}
                            </h1>

                        </li>

                        <li>

                            <p>{{$slider->describe}}</p>

                        </li>

                        <li>
                            @isset($slider->uri)
                                <a target="_blank" href="{!! $slider->uri !!}" class="btn btn-info waves-effect waves-light" rel="nofollow">{!! $slider->uri_title ?? 'click' !!}</a>
                            @endisset
                        </li>

                    </ul>

                </div>
                <!-- Caption -->

            </div>

        </div>

    </div>
@endforeach

