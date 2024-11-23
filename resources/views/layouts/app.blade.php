@php
$data = App\setting::first();

@endphp

<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>   {{  isset($data->title) ? $data->title : ' Admin Dashboard' }}</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('admin/plugins/fontawesome-free/css/all.min.css')}}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bbootstrap 4 -->
  <link rel="stylesheet" href="{{asset('admin/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{asset('admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
  <!-- JQVMap -->
  <link rel="stylesheet" href="{{asset('admin/plugins/jqvmap/jqvmap.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('admin/dist/css/adminlte.min.css')}}">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{asset('admin/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{asset('admin/plugins/daterangepicker/daterangepicker.css')}}">
  <!-- summernote -->
  <link rel="stylesheet" href="{{asset('admin/plugins/summernote/summernote-bs4.css')}}">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  {{-- <script>
	function initFreshChat() {
	  window.fcWidget.init({
		token: "641b359e-c9de-4f9f-81c3-e928e5764f58",
		host: "https://wchat.in.freshchat.com"
	  });
	}
	function initialize(i,t){var e;i.getElementById(t)?initFreshChat():((e=i.createElement("script")).id=t,e.async=!0,e.src="https://wchat.in.freshchat.com/js/widget.js",e.onload=initFreshChat,i.head.appendChild(e))}function initiateCall(){initialize(document,"Freshdesk Messaging-js-sdk")}window.addEventListener?window.addEventListener("load",initiateCall,!1):window.attachEvent("load",initiateCall,!1);
  </script> --}}
</head>
<body class="hold-transition sidebar-mini layout-fixed " id="side">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light fixed-top mb-5">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <ul class="navbar-nav ml-auto">
      <li class="nav-item ">
        <a class="nav-link" href="{{ route('admin.logout') }}"
        onclick="event.preventDefault();
                      document.getElementById('logout-form').submit();">
          Logout
        </a>
        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
      <img src="{{asset('admin/dist/img/AdminLTELogo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">Admin Panel</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-2 mb-2 d-flex">
        <div class="image mt-2">
          <img src="{{ isset($data->logo) ? asset('uploads/logo/' . $data->logo) : asset('logo.png') }}" class="img-circle elevation-2 mt-lg-0 " alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block my-auto">{{Auth::user()->name}}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item ">
            <a href="{{route('admin.home')}}" class="nav-link {{ request()->is('admin/home') ? 'active' : '' }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link {{ request()->is('admin/users') || request()->is('admin/activeUsers') ? 'active' : ''}}">
              <i class="nav-icon fas fa-users"></i>
              <p>
                  Users
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" style="display: none;">
              <li class="nav-item">
                <a href="{{route('admin.users')}}" class="nav-link {{ request()->is('admin/users') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>All Users</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('admin.activeUsers')}}" class="nav-link {{ request()->is('admin/activeUsers') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Active Users</p>
                </a>
              </li>
            </ul>
          </li>
            {{--  <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-wallet"></i>
              <p>
                  Transactions
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" style="display: none;">

            <li class="nav-item">
                <a href="{{route('admin.level_incomes')}}" class="nav-link {{ request()->is('admin/level_incomes') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Level Incomes</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('admin.direct_incomes')}}" class="nav-link {{ request()->is('admin/direct_incomes') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Direct Incomes</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="{{route('admin.investments')}}" class="nav-link {{ request()->is('admin/investments') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Investments
                  </p>
                </a>
              </li>


              <li class="nav-item">
                <a href="{{route('admin.transactions')}}" class="nav-link {{ request()->is('admin/transactions') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Transactions
                  </p>
                </a>
              </li>
            </ul>
          </li> --}}
            <li class="nav-item has-treeview">
            <a href="#" class="nav-link {{ request()->is('admin/rejected_deposits') || request()->is('admin/pending_deposits') ||  request()->is('admin/completed_deposits')  ? 'active' : '' }}">
              {{-- <i class="nav-icon fas fa-wallet"></i> --}}
              <i class="fas fa-dollar-sign nav-icon"></i>
              <p> Deposit Payments <i class="fas fa-angle-left right"></i> </p>
            </a>
            <ul class="nav nav-treeview" style="display: none;">
              <li class="nav-item">
                <a href="{{route('admin.pending_deposits')}}" class="nav-link {{ request()->is('admin/pending_deposits') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Pending Payments</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('admin.completed_deposits')}}" class="nav-link  {{ request()->is('admin/completed_deposits') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Completed Payments</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('admin.rejected_deposits')}}" class="nav-link  {{ request()->is('admin/rejected_deposits') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Rejected Payments</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item ">
            <a href="{{route('admin.transactions')}}" class="nav-link {{ request()->is('admin/transactions') ? 'active' : '' }}">
              <i class="nav-icon fas fa-wallet"></i>
              <p>
                Transactions
              </p>
            </a>
          </li>
          {{-- <li class="nav-item">
            <a href="{{ route('admin.deposit_history') }}" class="nav-link {{ request()->is('admin/deposit_history') ? 'active' : '' }}">
                <i class="fab fa-btc nav-icon"></i>

                <p>Deposit History</p>
            </a>
          </li> --}}
          <li class="nav-item ">
            <a href="{{route('admin.trades')}}" class="nav-link {{ request()->is('admin/all_trades') ? 'active' : '' }}">
              <i class="nav-icon fas fa-chart-line"></i>
              <p>
                Trades
              </p>
            </a>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link {{ request()->is('admin/spot_trading_history') || request()->is('admin/future_trading_history') ? 'active' : '' }}">
              {{-- <i class="nav-icon fas fa-wallet"></i> --}}
              <i class="fas fa-exchange-alt nav-icon"></i>
              <p>
                 Trade History
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" style="display: none;">
                <li class="nav-item">
                    <a href="{{route('admin.spot_trading_history', ['status' => 0])}}" class="nav-link {{ request()->is('admin/spot_trading_history') ? 'active' : '' }}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Spot Trading</p>
                    </a>
                  </li>
              <li class="nav-item">
                <a href="{{route('admin.future_trading_history', ['status' => 0])}}" class="nav-link {{ request()->is('admin/future_trading_history') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                  <p>Future Trading</p>
                </a>
              </li>


            </ul>
          </li>




          <li class="nav-item ">
            <a href="{{route('admin.coins')}}" class="nav-link {{ request()->is('admin/coins') ? 'active' : '' }}">
              <i class="nav-icon fas fa-coins"></i>
              <p>
                Coins
              </p>
            </a>
          </li>
          <li class="nav-item ">
            <a href="{{route('admin.assets')}}" class="nav-link {{ request()->is('admin/assets') ? 'active' : '' }}">
              {{-- <i class="nav-icon fas fa-coins"></i> --}}
              <i class="fas fa-th-list nav-icon"></i>
              <p>
                Assets
              </p>
            </a>
          </li>
          <li class="nav-item ">
            <a href="{{route('admin.posts')}}" class="nav-link {{ request()->is('admin/posts') ? 'active' : '' }}">
              <i class="nav-icon fas fa-newspaper"></i>
              <p>
                Posts
              </p>
            </a>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link {{ request()->is('admin/pending_payments') || request()->is('admin/completed_payments') || request()->is('admin/rejected_payments') ? 'active' : '' }}">
              {{-- <i class="nav-icon fas fa-wallet"></i> --}}
              <i class="fas fa-exchange-alt nav-icon"></i>
              <p>
                 P2P Deposits
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" style="display: none;">
              <li class="nav-item">
                <a href="{{route('admin.pending_payments')}}" class="nav-link {{ request()->is('admin/pending_payments') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Pending Payments</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('admin.completed_payments')}}" class="nav-link {{ request()->is('admin/completed_payments') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Completed Payments</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('admin.rejected_payments')}}" class="nav-link {{ request()->is('admin/rejected_payments') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Rejected Payments</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link {{ request()->is('admin/pending_withdraw') || request()->is('admin/completed_withdraw') || request()->is('admin/rejected_withdraw') ? "active" : '' }}">
              <i class="nav-icon fas fa-wallet"></i>
              <p>
                  Withdraw
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" style="display: none;">
              <li class="nav-item">
                <a href="{{route('admin.pending_withdraw')}}" class="nav-link {{ request()->is('admin/pending_withdraw') ?'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Pending Withdraw</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('admin.completed_withdraw')}}" class="nav-link {{ request()->is('admin/completed_withdraw') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Completed Withdraw</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('admin.rejected_withdraw')}}" class="nav-link {{ request()->is('admin/rejected_withdraw') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Rejected Withdraw</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link {{ request()->is('admin/price') || request()->is('admin/website_setting') || request()->is('admin/usdt_price') || request()->is('admin/add_upi') ? 'active' : '' }}">
              <i class="nav-icon fas fa-cog"></i>
              <p>
                  Settings
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" style="display: none;">
              <li class="nav-item ">
                <a href="{{route('admin.price')}}" class="nav-link {{ request()->is('admin/price') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Price Setting(NPF)</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('admin.usdt_price')}}" class="nav-link {{ request()->is('admin/usdt_price') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Price Setting(USDT)</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('admin.add_upi')}}" class="nav-link {{ request()->is('admin/add_upi') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Address Setting</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="{{route('admin.website_setting')}}" class="nav-link {{ request()->is('admin/website_setting') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Website Setting</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item ">
            <a href="{{route('admin.logout')}}" class="nav-link {{ request()->is('admin/logout') ? 'active' : '' }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              <i class="fas fa-sign-out-alt nav-icon"></i>
              <p> Logout </p>
            </a>
            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
        <main class="py-4 mt-5">
            @yield('content')
        </main>
    </div>
    <!-- jQuery -->
<script src="{{asset('admin/plugins/jquery/jquery.min.js')}}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{asset('admin/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{asset('admin/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- ChartJS -->
<script src="{{asset('admin/plugins/chart.js/Chart.min.js')}}"></script>
<!-- Sparkline -->
<script src="{{asset('admin/plugins/sparklines/sparkline.js')}}"></script>
<!-- JQVMap -->
<script src="{{asset('admin/plugins/jqvmap/jquery.vmap.min.js')}}"></script>
<script src="{{asset('admin/plugins/jqvmap/maps/jquery.vmap.usa.js')}}"></script>
<!-- jQuery Knob Chart -->
<script src="{{asset('admin/plugins/jquery-knob/jquery.knob.min.js')}}"></script>
<!-- daterangepicker -->
<script src="{{asset('admin/plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('admin/plugins/daterangepicker/daterangepicker.js')}}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{asset('admin/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
<!-- Summernote -->
<script src="{{asset('admin/plugins/summernote/summernote-bs4.min.js')}}"></script>
<!-- overlayScrollbars -->
<script src="{{asset('admin/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('admin/dist/js/adminlte.js')}}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{asset('admin/dist/js/pages/dashboard.js')}}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{asset('admin/dist/js/demo.js')}}"></script>

<script>



//watch window resize
$(window).on('resize', function() {
  if ($(this).width() > 950) {
    $('#side').addClass('sidebar-open').removeClass('sidebar-collapse');
  }
  else{
    $('#side').addClass('sidebar-collapse').removeClass('sidebar-open');
  }
});

$(window).on('load', function() {
  if ($(this).width() > 950) {
    $('#side').addClass('sidebar-open').removeClass('sidebar-collapse');
  }
  else{
    $('#side').addClass('sidebar-collapse').removeClass('sidebar-open');
  }
});
</script>
</body>
</html>
