@extends('layouts.dashboard')
@section('main')
    @printFormErrors
    @printSystemMessages
    @if($slider instanceof \App\Models\Slider)
        <form action="{!! route('admin.slider.update',[$slider->id]) !!}" method="post" enctype="multipart/form-data" class="md-form">
            @csrf
            @method('PUT')

            <div class="md-form">
                <input type="text" id="title" class="form-control" name="title" value="{{old('title',$slider->title)}}">
                <label for="title" class="">Title</label>
            </div>

            <div class="md-form">
                <input type="text" id="uri" class="form-control" name="uri" value="{{old('uri',$slider->uri)}}">
                <label for="uri">url</label>
            </div>

            <div class="md-form">
                <input type="text" id="uri_title" class="form-control" name="uri_title" value="{{old('uri_title',$slider->uri_title)}}">
                <label for="uri_title">url title</label>
            </div>

            <div class="md-form">
                <input type="text" id="sort" class="form-control" name="sort" value="{{old('sort',$slider->sort)}}">
                <label for="sort">Sort</label>
            </div>
            <div class="md-form mt-4 mt-4">
                <textarea id="describe" class="md-textarea form-control" rows="3" name="describe">{{old('describe',$slider->describe)}}</textarea>
                <label for="describe">description</label>
            </div>
            <div class="file-field">
                <a class="btn-floating purple-gradient mt-0 float-left waves-effect waves-light">
                    <i class="fas fa-cloud-upload-alt" aria-hidden="true"></i>
                    <input type="file" name="image">
                </a>
                <div class="file-path-wrapper">
                    <input class="file-path validate" type="text" placeholder="Upload your file">
                </div>
                <img src='{!! asset($slider->getAttribute('image')) !!}' class="imageDemo">
            </div>
            <div class="md-form">
                <!-- Material checked -->
                @php
                    $isActive=old('active')==1 || $slider->active==1;
                @endphp
                <div class="switch">
                    <label>
                        Off
                        <input type="checkbox" name="active" value="1" {{
        (($isActive) ? 'checked' : '')
}}>
                        <span class="lever"></span> On
                    </label>
                </div>
                <br><br>
            </div>
            <section class="btnWrapper text-center">
                <button class="btn btn-primary mt-4 waves-effect waves-light" type="submit"><i class="fas fa-layer-plus mr-1"></i> create</button>
                <button class="btn btn-red mt-4 waves-effect waves-light" type="submit"><i class="fas fa-broom mr-1"></i> clear</button>
            </section>
        </form>
    @elseif(is_null($slider))
        <section class="alert alert-danger text-right rtl" >
            چنین اسلایدری برای اپدیت یافت نشد !!!
            <ul>
                <li>
                    <a href="{!! route('admin.slider.index') !!}">
                        لیست اسلایدر ها
                    </a>
                </li>
            </ul>
        </section>
    @endif

@endsection
@section('css')
    @parent
    <style>
        .imageDemo
        {
            width: 90px;
            height: 90px;
            margin-top: 20px;
        }
    </style>
@endsection
