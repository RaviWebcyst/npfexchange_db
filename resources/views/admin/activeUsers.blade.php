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
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title ">All Users</h3>
                        <div class="card-tools d-block d-md-flex mt-1">
                            <form class="form-inline ml-3" action="" method="get" id="invest_type">
                                <div class="input-group input-group-sm">
                                    <select name="type" id="" class="form-control form-select" required>
                                        <option value="" disabled selected>Select Type</option>
                                        <option value="name" {{ Request()->type == 'name' ? 'selected' : '' }}>Name </option>
                                        <option value="email" {{ Request()->type == 'email' ? 'selected' : '' }}>Email </option>
                                        <option value="uid" {{ Request()->type == 'uid' ? 'selected' : '' }}>User Id </option>
                                        <option value="spid" {{ Request()->type == 'spid' ? 'selected' : '' }}>Sponser Id </option>
                                    </select>
                                </div>

                                <div class="input-group input-group-sm mt-3 mt-sm-0   ml-lg-3    ">
                                    <input class="form-control form-control-navbar" type="search" placeholder="Search"
                                        name="search" value="{{ Request()->search }}" required>
                                    <div class="input-group-append">
                                        <button class="btn btn-success" type="submit">
                                            <i class="fas fa-search"></i>
                                        </button>
                                        <a class="btn btn-danger ml-3" href="{{ route('admin.activeUsers') }}">Reset </a>

                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body ">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table ">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Phone</th>
                                            <th>Email</th>
                                            <th>Password</th>
                                            <th>UID</th>
                                            <th>Package Activated</th>
                                            <th> Activation Date</th>
                                            <th style="min-width:120px;">Date</th>
                                            <th style="min-width:100px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (!empty($users))
                                            @foreach ($users as $key => $user)
                                                <tr>
                                                    <td>{{ $users->firstItem() + $key }}</td>
                                                    <td>{{ $user->name }}</td>
                                                    <td>{{ $user->phone }}</td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>{{ $user->showPass }}</td>
                                                    <td>{{ $user->uid }}</td>
                                                    <td>{{ $user->enable == 1 ? 'Yes' : 'No' }}</td>
                                                    <td>{{ $user->paid_date }}</td>
                                                    <td>{{ $user->created_at }}</td>
                                                    <td class="d-flex">
                                                        <a href="{{ route('admin.editUser', $user->id) }}"
                                                            class="btn btn-warning btn-sm mr-2">Edit</a>
                                                        <a href="{{ route('admin.sendEpin', $user->id) }}"
                                                            class="btn btn-info btn-sm mr-2">Send USDT</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                                {{ $users->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
