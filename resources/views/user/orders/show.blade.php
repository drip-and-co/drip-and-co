<h3>Order Details</h3>
@foreach($order->items as $item)
<div>{{ $item->product->name }} - {{ $item->qty }} - ₹{{ $item->price }}</div>
@endforeach