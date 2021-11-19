<?php

namespace App\View\Components\Layouts\Mdb;

use Illuminate\View\Component;

class JavaScriptFooter extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('layouts.components.mdb.java-script-footer');
    }
}
