<?php

if (!function_exists('placeholder')) {
    function placeholder()
    {
        return asset(config('app.placeholder_image'));
    }
}

if (!function_exists('load_placeholder')) {
    function load_placeholder()
    {
        return "this.onerror=null;this.src='" . placeholder() . "'";
    }
}
