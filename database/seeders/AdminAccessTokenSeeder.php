<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Lib\AdminAccessTokenController;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Admin;
use App\Models\AdminAccessToken;
use Illuminate\Support\Carbon;

class AdminAccessTokenSeeder extends Seeder
{
    private Collection $admins;
    private Admin $admin;
    private int $max= 5;
    private AdminAccessToken $adminAccessToken;
    public function __construct(Admin $admin , AdminAccessToken $adminAccessToken)
    {
        $this->admin=$admin;
        $this->adminAccessToken = $adminAccessToken;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->setAcceptedAdminsToAdmins();
        $this->clearTable();
        $this->makeTokensPerEachAdmin();
        $this->setDeletedAtForTokens();
    }

    private function setAcceptedAdminsToAdmins():void
    {
      $this->admins = $this->admin->getAcceptedAdmins(['id']);
    }

    private function makeTokensPerEachAdmin()
    {
        foreach ($this->admins as $admin)
        {
            $max = rand(1,$this->max);
            for ($counter = 0 ; $counter<$max;$counter++)
                AdminAccessTokenController::make($admin->getAttribute('id'));
        }
    }
    private function clearTable()
    {
        $this->adminAccessToken->truncate();
    }

    private function setDeletedAtForTokens()
    {
        /** @var Collection $accessTokens*/
        $accessTokens =$this->adminAccessToken
                            ->where('admin_id','!=',1)
                            ->get(['id']);
        $accessTokens->each(function ($item){
            $chance = rand(0,1);
            if ($chance)
                $item->delete();
        });
    }
}
