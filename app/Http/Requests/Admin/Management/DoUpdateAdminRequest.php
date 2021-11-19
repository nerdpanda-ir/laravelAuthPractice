<?php

namespace App\Http\Requests\Admin\Management;

use App\Lib\Auth;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Admin;
use App\Rules\UpdateUniquenessColumn;
class DoUpdateAdminRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::isLogIn();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'=>'required|string|min:3|max:32' ,
            'family'=> 'required|string|min:3|max:32' ,
            'nick'=>'required|string|min:3|max:60' ,
            'username'=>[
                'required',
                'string',
                'min:3',
                'max:60' ,
                new UpdateUniquenessColumn(Admin::class,'username' , $this)
            ] ,
            'email'=>[
                'required' ,
                'email' ,
                'min:5' ,
                'max:160' ,
                new UpdateUniquenessColumn(Admin::class,'email',$this)
            ] ,
            'phone'=>[
                'required' ,
                'size:11' ,
                'string' ,
                'regex:/^[0-9]{11}$/' ,
                new UpdateUniquenessColumn(Admin::class,'phone',$this)
            ],
            'password'=>'nullable|min:8|confirmed|different:username,email,phone',
            'thumbnail'=>'nullable|image|max:1024' ,
            'active'=>'nullable' ,
        ];
    }
}
