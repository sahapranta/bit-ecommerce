@extends('layouts.auth')

@section('content')

<div class="row justify-content-center push">
    <div class="col-md-8 col-lg-6 col-xl-4">
        <!-- Sign In Block -->
        <div class="block block-rounded mb-0">
            <div class="block-header block-header-default">
                <h3 class="block-title">{{ __('Sign In') }}</h3>
                <div class="block-options">
                    <a class="btn-block-option fs-sm" href="{{ route('password.request') }}">Forgot Password?</a>
                    <a class="btn-block-option" href="{{ route('register') }}" data-bs-toggle="tooltip" data-bs-placement="left" title="New Account">
                        <i class="fa fa-user-plus"></i>
                    </a>
                </div>
            </div>
            <div class="block-content">
                <div class="p-sm-3 px-lg-4 px-xxl-5 py-lg-3">
                    <!-- <h1 class="h2 mb-1">OneUI</h1>
                    <p class="fw-medium text-muted">
                        Welcome, please login.
                    </p> -->

                    <form class="js-validation-signin login-form" action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="py-2">
                            <div class="mb-4">
                                <input id="email" placeholder="Email" type="email" class="form-control form-control-alt form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <input id="password" placeholder="Password" type="password" class="form-control form-control-alt form-control-lg @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="login-remember">{{ __('Remember Me') }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex mb-4">

                            <button type="submit" class="btn w-100 btn-alt-primary">
                                <i class="fa fa-fw fa-sign-in-alt me-1 opacity-50"></i> {{ __('Sign In') }}
                            </button>

                        </div>
                    </form>
                    <!-- END Sign In Form -->
                </div>
            </div>
        </div>
        <!-- END Sign In Block -->
        @if(config('app.env') === 'local')
        <div class="block block-rounded mt-2 mb-0">
            <div class="block-content">
                <table class="table table-sm">
                    <tbody>
                        <tr>
                            <td>Admin</td>
                            <td>admin@example.com</td>
                            <td><button onclick="login('admin')" class="btn btn-sm btn-alt-secondary">Login</button></td>
                        </tr>
                        <tr>
                            <td>Customer</td>
                            <td>customer1@example.com</td>
                            <td><button onclick="login('customer1')" class="btn btn-sm btn-alt-secondary">Login</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</div>

@endsection

@if(config('app.env') === 'local')
@section('js')
<script>
    function login(type) {
        document.getElementById('email').value = `${type}@example.com`;
        document.getElementById('password').value = 'password';
        document.querySelector('.login-form').submit();
    }
</script>
@endsection
@endif