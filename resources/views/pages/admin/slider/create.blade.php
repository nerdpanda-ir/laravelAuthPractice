@extends('layouts.dashboard')
@section('main')
    @printSystemMessages
    @printFormErrors
    <form method="post" action="{!! route('admin.slider.store') !!}" class="md-form" enctype="multipart/form-data">
    @csrf
    @method('POST')
        @error('title')
    <!-- Material input -->
        <div class="md-form">
            <input type="text" id="title" class="form-control is-invalid invalid" name="title" value="{{old('title')}}">
            <label for="title" class="invalid">Title</label>
            <p class="rtl text-center is-invalid text-danger">{{$message}}</p>
        </div>
        <!-- Material input -->

        @else
            <!-- Material input -->
                <div class="md-form">
                    <input type="text" id="title" class="form-control" name="title" value="{{old('title')}}">
                    <label for="title">Title</label>
                </div>
                <!-- Material input -->
        @endif


        <div class="md-form">
            <input type="text" id="url" class="form-control" name="url" value="{{old('url')}}">
            <label for="url">url</label>
        </div>
        <!-- Material input -->
        <div class="md-form">
            <input type="text" id="urlTitle" class="form-control" name="urlTitle" value="{{old('urlTitle')}}">
            <label for="urlTitle">url title</label>
        </div>

        <div class="md-form">
            <input type="text" id="sort" class="form-control" name="sort" value="{{old('sort')}}">
            <label for="sort">Sort</label>
        </div>
        <!--Material textarea-->

        <div class="md-form mt-4 mt-4">
            <textarea id="description" class="md-textarea form-control" rows="3" name="description" >{{old('description')}}</textarea>
            <label for="description">description</label>
        </div>
        <div class="file-field">
            <a class="btn-floating purple-gradient mt-0 float-left">
                <i class="fas fa-cloud-upload-alt" aria-hidden="true"></i>
                <input type="file" name="image">
            </a>
            <div class="file-path-wrapper">
                <input class="file-path validate" type="text" placeholder="Upload your file">
            </div>
        </div>
        <div class="md-form">
            <!-- Material checked -->
            <div class="switch">
                <label>
                    Off
                    @php
                        $key = '_old_input';
                    @endphp
                    @if(session()->has('_old_input') and !array_key_exists('active',session()->get($key)))
                    <input type="checkbox"  name="active" value="1">
                    @else
                        <input type="checkbox"  name="active" value="1" checked>
                    @endif

                    <span class="lever"></span> On
                </label>
            </div>
            <br><br>
        </div>


        <section class="btnWrapper text-center">
            <button class="btn btn-primary mt-4" type="submit"><i class="fas fa-layer-plus mr-1"></i> create</button>
            <button class="btn btn-red mt-4" type="submit"><i class="fas fa-broom mr-1"></i> clear</button>
        </section>

    </form>
@endsection
