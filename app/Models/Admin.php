<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Models\Admin\AdminModelScopes;
use Illuminate\Pagination\LengthAwarePaginator;

class Admin extends Model
{
    use HasFactory;
    use SoftDeletes;
    use AdminModelScopes;
    public $timestamps= false;
    public function findByEmail(string $email,array $columns=['*']):false|self
    {
        $item = $this->WhereEmailWithOutTrashed($email)->first($columns,['id']);
        if (is_null($item))
            return false;
        return $item;
    }
    public function getAcceptedAdmins($columns=['*']):Collection
    {
        return $this->
                withoutTrashed()
                ->IsActive()
                ->WherePhoneVerified()
                ->WhereEmailVerified()
                ->get($columns);
    }
    public function getAllAdminsForIndexView():Collection|LengthAwarePaginator
    {
        return $this
                ->AllByLastActivityForIndexView()
                ->paginate(8);
    }
    public function findForDestroy($id,array $column = ['id','nick','thumbnail']):self|null
    {
        $result = $this
            ->findForRemove($id)
            ->first($column);
        return  $result;
    }
    public function findItemForDelete(int $id,$columns=['id','nick']):null|self
    {
        $result = $this
                   ->FindForDelete($id)
                    ->first($columns);
        return $result;
    }
    // @todo delete with sql transactions !!!!
    public function deleteWithAccessTokens():bool
    {
        $accessToken = new AdminAccessToken();
        $accessTokensDeleteCount =
            $accessToken
                ->FindForDeleteByOwnerId($this->id)
                ->delete();
        return $this->delete();
    }
    public function getFindItemForEditView($id):null|self
    {
        return $this
            ->withTrashed()
            ->find($id,[
                'id' ,
                'name' ,
                'family' ,
                'nick' ,
                'username' ,
                'email' ,
                'phone' ,
                'active',
                'thumbnail'
            ]);
    }
    private function getViewColumns():array
    {
        return [
            'admins.id' ,
            '"#"' ,
            'concat("<img src=\"",if(isnull(thumbnail) , "'.asset('media/default.png').'" , thumbnail),"\">") as "image"' ,
            'username',
            'if(admins.active,"<i class=\"fa-solid fa-circle-check userActive\"></i>","<i class=\"fa-solid fa-ban userBan \"></i>") as "active"',
            'nick' ,
            'name' ,
            'family' ,
            'admins.created_at as "createDate"' ,
            'admins.updated_at as "updateDate"',
            'email',
            'email_verified_at as "emailVerifyDate"' ,
            'phone',
            'phone_verified_at as "phoneVerifyDate"' ,
        ];
    }
    public function getItemFromTrashed(int $id):null|self
    {
       return
           $this
            ->onlyTrashed()
            ->find($id,['id','username']);
    }
}
