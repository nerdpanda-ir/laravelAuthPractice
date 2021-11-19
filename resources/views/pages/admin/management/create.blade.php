@extends('layouts.dashboard')
@section('main')
    @printSystemMessages
    @printFormErrors
    <!-- Material form register -->
    <div class="card">

        <h5 class="card-header info-color white-text text-center py-4">
            <strong> Create New Admin </strong>
        </h5>

        <!--Card content-->
        <div class="card-body px-lg-5 pt-0">

            <!-- Form -->
            <form class="text-center md-form" style="color: #757575;" action="{!! route('admin.management.store') !!}" method="post" enctype="multipart/form-data">
                @csrf
                @method('POST')

                <div class="form-row">
                    <div class="col">
                        <!-- First name -->
                        <div class="md-form">
                            <input type="text" id="materialRegisterFormFirstName" class="form-control" name="name" value="{{old('name')}}">
                            <label for="materialRegisterFormFirstName">First name</label>
                        </div>
                    </div>
                    <div class="col">
                        <!-- Last name -->
                        <div class="md-form">
                            <input type="text" id="materialRegisterFormLastName" class="form-control" name="family" value="{{old('family')}}">
                            <label for="materialRegisterFormLastName">Last name</label>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col">
                        <!-- First name -->
                        <div class="md-form">
                            <input type="text" id="nick" class="form-control" name="nick" value="{{old('nick')}}">
                            <label for="nick">nick</label>
                        </div>
                    </div>
                    <div class="col">
                        <!-- Last name -->
                        <div class="md-form">
                            <input type="text" id="username" class="form-control" name="username" value="{{old('username')}}">
                            <label for="username">username</label>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="col">
                        <!-- First name -->
                        <div class="md-form">
                            <input type="text" id="email" class="form-control" name="email" value="{{old('email')}}">
                            <label for="nick">email</label>
                        </div>
                    </div>
                    <div class="col">
                        <!-- Last name -->
                        <div class="md-form">
                            <input type="text" id="phone" class="form-control" name="phone" value="{{old('phone')}}">
                            <label for="phone">phone</label>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col">
                        <div class="md-form">
                            <input type="text" id="password" class="form-control" name="password">
                            <label for="password">password</label>
                        </div>
                    </div>
                    <div class="col">
                        <!-- Last name -->
                        <div class="md-form">
                            <input type="text" id="password_confirmation" class="form-control" name="password_confirmation">
                            <label for="password_confirmation">password confirm</label>
                        </div>
                    </div>
                </div>


                <div class="file-field ">
                    <a class="btn-floating peach-gradient mt-0 float-left">
                        <i class="fas fa-paperclip" aria-hidden="true"></i>
                        <input type="file" name="thumbnail">
                    </a>
                    <div class="file-path-wrapper">
                        <input class="file-path validate" type="text" placeholder="Upload your file">
                    </div>
                </div>


                <section class="md-form row mb-5">
                    <!-- Material checked -->
                    <div class="switch">
                        <label>
                            disable
                            @if(session()->has('_old_input') and !session()->has('_old_input.active'))
                                <input type="checkbox" name="active" >
                            @else
                                <input type="checkbox" checked name="active" >
                            @endif
                            <span class="lever"></span> active
                        </label>
                    </div>
                </section>

                <!-- Sign up button -->
                <button  class="btn btn-cyan btn-rounded btn-block my-4 waves-effect z-depth-0" type="submit">create</button>
                <hr>

                <!-- Terms of service -->


            </form>
            <!-- Form -->

        </div>

    </div>
    <!-- Material form register -->
@endsection
