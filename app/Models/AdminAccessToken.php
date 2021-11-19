<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Models\AdminAccessToken\AdminAccessTokenModelScopes;
use Illuminate\Database\Eloquent\SoftDeletes;
class AdminAccessToken extends Model
{
    use HasFactory;
    use AdminAccessTokenModelScopes;
    public $timestamps=false;
    use SoftDeletes;
    public  function getTokenValidationResult(string $token,array $columns=['*']):self|null
    {
        return $this
            ->tokenIsValid($token)
            ->first($columns);
    }
}
