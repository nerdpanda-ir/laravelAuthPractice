<?php namespace App\Traits\Controllers\Admin ; ?>
<?php

use App\Models\Admin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\Management\DoStoreAdminRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;


trait StoreAdminTrait
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DoStoreAdminRequest $request , Admin $admin):RedirectResponse
    {
        $this->request = $request;
        $this->setStoreDataToDataRequest();
        $this->adminItem = $admin;
        // check have thumbnail
        if ($this->hasThumbnail())
            $this->requestData->put('thumbnail', $this->thumbnailUploader());
            // upload thumbnail to server
            // get thumbnail destination and save to data

        // set data to model
        $this->setStoreFormDataToModel();

        //apply model to admins
        $created = $this->adminItem->save();
        return $this->createChecker($created);
        //check insert result
            // successfully insert
                //set successfully message to system messages
                // redirect management index
            //fail insert
                // set fail message to system messages
                //redirect to back with old inputs
    }


    private function createChecker(bool $created):RedirectResponse
    {
        if ($created)
            return $this->successfullyCreated();
        else
            return $this->failCreated();
    }
    public function successfullyCreated():RedirectResponse
    {
        $this->successfullyCreatedMessage();
        return $this->redirectToIndexManagementWithSystemMessages();
    }
    private function successfullyCreatedMessage():void
    {
        $this->systemMessages
            ->push(
                collect([
                    'status'=>true ,
                    'message'=>' کاربر  '.$this->request->get('username').'با موفقیت ساخته شد !!' ,
                ])
            );
    }
    private function failCreated():RedirectResponse
    {
        $this->setFailCreatedMessage();
        if ($this->hasThumbnail())
            $this->deleteThumbnail($this->admin->thumbnail);

        return $this->redirectToBackWithSystemMessages()
                ->withInput(
                    $this->getInputDataInFailStore()
                );
    }
    private function setFailCreatedMessage():void
    {
        $this->systemMessages
            ->push(
                collect([
                    'status'=>false ,
                    'message'=> 'متاسفانه کاربر با ایمیل '. $this->request->get('email').'ساخته نشد !!!'
                ])
            );
    }
    private function getInputDataInFailStore():array
    {
        $expectInputes = ['thumbnail'];

        if ($this->requestData->get('active')===false)
            array_push($expectInputes,'active');
        return $this
            ->requestData
            ->except($expectInputes)
            ->toArray();
    }
    private function setStoreFormDataToModel():void
    {
        foreach ($this->requestData as $dataKey=> $dataItem)
            $this->adminItem->setAttribute($dataKey, $dataItem);
        $this->adminItem->setAttribute('password',Hash::make($this->requestData->get('password')));

        $this->adminItem->created_at = Carbon::now();
    }
}
