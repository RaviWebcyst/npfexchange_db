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
Route::get('/', function () {
    return view('welcome');
    // return redirect('admin/login');
});
Route::get('/home', function () {
    return view('welcome');
})->name('user.home');

Route::get('/exchange', function () {
    return view('welcome');
})->name('user.exchange');
Route::get('/trade', function () {
    return view('welcome');
})->name('user.trade');
Route::get('/market', function () {
    return view('welcome');
})->name('user.market');




Route::get('/game', function () {
    return view('welcome');
});
Route::get('/invite', function () {
    return view('welcome');
});
Route::get('/profile', function () {
    return view('welcome');
});
Route::get('/recharge', function () {
    return view('welcome');
});
Route::get('/payment_history', function () {
    return view('welcome');
});
Route::get('/payment', function () {
    return view('welcome');
});
Route::get('/orders', function () {
    return view('welcome');
});
Route::get('/refer', function () {
    return view('welcome');
});
Route::get('/Login', function () {
    return view('welcome');
});
Route::get('/Register', function () {
    return view('welcome');
});
Route::get('/invite_page', function () {
    return view('welcome');
});
Route::get('/edit_profile', function () {
    return view('welcome');
});
Route::get('/cash_payments', function () {
    return view('welcome');
});
Route::get('/transactions', function () {
    return view('welcome');
});
Route::get('/direct_list', function () {
    return view('welcome');
});
Route::get('/team_list', function () {
    return view('welcome');
});
Route::get('/withdraw', function () {
    return view('welcome');
});
Route::get('/withdraw_details', function () {
    return view('welcome');
});
Route::get('/swap', function () {
    return view('welcome');
});
Route::get('/deposit', function () {
    return view('welcome');
});
Route::get('/feed', function () {
    return view('welcome');
});
Route::get('/deposit_history', function () {
    return view('welcome');
});
Route::get('/funding', function () {
    return view('welcome');
});
Route::get('/funding_history/{slug}', function () {
    return view('welcome');
});
Route::get('/recieve', function () {
    return view('welcome');
});
Route::get('/phone_verification', function () {
    return view('welcome');
});
Route::get('/p2p', function () {
    return view('welcome');
});
Route::get('/forget_password', function () {
    return view('welcome');
});

Route::get("test",[App\Http\Controllers\BinanceController::class,'test']);
Route::get("test_mail",[App\Http\Controllers\usersController::class,'test_mail']);


Route::get("/admin/login",[App\Http\Controllers\usersController::class,'admin_login'])->name('admin.login');
Route::post("/admin/user_login",[App\Http\Controllers\usersController::class,'user_login'])->name('admin.user_login');


Route::get('/{any}', function () {
    return view('welcome');
});

// vue routes end



Auth::routes();


Route::middleware(['auth','is_admin'])->prefix('admin')->group(function(){
    Route::get('home', [App\Http\Controllers\HomeController::class,'adminHome'])->name('admin.home');
    Route::get("users",[App\Http\Controllers\usersController::class,'users'])->name("admin.users");
    Route::get("activeUsers",[App\Http\Controllers\usersController::class,'activeUsers'])->name("admin.activeUsers");

    Route::get("editUser/{id}",[App\Http\Controllers\usersController::class,'editUser'])->name("admin.editUser");
    Route::post("updateUser/{id}",[App\Http\Controllers\usersController::class,'updateUser'])->name("admin.updateUser");

    Route::get("sendEpin/{id}",[App\Http\Controllers\usersController::class,'sendEpin'])->name("admin.sendEpin");
    Route::post("postEpin/{id}",[App\Http\Controllers\usersController::class,'postEpin'])->name("admin.postEpin");

    Route::get("games",[App\Http\Controllers\usersController::class,'games'])->name("admin.games");
    Route::get("transactions",[App\Http\Controllers\usersController::class,'all_transactions'])->name("admin.transactions");
    
    //user payments
    Route::get("pending_payments",[App\Http\Controllers\usersController::class,'pending_payments'])->name('admin.pending_payments');
    Route::post("accept_payment",[App\Http\Controllers\usersController::class,'accept_payment'])->name('admin.accept_payment');
    Route::post("reject_payment",[App\Http\Controllers\usersController::class,'reject_payment'])->name('admin.reject_payment');

    Route::get("completed_payments",[App\Http\Controllers\usersController::class,'completed_payments'])->name('admin.completed_payments');
    Route::get("rejected_payments",[App\Http\Controllers\usersController::class,'rejected_payments'])->name('admin.rejected_payments');

    Route::get("manual_game",[App\Http\Controllers\usersController::class,'manual_game'])->name('admin.manual_game');
    Route::post("setResult",[App\Http\Controllers\usersController::class,'setResult'])->name('admin.setResult');

    Route::get("pending_withdraw",[App\Http\Controllers\usersController::class,'pending_withdraw'])->name('admin.pending_withdraw');
    Route::get("completed_withdraw",[App\Http\Controllers\usersController::class,'completed_withdraw'])->name('admin.completed_withdraw');
    Route::get("rejected_withdraw",[App\Http\Controllers\usersController::class,'rejected_withdraw'])->name('admin.rejected_withdraw');
    Route::post("acceptWd",[App\Http\Controllers\usersController::class,'acceptWd'])->name('admin.acceptWd');
    Route::post("rejectWd",[App\Http\Controllers\usersController::class,'rejectWd'])->name('admin.rejectWd');

    Route::get("add_upi",[App\Http\Controllers\usersController::class,'add_upi'])->name('admin.add_upi');
    Route::post("store_upi",[App\Http\Controllers\usersController::class,'store_upi'])->name('admin.store_upi');

    Route::get("posts",[App\Http\Controllers\usersController::class,'posts'])->name('admin.posts');
    Route::get("add_post",[App\Http\Controllers\usersController::class,'add_post'])->name('admin.add_post');
    Route::get("edit_post/{id}",[App\Http\Controllers\usersController::class,'edit_post'])->name('admin.edit_post');
    Route::get("delete_post/{id}",[App\Http\Controllers\usersController::class,'delete_post'])->name('admin.delete_post');
    Route::post("store_post",[App\Http\Controllers\usersController::class,'store_post'])->name('admin.store_post');
    Route::post("update_post/{id}",[App\Http\Controllers\usersController::class,'update_post'])->name('admin.update_post');

    Route::get("coins",[App\Http\Controllers\usersController::class,'coins'])->name('admin.coins');
    Route::get("add_coin",[App\Http\Controllers\usersController::class,'add_coin'])->name('admin.add_coin');
    Route::get("edit_coin/{id}",[App\Http\Controllers\usersController::class,'edit_coin'])->name('admin.edit_coin');
    Route::get("delete_coin/{id}",[App\Http\Controllers\usersController::class,'delete_coin'])->name('admin.delete_coin');
    Route::post("store_coin",[App\Http\Controllers\usersController::class,'store_coin'])->name('admin.store_coin');
    Route::post("update_coin/{id}",[App\Http\Controllers\usersController::class,'update_coin'])->name('admin.update_coin');

    Route::get("assets",[App\Http\Controllers\usersController::class,'assets'])->name('admin.assets');
    Route::get("add_asset",[App\Http\Controllers\usersController::class,'add_asset'])->name('admin.add_asset');
    Route::get("edit_asset/{id}",[App\Http\Controllers\usersController::class,'edit_asset'])->name('admin.edit_asset');
    Route::get("delete_asset/{id}",[App\Http\Controllers\usersController::class,'delete_asset'])->name('admin.delete_asset');
    Route::post("store_asset",[App\Http\Controllers\usersController::class,'store_asset'])->name('admin.store_asset');
    Route::post("update_asset/{id}",[App\Http\Controllers\usersController::class,'update_asset'])->name('admin.update_asset');

    Route::get("price",[App\Http\Controllers\HomeController::class,'price'])->name('admin.price');
    Route::post("store_price",[App\Http\Controllers\HomeController::class,'store_price'])->name('admin.store_price');

    Route::get("usdt_price",[App\Http\Controllers\HomeController::class,'usdt_price'])->name('admin.usdt_price');
    Route::post("store_usdtPrice",[App\Http\Controllers\HomeController::class,'store_usdtPrice'])->name('admin.store_usdtPrice');


    Route::get("all_trades",[App\Http\Controllers\BinanceController::class,'all_trades'])->name('admin.trades');


    Route::post('admin_logout', [App\Http\Controllers\HomeController::class,'logout'])->name('admin.logout');

});



// Route::get('/home', [App\Http\Controllers\HomeController::class,'index'])->name('home');

// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class,'index'])->name('home');


