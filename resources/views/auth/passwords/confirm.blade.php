@extends('layouts.simple')

@section('content')
<div class="row justify-content-center push">

    <div class="col-md-8 col-lg-6 col-xl-4">
        <div class="block block-rounded mb-0">
            <div class="block-header block-header-default">{{ __('Confirm Password') }}</div>

            <div class="block-content">
                <div class="p-sm-3 px-lg-4 px-xxl-5 py-lg-4">
                    {{ __('Please confirm your password before continuing.') }}

                    <form method="POST" action="{{ route('password.confirm') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="password" class="text-md-end">{{ __('Password') }}</label>
                            <input id="password" type="password" class="form-control form-control-lg form-control-alt @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror

                        </div>

                        <div class="mb-0">
                            <button type="submit" class="btn btn-alt-primary w-100">
                                {{ __('Confirm Password') }}
                            </button>

                            @if (Route::has('password.request'))
                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                            @endif

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection