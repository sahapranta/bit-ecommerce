<?php
$menus = [
    [
        'label' => 'Dashboard',
        'route' => route('user.dashboard', [], false),
        'icon' => 'fa fa-fw fa-tachometer-alt',
    ],
    [
        'label' => 'Profile',
        'route' => route('user.profile', [], false),
        'icon' => 'fa fa-fw fa-user-circle',
    ], [
        'label' => 'Addresses',
        'route' => route('user.addresses', [], false),
        'icon' => 'fa fa-fw fa-map-marker-alt',

    ],
    [
        'label' => 'Order History',
        'route' => route('user.order.history', [], false),
        'icon' => 'fa fa-fw fa-shopping-cart',
    ],
    [
        'label' => 'Track Order',
        'route' => route('user.order.track', [], false),
        'icon' => 'fa fa-fw fa-truck',
    ],
    [
        'label' => 'Notifications',
        'route' => route('user.notifications', [], false),
        'icon' => 'fa fa-fw fa-bell',
    ], [
        'label' => 'Support',
        'route' => route('user.support', [], false),
        'icon' => 'fa fa-fw fa-question-circle',
    ],
];
?>

<div class="block block-rounded">
    <div class="block-header block-header-default">
        <h3 class="block-title">Menu</h3>
    </div>
    <div class="block-content">
        <ul class="nav nav-pills flex-column push">
            @foreach($menus as $menu)
            <li class="nav-item py-1">
                <a href="{{ $menu['route'] }}" @class([ 'nav-link ' , 'active'=> request()->is(substr($menu['route'], 1))
                    ])>
                    <i class="{{ $menu['icon'] }}  me-1"></i> {{ $menu['label'] }}
                </a>
            </li>
            @endforeach
            <li class="nav-item py-1">
                <a href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault(); document.getElementById('user-logout-form').submit();">
                    <i class="fa fa-fw fa-sign-out-alt me-1"></i> Logout
                </a>
                <form id="user-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
            </li>
        </ul>
    </div>
</div>