@extends('layouts.app')
@section('content')
 <main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="my-account container">
      <h2 class="page-title">Account Details</h2>
      <div class="row">
        <div class="col-lg-3">
          @include('user.account-nav')
        </div>
        <div class="col-lg-9">
          <div class="page-content my-account__edit">
            <div class="my-account__edit-form">
                <form name="account_edit_form" action="{{ route('user.details.update') }}" method="POST" class="needs-validation" >
                    @csrf
                    @method('PUT')

                    @if (session('status'))
                          <div class="alert alert-success" role="alert">
                              {{ session('status') }}
                          </div>
                      @elseif (session('error'))
                          <div class="alert alert-danger" role="alert">
                              {{ session('error') }}
                          </div>
                      @endif
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating my-3">
                            <input type="text" id="name" class="form-control" name="name" value="{{ Auth::user()->name }}">
                            <label for="name">Name</label>
                            @error('name') 
                            <span class="text-danger">{{ $message }}</span> 
                            @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-floating my-3">
                            <input type="text" id="mobile" class="form-control" name="mobile" value="{{ Auth::user()->mobile }}">
                            <label for="mobile">Mobile Number</label>
                            @error('mobile') 
                            <span class="text-danger">{{ $message }}</span> 
                            @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-floating my-3">
                            <input type="email" id="email" class="form-control" name="email" value="{{ Auth::user()->email }}">
                            <label for="email">Email Address</label>
                            @error('email') 
                            <span class="text-danger">{{ $message }}</span> 
                            @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-floating my-3">
                        <button type="submit" class="btn btn-primary">Update Details</button>
                            </div>
                        </div>
                    </div>
                </form>
              <a href="{{ route('user.password.edit') }}" class="btn btn-primary">Change Password</a>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
@endsection