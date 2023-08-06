<?php

namespace App\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Str;

class AdminMenuComposer
{
    public function compose(View $view): void
    {
        $view->with('menu_links', $this->adminRouteBuilder());
    }


    protected function adminRouteBuilder(): array
    {
        $defaultChildren = [
            ['label' => 'All', 'icon' => 'list', 'route' => 'index'],
            ['label' => 'Add', 'icon' => 'plus', 'route' => 'create'],
        ];

        $links = [
            'Store',
            $this->makeLinks('Order', 'handbag'),
            $this->makeLinks('Product', 'present',  [...$defaultChildren, ['label' => 'Stock', 'route' => 'stock', 'icon' => 'calculator']]),
            $this->makeLinks('Category', 'organization',  $defaultChildren),
            $this->makeLinks('Customer', 'users'),
            'Content',
            $this->makeLinks('Pages', 'layers',  $defaultChildren),
            // $this->makeLinks('Subscriber', 'people',  $defaultChildren),
            ['name'=>'Notification', 'route'=>'admin.notifications.index', 'icon'=>'si si-bell', 'active'=>'admin/notifications*'],
            $this->makeLinks('Settings', 'settings'),
        ];

        return $links;
    }

    protected function makeLinks($name, $icon, $children = null): array
    {
        $plural = Str::of($name)->plural()->lower();

        $link = [
            'name' => $name,
            'route' => "admin.$plural.index",
            'icon' => "si si-$icon",
            'active' => "admin/$plural*",
        ];

        if ($children) {
            foreach ($children as $key => $value) {
                $route = "admin.$plural." . $value['route'];
                $active = route($route,  absolute: false);
                $active = substr($active, 1);

                $link['children'][] = [
                    'name' => $value['label'] . " $name",
                    'route' => $route,
                    'icon' => "si si-" . $value['icon'],
                    'active' => $active,
                ];
            }
        }

        return $link;
    }
}
