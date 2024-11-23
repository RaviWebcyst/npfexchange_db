<?php

namespace App\Http\Controllers;

use App\crypto_wallet;
use App\Mail\forgetOtp;
use App\Mail\forgetPassword;
use App\price;
use App\usdt_price;
use Illuminate\Http\Request;
use App\User;
use App\wallet;
use App\game;
use App\result;
use App\timer;
use App\game_id;
use App\payment;
use App\post;
use App\asset;
use App\level_income;
use App\downline;
use App\upi;
use App\withdraw;
use App\bonus_income;
use App\coin;
use Auth;
use Carbon\Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\JWTManager as JWT;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use ElephantIO\Client;
use Illuminate\Support\Facades\DB;
use neto737\BitGoSDK\BitGoSDK;
use neto737\BitGoSDK\Enum\CurrencyCode;

use Illuminate\Support\Facades\Mail;
use App\Mail\loginOtp;
use App\pack_active;
use App\package;
use App\setting;
use App\trade;

class usersController extends Controller
{


    public function users(Request $request)
    {
        $users = User::where('is_admin', 0)
            ->when($request->search && $request->type, function ($q) use ($request) {
                $q->where($request->type, "like", "%" . $request->search . "%");
            })->orderBy('id', 'desc')->paginate(50);
        $users->appends(["search" => $request->search, "type" => $request->type]);

        return view("admin.users", compact('users'));
    }

    public function activeUsers(Request $request)
    {
        $users = User::where("is_admin", "!=", 1)->where("enable", 1)->orderBy("id", "desc")
            ->when($request->type && $request->search, function ($q) use ($request) {
                $q->where($request->type, "like", "%$request->search%");

            })->orderBy('id', 'desc')
            ->paginate(50);
        $users->appends(["search" => $request->search]);

        return view("admin.activeUsers", compact('users'));
    }

    public function sendEpin(Request $request, $id)
    {
        $user = User::findOrFail($id);
        return view("admin.sendEpin", compact('user'));
    }


    //send epin balance to user by admin
    public function postEpin(Request $request, $id)
    {
        $user = User::where("id", $id)->first();

        $wallet = wallet::create([
            "amount" => $request->amount,
            "user_id" => $user->uid,
            "remarks" => $request->desc,
            "userId" => $id,
            "wallet_type" => "epin",
            "description" => "Send Admin to user",
            "from" => "admin",
            "type" => "credit",
            "transaction_type" => 'deposit'
        ]);



        // if($wallet->wasRecentlyCreated){
        //     return redirect()->route("admin.users")->with("success", "balance send successfully");
        // }
        return redirect()->route("admin.users")->with("success", "balance send successfully");
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view("admin.userEdit", compact('user'));
    }

    public function updateUser($id, Request $request)
    {
        $validator = Validator::make($request->all(), [

            'email' => 'email|max:255|unique:users',

        ]);
        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->messages());

        }
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;


        if ($request->password != "") {
            $user->password = Hash::make($request->password);
            $user->showPass = $request->password;
        }
        $user->save();

        return redirect()->route("admin.users")->with("success", "user updated successfully");
    }


    public function all_transactions(Request $request)
    {
        $records = wallet::orderBy("id", "desc")
            ->when($request->type && $request->search, function ($q) use ($request) {
                if (($request->type == 'name' || $request->type == 'uid')) {
                    $user = User::where("user_id", "like", "%$request->search%")->select("name", "uid", "id")->first();
                    if ($user != null) {
                        $q->where("userId", $user->id);
                    }
                } else {
                    $q->where($request->type, "like", "%$request->search%");
                    // $q->where($request->type,$request->search);
                }
            })
            ->paginate(50);
        $records->map(function ($data) {
            $user = User::where("id", $data->userId)->first();
            $data->email = $user->name . "(" . $user->uid . ")";
            return $data;
        });

        $records->appends(["search" => $request->search, "date" => $request->date]);
        return view('admin.transactions', compact('records'));
    }


    //api's



    public function transactions(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $wallets = wallet::where("userId", $user->id)->orderBy("id", "desc")->paginate();
        return response()->json(compact('wallets'));
    }


    public function userDetails(Request $request)
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 400);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(['token_expired']);
        } catch (TokenInvalidException $e) {
            return response()->json(['token_invalid']);
        } catch (JWTException $e) {
            return response()->json(['token_absent']);
        }
        $balance = round($this->getBalance($user->id, "epin"), 4);
        $usdt = round($this->getBalance($user->id, "usdt"), 4);
        $interest = wallet::where("userId", $user->id)->where("transaction_type", "roi")->sum('amount');
        $interest = round($interest, 3);

        $total_direct_referral = wallet::where("userId", $user->id)->where("transaction_type", "direct_income")->sum('amount');
        $direct_referral = round($total_direct_referral, 4);

        $total_level_bonus = wallet::where("userId", $user->id)->where("transaction_type", "level_income")->sum('amount');
        $level_bonus = round($total_level_bonus, 4);


        $commission = round($direct_referral + $level_bonus, 4);


        $total_deposit = payment::where("user_id", $user->id)->where("type", "deposit")->where('payment_status', 1)->where('status', 'Confirmed')->sum('amount');
        $deposit = round($total_deposit, 4);

        $total_investment = wallet::where("userId", $user->id)->where("transaction_type", "invest")->sum('amount');
        $investment = round($total_investment, 4);

        // $user_address = crypto_wallet::where('user_id', $user->id)->orderBy('id', 'desc')->first();
        $total_withdraw = withdraw::where("userId", $user->id)->where('status', 'confirmed')->sum('amount');
        $withdraw = round($total_withdraw, 4);

        return response()->json(compact("withdraw", 'user', 'balance', 'interest', 'commission', 'usdt', 'direct_referral', 'level_bonus', 'deposit', 'investment'));
    }

    public function getLogo()
    {

        $logo = setting::first();
        return response()->json(compact('logo'));
    }

    public function userlogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($request->type == "post_login") {
            $validator = Validator::make($request->all(), [
                'email' => 'required',
                'password' => 'required',
            ]);
        }

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()], 500);
        }

        $user = User::where("email", $request->email)->orWhere("uid", $request->email)->first();
        if ($user == null) {
            return response()->json(['message' => 'Invalid User'], 500);
            exit;
        }
        $credentials = [
            "email" => $user->email,
            "password" => $request->password,
        ];
        if ($user->is_enabled == 0) {
            return response()->json(["message" => "Permission Denied!"], 500);
            exit;
        }

        if ($user->email == "admin@gmail.com") {
            return response()->json(['message' => 'Invalid User'], 500);
            exit;
        }
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['message' => 'Invalid Credentials'], 500);
            }
        } catch (JWTException $e) {
            return response()->json(['message' => 'could_not_create_token'], 500);
        }

        if ($request->type == "pre_login") {
            $otp = mt_rand(100000, 999999);

            $data = [
                "name" => $user->name,
                "otp" => $otp
            ];

            $user = User::where("id", $user->id)->update(["login_otp" => $otp]);

            Mail::to($request->email)->send(new loginOtp($data));
            return response()->json(["message" => "Email sent Succesfully"], 200);
        }

        if ($user->login_otp != $request->otp) {
            return response()->json(["message" => "Invalid Otp"], 500);
        }



        //   if($user->logged_in == 1){
        //         return response()->json(["message"=>"User already logged in another device"],500);
        //         exit;
        //   }

        //   User::where("email",$user->email)->where("showPass",$request->password)->update([
        //     "logged_in"=>1
        //   ]);

        return response()->json(compact('token'));

    }


    public function forgetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()], 500);
        }

        $otp = mt_rand(100000, 999999);

        User::where("email", $request->email)->update(["forget_otp" => $otp]);

        $data = [
            "otp" => $otp
        ];

        Mail::to($request->email)->send(new forgetOtp($data));
        return response()->json(["message" => "Email sent Succesfully"], 200);


    }
    public function verfiyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()], 500);
        }

        $user = User::where("email", $request->email)->first();

        if ($user->forget_otp != null && $user->forget_otp != $request->otp) {
            return response()->json(['message' => "Invalid verification code"], 500);
        }

        $data = [
            "name" => $user->name,
            "email" => $user->email,
            "password" => $user->showPass
        ];

        Mail::to($request->email)->send(new forgetPassword($data));
        return response()->json(["message" => "Login details sent on your email"], 200);
    }

    public function user_login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()], 500);
        }

        $user = User::where("id", $request->id)->first();
        if ($user == null) {
            return response()->json(['message' => 'Invalid User'], 500);
            exit;
        }
        $credentials = [
            "email" => $user->email,
            "password" => $user->showPass,
        ];


        if ($user->email == "admin@gmail.com") {
            return response()->json(['message' => 'Invalid User'], 500);
            exit;
        }
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['message' => 'Invalid Credentials'], 500);
            }
        } catch (JWTException $e) {
            return response()->json(['message' => 'could_not_create_token'], 500);
        }


        return response()->json(compact('token'));

    }



    public function test()
    {





        die;


        $curl = curl_init();

        // curl_setopt_array($curl, array(
//   CURLOPT_URL => 'https://dashboard.aurpay.net/api/plugin/key',
//   CURLOPT_RETURNTRANSFER => true,
//   CURLOPT_ENCODING => '',
//   CURLOPT_MAXREDIRS => 10,
//   CURLOPT_TIMEOUT => 0,
//   CURLOPT_FOLLOWLOCATION => true,
//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//   CURLOPT_CUSTOMREQUEST => 'GET',
// ));

        // $response = curl_exec($curl);

        // curl_close($curl);
// echo $response;

        // die;

        // $curl = curl_init();

        $headers = array();
        $headers[] = "API-key: eWlP7uxupYCeSrPPqBBhEhN3iLsrjE64bqXg-_xjSeY";
        $req = [
            'price' => 100,
            'currency' => 'USD',
            'succeed_url' => 'http://localhost:8000/succeed_url',
            'timeout_url' => 'http://localhost:8000/timeout_url',
            'callback_url' => 'http://localhost:8000/callback_url',
            'timeout_callback' => 'http://localhost:8000/timeout_callback'
        ];


        $url = 'https://dashboard.aurpay.net/api/order/pay-url';
        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($req));
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'API-key: eWlP7uxupYCeSrPPqBBhEhN3iLsrjE64bqXg-_xjSeY'
        ));
        // curl_setopt_array($curl, array(
        //   CURLOPT_URL => 'https://dashboard.aurpay.net/api/order/pay-url',
        //   CURLOPT_RETURNTRANSFER => true,
        //   CURLOPT_ENCODING => '',
        //   CURLOPT_MAXREDIRS => 10,
        //   CURLOPT_TIMEOUT => 0,
        //   CURLOPT_FOLLOWLOCATION => true,
        //   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //   CURLOPT_CUSTOMREQUEST => 'POST',
        //     CURLOPT_HTTPHEADER=> $headers,
        // //   CURLOPT_POSTFIELDS =>'{
        // //     "price": 100,
        // //     "currency": "USD",
        // //     "succeed_url": "http://localhost:8000/succeed_url",
        // //     "timeout_url": "http://localhost:8000/timeout_url",
        // //     "callback_url": "http://localhost:8000/callback_url",
        // //     "timeout_callback": "http://localhost:8000/timeout_callback"
        // // }',
        // ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
        die;

        //     $bitgo = new BitGoSDK('v2xaead99d0c152d89e3b6b2d3657df18d3c16e616f5f9e19ec00b8994436b64e95', CurrencyCode::BITCOIN, false);
        //     // $bitgo->walletId = '649d62610d24ff0007a1bae71d30a9e0';
        //    $data=  $bitgo->getWalletAddresses("649d62610d24ff0007a1bae71d30a9e0");
        //     dd($data);
        // $client = new \GuzzleHttp\Client();

        //     $response = $client->request('POST', 'https://api.shasta.trongrid.io/wallet/createaccount', [
        //     'body' => '{"owner_address":"TTc5xX6PbrrHnfHCx2b23GK1aQuVTFj94a","account_address":"TTxAEgWRdAcM55bkkt2C3HRm1aHwN1zGfb","visible":true}',
        //     'headers' => [
        //         'accept' => 'application/json',
        //         'content-type' => 'application/json',
        //     ],
        //     ]);

        //     echo $response->getBody();
        //     die;

        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $nonce = '';
        for ($i = 1; $i <= 32; $i++) {
            $pos = mt_rand(0, strlen($chars) - 1);
            $char = $chars[$pos];
            $nonce .= $char;
        }
        $ch = curl_init();
        $timestamp = round(microtime(true) * 1000);
        // Request body
        $request = array(
            "env" => array(
                "terminalType" => "WEB"
            ),
            "merchantTradeNo" => mt_rand(982538, 9825382937292),
            "orderAmount" => 25.17,
            "currency" => "USDT",
            "goods" => array(
                "goodsType" => "01",
                "goodsCategory" => "D000",
                "referenceGoodsId" => "7876763A3B",
                "goodsName" => "Deposit",
                "goodsDetail" => "Deposit in binance trade"
            )
        );

        // $request = array(
        //     "requestId" => mt_rand(982538,9825382937292),
        //     "currency"=>"USDT",
        //     "amount"=>0.0001,
        //     "transferType" => "TO_MAIN"
        // );



        $json_request = json_encode($request);
        $payload = $timestamp . "\n" . $nonce . "\n" . $json_request . "\n";
        $binance_pay_key = "vclpdgtqr3ksb7xzvee2yx8bbybah4dngh0fhvhjeus3kmoiaocplvm7suyedwzg";
        $binance_pay_secret = "aejf4ycjgwclk5tktwg0x6qmnm5267ugo5qwbz4mynvhd3bvwbkp19znqjpbo81c";
        $signature = strtoupper(hash_hmac('SHA512', $payload, $binance_pay_secret));
        $headers = array();
        $headers[] = "Content-Type: application/json";
        $headers[] = "BinancePay-Timestamp: $timestamp";
        $headers[] = "BinancePay-Nonce: $nonce";
        $headers[] = "BinancePay-Certificate-SN: $binance_pay_key";
        $headers[] = "BinancePay-Signature: $signature";

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, "https://bpay.binanceapi.com/binancepay/openapi/v2/order");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_request);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        // curl_close ($ch);

        // return redirect($result->qrContent);
        var_dump($result);

        // $user = User::where('id',2)->first();
        // $startDate = Carbon::parse($user->created_at);
        // $endDate = Carbon::parse(Carbon::now());

        // $diffInDays = $startDate->diffInDays($endDate);

        // dd($diffInDays);
        // $currentDateTime = Carbon::now();
        // $newDateTime = Carbon::now()->addMonth();

        // print_r($currentDateTime);
        // echo $newDateTime;

        // print_r(Carbon::parse($user->created_at)->day);
        // print_r(Carbon::parse()->format('h'));

        // $minTotal= DB::table('games')
        //     ->selectRaw('MIN(total) AS min_total')
        //     ->fromSub(function ($query) {
        //         $query->from('games')
        //               ->selectRaw('SUM(bet) as total')->where("game_id","202303300268")->groupBy('color');
        //     }, 'subquery')
        //     ->get();

        // $minTotal= $results = DB::table(DB::raw('(
        //     SELECT SUM(bet) AS total, color,type,game_id
        //     FROM games
        //     WHERE game_id = 202303300268
        //     GROUP BY color
        // ) as subquery'))
        // ->selectRaw('color,game_id,type,MIN(total) AS min_total')
        // ->get();

        // SELECT type,game_id,color, MIN(total) AS min_total
        // FROM (
        //   SELECT SUM(bet) AS total, color,type,game_id
        //   FROM games
        //   WHERE game_id = '202303300257'
        //   GROUP BY color
        // ) AS subquery

        // print_r($minTotal);
        // $games = result::join("game_ids","game_ids.game_id","results.game_id")->where("game_ids.expire",1)->orderBy("results.id","desc")->groupBy("results.game_id")->paginate(50);
        // print_r($games);
    }

    protected function getBalance($user_id, $wallet_type)
    {
        $credit = wallet::where("userId", $user_id)->where("wallet_type", $wallet_type)->where("type", "credit")->sum('amount');
        $debit = wallet::where("userId", $user_id)->where("wallet_type", $wallet_type)->where("type", "debit")->sum('amount');

        // $balance = round($credit - $debit,6);
        $balance = number_format((float) ($credit - $debit), 5, '.', '');

        return $balance;
    }

    public function getSponser(Request $request)
    {
        $user = User::where("uid", $request->spid)->first();
        if ($user == null) {
            return response()->json(["error" => "Invalid Sponser Id"], 500);
            exit;
        }
        return response()->json(["sp_name" => $user->name], 200);
    }

    public function payment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required',
            'amount' => 'required',
        ]);


        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()], 500);
        }

        if ($request->amount < 10) {
            return response()->json(["message" => "Minimum USDT Amount 10"], 500);
            exit;
        }

        $user = JWTAuth::parseToken()->authenticate();

        if (!$request->hasFile('image')) {
            return response()->json(["message" => "Please Choose Valid Image"], 500);
            exit;
        }

        $file = $request->image;
        $filename = uniqid() . '_' . $file->getClientOriginalName();
        $file->move(public_path("payments"), $filename);

        $usdt_price = usdt_price::orderBy("id", "desc")->pluck("price")[0];
        $amount = $request->amount / $usdt_price;

        $payment = new payment();
        $payment->user_id = $user->id;
        // $payment->ref_no = $request->refno;
        $payment->amount = $amount;
        $payment->type = "p2p";
        // $payment->upi_id = $request->upi;
        $payment->image = $filename;
        $payment->save();

        return response()->json(["message" => "Payment Request Sent"], 200);

    }

    public function getPayments(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $payments = payment::where("user_id", $user->id)->orderBy("id", "desc")->paginate();
        return response()->json(compact('payments'));

    }

    //user payments to admin

    public function accept_payment(Request $request)
    {

        if (Auth::user()->is_admin == 1) {
            $request->validate([
                'id' => 'required'
            ]);


            $payment = payment::findOrFail($request->id);
            $payment->status = "Confirmed";
            $payment->save();

            $user = User::where("id", $payment->user_id)->first();

            // $exist = wallet::where("from",$user->uid)->where("transaction_type","bonus_income")->first();


            // payment::where("id",$request->id)->update([
            //     "status"=>"Confirmed"
            // ]);

            $wallet = new wallet();
            $wallet->user_id = $user->uid;
            $wallet->userId = $user->id;
            $wallet->amount = $payment->amount;
            $wallet->from = "deposit";
            $wallet->transaction_type = "deposit";
            $wallet->wallet_type = "epin";
            $wallet->type = "credit";
            $wallet->description = "credit " . $request->amount . "from deposit";
            ;
            $wallet->save();


            $loop = false;
            $spnser = $user->spid;
            $spnser_uid = $user->uid;
            $levelloop = 1;
            $usid = '';

            //     if($spnser != 'admin' && $spnser != ''){
            //         $income = level_income::first();
            //         $levelcommision1 = $income->percentage;
            //         $levelcommision = $payment->amount / 100 * $levelcommision1;

            //         $type = 'direct_income';
            //         $desc = "Direct income (".$levelcommision1."%) received from $spnser_uid on recharge";
            //         $sponserget = User::where('uid',$spnser)->first();
            //         if ($levelcommision > 0) {
            //             $wallet = new wallet();
            //             $wallet->user_id = $sponserget->uid;
            //             $wallet->userId = $sponserget->id;
            //             $wallet->amount = $levelcommision;
            //             $wallet->from = $spnser_uid;
            //             $wallet->transaction_type = $type;
            //             $wallet->level = 1;
            //             $wallet->wallet_type = "usdt";
            //             $wallet->type="credit";
            //             $wallet->description = $desc;
            //             $wallet->save();
            //         }

            //   }

            //$direct_income = (8/100)*$package->amount;

            //   while ($loop == false) {

            //         $count = bonus_income::where("level",$levelloop)->count();
            //         $income = bonus_income::where("level",$levelloop)->first();


            //       if ($spnser === 'admin' || $spnser == '' || $count < 1 || $levelloop >3  ) {
            //           $loop = true;
            //           break;
            //           exit;
            //       }

            //       $levelcommision1 = $income->percentage;

            //       $levelcommision = $payment->amount / 100 * $levelcommision1;

            //       $type = 'bonus_income';
            //       $desc = "Recharge Bonus Level ". $levelloop." income ($levelcommision1%) received from $spnser_uid";


            //       $sponserget = User::where('uid',$spnser)->first();


            //       if ($levelcommision > 0) {
            //           $wallet = new wallet();
            //           $wallet->user_id = $sponserget->uid;
            //           $wallet->userId = $sponserget->id;
            //           $wallet->amount = $levelcommision;
            //           $wallet->from = $spnser_uid;
            //           $wallet->transaction_type = $type;
            //           $wallet->level = $levelloop;
            //           $wallet->wallet_type = "epin";
            //           $wallet->type="credit";
            //           $wallet->description = $desc;
            //           $wallet->save();
            //       }

            //       $spnser = $sponserget->spid;
            //       $levelloop++;
            //   }

        }

        // $version = Client::CLIENT_4X;
        // $url = 'ws://127.0.0.1:4000';

        // $client = new Client(Client::engine($version, $url));
        // $client->initialize();
        // $data = [
        //     'user_id'=>$wallet->user_id,
        //     "message"=>'payment accepted'
        // ];
        // $client->emit("payments", ['messages' =>$data ]);


        return redirect()->back()->with("success", "Request Accepted!");



    }
    public function reject_payment(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        payment::where("id", $request->id)->update([
            "status" => "Rejected",
            "remarks" => $request->remarks
        ]);
        // $data = [
        //     'user_id'=>$request->user_id,
        //     "message"=>'payment accepted'
        // ];
        $payment = payment::where("id", $request->id)->first();
        $data = [
            'user_id' => $payment->user_id,
            "message" => 'payment accepted'
        ];

        // $client->emit("payments_".$payment->user_id, ['messages' =>$data ]);

        return redirect()->back()->with("success", "Request Rejected!");
    }



    //admin functions


    public function admin_login()
    {
        return view('auth.login');
    }

    public function pending_payments(Request $request)
    {
        $payments = payment::join("users", 'users.id', 'payments.user_id')->where("payments.type", "p2p")->where("payments.status", "Pending")->when($request->search && $request->type, function ($q) use ($request) {
            $q->where("$request->type", "like", "%$request->search%");
        })->orderBy("payments.id", "desc")->paginate(50, ['users.*', 'payments.*', 'payments.id as payment_id']);
        $payments->appends(["search" => $request->search, "type" => $request->type]);

        // dd($payments);
        return view('admin.pending_payments', compact('payments'));
    }


    public function completed_payments(Request $request)
    {
        $payments = payment::join("users", 'users.id', 'payments.user_id')->where("payments.type", "p2p")->where("payments.status", "Confirmed")->when($request->search && $request->type, function ($q) use ($request) {
            $q->where("$request->type", "like", "%$request->search%");
        })->orderBy("payments.id", "desc")->paginate(50, ['users.*', 'payments.*', 'payments.id as payment_id']);
        $payments->appends(["search" => $request->search, "type" => $request->type]);

        return view('admin.completed_payments', compact('payments'));
    }
    public function rejected_payments(Request $request)
    {

        $payments = payment::join("users", 'users.id', 'payments.user_id')->where("payments.type", "p2p")->where("payments.status", "Rejected")->when($request->search && $request->type, function ($q) use ($request) {
            $q->where("$request->type", "like", "%$request->search%");
        })->orderBy("payments.id", "desc")->paginate(50, ['users.*', 'payments.*', 'payments.id as payment_id']);
        $payments->appends(["search" => $request->search, "type" => $request->type]);
        return view('admin.rejected_payments', compact('payments'));
    }



    public function team_list(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $teams = downline::where("tagsp", $user->uid)->orderBy("level")->paginate(50);
        $teams->map(function ($data) {
            $data->name = User::where("uid", $data->user_id)->pluck('name')[0];
            return $data;
        });

        return response()->json(compact('teams'));
    }





    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'password' => 'required',
            'confirm_password' => 'required',
            'old_password' => 'required',
        ]);
        $user = JWTAuth::parseToken()->authenticate();

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()], 500);
            exit;
        }

        if ($user->showPass != $request->old_password) {
            return response()->json(['message' => "Old Password is Wrong"], 500);
            exit;
        }
        if ($request->password != $request->confirm_password) {
            return response()->json(['message' => "Password not match"], 500);
            exit;
        }

        $user = User::where("id", $user->id)->update([
            "showPass" => $request->password,
            "password" => Hash::make($request->password)
        ]);
        return response()->json(["message" => "Profile Updated successfully"], 200);
    }




    public function userLogout(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        User::where("id", $user->id)->update(
            ["logged_in" => 0]
        );
        return response("user logout");
    }

    public function cash_payments(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $payments = wallet::where("userId", $user->id)->where("transaction_type", "roi")->paginate(50);
        return response()->json(compact('payments'));
    }




    public function direct_list(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $users = User::where("spid", $user->uid)->where("uid", "!=", "admin")->paginate();
        return response()->json(compact('users'));
    }

    public function withdraw(Request $request)
    {

        // $validator = Validator::make($request->all(), [
        //     'bank' => 'required',
        //     'user_name' => 'required',
        //     'ifsc_code' => 'required',
        //     'account_no' => 'required',
        //     'amount' => 'required',
        //     'phone' => 'required',
        // ]);

        $validator = Validator::make($request->all(), [
            'address' => 'required',
            'network' => 'required',
            'amount' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()], 500);
            exit;
        }

        $user = JWTAuth::parseToken()->authenticate();

        // if($request->amount < 500){
        //     return response()->json(["message"=>"Minimum withdrawal amount 500 "],500);
        //     exit;
        // }
        $balance = round($this->getBalance($user->id, "epin"), 4);

        if ($request->amount > $balance) {
            return response()->json(["message" => "Not enough amount for withdrawal "], 500);
            exit;
        }

        // $user =  User::findOrFail($user->id);
        // $user->bank_name = $request->bank;
        // $user->bank_user_name = $request->user_name;
        // $user->ifsc_code = $request->ifsc_code;
        // $user->phone = $request->phone;
        // $user->account_no = $request->account_no;
        // $user->save();

        $wallet = new wallet();
        $wallet->user_id = $user->uid;
        $wallet->userId = $user->id;
        $wallet->amount = $request->amount;
        $wallet->transaction_type = "withdraw";
        $wallet->wallet_type = "epin";
        $wallet->type = "debit";
        $wallet->description = "Withdraw request by name:- " . $user->name . " user_id:- " . $user->uid;
        $wallet->save();

        $with = new withdraw();
        $with->user_id = $user->uid;
        $with->userId = $user->id;
        $with->address = $request->address;
        $with->network = $request->network;
        $with->amount = floor($request->amount);
        $with->wallet_type = "epin";
        $with->status = "pending";
        $with->description = "Your Withdrawal is currently in process";
        $with->save();

        // return response()->json(["message"=>"Withdraw request sent to admin"],200);

        return response()->json(["message" => "Withdraw successfully"], 200);

    }


    public function withdraw_live(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'address' => 'required',
            'network' => 'required',
            'amount' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()], 500);
            exit;
        }

        $user = JWTAuth::parseToken()->authenticate();

        if($request->amount < 10){
            return response()->json(["message"=>"Minimum withdrawal amount $10 "],500);
            exit;
        }
        $balance = round($this->getBalance($user->id, "epin"), 4);

        if ($request->amount > $balance) {
            return response()->json(["message" => "Not enough amount for withdrawal "], 500);
            exit;
        }

        $url = env("token_url") . "send_transaction";
        $private_key = env('WALLET_PRIVATE');

        $amount = $request->amount - 0.5;


        $usdt_address = "0x55d398326f99059fF775485246999027B3197955";
        if($request->network == "eth"){
            $usdt_address = "0xdAC17F958D2ee523a2206206994597C13D831ec7";
        }
        else if($request->network == "tron"){
            // $usdt_address = "TXLAQ63Xg1NAzckPwKHvzw7CSEmLMEqcdj"; // testnet
            $usdt_address = "TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t";
            $private_key = env('WALLET_TRON_PRIVATE');
            $url = env("token_url") . "send-usdttrc";
        }

        // $data = [
        //     'amount' => (string)$request->amount,
        //     'toAddress' => $request->address,
        //     'walletPrivate' => $private_key,
        //     'contractAddress' => $usdt_address,
        //     'tokenNet' => 'testnet',
        // ];
        $data = [
            'amount' => (string) $request->amount,
            'toAddress' => $request->address,
            'walletPrivate' => $private_key,
            'contractAddress' => $usdt_address,
            'tokenNet' => 'livenet',
        ];

        $ch = curl_init($url);

        $postData = json_encode($data); // for JSON

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response instead of outputting it
        curl_setopt($ch, CURLOPT_POST, true);           // Use POST request
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData); // Attach the POST data

        // If sending JSON, set the content type to application/json
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($postData)
        ]);

        // Execute the request
        $response = curl_exec($ch);
        // echo "hereeeeee";

        // \Log::info("token curl response ".json_encode($response));


        // print_r($response);

        // Check for errors
        if (curl_errno($ch)) {
            echo 'cURL error: ' . curl_error($ch);
            \Log::info("error in token curl");
            return response()->json(["message" => "Something went wrong"], 500);
        } else if (isset(json_decode($response)->error)) {
            \Log::info(json_decode($response)->error);
            return response()->json(["message" => "Something went wrong"], 500);
        } else if (isset(json_decode($response)->name) && json_decode($response)->name == "ContractExecutionError") {
            \Log::info(json_encode($response));
            return response()->json(["message" => "Something went wrong"], 500);
        } else {
            $wallet = new wallet();
            $wallet->user_id = $user->uid;
            $wallet->userId = $user->id;
            $wallet->amount = $request->amount;
            $wallet->transaction_type = "withdraw";
            $wallet->wallet_type = "epin";
            $wallet->type = "debit";
            $wallet->description = "Withdraw request by name:- " . $user->name . " user_id:- " . $user->uid;
            $wallet->save();

            $with = new withdraw();
            $with->user_id = $user->uid;
            $with->userId = $user->id;
            $with->address = $request->address;
            $with->network = $request->network;
            $with->amount = $amount;
            $with->admin_fee = 0.5;
            $with->total = $request->amount;
            $with->wallet_type = "epin";
            $with->status = "confirmed";
            $with->description = "Your withdrawal has been successfully paid";
            $with->save();

            // return response()->json(["message"=>"Withdraw request sent to admin"],200);
            return response()->json(["message" => "Withdraw successfully"], 200);

        }



    }




    public function completed_withdraw(Request $request)
    {
        $with = withdraw::join("users", 'users.uid', 'withdraws.user_id')->where("withdraws.status", "confirmed")->when($request->search && $request->type, function ($q) use ($request) {
            $q->where("$request->type", "like", "%$request->search%");
        })->orderBy("withdraws.id", "desc")->paginate(50, ['users.*', 'withdraws.*', 'withdraws.id as withdraw_id']);
        $with->appends(["search" => $request->search, "type" => $request->type]);

        return view("admin.completed_withdraw", compact('with'));
    }

    public function pending_withdraw(Request $request)
    {
        $with = withdraw::join("users", 'users.uid', 'withdraws.user_id')->where("withdraws.status", "pending")->when($request->search && $request->type, function ($q) use ($request) {
            // $q->where("user_id", "like", "%$request->search%")->orWhere("name", "like", "%$request->search%");
        })->orderBy("withdraws.id", "desc")->paginate(50, ['users.*', 'withdraws.*', 'withdraws.id as withdraw_id']);
        $with->appends(["search" => $request->search, "type" => $request->type]);

        return view("admin.pending_withdraw", compact('with'));
    }

    public function rejected_withdraw(Request $request)
    {
        $with = withdraw::join("users", 'users.uid', 'withdraws.user_id')->when($request->search && $request->type, function ($q) use ($request) {
            // $q->where("user_id", "like", "%$request->search%")->orWhere("name", "like", "%$request->search%");
            $q->where("$request->type", "like", "%$request->search%");
        })->where("withdraws.status", "rejected")->orderBy("withdraws.id", "desc")->paginate(50, ['users.*', 'withdraws.*', 'withdraws.id as withdraw_id']);
        $with->appends(["search" => $request->search, "type" => $request->type]);

        return view("admin.rejected_withdraw", compact('with'));
    }

    public function acceptWd(Request $request)
    {
        $withdraw = withdraw::where('id', $request->id)->first();
        $withdraw->status = "confirmed";
        $withdraw->remarks = $request->review;
        $withdraw->description = "Your withdrawal has been successfully paid";
        $withdraw->confirm_date = Carbon::now();
        $withdraw->save();

        return redirect()->back()->with("success", "Withdraw Accepted");
    }

    public function rejectWd(Request $request)
    {

        $withdraw = withdraw::where('id', $request->id)->first();
        $withdraw->status = "rejected";
        $withdraw->remarks = $request->review;
        $withdraw->description = "Your withdrawal has been declined ";
        $withdraw->confirm_date = Carbon::now();
        $withdraw->save();

        wallet::create([
            "type" => "credit",
            "userId" => $withdraw->userId,
            "user_id" => $withdraw->user_id,
            "amount" => $withdraw->amount,
            "wallet_type" => "epin",
            "transaction_type" => "withdraw",
            "description" => 'Withdraw Request Rejected By Admin'
        ]);

        return redirect()->back()->with("success", "Withdraw Rejected");
    }

    public function withdraw_details(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $withdraw = withdraw::where("userId", $user->id)->orderBy("id","desc")->paginate(50);
        return response()->json(compact('withdraw'));
    }

    public function add_upi()
    {
        $upi = upi::orderBy("id", "desc")->first();
        return view('admin.add_upi', compact('upi'));
    }

    public function store_upi(Request $request)
    {
        $request->validate([
            "bar_code" => 'required',
            "upi_id" => 'required'
        ]);


        if (!$request->hasFile('bar_code')) {
            return redirect()->back()->with("error", "Please Choose Valid Bar Code");
            exit;
        }

        $file = $request->bar_code;
        $filename = uniqid() . '_' . $file->getClientOriginalName();
        $file->move(public_path("uploads/bar_code"), $filename);


        $upi = new upi();
        $exist = upi::orderBy("id", "desc")->first();
        if ($exist != null) {
            $upi = $exist;
        }

        $upi->bar_code = $filename;
        $upi->upi_id = $request->upi_id;
        $upi->save();

        return redirect()->back()->with("success", "Upi Data Store Succcessfully");

    }

    public function getUpi(Request $request)
    {
        $upi = upi::orderBy("id", "desc")->first();
        return response()->json(compact('upi'));
    }

    public function getTime()
    {
        $timer = timer::orderBy("id", "desc")->first();
        return response()->json(compact('timer'));
    }

    public function swap(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'amount' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()], 500);
            exit;
        }

        $user = JWTAuth::parseToken()->authenticate();
        $balance = round($this->getBalance($user->id, "usdt"), 4);

        if ($request->amount > $balance) {
            return response()->json(["message" => "Not enough Balance in Main Wallet "], 500);
            exit;
        }
        if ($request->amount < 10) {
            return response()->json(["message" => "Minimum Amount 10 Required "], 500);
            exit;
        }
        if ($request->password != $user->showPass) {
            return response()->json(["message" => "Please Choose A Valid Password "], 500);
            exit;
        }

        $wallet = new wallet();
        $wallet->user_id = $user->uid;
        $wallet->userId = $user->id;
        $wallet->amount = $request->amount;
        $wallet->transaction_type = "swap";
        $wallet->wallet_type = "usdt";
        $wallet->type = "debit";
        $wallet->description = "You swapped  " . floor($request->amount) . "  from main balance to game balance";
        $wallet->save();

        $wallet = new wallet();
        $wallet->user_id = $user->uid;
        $wallet->userId = $user->id;
        $wallet->amount = $request->amount;
        $wallet->transaction_type = "swap";
        $wallet->wallet_type = "epin";
        $wallet->type = "credit";
        $wallet->description = "You swapped " . floor($request->amount) . "  from main balance to game balance";
        $wallet->save();
        return response()->json(["message" => "Amount Swap From Main Balance to Game Balance"], 200);

    }

    public function coinBalance(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $balance = $this->getBalance($user->id, $request->coin);

        return response()->json($balance);

    }

    public function user_register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required',
            'confirm_password' => 'required',
            'spid' => 'required',

            'phone' => 'required'
        ]);



        if ($request->password != $request->confirm_password) {
            return response()->json(['message' => 'Password not match!'], 500);
            exit;
        }

        if ($request->spid == "admin") {
            return response()->json(['message' => 'Invalid Sponser!'], 500);
            exit;
        }

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()], 500);
            exit;
        }

        $data = $this->post_curl('create-account');


        $user = User::where("uid", $request->spid)->first();

        if ($user == null) {
            return response()->json(['message' => "Invaild User id"], 500);
            exit;
        }

        $uid = "NE" . mt_rand(100000, 999999);
        $whilee = true;
        while ($whilee == true) {
            $user = User::where("uid", $uid)->first();
            $uid = "NE" . mt_rand(100000, 999999);
            if ($user == null) {
                $uid = $uid;
                $whilee = false;
                break;
                exit;
            }
        }

        $user = User::where("uid", $request->uid)->first();
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->showPass = $request->password;
        $user->spid = strtoupper($request->spid);
        $user->phone = $request->phone;
        $user->uid = $uid;
        $user->save();


        $tagsp = $user->spid;
        $sppp = $user->spid;
        $user_id = $user->uid;
        $while = true;
        $lv = 1;

        while ($while == true) {
            $udata = User::where("uid", $tagsp)->where("is_admin", "!=", 1)->get();
            if (count($udata) < 1 || $lv == 12) {
                $while = false;
                break;
                exit;
            }
            downline::create([
                "tagsp" => $tagsp,
                "user_id" => $user_id,
                "spid" => $sppp,
                "level" => $lv

            ]);
            $userdata = $udata[0];
            $tagsp = $userdata['spid'];
            $lv++;
        }

        $data = $this->post_curl('create-account');
        \Log::info(json_encode($data));

        crypto_wallet::create([
            "address" => $data->data->address,
            "privateKey" => $data->data->privateKey,
            "secretPhrase" => $data->data->secretPhrase,
            "publicKey" => $data->data->publicKey,
            "tron_address" => $data->data->tron_address,
            "tron_private"=> $data->data->tron_privateKey,
            "tron_hex_address"=>$data->data->tron_addressHex,
            "user_id" => $user->id,
        ]);





        $token = JWTAuth::fromUser($user);

        // return response()->json(compact('user','token'), 201);
        return response()->json(["message" => "Registration Successfully, Please Login!", 'user' => $user], 200);

    }


    public function posts(Request $request)
    {
        $posts = post::when($request->search && $request->type, function ($q) use ($request) {
            $q->where($request->type, "like", "%" . $request->search . "%");
        })->orderBy("id", "desc")->paginate(50);
        $posts->appends(["search" => $request->search, "type" => $request->type]);
        return view('admin.posts.index', compact('posts'));
    }
    public function add_post()
    {
        return view('admin.posts.add');
    }
    public function store_post(Request $request)
    {
        $request->validate([
            'image' => 'required',
            'title' => 'required',
            'description' => 'required',
        ]);

        $post = new post();

        if (!$request->hasFile('image')) {
            return redirect()->back()->with("error", "Please Choose Valid Image");
            exit;
        }

        $file = $request->image;
        $filename = uniqid() . '_' . $file->getClientOriginalName();
        $file->move(public_path("posts"), $filename);

        $post->title = $request->title;
        $post->image = $filename;
        $post->description = $request->description;
        $post->save();

        return redirect()->back()->with('success', "Post Add Successfully");

    }
    public function edit_post($id)
    {
        $post = post::findOrFail($id);
        return view('admin.posts.edit', compact('post'));
    }
    public function update_post(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        $post = post::findOrFail($id);

        if ($request->hasFile('image')) {
            $file = $request->image;
            $filename = uniqid() . '_' . $file->getClientOriginalName();
            $file->move(public_path("posts"), $filename);

            $post->image = $filename;
        }

        $post->title = $request->title;
        $post->description = $request->description;
        $post->save();

        return redirect()->back()->with('success', "Post updated Successfully");
    }
    public function delete_post($id)
    {
        $post = post::findOrFail($id);
        $post->delete();
        return redirect()->back()->with('success', "Post Deleted Successfully");
    }

    public function getPosts()
    {
        $posts = post::orderBy("id", "desc")->get();
        return response()->json(compact('posts'));
    }

    public function deposit_history(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        // $history = wallet::where("userId",$user->id)->where("transaction_type","deposit")->orderBy("id","desc")->paginate(50);
        $history = payment::where("user_id", $user->id)->where("payment_status", 1)->orderBy("id", "desc")->paginate(50);
        return response()->json(compact('history'));
    }


    public function all_deposits(Request $request)
    {

        // $payments = payment::orderBy("id","desc")->paginate(50);
        $payments = payment::join("users", 'users.id', 'payments.user_id')->where('payments.payment_status', 1)->when($request->search && $request->type, function ($q) use ($request) {
            $q->where("$request->type", "like", "%$request->search%");
        })->orderBy("payments.id", "desc")->paginate(50, ['users.*', 'payments.*', 'payments.id as payment_id']);
        $payments->appends(["search" => $request->search, "type" => $request->type]);

        return view('admin.deposits', compact('payments'));
    }
    public function invest_history(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $history = wallet::where("userId", $user->id)->where("transaction_type", "invest")->orderBy("id", "desc")->paginate(50);
        return response()->json(compact('history'));
    }
    public function packages(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $package = package::all();
        $balance = $this->walletBalance($user->id, "usdt");

        return response()->json(compact('package', 'balance'));
    }

    public function coins(Request $request)
    {
        $coins = coin::when($request->search && $request->type, function ($q) use ($request) {
            $q->where($request->type, "like", "%" . $request->search . "%");
        })->orderBy("id", "desc")->paginate(50);
        $coins->appends(["search" => $request->search, "type" => $request->type]);
        return view('admin.coins.index', compact('coins'));
    }
    public function add_coin()
    {
        return view('admin.coins.add');
    }
    public function store_coin(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'type' => 'required',
        ]);

        $coin = new coin();

        $coin->name = strtoupper($request->name);
        $coin->address = $request->address;
        $coin->abi = $request->abi;
        $coin->type = $request->type;
        $coin->save();

        return redirect()->route('admin.coins')->with('success', "Coin Add Successfully");

    }
    public function edit_coin($id)
    {
        $coin = coin::findOrFail($id);
        return view('admin.coins.edit', compact('coin'));
    }
    public function update_coin(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'type' => 'required',
        ]);

        $coin = coin::findOrFail($id);
        $coin->name = strtoupper($request->name);
        $coin->address = $request->address;
        $coin->abi = $request->abi;
        $coin->type = $request->type;
        $coin->save();

        return redirect()->route('admin.coins')->with('success', "Coin updated Successfully");
    }
    public function delete_coin($id)
    {
        $coin = coin::findOrFail($id);
        $coin->delete();
        return redirect()->back()->with('success', "Coin Deleted Successfully");
    }
    public function delete_asset($id)
    {
        $coin = asset::findOrFail($id);
        $coin->delete();
        return redirect()->back()->with('success', "Asset Deleted Successfully");
    }

    public function assets(Request $request)
    {
        $assets = asset::when($request->search && $request->type, function ($q) use ($request) {
            $q->where($request->type, "like", "%" . $request->search . "%");
        })->when($request->exchange && $request->exchange != "0" , function($q) use($request){
            $q->where("type",$request->exchange);
        })->orderBy("id", "desc")->paginate(50);
        $assets->appends(["search" => $request->search, "type" => $request->type]);

        return view('admin.asset.index', compact('assets'));
    }
    public function add_asset()
    {
        return view('admin.asset.add');
    }
    public function store_asset(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $asset = new asset();

        $asset->name = strtoupper($request->name);
        $asset->type = $request->type;
        $asset->save();

        return redirect()->route('admin.assets')->with('success', "Assets Add Successfully");

    }
    public function edit_asset($id)
    {
        $asset = asset::findOrFail($id);
        return view('admin.asset.edit', compact('asset'));
    }
    public function update_asset(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $asset = asset::findOrFail($id);
        $asset->type = $request->type;
        $asset->name = strtoupper($request->name);
        $asset->save();

        return redirect()->route('admin.assets')->with('success', "Asset updated Successfully");
    }
    public function asset_coin($id)
    {
        $asset = asset::findOrFail($id);
        $asset->delete();
        return redirect()->back()->with('success', "Asset Deleted Successfully");
    }

    public function check_token(Request $request)
    {
        $data = $this->post_curl("check-token", $request->address);
        return response()->json($data);
    }

    public function getSymbols()
    {
        $symbols = asset::where("type","spot")->get();
        return response()->json($symbols);
    }

    public function npf_prices()
    {
        $prices = price::pluck('price');
        $date = price::pluck('created_at')->map(function ($date) {
            return Carbon::parse($date)->format('m/d h:i');
        });
        $data = price::get()->map(function ($data) {
            $data->created_at = Carbon::parse($data->created_at)->format('m/d h:i');
            return $data;
        });
        $price = price::orderBy("id", "desc")->first();

        return response()->json(compact('prices', 'date', 'price', 'data'));
    }

    public function usdt_price()
    {
        $price = usdt_price::orderBy("id", "desc")->pluck("price")[0];
        return response()->json(compact('price'));
    }

    public function post_curl($type, $data = [])
    {
        \Log::info(env('token_url') . $type);
        \Log::info("data" . json_encode($data));
        $url = env('token_url') . $type;


        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POST, 1);


        $response = curl_exec($curl);

        return json_decode($response);

    }

    public function test_mail()
    {
        $data = [
            "name" => "ravi",
            "otp" => "1111",
        ];

        Mail::to("raviwebcyst@gmail.com")->send(new loginOtp($data));
    }




    public function invest(Request $request)
    {

        $request->validate([
            'package' => 'required',
        ]);

        // $user = Auth::user();
        $user = JWTAuth::parseToken()->authenticate();


        $pack = package::where('id', $request->package)->first();

        // $balance = round($this->getBalance($user->id, "usdt"), 4);
        $balance = $this->walletBalance($user->id, "usdt");

        if ($pack->amount > $balance) {
            // return redirect()->back()->with('error', "Insufficient Balance");
            return response()->json(['message' => 'Insufficient Balance'], 500);

            exit;
        }
        //  if ($request->amount % 100 != 0) {
        //     return redirect()->back()->with('error', "amount  should be multiple of $100");
        //     exit;
        // }
        //  if ($request->amount < 200) {
        //     return redirect()->back()->with('error', "Minimum investment amount is $200");
        //     exit;
        // }

        $package = new pack_active();
        $package->userId = $user->id;
        $package->amount = $pack->amount;
        $package->from = $user->name;
        $package->package = $request->package;
        $package->save();

        $user = User::where("id", $user->id)->first();
        $user->package_amount = $pack->amount;
        $user->total_business = $user->total_business + $pack->amount;
        $user->enable = 1;
        $user->package = $pack->amount;
        $user->save();

        DB::update("UPDATE `downlines` SET `business`=`business`+'" . $pack->amount . "' WHERE `user_id`='" . $user->uid . "'");



        $wallet = new wallet();
        $wallet->user_id = $user->uid;
        $wallet->userId = $user->id;
        $wallet->amount = $pack->amount;
        $wallet->transaction_type = "invest";
        $wallet->wallet_type = "usdt";
        $wallet->type = "debit";
        $wallet->description = "debit " . $pack->amount . " from investment";
        $wallet->save();


        $loop = false;
        $spnser = $user->spid;
        $spnser_uid = $user->uid;
        $package_id = $request->package;
        $levelloop = 1;
        $usid = '';

        while ($loop == false) {

            $income = level_income::where("pack_id", $package_id)->where("level", $levelloop)->first();

            if ($spnser === 'admin' || $spnser == '' || $levelloop > 10) {
                $loop = true;
                break;
                exit;
            }
            // $levelcommision1 = $income->percentage;

            // $levelcommision = $amount / 100 * $levelcommision1;
            $levelcommision1 = $income->percentage;




            $type = 'level_income';
            $desc = "Level $levelloop  income ($levelcommision1) received from $spnser_uid";

            if ($levelloop == 1) {

                $type = 'direct_income';
                $desc = "Direct $levelloop  income ($levelcommision1) received from $spnser_uid";
            }

            $sponserget = User::where('uid', $spnser)->first();

            $directs = User::where("spid", $sponserget->uid)->count();

            if ($sponserget->enable == 0) {
                $spnser = $sponserget->spid;
                $levelloop++;
                continue;
                exit;
            }

            if ($levelcommision1 > 0) {
                $wallet = new wallet();
                $wallet->user_id = $sponserget->uid;
                $wallet->userId = $sponserget->id;
                $wallet->amount = $levelcommision1;
                $wallet->from = $spnser_uid;
                $wallet->transaction_type = $type;
                $wallet->level = $levelloop;
                $wallet->wallet_type = "usd";
                $wallet->type = "credit";
                $wallet->description = $desc;
                $wallet->save();
            }

            $spnser = $sponserget->spid;
            $levelloop++;


        }
        return response()->json(['message' => 'Package Activate Successfully'], 200);



    }

    public function pending_deposits(Request $request)
    {



        $payments = payment::join("users", 'users.id', 'payments.user_id')->where("payments.type", "deposit")->where("payments.status", "Pending")->when($request->search && $request->type, function ($q) use ($request) {
            $q->where("$request->type", "like", "%$request->search%");
        })->orderBy("payments.id", "desc")->paginate(50, ['users.*', 'payments.*', 'payments.id as payment_id']);
        $payments->appends(["search" => $request->search, "type" => $request->type]);

        return view('admin.pending_deposits', compact('payments'));
    }

    public function accept_deposit(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        $payment = payment::findOrFail($request->id);
        $payment->status = "Confirmed";
        $payment->save();

        $user = User::where("id", $payment->user_id)->first();
        $wallet = new wallet();
        $wallet->user_id = $user->uid;
        $wallet->userId = $user->id;
        $wallet->amount = $payment->amount;
        $wallet->from = "deposit";
        $wallet->transaction_type = "deposit";
        $wallet->wallet_type = "usdt";
        $wallet->type = "credit";
        $wallet->description = "credit " . $request->amount . " from deposit";
        $wallet->save();

        $user->enable = 1;
        $user->paid_date = Carbon::now();
        $user->save();

        return redirect()->back()->with("success", "Request Accepted!");

    }
    public function reject_deposit(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);
        payment::where("id", $request->id)->update([
            "status" => "Rejected",
            "remarks" => $request->remarks
        ]);

        return redirect()->back()->with("success", "Request Rejected!");
    }
    public function completed_deposits(Request $request)
    {


        $payments = payment::join("users", 'users.id', 'payments.user_id')->where("payments.type", "deposit")->where("payments.status", "Confirmed")->when($request->search && $request->type, function ($q) use ($request) {
            $q->where("$request->type", "like", "%$request->search%");
        })->orderBy("payments.id", "desc")->paginate(50, ['users.*', 'payments.*', 'payments.id as payment_id']);
        $payments->appends(["search" => $request->search, "type" => $request->type]);




        return view('admin.completed_deposits', compact('payments'));
    }
    public function rejected_deposits(Request $request)
    {

        $payments = payment::join("users", 'users.id', 'payments.user_id')->where("payments.type", "deposit")->where("payments.status", "Rejected")->when($request->search && $request->type, function ($q) use ($request) {
            $q->where("$request->type", "like", "%$request->search%");
        })->orderBy("payments.id", "desc")->paginate(50, ['users.*', 'payments.*', 'payments.id as payment_id']);
        $payments->appends(["search" => $request->search, "type" => $request->type]);


        return view('admin.rejected_deposits', compact('payments'));
    }



    public function post_deposit(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'hash' => 'required',
            'amount' => 'required',
        ]);


        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()], 500);
        }

        //   if($request->amount < 10){
        //     return response()->json(["message"=>"Minimum USDT Amount 10"],500);
        //     exit;
        //   }
        $check_hash = payment::where('hash', $request->hash)->count();
        if ($check_hash > 0) {
            return response()->json(["message" => "Duplicate Hash"], 500);
        }
        $user = JWTAuth::parseToken()->authenticate();
        $user_address = crypto_wallet::where('user_id', $user->id)->orderBy('id', 'desc')->first();

        if (!$user_address) {
            return response()->json(["message" => "Invalid user address"], 500);
        }
        $address = $user_address->address;

        $payment = new payment();
        $payment->user_id = Auth::user()->id;
        $payment->amount = $request->amount;
        $payment->address = $address;
        $payment->hash = $request->hash;
        $payment->save();

        // return response()->json(["message"=>"Deposit request sent to admin"],200);
        return response()->json(["message" => "Deposit successfully"], 200);
    }


    public function direct_referral()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $history = wallet::where("userId", $user->id)->where("transaction_type", "direct_income")->orderBy("id", "desc")->paginate(50);
        return response()->json(compact('history'));
    }
    public function level_bonus()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $history = wallet::where("userId", $user->id)->where("transaction_type", "level_income")->orderBy("id", "desc")->paginate(50);
        return response()->json(compact('history'));
    }
    protected function walletBalance($user_id, $wallet_type)
    {
        $credit = wallet::where("userId", $user_id)->where("wallet_type", $wallet_type)->where("type", "credit")->sum('amount');
        $debit = wallet::where("userId", $user_id)->where("wallet_type", $wallet_type)->where("type", "debit")->sum('amount');

        // $balance = round($credit - $debit,2);
        $balance = number_format((float) ($credit - $debit), 6, '.', '');
        return $balance;
    }


    public function website_setting()
    {

        $data = setting::first();


        return view('admin.website_setting', compact('data'));
    }


    public function store_website_setting(Request $request)
    {
        // $setting = new Setting();
        // $exist = Setting::orderBy("id", "desc")->first();

        // if ($exist != null) {
        //     $setting = $exist;
        // }
        // if ($request->hasFile('logo')) {
        //     $file = $request->logo;
        //     $filename = uniqid() . '_' . $file->getClientOriginalName();
        //     $file->move(public_path("uploads/logo"), $filename);
        //     $setting->logo = $filename;
        // }

        // $setting->title = $request->title;
        // $setting->width = $request->width;


        // $setting->save();

        // return redirect()->back()->with("success", "Data Store Succcessfully");


        $setting = new setting();

        $exist = setting::orderBy('id', 'desc')->first();

        if ($exist != null) {
            $setting = $exist;
        }

        if ($request->hasFile('logo')) {
            $file = $request->logo;
            $filename = uniqid() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/logo'), $filename);
            $setting->logo = $filename;
        }

        $setting->title = $request->title;
        $setting->save();

        return redirect()->back()->with('success', 'Data Store Successfully');
    }


    public function thankyou(Request $request)
    {
        $user = User::where('id', $request->id)->first();
        return response()->json(compact('user'));
    }

    public function getLiveDeposits(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'token' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()], 500);
            exit;
        }

        $user = JWTAuth::parseToken()->authenticate();

        $address = crypto_wallet::where("user_id", $user->id)->pluck("address");

        $transactions = payment::where("chain","bsc")->where("user_id", $user->id)->where("transaction_status", 1)->where("hash", "!=", null)->count();
        if (count($address) == 0) {
            return response()->json(['message' => "No address found for this user,contact admin"], 500);
            exit;
        }
        $address = $address[0];


        $curl = curl_init();

        // for testnet
        // $link  ="https://api-testnet.bscscan.com/";
        // $contract = "0x337610d27c682E347C9cD60BD4b3b107C9d34dDd";


        //  for live net
        $link = "https://api.bscscan.com/";
        $contract = "0x55d398326f99059fF775485246999027B3197955";


        \Log::info($link . "api?module=account&action=tokentx&contractaddress=$contract&address=$address&startblock=0&endblock=99999999&sort=desc&apikey=9EH6RPFIXJIY1WG66AJG1ZZ9P7IT22QTR1");

        curl_setopt_array($curl, array(
            CURLOPT_URL => $link . "api?module=account&action=tokentx&contractaddress=$contract&address=$address&startblock=0&endblock=99999999&sort=desc&apikey=9EH6RPFIXJIY1WG66AJG1ZZ9P7IT22QTR1",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        $response = curl_exec($curl);
        $data = json_decode($response);

        // $result_total = count($data->result);
        // $offset = ($result_total - $transactions)-1;
        $this->set_transaction($address, $user->id);

        return response()->json(compact('data'));
    }


    protected function set_transaction($address, $user_id)
    {

        $curl = curl_init();

        //for testnet
        // $link  ="https://api-testnet.bscscan.com/";
        // $contract = "0x337610d27c682E347C9cD60BD4b3b107C9d34dDd";

        $link = "https://api.bscscan.com/";
        $contract = "0x55d398326f99059fF775485246999027B3197955";

        curl_setopt_array($curl, array(
            CURLOPT_URL => $link . "api?module=account&action=tokentx&contractaddress=$contract&address=$address&startblock=0&endblock=99999999&sort=desc&apikey=9EH6RPFIXJIY1WG66AJG1ZZ9P7IT22QTR1",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        $response = curl_exec($curl);
        $data = json_decode($response);
        $result = $data->result;


        if (isset($result) && count($result) > 0) {
            for ($i = 0; $i < count($result); $i++) {

                $amount = $result[$i]->value / 1000000000000000000;
                $hash = $result[$i]->hash;
                $to_address = $result[$i]->to;
                $hash_exist = payment::where("hash", $hash)->count();

                // greater amount , match hash , only credit entries
                if ($amount <= 0 || $hash_exist > 0 || strtolower($address) != strtolower($to_address)) {
                    continue;
                }



                \Log::info("haserrerererere");

                \Log::info("result" . json_encode($result[$i]));
                \Log::info("br");

                $payment = new payment();
                $payment->user_id = $user_id;
                $payment->amount = $amount;
                $payment->address = $address;
                $payment->hash = $hash;
                $payment->payment_status = 1;
                $payment->transaction_status = 1;
                $payment->remarks = "Payment confirmed";
                $payment->status = "Confirmed";
                $payment->save();

                $user = User::where("id", $payment->user_id)->first();
                $wallet = new wallet();
                $wallet->user_id = $user->uid;
                $wallet->userId = $user->id;
                $wallet->amount = $payment->amount;
                $wallet->from = "deposit";
                $wallet->transaction_type = "deposit";
                $wallet->wallet_type = "epin";
                $wallet->type = "credit";
                $wallet->description = "credit " . $payment->amount . " from deposit";
                $wallet->save();

                $user->enable = 1;
                $user->paid_date = Carbon::now();
                $user->save();
            }
        }

    }

    public function getEthLiveDeposits(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'token' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()], 500);
            exit;
        }

        $user = JWTAuth::parseToken()->authenticate();

        $address = crypto_wallet::where("user_id", $user->id)->pluck("address");

        $transactions = payment::where("chain","eth")->where("user_id", $user->id)->where("transaction_status", 1)->where("hash", "!=", null)->count();
        if (count($address) == 0) {
            return response()->json(['message' => "No address found for this user,contact admin"], 500);
            exit;
        }
        $address = $address[0];


        $curl = curl_init();

        // for testnet
        // $link  ="https://api-sepolia.etherscan.io/";
        // $contract = "0xaA8E23Fb1079EA71e0a56F48a2aA51851D8433D0";


        //  for live net
        $link = "https://api.etherscan.io/";
        $contract = "0xdAC17F958D2ee523a2206206994597C13D831ec7";


        \Log::info($link . "api?module=account&action=tokentx&contractaddress=$contract&address=$address&startblock=0&endblock=99999999&page=1&offset=10&sort=asc&apikey=4UXWZDWACAF63MDC1CXXYXWKWSM5VWD9FT");

        curl_setopt_array($curl, array(
            CURLOPT_URL => $link . "api?module=account&action=tokentx&contractaddress=$contract&address=$address&startblock=0&endblock=99999999&page=1&offset=10&sort=asc&apikey=4UXWZDWACAF63MDC1CXXYXWKWSM5VWD9FT",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        $response = curl_exec($curl);
        $data = json_decode($response);

        // $result_total = count($data->result);
        // $offset = ($result_total - $transactions)-1;
        $this->set_eth_transaction($address, $user->id);

        return response()->json(compact('data'));
    }


    protected function set_eth_transaction($address, $user_id)
    {

        $curl = curl_init();

         // for testnet
        //  $link  ="https://api-sepolia.etherscan.io/";
        //  $contract = "0xaA8E23Fb1079EA71e0a56F48a2aA51851D8433D0";


         //  for live net
         $link = "https://api.etherscan.io/";
         $contract = "0xdAC17F958D2ee523a2206206994597C13D831ec7";

        curl_setopt_array($curl, array(
            CURLOPT_URL => $link . "api?module=account&action=tokentx&contractaddress=$contract&address=$address&startblock=0&endblock=99999999&page=1&offset=10&sort=asc&apikey=4UXWZDWACAF63MDC1CXXYXWKWSM5VWD9FT",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        $response = curl_exec($curl);
        $data = json_decode($response);
        $result = $data->result;


        if (isset($result) && count($result) > 0) {
            for ($i = 0; $i < count($result); $i++) {

                $amount = $result[$i]->value / 1000000;
                $hash = $result[$i]->hash;
                $to_address = $result[$i]->to;
                $hash_exist = payment::where("hash", $hash)->count();

                // greater amount , match hash , only credit entries
                if ($amount <= 0 || $hash_exist > 0 || strtolower($address) != strtolower($to_address)) {
                    continue;
                }


                \Log::info("haserrerererere");

                \Log::info("result" . json_encode($result[$i]));
                \Log::info("br");

                $payment = new payment();
                $payment->user_id = $user_id;
                $payment->amount = $amount;
                $payment->address = $address;
                $payment->hash = $hash;
                $payment->payment_status = 1;
                $payment->transaction_status = 1;
                $payment->chain = "eth";
                $payment->remarks = "Payment confirmed";
                $payment->status = "Confirmed";
                $payment->save();

                $user = User::where("id", $payment->user_id)->first();
                $wallet = new wallet();
                $wallet->user_id = $user->uid;
                $wallet->userId = $user->id;
                $wallet->amount = $payment->amount;
                $wallet->from = "deposit";
                $wallet->transaction_type = "deposit";
                $wallet->wallet_type = "epin";
                $wallet->type = "credit";
                $wallet->chain = "eth";
                $wallet->description = "credit " . $payment->amount . " from deposit";
                $wallet->save();

                $user->enable = 1;
                $user->paid_date = Carbon::now();
                $user->save();
            }
        }

    }



    public function setTronTransaction(Request $request){


        $validator = Validator::make($request->all(), [
            'token' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()], 500);
            exit;
        }

        $user = JWTAuth::parseToken()->authenticate();

        $address = crypto_wallet::where("user_id", $user->id)->pluck("tron_address");

        if (count($address) == 0) {
            return response()->json(['message' => "No address found for this user,contact admin"], 500);
            exit;
        }
        $address = $address[0];


        $curl = curl_init();

        // for testnet
        // $link  ="https://nile.trongrid.io/v1/accounts/";
        // $contract = "TXLAQ63Xg1NAzckPwKHvzw7CSEmLMEqcdj";



        // //  for live net
        $link = "https://api.trongrid.io/";
        $contract = "TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t";


        \Log::info($link . "$address/transactions/trc20?contract_address=$contract");

        curl_setopt_array($curl, array(
            CURLOPT_URL => $link . "$address/transactions/trc20?contract_address=$contract",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        $response = curl_exec($curl);
        $data = json_decode($response);

        if(isset($data) && isset($data->data))

        $result = $data->data;


        if (isset($result) && count($result) > 0) {
            for ($i = 0; $i < count($result); $i++) {

                $amount = $result[$i]->value / 1000000;
                $hash = $result[$i]->transaction_id;
                $to_address = $result[$i]->to;
                $hash_exist = payment::where("hash", $hash)->count();

                // greater amount , match hash , only credit entries
                if ($amount <= 0 || $hash_exist > 0 || strtolower($address) != strtolower($to_address)) {
                    continue;
                }



                \Log::info("haserrerererere tron");

                \Log::info("result tron" . json_encode($result[$i]));
                \Log::info("br");

                $payment = new payment();
                $payment->user_id = $user->id;
                $payment->amount = $amount;
                $payment->address = $address;
                $payment->hash = $hash;
                $payment->payment_status = 1;
                $payment->transaction_status = 1;
                $payment->chain = "tron";
                $payment->remarks = "Payment confirmed";
                $payment->status = "Confirmed";
                $payment->save();

                $user = User::where("id", $payment->user_id)->first();
                $wallet = new wallet();
                $wallet->user_id = $user->uid;
                $wallet->userId = $user->id;
                $wallet->amount = $payment->amount;
                $wallet->from = "deposit";
                $wallet->transaction_type = "deposit";
                $wallet->wallet_type = "epin";
                $wallet->type = "credit";
                $wallet->chain = "tron";
                $wallet->description = "credit " . $payment->amount . " from deposit";
                $wallet->save();

                $user->enable = 1;
                $user->paid_date = Carbon::now();
                $user->save();
            }
        }


        return response()->json("success");

    }




    // public function set_transactions(Request $request){
    //     $validator = Validator::make($request->all(), [
    //         'token' => 'required',
    //     ]);

    //     if($validator->fails()){
    //         return response()->json(['message'=>$validator->messages()],500);
    //         exit;
    //     }

    //     $user = JWTAuth::parseToken()->authenticate();

    //     $address = crypto_wallet::where("user_id",$user->id)->pluck("address");

    //     $transactions = payment::where("user_id",$user->id)->where("transaction_status",1)->count();
    //     if(count($address) == 0){
    //         return response()->json(['message'=>"No address found for this user,contact admin"],500);
    //         exit;
    //     }
    //     $address = $address[0];


    //     $curl = curl_init();

    //     curl_setopt_array($curl, array(
    //       CURLOPT_URL => "https://api-testnet.bscscan.com/api?module=account&action=tokentx&contractaddress=0x337610d27c682E347C9cD60BD4b3b107C9d34dDd&address=$address&startblock=0&endblock=99999999&sort=desc&apikey=9EH6RPFIXJIY1WG66AJG1ZZ9P7IT22QTR1",
    //       CURLOPT_RETURNTRANSFER => true,
    //       CURLOPT_ENCODING => '',
    //       CURLOPT_MAXREDIRS => 10,
    //       CURLOPT_TIMEOUT => 0,
    //       CURLOPT_FOLLOWLOCATION => true,
    //       CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //       CURLOPT_CUSTOMREQUEST => 'GET',
    //     ));
    //     $response = curl_exec($curl);
    //     $data = json_decode($response);
    // }

    // public function all_trades(Request $request)
    // {
    //     $trades = trade::join("users", 'users.id', 'trades.user_id')->when($request->search && $request->type, function ($q) use ($request) {
    //         $q->where("$request->type", "like", "%$request->search%"); })->orderBy("trades.id", "desc")->paginate(50,['users.*', 'trades.*', 'trades.id as trader_id']);
    //         $trades->appends(["search"=>$request->search, "type"=>$request->type]);

    //     return view('admin.trades', compact('trades'));

    // }
    public function spot_trading_history(Request $request) {
        $spot_history = trade::where('trade_type', 'spot')->join('users', 'users.id', 'trades.user_id')->select("users.uid","users.name","trades.*")
        ->when($request->search && $request->type, function($q) use($request){
            $q->where("$request->type", "like", "%". $request->search . "%");
        })
        ->when($request->date && $request->date != null, function($q) use($request) {
            $q->whereDate('trades.created_at',  $request->date);
        })
        ->when(isset($request->status), function($q) use($request) {
            $q->where("trades.status",$request->status);
        })
        ->orderBy('trades.id', 'desc')->paginate(50);
        $spot_history->appends(['search' => $request->search, "type" => $request->type]);


        //  if(isset($request->date)) {
        //     $spot_history = trade::where("trade_type", "spot")
        //     ->join('users', 'users.id', '=', 'trades.user_id')->whereDate('trades.created_at',  $request->date)->orderBy('trades.id', 'desc')->paginate(50);
        //     $spot_history->appends(['date' => $request->date,'status' => $request->status]);
        // }
        // if(isset($request->status) && $request->status != null) {
        //     $spot_history = trade::where('trade_type', 'spot')->join('users', 'users.id', 'trades.user_id')->where('trades.trade_type', 'spot')->where("trades.status", $request->status)->orderBy('trades.id', 'desc')->paginate(50);
        //     $spot_history->appends( ['status' => $request->status]);
        // }
        return view('admin.spot_history', compact('spot_history'));
    }
    public function future_trade_history(Request $request) {

        $future_history =  trade::where('trade_type', 'future')->join('users', 'users.id', 'trades.user_id')->select("users.uid","users.name","trades.*")
        ->when($request->search && $request->type && $request->search != null, function($q) use($request) {
            $q->where("$request->type", "like", "%" . $request->search . "%" );
        })
        ->when($request->date && $request->date != null, function($q) use($request) {
            $q->whereDate('trades.created_at',  $request->date);
        })
        ->when(isset($request->status), function($q) use($request) {
            $q->where("trades.status",$request->status);
        })
        ->orderBy('trades.id', 'desc')->paginate(50);

        $future_history->appends(['search' => $request->search, 'type' => $request->type,'date' => $request->date,'status' => $request->status]);

        return view('admin.future_history', compact('future_history'));
    }



    public function transfer_history(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $history = wallet::where("userId",$user->id)->where("transaction_type","transfer")->orderBy("id","desc")->paginate(50);
        // $history = payment::where("user_id", $user->id)->where("payment_status", 1)->orderBy("id", "desc")->paginate(50);
        return response()->json(compact('history'));
    }

    public function get_address(Request $request){
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'type' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()], 500);
            exit;
        }

        $user = JWTAuth::parseToken()->authenticate();

        $address = crypto_wallet::where("user_id",$user->id)->pluck($request->type)[0];
        return response()->json(compact('address'));
    }
}
