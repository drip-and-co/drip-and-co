@extends('layouts.app')
@section('content')
    <style>
        .my-account__address-item__title .address-actions {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: nowrap;
        }
        .my-account__address-item__title .address-actions form {
            display: inline-flex;
            align-items: center;
        }
        .my-account__address-item__title .address-actions .btn-link,
        .my-account__address-item__title .address-actions a.btn-link {
            border-bottom: none !important;
            text-decoration: none !important;
            text-transform: none !important;
            font-size: 0.875rem !important;
            line-height: 1 !important;
            padding: 0 !important;
            vertical-align: middle;
        }
    </style>
    <main class="pt-90">
        <div class="mb-4 pb-4"></div>
        <section class="my-account container">
        <h2 class="page-title">Addresses</h2>
        <div class="row">
            <div class="col-lg-3">
             @include('user.account-nav')
            </div>
            <div class="col-lg-9">
            <div class="page-content my-account__address">
                @if(session('status'))
                    <div class="alert alert-success mb-3">{{ session('status') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger mb-3">{{ session('error') }}</div>
                @endif
                <div class="row">
                <div class="col-6">
                    <p class="notice">The following addresses will be used on the checkout page by default.</p>
                </div>
                <div class="col-6 text-right">
                    <a href="{{ route('user.address.add') }}" class="btn btn-sm btn-info">Add New</a>
                </div>
                </div>
                <div class="my-account__address-list row">
                <h5>Shipping Address</h5>

                @foreach($addresses as $address)
                <div class="my-account__address-item col-md-6">
                        <div class="my-account__address-item__title">
                        <p>{{ $address->name }}</p>
                        <span class="address-actions">
                            <a href="{{ route('user.address.edit', ['id' => $address->id]) }}" class="btn btn-link btn-sm p-0 text-decoration-none">Edit</a>
                            <form method="POST" action="{{ route('user.address.delete', ['id' => $address->id]) }}" class="d-inline" onsubmit="return confirm('Delete this address?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-link btn-sm p-0 text-danger">Delete</button>
                            </form>
                        </span>
                        </div>
                        <div class="my-account__address-item__detail">
                        <p>{{ $address->address }} {{ $address->locality }}</p>
                        <p>{{ $address->city }}, {{ $address->state }}</p>
                        <p>{{ $address->zip }}</p>
                        <br>
                        <p>{{ $address->phone }}</p>
                        </div>
                </div>
                @endforeach
                </div>
            </div>
            </div>
        </div>
        </section>
    </main>
@endsection