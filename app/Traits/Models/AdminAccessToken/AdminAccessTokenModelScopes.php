<?php namespace App\Traits\Models\AdminAccessToken ;?>
<?php

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

trait AdminAccessTokenModelScopes
{
    public static function scopeFindByToken(Builder $query,string $token):Builder
    {
        return $query
                ->where(
                    'token',
                    '=',
                    $token
                );
    }
    public function scopeIsExistToken(Builder $query,string $token):bool
    {
        return $query
            ->findByToken($token)
            ->count(['id']);
    }
    public function scopeIsActive(Builder $query):Builder
    {
        return $query
                ->where($this->getTable().'.active','=',true);
    }
    public function scopeIsNotExpire(Builder $query):Builder
    {
        $now = Carbon::now()->format('Y-m-d H:i:s');
        return $query
            ->whereRaw('? <= `expired_at`',[$now]);
    }
    public function scopeTokenOwnerIsNotTrash(Builder $query):Builder
    {
        return $query->whereNull('admins.deleted_at');
    }
    public function scopeJoinWithAdmin(Builder $query):Builder
    {
        return $query
            ->join('admins','admin_access_tokens.admin_id','=','admins.id');
    }
    public function scopeTokenIsValid(Builder $query,string $token):Builder
    {
        return $query
                ->joinWithAdmin()
                ->TokenOwnerIsNotTrash()
                ->withoutTrashed()
                ->findByToken($token)
                ->isActive()
                ->isNotExpire();
    }
    public function scopeFindByOwnerId(Builder $query , int $adminId):Builder
    {
        return $query
                ->where('admin_id','=',$adminId);
    }
    public function scopeFindForDeleteByOwnerId(Builder $query , int $id ):Builder
    {
        return $query
                ->FindByOwnerId($id)
                ->withoutTrashed();
    }
}
