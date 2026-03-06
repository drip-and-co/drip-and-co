@extends('layouts.app')
@section('content')
    <style>
        .pt-90 { padding-top: 90px !important; }
        .my-account .page-title {
            font-size: 1.5rem;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 40px;
            border-bottom: 1px solid;
            padding-bottom: 13px;
        }
        .my-account .wg-box {
            display: flex;
            padding: 24px;
            flex-direction: column;
            gap: 24px;
            border-radius: 12px;
            background: var(--White);
            box-shadow: 0px 4px 24px 2px rgba(20, 25, 38, 0.05);
        }
        .return-product-thumb {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 10px;
            border: 1px solid #e1e1e1;
        }
        .return-product-info { display: flex; gap: 16px; align-items: center; }
    </style>

    <main class="pt-90" style="padding-top: 0px;">
        <div class="mb-4 pb-4"></div>
        <section class="my-account container">
            <h2 class="page-title">Return Item</h2>
            <div class="row">
                <div class="col-lg-2">
                    @include('user.account-nav')
                </div>

                <div class="col-lg-10">

                    {{-- Product summary --}}
                    <div class="wg-box mb-4">
                        <h5>Item Being Returned</h5>
                        <div class="return-product-info">
                            <img src="{{ asset('uploads/products/thumbnails') }}/{{ $item->product->image }}"
                                 alt="{{ $item->product->name }}"
                                 class="return-product-thumb">
                            <div>
                                <p class="mb-1"><strong>{{ $item->product->name }}</strong></p>
                                <p class="mb-1 text-muted">Order #{{ $order->id }} &mdash; Qty: {{ $item->quantity }}</p>
                                <p class="mb-0">£{{ number_format($item->price, 2) }} per item</p>
                                @if ($item->options)
                                    @php $opts = json_decode($item->options, true) @endphp
                                    @if ($opts && is_array($opts))
                                        <div class="mt-1">
                                            @if (isset($opts['color']))
                                                <span class="badge bg-info me-1">{{ $opts['color'] }}</span>
                                            @endif
                                            @if (isset($opts['size']))
                                                <span class="badge bg-secondary">{{ $opts['size'] }}</span>
                                            @endif
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Return reason form --}}
                    <div class="wg-box">
                        <h5>Return Reason</h5>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <p class="mb-0">{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif

                        <form action="{{ route('user.return.store', [$order->id, $item->id]) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="return_reason" class="form-label fw-semibold">
                                    Please describe the reason for your return <span class="text-danger">*</span>
                                </label>
                                <textarea
                                    name="return_reason"
                                    id="return_reason"
                                    rows="5"
                                    maxlength="500"
                                    class="form-control @error('return_reason') is-invalid @enderror"
                                    placeholder="e.g. Wrong size, damaged item, changed my mind..."
                                >{{ old('return_reason') }}</textarea>
                                @error('return_reason')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Max 500 characters.</small>
                            </div>

                            <div class="alert alert-warning" role="alert">
                                <strong>Please note:</strong> Submitting this return request will restore the item's
                                stock. This action cannot be undone.
                            </div>

                            <div class="d-flex gap-3">
                                <button type="submit" class="btn btn-primary">Submit Return Request</button>
                                <a href="{{ route('user.order.details', $order->id) }}" class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </section>
    </main>
@endsection
