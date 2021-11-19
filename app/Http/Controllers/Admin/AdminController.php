<?php

namespace App\Http\Controllers\Admin;

use App\Lib\Auth;
use App\Models\Admin;
use App\Traits\Controllers\Admin\CreateAdminTrait;
use App\Traits\Controllers\Admin\DeleteAdminTrait;
use App\Traits\Controllers\Admin\DestroyAdminTrait;
use App\Traits\Controllers\Admin\RestoreAdminTrait;
use App\Traits\Controllers\Admin\StoreAdminTrait;
use App\Traits\Controllers\Admin\TrashesAdminTrait;
use App\Traits\Controllers\Admin\UpdateAdminTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use \App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\View\View;
use \Illuminate\Support\Collection as RealCollection;
use App\Traits\Controllers\Admin\IndexAdminTrait;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Traits\Controllers\Admin\EditAdminTrait;
class AdminController extends Controller
{
    use IndexAdminTrait;
    use DestroyAdminTrait;
    use DeleteAdminTrait;
    use CreateAdminTrait;
    use StoreAdminTrait;
    use EditAdminTrait;
    use UpdateAdminTrait;
    use TrashesAdminTrait;
    use RestoreAdminTrait ;

    private Admin $admin;
    private Admin|null $adminItem;
    private RealCollection $systemMessages;
    private Request $request;
    private string $thumbnailKey = 'thumbnail';
    private string $thumbnailRootDestination;
    private RealCollection $requestData;
    public function __construct(Admin $admin)
    {
        $this->admin=  $admin;
        $this->systemMessages=collect();
        $this->thumbnailRootDestination = public_path('media').DIRECTORY_SEPARATOR;

    }

    private function getModelColumns(Model $model,array $expect=[]):RealCollection
    {

        $attributes = $model->getAttributes();
        $keys = collect(array_keys($attributes));
        return  $keys->except($expect);
    }
    private function setNotFoundModelMessageToSystemMessages():void
    {
        $this->systemMessages->push(
            collect([
                'status'=>false  ,
                'message'=>'ایتم مورد نظر یافت نشد !!' ,
            ])
        );
    }
    private function failFoundModel():RedirectResponse
    {
        $this->setNotFoundModelMessageToSystemMessages();
        return $this->redirectToBackWithSystemMessages();
    }



    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function show(Admin $admin)
    {
        //
    }





    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */


    private  function redirectToBackWithSystemMessages():RedirectResponse
    {
        return Redirect::back()
            ->with('systemMessages',$this->systemMessages);
    }
    private function redirectToIndexManagementWithSystemMessages():RedirectResponse
    {
        return Redirect::route('admin.management.index')
                ->with('systemMessages',$this->systemMessages);
    }

    private function hasThumbnail():bool
    {
        return $this
            ->request
            ->hasFile($this->thumbnailKey);
    }
    private function getThumbnail():UploadedFile
    {
        return $this->request->file($this->thumbnailKey);
    }
    private function thumbnailUploader():string
    {
        //make destination
        $destination = $this->thumbnailRootDestination.Str::random(8);
        mkdir($destination);
        // upload file to destination
        $thumbnail = $this->getThumbnail();
        /**@var File*/
        $uploadedFile = $thumbnail->move($destination,$thumbnail->getClientOriginalName());
        $pathName = $uploadedFile->getPathname();
        $result = str_replace(public_path(),'',$pathName);
        return $result;
    }
    private function deleteThumbnail(string $thumbnailFile):void
    {
        $fileSystem = new Filesystem();
        $thumbnailDir =  dirname($thumbnailFile);
        $uniqueDestination = pathinfo($thumbnailDir,PATHINFO_BASENAME);
        $fullPath = $this->thumbnailRootDestination.$uniqueDestination;
        $fileSystem->deleteDirectory($fullPath);
    }
    private function setStoreDataToDataRequest():void
    {
        $result =
            $this->
            request->except('_method','_token','password_confirmation','active');

        if (!$this->request->has('active'))
            $result['active']=false;
        else
            $result['active']=true;

        $this->requestData = collect($result);
    }
}
