<?php namespace App\Traits\Controllers\Admin; ?>
<?php

use App\Lib\Auth;
use App\Models\Admin;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

trait EditAdminTrait
{
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id):View|RedirectResponse
    {
        if (!Auth::isLogIn())
            return Auth::redirectToLogInResponse();

        $this->adminItem = $this->admin->getFindItemForEditView($id);
        if (is_null($this->adminItem))
        {
            $this->setNotFoundModelMessageToSystemMessages();
            return $this->redirectToIndexManagementWithSystemMessages();
        }
        return \view('pages.admin.management.edit',['item'=>$this->adminItem->getAttributes()]);
    }
}
