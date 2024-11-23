<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post("myOrders",[App\Http\Controllers\usersController::class,'myOrders']);
Route::post("allOrders",[App\Http\Controllers\usersController::class,'allOrders']);
Route::post("getResult",[App\Http\Controllers\usersController::class,'getResult']);
Route::post("transactions",[App\Http\Controllers\usersController::class,'transactions']);
Route::post("getUserDetails",[App\Http\Controllers\usersController::class,'userDetails']);
Route::post("userlogin",[App\Http\Controllers\usersController::class,'userlogin']);
Route::post("getSponser",[App\Http\Controllers\usersController::class,'getSponser']);
Route::post("payment",[App\Http\Controllers\usersController::class,'payment']);
Route::post("getPayments",[App\Http\Controllers\usersController::class,'getPayments']);
Route::post("submitBet",[App\Http\Controllers\usersController::class,'submitBet']);
Route::post("updateProfile",[App\Http\Controllers\usersController::class,'updateProfile']);
Route::post("prevGames",[App\Http\Controllers\usersController::class,'prevGames']);
Route::post("lastGame",[App\Http\Controllers\usersController::class,'lastGame']);
Route::post("allGames",[App\Http\Controllers\usersController::class,'allGames']);
Route::get("getGameId",[App\Http\Controllers\usersController::class,'getGameId']);
Route::post("userLogout",[App\Http\Controllers\usersController::class,'userLogout']);
Route::post("cash_payments",[App\Http\Controllers\usersController::class,'cash_payments']);
Route::post("direct_list",[App\Http\Controllers\usersController::class,'direct_list']);
Route::post("team_list",[App\Http\Controllers\usersController::class,'team_list']);
Route::post("withdraw",[App\Http\Controllers\usersController::class,'withdraw']);
Route::post("withdraw_live",[App\Http\Controllers\usersController::class,'withdraw_live']);
Route::post("withdraws",[App\Http\Controllers\usersController::class,'withdraw_details']);

Route::post("upi",[App\Http\Controllers\usersController::class,'getUpi']);
Route::post("getTime",[App\Http\Controllers\usersController::class,'getTime']);

Route::post("post_swap",[App\Http\Controllers\usersController::class,'swap']);


Route::get("test",[App\Http\Controllers\BinanceController::class,'test']);

Route::post("getBalance",[App\Http\Controllers\BinanceController::class,'getBalance']);
Route::post("usdtBalance",[App\Http\Controllers\BinanceController::class,'usdtBalance']);
Route::post("getPrice",[App\Http\Controllers\BinanceController::class,'getPrice']);



Route::post("buyCoin",[App\Http\Controllers\BinanceController::class,'buyCoin']);
Route::post("sellCoin",[App\Http\Controllers\BinanceController::class,'sellCoin']);

Route::post("manualBuy",[App\Http\Controllers\BinanceController::class,'manualBuy']);
Route::post("manualSell",[App\Http\Controllers\BinanceController::class,'manualSell']);
Route::post("closeOrder",[App\Http\Controllers\BinanceController::class,'closeOrder']);


Route::post("cancelOrder",[App\Http\Controllers\BinanceController::class,'cancelOrder']);




Route::post("openBuyOrder",[App\Http\Controllers\BinanceController::class,'openBuyOrder']);
Route::post("openSellOrder",[App\Http\Controllers\BinanceController::class,'openSellOrder']);



Route::post("tradeHistory",[App\Http\Controllers\BinanceController::class,'tradeHistory']);
Route::post("user_register",[App\Http\Controllers\usersController::class,'user_register']);
Route::post("thank_you",[App\Http\Controllers\usersController::class,'thankyou']);
// Route::post("coinBalance",[App\Http\Controllers\usersController::class,'coinBalance']);
Route::post("deposit",[App\Http\Controllers\BinanceController::class,'deposit']);
Route::post("invest",[App\Http\Controllers\usersController::class,'invest']);
Route::post("post_deposit",[App\Http\Controllers\usersController::class,'post_deposit']);
Route::post("invest_history",[App\Http\Controllers\usersController::class,'invest_history']);
Route::post("direct_referral",[App\Http\Controllers\usersController::class,'direct_referral']);
Route::post("level_bonus",[App\Http\Controllers\usersController::class,'level_bonus']);

Route::post("packages",[App\Http\Controllers\usersController::class,'packages']);
Route::post("get_address",[App\Http\Controllers\usersController::class,'get_address']);


Route::post("coinBalance",[App\Http\Controllers\BinanceController::class,'assetBalance']);




Route::post("success_url",[App\Http\Controllers\BinanceController::class,'success_url']);
Route::post("getAssets",[App\Http\Controllers\BinanceController::class,'getAssets']);
Route::post("getCoins",[App\Http\Controllers\BinanceController::class,'getCoins']);
Route::post("recieveCoin",[App\Http\Controllers\BinanceController::class,'recieveCoin']);
Route::get("posts",[App\Http\Controllers\usersController::class,'getPosts']);
Route::post("deposit_history",[App\Http\Controllers\usersController::class,'deposit_history']);
Route::post("check_token",[App\Http\Controllers\usersController::class,'check_token']);
Route::post("getGasFee",[App\Http\Controllers\BinanceController::class,'getGasFee']);
Route::post("sendToken",[App\Http\Controllers\BinanceController::class,'sendToken']);
Route::post("transferToken",[App\Http\Controllers\BinanceController::class,'transferToken']);
Route::post("openOrders",[App\Http\Controllers\BinanceController::class,'openOrders']);
Route::post("closeOrders",[App\Http\Controllers\BinanceController::class,'closeOrders']);
Route::post("crypto_history",[App\Http\Controllers\BinanceController::class,'crypto_history']);
Route::get("coin_details",[App\Http\Controllers\BinanceController::class,'coin_details']);
Route::get("coin_balance",[App\Http\Controllers\BinanceController::class,'coin_balance']);
Route::get("total_balance",[App\Http\Controllers\BinanceController::class,'total_balance']);
Route::get("asset_price",[App\Http\Controllers\BinanceController::class,'asset_price']);
Route::post("getAssetPrice",[App\Http\Controllers\BinanceController::class,'getAssetPrice']);
Route::post("transfer_usdt",[App\Http\Controllers\BinanceController::class,'transferCoin']);



//future api's
Route::post("getFutureSymbols",[App\Http\Controllers\FutureController::class,'getSymbols']);
Route::get("future_coin_details",[App\Http\Controllers\FutureController::class,'coin_details']);
Route::post("getFuturePrice",[App\Http\Controllers\FutureController::class,'getAssetPrice']);
Route::get("getLeverage",[App\Http\Controllers\FutureController::class,'getLeverage']);
Route::post("setLeverage",[App\Http\Controllers\FutureController::class,'setLeverage']);
Route::post("setMargin",[App\Http\Controllers\FutureController::class,'setMargin']);

Route::post("manualFutureBuy",[App\Http\Controllers\FutureController::class,'manualBuy']);
Route::post("manualFutureSell",[App\Http\Controllers\FutureController::class,'manualSell']);
Route::get("openPositions",[App\Http\Controllers\FutureController::class,'openPositions']);
Route::get("positionHistory",[App\Http\Controllers\FutureController::class,'positionHistory']);
Route::post("closePosition",[App\Http\Controllers\FutureController::class,'closePosition']);
Route::post("coinFutureBalance",[App\Http\Controllers\FutureController::class,'coinBalance']);
Route::get("future_total_balance",[App\Http\Controllers\FutureController::class,'total_balance']);
Route::post("limitBuyOrder",[App\Http\Controllers\FutureController::class,'openBuyOrder']);
Route::post("limitSellOrder",[App\Http\Controllers\FutureController::class,'openSellOrder']);
Route::post("openFutureOrders",[App\Http\Controllers\FutureController::class,'openOrders']);
Route::post("futureOrderHistory",[App\Http\Controllers\FutureController::class,'orderHistory']);
Route::post("updatePl",[App\Http\Controllers\FutureController::class,'updatePl']);
Route::post("cancelFutureOrder",[App\Http\Controllers\FutureController::class,'cancelOrder']);






//future api's end




Route::post("getSymbols",[App\Http\Controllers\usersController::class,'getSymbols']);
Route::post("transfer_history",[App\Http\Controllers\usersController::class,'transfer_history']);

Route::post("forgetPassword",[App\Http\Controllers\usersController::class,'forgetPassword']);
Route::post("verfiyOtp",[App\Http\Controllers\usersController::class,'verfiyOtp']);

Route::post("npfPrices",[App\Http\Controllers\usersController::class,'npf_prices']);

Route::get("usdt_price",[App\Http\Controllers\usersController::class,'usdt_price']);

Route::get("getLiveDeposits",[App\Http\Controllers\usersController::class,'getLiveDeposits']);
Route::post("set_transactions",[App\Http\Controllers\usersController::class,'set_transactions']);


Route::get("getEthLiveDeposits",[App\Http\Controllers\usersController::class,'getEthLiveDeposits']);
// Route::post("set_eth_transactions",[App\Http\Controllers\usersController::class,'set_eth_transactions']);
Route::post("setTronTransaction",[App\Http\Controllers\usersController::class,'setTronTransaction']);







// Route::get("curl",[App\Http\Controllers\usersController::class,'curl']);
