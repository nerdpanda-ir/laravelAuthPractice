<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DoLogInRequest ;
use App\Models\Admin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use App\Lib\AdminAccessTokenController;
use Illuminate\Support\Facades\Session;
use App\Lib\Auth;
class LogInController extends Controller
{
    private Collection $systemMessages;
    private Admin $item;
    private array $accessCheckResults;
    public function __construct()
    {
        $this->systemMessages=collect();
    }

    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application|RedirectResponse
    {
        if (!Auth::isLogIn())
            return view('pages.admin.login');
        return Auth::redirectToAdminDashResponse();
    }
    public function doLogIn(DoLogInRequest $request , Admin $admin)
    {
        // login only -> active user , email verified , phone verified
        $data = $request->except('_token','_method');
        $this->item = $admin->findByEmail(
                $data['email'],['password','nick','id' , 'active','email_verified_at','phone_verified_at']
            );
        $this->putAccessResultsFromModel();

        /*if (is_bool($this->item) and !$this->item)
            return $this->failLogIn();*/


        if (Hash::check($data['password'] , $this->item->password))
            return $this->successfullyLogIn();
        else
            return $this->failLogIn();
    }
    private function failLogIn():RedirectResponse
    {
        $this->setFailMessageToSystemMessages();
        return $this->redirectToLogIn();
    }
    private function setFailMessageToSystemMessages()
    {
        $this->systemMessages->push(
            collect([
                'status'=>false ,
                'message'=>'ایمیل یا پسورد اشتب است !!'
            ])
        );
    }
    private function successfullyLogIn():RedirectResponse {
        if ($this->hasAccessToPanel())
            return $this->prepareForLogIn();
        return $this->noAccessToPanel();
    }
    private function setSuccessfullyLogInToSystemMessages()
    {
        $this->systemMessages->push(
            collect([
                'status'=>true ,
                'message'=> $this->item->nick . 'خوش امدید مشرف فرمودید !!' ,
            ])
        );
    }

    private function redirectToAdminDashboard():RedirectResponse
    {
        return redirect()
            ->route('admin.dashboard')
            ->with('systemMessages',$this->systemMessages);
    }
    private function redirectToLogIn():RedirectResponse
    {
        return redirect()
            ->back()
            ->with('systemMessages',$this->systemMessages)
            ->withInput(['email'=>request()->get('email')]);
    }
    private function putAccessResultsFromModel():void
    {
        $this->accessCheckResults= [
            'active'=> $this->item->getAttribute('active') ,
            'emailVerify' => $this->item->getAttribute('email_verified_at') ,
            'phoneVerify'=> $this->item->getAttribute('phone_verified_at') ,
        ];
    }
    private function hasAccessToPanel():bool
    {
        return !in_array(false,$this->accessCheckResults);
    }
    private function prepareForLogIn():RedirectResponse
    {
        AdminAccessTokenController::make($this->item->id);
        Session::put('admin.accessToken',AdminAccessTokenController::getToken());
        $this->setSuccessfullyLogInToSystemMessages();
        return $this->redirectToAdminDashboard();
    }
    private function setNoAccessMessage():void
    {
        if (!$this->accessCheckResults['active'])
            $this->setNoActiveMessage();
        else
            if (!$this->accessCheckResults['emailVerify'])
                $this->setNoEmailVerifyMessage();
            else
                $this->setNoPhoneVerifyMessage();

    }
    private function setNoActiveMessage():void
    {
        $this->systemMessages
            ->push(collect([
                'status'=>false ,
                'message'=>'شما از سیستم بن شده اید !!! ' ,
            ]));
    }
    private function setNoEmailVerifyMessage():void
    {
        $this->systemMessages
            ->push(collect([
                'status'=>false ,
                'message'=>'تازمانیکه ایمیل را تایید نکنید اجازه ورود ندارید !! !!! ' ,
            ]));
    }

    private function setNoPhoneVerifyMessage():void
    {
        $this->systemMessages
            ->push(
                collect([
                    'status'=>false ,
                    'message'=>'تازمانیکه شماره تلفن خود را تایید نکنید اجازه ورود ندارید !!',
                ])
            );
    }

    private function noAccessToPanel():RedirectResponse
    {
        $this->setNoAccessMessage();
        return $this->redirectToLogIn();
    }
}
