@extends('layouts.backend')

@section('content')
<!-- Hero -->
<div class="bg-body-light">
  <div class="content py-3">
    <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
      <div class="flex-grow-1">
        <h1 class="h3 fw-bold mb-1">
          Settings
        </h1>
        <h2 class="fs-base lh-base fw-medium text-muted mb-0">
          Set your site settings here
        </h2>
      </div>
      <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-alt">
          <li class="breadcrumb-item">
            <a class="link-fx" href="javascript:void(0)">Index</a>
          </li>
          <li class="breadcrumb-item" aria-current="page">
            Settings
          </li>
        </ol>
      </nav>
    </div>
  </div>
</div>

@if (session()->has('success'))
<x-alert type="success" :message="session('success')" />
@endif
@if (session()->has('error'))
<x-alert type="error" :message="session('error')" />
@endif

<!-- Page Content -->
<div class="content">
  <div class="row">
    <div class="col-12">
      <div class="block block-rounded row g-0 shadow">

        <ul class="nav nav-tabs nav-tabs-block flex-md-column col-md-3 col-xxl-2 bg-light" role="tablist">
          @php
          $navItems = [
          [
          'targetId' => 'homepage',
          'icon' => 'fa-home',
          'title' => 'Home',
          'description' => 'Check out your main activity and any pending notifications',
          ],
          [
          'targetId' => 'homepage-links',
          'icon' => 'fa-link',
          'title' => 'Links',
          'description' => 'Update your public information and promote your projects',
          ],
          [
          'targetId' => 'admin-settings',
          'icon' => 'fa-cog',
          'title' => 'Admin Settings',
          'description' => 'Update your email, password and set up your recovery options',
          ],
          ];

          function isActive($targetId, $isFirst)
          {
          $id = request('id');
          return $id === $targetId ? 'active' : ($isFirst && !$id ? 'active' : '');
          }
          @endphp
          <ul class="nav">
            @foreach($navItems as $item)
            <li class="nav-item d-md-flex flex-md-column">
              <button class="nav-link text-md-start {{ isActive($item['targetId'], $loop->first) }}" id="{{ $item['targetId'] }}-tab" data-bs-toggle="tab" data-bs-target="#{{ $item['targetId'] }}" role="tab" aria-controls="{{ $item['targetId'] }}" aria-selected="{{ isActive($item['targetId'], $loop->first) ? 'true' : 'false' }}">
                <i class="fa fa-fw {{ $item['icon'] }} opacity-50 me-1 d-none d-sm-inline-block"></i>
                <span>{{ $item['title'] }}</span>
                <span class="d-none d-md-block fs-xs fw-medium opacity-75 mt-md-2">
                  {{ $item['description'] }}
                </span>
              </button>
            </li>
            @endforeach
          </ul>

        </ul>

        <div class="tab-content col-md-9 col-xxl-10">
          <div class="block-content tab-pane {{ request('id') == 'homepage' || !request('id') ? 'active' : '' }}" id="homepage" role="tabpanel" aria-labelledby="homepage-tab" tabindex="0">
            <h4 class="fw-semibold">Base Settings</h4>
            <form method="post">
              @csrf
              <button type="submit" class="btn btn-warning me-1 need-confirm" formaction="{{ route('admin.settings.action', 'clearCache') }}">
                <i class="fa fa-fw fa-refresh"></i>
                Clear Cache
              </button>

              <button type="submit" class="btn btn-danger me-1 need-confirm" formaction="{{ route('admin.settings.action', 'clearCacheForce') }}">
                <i class="fa fa-fw fa-trash"></i>
                Force Clear
              </button>
            </form>
            <div class="border my-4 p-3">
              <form action="{{ route('admin.settings.action', 'homepage') }}" method="post" x-ref="foo">
                @csrf
                <x-input name="site_name" placeholder="Website Name" :value="$settings->site_name" />
                <x-input name="site_title" label="Site Title or Slogan" placeholder="Slogan" :value="$settings->site_title" />
                <x-input type="number" label="Home Products Show" name="product_per_page" placeholder="Products Per Page" :value="$settings->product_per_page" />
                <div class="row">
                  <div class="col-md-8 mb-4">
                    <label class="form-label" for="logo">Site Logo</label>
                    <input type="file" name="logo" id="logo" class="form-control">
                  </div>
                  <div class="col-md-4">
                    <img src="{{ asset('media/logo.webp') }}" alt="logo" width="100%" class="shadow" id="site_logo" style="max-height: 80px;">
                  </div>
                  <div class="col-md-12">
                    <div class="mb-4">
                      <label class="form-label" for="address">Address</label>
                      <textarea name="address" id="address" class="form-control" rows="2">{{ $settings->address }}</textarea>
                    </div>
                  </div>
                </div>
                <x-input name="copyright_note" :value="$settings->copyright_note" />

                <div class="d-flex justify-content-start my-4">
                  <button type="submit" class="btn btn-primary" x-on:click.prevent="$nextTick(() => { $refs.foo.submit() })">
                    <i class="fa fa-fw fa-save me-1"></i>
                    {{ __('Update Settings') }}
                  </button>
                </div>
              </form>
            </div>

          </div>

          <div class="block-content tab-pane {{ request('id') == 'homepage-links' ? 'active' : '' }}" id="homepage-links" role="tabpanel" aria-labelledby="homepage-links-tab" tabindex="0">
            <h4 class="fw-semibold">Homepage Links</h4>
            <form action="{{ route('admin.settings.action', 'homepage') }}" method="post" x-ref="foo">
              @csrf
              <x-key-value-input name="navbar_links" :data="optional($settings)->navbar_links" />
              <x-key-value-input name="information_links" :data="optional($settings)->information_links" />
              <x-key-value-input name="quick_links" :data="optional($settings)->quick_links" />
              <x-key-value-input name="social_links" :data="$settings->social_links" />


              <div class="d-flex justify-content-start my-4">
                <button type="submit" class="btn btn-primary" x-on:click.prevent="$nextTick(() => { $refs.foo.submit() })">
                  <i class="fa fa-fw fa-save me-1"></i>
                  {{ __('Update Settings') }}
                </button>
              </div>
            </form>
          </div>

          <div class="block-content tab-pane {{ request('id') == 'admin-settings' ? 'active' : '' }}" id="admin-settings" role="tabpanel" aria-labelledby="admin-settings-tab" tabindex="0">
            <h4 class="fw-semibold">Admin Settings</h4>
            <form action="{{ route('admin.settings.action', 'homepage') }}" method="post" x-ref="admin">
              @csrf
              <x-input name="default_paginate_limit" label="Pagination Limit" :value="$settings->default_paginate_limit" type="number" />

              <div class="d-flex gap-4">
                <x-input name="currency_symbol" label="Currency Sign" :value="$settings->currency_symbol" />
                <x-input name="currency_code" label="Currency Code" :value="$settings->currency_code" />
                <x-input name="currency_rate" label="Currency Rate" :value="$settings->currency_rate" type="number" step="any" min="0" info="Change with cautious, it will cause the whole site." />
              </div>

              <div class="">
                <div class="mb-4">
                  <div class="form-check form-switch">
                    <label class="form-check-label" for="tax_enabled">{{ $settings->tax_enabled ? 'Disable Tax' : 'Enable Tax' }}</label>
                    <input class="form-check-input" type="checkbox" value="on" id="tax_enabled" name="tax_enabled" {{ $settings->tax_enabled ? 'checked' : '' }}>
                  </div>
                </div>

                <x-input name="tax_rate" label="Tax Rate" :value="$settings->tax_rate" type="number" step="any" min="0" :disabled="!$settings->tax_enabled" />
              </div>

              <div class="mb-4">
                <div class="form-check form-switch">
                  <label class="form-check-label" for="comment_system_enabled">{{ $settings->comment_system_enabled ? 'Disable' : 'Enable' }} Comment System</label>
                  <input class="form-check-input" type="checkbox" value="on" id="comment_system_enabled" name="comment_system_enabled" {{ $settings->comment_system_enabled ? 'checked' : '' }}>
                </div>
              </div>

              <div class="mb-4">
                <div class="form-check form-switch">
                  <label class="form-check-label" for="review_system_enabled">{{ $settings->review_system_enabled ? 'Disable' : 'Enable' }} Review System</label>
                  <input class="form-check-input" type="checkbox" value="on" id="review_system_enabled" name="review_system_enabled" {{ $settings->review_system_enabled ? 'checked' : '' }}>
                </div>
              </div>


              <x-key-value-input name="delivery_methods" :data="$settings->delivery_methods" />

              <div class="d-flex justify-content-start my-4">
                <button type="submit" class="btn btn-primary" x-on:click.prevent="$nextTick(() => { $refs.admin.submit() })">
                  <i class="fa fa-fw fa-save me-1"></i>
                  {{ __('Update Settings') }}
                </button>
              </div>
            </form>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('js')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script src="{{ asset('js/lib/jquery.min.js') }}"></script>
@include('backend.partials.swal')
<script>
  $(document).ready(function() {
    $('.need-confirm').click(async function(e) {
      e.preventDefault();
      e.stopPropagation();
      if (!await confirmation("Clearing Cache will slowdown, your site for a while!", {
          confirmButtonText: "Yes, Clear Now!",
        })) return false;
      $(this).attr('disabled', true);
      $(this).append('<i class="fa fa-fw fa-spin fa-spinner"></i>');
      $(this).closest('form').attr('action', $(this).attr('formaction')).submit();
    });

    $('#tax_enabled').change(function() {
      if ($(this).is(':checked')) {
        $(this).closest('form').find('input[name="tax_rate"]').attr('disabled', false);
      } else {
        $(this).closest('form').find('input[name="tax_rate"]').attr('disabled', true);
      }
    });

    $('#logo').change(function() {
      if (this.files && this.files[0]) {
        var r = new FileReader();
        r.onload = (e) => $('#site_logo').attr('src', e.target.result);
        r.readAsDataURL(this.files[0]);
      }
    });

    $('.nav-tabs button').click(function() {
      // add url hash without scrolling
      // window.location.hash = ;
      history.replaceState(null, null, '?id=' + $(this).attr('id').replace('-tab', ''));
      // stop scrolling
      return false;
    });
  });
</script>

@endsection