<?php

use Illuminate\Support\Facades\Route;


Route::namespace('App\Http\Controllers\Admin')->name('admin.')->group(function () {

    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
    Route::get('/user/profile', 'DashboardController@profile')->name('user.profile');
    Route::get('/notifications', 'DashboardController@notifications')->name('notifications.index');

    // Route::resource('users', 'UserController', ['except' => ['show', 'create', 'store']]);
    Route::get('/stock', 'ProductController@stock')->name('products.stock');
    Route::post('/stock', 'ProductController@stockUpdate')->name('products.stock.update');
    Route::get('/products/search', 'ProductController@search')->name('products.search');
    Route::resource('products', 'ProductController');
    Route::resource('customers', 'CustomerController');
    Route::get('/orders/{order}/invoice', 'OrderController@invoice')->name('orders.invoice');
    Route::get('/orders/{order}/sendmail', 'OrderController@sendmail')->name('orders.mail');
    Route::resource('orders', 'OrderController');
    Route::resource('categories', 'CategoryController');
    // Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::post('/pages/upload', 'PageController@upload')->name('pages.upload');
    Route::resource('pages', 'PageController');
    Route::resource('subscribers', 'SubscriberController');

    Route::get('/settings', 'SettingController@index')->name('settings.index');
    Route::post('/settings/{action}', 'SettingController@action')->name('settings.action');
});
