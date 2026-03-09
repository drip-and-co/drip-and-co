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
                  <div class="col-md-12">
                    <div class="my-3">
                      <h5 class="text-uppercase mb-0">Change Password</h5>
                    </div>
                  </div>
                  @if (session('status'))
                      <div class="alert alert-success">{{ session('status') }}</div>
                  @endif

                  <form action="{{ route('user.password.update') }}" method="POST">
                      @csrf
                      
                      @if (session('status'))
                          <div class="alert alert-success" role="alert">
                              {{ session('status') }}
                          </div>
                      @elseif (session('error'))
                          <div class="alert alert-danger" role="alert">
                              {{ session('error') }}
                          </div>
                      @endif
                      <div class="mb-3">
                          <label for="current_password">Current Password</label>
                          <input id="current_password" type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror">
                          @error('current_password') 
                          <span class="text-danger">{{ $message }}</span> 
                          @enderror
                      </div>

                      <div class="mb-3">
                          <label for="new_password">New Password</label>
                          <input id="new_password" type="password" name="new_password" class="form-control " >
                          <small class="text-muted d-block mt-1">Password must be at least 8 characters and include a number and a capital letter.</small>
                          @if ($errors->has('new_password'))
                              @foreach ($errors->get('new_password') as $message)
                                  <span class="text-danger d-block">{{ $message }}</span>
                              @endforeach
                          @endif
                      </div>

                      <div class="mb-3">
                          <label for="new_password_confirmation">Confirm New Password</label>
                          <input id="new_password_confirmation" type="password" name="new_password_confirmation" class="form-control" >
                          @error('new_password_confirmation') <span class="text-danger">{{ $message }}</span> @enderror
                      </div>

                      <button type="submit" class="btn btn-primary">Update Password</button>
                  </form>
                </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
@endsection