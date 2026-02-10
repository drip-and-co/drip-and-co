<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return view('user.index');
    }

    public function orders()
    {
        $orders = Order::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->paginate(10);
        return view('user.orders', compact('orders'));
    }
    public function order_details($order_id)
    {
        $order = Order::where('user_id', auth()->user()->id)->where('id', $order_id)->first();
        if (!$order) 
            
        {
        $orderItems = OrderItem::where('order_id', $order->id)->orderBy("id")->paginate(12);
        $transactions = Transaction::where('order_id', $order->id)->first();
       return view('user.order_details', compact('order',"orderItems","transactions"));
        }
         else {
            return redirect()->route('login')
            ;
        }

}

public function order_cancel(Request $request)
{
$order = Order::find($request->order_id);
$order->status = "cancelled";
$order->cancled_date = carbon::now();
$order->save();
return back()->with("status","Order has been cancelled Successfully");

}
}