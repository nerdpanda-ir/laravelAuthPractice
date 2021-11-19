<?php namespace App\Traits\Controllers\Admin;?>
<?php

use App\Lib\Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

trait IndexAdminTrait
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index():RedirectResponse|View
    {
        if (!Auth::isLogIn())
            return Auth::redirectToLogInResponse();
        //get admins from database !!!
        $data = $this->getIndexData();
        //return view
        return \view('pages.admin.management.index',$data);
    }
    private function getIndexData():array
    {
        $columns = [];
        /** @var Collection $admins*/
        $admins =
            $this->admin
                ->getAllAdminsForIndexView();
        if ($admins->count()>0)
            $columns =$this->getModelColumns($admins->first(),[0]);
        return compact('admins','columns');
    }
}
