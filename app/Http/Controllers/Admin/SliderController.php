<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Slider;
use Carbon\Carbon;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\Admin\Slider\DoStoreSliderRequest;
use Symfony\Component\HttpFoundation\File\File;
use App\Http\Requests\Admin\Slider\DoUpdateSliderRequest ;
class SliderController extends Controller
{
    private string $fileDestination ;
    public function __construct()
    {
        $this->fileDestination = public_path('media').DIRECTORY_SEPARATOR;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Slider $slider)
    {
        /*$sliders = $slider->withoutTrashed()->paginate(2);*/
        $sliders = $slider
            ->withoutTrashed()
            ->orderByRaw('isnull(`sort`) asc')
            ->orderBy('sort','asc')
            ->orderBy('created_at','desc')
            ->paginate(8);
        $columns=[];
        if ($sliders->isNotEmpty())
            $columns = array_keys($sliders->items()[0]->getAttributes());
        return view('pages.admin.slider.index',compact('sliders','columns'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.admin.slider.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(DoStoreSliderRequest $request, Slider $slider)
    {
        $systemMessages = collect([]);
        $image = $request->file('image');
        $stored = $this->storeFile($image);
        if (is_bool($stored) and !$stored)
            return redirect()->back()->with('systemMessages',collect([
                collect(['status'=>false , 'message'=>'مشکل در اپلود فایل روی سرور لطفا لاگ ارور ها را بخوانید !!!'])
            ]));
            $this->doStoreRequestToModel($request,$slider,$stored);
        if($slider->save())
            $systemMessages->push(collect(['status'=>true,'message'=>'اسلایدر با موفقیت ساخته شد!!']));
        else
            $systemMessages->push(collect(['status'=>false,'message'=>'اسلایدر با موفقیت ساخته نشد!!']));
        return redirect()->back()->with('systemMessages',$systemMessages);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function edit(Slider $slider,$id)
    {
        $item = $slider->sliderEditData($id);
        return view('pages.admin.slider.edit',['slider'=>$item]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function update(DoUpdateSliderRequest $request, Slider $slider,$id, Filesystem $filesystem)
    {
        $item = $slider->sliderEditData($id);
        $systemMessages = collect() ;
        $data = $this->getUpdateData($request,$item->image,$systemMessages);
        $data->each(function ($data,$key) use ($item){
            $item->setAttribute($key,$data);
        });
        $updated = $item->save();
        if ($updated)
            $systemMessages->push(
                collect([
                    'status'=>true ,
                    'message'=> 'با موفقیت اپدیت شد !!!' ,
                ])
            );
        else
            $systemMessages->push(
                collect([
                    'status'=>false,
                    'message'=> 'ناموفق در اپدیت ایتم !!!' ,
                ])
            );
        return redirect()
            ->route('admin.slider.index')
            ->with('systemMessages',$systemMessages);
    }
    private function getUpdateData(DoUpdateSliderRequest $request , string $oldFile , Collection $systemMessages):Collection
    {
        $data =collect( $request->except('_method','_token'));
        $image = $data->get('image');
        $data->put('updated_at',Carbon::now());
        if (!$data->has('active'))
            $data['active'] = false;
        if ($image instanceof UploadedFile)
        {
            $stored = $this->storeFile($image);
            if (!is_bool($stored) and is_string($stored))
            {
                $data->put('image',$stored);
                $fileSystem = new Filesystem();
                $fileSystem->deleteDirectory(public_path(dirname($oldFile)));
            }
            elseif (is_bool($stored) and !$stored)
            {
                $data->forget('image');
                $systemMessages->push(
                  collect([
                      'status'=>false,
                      'message'=>'ناموفق در اپلود فایل جدید !!!' ,
                  ])
                );

            }

        }
        return $data;
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function destroy(Slider $slider,$id)
    {
        $slider = $slider->withTrashed()->findOrFail($id,['deleted_at','id','image']);
        if (is_null($slider->deleted_at))
            return $this->moveToTrash($slider);
        else
            return $this->forEverDelete($slider);

    }
    private function moveToTrash(Slider $slider)
    {
        $slider->delete();
        Session::flash('systemMessages',collect(
            [collect(['status'=>true,'message'=>'اسلایدر به سطل زباله انتقال یافت !!!'])]));
        return redirect()->back();
    }
    private function forEverDelete(Slider $slider)
    {
        $fileSystem = new Filesystem();
        $dir =dirname(public_path($slider->image));
        $fileSystem->deleteDirectory($dir);
        $slider->forceDelete();
        Session::flash('systemMessages',collect([collect(['status'=>true,'message'=>'اسلایدر از دیتابیس پاک گردید !!!'])]));
        return redirect()->back();
    }
    public function trashes(Slider $slider)
    {
        $columns = [];
        /** @var LengthAwarePaginator $sliders*/
        $sliders = $slider
            ->select('id')
            ->selectRaw(
               '
                 "#" ,
                 `id` ,
       concat("<img src=",`image`,">") as `image` ,
       `title` ,
       `describe` as "description" ,
       concat("<a href=\"",`uri`,"\">",if(`uri_title` is null,"",`uri_title`),"</a>") as "url" ,
       `sort` ,
        if(`active` is true , "<i class=\"fa-thin fa-check text-success display-4\"></i>" , "<i class=\"fa-thin fa-cancel text-danger display-4\"></i>")  as "active",
       `created_at` as \'CreatedAt\' ,
       `updated_at`  as \'UpdatedAt\',
       `deleted_at` as \'DeletedAt\'
               '
            )
            ->onlyTrashed()
            ->orderBy('deleted_at','desc')
            ->paginate(8);
            if ($sliders->isNotEmpty())
            {
                $columns =array_keys($sliders->items()[0]->getAttributes());
                array_shift($columns);
            }
            return view('pages.admin.slider.trashes',compact('sliders','columns'));
    }
    public function restore(Slider $slider , $id)
    {
        $systemMessages = collect();
        $item = $slider->onlyTrashed()->find($id,['id']);
        if (is_null($item))
        {
            $systemMessages->push(
              collect([
                  'status'=>false ,
                  'message'=>' ایتم مورد نظر یافت نشد !!!'
              ])
            );
            return redirect()->back()->with('systemMessages',$systemMessages);
        }
        elseif ($item instanceof Slider)
        {
            $restored = $item->restore();
            if ($restored)
                $systemMessages->push(collect([
                    'status'=>true ,
                    'message'=>' ایتم با موفقیت از سطل اشغال بیرون امد !!'
                ]));
            else
                $systemMessages->push(collect([
                    'status'=>false,
                    'message'=>'ناموفق در restore کردن ایتم !!! '
                ]));
            return redirect()->back()->with('systemMessages',$systemMessages);
        }
    }
    private function storeFile(UploadedFile $file)
    {
        /** @var Collection $finalDestination*/
        $finalDestination = $this->makeUniqueDir();
        if (!$finalDestination->get('status'))
            return false ;
        $finalDestination= $finalDestination->get('fullPath');
        try {
            /** @var File $uploaded*/
            $uploaded = $file->move($finalDestination,$file->getClientOriginalName());
            $finalDestination = $uploaded->getPathname();
            $publicPos = strpos($finalDestination,'public'.DIRECTORY_SEPARATOR)+6;
            $mainDestination =substr($finalDestination,$publicPos);
                return $mainDestination;
        }catch (\Exception $exception)
        {
            \Log::error('cant move file from '.$_FILES['image']['tmp_name']. ' to '.$finalDestination , ['exception'=> $exception]);
            return false;
        }
    }
    private function getUniqueDirName():string
    {
        do{
            $rnd = microtime();
            $hash = sha1($rnd);
            $hash=substr($hash,0,6);
        }while(is_dir($this->fileDestination.$hash));
        return $hash;
    }
    private function makeUniqueDir():Collection
    {
        $result = collect([
            'status'=>false,
            'name'=>$this->getUniqueDirName() ,
        ]);
        $result->put('fullPath' , $this->fileDestination.$result->get('name'));
        try {
            $result->put('status',mkdir($result->get('fullPath')));
        }catch (\Exception $exception)
        {
            \Log::error('cant make Unique Dir for file upload ',['class'=>__METHOD__ , 'exception'=>$exception]);
        } finally {
            return $result;
        }
    }
    private function doStoreRequestToModel(Request $request , Slider $slider ,string $image)
    {
        $slider->image = $image;
        $slider->title = $request->get('title');
        $slider->uri = $request->get('url');
        $slider->uri_title = $request->get('urlTitle');
        $slider->user_id=1;
        $slider->created_at = now();
        if (!$request->has('active'))
            $slider->active  = false;
        $slider->sort = $request->get('sort');
        $slider->describe = $request->get('description');
    }
}
