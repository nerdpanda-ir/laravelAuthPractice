<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use \App\Models\Admin;
use Faker\Factory as FakerGenerator ;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    private Admin $admin;
    private Faker $faker;
    private int $count=4;
    public function __construct(Admin $admin)
    {
        $this->faker = FakerGenerator::create();
        $this->admin = $admin;
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->admin->clearAdmins();
        $this->makePanda();
        $this->makeAdmins();
    }
    private function clearTable()
    {

        $this->admin->truncate();
    }
    private function makePanda()
    {
        $this->admin->name = 'nerd';
        $this->admin->family = 'panda';
        $this->admin->nick = 'nerd panda';
        $this->admin->username = 'nerdpanda';
        $this->admin->email = 'nerdpanda@gmail.com';
        $this->admin->phone='09058877412';
        $this->admin->password = Hash::make('nerdpanda');
        $this->admin->active=true ;
        $this->admin->thumbnail=$this->getFakeImage();
        $this->admin->created_at = Carbon::now()->subMonths(6);
        $this->admin->updated_at = Carbon::now()->subWeeks(3);
        $this->admin->email_verified_at =clone $this->admin->created_at;
        $this->admin->phone_verified_at = clone $this->admin->created_at;
        $this->admin->email_verified_at->addMinutes(rand(25,40));
        $this->admin->phone_verified_at->addMinutes(rand(1,24));
        $this->admin->save();
        $this->count--;
        $this->admin = new Admin();
    }
    private function getFakeImage()
    {
        $categories = ['people','cats','animals','nature'];
        $categoriesLen = count($categories) -1 ;
        $rndCategorie = $categories[rand(0,$categoriesLen)];
        return 'http://lorempixel.com/200/200/'.$rndCategorie.'/'.rand(1,10).'/';
    }
    private function makeAdmin()
    {
        $this->admin->name = $this->faker->firstName();
        $this->admin->family = $this->faker->lastName();
        $this->admin->nick = $this->admin->name . ' ' . $this->admin->family;
        $this->admin->username = $this->faker->userName();
        $this->admin->email = $this->faker->email;
        $this->admin->phone=trim($this->faker->e164PhoneNumber(),'.()+');
        $this->admin->password = Hash::make($this->faker->password);
        $this->admin->active=$this->faker->boolean ;
        $this->admin->thumbnail=$this->getFakeImage();
        $this->admin->created_at =$this->getCreatedAt();
        $this->admin->updated_at = $this->getChanceableDateAfterCreatedAt();
        $this->admin->email_verified_at = $this->getChanceableDateAfterCreatedAt();
        $this->admin->phone_verified_at =  $this->getChanceableDateAfterCreatedAt();
        $this->admin->deleted_at = $this->getDeletedAt();
        $this->admin->save();
        $this->count--;
        $this->admin = new Admin();
    }
    private function getCreatedAt():Carbon
    {
        $chance = rand(1,5);
        $now = Carbon::now();
        switch ($chance)
        {
            default :
            case 1 :
                return $now->subDays(rand(1,100));
            break;
            case 2 :
                return $now->subHours(rand(1,16));
            break ;
            case 3:
                return $now->subMinutes(rand(1,30));
            break ;
            case 4:
                return $now->subSeconds(rand(10,55));
            break ;
            case 5:
                return $now->subMonths(rand(1,5));
            break ;
        }
    }
    private function getRndDateAfterCreatedAt():Carbon
    {
        $createdAt =$this->admin->created_at;
        $current = Carbon::now();
        $diff = $current->diffInSeconds($createdAt);
        $current->subSeconds(rand(1,$diff));
        return $current;
    }
    private function getChanceableDateAfterCreatedAt():null|Carbon
    {
        $chance = $this->faker->boolean;
        if ($chance)
            return $this->getRndDateAfterCreatedAt();
        return  null;
    }
    private function makeAdmins():void
    {
        while ($this->count>=1)
            $this->makeAdmin();
    }
    private function getBigDate():Carbon
    {
        $dates = collect([
            $this->admin->created_at->getTimestamp()
        ]);
        if (!is_null($this->admin->updated_at))
            $dates->push( $this->admin->updated_at->getTimestamp() );

        if (!is_null($this->admin->email_verified_at))
            $dates->push( $this->admin->email_verified_at->getTimestamp() );

        if (!is_null($this->admin->phone_verified_at))
            $dates->push( $this->admin->phone_verified_at->getTimestamp() );

        $current = Carbon::now();
        $current->setTimestamp($dates->max());
        return $current;
    }
    private function getDeletedAt():Carbon|null
    {
        if (!$this->faker->boolean)
            return null;
        $bigDate = $this->getBigDate();
        $current = Carbon::now();
        $diff = $current->diffInSeconds($bigDate);
        $rndDiff = rand(1,$diff);
        $current->subSeconds($rndDiff);
        return $current;
    }
}
