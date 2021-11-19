<?php namespace App\Traits\Controllers\Admin;?>
<?php

use App\Lib\Auth;
use App\Models\Admin;
use Illuminate\Http\RedirectResponse;

trait DestroyAdminTrait
{
    public function destroy(int $id):RedirectResponse
    {
        if (!Auth::isLogIn())
            return Auth::redirectToLogInResponse();
        /** @var null|Admin $item */
        $this->adminItem = $this->admin->findForDestroy($id);
        if (is_null($this->adminItem))
            return $this->failFoundModel();

        /** @var bool $deleted*/
        $deleted = $this->adminItem->forceDelete();
        return $this->destroyChecker($deleted);
    }

    private function successfullyDestroyMessage():void
    {
        $this->systemMessages
            ->push(
                collect([
                    'status'=>true ,
                    'message'=>'مدل مورد نظر '.$this->adminItem->nick.'با موفقیت برای همیشه از سیستم حذف شد !!!' ,
                ])
            );
    }
    private function successfullyDestroyModel():RedirectResponse
    {
        $this->deleteThumbnail($this->adminItem->thumbnail);
       $this->successfullyDestroyMessage();
        return $this->redirectToBackWithSystemMessages();
    }
    private function failDestroyModel():RedirectResponse
    {
        $this->failDestroyMessage();
        return $this->redirectToBackWithSystemMessages();
    }
    private function failDestroyMessage():void
    {
        $this->systemMessages
            ->push(
                collect([
                    'status'=>false ,
                    'message'=>'ناموفق در حذف ایتم'.$this->adminItem->nick.'!!!'
                ])
            );
    }
    private function destroyChecker(bool $deleted):RedirectResponse
    {

        if ($deleted)
            return $this->successfullyDestroyModel();
        else
            return $this->failDestroyModel();
    }
}
