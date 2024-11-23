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
                    <h3 class="card-title ">Completed Deposits</h3>
                    <div class="card-tools d-flex mt-1">
                        <form class="form-inline ml-3 my-auto" action="" method="get" id="invest_type">
                            <div class="input-group input-group-sm">
                                <select name="type" id="" class="form-control form-select" required>
                                    <option value="" disabled selected>Search Type</option>


                                    <option value="uid" {{ Request()->type == 'uid' ? 'selected' : '' }}>  User Id</option>
                                    <option value="name" {{ Request()->type == 'name' ? 'selected' : '' }}> Name</option>
                                    <option value="amount" {{ Request()->type == 'amount' ? 'selected' : '' }}>Amount </option>
                                 </select>
                            </div>


                            <div class="input-group input-group-sm  mt-3 mt-sm-0   ml-lg-3">
                                <input class="form-control form-control-navbar" type="search" placeholder="Search"
                                    name="search" value="{{ Request()->search }}" required>
                                <div class="input-group-append">
                                    <button class="btn btn-success" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <a class="btn btn-danger ml-3" href="{{ route('admin.completed_payments') }}">Reset
                                    </a>
                                </div>
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
                            <th>Sr. No.</th>
                            <th>User Name</th>
                            <th>Amount (USDT)</th>
                            <th>Amount</th>
                            <th>Transaction Hash</th>
                            {{-- <th>Slip</th> --}}
                            <!--<th>Loan Type</th>-->
                            {{-- <th>Slip</th> --}}
                            <th>Request Date</th>
                            <th>Action Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($payments) > 0)
                        @foreach ($payments as $payment)
                        <tr>
                            <td>{{$loop->index + 1}}</td>
                            <td>{{$payment->name}}({{$payment->uid}})</td>
                            <td>${{ $payment->amount }}</td>
                            <td>{{$payment->hash}}</td>
                            <td>{{$payment->address}}</td>

                            <!--<td>{{$payment->loan_type}}</td>-->
                            {{-- <td><a href="{{asset('payments/'.$payment->image)}}"> <img src="{{asset('payments/'.$payment->image)}}" width="100" height="100"></a></td> --}}
                            <td>{{$payment->created_at}}</td>
                            <td>{{$payment->updated_at}}</td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="7" class="text-center py-3 h5"><b>No Payments Found</b></td>
                        </tr>
                        @endif
                    </tbody>
                  </table>
                </div>

                </div>
              </div>
        </div>
    </section>
</div>

@endsection
