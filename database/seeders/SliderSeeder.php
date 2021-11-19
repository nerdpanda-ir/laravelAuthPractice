<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Slider;
use Faker\Generator as Faker;
use Faker\Factory as FakerGenerator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use App\Models\User;

class SliderSeeder extends Seeder
{
    private Slider $slider;
    private Faker $faker;
    private Collection $fakeImages;
    private User $user;
    public function __construct(Slider $slider , User $user)
    {
        $this->slider = $slider;
        $this->faker = FakerGenerator::create();
        $this->fakeImages = collect($this->scanFakeImages());
        $this->user= $user;
    }


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->clearTable();
        $this->createSliders();
    }
    private function createSlider(string $img)
    {
        $uri = $this->makeUriAttributes();
        $this->slider->setAttribute('image',DIRECTORY_SEPARATOR.'media/fakerImg'.DIRECTORY_SEPARATOR.$img);
        $this->slider->setAttribute('title',$this->makeText());
        if (!is_null($this->slider->getAttribute('title')))
            $this->slider->setAttribute('describe',$this->makeText());
        $this->slider->setAttribute('uri',$uri->get('uri'));
        $this->slider->setAttribute('uri_title',$uri->get('title'));
        $this->slider->setAttribute('active',$this->faker->boolean);
        $this->slider->setAttribute('user_id',$this->user->getRndUser());
        $this->slider->setAttribute('created_at',$this->makeCreatedAt());
        $this->slider->setAttribute('updated_at',$this->makeUpdatedAt());
        $this->slider->setAttribute('deleted_at',$this->makeDeletedAt());
        if ($this->faker->boolean)
            $this->slider->sort=rand(1,10);
        $this->slider->save();
        $this->slider = new Slider();
    }
    private function scanFakeImages():array
    {
        $images =scandir(public_path('media/fakerImg'));
        $this->shiftArray($images,2);
        return $images;
    }
    private function shiftArray(array &$arr,int $count=1)
    {
        for ($counter= 0 ; $counter<=($count--);$counter++)
            array_shift($arr);
    }
    private function makeText()
    {
        $chance = $this->faker->boolean;
        if (!$chance)
            return null ;
        return $this->faker->realText(rand(14,34));
    }
    private function makeUriAttributes():Collection
    {
        $data = collect(['uri'=>null,'title'=>null]);
        $chance = $this->faker->boolean;
        if (!$chance)
            return $data;
        $data->put('uri',$this->faker->url());
        $chance = $this->faker->boolean;
        if (!$chance)
            return $data;
        $data->put('title',implode(' ',$this->faker->words(rand(2,6))));
        return  $data;
    }

    private function makeCreatedAt():Carbon
    {
        $chance = rand(1,3);
        $createdAt= Carbon::now();
        switch ($chance)
        {
            default :
            case 1:
                $createdAt = $createdAt->subDays(rand(2,6));
            break;
            case 2 :
                $createdAt = $createdAt->subSeconds(rand(500,18000));
            break;
            case 3 :
                $createdAt = $createdAt->subWeeks(rand(1,5));
            break;
        }
        return $createdAt;
    }
    private function makeUpdatedAt()
    {
        $chance = $this->faker->boolean;
        if (!$chance)
            return null;
        /** @var Carbon $createdAt*/
        $createdAt = Carbon::createFromTimestamp(strtotime( $this->slider->getAttribute('created_at')));
        $now = Carbon::now();
        $diff = $now->diffInSeconds($createdAt);
        $createdAt->addSeconds(rand(1,$diff));
        return $createdAt->format('Y/m/d H:i:s');
    }
    private function makeDeletedAt()
    {
        $chance = $this->faker->boolean;
        if (!$chance)
            return null;
        /** @var Carbon $updatedAt */
        $updatedAt = $this->slider->getAttribute('updated_at');
        /** @var Carbon $createdAt */
        $createdAt = $this->slider->getAttribute('created_at');
        $now = Carbon::now();
        if (!is_null($updatedAt))
        {
            $updatedAt= Carbon::createFromTimestamp(strtotime($updatedAt));
            $diff = $now->diffInSeconds($updatedAt);
            return $updatedAt->addSeconds(rand(1,$diff));
        }
        else
        {
            $createdAt = Carbon::createFromTimestamp(strtotime($createdAt));
            $diff = $now->diffInSeconds($createdAt);
            return $createdAt->addSeconds(rand(1,$diff));
        }
    }
    private function createSliders()
    {
        $this->fakeImages->each(function (string $item , int $index){
            $this->createSlider($item);
        });
    }
    private function clearTable()
    {
        $this->slider->truncate();
    }
}
