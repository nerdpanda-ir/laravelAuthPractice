<?php

namespace App\Http\Requests\Admin\Management;

use App\Lib\Auth;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Admin;
class DoStoreAdminRequest extends FormRequest
{
    private const REQUIRED_MESSAGE = ':attribute الزامی میباشد !!! ';
    private const STRING_MESSAGE = ':attribute حتما باید از نوع رشته ای باشد !!! ' ;
    private const STRING_MAX_MESSAGE = ':attribute حد اکثر میتواند :max کاراکتر بپذیرد';
    private const STRING_MIN_MESSAGE = ':attribute حد اقل باید :min کاراکتر داشته باشد';
    private const UNIQUE_MESSASGE = ':attribute از قبل موجود است !!' ;
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
    public function rules():array
    {
        return [
            'name'=>'required|string|max:32|min:3' ,
            'family'=>'required|string|max:32|min:3' ,
            'nick'=>'required|string|max:60|min:3' ,
            'username'=>'required|string|max:60|min:3|unique:'.Admin::class.',username' ,
            'email'=>'required|email|max:160|min:5|unique:'.Admin::class.',email'  ,
            'phone'=>'required|string|size:11|regex:/^[0-9]{11}$/|unique:'.Admin::class.',phone'  ,
            'password'=>'required|confirmed|min:8|different:email,phone,username' ,
            'thumbnail'=>'image|max:1024' ,
            'active'=>'nullable'
        ];
    }
    public function messages():array
    {
        $messages = [
            'name.required'=> static::REQUIRED_MESSAGE,
            'name.string'=> static::STRING_MESSAGE,
            'name.max'=>static::STRING_MAX_MESSAGE ,
            'name.min'=>static::STRING_MIN_MESSAGE ,
            'family.required'=> static::REQUIRED_MESSAGE,
            'family.string'=> static::STRING_MESSAGE,
            'family.max'=>static::STRING_MAX_MESSAGE ,
            'family.min'=>static::STRING_MIN_MESSAGE ,
            'nick.required'=> static::REQUIRED_MESSAGE,
            'nick.string'=> static::STRING_MESSAGE,
            'nick.max'=>static::STRING_MAX_MESSAGE ,
            'nick.min'=>static::STRING_MIN_MESSAGE ,
            'username.required'=>static::REQUIRED_MESSAGE ,
            'username.string'=>static::STRING_MAX_MESSAGE ,
            'username.max'=>static::STRING_MAX_MESSAGE ,
            'username.min'=>static::STRING_MIN_MESSAGE ,
            'username.unique'=>static::UNIQUE_MESSASGE ,
            'email.required'=>static::REQUIRED_MESSAGE ,
            'email.email'=>':attribute باید با فورمت ایمیل باشد !!' ,
            'email.max'=>static::STRING_MAX_MESSAGE ,
            'email.min'=>static::STRING_MIN_MESSAGE,
            'email.unique'=>static::UNIQUE_MESSASGE ,
            'phone.required'=>static::REQUIRED_MESSAGE ,
            'phone.string' => static::STRING_MAX_MESSAGE ,
            'phone.size'=>':attribute باید دارای :size کاراکتر باشد !!!' ,
            'phone.unique'=>static::UNIQUE_MESSASGE ,
            'password.required'=>static::REQUIRED_MESSAGE ,
            'password.confirmed'=>':attribute باید با تکرار پسورد یکی باشد !!' ,
            'password.min'=>static::STRING_MIN_MESSAGE,
            'password.different' => ':attribute نباید با فیلد های دیگر یکی باشد !!!' ,
            'thumbnail.uploaded'=>':attribute به درستی اپلود نشد !!!' ,
            'thumbnail.image'=>'فایل ارسالی در :attribute باید از نوع عکس باشد !!!' ,
            'thumbnail.max'=>'فایل ارسالی در :attribute باید حداکثر :max کیلوبایت باشد !!! ' ,
        ];
        return  $messages;
    }

}
