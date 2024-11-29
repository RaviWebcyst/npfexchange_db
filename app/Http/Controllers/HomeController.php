<?php

namespace App\Http\Controllers;

use App\coin;
use App\downline;
use App\pack_active;
use App\payment;
use App\post;
use App\price;
use App\usdt_price;
use Illuminate\Http\Request;
use App\User;
use App\wallet;
use App\result;
use App\trade;
use App\withdraw;
use App\asset;
use Carbon\Carbon;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $wallet_balance = round($this->getBalance(Auth::user()->id, "usdt"), 4);
        $income_balance = round($this->getBalance(Auth::user()->id, "usd"), 4);
        $investment = pack_active::where('userId',Auth::user()->id)->sum('amount');
        $team = downline::where("tagsp",Auth::user()->uid)->count();
        $directs = User::where("spid",Auth::user()->uid)->count();
         $investments = pack_active::where('userId',Auth::user()->id )->orderBy('id', 'desc')->limit(10)->get();
        $referral_earn = wallet::where("userId", Auth::user()->uid)->where("transaction_type", "level_income")->sum('amount');

        return view('home', compact('wallet_balance', 'income_balance','investment','team', 'investments', 'referral_earn', 'directs'));
    }

    public function adminHome()
    {
        $users = User::where('is_admin', 0)->count();
        $active_users = User::where('enable', 1)->where('is_admin', 0)->count();
         $total_withdraw = withdraw::where("status","confirmed")->sum('amount');
        $total_interest = wallet::where("transaction_type","roi")->sum('amount');
        $games = result::count();
        $today_games = result::whereDate("created_at",Carbon::today())->count();


        $completed_deposit = payment::where("status","Confirmed")->where('type', 'p2p')->sum('amount');
        $pending_deposit = payment::where("status","Pending")->where('type', 'p2p')->sum('amount');
        $rejected_deposit = payment::where("status","Rejected")->where('type', 'p2p')->sum('amount');

        $completed_withdraw = withdraw::where("status","confirmed")->sum('amount');
        $pending_withdraw = withdraw::where("status","pending")->sum('amount');
        $rejected_withdraw = withdraw::where("status","rejected")->sum('amount');

        $total_direct_referral = wallet::where("transaction_type","direct_income")->sum('amount');
        $total_level_bonus = wallet::where("transaction_type","level_income")->sum('amount');
        $total_posts = post::count();
        $total_traders = trade::count();
        $total_coins = coin::count();
        $total_assets =  asset::count();

        // $total_deposit = payment::where('type', 'deposit')->where('payment_status', 1)->sum('amount');

        $completed_payments = payment::where("status","Confirmed")->where('type', 'deposit')->where('payment_status', 1)->sum('amount');
        $pending_payments = payment::where("status","Pending")->where('payment_status', 1)->where('type', 'deposit')->sum('amount');
        $rejected_payments = payment::where("status","Rejected")->where('type', 'deposit')->sum('amount');


        $total_completed_payments = payment::where("status","Confirmed")->where('type', 'deposit')->where('payment_status', 1)->count();
        $total_pending_payments = payment::where("status","Pending")->where('payment_status', 1)->where('type', 'deposit')->count();
        $total_rejected_payments = payment::where("status","Rejected")->where('type', 'deposit')->count();

        $today_completed_payments = payment::where("status","Confirmed")->where('type', 'deposit')->where('payment_status', 1)->whereDate("created_at",Carbon::today())->count();

        $total_spot_open_orders = trade::where('trade_type', 'spot')->where('status', 0)->count();
        $total_spot_close_orders = trade::where('trade_type', 'spot')->where('status', 1)->count();


        $total_future_open_orders = trade::where('trade_type', 'future')->where('status', 0)->count();
        $total_future_close_orders = trade::where('trade_type', 'future')->where('status', 1)->count();



        return view('admin.home',compact("total_future_close_orders","total_future_open_orders","total_spot_close_orders","total_spot_open_orders",'today_completed_payments','total_rejected_payments','total_completed_payments', 'total_pending_payments','rejected_payments','pending_payments','completed_payments','total_assets','total_coins',"total_traders","completed_withdraw","rejected_withdraw","pending_withdraw",'active_users',"total_withdraw","total_posts",'users','total_interest','games','today_games', 'completed_deposit', 'pending_deposit', 'rejected_deposit', 'total_direct_referral', 'total_level_bonus'));
    }

    public function price(){
        $coin = price::latest()->first();
        return view('admin.add_price',compact('coin'));
    }
    public function store_price(Request $request){
        $price = new price();
        $price->price = $request->price;
        $price->open = $request->open;
        $price->high = $request->high;
        $price->low = $request->close;
        $price->close = $request->close;
        $price->save();
        return redirect()->back()->with('success',"Price update successfully");
    }

    public function usdt_price(){
        $coin = usdt_price::latest()->first();
        return view('admin.usdt_price',compact('coin'));
    }
    public function store_usdtPrice(Request $request){
        $price = new usdt_price();
        $price->price = $request->price;
        $price->save();
        return redirect()->back()->with('success',"Price update successfully");
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('admin.login');
    }
    protected function getBalance($user_id, $wallet_type)
    {
        $credit = wallet::where("userId", $user_id)->where("wallet_type", $wallet_type)->where("type", "credit")->sum('amount');
        $debit = wallet::where("userId", $user_id)->where("wallet_type", $wallet_type)->where("type", "debit")->sum('amount');
        if($debit == null){
            $debit = 0;
        }

        $balance = round($credit - $debit, 2);
        return $balance;
    }
    public function level_income(Request $request)
    {
        $records = wallet::where("transaction_type", "level_income")
        ->when($request->search, function($q) use($request) {
            $q->where("user_id", "like","%$request->search%");
        })
        ->orderBy("id", "desc")->paginate(50);
        $records->map(function ($data) {
            $user = User::where("id", $data->userId)->first();
            $data->email = $user->name."(".$user->uid .")";
            return $data;
        });
        $total = wallet::where("transaction_type", "level_income")
        ->when($request->search, function($q) use($request) {
            $q->where("user_id", "like","%$request->search%");
        })
        ->sum("amount");

        $records->appends(["search"=>$request->search]);
        if ($request->search) {
            $records = wallet::where("transaction_type", "level_income")->where(function ($q) use ($request) {
                $q->where("user_id", "like", "%" . $request->search . "%");
            })->paginate();
            $records->appends(["search" => $request->search]);

            $records = wallet::where("transaction_type", "level_income")
        ->when($request->search, function($q) use($request) {
            $q->where("user_id", "like","%$request->search%");
        })
        ->orderBy("id", "desc")->paginate(50);
        $records->map(function ($data) {
            $user = User::where("id", $data->userId)->first();
            $data->email = $user->name."(".$user->uid .")";
            return $data;
        });
        $total = wallet::where("transaction_type", "level_income")
        ->when($request->search, function($q) use($request) {
            $q->where("user_id", "like","%$request->search%");
        })
        ->sum("amount");

        $records->appends(["search"=>$request->search]);
        }

        return view('admin.level_incomes', compact('records', 'total'));
    }
    public function direct_incomes(Request $request)
    {
        $records = wallet::where("transaction_type", "direct_income")
        ->when($request->search, function($q) use($request) {
            $q->where("user_id", "like","%$request->search%");
        })
        ->orderBy("id", "desc")->paginate(50);
        $records->map(function ($data) {
            $user = User::where("id", $data->userId)->first();
            $data->email = $user->name."(".$user->uid .")";
            return $data;
        });
        $total = wallet::where("transaction_type", "direct_income")
        ->when($request->search, function($q) use($request) {
            $q->where("user_id", "like","%$request->search%");
        })
        ->sum("amount");

        $records->appends(["search"=>$request->search]);
        if ($request->search) {
            $records = wallet::where("transaction_type", "direct_income")->where(function ($q) use ($request) {
                $q->where("user_id", "like", "%" . $request->search . "%");
            })->paginate();
            $records->appends(["search" => $request->search]);

            $records = wallet::where("transaction_type", "direct_income")
        ->when($request->search, function($q) use($request) {
            $q->where("user_id", "like","%$request->search%");
        })
        ->orderBy("id", "desc")->paginate(50);
        $records->map(function ($data) {
            $user = User::where("id", $data->userId)->first();
            $data->email = $user->name."(".$user->uid .")";
            return $data;
        });
        $total = wallet::where("transaction_type", "direct_income")
        ->when($request->search, function($q) use($request) {
            $q->where("user_id", "like","%$request->search%");
        })
        ->sum("amount");

        $records->appends(["search"=>$request->search]);
        }

        return view('admin.direct_incomes', compact('records', 'total'));
    }


    public function investments(Request $request)
    {

        $records = pack_active::when($request->invest_type ,function($q) use($request){
            if($request->invest_type != 2){
                $q->where("status",$request->status);
            }
        })->when($request->search ,function($q) use($request){
            $user = User::where("uid","like","%$request->search%")->first();
            if($user != null){
                $q->where("user_id",$user->id);
            }
        })->orderBy("id","desc")->paginate(50);
         $records->map(function ($data) {
            $user = User::where("id", $data->userId)->first();
            $data->email = $user->name."(".$user->uid .")" ;
            $data->uid = $user->uid;
            return $data;
        });
        $total = pack_active::when($request->search ,function($q) use($request){
            $user = User::where("uid","like","%$request->search%")->first();
            if($user != null){
                $q->where("user_id",$user->id);
            }
        })->sum('amount');

        $records->appends(["search"=>$request->search]);


        return view('admin.investments', compact('records', 'total'));
    }
}
