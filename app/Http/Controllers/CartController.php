<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Surfsidemedia\Shoppingcart\Facades\Cart;

class CartController extends Controller
{
    public function index()
    {
        $items = Cart::instance('cart')->content();
        return view('cart', compact('items'));
    }

    public function add_to_cart(Request $request)
    {
           $product = Product::find($request->id);
    $options = ['product_id' => $request->id];

    // if size/color provided, look up the variant
    $variant = null;
    if ($product && ($request->has('size') || $request->has('color'))) {
        $query = $product->variants();
        if ($request->has('size')) {
            $query->where('size', $request->size);
            $options['size'] = $request->size;
        }
        if ($request->has('color')) {
            $query->where('color', $request->color);
            $options['color'] = $request->color;
        }
        $variant = $query->first();
        if ($variant) {
            $options['variant_id'] = $variant->id;
        }
    }

    // prevent adding more than available stock
    if ($variant) {
        if ($request->quantity > $variant->quantity) {
            return redirect()->back()->with('error', 'Requested quantity exceeds available stock for the selected variant.');
        }
    } else {
        if ($product && $request->quantity > $product->quantity) {
            return redirect()->back()->with('error', 'Requested quantity exceeds available stock.');
        }
    }

    Cart::instance('cart')->add(
        $variant ? 'variant-'.$variant->id : $request->id,
        $request->name,
        $request->quantity,
        $request->price,
        $options
    )->associate('App\Models\Product');

    return redirect()->back();
    }

    public function increase_cart_quantity($rowId)
    {
        $product = Cart::instance('cart')->get($rowId);
        $qty = $product->qty + 1;
        Cart::instance('cart')->update($rowId, $qty);
        return redirect()->back();
    }

    public function decrease_cart_quantity($rowId)
    {
        $product = Cart::instance('cart')->get($rowId);
        $qty = $product->qty - 1;
        Cart::instance('cart')->update($rowId, $qty);
        return redirect()->back();
    }

    public function update_cart_quantity(Request $request, $rowId)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);
        $qty = (int) $request->quantity;
        Cart::instance('cart')->update($rowId, $qty);
        return redirect()->back();
    }

    public function remove_item($rowId)
    {
        Cart::instance('cart')->remove($rowId);
        return redirect()->back();
    }

    public function empty_cart()
    {
        Cart::instance('cart')->destroy();
        return redirect()->back();
    }

    public function apply_coupon_code(Request $request)
    {
        $coupon_code = $request->coupon_code;
        if(isset($coupon_code))
        {
            $coupon = Coupon::where('code', $coupon_code)->where('expiry_date', '>=', Carbon::today())->where('cart_value', '<=', Cart::instance('cart')->subtotal())->first();
            if(!$coupon)
                {
                    return redirect()->back()->with('error','Invalid coupon code');
                }
            else{
                Session::put('coupon', [
                    'code' => $coupon->code,
                    'type' => $coupon->type,
                    'value' => $coupon->value,
                    'cart_value' => $coupon->cart_value
                ]);
                $this->calculateDiscount();
                return redirect()->back()->with('success','Coupon applied successfully');
            }
        }
        else{
            return redirect()->back()->with('error','Invalid coupon code');
        }
    }

    public function calculateDiscount()
    {
        $discount = 0;
        if(Session::has('coupon'))
        {
            if(Session::get('coupon')['type'] == 'fixed')
            {
                $discount = Session::get('coupon')['value'];
            }
            else
            {
                $discount = (Cart::instance('cart')->subtotal() * Session::get('coupon')['value']) / 100;
            }

            $subtotalAfterDiscount = Cart::instance('cart')->subtotal() - $discount;
            $taxAfterDiscount = ($subtotalAfterDiscount * config('cart.tax'))/100;
            $totalAfterDiscount = $subtotalAfterDiscount + $taxAfterDiscount;

            Session::put('discounts',[
                'discount' => number_format(floatval($discount),2,'.',''),
                'subtotal' => number_format(floatval($subtotalAfterDiscount),2,'.',''),
                'tax' => number_format(floatval($taxAfterDiscount),2,'.',''),
                'total' => number_format(floatval($totalAfterDiscount),2,'.','')
            ]);
        }
    }

    public function remove_coupon_code()
    {
        Session::forget('coupon');
        Session::forget('discounts');
        return back()->with('error','Coupon has been removed successfully');
    }

    public function checkout()
    {
        if(!Auth::check())
        {
            return redirect()->route('login');
        }

        $addresses = Address::where('user_id', Auth::user()->id)->orderBy('isdefault', 'desc')->get();
        $address = $addresses->where('isdefault', 1)->first() ?? $addresses->first();

        // Build stock warnings and available quantities per product/variant for the view
        $stockWarnings = [];
        $availableByProduct = [];
        foreach (Cart::instance('cart')->content() as $item) {
            $available = 0;
            if (isset($item->options['variant_id'])) {
                $variant = \App\Models\ProductVariant::find($item->options['variant_id']);
                $available = $variant ? (int) $variant->quantity : 0;
                $availableByProduct['variant_'.$item->options['variant_id']] = $available;
            } else {
                $product = Product::find($item->id);
                $available = $product ? (int) $product->quantity : 0;
                $availableByProduct[$item->id] = $available;
            }
            if ($available < (int) $item->qty) {
                $stockWarnings[] = [
                    'name'      => $item->name,
                    'requested' => (int) $item->qty,
                    'available' => $available,
                ];
            }
        }

        return view('checkout', compact('address', 'addresses', 'stockWarnings', 'availableByProduct'));
    }

    public function place_an_order(Request $request)
    {
        $user_id = Auth::user()->id;
        $address = null;

        if ($request->filled('address_id')) {
            $address = Address::where('user_id', $user_id)->where('id', $request->address_id)->first();
            if (!$address) {
                return redirect()->route('cart.checkout')->with('error', 'Selected address is invalid.');
            }
        }

        if (!$address) {
            $address = Address::where('user_id', $user_id)->where('isdefault', true)->first();
        }

        if (!$address) {
            $request->validate([
                'name' => 'required|max:100',
                'phone' => 'required|numeric|digits:10',
                'zip' => ['required', 'regex:/^([A-Z]{1,2}[0-9]{1,2}[A-Z]?(?:\s?[0-9][A-Z]{2})?)$/i'],
                'state' => 'required',
                'city' => 'required',
                'address' => 'required',
                'locality' => 'required',
                'mode' => 'required|in:card,paypal,cod'
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
            $address->isdefault = true;
            $address->save();
        }

        $request->validate(['mode' => 'required|in:card,paypal,cod']);

            $this->setAmountforCheckout();


            $order = new Order();

            $order->user_id = $user_id;
            $order->subtotal = $order->total = (float) str_replace(',', '', Session::get('checkout')['subtotal']);;
            $order->discount = $order->total = (float) str_replace(',', '', Session::get('checkout')['discount']);;
            $order->tax = $order->total = (float) str_replace(',', '', Session::get('checkout')['tax']);;
            $order->total = $order->total = (float) str_replace(',', '', Session::get('checkout')['total']);;
            $order->name = $address->name;
            $order->phone = $address->phone;
            $order->locality = $address->locality;
            $order->address = $address->address;
            $order->city = $address->city;
            $order->state = $address->state;
            $order->country = $address->country;
            $order->zip = $address->zip;
            $order->save();

            foreach (Cart::instance('cart')->content() as $item)
            {
                $orderItem = new OrderItem();
                $orderItem->product_id = $item->options['product_id'] ?? $item->id;
                $orderItem->order_id = $order->id;
                $orderItem->price = $item->price;
                $orderItem->quantity = $item->qty;
                $orderItem->options = json_encode($item->options);
                $orderItem->save();

                // if variant exists reduce its quantity, otherwise fall back to product
                if (isset($item->options['variant_id'])) {
                    $variant = \App\Models\ProductVariant::find($item->options['variant_id']);
                    if ($variant) {
                        $newQuantity = max(0, $variant->quantity - $item->qty);
                        $variant->quantity = $newQuantity;
                        $variant->stock_status = $newQuantity > 0 ? 'instock' : 'outofstock';
                        $variant->save();

                        // update parent product status based on remaining in-stock variants
                        $product = $variant->product;
                        if ($product) {
                            if ($product->variants()->where('stock_status','instock')->exists()) {
                                $product->stock_status = 'instock';
                            } else {
                                $product->stock_status = 'outofstock';
                            }
                            $product->save();
                        }
                    }
                } else {
                    $product = Product::find($item->id);
                    if ($product) {
                        $newQuantity = max(0, $product->quantity - $item->qty);
                        $product->quantity = $newQuantity;
                        if ($newQuantity === 0) {
                            $product->stock_status = 'outofstock';
                        }
                        $product->save();
                    }
                }
            }

            if($request->mode == 'card')
                {
                    $transaction = new Transaction();
                    $transaction->user_id = $user_id;
                    $transaction->order_id = $order->id;
                    $transaction->mode = $request->mode;
                    $transaction->status = 'pending';
                    $transaction->save();
                }
            elseif($request->mode == 'paypal')
                {
                    $transaction = new Transaction();
                    $transaction->user_id = $user_id;
                    $transaction->order_id = $order->id;
                    $transaction->mode = $request->mode;
                    $transaction->status = 'pending';
                    $transaction->save();
                }
            elseif($request->mode == 'cod')
                {
                    $transaction = new Transaction();
                    $transaction->user_id = $user_id;
                    $transaction->order_id = $order->id;
                    $transaction->mode = $request->mode;
                    $transaction->status = 'pending';
                    $transaction->save();
                }
                
            

            Cart::instance('cart')->destroy();
            Session::forget('checkout');
            Session::forget('coupon');
            Session::forget('discounts');
            Session::put('order_id', $order->id);
            return redirect()->route('cart.order.confirmation');

    }

    public function setAmountforCheckout()
    {
       if(!Cart::instance('cart')->content()->count() > 0)
        {
            Session::forget('checkout');
            return;
        }

        if(Session::has('coupon'))
        {
            Session::put('checkout',[
                'discount' => Session::get('discounts')['discount'],
                'subtotal' => Session::get('discounts')['subtotal'],
                'tax' => Session::get('discounts')['tax'],
                'total' => Session::get('discounts')['total']
            ]);
        }
        else{
            Session::put('checkout',[
                'discount' => 0,
                'subtotal' => Cart::instance('cart')->subtotal(),
                'tax' => Cart::instance('cart')->tax(),
                'total' => Cart::instance('cart')->total()
            ]);
        }

    }

    public function order_confirmation()
    {
        if(Session::has('order_id'))
            {
                $order = Order::find(Session::get('order_id'));
                return view('order-confirmation',compact('order'));
            }
            return redirect()->route('cart.index');
        
    }
}
