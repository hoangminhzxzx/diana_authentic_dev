<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/admin', 'HomeController@index')->name('home');

//Admin
Route::group(['middleware' => 'auth'], function () {
    Route::group(['prefix' => 'admin'], function () {
        Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
        Route::get('category-tree-view','CategoryController@manageCategory')->name('category-tree-view');
        Route::post('add-category','CategoryController@addCategory')->name('add.category');
        Route::get('/category-list', 'CategoryController@list')->name('admin.category.list');
        Route::get('/category-edit/{id}', 'CategoryController@edit')->name('admin.category.edit');
        Route::post('/category-update/{id}', 'CategoryController@update')->name('admin.category.update');
        Route::post('/category-delete', 'CategoryController@delete')->name('admin.category.delete');

        Route::get('/product/list', 'ProductController@index')->name('admin.product.list');
        Route::get('/product/add', 'ProductController@add')->name('admin.product.add');
        Route::post('/upload-images-dz', 'ProductController@uploadImageDZ')->name('admin.upload.images.dz');
        Route::post('/remove-image-single', 'ProductController@removeImageSingle')->name('admin.remove.image.single');
        Route::post('/product/store', 'ProductController@store')->name('admin.product.store');
        Route::get('/product/edit/{id}', 'ProductController@edit')->name('admin.product.edit');
        Route::post('/product/update/{id}', 'ProductController@update')->name('admin.product.update');
        Route::post('/product/delete', 'ProductController@delete')->name('admin.product.delete');
        Route::post('/product/set-publish', 'ProductController@setPublish')->name('admin.product.setPublish');

        Route::post('/product/variant', 'ProductVariantController@store')->name('admin.product.variant.store');
        Route::post('/product/variant-ass', 'ProductVariantController@storeAss')->name('admin.product.variant_ass.store');
        Route::get('/product/variant/{id}', 'ProductVariantController@edit')->name('admin.product.variant.edit');
        Route::post('/product/variant/update/{id}', 'ProductVariantController@update')->name('admin.product.variant.update');
        Route::post('/product/variant-ass/update/{id}', 'ProductVariantController@updateAss')->name('admin.product.variantAss.update');
        Route::post('/product/variant/delete/{id}', 'ProductVariantController@delete')->name('admin.product.variant.delete');

        Route::get('/user/list', 'UserController@index')->name('admin.user.list');
        Route::get('/user/add', 'UserController@add')->name('admin.user.add')->middleware('CheckRole');
        Route::post('/user/store', 'UserController@store')->name('admin.user.store')->middleware('CheckRole');
        Route::get('/user/edit/{id}', 'UserController@edit')->name('admin.user.edit')->middleware('CheckRole');
        Route::post('/user/update/{id}', "UserController@update")->name('admin.user.update')->middleware('CheckRole');
        Route::post('/user/delete/{id}', 'UserController@delete')->name('admin.user.delete')->middleware('CheckRole');

        Route::post('/upload-image-tinymce', 'ProductController@uploadImageTinymce')->name('admin.upload.file.tinymce');

        //Banner
        Route::get('/config-banner', 'ProductController@detailBanner')->name('admin.product.config_banner.detail');
        Route::post('/config-banner-store', 'ProductController@storeBanner')->name('admin.product.config_banner.store');

        //Orders
        Route::get('/order-list', 'OrderController@list')->name('admin.order.list');
        Route::get('/order-detail/{id}', 'OrderController@detail')->name('admin.order.detail');

        //-----status order---------
        Route::post('/check-order', 'OrderController@checkOrder')->name('admin.order.checkOrder');
        Route::post('/confirm-ship-order', 'OrderController@confirmShipOrder')->name('admin.order.confirm.ship.order');
        Route::post('/confirm-complete-order', 'OrderController@confirmCompleteOrder')->name('admin.order.confirm.complete.order');
        Route::post('/confirm-cancle-order', 'OrderController@confirmCancleOrder')->name('admin.order.confirm.cancle.order');

        //----- config product hot most--------------
        Route::get('/config-product', 'ProductController@configProduct')->name('admin.product.config.product');
        Route::post('/config-product-update', 'ProductController@configProductStore')->name('admin.product.config.product.store');
        Route::post('/config-change-position', 'ProductController@configChangePosition')->name('admin.product.config.change.position');

        //----Timeline-----
        Route::get('/stock', 'StockController@index')->name('admin.stock.index');
        Route::post('/stock/store', 'StockController@store')->name('admin.stock.store');
        Route::post('/stock/upload-images-dz', 'StockController@uploadImageDZ')->name('admin.stock.upload.images.dz');
        Route::post('/remove-image-single-stock', 'StockController@removeImageSingle')->name('admin.remove.image.single.stock');
        Route::get('/stock/edit/{id}', 'StockController@edit')->name('admin.stock.edit');
        Route::post('/stock/update/{id}', 'StockController@update')->name('admin.stock.update');
        Route::post('/stock/delete/{id}', 'StockController@delete')->name('admin.stock.delete');

        //--- member Diana Authentic -----
        Route::get('/member/index', 'MemberController@index')->name('admin.member.index');
//        Route::get('/member/detail/{id}', 'MemberController@detail')->name('admin.member.detail');
    });
    Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
        \UniSharp\LaravelFilemanager\Lfm::routes();
    });
});

//Client
Route::get('/', 'Front\HomeController@index')->name('homeFront');
Route::get('/product/detail/{slug}', 'Front\ProductController@detailProduct')->name('client.product.detail');
Route::post('/choose-size', 'Front\ProductController@chooseSize')->name('client.choose.size');
// Route::post('/choose-size', function (Request $request) {
	// $response = ['success' => false];
        // $data = $request->input();
		// dd($data);
        // $product_variants = ProductVariant::query()->where('product_id', '=', $data['product_id'])->where('size_id', '=', $data['size_id'])
            // ->where('is_out_stock', '=', 0)
            // ->get();
        // if ($product_variants) {
            // $list_color = [];
            // foreach ($product_variants as $product_variant) {
                // $color = ProductOption::query()->where('id', '=', $product_variant->color_id)->first();
                // $list_color[] = $color;
            // }
        // }
        // $response['html'] = view('front.product.render_list_color', [
            // 'colors' => $list_color
        // ])->toHtml();
        // $response['success'] = true;
        // return response()->json($response);
// });
Route::post('/product/add-to-cart', 'Front\ProductController@addToCart')->name('client.product.add_to_cart');
Route::get('/gio-hang', 'Front\ProductController@renderCartInfo')->name('client.cart');
Route::post('/product/remove-to-cart', 'Front\ProductController@removeProduct')->name('client.product.remove');
Route::post('/product/updateQtyAjax/{rowId}', 'Front\ProductController@updateQty')->name('client.updateQty');
Route::post('/select-province', 'Front\ProductController@selectProvince')->name('client.select.province');
Route::post('/select-district', 'Front\ProductController@selectDistrict')->name('client.select.district');
Route::get('/dat-hang', 'Front\ProductController@checkOutGet')->name('client.checkout');

Route::post('/thanh-toan', 'Front\OrderController@insertOrder')->name('client.insert.order');
Route::get('/thank-you', 'Front\OrderController@thankYou')->name('client.thank.you');

Route::get('/category/{slug}', 'Front\CategoryController@listProductFollowCategory')->name('client.category.list.product');

//Account Client
Route::get('/tai-khoan', 'Front\AccountController@index')->name('client.account.client');
Route::get('/tai-khoan/ho-so', 'Front\AccountController@detail')->name('client.account.detail');
Route::post('/dang-ky-tai-khoan', 'Front\AccountController@register')->name('client.account.client.register');
Route::post('/dang-nhap-tai-khoan', 'Front\AccountController@login')->name('client.account.client.login');
Route::post('/dang-xuat', 'Front\AccountController@logout')->name('client.account.client.logout');
Route::get('/khoi-phuc-mat-khau', 'Front\AccountController@restorePassword')->name('client.restore.password');
Route::get('/doi-mat-khau', 'Front\AccountController@changePassword')->name('client.account.changPassword');

Route::get('/send-mail', 'MailController@testMail');
Route::post('/restart-password-client', 'MailController@restartPasswordClient')->name('client.restart.password');
Route::get('/cap-nhat-mat-khau/{key}/{reset}', 'MailController@updatePasswordNewFromEmail')->name('client.update.password.from.email');
Route::post('/restore-update-password', 'Front\AccountController@restoreUpdatePassword')->name('client.restore.update.password');
Route::post('/tai-khoan/cap-nhap', 'Front\AccountController@updateAccountClient')->name('client.update.account');

Route::get('/clear-cache', function() {
	$exitCode = \Illuminate\Support\Facades\Artisan::call('cache:clear');
    $exitCode = \Illuminate\Support\Facades\Artisan::call('config:cache');
    $exitCode = \Illuminate\Support\Facades\Artisan::call('config:cache');
    return 'DONE'; //Return anything
});

// Route::get('/clear-route', function() {
    // $exitCode = \Illuminate\Support\Facades\Artisan::call('route:clear');
	// $exitCode = \Illuminate\Support\Facades\Artisan::call('route:clear');
    // return 'DONE Route'; //Return anything
// });

// Route::get('/storage-link', function() {
    // $exitCode = \Illuminate\Support\Facades\Artisan::call('storage:link');
	// dd($exitCode);
    // return 'DONE Storage Link'; //Return anything
// });
Route::get('/search-diana-authentic', 'Front\ProductController@searchDianaAuthentic')->name('client.search.diana.authentic');

Route::get('/login/facebook', 'Front\LoginSocialController@redirectToProvider');
Route::get('/login/facebook/callback', 'Front\LoginSocialController@handleProviderCallback');
