<?php namespace App\Traits\Controllers\Admin; ?>
<?php

use App\Lib\Auth;
use Illuminate\Http\RedirectResponse;

trait DeleteAdminTrait
{
    public function delete(int $id):RedirectResponse
    {
        if (!Auth::isLogIn())
            return Auth::redirectToLogInResponse();
        //get item from db
        $this->adminItem = $this->admin->findItemForDelete($id);
        // check exist
        if(is_null($this->adminItem))
            return $this->failFoundModel();
        // delete admin with tokens !!!
        $deleted = $this->adminItem->deleteWithAccessTokens();
        // is delete
        return $this->deleteChecker($deleted);
    }
    private function deleteChecker(bool $isDelete):RedirectResponse
    {
        if ($isDelete)
            return $this->successfullyDeleted();
        else
            return $this->failDeleted();
    }
    private function successfullyDeleted():RedirectResponse
    {
        $this->successfullyDeleteAdminMessage();
        return $this->redirectToBackWithSystemMessages();
    }
    private function successfullyDeleteAdminMessage():void
    {
        $this->systemMessages
            ->push(
                collect([
                    'status'=>true ,
                    'message'=>$this->adminItem->nick.'با موفقیت به سطل اشغال انتقال یافت !!'
                ])
            );
    }
    private function failDeleted():RedirectResponse
    {
        $this->setFailDeleteMessage();
        return $this->redirectToBackWithSystemMessages();
    }
    private function setFailDeleteMessage()
    {
        $this->systemMessages
            ->push(
                collect([
                    'status'=>false ,
                    'message'=>'ناموفق در انتقال'.$this->adminItem->nick.'به سطل اشغال'
                ])
            );
    }
}
