<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    public function index()
    {
        return view('user.index');
    }

    public function orders()
    {
        $orders = Order::where('user_id',Auth::user()->id)->orderBy('created_at', 'DESC')->paginate(10);
        return view('user.orders', compact('orders'));
    }
    
    public function order_details($order_id)
    {
        $order = Order::where('user_id',Auth::user()->id)->where('id',$order_id)->first();
        if($order)        
        {
             $orderItems = OrderItem::where('order_id',$order->id)->orderBy('id')->paginate(12);
             $transaction = Transaction::where('order_id',$order->id)->first();
             return view('user.order-details', compact('order','orderItems','transaction'));
       }
        else{
            return redirect()->route('login');
        }
    }
    public function order_cancel(Request $request)
    {
        $order = Order::find($request->order_id);
        $order->status = "canceled";
        $order->canceled_date = Carbon::now();
        $order->save();
        return back()->with('status',"Order has been canceled successfully!");
        
    }

    public function details()
    {
        return view('user.account-details');
    }

    public function details_update(Request $request){

        $user = Auth::user();
        
        // Validate input
        $request->validate(
        [
            'name'   => 'required|string|max:255',
            'mobile' => 'required|digits:10|unique:users,mobile,' . $user->id ,
            'email'  => 'required|email|max:255|unique:users,email,' . $user->id,
        ],
        );

        $user->name = $request->name;
        $user->mobile = $request->mobile;
        $user->email = $request->email;

        $user->save();

        return back()->with('status', 'Details changed successfully!');
    }

    public function address()
    {
        $address = Address::where('user_id', Auth::user()->id)->first();
        $addresses = Address::where('user_id', Auth::user()->id)->get();
        return view('user.account-address', compact('address', 'addresses'));
    }
    
    public function address_edit($id)
    {
        $address = Address::where('user_id', Auth::user()->id)->where('id', $id)->first();
        return view('user.account-address-edit', compact('address'));
    }
    
    public function address_update(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;

        $request->validate([
        'name' => 'required|max:100',
        'phone' => 'required|numeric|digits:10',
        'zip' => ['required', 'regex:/^([A-Z]{1,2}[0-9]{1,2}[A-Z]?(?:\s?[0-9][A-Z]{2})?)$/i'],
        'state' => 'required',
        'city' => 'required',
        'address' => 'required',
        'locality' => 'required',
    ]);

    $address = Address::where('user_id', $user_id)->where('id', $request->id)->first();
    $address->name = $request->name;
    $address->phone = $request->phone;
    $address->zip = $request->zip;
    $address->state = $request->state;
    $address->city = $request->city;
    $address->address = $request->address;
    $address->locality = $request->locality;
    $address->country = 'United Kingdom';
    $address->user_id = $user_id;
    $address->save();

    return back()->with('status', 'Address added successfully!');

    }

    public function address_add()
    {
        return view('user.account-address-add');
    }

    public function address_save(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;

        $request->validate([
        'name' => 'required|max:100',
        'phone' => 'required|numeric|digits:10',
        'zip' => ['required', 'regex:/^([A-Z]{1,2}[0-9]{1,2}[A-Z]?(?:\s?[0-9][A-Z]{2})?)$/i'],
        'state' => 'required',
        'city' => 'required',
        'address' => 'required',
        'locality' => 'required',
    ]);

    $address = new Address();
    $address->name = $request->name;
    $address->phone = $request->phone;
    $address->zip = $request->zip;
    $address->state = $request->state;
    $address->city = $request->city;
    $address->address = $request->address;
    $address->locality = $request->locality;
    $address->country = 'United Kingdom';
    $address->user_id = $user_id;
    $address->save();

    return back()->with('status', 'Address added successfully!');

    }
}
