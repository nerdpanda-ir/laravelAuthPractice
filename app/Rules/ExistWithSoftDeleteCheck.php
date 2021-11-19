<?php

namespace App\Rules;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

class ExistWithSoftDeleteCheck implements Rule
{
    private Model $model;
    private string $column;
    private null|string $errorMessage;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(string $model , string $column , string|null $errorMessage = null)
    {
        $this->model =  new $model;
        $this->column = $column;
        $this->errorMessage = $errorMessage;
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
        return $this->isExist($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        if (is_null($this->errorMessage))
            return ':attribute is not exist';
        return $this->errorMessage;
    }
    private function isExist($value):bool
    {
        $rowCount = $this->model
            ->limit(1)
            ->withoutTrashed()
            ->where($this->column,'=',$value)
            ->count($this->model->getKeyName());
        if ($rowCount>=1)
            return true;
        return false;
    }
}
