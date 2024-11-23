<?php

namespace App\Http\Controllers;


use App\price;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\trade;
use App\wallet;
use App\User;
use App\payment_log;
use App\payment;
use App\callback;
use App\coin;
use App\crypto_wallet;
use App\asset;
use App\leverage;
use App\open_order;
use Auth;

use Tymon\JWTAuth\Facades\JWTAuth;

class FutureController extends Controller
{

    public function getSymbols()
    {
        $symbols = asset::where("type","future")->get();
        return response()->json($symbols);
    }

    public function coin_details(Request $request){
        $ch = curl_init();


        \Log::info("link".env('mix_api_url')."future_coin.json");
        // Set the URL for the request
        curl_setopt($ch, CURLOPT_URL, env('mix_api_url')."future_coin.json");

        // Set options to return the result as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute the request and get the response
        $response = curl_exec($ch);

        // Check for errors
        if (curl_errno($ch)) {
            return response()->json( curl_error($ch));
        } else {
            // Decode the JSON response
            $data = json_decode($response, true);
            $coins = [];
            foreach($data as $dat){
                if($dat['symbol'] == $request->coin."USDT"){
                    $coins[] = $dat;
                }
            }
            return response()->json($coins);



            // Check if decoding was successful
            if (json_last_error() === JSON_ERROR_NONE) {
                // Print the data
                // return response()->json($data);
            } else {
                return response()->json(json_last_error_msg());
            }
        }
    }

    public function getAssetPrice(Request $request){
        \Log::info("getAssetPrice");
        $price= $this->getPrice($request->coin);
        \Log::info(json_encode($price));
        return response()->json($price);

    }


    public function setLeverage(Request $request){
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'coin' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()], 500);
            exit;
        }

        $user = JWTAuth::parseToken()->authenticate();

        leverage::updateOrCreate(
            ["asset"=>$request->coin,"user_id"=>$user->id],
            ["leverage"=>$request->leverage]
         );

         return response()->json(["message"=>"Leverage update successfully"],200);
    }
    public function getLeverage(Request $request){
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'coin' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()], 500);
            exit;
        }

        $user = JWTAuth::parseToken()->authenticate();

        $lev = leverage::where("user_id",$user->id)->where("asset",$request->coin)->pluck("leverage");
        $mar = leverage::where("user_id",$user->id)->where("asset",$request->coin)->pluck("margin");
        $leverage = 5;
        if(COUNT($lev) > 0){
            $leverage = $lev[0];
        }
        $margin = "isloated";
        if(COUNT($mar) > 0){
            $margin = $mar[0];
        }

        return response()->json(compact('leverage', 'margin'));

    }


    public function manualBuy(Request $request){
        $validator = Validator::make($request->all(), [
            'coin' => 'required',
            'quantity' => 'required',
            'token' => 'required'
        ]);



        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()], 500);
            exit;
        }
        if($request->quantity <= 0){
            return response()->json(['message' => "Amount should be greater than zero"], 500);
        }


        // if($request->coin == "NPF"){
        //     return response()->json(["message"=>"order not available for this token"],500);
        // }

        $user = JWTAuth::parseToken()->authenticate();
        $balance = $this->getBalance($user->id,"usd");

        $price= price::orderBy("id",'desc')->pluck('price')[0];

         $symbol = $request->coin."USDT";
        if($request->coin != "NPF"){
            $price = $this->getPrice($request->coin);
        }

        $tickSize = $this->coin_info($request->coin);

        $size = 0;
        if(count($tickSize) > 0){
            $size = $tickSize[0];
        }

        //for take profit
        if($request->take_profit  != null ){
            if($request->take_profit <= $price){
                return response()->json(["message"=>"Order rejected, Take profit should be greater than market price"],500);
                exit;
            }
        }

        //for stop loss
        if($request->stop_loss  != null ){
            if($request->stop_loss >= $price){
                return response()->json(["message"=>"Order rejected, Stop loss should be less than market price"],500);
                exit;
            }
        }


        // $final_amount = $request->quantity * $price;   //old code
        $leverage = 1;
        if($leverage > 0){
            $leverage = $request->leverage;
        }

        //for cross margin order
        $cal_amount = ($request->quantity * $price)/$leverage;
        $final_amount = bcdiv($cal_amount, 1, $size);

        // \Log::info("final_amount".$final_amount);


        if($final_amount > $balance){
            return response()->json(["message"=>"Something went wrong"],500);
        }

        if($final_amount < 1){
            return response()->json(['message' => "Minimum trade amount is $1"], 500);
        }




        $order_id =  now()->timestamp;

        //position already exist
        // $open_order = trade::where("user_id",  $user->id)->where('status',0)->where("order_type","Market")->where("type","Sell")->where("trade_type","future")->where('coin', $request->coin)->orderBy("id","desc")->first();
        // if($open_order != null) {

        //     $amount  = ($open_order->amount - $final_amount);
        //     $quantity =  ($open_order->quantity - $request->quantity);

        //     $pnl = ($price - $open_order->price) * $request->quantity;

        //     //calculate for cross margin order
        //     $close_amount = $open_order->amount - $pnl;


        //     trade::where("id",$open_order->id)->update([
        //         "close_price"=>$price,
        //         "close_amount"=>$close_amount,
        //         "close_quantity"=>$request->quantity,
        //         'status' => 1,
        //     ]);




        //     //debit close position coin quantity from wallet on previous sell position
        //     $wallet = new wallet();
        //     $wallet->user_id = $user->uid;
        //     $wallet->userId = $user->id;
        //     $wallet->amount = $request->quantity;
        //     $wallet->from = "future_trade";
        //     $wallet->transaction_type = "sell";
        //     $wallet->wallet_type = $request->coin;
        //     $wallet->type = "debit";
        //     $wallet->description = "debit " . $request->quantity . " from sell close position";
        //     $wallet->symbol = $symbol;
        //     $wallet->trade_type = "future";
        //     $wallet->save();

        //     //credit close position amount in wallet on previous sell position
        //     $wallet = new wallet();
        //     $wallet->user_id = $user->uid;
        //     $wallet->userId = $user->id;
        //     $wallet->amount = $close_amount;
        //     $wallet->from = "future_trade";
        //     $wallet->transaction_type = "sell";
        //     $wallet->wallet_type = "usd";
        //     $wallet->type = "credit";
        //     $wallet->description = "credit " . $close_amount . " from sell close position";
        //     $wallet->symbol = $symbol;
        //     $wallet->trade_type = "future";
        //     $wallet->save();


        //         if($amount != 0 && $quantity != 0){
        //             $trade_type = ($request->quantity >= $open_order->quantity) ? 'Buy':'Sell';

        //             if($trade_type == "Buy"){
        //                 $amount = -$amount;
        //                 $quantity = -$quantity;
        //             }

        //             $trade = new trade();
        //             $trade->price = $price;
        //             $trade->user_id = $user->id;
        //             $trade->quantity = $quantity;
        //             $trade->symbol = $symbol;
        //             $trade->type = $trade_type;
        //             $trade->amount = $amount;
        //             $trade->order_id = $order_id;
        //             $trade->trade_type = "future";
        //             $trade->coin = $request->coin;
        //             $trade->leverage = $open_order->leverage;
        //             $trade->margin_mode = $open_order->margin_mode;
        //             $trade->save();

        //             //debit when sell order is larger than previous position
        //             $wallet = new wallet();
        //             $wallet->user_id = $user->uid;
        //             $wallet->userId = $user->id;
        //             $wallet->amount = $amount;
        //             $wallet->from = "future_trade";
        //             $wallet->transaction_type = strtolower($trade_type);
        //             $wallet->wallet_type = "usd";
        //             $wallet->type = "debit";
        //             $wallet->description = "debit " . $amount . " from sell position $request->coin";
        //             $wallet->symbol = $symbol;
        //             $wallet->trade_type = "future";
        //             $wallet->save();

        //             //credit coin quantity in wallet
        //             $wallet = new wallet();
        //             $wallet->user_id = $user->uid;
        //             $wallet->userId = $user->id;
        //             $wallet->amount = $quantity;
        //             $wallet->from = "future_trade";
        //             $wallet->transaction_type = strtolower($trade_type);
        //             $wallet->wallet_type = $request->coin;
        //             $wallet->type = "credit";
        //             $wallet->description = "credit " . $quantity . " from sell";
        //             $wallet->symbol = $symbol;
        //             $wallet->trade_type = "future";
        //             $wallet->save();
        //         }
        // }
        // // open new position for buy
        // else{

                $trade = new trade();
                $trade->price = $price;
                $trade->user_id = $user->id;
                $trade->quantity = $request->quantity;
                $trade->symbol = $symbol;
                $trade->type = "Buy";
                $trade->amount = $final_amount;
                $trade->order_id =  $order_id;
                $trade->trade_type = "future";
                $trade->coin = $request->coin;
                $trade->leverage = $request->leverage;
                $trade->margin_mode = $request->margin_mode;
                if($request->take_profit  != null ){
                    $trade->take_profit = $request->take_profit;
                }
                if($request->stop_loss  != null ){
                    $trade->stop_loss = $request->stop_loss;
                }
                $trade->save();

                $wallet = new wallet();
                $wallet->user_id = $user->uid;
                $wallet->userId = $user->id;
                $wallet->amount = $final_amount;
                $wallet->from = "future_trade";
                $wallet->transaction_type = "buy";
                $wallet->wallet_type = "usd";
                $wallet->type = "debit";
                $wallet->description = "debit " . $final_amount . "from buy $request->coin";
                $wallet->symbol = $symbol;
                $wallet->trade_type = "future";
                $wallet->save();

                $wallet = new wallet();
                $wallet->user_id = $user->uid;
                $wallet->userId = $user->id;
                $wallet->amount = $request->quantity;
                $wallet->from = "future_trade";
                $wallet->transaction_type = "buy";
                $wallet->wallet_type = $request->coin;
                $wallet->type = "credit";
                $wallet->description = "credit " .  $request->quantity . "from buy $request->coin";
                $wallet->symbol = $symbol;
                $wallet->trade_type = "future";
                $wallet->save();
        // }

            return response()->json(["message" => "Order Placed Successfully!"], 200);

    }

    public function manualSell(Request $request){
        $validator = Validator::make($request->all(), [
            'coin' => 'required',
            'quantity' => 'required',
            'token' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()], 500);
            exit;
        }
        $user = JWTAuth::parseToken()->authenticate();

        $user = JWTAuth::parseToken()->authenticate();
        // $balance = $this->getBalance($user->id,$request->coin);
        $balance = $this->getBalance($user->id,"usd");

        $price = price::orderBy("id",'desc')->pluck('price')[0];

        $symbol = $request->coin."USDT";

        if($request->coin != "NPF"){
            $price = $this->getPrice($request->coin);
        }

        $tickSize = $this->coin_info($request->coin);


        $size = 0;
        if(count($tickSize) > 0){
            $size = $tickSize[0];
        }


        //for take profit
        if($request->take_profit  != null ){
            if($request->take_profit >= $price){
                return response()->json(["message"=>"Order rejected, Take profit should be less than market price"],500);
                exit;
            }
        }

        //for stop loss
        if($request->stop_loss  != null ){
            if($request->stop_loss <= $price){
                return response()->json(["message"=>"Order rejected, Stop loss should be greater than market price"],500);
                exit;
            }
        }


        $cal_amount = ($request->quantity * $price)/$request->leverage;

        //set value from range selector
        // if($request->coin_type == "per"){
        //     $cal_amount = ($request->quantity * $price);
        // }



        $final_amount = bcdiv($cal_amount, 1, $size);


        if($final_amount > $balance){
            return response()->json(["message"=>"Something went wrong"],500);
        }

        if($request->quantity <= 0){
            return response()->json(['message' => "Amount should be greater than zero"], 500);
        }

        if($final_amount < 1){
            return response()->json(['message' => "Minimum trade amount is $1"], 500);
        }


        $order_id =  now()->timestamp;



        // //position already exist
        // $open_order = trade::where("user_id",  $user->id)->where('status',0)->where("order_type","Market")->where("type","Buy")->where("trade_type","future")->where('coin', $request->coin)->orderBy("id","desc")->first();
        // // $order_id =  now()->timestamp;
        // if($open_order != null) {
        //     $amount  = ($open_order->amount - $final_amount);
        //     $quantity =  ($open_order->quantity - $request->quantity);



        //     $pnl = ($price - $open_order->price) * $request->quantity;
        //     // $pnl = bcdiv($pnl, 1, 2);


        //     //calculate for cross margin order
        //     $close_amount = $open_order->amount + $pnl;

        //     trade::where("id",$open_order->id)->update([
        //         "close_price"=>$price,
        //         "close_amount"=>$close_amount,
        //         "close_quantity"=>$request->quantity,
        //         'status' => 1,
        //     ]);

        //     if($amount < 0){
        //         $amount = -$amount;
        //     }

        //     if($quantity < 0){
        //         $quantity = -$quantity;
        //     }


        //      //debit  close position quantity from wallet on previous buy position
        //      $wallet = new wallet();
        //      $wallet->user_id = $user->uid;
        //      $wallet->userId = $user->id;
        //      $wallet->amount = $request->quantity;
        //      $wallet->from = "future_trade";
        //      $wallet->transaction_type = "buy";
        //      $wallet->wallet_type = $request->coin;
        //      $wallet->type = "debit";
        //      $wallet->description = "debit " . $request->quantity . " from buy close position";
        //      $wallet->symbol = $symbol;
        //      $wallet->trade_type = "future";
        //      $wallet->save();

        //     //credit close position amount in wallet on previous buy position
        //     $wallet = new wallet();
        //     $wallet->user_id = $user->uid;
        //     $wallet->userId = $user->id;
        //     $wallet->amount = $close_amount;
        //     $wallet->from = "future_trade";
        //     $wallet->transaction_type = "buy";
        //     $wallet->wallet_type = "usd";
        //     $wallet->type = "credit";
        //     $wallet->description = "credit " . $close_amount . " from buy close position";
        //     $wallet->symbol = $symbol;
        //     $wallet->trade_type = "future";
        //     $wallet->save();


        //     // amount not equal to prev amount open diff position
        //     if($amount != 0 && $quantity !=0){

        //         $trade_type = ($request->quantity >= $open_order->quantity) ? 'Sell':'Buy';



        //         $trade = new trade();
        //         $trade->price = $price;
        //         $trade->user_id = $user->id;
        //         $trade->quantity = $quantity;
        //         $trade->symbol = $symbol;
        //         $trade->type = $trade_type;
        //         $trade->amount = $amount;
        //         $trade->order_id = $order_id;
        //         $trade->trade_type = "future";
        //         $trade->coin = $request->coin;
        //         $trade->leverage = $open_order->leverage;
        //         $trade->margin_mode = $open_order->margin_mode;
        //         $trade->save();

        //         //debit when sell order is larger than previous position
        //         $wallet = new wallet();
        //         $wallet->user_id = $user->uid;
        //         $wallet->userId = $user->id;
        //         $wallet->amount = $amount;
        //         $wallet->from = "future_trade";
        //         $wallet->transaction_type = strtolower($trade_type);
        //         $wallet->wallet_type = "usd";
        //         $wallet->type = "debit";
        //         $wallet->description = "debit " . $amount . " from sell position $request->coin";
        //         $wallet->symbol = $symbol;
        //         $wallet->trade_type = "future";
        //         $wallet->save();

        //         //credit coin quantity in wallet
        //         $wallet = new wallet();
        //         $wallet->user_id = $user->uid;
        //         $wallet->userId = $user->id;
        //         $wallet->amount = $quantity;
        //         $wallet->from = "future_trade";
        //         $wallet->transaction_type = strtolower($trade_type);
        //         $wallet->wallet_type = $request->coin;
        //         $wallet->type = "credit";
        //         $wallet->description = "credit " . $quantity . " from sell";
        //         $wallet->symbol = $symbol;
        //         $wallet->trade_type = "future";
        //         $wallet->save();
        //     }



        // }
        // // open new position for sell
        // else{

                $trade = new trade();
                $trade->price = $price;
                $trade->user_id = $user->id;
                $trade->quantity = $request->quantity;
                $trade->symbol = $symbol;
                $trade->coin = $request->coin;
                $trade->type = "Sell";
                $trade->amount = $final_amount;
                $trade->order_id = $order_id;
                $trade->trade_type = "future";
                $trade->leverage = $request->leverage;
                $trade->margin_mode = $request->margin_mode;
                if($request->take_profit  != null ){
                    $trade->take_profit = $request->take_profit;
                }
                if($request->stop_loss  != null ){
                    $trade->stop_loss = $request->stop_loss;
                }
                $trade->save();

                $wallet = new wallet();
                $wallet->user_id = $user->uid;
                $wallet->userId = $user->id;
                $wallet->amount = $final_amount;
                $wallet->from = "future_trade";
                $wallet->transaction_type = "sell";
                $wallet->wallet_type = "usd";
                $wallet->type = "debit";
                $wallet->description = "debit " . $final_amount . " from sell $request->coin";
                $wallet->symbol = $symbol;
                $wallet->trade_type = "future";
                $wallet->save();

                $wallet = new wallet();
                $wallet->user_id = $user->uid;
                $wallet->userId = $user->id;
                $wallet->amount = $request->quantity;
                $wallet->from = "future_trade";
                $wallet->transaction_type = "sell";
                $wallet->wallet_type = $request->coin;
                $wallet->type = "credit";
                $wallet->description = "credit " . $request->quantity . " from sell";
                $wallet->symbol = $symbol;
                $wallet->trade_type = "future";
                $wallet->save();
        // }

        return response()->json(["message" => "Order Placed Successfully!"], 200);

    }



    public function closePosition(Request $request){
        $user = JWTAuth::parseToken()->authenticate();
        $trades =  trade::where('id', $request->id)->first();


        $trades =  trade::where('id', $request->id)->first();

        if($trades->status == 1){
            return response()->json(["message"=>'Position already closed'],500);
        }


        // $balance = $this->balance($user->id,$request->coin);

        $price= price::orderBy("id",'desc')->pluck('price')[0];
        // $data->coin = 'BTC';
        $symbol = $trades->symbol;


        if($trades->coin != "NPF"){
            $price = $this->getPrice($trades->coin);
        }


        $tickSize = $this->coin_info($trades->coin);

        $size = 0;
        if(count($tickSize) > 0){
            $size = $tickSize[0];
        }

        // $cal_amount = ($trades->quantity * $price)/$trades->leverage;

        $pnl = ($price - $trades->price) * $trades->quantity;
        // $pnl = bcdiv($pnl, 1, 2);


        //calculate for cross margin order
        $cal_amount = $trades->amount + $pnl;

        //open type sell then calculate amount (minus amount)
        if($trades->type == "Sell"){
            $cal_amount = $trades->amount - $pnl;
        }

        $leverage= 1;
        if($trades->leverage > 0){
            $leverage = $trades->leverage;
        }


        //calculate for isolated margin order
        // $liq_per = $this->calLiqPer($trades->quantity,$leverage,$trades->price,$price);
        // $liq_amount = ($liq_per/100) * $trades->amount;
        // $cal_amount = $trades->amount + $liq_amount;

        // $final_amount = bcdiv($cal_amount, 1, $size);
        $final_amount = bcdiv($cal_amount, 1, 4);


        //close previous
        trade::where('id', $request->id)->update([
            'status' => 1,
            'close_type' => ucfirst($trades->type),
            "close_price"=>$price,
            "close_amount"=>$final_amount,
            "close_quantity"=>$trades->quantity,
        ]);

            $wallet = new wallet();
            $wallet->user_id = $user->uid;
            $wallet->userId = $user->id;
            $wallet->amount = $trades->quantity;
            $wallet->from = "future_trade";
            $wallet->transaction_type = strtolower($trades->type);
            $wallet->wallet_type = $trades->coin;
            $wallet->type = "debit";
            $wallet->description = "debit " . $trades->quantity . " from close order $trades->coin";
            $wallet->symbol = $symbol;
            $wallet->trade_type = "future";
            $wallet->save();

            $wallet = new wallet();
            $wallet->user_id = $user->uid;
            $wallet->userId = $user->id;
            $wallet->amount = $final_amount;
            $wallet->from = "future_trade";
            $wallet->transaction_type = strtolower($trades->type);
            $wallet->wallet_type = "usd";
            $wallet->type = "credit";
            $wallet->description = "credit " . $final_amount . " from close order $trades->coin";
            $wallet->symbol = $symbol;
            $wallet->trade_type = "future";
            $wallet->save();


         return response()->json(["message" => "Position Closed Successfully!"], 200);

    }

    public function openPositions(Request $request){
        $validator = Validator::make($request->all(), [
            'token' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()], 500);
            exit;
        }
        $user = JWTAuth::parseToken()->authenticate();

        $history = trade::where("user_id",$user->id)->where("trade_type","future")->where("status",0)->where("trade_status",1)->orderBy("id","desc")->paginate(50);
        return response()->json(compact('history'));
    }


    public function positionHistory(Request $request){
        $validator = Validator::make($request->all(), [
            'token' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()], 500);
            exit;
        }
        $user = JWTAuth::parseToken()->authenticate();

        $history = trade::where("user_id",$user->id)->where("trade_type","future")->where("status",1)->orderBy("id","desc")->paginate(50);

        // set sell order minus sign
        $history->map(function($data){
            $pnl = ($data->close_amount - $data->amount);
            $data->pnl = $pnl;
            return $data;
        });

        $today_open_amount = trade::where("trade_type","future")->where("user_id",$user->id)->whereDate("created_at",Carbon::today())->sum('amount');
        $today_close_amount = trade::where("trade_type","future")->where("user_id",$user->id)->whereDate("created_at",Carbon::today())->sum('close_amount');
        $today_pnl = $today_close_amount - $today_open_amount;

        return response()->json(compact('history','today_pnl'));
    }



    protected function getPrice($coin){
        \Log::info("here price outer top ");

        $price = 0;
        if($coin == "NPF"){
            $price = price::orderBy("id","desc")->pluck('price')[0];
        }
        else{
            \Log::info("here price ");
            $symbol= strtoupper($coin."USDT");
            $ch = curl_init();

            
            // Set the URL for the request
            // curl_setopt($ch, CURLOPT_URL, "https://fapi.binance.com/fapi/v2/ticker/price?symbol=$symbol");
            // curl_setopt($ch, CURLOPT_URL, "https://test.mydreamgrow.in/admin/api/getFuturePrice?symbol=$symbol");
            curl_setopt($ch, CURLOPT_URL, "https://muftdeal.com/api/getFuturePrice?symbol=$symbol");

            // Set options to return the result as a string
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // Execute the request and get the response
            $response = curl_exec($ch);
            \Log::info("price ");
            \Log::info($response);
            $data  = json_decode($response);
            $price =  $data->price;
        }
        return $price;

    }

    protected function getBalance($user_id, $wallet_type)
    {
        $credit = wallet::where("userId", $user_id)->where("wallet_type", $wallet_type)->where("trade_type","future")->where("type", "credit")->sum('amount');
        $debit = wallet::where("userId", $user_id)->where("wallet_type", $wallet_type)->where("trade_type","future")->where("type", "debit")->sum('amount');

        // $balance = round($credit - $debit,6);
        $balance = number_format((float) ($credit - $debit), 5, '.', '');

        return $balance;
    }

    public function coinBalance(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $balance = $this->getBalance($user->id, $request->coin);
        return response()->json($balance);
    }

    protected function coin_info($asset="BTC")
    {
        $ch = curl_init();

        $url = env('mix_app_url').'future_coin.json';

        // Set the URL for the request
        curl_setopt($ch, CURLOPT_URL, $url);

        // Set options to return the result as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute the request and get the response
        $response = curl_exec($ch);

        // Check for errors
        if (curl_errno($ch)) {
            return curl_error($ch);
        } else {
            // Decode the JSON response
            $data = json_decode($response, true);
            $coins = [];
            foreach($data as $dat){
                if($dat['symbol'] == $asset."USDT"){
                    $coins[] = $dat['tickSize'];
                }
            }
            return $coins;



            // Check if decoding was successful
            if (json_last_error() === JSON_ERROR_NONE) {
                // Print the data
                // return response()->json($data);
            } else {
                return json_last_error_msg();
            }
        }
    }



    // const calLiqPer = async (symbol,size,leverage,open_price) =>{
    //     var current_price = await fetchPrice(symbol);
    //     console.log(current_price);

    //     var order_value = (current_price * size)/leverage;   //pnl or margin
    //     var unrealized_value = (current_price-open_price)*size;
    //     var percentage  = (unrealized_value/order_value)*100;
    //     return percentage.toFixed(2);
    // }
    protected  function calLiqPer($size,$leverage,$open_price,$current_price){
        $order_value = ($current_price*$size)/$leverage;
        $unrealized_value = ($current_price-$open_price)*$size;
        $percentage  = ($unrealized_value/$order_value)*100;
        return  number_format( ($percentage), 2, '.', '');
    }



    public function openOrders(Request $request){
        $validator = Validator::make($request->all(), [
            'token' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()], 500);
            exit;
        }
        $user = JWTAuth::parseToken()->authenticate();

        $history = trade::where("user_id",$user->id)->where("trade_type","future")->where("order_type","Limit")->where("trade_status",0)->where("status","!=",2)->orderBy("id","desc")->paginate(6);
        return response()->json(compact('history'));
    }


    public function orderHistory(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $trades = trade::where("user_id", $user->id)->where("trade_status",1)->where("trade_type","future")->where("order_type","Limit")->orderBy('id','desc')->paginate(6);
        return response()->json(compact('trades'));
    }
    // public function futureOrderHistory(Request $request)
    // {
    //     $user = JWTAuth::parseToken()->authenticate();

    //     $trades = trade::where("user_id", $user->id)->where("status","!=",2)->where("trade_type","future")->where("order_type","Limit")->orderBy('id','desc')->paginate(6);
    //     return response()->json(compact('trades'));
    // }


    public function total_balance(Request $request){
        $validator = Validator::make($request->all(), [
            'token' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()], 500);
            exit;
        }

        $user = JWTAuth::parseToken()->authenticate();
        $usdt_balance = $this->getBalance($user->id,"usd");

        //get all exist trade wallet type in wallet table except usdt and usd
        $all_coins = wallet::where("userId",$user->id)->where("trade_type","future")->where("wallet_type","!=",null)->groupBy("wallet_type")->pluck("wallet_type");

        $usdt_coin_balance = 0;
        if(count($all_coins)  > 0){
            $leverage = 5;
        for($i=0;$i<count($all_coins);$i++){
            $lev = leverage::where("asset",$all_coins[$i])->pluck("leverage");
            if(count($lev) > 0){
                $leverage = $lev[0];
            }
            // if($all_coins[$i] == "usd" || $all_coins[$i] == 'NPF'){
            if($all_coins[$i] == "usd"){
                continue;
            }
            // \Log::info("coins ".$all_coins[$i]."USDT");
            $price = $this->getPrice($all_coins[$i]);
            \Log::info($price);
            \Log::info($all_coins[$i]);
            $coin_balance = $this->getBalance(Auth::user()->id,$all_coins[$i]);
            $coin_balance = $coin_balance/$leverage;
            $usdt_coin_balance += ($price * $coin_balance);
        }

        \Log::info("coins balance".$usdt_coin_balance);
        \Log::info("usdt_balance".$usdt_balance);

        // $coin_balance = $this->balance(Auth::user()->id,$request->coin);
        // $price = $api->price($request->coin . "USDT");
        // $usdt_coin_balance = $price * $coin_balance;

        $total_balance = round(($usdt_coin_balance + $usdt_balance),4);

        return response()->json(compact('total_balance'));

        }
    }



    public function openBuyOrder(Request $request){
        $validator = Validator::make($request->all(), [
            'coin' => 'required',
            'price' => 'required',
            'token' => 'required'
        ]);



        \Log::info(json_encode($request->all()));


        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()], 500);
            exit;
        }


        if($request->quantity <= 0){
            return response()->json(['message' => "Amount should be greater than zero"], 500);
        }


        // if($request->coin == "NPF"){
        //     return response()->json(["message"=>"order not available for this token"],500);
        // }

        $user = JWTAuth::parseToken()->authenticate();
        $balance = $this->getBalance($user->id,"usd");

        $price= price::orderBy("id",'desc')->pluck('price')[0];

         $symbol = $request->coin."USDT";
        if($request->coin != "NPF"){
            $price = $this->getPrice($request->coin);
        }

        $tickSize = $this->coin_info($request->coin);

        $size = 0;
        if(count($tickSize) > 0){
            $size = $tickSize[0];
        }


        // $final_amount = $request->quantity * $price;   //old code
        $leverage = 1;
        if($leverage > 0){
            $leverage = $request->leverage;
        }

        //for cross margin order
        $cal_amount = ($request->quantity * $price)/$leverage;
        $final_amount = bcdiv($cal_amount, 1, $size);

        // \Log::info("final_amount".$final_amount);


        if($final_amount > $balance){
            return response()->json(["message"=>"Something went wrong"],500);
        }

        if($final_amount < 1){
            return response()->json(['message' => "Minimum trade amount is $1"], 500);
        }




        $order_id =  now()->timestamp;

        //position already exist
        $open_order = trade::where("user_id",  $user->id)->where('status',0)->where("order_type","Market")->where("type","Sell")->where("trade_type","future")->where('coin', $request->coin)->orderBy("id","desc")->first();
        if($open_order != null) {

            $amount  = ($open_order->amount - $final_amount);
            $quantity =  ($open_order->quantity - $request->quantity);

            $pnl = ($price - $open_order->price) * $request->quantity;

            //calculate for cross margin order
            $close_amount = $open_order->amount - $pnl;


            trade::where("id",$open_order->id)->update([
                "close_price"=>$price,
                "close_amount"=>$close_amount,
                "close_quantity"=>$request->quantity,
                'status' => 1,
            ]);




            //debit close position coin quantity from wallet on previous sell position
            $wallet = new wallet();
            $wallet->user_id = $user->uid;
            $wallet->userId = $user->id;
            $wallet->amount = $request->quantity;
            $wallet->from = "future_trade";
            $wallet->transaction_type = "sell";
            $wallet->wallet_type = $request->coin;
            $wallet->type = "debit";
            $wallet->description = "debit " . $request->quantity . " from sell close position";
            $wallet->symbol = $symbol;
            $wallet->trade_type = "future";

            $wallet->save();

            //credit close position amount in wallet on previous sell position
            $wallet = new wallet();
            $wallet->user_id = $user->uid;
            $wallet->userId = $user->id;
            $wallet->amount = $close_amount;
            $wallet->from = "future_trade";
            $wallet->transaction_type = "sell";
            $wallet->wallet_type = "usd";
            $wallet->type = "credit";
            $wallet->description = "credit " . $close_amount . " from sell close position";
            $wallet->symbol = $symbol;
            $wallet->trade_type = "future";
            $wallet->save();


                if($amount != 0 && $quantity != 0){
                    $trade_type = ($request->quantity >= $open_order->quantity) ? 'Buy':'Sell';

                    if($trade_type == "Buy"){
                        $amount = -$amount;
                        $quantity = -$quantity;
                    }

                    $trade = new trade();
                    $trade->price = $price;
                    $trade->user_id = $user->id;
                    $trade->quantity = $quantity;
                    $trade->symbol = $symbol;
                    $trade->type = $trade_type;
                    $trade->amount = $amount;
                    $trade->order_id = $order_id;
                    $trade->trade_type = "future";
                    $trade->coin = $request->coin;
                    $trade->leverage = $open_order->leverage;
                    $trade->order_type = "Limit";
                    $trade->margin_mode = $open_order->margin_mode;
                    $trade->save();

                    //debit when sell order is larger than previous position
                    $wallet = new wallet();
                    $wallet->user_id = $user->uid;
                    $wallet->userId = $user->id;
                    $wallet->amount = $amount;
                    $wallet->from = "future_trade";
                    $wallet->transaction_type = strtolower($trade_type);
                    $wallet->wallet_type = "usd";
                    $wallet->type = "debit";
                    $wallet->description = "debit " . $amount . " from sell position $request->coin";
                    $wallet->symbol = $symbol;
                    $wallet->trade_type = "future";
                    $wallet->save();

                    //credit coin quantity in wallet
                    $wallet = new wallet();
                    $wallet->user_id = $user->uid;
                    $wallet->userId = $user->id;
                    $wallet->amount = $quantity;
                    $wallet->from = "future_trade";
                    $wallet->transaction_type = strtolower($trade_type);
                    $wallet->wallet_type = $request->coin;
                    $wallet->type = "credit";
                    $wallet->description = "credit " . $quantity . " from sell";
                    $wallet->symbol = $symbol;
                    $wallet->trade_type = "future";
                    $wallet->save();
                }
        }
        // open new position for buy
        else{

            $price= $request->price;

                $trade = new trade();
                $trade->price = $price;
                $trade->user_id = $user->id;
                $trade->quantity = $request->quantity;
                $trade->symbol = $symbol;
                $trade->type = "Buy";
                $trade->amount = $final_amount;
                $trade->order_id =  $order_id;
                $trade->trade_type = "future";
                $trade->coin = $request->coin;
                $trade->leverage = $request->leverage;
                $trade->order_type = "Limit";
                $trade->trade_status = 0;
                $trade->margin_mode = $request->margin_mode;
                $trade->save();

                $wallet = new wallet();
                $wallet->user_id = $user->uid;
                $wallet->userId = $user->id;
                $wallet->amount = $final_amount;
                $wallet->from = "future_trade";
                $wallet->transaction_type = "buy";
                $wallet->wallet_type = "usd";
                $wallet->type = "debit";
                $wallet->description = "debit " . $final_amount . "from buy $request->coin";
                $wallet->symbol = $symbol;
                $wallet->trade_type = "future";
                $wallet->save();

                $wallet = new wallet();
                $wallet->user_id = $user->uid;
                $wallet->userId = $user->id;
                $wallet->amount = $request->quantity;
                $wallet->from = "future_trade";
                $wallet->transaction_type = "buy";
                $wallet->wallet_type = $request->coin;
                $wallet->type = "credit";
                $wallet->description = "credit " .  $request->quantity . "from buy $request->coin";
                $wallet->symbol = $symbol;
                $wallet->trade_type = "future";
                $wallet->save();
        }

            return response()->json(["message" => "Order Placed Successfully!"], 200);




    }

    public function openSellOrder(Request $request){
        $validator = Validator::make($request->all(), [
            'coin' => 'required',
            'price' => 'required',
            'token' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()], 500);
            exit;
        }


        $user = JWTAuth::parseToken()->authenticate();

        $user = JWTAuth::parseToken()->authenticate();
        // $balance = $this->getBalance($user->id,$request->coin);
        $balance = $this->getBalance($user->id,"usd");

        $price = price::orderBy("id",'desc')->pluck('price')[0];

        $symbol = $request->coin."USDT";

        if($request->coin != "NPF"){
            $price = $this->getPrice($request->coin);
        }

        $tickSize = $this->coin_info($request->coin);


        $size = 0;
        if(count($tickSize) > 0){
            $size = $tickSize[0];
        }


        $cal_amount = ($request->quantity * $price)/$request->leverage;

        //set value from range selector
        // if($request->coin_type == "per"){
        //     $cal_amount = ($request->quantity * $price);
        // }



        $final_amount = bcdiv($cal_amount, 1, $size);


        if($final_amount > $balance){
            return response()->json(["message"=>"Something went wrong"],500);
        }

        if($request->quantity <= 0){
            return response()->json(['message' => "Amount should be greater than zero"], 500);
        }

        if($final_amount < 1){
            return response()->json(['message' => "Minimum trade amount is $1"], 500);
        }


        $order_id =  now()->timestamp;



        //position already exist
        $open_order = trade::where("user_id",  $user->id)->where('status',0)->where("order_type","Market")->where("type","Buy")->where("trade_type","future")->where('coin', $request->coin)->orderBy("id","desc")->first();
        // $order_id =  now()->timestamp;
        if($open_order != null) {
            $amount  = ($open_order->amount - $final_amount);
            $quantity =  ($open_order->quantity - $request->quantity);



            $pnl = ($price - $open_order->price) * $request->quantity;
            // $pnl = bcdiv($pnl, 1, 2);


            //calculate for cross margin order
            $close_amount = $open_order->amount + $pnl;

            trade::where("id",$open_order->id)->update([
                "close_price"=>$price,
                "close_amount"=>$close_amount,
                "close_quantity"=>$request->quantity,
                'status' => 1,
            ]);

            if($amount < 0){
                $amount = -$amount;
            }

            if($quantity < 0){
                $quantity = -$quantity;
            }


             //debit  close position quantity from wallet on previous buy position
             $wallet = new wallet();
             $wallet->user_id = $user->uid;
             $wallet->userId = $user->id;
             $wallet->amount = $request->quantity;
             $wallet->from = "future_trade";
             $wallet->transaction_type = "buy";
             $wallet->wallet_type = $request->coin;
             $wallet->type = "debit";
             $wallet->description = "debit " . $request->quantity . " from buy close position";
             $wallet->symbol = $symbol;
             $wallet->trade_type = "future";
             $wallet->save();

            //credit close position amount in wallet on previous buy position
            $wallet = new wallet();
            $wallet->user_id = $user->uid;
            $wallet->userId = $user->id;
            $wallet->amount = $close_amount;
            $wallet->from = "future_trade";
            $wallet->transaction_type = "buy";
            $wallet->wallet_type = "usd";
            $wallet->type = "credit";
            $wallet->description = "credit " . $close_amount . " from buy close position";
            $wallet->symbol = $symbol;
            $wallet->trade_type = "future";
            $wallet->save();


            // amount not equal to prev amount open diff position
            if($amount != 0 && $quantity !=0){

                $trade_type = ($request->quantity >= $open_order->quantity) ? 'Sell':'Buy';



                $trade = new trade();
                $trade->price = $price;
                $trade->user_id = $user->id;
                $trade->quantity = $quantity;
                $trade->symbol = $symbol;
                $trade->type = $trade_type;
                $trade->amount = $amount;
                $trade->order_id = $order_id;
                $trade->trade_type = "future";
                $trade->coin = $request->coin;
                $trade->leverage = $open_order->leverage;
                $trade->order_type = "Limit";
                $trade->trade_status = 0;
                $trade->margin_mode = $open_order->margin_mode;
                $trade->save();

                //debit when sell order is larger than previous position
                $wallet = new wallet();
                $wallet->user_id = $user->uid;
                $wallet->userId = $user->id;
                $wallet->amount = $amount;
                $wallet->from = "future_trade";
                $wallet->transaction_type = strtolower($trade_type);
                $wallet->wallet_type = "usd";
                $wallet->type = "debit";
                $wallet->description = "debit " . $amount . " from sell position $request->coin";
                $wallet->symbol = $symbol;
                $wallet->trade_type = "future";
                $wallet->save();

                //credit coin quantity in wallet
                $wallet = new wallet();
                $wallet->user_id = $user->uid;
                $wallet->userId = $user->id;
                $wallet->amount = $quantity;
                $wallet->from = "future_trade";
                $wallet->transaction_type = strtolower($trade_type);
                $wallet->wallet_type = $request->coin;
                $wallet->type = "credit";
                $wallet->description = "credit " . $quantity . " from sell";
                $wallet->symbol = $symbol;
                $wallet->trade_type = "future";
                $wallet->save();
            }



        }
        // open new position for sell
        else{

                $price  = $request->price;

                $trade = new trade();
                $trade->price = $price;
                $trade->user_id = $user->id;
                $trade->quantity = $request->quantity;
                $trade->symbol = $symbol;
                $trade->coin = $request->coin;
                $trade->type = "Sell";
                $trade->amount = $final_amount;
                $trade->order_id = $order_id;
                $trade->trade_type = "future";
                $trade->leverage = $request->leverage;
                $trade->order_type = "Limit";
                $trade->margin_mode = $request->margin_mode;
                $trade->save();

                $wallet = new wallet();
                $wallet->user_id = $user->uid;
                $wallet->userId = $user->id;
                $wallet->amount = $final_amount;
                $wallet->from = "future_trade";
                $wallet->transaction_type = "sell";
                $wallet->wallet_type = "usd";
                $wallet->type = "debit";
                $wallet->description = "debit " . $final_amount . " from sell $request->coin";
                $wallet->symbol = $symbol;
                $wallet->trade_type = "future";
                $wallet->save();

                $wallet = new wallet();
                $wallet->user_id = $user->uid;
                $wallet->userId = $user->id;
                $wallet->amount = $request->quantity;
                $wallet->from = "future_trade";
                $wallet->transaction_type = "sell";
                $wallet->wallet_type = $request->coin;
                $wallet->type = "credit";
                $wallet->description = "credit " . $request->quantity . " from sell";
                $wallet->symbol = $symbol;
                $wallet->trade_type = "future";
                $wallet->save();
        }


        return response()->json(["message" => "Order Placed Successfully!"], 200);

    }

    public function updatePl(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'token' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()], 500);
            exit;
        }



        $trade = trade::findOrFail($request->id);

        $price = price::orderBy("id",'desc')->pluck('price')[0];

        if($trade->coin != "NPF"){
            $price = $this->getPrice($trade->coin);
        }
        if($request->stop_loss  && $request->stop_loss != ""){
            if($request->stop_loss >= $price && $trade->type == "Buy"){
                return response()->json(["message"=>"Order rejected, Stop loss should be less than market price"],500);
                exit;
            }
            else if($request->stop_loss <= $price && $trade->type == "Sell"){
                return response()->json(["message"=>"Order rejected, Stop loss should be greater than market price"],500);
                exit;
            }
            $trade->stop_loss = $request->stop_loss;
        }
        if($request->take_profit  && $request->take_profit != ""){

            if($request->take_profit >= $price && $trade->type == "Sell"){
                return response()->json(["message"=>"Order rejected, Take profit should be less than market price"],500);
                exit;
            }
            else if($request->take_profit <= $price && $trade->type == "Buy"){
                return response()->json(["message"=>"Order rejected, Take profit should be greater than market price"],500);
                exit;
            }
            $trade->take_profit = $request->take_profit;
        }
        $trade->save();

        return response()->json(["message"=>"PL/SL updated successfully"],200);


    }



    public function cancelOrder(Request $request) {

        $user = JWTAuth::parseToken()->authenticate();


        $trades =  trade::where('id', $request->id)->first();

        $close_type =  $trades->type == 'sell' ? 'buy' : 'sell';
        $symbol = $trades->symbol;



        trade::where("user_id", $user->id)->where("trade_type","future")->where('id', $request->id)->update([
            'status' => 2,
        ]);

        $debit_amount =  $trades->type == 'Buy' ? $trades->quantity : $trades->amount;
        $credit_amount =  $trades->type == 'Buy' ?  $trades->amount : $trades->quantity;
        $debit_wallet =  $trades->type == 'Buy' ? $trades->coin : "usd";
        $credit_wallet =  $trades->type == 'Buy' ?  "usd" : $trades->coin;




        $wallet = new wallet();
        $wallet->user_id = $user->uid;
        $wallet->userId = $user->id;
        $wallet->amount = $debit_amount;
        $wallet->from = "future_trade";
        $wallet->transaction_type = $close_type;
        $wallet->wallet_type = $debit_wallet;
        $wallet->type = "debit";
        $wallet->description = "debit " . $debit_amount . " from cancel order $trades->coin";
        $wallet->symbol = $symbol;
        $wallet->trade_type = $trades->trade_type;
        $wallet->save();



        $wallet = new wallet();
        $wallet->user_id = $user->uid;
        $wallet->userId = $user->id;
        $wallet->amount = $credit_amount;
        $wallet->from = "future_trade";
        $wallet->transaction_type = $close_type;
        $wallet->wallet_type = $credit_wallet;
        $wallet->type = "credit";
        $wallet->description = "credit " . $credit_amount . " from cancel order $trades->coin";
        $wallet->symbol = $symbol;
        $wallet->trade_type = $trades->trade_type;
        $wallet->save();
        return response()->json(['message' => "order cancelled Successfully"], 200);

    }





    public function setMargin(Request $request){
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'margin' => 'required',
            'coin' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()], 500);
            exit;
        }

        $user = JWTAuth::parseToken()->authenticate();

        leverage::updateOrCreate(
            ["asset"=>$request->coin,"user_id"=>$user->id],
            ["margin"=>$request->margin]
         );

         return response()->json(["message"=>"Margin update successfully"],200);
    }

}
