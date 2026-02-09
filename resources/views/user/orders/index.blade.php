@foreach($orders as $order)
<div>
    {{ $order->tracking_no }} | {{ $order->status }}
    <a href="{{ url('my-orders/'.$order->id) }}">View</a>
</div>
@endforeach