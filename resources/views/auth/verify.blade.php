@extends('layouts.simple')

@section('content')
<div class="row justify-content-center push">
    <div class="col-md-8 col-lg-6 col-xl-4">
        <div class="block block-rounded mb-0">
            <div class="block-header block-header-default">{{ __('Verify Your Email Address') }}</div>

            <div class="block-content">
                <div class="p-sm-3 px-lg-4 px-xxl-5 py-lg-4">
                    @if (session('resent'))
                    <div class="alert alert-success" role="alert">
                        {{ __('A fresh verification link has been sent to your email address.') }}
                    </div>
                    @endif

                    {{ __('Before proceeding, please check your email for a verification link.') }}
                    {{ __('If you did not receive the email') }},
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection