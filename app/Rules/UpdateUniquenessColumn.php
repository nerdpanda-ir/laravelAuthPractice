<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class UpdateUniquenessColumn implements Rule
{
    private Model $model;
    private string $column;
    private string $formValue;
    private Request $request;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(string $modelNameSpace , string $column , Request $request)
    {
        $this->model = new $modelNameSpace;
        $this->column = $column;
        $this->request = $request;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $this->formValue = $value;
        if ($this->formValueEqualToCurrent())
            return true ;
        else if ($this->isExistInColumn())
                return false;
            else
                return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'این :attribute از قبل موجود میباشد !!';
    }
    private function formValueEqualToCurrent():bool
    {
        $isEqual = $this->model
                        ->withoutTrashed()
                        ->where($this->column,'=',$this->formValue)
                        ->where($this->model->getKeyName(),'=',$this->request->id)
                        ->count($this->model->getKeyName());
        return $isEqual>0;
    }
    private function isExistInColumn():bool
    {
        $isExist =
            $this->model
                ->withoutTrashed()
                 ->where($this->column,'=',$this->formValue)
                 ->count($this->model->getKeyName());
        return $isExist>0;
    }
}
