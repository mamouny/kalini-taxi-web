@extends('admin.layout.auth-layout')
@section('title', @trans('traduction.login'))
@section('content')
<div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xxl-4 col-lg-6">
                <div class="card">

                    <!-- Logo -->
                    <div class="card-header py-2 text-center bg-warning text-white">
{{--                        <a href="#">--}}
{{--                            <span><img src="{{asset('assets/images/logo.png')}}" alt="" height="18"></span>--}}
{{--                        </a>--}}
                        <h4>{{trans('traduction.login_admin_title')}}</h4>
                    </div>

                    <div class="card-body p-3">

                        <div class="text-center w-75 m-auto">
                            <h4 class="text-dark-50 text-center pb-0 fw-bold">{{trans('traduction.login')}}</h4>
                            <p class="text-muted mb-3">{{trans('traduction.enter_ids')}}</p>
                        </div>

                        @if(session()->has('info'))
                            <div class="alert alert-info alert-dismissible fade show" role="alert">
                                <strong>{{session()->get('info')}}</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if(session()->has('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>{{session()->get('error')}}</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{route('login')}}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">{{trans('traduction.login')}}</label>
                                <input class="form-control @error('email') is-invalid @enderror"type="text" id="email" name="email" required=""
                                       placeholder="{{trans('traduction.enter_email')}}" value="{{old('email')}}">
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>

                            <div class="mb-3">
{{--                                @if (Route::has('password.request'))--}}
{{--                                    <a href="{{ route('password.request') }}" class="text-muted float-end"><small>{{trans('traduction.forgot_password')}}</small></a>--}}
{{--                                @endif--}}
                                <label for="password" class="form-label">{{trans('traduction.password')}}</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" class="form-control @error('password') is-invalid @enderror"  name="password"
                                           placeholder="{{trans('traduction.enter_password')}}">
                                    <div class="input-group-text" data-password="false">
                                        <span class="password-eye"></span>
                                    </div>
                                </div>
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>

{{--                            <div class="mb-3 mb-3">--}}
{{--                                <div class="form-check">--}}
{{--                                    <input class="form-check-input" id="checkbox-signin" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>--}}
{{--                                    <label class="form-check-label" for="checkbox-signin">{{trans('traduction.remember_me')}}</label>--}}
{{--                                </div>--}}
{{--                            </div>--}}

                            <div class="mb-3 mb-0 text-center">
                                <button class="btn btn-warning text-white w-50" type="submit"> {{trans("traduction.submit_login")}} <i class="mdi mdi-login"></i> </button>
                            </div>

                        </form>
                    </div> <!-- end card-body -->
                </div>
                <!-- end card -->

                <!-- end row -->

            </div> <!-- end col -->
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
</div>
@endsection
