<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Lib\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class LogOutController extends Controller
{
    private Collection $systemMessages;
    public function __construct()
    {
        $this->systemMessages= collect();
    }

    public function index():RedirectResponse
    {
        if (Auth::isLogIn())
           return $this->doLogOut();
        else
           return Auth::redirectToLogInResponse();
    }
    private function doLogOut():RedirectResponse
    {
        // delete current token
        Auth::getAccessTokenFromDataBase()->delete();
        //remove admin session
        Auth::removeAdminFromSession();
        //redirect to Home with log out  message by flash session
        $this->setMessageForLogOutToSystemMessages();
        return redirect()
            ->route('home')
            ->with('systemMessages',$this->systemMessages);
    }
    private function setMessageForLogOutToSystemMessages()
    {
        $this
            ->systemMessages
            ->push(collect([
                'status'=>true ,
                'message'=>'دکمه ی شما با موفقیت خورد !!' ,
            ]));
    }
}
