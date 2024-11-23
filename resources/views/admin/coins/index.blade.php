@extends('layouts.admin')

@section('content')
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                <div class="ml-auto my-2 py-3 me-auto d-flex justify-content-end text-end">
                <a href="{{route('admin.add_coin')}}" class="btn btn-info btn-sm" >Add Coin</a>
              </div>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title ">All Coins</h3>
                        <div class="card-tools d-flex mt-1">
                            <form class="form-inline ml-3" action="" method="get" id="invest_type">
                                <div class="input-group input-group-sm">
                                    <select name="type" id="" class="form-control form-select" required>
                                        <option value="" disabled selected>Search Type</option>
                                        <option value="name" {{ Request()->type == 'name' ? 'selected' : '' }}>Name </option>
                                        <option value="network" {{ Request()->type == 'network' ? 'selected' : '' }}>Network </option>
                                        <option value="type" {{ Request()->type == 'type' ? 'selected' : '' }}>Type</option>
                                        <option value="address" {{ Request()->type == 'address' ? 'selected' : '' }}>Contract Address</option>
                                    </select>
                                </div>


                                <div class="input-group input-group-sm  mt-3 mt-sm-0 ml-lg-3   ">
                                    <input class="form-control form-control-navbar" type="search" placeholder="Search"
                                        name="search" value="{{ Request()->search }}" required>
                                    <div class="input-group-append">
                                        <button class="btn btn-success" type="submit">
                                            <i class="fas fa-search"></i>
                                        </button>
                                        <a class="btn btn-danger ml-3" href="{{ route('admin.coins') }}">Reset </a>
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
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Network</th>
                                            <th>Type</th>
                                            <th>Contract Address</th>
                                            {{-- <th style="min-width:120px;">Date</th> --}}
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($coins) > 0)
                                            @foreach ($coins as $key => $coin)
                                                <tr>
                                                    <td>{{ $coins->firstItem() + $key }}</td>
                                                    <td>{{ $coin->name }}</td>
                                                    <td>{{ $coin->network }}</td>
                                                    <td>{{ $coin->type }}</td>
                                                    <td>{{ $coin->address }}</td>
                                                    {{-- <td>{{$coin->created_at}}</td> --}}
                                                    <td>
                                                        <a href="{{ route('admin.edit_coin', $coin->id) }}"
                                                            class="text-info mr-2"><i class="fa fa-edit"></i></a>
                                                        <a href="{{ route('admin.delete_coin', $coin->id) }}"
                                                            class="text-danger"
                                                            onclick="return confirm('Are You sure want to delete coin!')"><i
                                                                class="fa fa-trash"></i></a>
                                                    </td>
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
                            {{ $coins->links() }}
                        </div>
                    </div>
                </div>
        </section>
    </div>
@endsection
