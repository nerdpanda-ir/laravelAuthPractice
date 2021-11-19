<?php

namespace App\Http\Requests\Admin\Slider;

use Illuminate\Foundation\Http\FormRequest;

class DoStoreSliderRequest extends FormRequest
{
    private string $maxMsg = 'حداکثر :attribute باید :max کاراکتر باشد !!!';
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
        return
            [
                'image'=>'required|image|max:1024' ,
                'title'=>'nullable|max:255',
                'url'=>'nullable|url|max:255' ,
                'urlTitle'=>'nullable|max:64' ,
                'sort'=>'nullable|int|max:18446744073709551615|gte:0' ,
                'description'=>'nullable|max:255' ,
                'active'=>'boolean|nullable'
            ];
    }
    public function messages()
    {
        return
            [
                'image.required'=>':attribute الزامی میباشد !!!' ,
                'image.image'=>'فایل ارسالی حتما باید از نوع عکس باشد !!! فورمت های -> (jpg, jpeg, png, bmp, gif, svg, or webp) مجاز میباشد !!!' ,
                'image.max'=>'حداکثر سایز عکس باید یک مگابایت باشد !!!' ,
                'image.uploaded'=>'خطا در اپلود :attribute' ,
                'title.max'=>$this->maxMsg ,
                'url.url'=>'فورمت :attribute حتما باید url باشد !!! ' ,
                'url.max'=>$this->maxMsg ,
                'urlTitle.max'=>$this->maxMsg ,
                'sort.gte'=>'باید بزرگتر و یا مساوی :value باشد !!!' ,
                'sort.integer'=>':attribute حتما باید عددی باشد !!! ' ,
                'sort.max'=>$this->maxMsg ,
                'description.max'=>$this->maxMsg ,
                'active'=>':attribute حتما باید از نوع boolean باشد !!! ' ,
            ];
    }
    public function attributes()
    {
        return
        [
            'image'=>'عکس اسلایدر ' ,
        ];
    }
}
