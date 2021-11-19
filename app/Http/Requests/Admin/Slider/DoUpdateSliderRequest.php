<?php

namespace App\Http\Requests\Admin\Slider;

use Illuminate\Foundation\Http\FormRequest;

class DoUpdateSliderRequest extends FormRequest
{
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
            'image'=>'nullable|image|max:1024' ,
            'title'=>'nullable|string|max:255',
            'describe'=>'nullable|string|max:255',
            'uri'=>'nullable|string|max:255',
            'uri_title'=>'nullable|string|max:64',
            'sort'=>'nullable|int' ,
            'active'=>'nullable|boolean',
        ];
    }
    public function messages()
    {
        $maxMsg = 'حداکثر کاراکتر :attribute باید :max باشد !!';
        $failMessages =
            [
                'image.uploaded' => ' :attribute به درستی اپلود نشد !!!' ,
                'image.image'=>'فایل اپلودی از نوع عکس نیست !!!' ,
                'image.max'=>'حداکثر سایز :attribute باید یک مگابایت باشد !!! ' ,
                'title.max'=>$maxMsg ,
                'describe.max' => $maxMsg ,
                'uri.max'=> $maxMsg,
                'uri_title.max'=>$maxMsg,
                'sort.integer'=>':attribute باید از نوع عددی باشد !!!' ,
                'active.boolean'=>':attribute باید از نوع boolean باشد !!! ' ,
            ];
        return $failMessages;
    }
    public function attributes()
    {
        return [
            'image'=>'عکس اسلایدر' ,
        ];
    }
}
