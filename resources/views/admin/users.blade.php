@extends('layouts.admin')
@section('content')

<div class="main-content-inner">
    <div class="main-content-wrap">

        <!-- Page Header -->
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Users</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{ route('admin.index') }}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">All User</div>
                </li>
            </ul>
        </div>

        <!-- Users Table -->
        <div class="wg-box">
            <div class="flex items-center justify-between gap10 flex-wrap">
                <div class="wg-filter flex-grow">
                    <form class="form-search" method="GET" action="{{ route('admin.users') }}">
                        <fieldset class="name">
                            <input type="text"
                                   placeholder="Search here..."
                                   name="q"
                                   value="{{ request('q') }}">
                        </fieldset>
                        <div class="button-submit">
                            <button type="submit">
                                <i class="icon-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="wg-table table-all-user">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th class="text-center">Total Orders</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>

                                    <td class="pname">
                                        <div class="image">
                                            <img src="{{ asset('assets/images/avatar/user-1.png') }}"
                                                 alt="user"
                                                 class="image">
                                        </div>
                                        <div class="name">
                                            <a href="#" class="body-title-2">
                                                {{ $user->name }}
                                            </a>
                                            <div class="text-tiny mt-3">
                                                {{ strtoupper(substr($user->name,0,3)) }}
                                            </div>
                                        </div>
                                    </td>

                                  <td>{{ $user->mobile ?? '-' }}</td>

                                    <td>{{ $user->email }}</td>

                                    <td class="text-center">
                                        {{ $user->orders_count ?? 0 }}
                                    </td>

                                    <td>
                                        <div class="list-icon-function">
                                            <a href="{{ route('admin.user.edit', ['id' => $user->id]) }}">
                                                <div class="item edit">
                                                    <i class="icon-edit-3"></i>
                                                </div>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">
                                        No users found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="divider"></div>

            <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                {{ $users->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

@endsection