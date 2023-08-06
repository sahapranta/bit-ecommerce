<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SubscriptionController;

Route::redirect('/home', '/');
Route::redirect('/admin', '/admin/dashboard');

Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('home');
    Route::get('/category/{category:slug}', 'category')->name('home.products.category');
    Route::get('/tags/{tag}', 'productByTags')->name('home.products.tags');
});

Route::get('/search', [StoreController::class, 'search'])->name('search');
Route::get('/product/{product:slug}', [StoreController::class, 'product'])->name('product.view');
Route::get('/track-order', [StoreController::class, 'trackOrder'])->name('order.track');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::controller(StoreController::class)->group(function () {
        Route::get('/checkout', 'showCheckout')->name('checkout');
        Route::post('/checkout', 'checkout')->name('checkout.process');
        Route::get('/order-confirmed/{order:order_id}', 'confirm')->name('checkout.confirm');
        Route::get('/invoice/{order:order_id}', 'invoice')->name('checkout.invoice');
    });

    Route::controller(CustomerController::class)->prefix('/user')->name('user.')->group(function () {
        Route::get('/order-history', 'orderHistory')->name('order.history');
        Route::get('/profile', 'profile')->name('profile');
        Route::post('/profile', 'update')->name('profile.update');
        Route::post('/profile/password', 'updatePassword')->name('profile.password');
        Route::get('/addresses', 'addresses')->name('addresses');
        Route::get('/support', 'support')->name('support');
        Route::post('/support', 'supportSave')->name('support.save');
        Route::get('/notifications', 'notifications')->name('notifications');
        Route::get('/track-order', 'trackOrder')->name('order.track');
        Route::post('/order-cancel/{order:order_id}')->name('order.cancel');
        Route::get('/dashboard', 'dashboard')->name('dashboard');
        Route::get('/notifications/mark-all-read', 'markAllNotificationsAsRead')->name('notifications.mark-all-read');
        Route::get('/notifications/delete-all-read', 'deleteReadNotifications')->name('notifications.delete-all-read');
    });

    Route::post('/image-upload', [UploadController::class, 'upload'])->name('image.upload');
});

// Route::get('/test-email', function () {
//     $order = App\Models\Order::with(['items', 'items.product'])->latest()->first();
//     return new App\Mail\OrderSuccess($order);
// });

Route::impersonate();

Route::middleware('signed')
    ->controller(SubscriptionController::class)
    ->prefix('/subscriber')
    ->group(function () {
        Route::get('/verify/{subscriber:verification_code}', 'verify')->name('subscriber.verify');
        Route::get('/unsubscribe', 'unsubscribe')->name('unsubscribe');
    });

Route::any('/payments/callback/{payment}', 'PaymentController@callback')->name('payments.callback');


Route::controller(PagesController::class)->name('pages.')->group(function () {
    Route::get('/page/{page:slug}', 'show')->name('show');
});
