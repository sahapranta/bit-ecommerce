@props(['item', 'parent' => false])

<li class="nav-main-item @if($parent && request()->is($item['active'])) open @endif">
    <a @class([
        'nav-main-link',
        'active' => request()->is($item['active']),
        'nav-main-link-submenu' => $parent,
        ]) <?= $parent ? 'data-toggle="submenu" aria-haspopup="true" aria-expanded="true" href="#"' : 'href="' . route($item['route']) . '"' ?>>
        <i class="nav-main-link-icon {{ $item['icon'] }}"></i>
        <span class="nav-main-link-name">
            @if ($slot != '')
                {{ $slot }}
            @else
                {{ $item['name'] }}
            @endif
        </span>
    </a>
    @if ($parent)
        <ul class="nav-main-submenu">
          {{ $child }}
        </ul>
    @endif
</li>