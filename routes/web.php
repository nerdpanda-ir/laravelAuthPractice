<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\LogInController as AdminLongInController;
use App\Http\Controllers\Admin\LogOutController as AdminLogOutController;
use App\Http\Controllers\Admin\AdminController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('',[PageController::class,'home'])->name('home');
Route::
    name('admin.')
    ->prefix('admin')
    ->group(function (){
        Route::get('',[PageController::class,'adminDashboard'])->name('dashboard');

        Route::name('slider.')
            ->prefix('slider')
            ->group(function (){

                Route::get('trashes',[SliderController::class,'trashes'])
                    ->name('trashes');
                Route::get('restore/{id}',[SliderController::class,'restore'])
                ->name('restore');
                Route::resource('',SliderController::class)
                    ->parameter('','id')
                    ->except('show');
            });

        /* log in */
        Route::get('login',[AdminLongInController::class,'index'])->name('login');
        Route::post('login',[AdminLongInController::class,'doLogIn'])->name('login');

        /* log out */
        Route::get('logout',[AdminLogOutController::class,'index'])->name('logout');

        /* admin management routes */
        Route::name('management.')
            ->prefix('management')
            ->group(function (){
                /* trashes */
                Route::get('trash',[AdminController::class,'trashes'])
                    ->name('trashes');

                Route::get('trash/{id}/restore',[AdminController::class,'restore'])
                    ->name('restore');


                //soft delete
                Route::delete('trash/{id}',[AdminController::class,'delete'])
                ->name('delete');

                Route::resource('',AdminController::class)
                    ->parameter('','id');

            });


    });

