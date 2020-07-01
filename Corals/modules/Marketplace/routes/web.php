<?php

Route::group(['prefix' => 'marketplace', 'as' => 'marketplace.'], function () {
    Route::get('shop', ['as' => 'index', 'uses' => 'ShopController@index']);
    Route::get('shop/{slug}', ['as' => 'ma-show', 'uses' => 'ShopController@show']);
    Route::get('cart', 'CartController@index');

    Route::group(['prefix' => 'checkout'], function () {
        Route::get('/', 'CheckoutController@index');
        Route::post('/', 'CheckoutController@doCheckout');
        Route::get('step/{step}', 'CheckoutController@checkoutStep');
        Route::post('step/{step}', 'CheckoutController@saveCheckoutStep');
        Route::get('shipping-address', 'CheckoutController@checkoutShippingAddress');
        Route::get('order-success/{order}', 'CheckoutController@showOrderSuccessPage');
    });

    Route::get('products/download/{id}', 'ProductsController@downloadFile');
    Route::post('stores/bulk-action', 'StoresController@bulkAction');
    Route::post('products/bulk-action', 'ProductsController@bulkAction');
    Route::post('tags/bulk-action', 'TagsController@bulkAction');
    Route::post('brands/bulk-action', 'BrandsController@bulkAction');
    Route::post('coupons/bulk-action', 'CouponsController@bulkAction');
    Route::resource('stores', 'StoresController');
    Route::resource('products', 'ProductsController');
    Route::resource('categories', 'CategoriesController', ['except' => ['show']]);
    Route::get('categories/attributes/{product_id?}', 'CategoriesController@getCategoryAttributes');

    Route::resource('attributes', 'AttributesController', ['except' => ['show']]);
    Route::resource('tags', 'TagsController', ['except' => ['show']]);
    Route::resource('brands', 'BrandsController', ['except' => ['show']]);
    Route::post('products/{product}/create-gateway-product', ['as' => 'create-gateway-product', 'uses' => 'ProductsController@createGatewayProduct']);

    Route::get('categories/hierarchy', 'CategoriesController@categoriesHierarchy');
    Route::post('categories/update-tree', 'CategoriesController@updateCategoriesHierarchy');
    Route::post('categories/bulk-action', 'CategoriesController@bulkAction');

    Route::group(['prefix' => 'products', 'as' => 'product-gallery.'], function () {
        Route::get('{product}/gallery', ['as' => 'list', 'uses' => 'ProductsController@gallery']);
        Route::post('{product}/gallery/upload', ['as' => 'upload', 'uses' => 'ProductsController@galleryUpload']);
        Route::post('{media}/gallery/mark-as-featured', ['as' => 'mark-as-featured', 'uses' => 'ProductsController@galleryItemFeatured']);
        Route::delete('{media}/gallery/delete', ['as' => 'delete', 'uses' => 'ProductsController@galleryItemDelete']);
    });

    Route::group(['prefix' => 'products', 'as' => 'product-comments.'], function () {
        Route::post('{product}/create-comment', 'CommentController@createComment')->name('create-comment');
        Route::post('{comment}/create-reply', 'CommentController@replyComment')->name('reply-comment');
    });

    Route::group(['prefix' => 'wishlist'], function () {
        Route::post('{product}', 'WishlistController@setWishlist');
        Route::delete('{wishlist}', 'WishlistController@destroy');
        Route::get('my', ['as' => 'my-wishlist', 'uses' => 'WishlistController@myWishlist']);
    });

    Route::group(['prefix' => 'follow'], function () {
        Route::post('{store}', 'FollowController@setWishlist');
        Route::delete('{wishlist}', 'FollowController@destroy');
        Route::get('my', ['as' => 'store-follows', 'uses' => 'FollowController@myFollowList']);
    });

    Route::resource('products.sku', 'SKUController');
    Route::post('products/{product}/sku/{sku}/create-gateway-sku', ['as' => 'create-gateway-sku', 'uses' => 'SKUController@createGatewaySKU']);

    Route::post('orders/bulk-action', 'OrdersController@bulkAction');
    Route::get('orders/my', ['as' => 'my-orders', 'uses' => 'OrdersController@myOrders']);
    Route::get('orders/store', ['as' => 'store-orders', 'uses' => 'OrdersController@storeOrders']);

    Route::group(['prefix' => 'orders'], function () {
        Route::get('{order}/refund-order', 'OrdersController@getRefundView');
        Route::put('{order}/do-refund', 'OrdersController@doRefund');
    });

    Route::get('downloads/my', ['as' => 'my-downloads', 'uses' => 'OrdersController@myDownloads']);
    Route::get('private-pages/my', ['as' => 'my-private-pages', 'uses' => 'OrdersController@myPrivatePages']);
    Route::resource('orders', 'OrdersController', ['only' => ['index', 'show', 'edit', 'update']]);
    Route::get('orders/{order}/track', 'OrdersController@track');
    Route::get('orders/{order}/download/{id}', 'OrdersController@downloadFile');

    Route::get('settings', 'ShopController@settings');
    Route::post('settings', 'ShopController@saveSettings');

    Route::get('gateway-payment/{gateway}/{amount?}', 'CheckoutController@gatewayPayment');
    Route::get('gateway-payment-token/{gateway}/{amount?}', 'CheckoutController@gatewayPaymentToken');
    Route::get('gateway-check-payment-token/{gateway}', 'CheckoutController@gatewayCheckPaymentToken');

    Route::resource('coupons', 'CouponsController');


    Route::group(['prefix' => 'shippings'], function () {
        Route::get('upload', 'ShippingsController@upload');
        Route::post('upload', 'ShippingsController@doUpload');
        Route::get('import-report/{action}', 'ShippingsController@importShippingReport');


    });

    Route::resource('shippings', 'ShippingsController');

    Route::get('store/settings', 'StoresController@settings');
    Route::post('store/settings', 'StoresController@saveSettings');

    Route::get('store/enroll', 'StoresController@enroll');
    Route::post('store/enroll', 'StoresController@doEnroll');

    Route::group(['prefix' => 'transactions'], function () {
        Route::get('/', 'TransactionsController@index');
        Route::get('withdraw', ['as' => 'withdraw-transactions', 'uses' => 'TransactionsController@withdraw']);

    });

    Route::group(['prefix' => 'transactions'], function () {
        Route::get('/', 'TransactionsController@index');
        Route::get('withdraw', ['as' => 'withdraw-transactions', 'uses' => 'TransactionsController@withdraw']);
        Route::post('withdraw', ['as' => 'post-withdraw-transactions', 'uses' => 'TransactionsController@requestWithdrawal']);

    });

    Route::post('product/contact', 'ShopPublicController@contact');
});


Route::group(['prefix' => 'shop', 'as' => 'shop.'], function () {
    Route::get('/', ['as' => 'index', 'uses' => 'ShopPublicController@index']);
    Route::post('{product}/rate', ['as' => 'show', 'uses' => 'RatingController@createRating']);
    Route::get('{slug}', ['as' => 'ma-show', 'uses' => 'ShopPublicController@show']);
});
Route::get('store/{slug}', ['as' => 'show', 'uses' => 'ShopPublicController@showStore']);


Route::group(['prefix' => 'cart'], function () {
    Route::get('/', 'CartPublicController@index');
    Route::get('/summary', 'CartPublicController@getCartItemsSummary');
    Route::post('empty', 'CartPublicController@emptyCart');
    Route::post('quantity/{itemhash}', 'CartPublicController@setQuantity');
    Route::post('{product}/add-to-cart/{sku?}', 'CartPublicController@addToCart');
    Route::get('load/{email_token}', 'CartPublicController@loadAbandonedCart');
});

Route::group(['prefix' => 'checkout'], function () {

    Route::get('gateway-payment/{gateway}/{order?}', 'CheckoutPublicController@gatewayPayment');
    Route::get('gateway-payment-token/{gateway}/{order?}', 'CheckoutPublicController@gatewayPaymentToken');
    Route::get('gateway-check-payment-token/{gateway}', 'CheckoutPublicController@gatewayCheckPaymentToken');

    Route::get('/', 'CheckoutPublicController@index');
    Route::post('/', 'CheckoutPublicController@doCheckout');
    Route::get('step/{step}', 'CheckoutPublicController@checkoutStep');
    Route::post('step/{step}', 'CheckoutPublicController@saveCheckoutStep');
    Route::get('shipping-address', 'CheckoutPublicController@checkoutShippingAddress');
    Route::get('order-success/{order}', 'CheckoutPublicController@showOrderSuccessPage');
});

Route::get('set-store/{store}', 'StoresController@setStore');
