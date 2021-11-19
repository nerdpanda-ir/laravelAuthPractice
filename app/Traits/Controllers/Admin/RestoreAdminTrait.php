<?php namespace App\Traits\Controllers\Admin ; ?>
<?php

use App\Lib\Auth;
use Illuminate\Http\RedirectResponse;

trait RestoreAdminTrait
{
    public function restore(int $id):RedirectResponse
    {
        if (!Auth::isLogIn())
            return Auth::redirectToLogInResponse();
        //find item from database
        $this->adminItem = $this->admin->getItemFromTrashed($id);

        if (is_null($this->adminItem))
            return $this->failFoundModel();

        $restored =$this->adminItem->restore();

        return $this->restoreChecker($restored);
    }
    private function restoreChecker(bool $restored):RedirectResponse
    {
        if ($restored)
            return $this->successfullyRestored();
        else
            return $this->failRestored();
    }
    private function successfullyRestored():RedirectResponse
    {
        // set message
        $this->setSuccessfullyRestoreMessage();
        //redirect
        return $this->redirectToIndexManagementWithSystemMessages();
    }
    private function setSuccessfullyRestoreMessage():void
    {
        $this->systemMessages
            ->push(
                collect([
                    'status'=>true ,
                    'message'=>$this->adminItem->getAttribute('username').'با موفقیت از سطل اشغال در امد !!!'
                ])
            );
    }
    private function failRestored():RedirectResponse
    {
        $this->setFailRestoreMessage();
        return $this->redirectToIndexManagementWithSystemMessages();
    }

    private function setFailRestoreMessage():void
    {
        $this->systemMessages->push(collect([
            'status'=>false ,
            'message'=>'متاسفانه ایتم '.$this->adminItem->username.'نتوانست از اشغال ها در بیاید !!! لطفا بعدا تلاش نمایید !!!' ,
        ]));
    }
}
