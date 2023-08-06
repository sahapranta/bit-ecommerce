<footer id="page-footer" class="bg-body-extra-light">
    <div class="content">
        <div class="mx-auto" style="width:90%;">
            <div class="row items-push fs-sm pt-4">
                <div class="col-md-4">
                    <h3>{{ config('app.name') }}</h3>
                    <div class="fs-sm mb-4">
                        {!! AppSettings::get('address') !!}
                    </div>
                    <!-- <h3>{{ __('Subscribe to our news') }}</h3>
                    <form class="push">
                        <div class="input-group">
                            <input type="email" class="form-control form-control-alt" id="dm-gs-subscribe-email" name="subscribe-email" placeholder="Your email..">
                            <button type="submit" class="btn btn-alt-primary">Subscribe</button>
                        </div>
                    </form> -->
                    @settings('social_links')
                    <h3 class="h3">{{ __('Social Links') }}</h3>
                    <div class="d-flex align-items-center gap-3">
                        @foreach (AppSettings::get('social_links', []) as $name => $link)
                        <a class="text-muted" href="{{$link}}" title="{{$name}}"><i class="fab fa-2x fa-{{$name}}"></i></a>
                        @endforeach
                    </div>
                    @endsettings
                </div>
                <div class="col-sm-6 col-md-4">
                    <h4 class="h4 text-muted">Information</h4>
                    <ul class="list list-simple-mini">
                        @foreach (AppSettings::get('information_links', []) as $name => $link)
                        <li>
                            <a title="{{ $name }}" class="fw-semibold @if (request()->is($link) || request()->is($link.'/*')) active @endif" href="{{ empty($link) ? '#': url($link) }}">
                                <i class="fa fa-fw fa-link text-primary-lighter me-1"></i> {{ $name }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-sm-6 col-md-4">
                    <h4 class="text-muted h4">Quick Links</h4>
                    <ul class="list list-simple-mini">
                    @foreach (AppSettings::get('quick_links', []) as $name => $link)
                        <li>
                            <a title="{{ $name }}" class="fw-semibold @if (request()->is($link) || request()->is($link.'/*')) active @endif" href="{{ empty($link) ? '#': url($link) }}">
                                <i class="fa fa-fw fa-link text-primary-lighter me-1"></i> {{ $name }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>

            </div>
        </div>
    </div>
    <div class="bg-dark py-3">
        <p class="m-0 text-center text-white">{!! AppHelper::replacePlaceholders(AppSettings::get('copyright_note'), ['sitename' => AppSettings::get('site_name', 'Laravel'), 'year'=> date('Y') ]) !!}</p>
    </div>
</footer>