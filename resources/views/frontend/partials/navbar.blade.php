<nav class="navbar pt-0 pb-1 navbar-expand-lg navbar-light bg-light">
    <div class="container px-4 px-lg-5">
        <a class="navbar-brand" href="{{ route('home') }}">
            @settings('navbar_logo')
            <img src="{{ asset(AppSettings::get('logo', 'media/logo.webp')) }}" alt="{{ AppSettings::get('site_name', 'Laravel') }} Logo" class="" width="100" height="40">
            @else
            <div class="py-1">{{ AppSettings::get('site_name', 'Laravel') }}</div>
            @endsettings
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                @settings('navbar_links')
                @foreach(AppSettings::get('navbar_links', []) as $name => $link)
                <li class="nav-item">
                    <a class="nav-link @if (request()->is($link) || request()->is($link.'/*')) active @endif text-capitalize" href="{{ empty($link) ? '#': url($link) }}">{{ __($name) }}</a>
                </li>
                @endforeach
                @else
                <li class="nav-item"><a class="nav-link" href="{{ route('pages.show', 'about-us') }}">{{ __('About') }}</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('pages.show', 'contact-us') }}">{{ __('Contact') }}</a></li>
                @endsettings
            </ul>
            <div class="d-flex align-items-center gap-2">
                <livewire:top-cart />

                @auth
                <div class="dropdown flex-fill">
                    <button type="button" class="btn btn-sm btn-outline-dark d-flex align-items-center" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img class="rounded-circle" src="{{ auth()->user()->avatar }}" alt="Header Avatar" style="width: 28px;" />
                        <span class="ms-2">{{ Str::limit(auth()->user()->name, 10) }}</span>
                        <i class="fa fa-fw fa-angle-down ms-1 mt-1"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-md dropdown-menu-end p-0 border-0" aria-labelledby="page-header-user-dropdown">
                        <div class="p-3 text-center bg-body-light border-bottom rounded-top">
                            <img class="img-avatar img-avatar48 img-avatar-thumb" src="{{ auth()->user()->avatar }}" alt="Profile">
                            <p class="mt-2 mb-0 fw-medium">{{ auth()->user()->name }}</p>
                        </div>
                        <div class="p-2">
                            <a class="dropdown-item d-flex align-items-center justify-content-between @if(request()->is('user/dashboard')) active @endif" href="{{ auth()->user()->is_admin? route('admin.dashboard') : route('user.dashboard') }}">
                                <span class="fs-sm fw-medium">{{ __('Dashboard') }}</span>
                            </a>
                            <a class="dropdown-item d-flex align-items-center justify-content-between @if(request()->is('user/profile')) active @endif" href="{{ route('user.profile') }}">
                                <span class="fs-sm fw-medium">{{ __('Profile') }}</span>
                            </a>
                            <a class="dropdown-item d-flex align-items-center justify-content-between @if(request()->is('user/notifications')) active @endif" href="{{ route('user.notifications') }}">
                                <span class="fs-sm fw-medium">{{ __('Notification') }}</span>
                                @if ($unread = auth()->user()->unreadNotifications->count() )
                                <span class="badge rounded-pill bg-primary ms-2">{{ $unread }}</span>
                                @endif
                            </a>

                        </div>
                        <div role="separator" class="dropdown-divider m-0"></div>
                        <div class="p-2">
                            @impersonating()
                            <a class="dropdown-item" href="{{ route('impersonate.leave') }}">{{ __('Log Out') }}</a>
                            @else
                            <form action="{{ route('logout') }}" method="post">
                                @csrf
                                <button type="submit" class="dropdown-item d-flex align-items-center justify-content-between">
                                    <span class="fs-sm fw-medium">{{ __('Log Out') }}</span>
                                </button>
                            </form>
                            @endImpersonating
                        </div>
                    </div>
                </div>
                @else
                <div class="d-flex gap-2">
                    <a href="{{ route('login') }}" class="btn btn-outline-dark">{{ __('Login') }}</a>
                    <a href="{{ route('register') }}" class="btn btn-outline-dark">{{ __('Sign Up') }}</a>
                </div>
                @endauth
            </div>
        </div>
    </div>
</nav>