<h3>Order {{ $order->tracking_no }}</h3>
<p>User: {{ $order->user->name }}</p>
@foreach($order->items as $item)
<div>{{ $item->product->name }} - {{ $item->qty }} - ₹{{ $item->price }}</div>
@endforeach