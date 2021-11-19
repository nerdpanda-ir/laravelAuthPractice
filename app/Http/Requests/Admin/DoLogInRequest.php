<?php

namespace App\Http\Requests\Admin;

use App\Models\Admin;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ExistWithSoftDeleteCheck;
class DoLogInRequest extends FormRequest
{
    protected $stopOnFirstFailure=true;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'password' => 'required|min:8',
            'email'=>['required','min:5','max:160','email', new ExistWithSoftDeleteCheck(Admin::class,'email' , 'ایمیل یا پسورد اشتباه است !!')]
        ];
    }
    public function messages()
    {
        return [
            'email.required'=>'فیلد :attribute الزامی میباشد !!' ,
            'email.min'=>'حداقل کاراکتر برای :attribute :min است !!' ,
            'email.max'=>'حداکثر کاراکتر برای :attribute :min است !!' ,
            'email.email'=>'مقدار وارد شده حتما باید با فورمت ایمل همخوانی داشته باشد !!!' ,
            'email.exists'=>'ایمیل یا پسورد اشتباه است !!' ,
            'password.required'=>'فیلد :attribute الزامی میباشد !! ' ,
            'password.min'=>'حداقل کاراکتر برای :attribute :min است ‌!!!' ,
        ] ;
    }
    public function attributes()
    {
        return [
          'email'=>'ایمیل' ,
          'password'=>'پسورد'
        ];
    }
}
