<?php

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


//Front-end
Route::resource('/singlejourney', 'frontend\SingleJourneyController');
Route::get('/travelguide', 'TravelController@index');
Route::get('/travelblogdetail/{id}', 'TravelController@detail');
Route::get('/tagtravel/{id}', 'TravelController@showtag');

Route::get('/howitwork', 'admin\TravelguideController@howitworkindex');
Route::post('/howitwork', 'AccountCustomerController@howitworkCheck');

Route::get('/joinus', 'TravelController@joinus');
Route::post('/addjoin', 'TravelController@addjoin');
Route::get('/menu/search', 'HomeController@search');
Route::get('/deal/location', 'frontend\DealController@location');
Route::get('/deal/{type}', 'frontend\DealController@typeDeal');


Route::get('/sign-in', function () {
    return view('pages.login');
})->name('login');

Route::get('/register', function () {
    return view('pages.register');
});

Route::get('/search/{search}', 'frontend\SearchController@show');
Route::get('/admin', function () {
    return redirect('backoffice/login');
});
Route::get('/forgotpassword', function () {
    return view('pages.forgotpassword');
});
//Route::get('/testbajax', function () {return view('backoffice.blog.create_backup');});


//Back-end

Route::get('/backoffice/test/{search}', 'frontend\SearchController@testSearch');
Route::get('/backoffice/login', 'Auth\LoginAdminController@ShowLoginAdmin')->name('admin.login');
Route::post('/backoffice/login', 'Auth\LoginAdminController@login')->name('admin.login.submit');
Route::get('sendmail/voucher', 'SendMailVoucherController@testMail');
Route::post('sendmail/voucher', 'SendMailVoucherController@requestSendmailCartSuccess');


Route::middleware(['auth'])->group(function () {
//    Route::delete('/backoffice/main_voucher/{id}', 'admin\MainVoucherController@destroy');

    Route::resource('/backoffice/location', 'admin\LocationController');
    Route::post('/backoffice/data_table/location', 'admin\LocationController@queryDatatable');

//Route::prefix('admin')->group(function() {
    Route::get('/backoffice/logout', 'Auth\LoginAdminController@logout')->name('admin.logout');
    Route::get('/backoffice/profile', 'admin\ProfileController@index');
    Route::post('/backoffice/profile', 'admin\ProfileController@update');
//Order
    Route::resource('/backoffice/order', 'admin\OrderController');
    Route::get('/backoffice/order/{main}/{date}/{stat}', 'admin\OrderController@showtable');
    Route::get('/backoffice/ordershow/{id}', 'admin\OrderController@show2');
    Route::get('/backoffice/ordersuccess', 'admin\OrderController@OrderSuccess');
    Route::get('/backoffice/orderunsuccess', 'admin\OrderController@OrderUnSuccess');
    Route::get('/backoffice/changeorder/{id}', 'admin\OrderController@ChangeOrder');
    Route::get('/backoffice/changeSendVoucher/{id}', 'admin\OrderController@changeSendVoucher');
    Route::get('/backoffice/sendVoucherTrue/{id}', 'admin\OrderController@viewSendVoucherTrue');
    Route::post('/backoffice/saveSendVoucherTrue/{id}', 'admin\OrderController@SaveSendVoucherTrue');
    Route::PATCH('/backoffice/updatestatus/{id}', 'admin\OrderController@update');
//Discount
    Route::post('/backoffice/discount/del_sub_code/{id}', 'admin\DiscountController@deleteCode');
    Route::post('/backoffice/discount/check_code', 'admin\DiscountController@CheckCode');
    Route::post('/backoffice/discount/add_code', 'admin\DiscountController@AddCode');
    Route::get('/backoffice/discount/viewMain/{id}', 'admin\DiscountController@viewMain');
    Route::get('/backoffice/discount/import', 'admin\DiscountController@importView');
    Route::post('/backoffice/discount/import', 'ImportExcelController@importDiscount');
    Route::post('/backoffice/data_table/discount/{id}', 'admin\DiscountController@DataTableDiscount');
    Route::resource('/backoffice/discount', 'admin\DiscountController');
//Sale
    Route::resource('/backoffice/sale', 'admin\SaleController');
    Route::get('/backoffice/name-blog', 'admin\SaleController@blogdetail');

//PDF 
    Route::resource('/backoffice/report', 'admin\ShowpdfController');
    Route::get('/backoffice/reportproduct', 'admin\ShowpdfController@index2');
    Route::get('/backoffice/reportshow/{id}', 'admin\ShowpdfController@show2');
    Route::post('/backoffice/ShowPDF/', 'admin\ShowpdfController@ShowPDF');
    Route::get('/backoffice/ShowPDF2', 'admin\ShowpdfController@ShowPDF2');
//sort
    Route::PATCH('/backoffice/sort/album', 'admin\SortTableController@sortAlbum');
    Route::get('/backoffice/show_type_blog/{id}', 'admin\TypeBlogController@show')->name("showTypeBlog");


//dataTable
    Route::get('/backoffice/index', 'admin\FacilitiesController@queryDatatable');
    Route::get('/backoffice/indexTypeblog', 'admin\TypeBlogController@queryDatatable');
    Route::get('/backoffice/blog/data_table', 'admin\BlogController@queryDatatable');
    Route::post('/backoffice/voucher/data_table', 'admin\VoucherController@queryDatatable');
    Route::get('/backoffice/voucher__/{id}', 'admin\VoucherController@changeExipired');
    Route::get('/backoffice/changeSale/{id}', 'admin\VoucherController@changeSale');
    Route::get('/backoffice/change_status_voucher/{id}', 'admin\VoucherController@changeStatusVoucher');
    Route::get('/backoffice/main/changeSale/{id}', 'admin\MainVoucherController@changeSale');
    Route::get('/backoffice/banner/data_table', 'admin\BannerController@queryDatatable');
    Route::get('/backoffice/banner_single_journey/data_table', 'admin\BannerSingleJourneyController@queryDatatable');
//    Route::get('/backoffice/single_journey/data_table', 'admin\SingleJourneyController@queryDatatable');
    Route::get('/backoffice/image_blog/data_table', 'admin\ImageCkeditor2Controller@queryDatatable');
    Route::get('/backoffice/image_voucher/data_table', 'admin\ImageCkeditorController@queryDatatable');
    Route::get('/backoffice/select_voucher/data_table', 'admin\SelectVoucherController@queryDatatable');
    Route::get('/backoffice/permission/data_table', 'admin\PermissionController@queryDatatable');

//    Route::get('/backoffice/bulk_upload', function (){
//        return 'Coming soon';
//    });
    Route::get('/backoffice/bulk_upload/test', 'admin\BulkUploadController@test');
    Route::resource('/backoffice/bulk_upload', 'admin\BulkUploadController');
    Route::post('/backoffice/bulk_upload/data_table', 'admin\BulkUploadController@queryDatatable');

    Route::get('/backoffice/total_click_voucher/export', 'admin\TotalClickVoucher@viewExport');
    Route::resource('/backoffice/total_click_voucher', 'admin\TotalClickVoucher');
    Route::get('/backoffice/total_click_voucher/{id}/list', 'admin\TotalClickVoucher@listClick');
    Route::post('/backoffice/total_click_voucher/data_table', 'admin\TotalClickVoucher@queryDatatable');
    Route::post('/backoffice/total_click_voucher/data_table/export', 'admin\TotalClickVoucher@tableExport');
    Route::post('/backoffice/total_click_voucher/data_table/main', 'admin\TotalClickVoucher@mainVoucherTable');
    Route::post('/backoffice/total_click_voucher/data_table/list', 'admin\TotalClickVoucher@listVoucherTable');

    // Route::get('/backoffice/indexOrder/', 'admin\OrderController@queryDatatable');
    Route::post('/backoffice/indexOrder/{main}/{date}', 'admin\OrderController@queryDatatable');
    Route::post('/backoffice/indexOrder/', 'admin\OrderController@queryDatatable4');
    Route::get('/backoffice/indexOrder2', 'admin\OrderController@queryDatatable2');
    Route::get('/backoffice/indexOrder3', 'admin\OrderController@queryDatatable3');
    Route::get('/backoffice/indexDiscount', 'admin\DiscountController@queryDatatable');
    Route::get('/backoffice/member/data_table', 'admin\MemberController@queryDatatable');
    Route::get('/backoffice/reportBlog', 'admin\ShowpdfController@queryDatatable');
    Route::get('/backoffice/reportProductTB', 'admin\ShowpdfController@queryDatatable2');
    Route::get('/backoffice/admin/data_table', 'admin\AdminController@queryDatatable');
    Route::get('/backoffice/main_voucher/data_table', 'admin\MainVoucherController@queryDatatable');
    Route::get('/backoffice/indexTralvel', 'admin\TravelguideController@queryDatatable');
    Route::get('/backoffice/filter/data_table', 'admin\FilterController@queryDatatable');
    Route::get('/backoffice/joinus/data_table', 'admin\TravelguideController@queryDatatable2');
//view menu

    Route::get('/backoffice/deal', function () {

        return view('backoffice.deal/index');

    });
    // Route::post('/', function () {
    //     return view('pages.voucherbrowsing');
    // })->name('home');

    Route::get('/backoffice/dashboard', 'admin\DashboardsController@index')->name("dashboard");


//Controller menu
    Route::get('/backoffice/voucher/sort', 'admin\VoucherController@viewSorting');
    Route::get('/backoffice/voucher/sort/create', 'admin\VoucherController@addSorting');
    Route::get('/backoffice/voucher/sort/{id}', 'admin\VoucherController@deleteSorting');
    Route::post('/backoffice/voucher/sort', 'admin\VoucherController@saveSorting');


    Route::get('/backoffice/filter', 'admin\FilterController@index');
    Route::get('/backoffice/filter/{id}', 'admin\FilterController@update');

    Route::get('/backoffice/blog/select_show', 'admin\BlogController@select_show');
    Route::post('/backoffice/blog/select', 'admin\BlogController@update_select');

    Route::resource('/backoffice/permission', 'admin\PermissionController');
    Route::resource('/backoffice/facilities', 'admin\FacilitiesController');
    Route::resource('/backoffice/type_blog', 'admin\TypeBlogController');
    Route::resource('/backoffice/blog', 'admin\BlogController');
    Route::resource('/backoffice/voucher', 'admin\VoucherController');

    Route::resource('/backoffice/single_journey', 'admin\SingleJourneyController');
    Route::resource('/backoffice/banner_single_journey', 'admin\BannerSingleJourneyController');
    Route::resource('/backoffice/banner', 'admin\BannerController');
    Route::post('/backoffice/updatelogo', 'admin\BannerController@updatelogo');

    Route::resource('/backoffice/image_voucher', 'admin\ImageCkeditorController');
    Route::resource('/backoffice/image_blog', 'admin\ImageCkeditor2Controller');
    Route::post('/backoffice/image_blog/album/create', 'admin\ImageCkeditor2Controller@addAlbum');
    Route::post('/backoffice/image_blog/album/delete/{id}', 'admin\ImageCkeditor2Controller@delAlbum');
    Route::post('/backoffice/image_blog/album/update/{id}', 'admin\ImageCkeditor2Controller@updateAlbum');

    Route::get('/backoffice/select_voucher/test', 'admin\SelectVoucherController@test');
    Route::resource('/backoffice/select_voucher', 'admin\SelectVoucherController');
    Route::resource('/backoffice/member', 'admin\MemberController');
    Route::resource('/backoffice/admin', 'admin\AdminController');
    Route::resource('/backoffice/main_voucher', 'admin\MainVoucherController');


    //Joinus
    Route::get('/backoffice/joinus', 'admin\TravelguideController@joinus');
    Route::get('/backoffice/joinus/showview/{id}', 'admin\TravelguideController@showjoinus');
    Route::delete(' backoffice/joinus/joinusdel/{id}', 'admin\TravelguideController@deljoinus');

    Route::get('/backoffice/showjoinindex', 'admin\TravelguideController@showjoinindex');
    Route::post('/backoffice/savejoinus', 'admin\TravelguideController@savejoinus');

    //Howitwork
    Route::get('/backoffice/howitwork/', 'admin\TravelguideController@howitwork');
    Route::post('/backoffice/howitwork/create', 'admin\TravelguideController@createhow');

    //PDFmember
    Route::get('/backoffice/MemberPDF', 'admin\MemberController@showPDF');

    //PDFOrder
    Route::get('/backoffice/OrderPDF', 'admin\OrderController@OrderPDF');

    //PDFadmin
    Route::get('/backoffice/AdminPDF', 'admin\AdminController@showPDF');

    //PDFvoucher
    Route::get('/backoffice/VoucherPDF', 'admin\VoucherController@showPDF');


});


// Register //

Route::get('/regis', 'Auth\RegisterController@index');

Route::post('/sign-up', 'Auth\RegisterController@store');
Route::get('/user/confirmation/{token}', 'Auth\RegisterController@comfirmation');
// Route::resource('register','Auth\RegisterController');


// Socail Login //

Route::get('auth/google', 'Auth\LoginController@redirectToProvider')->name('google.login');

Route::get('auth/google/callback', 'Auth\LoginController@handleProviderCallback');


Route::get('auth/facebook', 'Auth\LoginController@redirectToProvider2')->name('facebook.login');

Route::get('auth/facebook/callback', 'Auth\LoginController@handleProviderCallback2');


// EMAIL LOGIN //

Route::post('/chklogin', 'Auth\LoginController@store');


//BACK-END LOGIN


// Logout ///

Route::get('/logout2', 'Auth\LoginController@logout2');

Auth::routes();


// CART //

Route::get('/add-to-cart/{id}', 'VoucherController@getAddToCart');

Route::get('/cart', 'VoucherController@getCart')->name('cart_index');

Route::get('/paymentstarting', 'VoucherController@getPaymentStarting');

Route::get('/deletecart', 'VoucherController@deleteCart');

Route::get('/updatecart', 'VoucherController@updateCart');

Route::get('/destroyCart', 'VoucherController@destroyCart');

Route::get('/paymentending', 'VoucherController@paymentending');
Route::post('/paymentending', 'VoucherController@getPaymentEnding')->name('addorder');

Route::post('/fecth-amphur', 'VoucherController@fecthAmphur')->name('fecth.amphur');

Route::post('/fecth-districts', 'VoucherController@fecthDistricts')->name('fecth.districts');

Route::post('/fecth-postcode', 'VoucherController@fecthPostcode')->name('fecth.postcode');

Route::post('/chekcode', 'VoucherController@checkCode')->name('checkcode');

Route::post('/addorder', 'VoucherController@getOrder')->name('addorder2');

Route::get('showdetail/{id}', 'VoucherController@showdetail');

Route::get('ConfirmationOrder/{token}', 'VoucherController@ConfirmationOrder');

Route::get('/cart_success/{id}', 'VoucherController@CartsuccessView')->name('cartsuccess');
Route::get('/cart-summary', 'VoucherController@CartSummary');
// Voicher //
Route::get('/check_voucher_countdown', 'frontend\VoucherbrowsingController@checkCountDown');

Route::get('/', 'frontend\VoucherbrowsingController@index');
Route::get('/voucherdetail/{id}', 'frontend\VoucherbrowsingController@show');
Route::get('/voucherdetail/click_voucher/{id}', 'frontend\VoucherbrowsingController@clickVoucher');
Route::get('/voucherbrowsing', 'frontend\VoucherbrowsingController@index');
Route::get('/voucherbrowsing/{id}', 'frontend\VoucherbrowsingController@filter');
Route::post('/voucherbrowsing/pagination/{page}', 'frontend\VoucherbrowsingController@pagination');
Route::get('/autocompleteampuhers', 'frontend\VoucherbrowsingController@autocompleteampuhers');
Route::get('/autocompletedistricts', 'frontend\VoucherbrowsingController@autocompletedistricts');

// Accoun //
Route::post('customeraccount/cancel/{id}', 'AccountCustomerController@cancel');
Route::resource('customeraccount', 'AccountCustomerController');

Route::resource('/payment', 'PaymentController');
Route::get('/test_url', 'TestController@backEndPayment');
Route::post('/redirect', 'PaymentController@redirected');

Route::get('/back_end_payment', 'PaymentController@backEndPayment');
Route::post('/back_end_payment', 'PaymentController@backEndPayment');

Route::get('/order-detail/{id}', 'AccountCustomerController@showdetail');
Route::get('/testmail/{id}', 'PaymentController@testmail');


//Travelguide
Route::resource('/backoffice/travelguidemanage', 'admin\TravelguideController');


Route::post('/forgetpassword', 'AccountCustomerController@forget');


Route::get('/test', 'TestController@test');
Route::get('/test/email/confirm_orders', 'TestController@email');
Route::get('/test/confirm_orders', 'TestController@testConfirmOrder');

Route::get('/check_limit/{id}/{qty}', 'VoucherController@check_limit');


Route::get('/clc', function () {

    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    // Artisan::call('view:clear');
    // session()->forget('key');
    return "Cleared!";

});

Route::get('/tester', 'TestController@tester');