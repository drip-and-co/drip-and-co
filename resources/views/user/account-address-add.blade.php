@extends('layouts.app')
@section('content')
<main class="pt-90">
        <div class="mb-4 pb-4"></div>
        <section class="my-account container">
        <h2 class="page-title">Address</h2>
        <div class="row">
            <div class="col-lg-3">
            @include('user.account-nav')
            </div>
            <div class="col-lg-9">
            <div class="page-content my-account__address">
                <div class="row">
                    <div class="col-6">
                        <p class="notice">The following addresses will be used on the checkout page by default.</p>
                    </div>
                    <div class="col-6 text-right">
                        <a href="{{ route('user.address') }}" class="btn btn-sm btn-danger">Back</a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8">
                        <div class="card mb-5">
                            <div class="card-header">
                                <h5>Add New Address</h5>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('user.address.save') }}" method="POST">
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
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-floating my-3">
                                                <input type="text" class="form-control" name="name" required="" value="{{ old('name') }}">
                                                <label for="name">Full Name *</label>
                                                @error('name')<span class="text-danger">{{ $message }}</span>@enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating my-3">
                                                <input type="text" class="form-control" name="phone" required="" value="{{ old('phone') }}" >
                                                <label for="phone">Phone Number *</label>
                                                @error('phone')<span class="text-danger">{{ $message }}</span>@enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-floating my-3">
                                                <input type="text" class="form-control" name="zip" required="" value="{{ old('zip') }}">
                                                <label for="zip">Postcode *</label>
                                                @error('zip')<span class="text-danger">Please enter a valid postcode (e.g., B4 7ET)</span>@enderror
                                            </div>
                                        </div>                        
                                        <div class="col-md-4">
                                            <div class="form-floating mt-3 mb-3">
                                                <input type="text" class="form-control" name="state" required="" value="{{ old('state') }}">
                                                <label for="state">County *</label>
                                                @error('state')<span class="text-danger">{{ $message }}</span>@enderror
                                            </div>                            
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-floating my-3">
                                                <input type="text" class="form-control" name="city" required="" value="{{ old('city') }}">
                                                <label for="city">Town / City *</label>
                                                @error('city')<span class="text-danger">{{ $message }}</span>@enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating my-3">
                                                <input type="text" class="form-control" name="address" required="" value="{{ old('address') }}">
                                                <label for="address">House no, Building Name *</label>
                                                @error('address')<span class="text-danger">{{ $message }}</span>@enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating my-3">
                                                <input type="text" class="form-control" name="locality" required="" value="{{ old('locality') }}">
                                                <label for="locality">Road Name, Area *</label>
                                                @error('locality')<span class="text-danger">{{ $message }}</span>@enderror
                                            </div>
                                        </div>       
                                        <div class="col-md-12 text-right">
                                            <button type="submit" class="btn btn-success">Submit</button>
                                        </div>                                     
                                    </div>
                                </form> 
                            </div>
                        </div>
                    </div>
                </div>
                <hr>                    
            </div>
        </div>
        </div>
    </section>
</main>
@endsection