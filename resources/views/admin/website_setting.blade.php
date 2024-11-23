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
                  <h4 >Website Setting
                </h4>

                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                    <div class="card-body">
                        <form role="form" method="post" action="{{route('admin.store_website_setting')}}" enctype="multipart/form-data">
                            @csrf
                          <div class="col-md-6">

                              <!-- text input -->
                              <div class="form-group">
                                <label>Title</label>
                                <input type="text" class="form-control" placeholder="Enter Title" name="title" value="{{$data!=null ? $data->title:''}}" >
                              </div>
                              <div class="form-group">
                                <label>Logo</label>
                                <input type="file" class="form-control"  name="logo"   >
                                @if(!empty($data && $data->logo))
                                <img src="{{asset('uploads/logo/'.$data->logo)}}" width="100">
                                @endif
                              </div>

                          <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                      </div>
                </div>
              </div>
        </div>
    </section>
</div>
@endsection
