@extends('layouts.admin')
@section('content')

<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Edit User</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{ route('admin.index') }}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li><i class="icon-chevron-right"></i></li>
                <li>
                    <a href="{{ route('admin.users') }}">
                        <div class="text-tiny">Users</div>
                    </a>
                </li>
                <li><i class="icon-chevron-right"></i></li>
                <li><div class="text-tiny">Edit</div></li>
            </ul>
        </div>

        <div class="wg-box">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.user.update', ['id' => $user->id]) }}"
                  method="POST"
                  class="form-new-product form-style-1 needs-validation">
                @csrf
                @method('PUT')

                <fieldset class="name">
                    <div class="body-title">Name <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="text" name="name" value="{{ old('name', $user->name) }}" required>
                </fieldset>

                <fieldset class="name">
                    <div class="body-title">Mobile Number</div>
                    <input class="flex-grow" type="text" name="mobile" value="{{ old('mobile', $user->mobile) }}">
                </fieldset>

                <fieldset class="name">
                    <div class="body-title">Email Address <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="email" name="email" value="{{ old('email', $user->email) }}" required>
                </fieldset>

                <div class="row">
                    <div class="col-md-12">
                        <div class="my-3">
                            <h5 class="text-uppercase mb-0">Password Change</h5>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <fieldset class="name">
                            <div class="body-title pb-3">New password</div>
                            <input class="flex-grow" type="password" name="password" placeholder="New password">
                        </fieldset>
                    </div>

                    <div class="col-md-12">
                        <fieldset class="name">
                            <div class="body-title pb-3">Confirm new password</div>
                            <input class="flex-grow" type="password" name="password_confirmation" placeholder="Confirm new password">
                        </fieldset>
                    </div>

                    <div class="col-md-12">
                        <div class="my-3">
                            <button type="submit" class="btn btn-primary tf-button w208">
                                Save Changes
                            </button>
                        </div>
                    </div>
                </div>

            </form>
        </div>

    </div>
</div>

@endsection