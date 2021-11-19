<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Faker\Factory as FakerGenerator;
use Faker\Generator as Faker;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    private Faker $faker;
    private User $user;
    private int $count=6;
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->faker = FakerGenerator::create();
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->clearTable();
        $this->createPanda();
        $this->count--;
        $this->createUsers();

    }
    private function clearTable()
    {
        Schema::disableForeignKeyConstraints();
        $this->user->truncate();
        Schema::enableForeignKeyConstraints();
    }
    private function createPanda()
    {
        $this->setSharedAttributes();
        $this->user->setAttribute('name','nerdpanda');
        $this->user->setAttribute('email','nerdpanda@gmail.com');
        $this->user->password = Hash::make('nerdpanda');
        $this->saveToDb();
    }
    private function createUser()
    {
        $this->user->setAttribute('name',$this->faker->name());
        $this->user->setAttribute('email',$this->faker->email());
        $this->user->password = Hash::make($this->faker->password);
        $this->setSharedAttributes();
        $this->saveToDb();
    }
    private function createUsers()
    {
        for($counter= 0; $counter<=($this->count-1);$counter++)
            $this->createUser();
    }
    private function makeCreatedAt():Carbon
    {
        $date = Carbon::now()->subDays(rand(2,14));
        return $date;
    }
    private function makeEmailVerifiedAt()
    {
        $chance =$this->faker->boolean;
        if (!$chance)
            return null;
        $chance= $this->faker->boolean;
        /** @var Carbon $createdAt*/
        $createdAt = $this->user->getAttribute('created_at');
        $now = Carbon::now();
        $diff = $now->diffInSeconds($createdAt);
        return $createdAt->addSeconds(rand(1,$diff));
    }
    private function makeUpdatedAt()
    {
        $chance = $this->faker->boolean;
        if (!$chance)
            return null;
        /** @var Carbon $createdAt*/
        $createdAt = $this->user->getAttribute('created_at');
        $now = Carbon::now();
        $diff = $now->diffInSeconds($createdAt);
       return $createdAt->addSeconds(rand(1,$diff));
    }
    private function makeRememberToken()
    {
        $chance = $this->faker->boolean;
        if (!$chance)
            return null;
        return sha1(microtime());
    }
    private function setSharedAttributes()
    {
        $this->user->setAttribute('created_at',$this->makeCreatedAt());
        $this->user->setAttribute('email_verified_at',$this->makeEmailVerifiedAt());
        $this->user->setAttribute('updated_at',$this->makeUpdatedAt());
        $this->user->setAttribute('remember_token',$this->makeRememberToken());
    }

    private function saveToDb()
    {
        $this->user->save();
        $this->user = new User();
    }
}
