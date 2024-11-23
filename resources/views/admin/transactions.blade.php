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
                    <h3 class="card-title ">All Transactions</h3>
                    <div class="card-tools d-flex mt-1">
                      <form class="form-inline ml-3" action="" method="get" id="invest_type">
                          <div class="input-group input-group-sm">
                           <select name="type" id="" class="form-control form-select" required>
                            <option value="" disabled selected>Search Type</option>
                            {{-- <option value="name" {{ Request()->type == 'name' ? 'selected': '' }}>Name</option> --}}
                            <option value="user_id" {{ Request()->type == 'user_id' ? 'selected': '' }}>User Id</option>
                             <option value="amount" {{ Request()->type == 'amount' ? 'selected': '' }}>Amount</option>
                            <option value="type" {{ Request()->type == 'type' ? 'selected':''}}>Type</option>
                            {{-- <option value="description" {{ Request()->type == 'description' ? 'selected':''}}>Description</option> --}}
                            </select>
                          </div>


                          <div class="input-group input-group-sm  mt-3 mt-sm-0 ml-lg-3   ">
                              <input class="form-control form-control-navbar" type="search" placeholder="Search"  name="search" value="{{ Request()->search}}" required>
                              <div class="input-group-append">
                                  <button class="btn btn-success" type="submit">
                                      <i class="fas fa-search"></i>
                                  </button>
                                  <a class="btn btn-danger ml-3" href="{{route('admin.transactions')}}">Reset  </a>
                              </div>
                          </div>
                      </form>
                  </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                    <div class="table-responsive">
                  <table class="table ">
                    <thead>
                      <tr>
                        <th >#</th>
                        <th>User</th>
                        <th>Amount</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th style="min-width:120px;">Date</th>
                      </tr>
                    </thead>
                    <tbody>
                        @if(count($records) > 0)
                        @foreach ($records as $key=>$user)
                        <tr>
                            <td>{{$records->firstItem()+$key}}</td>
                            <td>{{$user->email}}</td>
                            <td>${{$user->amount}}</td>
                            <td>{{$user->type}}</td>
                            <td>{{$user->description}}</td>
                            <td>{{$user->created_at}}</td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="6" class="text-center py-3 h5"><b>Data Not Found</b></td>
                        </tr>
                        @endif
                    </tbody>
                  </table>
                    </div>
                  {{$records->links()}}
                </div>
              </div>
        </div>
    </section>
</div>
@endsection
