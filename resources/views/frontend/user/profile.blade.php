@extends('layouts.simple')

@section('content')
<x-hero />

<!-- Page Content -->
<div class="content content-boxed">
    <div class="row">
        <div class="col-md-4">
            @include('frontend.user._sidebar')
        </div>
        <div class="col-md-8">
            <!-- User Profile -->
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">User Profile</h3>
                </div>
                <div class="block-content">
                    <div class="row push">
                        <div class="col-lg-4">
                            <p class="fs-sm text-muted">
                                Your account’s vital info. Your username will be publicly visible.
                            </p>
                        </div>
                        <div class="col-lg-8 col-xl-5">
                            <form action="{{ route('user.profile.update') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                                <x-input name="name" label="Full Name" value="{{ $user->name }}" />
                                <x-input name="email" value="{{ $user->email }}" info="Changing Email Requires Verification" />
                                <div class="mb-4">
                                    <label class="form-label">Your Avatar</label>
                                    <div class="mb-4">
                                        <img class="img-avatar" src="{{ $user->avatar }}" alt="User Avatar">
                                    </div>
                                    <div class="mb-4">
                                        <label for="avatar" class="form-label">Choose a new avatar</label>
                                        <input class="form-control @error('avatar') is-invalid @enderror" name="avatar" type="file" id="avatar">
                                        @error('avatar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @else
                                        <div class="feedback fs-sm mt-1">
                                            Support: jpeg, png, jpg. Maximum: <kbd>300kb</kbd>
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <button type="submit" class="btn btn-alt-primary">
                                        Update
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END User Profile -->

            <!-- Change Password -->
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Change Password</h3>
                </div>
                <div class="block-content">
                    <form action="{{ route('user.profile.password') }}" method="POST">
                        @csrf
                        <div class="row push">
                            <div class="col-lg-4">
                                <p class="fs-sm text-muted">
                                    Changing your sign in password is an easy way to keep your account secure.
                                </p>
                            </div>
                            <div class="col-lg-8 col-xl-5">
                                <x-input name="old_password" label="Current Password" type="password" required />
                                <x-input name="password" label="New Password" type="password" required />
                                <x-input name="password_confirmation" label="Confirm New Password" type="password" required />
                                <div class="mb-4">
                                    <button type="submit" class="btn btn-alt-primary">
                                        Update
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- END Change Password -->

            <!-- Billing Information -->
            <!-- <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Billing Information</h3>
                </div>
                <div class="block-content">
                    <form action="be_pages_projects_edit.html" method="POST" onsubmit="return false;">
                        <div class="row push">
                            <div class="col-lg-4">
                                <p class="fs-sm text-muted">
                                    Your billing information is never shown to other users and only used for creating your invoices.
                                </p>
                            </div>
                            <div class="col-lg-8 col-xl-5">
                                <div class="mb-4">
                                    <label class="form-label" for="one-profile-edit-company-name">Company Name (Optional)</label>
                                    <input type="text" class="form-control" id="one-profile-edit-company-name" name="one-profile-edit-company-name">
                                </div>
                                <div class="row mb-4">
                                    <div class="col-6">
                                        <label class="form-label" for="one-profile-edit-firstname">Firstname</label>
                                        <input type="text" class="form-control" id="one-profile-edit-firstname" name="one-profile-edit-firstname">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label" for="one-profile-edit-lastname">Lastname</label>
                                        <input type="text" class="form-control" id="one-profile-edit-lastname" name="one-profile-edit-lastname">
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label" for="one-profile-edit-street-1">Street Address 1</label>
                                    <input type="text" class="form-control" id="one-profile-edit-street-1" name="one-profile-edit-street-1">
                                </div>
                                <div class="mb-4">
                                    <label class="form-label" for="one-profile-edit-street-2">Street Address 2</label>
                                    <input type="text" class="form-control" id="one-profile-edit-street-2" name="one-profile-edit-street-2">
                                </div>
                                <div class="mb-4">
                                    <label class="form-label" for="one-profile-edit-city">City</label>
                                    <input type="text" class="form-control" id="one-profile-edit-city" name="one-profile-edit-city">
                                </div>
                                <div class="mb-4">
                                    <label class="form-label" for="one-profile-edit-postal">Postal code</label>
                                    <input type="text" class="form-control" id="one-profile-edit-postal" name="one-profile-edit-postal">
                                </div>
                                <div class="mb-4">
                                    <label class="form-label" for="one-profile-edit-vat">VAT Number</label>
                                    <input type="text" class="form-control" id="one-profile-edit-vat" name="one-profile-edit-vat" value="IT00000000" disabled>
                                </div>
                                <div class="mb-4">
                                    <button type="submit" class="btn btn-alt-primary">
                                        Update
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div> -->
            <!-- END Billing Information -->

            @settings('social_login_enabled')
            <!-- Connections -->
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Connections</h3>
                </div>
                <div class="block-content">
                    <div class="row push">
                        <div class="col-lg-4">
                            <p class="fs-sm text-muted">
                                You can connect your account to third party networks to get extra features.
                            </p>
                        </div>
                        <div class="col-lg-8 col-xl-7">
                            <div class="row mb-4">
                                <div class="col-sm-10 col-md-8 col-xl-6">
                                    <a class="btn w-100 btn-alt-danger text-start" href="javascript:void(0)">
                                        <i class="fab fa-fw fa-google opacity-50 me-1"></i> Connect to Google
                                    </a>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-sm-10 col-md-8 col-xl-6">
                                    <a class="btn w-100 btn-alt-info text-start" href="javascript:void(0)">
                                        <i class="fab fa-fw fa-twitter opacity-50 me-1"></i> Connect to Twitter
                                    </a>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-sm-10 col-md-8 col-xl-6">
                                    <a class="btn w-100 btn-alt-primary bg-white d-flex align-items-center justify-content-between" href="javascript:void(0)">
                                        <span>
                                            <i class="fab fa-fw fa-facebook me-1"></i> John Doe
                                        </span>
                                        <i class="fa fa-fw fa-check me-1"></i>
                                    </a>
                                </div>
                                <div class="col-sm-12 col-md-4 col-xl-6 mt-1 d-md-flex align-items-md-center fs-sm">
                                    <a class="btn btn-sm btn-alt-secondary rounded-pill" href="javascript:void(0)">
                                        <i class="fa fa-fw fa-pencil-alt me-1"></i> Edit Facebook Connection
                                    </a>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-sm-10 col-md-8 col-xl-6">
                                    <a class="btn w-100 btn-alt-warning bg-white d-flex align-items-center justify-content-between" href="javascript:void(0)">
                                        <span>
                                            <i class="fab fa-fw fa-instagram me-1"></i> @john_doe
                                        </span>
                                        <i class="fa fa-fw fa-check me-1"></i>
                                    </a>
                                </div>
                                <div class="col-sm-12 col-md-4 col-xl-6 mt-1 d-md-flex align-items-md-center fs-sm">
                                    <a class="btn btn-sm btn-alt-secondary rounded-pill" href="javascript:void(0)">
                                        <i class="fa fa-fw fa-pencil-alt me-1"></i> Edit Instagram Connection
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END Connections -->
            @endsettings
        </div>
    </div>
</div>

@endsection

@section('js')
<script>
    document.querySelectorAll('button[type="submit"]')
        .forEach(function(element) {
            element.addEventListener('click', function(e) {
                e.preventDefault();
                this.setAttribute('disabled', true);
                this.classList.add('disabled');
                this.insertAdjacentHTML('beforeend', '<i class="fa fa-spinner fa-spin"></i>...');
                this.form.submit();
            });
        });
</script>
@endsection