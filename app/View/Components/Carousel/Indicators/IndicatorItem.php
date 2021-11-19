<?php

namespace App\View\Components\Carousel\Indicators;

use Illuminate\View\Component;

class IndicatorItem extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.carousel.indicators.indicator-item');
    }
}
