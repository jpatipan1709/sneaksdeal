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


Route::get('/travelguide', function () {return view('pages.travelguide');});
Route::get('/howitwork', function () {return view('pages.howitwork');});
Route::get('/joinus', function () {return view('pages.joinus');});
Route::get('/customeraccount', function () {return view('pages.customeraccount');});
Route::get('/sign-in', function () {return view('pages.login');});
Route::get('/register', function () {return view('pages.register');});
Route::get('/search', function () {return view('pages.search');});
Route::get('/travelblogdetail', function () {return view('pages.travelblogdetail');});



//Back-end
Route::get('/backoffice/login', function () {
    return view('backoffice.login');
})->name('loginBack');

Route::middleware(['auth'])->group(function () {



//dataTable
    Route::get('/backoffice/index', 'admin\FacilitiesController@queryDatatable');
    Route::get('/backoffice/indexTypeblog', 'admin\TypeBlogController@queryDatatable');
    Route::get('/backoffice/blog/data_table', 'admin\BlogController@queryDatatable');
    Route::get('/backoffice/voucher/data_table', 'admin\VoucherController@queryDatatable');
    Route::get('/backoffice/banner/data_table', 'admin\BannerController@queryDatatable');

//view menu
    Route::get('/backoffice/deal', function () {
        return view('backoffice.deal/index');
    });
    Route::get('/backoffice/dashboard', function () {
        return view('backoffice.dashboard/index');
    })->name("dashboard");



//Controller menu
    Route::resource('/backoffice/facilities', 'admin\FacilitiesController');
    Route::resource('/backoffice/type_blog', 'admin\TypeBlogController');
    Route::resource('/backoffice/blog', 'admin\BlogController');
    Route::resource('/backoffice/voucher', 'admin\VoucherController');
    Route::resource('/backoffice/banner', 'admin\BannerController');

});

Route::get('/home', 'HomeController@index')->name('home');

// Register //
Route::get('/regis','Auth\RegisterController@index');
Route::post('/sign-up','Auth\RegisterController@store');
// Route::resource('register','Auth\RegisterController');


// Socail Login //
Route::get('auth/google', 'Auth\LoginController@redirectToProvider')->name('google.login');
Route::get('auth/google/callback', 'Auth\LoginController@handleProviderCallback');

Route::get('auth/facebook', 'Auth\LoginController@redirectToProvider2')->name('facebook.login');
Route::get('auth/facebook/callback', 'Auth\LoginController@handleProviderCallback2');

// EMAIL LOGIN //
Route::post('/chklogin', 'Auth\LoginController@store');


// Logout ///
Route::get('/logout2', 'Auth\LoginController@logout2');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// CART //
Route::get('/add-to-cart/{id}', 'VoucherController@getAddToCart');
Route::get('/cart', 'VoucherController@getCart');
Route::get('/paymentstarting', 'VoucherController@getPaymentStarting');
Route::get('/deletecart', 'VoucherController@deleteCart');
Route::get('/updatecart', 'VoucherController@updateCart');
Route::get('/destroyCart', 'VoucherController@destroyCart');
Route::get('/paymentending', 'VoucherController@getPaymentEnding');

// Voicher //
Route::get('/', 'frontend\VoucherbrowsingController@index');
Route::get('/voucherdetail/{id}', 'frontend\VoucherbrowsingController@show');
Route::get('/voucherbrowsing', 'frontend\VoucherbrowsingController@index');


Route::resource('customeraccount', 'AccountCustomerController');