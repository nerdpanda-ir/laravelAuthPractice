@extends('layouts.mdb')
@section('body')
    @printFormErrors
    @printSystemMessages
    <!-- Main Navigation -->
    <header>
        <!-- Intro Section -->
        <section class="view intro-2">
            <div class="mask rgba-stylish-strong h-100 d-flex justify-content-center align-items-center">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-5 col-lg-6 col-md-10 col-sm-12 mx-auto mt-5">

                            <!-- Form with header -->
                            <div class="card wow fadeIn" data-wow-delay="0.3s">
                                <div class="card-body">

                                    <!-- Header -->
                                    <div class="form-header purple-gradient">
                                        <h3 class="font-weight-500 my-2 py-1"><i class="fas fa-user"></i> Log in:</h3>
                                    </div>
                                    <form action="{{route('admin.login')}}" method="post">
                                        @csrf
                                        @method('POST')
                                        <div class="md-form">
                                            <i class="fas fa-envelope prefix white-text"></i>
                                            <input type="text" id="orangeForm-email" class="form-control" name="email" value="{{old('email')}}">
                                            <label for="orangeForm-email">Your email</label>
                                        </div>

                                        <div class="md-form">
                                            <i class="fas fa-lock prefix white-text"></i>
                                            <input type="password" id="orangeForm-pass" class="form-control" name="password" value="{{old('password')}}">
                                            <label for="orangeForm-pass">Your password</label>
                                        </div>

                                        <div class="text-center">
                                            <button class="btn purple-gradient btn-lg" type="submit">Sign up</button>
                                            <hr class="mt-4">
                                            <div class="inline-ul text-center d-flex justify-content-center">
                                                <a class="p-2 m-2 fa-lg tw-ic" href="http://nerdpanda.ir"><i class="fab fa-firefox-browser white-text"></i></a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- Form with header -->

                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Intro Section -->

    </header>
    <!-- Main Navigation -->
@endsection
@push('bodyAttrs')
class='login-page'
@endpush
@push('css')
    <style>
        html,
        body,
        header,
        .view {
            height: 100%;
        }
        @media (min-width: 560px) and (max-width: 740px) {
            html,
            body,
            header,
            .view {
                height: 650px;
            }
        }
        @media (min-width: 800px) and (max-width: 850px) {
            html,
            body,
            header,
            .view  {
                height: 650px;
            }
        }
        .login-page .intro-2 {
            background: url("https://mdbootstrap.com/img/Photos/Horizontal/Nature/full page/img%20(11).jpg") center center no-repeat;
            background-size: auto;
            background-size: cover;
        }
        .login-page .card {
            background-color: rgba(229,228,255,.2);
            margin-top: 30px;
        }
        .login-page .md-form .form-control, .login-page .md-form label {
            color: #fff;
        }
    </style>

@endpush
@push('jsFooter')
    <!-- Custom scripts -->
    <script>

        new WOW().init();

    </script>
@endpush
