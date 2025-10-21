@extends('layouts.app')
@section('content')
    <div class="row ">
        <div class="col-md-7 vid">
            <div class="video-container">
                <video autoplay muted loop>
                    <source src="{{ asset('video/vid1.mp4') }}" type="video/mp4" />
                </video>
                <div class=" logo caption "> <img class="" src="{{ asset('video/lg.png') }}" alt=""> </div>
            </div>
        </div>
        <div class="col-md-5 login ">
            <div class="row">
                <div class="card col-lg-7 bg_bl">
                    <div class="card-body p-4">
                        <p class="cn1z"><img class="w_100" src="{{ asset('css/img/sims1.png') }}" alt=""> </p>
                        <!-- <h1>{{ trans('panel.site_title') }}</h1> -->
                        <div class="in_tex">
                            <P class="p_1">Welcome back,</p>
                            <p class="p_2">Sign in to your Account</p>
                        </div>
                        <!-- {{ trans('global.login') }} -->
                        @if (session('message'))
                            <div class="alert alert-info" role="alert">
                                {{ session('message') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fi fi-rr-at"></i>
                                    </span>
                                </div>

                                <input id="email" name="email" type="text"
                                    class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" required
                                    autocomplete="email" autofocus placeholder="{{ trans('global.login_email') }}"
                                    value="{{ old('email', null) }}">

                                @if ($errors->has('email'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('email') }}
                                    </div>
                                @endif
                            </div>

                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fi fi-rr-fingerprint"></i></span>
                                </div>

                                <input id="password" name="password" type="password"
                                    class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" required
                                    placeholder="{{ trans('global.login_password') }}">

                                @if ($errors->has('password'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('password') }}
                                    </div>
                                @endif
                            </div>

                            <div class="input-group mb-4">
                                <div class="form-check checkbox cnal">
                                    <input class="form-check-input" name="remember" type="checkbox" id="remember"
                                        style="vertical-align: middle;" />
                                    <label class="form-check-label texlb" for="remember" style="vertical-align: middle;">
                                        {{ trans('global.remember_me') }}
                                    </label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 tc">
                                    <button type="submit" class="btn btn-primary px-4">
                                        {{ trans('global.login') }}
                                    </button>
                                </div>
                                <!-- <div class="col-6 text-right">
                                    @if (Route::has('password.request'))
    <a class="btn btn-link px-0" href="{{ route('password.request') }}">
                                            {{ trans('global.forgot_password') }}
                                        </a><br>
    @endif

                                </div> -->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;400;500;600;700;800&display=swap');
        @import url('https://cdn-uicons.flaticon.com/uicons-regular-rounded/css/uicons-regular-rounded.css');

        @import url('https://cdn-uicons.flaticon.com/uicons-solid-rounded/css/uicons-solid-rounded.css');

        @import url('https://cdn-uicons.flaticon.com/uicons-bold-rounded/css/uicons-bold-rounded.css');

        body {
            font-family: 'Manrope', sans-serif;
        }

        .logo {
            width: 40%;
            margin: 38% auto 6% auto;
        }

        .btn-primary.focus,
        .btn-primary:focus {
            box-shadow: 0 0 0 .2rem rgba(65, 181, 222, 0);
        }

        .logo img {
            width: 100%;
        }

        .cn1z {
            font-size: 18px;
            font-weight: 800;
            margin-left: 0px;
            /* margin-top: 20px; */
            color: #2b68e8;
            /* background: #dfebf5; */
            /* padding: 20px 30px; */
            border-radius: 10px;
            text-align: center;
            margin-bottom: 30px;
            /* position: absolute; */
            /* right: 157px; */
            width: 100%;
            overflow: hidden;
            margin-top: 20px;
        }

        .w_100 {
            width: 100%;
        }

        .bg_bl {
            background: #fff;
            /* width: 50%; */
            margin: 23% auto;
            /* min-height: 400px; */
            padding-top: 10px;
            padding-bottom: 30px;
            box-shadow: 0px 12px 23px 0px rgba(112, 112, 112, 0.04);
            height: calc(100% - 30px);
            border: 0px;
        }

        .texlb {
            vertical-align: middle;
            font-size: 12px;
            /* margin-bottom: 2px; */
            /* font-weight: 800; */
        }

        .cnal {
            /* text-align: center; */
            margin: 0px auto;
        }

        .in_tex {
            margin-top: 60px;
            text-align: center;
        }

        .tc {
            text-align: center;
        }

        .p_1 {
            font-size: 12px;
            font-weight: 800;
            margin-bottom: 1px;
        }

        .p_2 {
            font-size: 16px;
            font-weight: 800;
        }

        .vid {
            background: #d7d5cf;
            min-height: 100vh;
            overflow: hidden;
        }

        .login {
            background: #f4f5f9;
            min-height: 100vh;
        }

        .form-control {
            border-radius: 13px;
            background: transparent;
            border-bottom: 1px solid #f0f1f5;
            color: #000;
            height: 56px;
            /* border: 1px; */
            border: 2px solid #949497;
            color: #404040;
            border-left: 0px;
        }

        .input-group-text {
            /* display: -ms-flexbox; */
            border: 2px solid #949497;
            border-radius: 13px 0px 13px 13px !important;
            padding: .375rem 1rem;
        }

        .mr10 {
            margin-right: 10px;
            display: block;
            float: left;
            margin-top: 2px;
        }

        .form-control:hover,
        .form-control:focus,
        .form-control.active {
            box-shadow: none;
            /* background: #fff; */
            color: #020202;
        }

        .video-container video {
            min-width: 100%;
            min-height: 100%;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translateX(-50%) translateY(-50%);
        }

        .btn-primary:hover {
            color: #2b68e8;
            background-color: #fff;
            border-color: #2b68e8;
            border: 2px solid #2b68e8;
            font-weight: 800;
        }

        .btn-primary {
            color: #fff;
            background-color: #2b68e8;
            border-color: #2b68e8;
            border: 2px solid #2b68e8;
            font-weight: 800;
        }

        /* Just styling the content of the div, the *magic* in the previous rules */
        .video-container .caption {
            z-index: 1;
            position: relative;
            text-align: center;
            color: #dc0000;
            padding: 10px;
        }

        .card {

            border-radius: 20px;
        }

        @media (max-width: 767.98px) {

            .bg_bl {

                margin: 5% auto;

            }

            .logo {
                width: 68%;
                margin: 11% auto 6% auto;
            }

            .vid {
                background: #d7d5cf;
                min-height: 5vh;
                overflow: hidden;
            }
        }
    </style>
@endsection
