<?php namespace App\Traits\Models\Admin ; ?>
<?php

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

trait AdminModelScopes
{
    public function scopeWhereEmail(Builder $query,string $email):Builder
    {
        return $query->where('email','=',$email);
    }
    public function scopeWhereEmailWithOutTrashed(Builder $query,string $email):Builder
    {
        return $this
            ->withoutTrashed()
            ->whereEmail($email);
    }
    public function scopeIsActive(Builder $query):Builder
    {
        return $query
                ->where('active','=',true);
    }
    public function scopeWhereEmailVerified(Builder $query):Builder
    {
        return $query
                ->whereNotNull('email_verified_at');
    }
    public function scopeWherePhoneVerified(Builder $query):Builder
    {
        return $query->
                whereNotNull('phone_verified_at');
    }
    public function scopeClearAdmins(Builder $query):void
    {
        Schema::disableForeignKeyConstraints();
        $query->truncate();
        Schema::enableForeignKeyConstraints();
    }

    public function scopeAllByLastActivity(Builder $query,$columns=['admins.*']):Builder
    {
        return $query
                ->selectRaw(implode(',',$columns))
                ->selectRaw('max(`admin_access_tokens`.`created_at`) as `last_log_in`')
                ->leftJoin('admin_access_tokens',$this->getTable().'.id','=','admin_access_tokens.admin_id')
                ->groupBy($this->getTable().'.id')
                ->orderBy('last_log_in','desc')
                ->orderBy($this->getTable().'.created_at','desc')
               ;
    }
    public function scopeAllByLastActivityForView(Builder $query):Builder
    {
        return $query
               ->allByLastActivity(
                   $this->getViewColumns()
               );
    }
    public function scopeAllByLastActivityForIndexView(Builder $query):Builder
    {
        return $query
                ->withoutTrashed()
                ->allByLastActivityForView();
    }
    public function scopeFindForRemove(Builder $query , int $id):Builder
    {
        return $query
                ->withTrashed()
                ->where($this->getKeyName(),'=',$id)
                ->limit(1);
    }
    public function scopeFindForDelete(Builder $query , int $id):Builder
    {
        return $query
                ->findForRemove($id)
                ->withoutTrashed();
    }
    public function scopeTrashedItems(Builder $query):Builder
    {
        return $query
                ->selectRaw(implode(',',$this->getViewColumns()))
                ->selectRaw('`deleted_at` as deletedAt')
                ->orderBy('deleted_at','desc')
                ->onlyTrashed();
    }
}
