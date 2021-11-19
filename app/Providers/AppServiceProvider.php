<?php

namespace App\Providers;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPrintSystemMessagesBladeDirective();
        $this->registerPrintFormErrorsBladeDirective();
    }
    private function registerPrintSystemMessagesBladeDirective()
    {
        Blade::directive('printSystemMessages',function (){
            $code = '<?php
        $key = "systemMessages";
        if (session()->has($key))
        {
            $messages = session()->get($key);
            $result="";
            $messages->each(function ($item)use (&$result){
                $message = $item->get("message");
                if ($item->get("status"))
                    $result.="<div class=\"alert alert-success text-center rtl\" role=\"alert\">".$message."</div>";
                else
                    $result.="<div class=\"alert alert-danger text-center rtl\" role=\"alert\">".$message."</div>";
            });
            echo $result;
        } ?>';
            return $code;
        });
    }
    private function registerPrintFormErrorsBladeDirective()
    {
        Blade::directive('printFormErrors',function (){
            $execute = '<?php
                if (session()->has(\'errors\'))
                {
                    $result= "<ol class=\"alert alert-danger rtl text-right\" role=\"alert\">" ;
                    $errorItems = session()->get(\'errors\')->all();
                    foreach ($errorItems as $error)
                        $result.="<li>$error</li>";
                    echo $result."</ol>";
                }

            ?>';
            return $execute;
        });
    }
}
