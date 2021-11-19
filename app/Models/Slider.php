<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Slider extends Model
{
    use HasFactory;
    use SoftDeletes;
    public $timestamps =false ;
    public function showableSliders():Builder
    {
        return $this
            ->withoutTrashed()
            ->where('active','=',true)
            ->orderByRaw('isnull(`sort`) asc')
            ->orderBy('sort','asc')
            ->orderBy('created_at','desc');
    }
    public function getShowableSliders(array $columns=['*']):Collection
    {
        return $this->showableSliders()->get($columns);
    }
    public function getHomePageSliders():Collection
    {
        $sliders = $this->getShowableSliders([
            'image',
            'title',
            'describe',
            'uri' ,
            'uri_title'
        ]);
        return $sliders;
    }
    public function sliderEditData(int $id):Slider|null
    {
        $item = $this->withoutTrashed()->find(
            $id ,
            [
                'id',
                'image' ,
                'title' ,
                'uri' ,
                'uri_title' ,
                'sort' ,
                'describe' ,
                'active'
            ]
        );
        return $item;
    }
}
