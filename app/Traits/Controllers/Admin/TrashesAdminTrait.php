<?php namespace App\Traits\Controllers\Admin;  ?>
<?php

use App\Lib\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

trait TrashesAdminTrait
{
    public function trashes():View|RedirectResponse
    {
        if (!Auth::isLogIn())
            return Auth::redirectToLogInResponse();
        $data = $this->getTrashesData();
        return \view('pages.admin.management.trashes',$data);
    }
    private function getTrashesData():array
    {
        /** @var LengthAwarePaginator $items*/
        $items = $this->admin->TrashedItems()->paginate(8);
        $columns = [];
        if ($items->isNotEmpty())
            $columns = $this->getModelColumns($items->first())->toArray();
        return  compact('items','columns');
    }
}
