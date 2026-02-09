<?php
namespace App\Http\Controllers;

use App\Models\Order;

class UserOrderController extends Controller
{
    public function index() {
        $orders = Order::where('user_id', auth()->id())->latest()->get();
        return view('user.orders.index', compact('orders'));
    }

    public function show(Order $order) {
        abort_if($order->user_id !== auth()->id(), 403);
        $order->load('items.product');
        return view('user.orders.show', compact('order'));
    }
}