@extends('layouts.admin')

@section('content')
<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            @if (session('success'))
                <div class="alert alert-success">{{session('success')}}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{session('error')}}</div>
            @endif
            <div class="card">
                <div class="card-header">
                  <h3 class="card-title ">Future Trading History</h3>
                  <div class="card-tools d-sm-flex mt-1 d-block">
                    <form class="form-inline ml-0 ml-sm-3 mt-4" action="" method="get" id="select_type">
                        <div class="input-group input-group-sm">
                            <select name="status"  class="form-control form-select"  onchange="$('#select_type').submit();">
                                <option value="" disabled selected>Select Order Type</option>
                                <option value="" {{ Request()->status == null ? 'selected':'' }}>All Orders </option>
                                <option value="0" {{ Request()->status == '0' ? 'selected':'' }}>Open Orders </option>
                                <option value="1" {{ Request()->status == '1' ? 'selected':'' }}>Close Orders </option>
                            </select>
                        </div>
                        <div class="input-group input-group-sm  mt-3 mt-sm-0   ml-lg-3">
                            <input class="form-control form-control-navbar" type="date" placeholder="Search" name="date" value="{{ Request()->date }}" onchange="$('#select_type').submit();">
                        </div>
                      {{-- </form> --}}
                    {{-- <form class="form-inline  ml-0 ml-sm-3 my-auto" action="" method="get" id="date">
                        <div class="input-group input-group-sm  mt-3 mt-sm-0   ml-lg-3">
                            <input class="form-control form-control-navbar" type="date" placeholder="Search" name="date" value="{{ Request()->date }}" onchange="$('#date').submit();">
                        </div>
                    </form> --}}

                    {{-- <form class="form-inline  ml-0 ml-sm-3 my-auto" action="" method="get" >    --}}
                        <div class="input-group input-group-sm ml-0 ml-sm-3 mt-3 mt-sm-0 ">
                            <select name="type" id="" class="form-control form-select" >
                                <option value="" disabled selected>Search Type</option>
                                <option value="name" {{ Request()->type == 'name' ? 'selected' : '' }}> Name</option>
                                <option value="uid" {{ Request()->type == 'uid' ? 'selected' : '' }}>  User Id</option>
                                <option value="symbol" {{ Request()->type == 'symbol' ? 'selected' : '' }}>Symbol </option>
                                {{-- <option value="type" {{ Request()->type == 'type' ? 'selected' : '' }}>Type </option>
                                <option value="price" {{ Request()->type == 'price' ? 'selected' : '' }}> Open Price </option>
                                <option value="amount" {{ Request()->type == 'amount' ? 'selected' : '' }}>	Amount(USDT) </option>
                                <option value="close_price" {{ Request()->type == 'close_price' ? 'selected' : '' }}>Close Price </option>
                                <option value="leverage" {{ Request()->type == 'leverage' ? 'selected' : '' }}>	Leverage </option> --}}
                             </select>
                        </div>

                        <div class="mt-3 mt-sm-2 ml-lg-3 ">
                            <input class="form-control form-control-sm" type="search" placeholder="Search" name="search" value="{{ Request()->search }}" >
                        </div>
                        <div class="mt-3 mt-sm-2 ml-3 ml-sm-3 ">
                            <button class="btn btn-success btn-sm" type="submit"> Search </button>
                            <a class="btn btn-danger ml-3 btn-sm" href="{{ route('admin.future_trading_history') }}">Reset </a>
                        </div>
                    </form>
                </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                  <div class="table-responsive">
                  <table class="table ">
                    <thead>
                      <tr>
                        <th >#</th>
                        <th>User</th>
                        <th>Symbol</th>
                        <th>Type</th>
                        <th>Open Price</th>
                        <th>Amount(USDT)</th>
                        <th>Close Price</th>
                        <th>Leverage</th>
                        <th style="min-width:120px;">Date</th>
                      </tr>
                    </thead>
                    <tbody>

                        @if(count($future_history) > 0)
                        @foreach ($future_history as $key=>$trade)
                        <tr>
                            <td>{{$future_history->firstItem()+$key}}</td>

                            <td>{{$trade->name}}({{$trade->uid}})</td>

                            <td>{{$trade->symbol}}</td>
                            <td>{{$trade->type}}</td>
                            <td>{{$trade->price}}</td>
                            <td>${{$trade->amount}}</td>
                            <td>{{$trade->close_price}}</td>
                            <td>{{$trade->leverage}}</td>
                            {{-- <td>{{$user->enable == 1 ? "Yes":"No"}}</td> --}}
                            <td>{{$trade->created_at}}</td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="9" class="text-center py-3 h5"><b>Data Not Found</b></td>
                        </tr>
                        @endif
                    </tbody>
                  </table>
                  {{$future_history->links()}}
                  </div>
                </div>
              </div>
        </div>
    </section>
</div>
@endsection
