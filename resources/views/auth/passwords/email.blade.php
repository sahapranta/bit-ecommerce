@extends('layouts.auth')

@section('content')

<div class="row justify-content-center push">
    <div class="col-10">
        @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
        @endif
    </div>

    <div class="col-md-8 col-lg-6 col-xl-4">
        <!-- Reminder Block -->
        <div class="block block-rounded mb-0">
            <div class="block-header block-header-default">
                <h3 class="block-title">{{ __('Password Reminder') }}</h3>
                <div class="block-options">
                    <a class="btn-block-option" href="{{ route('login') }}" data-bs-toggle="tooltip" data-bs-placement="left" title="Sign In">
                        <i class="fa fa-sign-in-alt"></i>
                    </a>
                </div>
            </div>
            <div class="block-content">
                <div class="p-sm-3 px-lg-4 px-xxl-5 py-lg-4">
                    <!-- <h1 class="h2 mb-1">OneUI</h1> -->
                    <p class="fw-medium text-muted">
                        {{ __('Please provide your account’s email.') }}
                    </p>

                    <form class="js-validation-reminder mt-4" action="{{ route('password.email') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <!-- <input type="text" class="form-control " id="reminder-credential" name="reminder-credential" > -->
                            <input placeholder="Email" id="email" type="email" class="form-control form-control-lg form-control-alt @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="d-flex mb-4">

                            <button type="submit" class="btn w-100 btn-alt-primary">
                                <i class="fa fa-fw fa-envelope me-1 opacity-50"></i> {{ __('Send Password Reset Link') }}
                            </button>

                        </div>
                    </form>
                    <!-- END Reminder Form -->
                </div>
            </div>
        </div>
        <!-- END Reminder Block -->
    </div>
</div>
@endsection