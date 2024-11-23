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
            <div class="ml-auto my-2 py-3 me-auto d-flex justify-content-end text-end">
                <a href="{{route('admin.add_asset')}}" class="btn btn-info btn-sm" >Add Assets</a>
              </div>
            <div class="card">

                <div class="card-header">
                    <h3 class="card-title ">All Assets</h3>
                    <div class="card-tools d-flex mt-1">
                      <form class="form-inline ml-3" action="" method="get" id="exchange">
                        <div class="input-group input-group-sm ">
                            <select name="exchange"  class="form-control form-select" required onchange="$('#exchange').submit();">
                                <option value="" disabled selected>Select Exchange</option>
                                <option value="0" {{ Request()->exchange == '0' ? 'selected':'' }}>All </option>
                                <option value="spot" {{ Request()->exchange == 'spot' ? 'selected':'' }}>Spot </option>
                                <option value="future" {{ Request()->exchange == 'future' ? 'selected':'' }}>Future </option>
                            </select>
                        </div>
                      </form>
                        <form class="form-inline ml-3" action="" method="get" id="invest_type">
                            <div class="input-group input-group-sm">
                                <select name="type" id="" class="form-control form-select" required>
                                    <option value="" disabled selected>Search Type</option>
                                    <option value="name" {{ Request()->type == 'name' ? 'selected' : '' }}>Name </option>
                                </select>
                            </div>


                            <div class="input-group input-group-sm  mt-3 mt-sm-0 ml-lg-3   ">
                                <input class="form-control form-control-navbar" type="search" placeholder="Search"
                                    name="search" value="{{ Request()->search }}" required>
                                <div class="input-group-append">
                                    <button class="btn btn-success" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <a class="btn btn-danger ml-3" href="{{ route('admin.assets') }}">Reset </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                {{-- <div class="card-header">
                    <div class="d-flex">
                  <h3 class="card-title ">All Assets</h3>
                  <div class="ml-auto">
                    <a href="{{route('admin.add_asset')}}" class="btn btn-info btn-sm" >Add</a>
                  </div>
                    </div>
                </div> --}}
                <!-- /.card-header -->
                <div class="card-body p-0">
                <div class="table-responsive">
                  <table class="table ">
                    <thead>
                      <tr>
                        <th >#</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>

                        @if(count($assets) > 0)
                        @foreach ($assets as $key=>$asset)
                        <tr>
                            <td>{{$assets->firstItem()+$key}}</td>
                            <td>{{$asset->name}}</td>
                            <td>{{$asset->type}}</td>
                            <td>
                                <a href="{{route('admin.edit_asset',$asset->id)}}" class="text-info mr-2"><i class="fa fa-edit"></i></a>
                                <a href="{{route('admin.delete_asset',$asset->id)}}" class="text-danger" onclick="return confirm('Are You sure want to delete asset!')"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="4" class="text-center py-3 h5"><b>Data Not Found</b></td>
                        </tr>
                        @endif
                    </tbody>
                  </table>
                </div>
                  {{$assets->links()}}
                </div>
              </div>
        </div>
    </section>
</div>
@endsection
