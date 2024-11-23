@extends('layouts.admin')

@section('content')
<div class="content-wrapper">
    <section class="content">
<div class="container">
    {{-- <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Welcome To Admin Dashboard</div>

            </div>
        </div>
    </div> --}}
    <div class="row  pt-5">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">Total Users</div>
                <div class="card-body">
                    <h5>{{$users}}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">Active Users</div>
                <div class="card-body">
                    <h5>{{$active_users}}</h5>
                </div>
            </div>
        </div>





        {{-- <div class="col-md-3">
            <div class="card">
                <div class="card-header">Total Withdraw</div>
                <div class="card-body">
                    <h5>${{$total_withdraw}}</h5>
                </div>
            </div>
        </div> --}}

       {{-- <div class="col-md-3">
            <div class="card">
                <div class="card-header">Total Interest</div>
                <div class="card-body">
                    <h5>${{$total_interest}}</h5>
                </div>
            </div>
        </div> --}}
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">Total Traders</div>
                <div class="card-body">
                    <h5>{{$total_traders}}</h5>
                </div>
            </div>
        </div>
       <div class="col-md-3">
            <div class="card">
                <div class="card-header">Total Coins</div>
                <div class="card-body">
                    <h5>{{$total_coins}}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">Total Assets</div>
                <div class="card-body">
                    <h5>{{$total_assets}}</h5>
                </div>
            </div>
        </div>
       <div class="col-md-3">
            <div class="card">
                <div class="card-header">Total Post</div>
                <div class="card-body">
                    <h5>{{$total_posts}}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">Today Complete Deposit</div>
                <div class="card-body">
                    <h5>${{$today_completed_payments}}</h5>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-header"> Pending Deposits</div>
                <div class="card-body">
                    <h5>{{$total_pending_payments}}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-header"> Complete Deposits</div>
                <div class="card-body">
                    <h5>{{$total_completed_payments}}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-header"> Reject Deposits</div>
                <div class="card-body">
                    <h5>{{$total_rejected_payments}}</h5>
                </div>
            </div>
        </div>



        <div class="col-md-3">
            <div class="card">
                <div class="card-header">Total Pending Deposits</div>
                <div class="card-body">
                    <h5>${{$total_pending_payments}}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">Total Complete Payments</div>
                <div class="card-body">
                    <h5>${{$completed_payments}}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">Total Reject Payments</div>
                <div class="card-body">
                    <h5>${{$rejected_payments}}</h5>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-header">Pending P2P Deposits</div>
                <div class="card-body">
                    <h5>${{$pending_deposit}}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">Complete P2P Deposits</div>
                <div class="card-body">
                    <h5>${{$completed_deposit}}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">Reject P2P Deposits</div>
                <div class="card-body">
                    <h5>${{$rejected_deposit}}</h5>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-header">Pending Withdraw</div>
                <div class="card-body">
                    <h5>${{$pending_withdraw}}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">Complete Withdraw</div>
                <div class="card-body">
                    <h5>${{$completed_withdraw}}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">Reject Withdraw</div>
                <div class="card-body">
                    <h5>${{$rejected_withdraw}}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">Total Spot Open Orders</div>
                <div class="card-body">
                    <h5>{{$total_spot_open_orders}}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">Total Spot Close Orders</div>
                <div class="card-body">
                    <h5>{{$total_spot_close_orders}}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">Total Future Open Orders</div>
                <div class="card-body">
                    <h5>{{$total_future_open_orders}}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">Total Future close Orders</div>
                <div class="card-body">
                    <h5>{{$total_future_close_orders}}</h5>
                </div>
            </div>
        </div>


     {{--
         <div class="col-md-3">
            <div class="card">
                <div class="card-header">Total Direct Referral  </div>
                <div class="card-body">
                    <h5>${{$total_direct_referral}}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">Total Level Bonus  </div>
                <div class="card-body">
                    <h5>${{$total_level_bonus}}</h5>
                </div>
            </div>
        </div> --}}


    </div>
</div>
    </section>
</div>
@endsection
