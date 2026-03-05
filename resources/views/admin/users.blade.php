@extends('layouts.admin')
@section('content')

<div class="main-content-inner">
    <div class="main-content-wrap">

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
                                            @if($user->utype === 'ADM')
                                                <span class="badge bg-danger">Admin</span>
                                            @else
                                                <span class="badge bg-primary">User</span>
                                            @endif
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

                                            <a href="{{ route('admin.user.edit', $user) }}">
                                                <div class="item edit">
                                                    <i class="icon-edit-3"></i>
                                                </div>
                                            </a>

                                            @if($user->utype !== 'ADM' && $user->id !== auth()->id())
                                               <form id="delete-user-{{ $user->id }}" action="{{ route('admin.user.delete', $user) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" onclick="deleteUser({{ $user->id }})" style="background:none;border:none;padding:0;">
                                                        <div class="item delete">
                                                            <i class="icon-trash-2 text-danger"></i>
                                                        </div>
                                                    </button>
                                                </form>
                                            @endif

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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
const deleteUser = (id) =>
  Swal.fire({
    title: 'Delete User',
    text: 'Are you sure you want to delete this user?',
    icon: 'warning',
    width: 720,
    showCancelButton: true,
    confirmButtonText: 'Yes, delete user',
    cancelButtonText: 'Cancel',
    confirmButtonColor: '#e55353',
    cancelButtonColor: '#6c757d'
  }).then(({ isConfirmed }) =>
    isConfirmed && document.getElementById(`delete-user-${id}`).submit()
  );
</script>

<style>
.swal2-popup{
    padding:40px !important;
    border-radius:16px !important;
}

.swal2-title{
    font-size:34px !important;
    font-weight:700 !important;
}

.swal2-html-container{
    font-size:20px !important;
    margin-top:12px !important;
}

.swal2-actions{
    margin-top:22px !important;
}

.swal2-actions button{
    font-size:18px !important;
    padding:16px 38px !important;
    border-radius:12px !important;
}

.swal2-icon{
    transform:scale(1.25);
}
</style>

@endsection