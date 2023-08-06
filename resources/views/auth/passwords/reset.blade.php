@extends('layouts.auth')

@section('content')

<div class="row justify-content-center push">
    <div class="col-md-8 col-lg-6 col-xl-4">
        <div class="block block-rounded mb-0">
            <div class="block-header block-header-default">
                <div class="block-title">{{ __('Reset Password') }}</div>
                <div class="block-options">
                    <a class="btn-block-option" href="{{ route('login') }}" data-bs-toggle="tooltip" data-bs-placement="left" title="Sign In">
                        <i class="fa fa-sign-in-alt"></i>
                    </a>
                </div>
            </div>

            <div class="block-content">
                <div class="p-sm-3 px-lg-4 px-xxl-5 py-lg-4">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="mb-4">
                            <input id="email" type="email" class="form-control form-control-lg form-control-alt @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <input id="password" placeholder="Password" type="password" class="form-control form-control-lg form-control-alt @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror

                        </div>

                        <div class="mb-4">
                            <input id="password-confirm" type="password" class="form-control form-control-lg form-control-alt" placeholder="Confirm Password" name="password_confirmation" required autocomplete="new-password">
                        </div>

                        <div class="mb-2">
                            <button type="submit" class="btn w-100 btn-alt-primary">
                                {{ __('Reset Password') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection