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
                  <h3 class="card-title ">Pending Payments</h3>
                  <div class="card-tools d-flex mt-1">
                    <form class="form-inline ml-3" action="" method="get">
                        <div class="input-group input-group-sm">
                            <select name="type" id="" class="form-control form-select" required>
                                <option value="" disabled selected>Search Type</option>

                                <option value="name" {{ Request()->type == 'name' ? 'selected' : '' }}>Name </option>
                                <option value="uid" {{ Request()->type == 'uid' ? 'selected' : '' }}>  User Id</option>
                                <option value="address" {{ Request()->type == 'address' ? 'selected' : '' }}>Address </option>
                                <option value="hash" {{ Request()->type == 'hash' ? 'selected' : '' }}>	Transaction Hash </option>
                             </select>
                        </div>


                        <div class="input-group input-group-sm  mt-3 mt-sm-0   ml-lg-3">
                            <input class="form-control form-control-navbar" type="search" placeholder="Search"
                                name="search" value="{{ Request()->search }}" required>
                            <div class="input-group-append">
                                <button class="btn btn-success" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                                <a class="btn btn-danger ml-3" href="{{ route('admin.pending_deposits') }}">Reset
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
                             {{-- <th>Account No</th>
                             <th>Bank Name</th>
                             <th>IFSC Code</th> --}}
                             <th>Amount</th>
                             <th>Transaction Hash</th>
                             <th>Address</th>
                             <th>Remarks</th>
                            {{-- <th>Slip</th> --}}
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($payments) > 0)
                        @foreach ($payments as $payment)
                        <tr>
                            <td>{{$loop->index + 1}}</td>
                            <td> {{$payment->name ? $payment->name : ''}}({{$payment->uid ? $payment->uid : ''}})</td>
                            {{-- <td>{{$bank_detail->account_no}}</td>
                            <td>{{$bank_detail->bank_name}}</td>
                            <td>{{$bank_detail->ifsc_code}}</td> --}}
                            <td>${{$payment->amount}}</td>
                            <td>{{$payment->hash}}</td>
                            <td>{{$payment->address}}</td>
                            <td>{{$payment->remarks}}</td>
                            {{-- <td><a href="{{asset('payments/'.$payment->image)}}"> <img src="{{asset('payments/'.$payment->image)}}" width="100"></a></td> --}}
                            <td>{{$payment->created_at}}</td>
                            <td class="d-flex">
                               <form method="POST" class="my-0" action="{{route('admin.accept_deposit')}}">
                                    @csrf
                                    <input type="hidden"  name="id" value="{{$payment->id}}">
                                <button type="submit" class="btn btn-sm btn-primary " onclick="return confirm('Are You Sure Want to Confirm!')">
                                    Confirm
                                </button>
                               </form>
                                <a href="#" class="btn btn-sm btn-danger ml-2 "  data-toggle="modal" data-target="#exampleModal" onclick="rejectRequest({{$payment->id}})">
                                    Reject
                                </a>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="7" class="text-center py-3 h5"><b>Data Not Found</b></td>
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
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Reject Payment</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>

        </div>
        <div class="modal-body">
            <form  method="POST" action="{{route('admin.reject_deposit')}}">
                @csrf
                <input type="hidden" id="payment_id" name="id" >
                <div class="form-group">
                    <label>Enter Reason</label>
                    <textarea class="form-control" name="remarks" rows="3" placeholder="Enter Reject Reason" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary my-2">Submit</button>

            </form>
        </div>

      </div>
    </div>
  </div>
<script>
    function rejectRequest(id){
        document.getElementById('payment_id').value = id;
    }
</script>
@endsection
