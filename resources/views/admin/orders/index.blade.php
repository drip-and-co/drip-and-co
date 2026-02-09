@foreach($orders as $order)
<div>
    <strong>{{ $order->tracking_no }}</strong> |
    {{ $order->user->name }} |
    {{ $order->status }}
    <a href="{{ url('admin/orders/'.$order->id) }}">View</a>
</div>
@endforeach