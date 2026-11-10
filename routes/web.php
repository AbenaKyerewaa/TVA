<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\InventoryController as AdminInventoryController;
use App\Http\Controllers\Admin\NewsletterSubscriberController as AdminNewsletterSubscriberController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ShipmentController as AdminShipmentController;
use App\Http\Controllers\Admin\ShippingMethodController as AdminShippingMethodController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Customer\AccountController;
use App\Http\Controllers\Customer\AddressController;
use App\Http\Controllers\Customer\ProfileController as CustomerProfileController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\OrderTrackingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\StorefrontController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Storefront Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [StorefrontController::class, 'index'])->name('home');
Route::get('/founder', [StorefrontController::class, 'founder'])->name('founder');
Route::get('/about', [StorefrontController::class, 'founder'])->name('about');
Route::get('/collections', [StorefrontController::class, 'collections'])->name('collections');
Route::get('/collection/{slug}', [StorefrontController::class, 'collection'])->name('collection.show');
Route::get('/catalog', [StorefrontController::class, 'catalog'])->name('catalog');
Route::get('/bridal', function (\Illuminate\Http\Request $request) {
    return redirect()->route('catalog', ['category' => 'engagement']);
})->name('bridal');
Route::get('/product/{slug}', [StorefrontController::class, 'product'])->name('product.show');
Route::get('/api/search', [StorefrontController::class, 'searchApi'])->middleware('throttle:60,1')->name('api.search');
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->middleware('throttle:10,1')->name('newsletter.subscribe');
Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SeoController::class, 'robots'])->name('robots');

/*
|--------------------------------------------------------------------------
| Shopping Bag & Cart Routes
|--------------------------------------------------------------------------
*/
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'store'])->name('cart.add');
Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
Route::patch('/cart/items/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/items/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::post('/cart/coupon', [CartController::class, 'applyCoupon'])->name('cart.coupon.apply');
Route::delete('/cart/coupon', [CartController::class, 'removeCoupon'])->name('cart.coupon.remove');
Route::get('/api/cart/summary', [CartController::class, 'summary'])->name('cart.summary');

/*
|--------------------------------------------------------------------------
| Wishlist Routes
|--------------------------------------------------------------------------
*/
Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
Route::post('/wishlist/{item}/move-to-cart', [WishlistController::class, 'moveToCart'])->name('wishlist.move_to_cart');
Route::delete('/wishlist/{item}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');

/*
|--------------------------------------------------------------------------
| Checkout & Payment Routes
|--------------------------------------------------------------------------
*/
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->middleware('throttle:15,1')->name('checkout.store');
Route::get('/checkout/payment/{order_number}', [PaymentController::class, 'show'])->name('checkout.payment');
Route::post('/checkout/payment/{order_number}/process', [PaymentController::class, 'process'])->middleware('throttle:15,1')->name('checkout.payment.process');
Route::get('/order/confirmation/{order_number}', [CheckoutController::class, 'confirmation'])->name('order.confirmation');
Route::get('/order/{order_number}/invoice', [PaymentController::class, 'invoice'])->name('order.invoice');
Route::get('/orders/track', [OrderTrackingController::class, 'show'])->name('order.tracking');

/*
|--------------------------------------------------------------------------
| Customer Authenticated Area
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [AccountController::class, 'dashboard'])->name('dashboard');
    Route::get('/account/orders', [AccountController::class, 'orders'])->name('account.orders.index');
    Route::get('/account/orders/{order_number}', [AccountController::class, 'orderShow'])->name('account.orders.show');
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');

    Route::get('/customer/profile', [CustomerProfileController::class, 'show'])->name('customer.profile');
    Route::post('/customer/profile', [CustomerProfileController::class, 'update'])->name('customer.profile.update');

    Route::get('/customer/addresses', [AddressController::class, 'index'])->name('customer.addresses.index');
    Route::post('/customer/addresses', [AddressController::class, 'store'])->name('customer.addresses.store');
    Route::delete('/customer/addresses/{address}', [AddressController::class, 'destroy'])->name('customer.addresses.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Atelier Administrative Area
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('categories', AdminCategoryController::class);
    Route::resource('products', AdminProductController::class);

    // Orders Management
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
    Route::post('/orders/{order}/note', [AdminOrderController::class, 'addNote'])->name('orders.note');

    // Inventory Control & Logs
    Route::get('/inventory', [AdminInventoryController::class, 'index'])->name('inventory.index');
    Route::post('/inventory/{product}/adjust', [AdminInventoryController::class, 'adjust'])->name('inventory.adjust');
    Route::get('/inventory/logs', [AdminInventoryController::class, 'logs'])->name('inventory.logs');

    // Shipping & Courier Logistics
    Route::post('/shipping-methods/{shipping_method}/toggle', [AdminShippingMethodController::class, 'toggle'])->name('shipping-methods.toggle');
    Route::resource('shipping-methods', AdminShippingMethodController::class);
    Route::get('/shipments', [AdminShipmentController::class, 'index'])->name('shipments.index');
    Route::patch('/shipments/{shipment}', [AdminShipmentController::class, 'update'])->name('shipments.update');

    // Clientele & CRM
    Route::get('/customers', [AdminCustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/{customer}', [AdminCustomerController::class, 'show'])->name('customers.show');

    // Promotional Codes & Privileges
    Route::post('/coupons/{coupon}/toggle', [AdminCouponController::class, 'toggle'])->name('coupons.toggle');
    Route::resource('coupons', AdminCouponController::class);

    // Marketing & Newsletter Subscribers
    Route::get('/subscribers', [AdminNewsletterSubscriberController::class, 'index'])->name('subscribers.index');
    Route::post('/subscribers/{subscriber}/toggle', [AdminNewsletterSubscriberController::class, 'toggle'])->name('subscribers.toggle');
    Route::delete('/subscribers/{subscriber}', [AdminNewsletterSubscriberController::class, 'destroy'])->name('subscribers.destroy');
    Route::get('/subscribers/export', [AdminNewsletterSubscriberController::class, 'export'])->name('subscribers.export');
});

require __DIR__.'/auth.php';
