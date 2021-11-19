<?php namespace App\Traits\Controllers\Admin ; ?>
<?php

use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Admin\Management\DoUpdateAdminRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use App\Models\AdminAccessToken;

trait UpdateAdminTrait
{
    private Collection $oldAdminData;
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function update(DoUpdateAdminRequest $request , $id):RedirectResponse
    {

        //@todo delete token when change password and change email and phone
        $this->request = $request;
        $this->adminItem =
            $this->
            admin->
            find($id,['id','thumbnail','username','email','phone']) ;
        $this->setAdminDataToOldData();
        $this->setStoreDataToDataRequest();

        //  check has thumbnail
            if ($this->hasThumbnail())
               $this->requestData->put('thumbnail',$this->thumbnailUploader());
            //upload thumbnail

        // insert data into forms from model
        $this->setUpdateFormDataToModel();

        //update
        $updated = $this->adminItem->update();

        return $this->updateChecker($updated);

    }
    private function setAdminDataToOldData():void
    {
        $this->oldAdminData = collect([
            'thumbnail'=>$this->adminItem->getAttribute('thumbnail') ,
            'email'=>$this->adminItem->getAttribute('email') ,
            'phone'=>$this->adminItem->getAttribute('phone') ,
            'username'=>$this->adminItem->getAttribute('username') ,
        ]);
    }
    private function setUpdateFormDataToModel():void
    {
        if (is_null($this->requestData->get('password')))
            $this->requestData->forget('password');
        else
            $this->requestData->put(
                'password',
                Hash::make($this->requestData->get('password'))
            );

        $this->requestData->each(function ($item,$itemKey){
            $this->adminItem->setAttribute($itemKey,$item);
        });

        $this->adminItem->updated_at = Carbon::now();
    }
    private function updateChecker(bool $updated):RedirectResponse
    {
        if ($updated)
            return $this->successfullyUpdated();
        else
            return $this->failUpdated();
    }
    private function successfullyUpdated():RedirectResponse{
        $this->setSuccessfullyUpdatedMessage();
        $this->deleteOldThumbnail();
        $this->deleteAccessTokens();
        return $this->redirectToBackWithSystemMessages();
    }
    private function setSuccessfullyUpdatedMessage():void
    {
        $this->systemMessages
            ->push(
                collect([
                    'status'=>true ,
                    'message'=>'مدل با موفقیت تغییر یافت !!'
                ])
            );
    }
    private function deleteOldThumbnail():void
    {
        if (!is_null($this->oldAdminData->get('thumbnail')) and $this->hasThumbnail())
            $this->deleteThumbnail($this->oldAdminData->get('thumbnail'));
    }
    private function failUpdated():RedirectResponse
    {
        $this->setFailUpdateMessage();
        $this->deleteNewThumbnail();
        return $this->redirectToBackWithSystemMessageAndInputes();
    }
    private function setFailUpdateMessage():void
    {
        $this->systemMessages
            ->push(
                collect([
                    'status'=>false ,
                    'message'=>'متاسفانه مدل اپدیت نشد !!' ,
                ])
            );
    }
    private function deleteNewThumbnail():void
    {
        if ($this->hasThumbnail())
            $this->deleteThumbnail($this->adminItem->thumbnail);
    }
    private function getInputDataForFailUpdate():array
    {
        $this->requestData->forget(['password','thumbnail']);
        if ($this->requestData->get('active')===false)
            $this->requestData->forget(['active']);
        return  $this->requestData->toArray();
    }
    private function redirectToBackWithSystemMessageAndInputes():RedirectResponse
    {
        $inputes =  $this->getInputDataForFailUpdate();
        return $this->redirectToBackWithSystemMessages()
                ->withInput($inputes);
    }
    private function hasImportantChange():bool
    {
        $changes = [
            !is_null($this->adminItem->password) ,
            $this->oldAdminData->get('email')!=$this->adminItem->getAttribute('email') ,
            $this->oldAdminData->get('username')!=$this->adminItem->getAttribute('username') ,
            $this->oldAdminData->get('phone')!=$this->adminItem->getAttribute('phone') ,
            !$this->adminItem->active
        ];
        return in_array(true,$changes);
    }
    private function deleteAccessTokens():void
    {
            $hasChanges = $this->hasImportantChange();
            if ($hasChanges)
            {
                $accessToken = new AdminAccessToken();
                    $accessToken->FindForDeleteByOwnerId($this->adminItem->id)->delete();
            }
    }
}
?>
