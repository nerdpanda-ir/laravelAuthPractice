<?php

namespace App\Http\Controllers;
use App\Lib\Auth;
use App\Models\Slider;
use Illuminate\Support\Facades\Redis;

class PageController extends Controller
{
    /**
     * @param Slider $slider
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function home(Slider $slider)
    {
        $sliders = $slider->getHomePageSliders();
        return view('pages.home',compact('sliders'));
    }
    public function adminDashboard()
    {

        if (Auth::isLogIn())
            return view('pages.admin.dashboard');
        return Auth::redirectToLogInResponse();
    }
}
