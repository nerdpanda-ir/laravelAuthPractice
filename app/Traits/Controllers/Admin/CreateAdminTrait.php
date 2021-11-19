<?php namespace App\Traits\Controllers\Admin ; ?>
<?php

use App\Lib\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

trait CreateAdminTrait
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create():View|RedirectResponse
    {
        if (Auth::isLogIn())
            return \view('pages.admin.management.create');
        return Auth::redirectToLogInResponse();
    }
}
