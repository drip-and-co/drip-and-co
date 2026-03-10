<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\Transaction;
use App\Models\Slide;
use App\Models\Contact;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Intervention\Image\Laravel\Facades\Image;
use App\Models\Address;
use Illuminate\Support\Facades\Hash;


class AdminController extends Controller
{
    public function index()
    {
        $orders = Order::orderBy('created_at', 'DESC')->get()->take(10);
        $dashboardDatas = DB::select("Select sum(total) As TotalAmount,
                                    sum(if(status='ordered',total,0)) As TotalOrderedAmount,
                                    sum(if(status='delivered',total,0)) As TotalDeliveredAmount,
                                    sum(if(status='canceled',total,0)) As TotalCanceledAmount,
                                    Count(*) As Total,
                                    sum(if(status='ordered',1,0)) As TotalOrdered,
                                    sum(if(status='delivered',1,0)) As TotalDelivered,
                                    sum(if(status='canceled',1,0)) As TotalCanceled
                                    From Orders
                                    ");

        $monthlyDatas = DB::select("SELECT M.id As MonthNo, M.name As MonthName,
                            IFNULL(D.TotalAmount,0) As TotalAmount,
                            IFNULL(D.TotalOrderedAmount,0) As TotalOrderedAmount,
                            IFNULL(D.TotalDeliveredAmount,0) As TotalDeliveredAmount,
                            IFNULL(D.TotalCanceledAmount,0) As TotalCanceledAmount FROM month_names M
                            LEFT JOIN (Select DATE_FORMAT(created_at, '%b') As MonthName,
                            MONTH(created_at) As MonthNo,
                            sum(total) As TotalAmount,
                            sum(if(status='ordered',total,0)) As TotalOrderedAmount,
                            sum(if(status='delivered',total,0)) As TotalDeliveredAmount,
                            sum(if(status='canceled',total,0)) As TotalCanceledAmount
                            From Orders WHERE YEAR(created_at)=YEAR(NOW()) GROUP BY YEAR(created_at), MONTH(created_at) , DATE_FORMAT(created_at, '%b')
                            Order By MONTH(created_at)) D On D.MonthNo=M.id");

        $AmountM = implode(',', collect($monthlyDatas)->pluck('TotalAmount')->toArray());
        $OrderedAmountM = implode(',', collect($monthlyDatas)->pluck('TotalOrderedAmount')->toArray());
        $DeliveredAmountM = implode(',', collect($monthlyDatas)->pluck('TotalDeliveredAmount')->toArray());
        $CanceledAmountM = implode(',', collect($monthlyDatas)->pluck('TotalCanceledAmount')->toArray());

        $TotalAmount = collect($monthlyDatas)->sum('TotalAmount');
        $TotalOrderedAmount = collect($monthlyDatas)->sum('TotalOrderedAmount');
        $TotalDeliveredAmount = collect($monthlyDatas)->sum('TotalDeliveredAmount');
        $TotalCanceledAmount = collect($monthlyDatas)->sum('TotalCanceledAmount');

        return view('admin.index', compact('orders', 'dashboardDatas', 'AmountM', 'OrderedAmountM', 'DeliveredAmountM', 'CanceledAmountM', 'TotalAmount', 'TotalOrderedAmount', 'TotalDeliveredAmount', 'TotalCanceledAmount'));
    }

    public function brands()
    {
        $brands = Brand::orderBy('id', 'DESC')->paginate(10);
        return view('admin.brands', compact('brands'));
    }

    public function add_brand()
    {
        return view('admin.brand-add');
    }

    public function brand_store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:brands,slug',
            'image' => 'mimes:png,jpg,jpeg|max:2048'
        ]);
        $brand = new Brand();
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);
        $image = $request->file('image');
        $file_extension = $request->file('image')->extension();
        $file_name = Carbon::now()->timestamp . '.' . $file_extension;
        $this->GenerateBrandThumbnailsImage($image, $file_name);
        $brand->image = $file_name;
        $brand->save();
        return redirect()->route('admin.brands')->with('status', 'Brand has been added successfully');
    }

    public function brand_edit($id)
    {
        $brand = Brand::find($id);
        return view('admin.brand-edit', compact('brand'));
    }

    public function brand_update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:brands,slug',
            'image' => 'mimes:png,jpg,jpeg|max:2048'
        ]);

        $brand = Brand::find($request->id);
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);
        if ($request->hasFile('image')) {
            if (File::exists(public_path('uploads/brands') . '/' . $brand->image)) {
                File::delete(public_path('uploads/brands') . '/' . $brand->image);
            }
            $image = $request->file('image');
            $file_extension = $request->file('image')->extension();
            $file_name = Carbon::now()->timestamp . '.' . $file_extension;
            $this->GenerateBrandThumbnailsImage($image, $file_name);
            $brand->image = $file_name;
        }

        $brand->save();
        return redirect()->route('admin.brands')->with('status', 'Brand has been updated successfully');
    }

    public function GenerateBrandThumbnailsImage($image, $imageName)
    {
        $destinationPath = public_path('uploads/brands');
        $img = Image::read($image->path());
        $img->cover(124, 124, 'top');
        $img->resize(124, 124, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath . '/' . $imageName);
    }

    public function brand_delete($id)
    {
        $brand = Brand::find($id);
        if (File::exists(public_path('uploads/brands') . '/' . $brand->image)) {
            File::delete(public_path('uploads/brands') . '/' . $brand->image);
        }
        $brand->delete();
        return redirect()->route('admin.brands')->with('status', 'Brand has been deleted successfully!');
    }

    public function categories()
    {
        $categories = Category::orderBy('id', 'DESC')->paginate(10);
        return view('admin.categories', compact('categories'));
    }

    public function category_add()
    {
        return view('admin.category-add');
    }

    public function category_store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories,slug',
            'image' => 'mimes:png,jpg,jpeg|max:2048'
        ]);
        $category = new Category();
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $image = $request->file('image');
        $file_extension = $request->file('image')->extension();
        $file_name = Carbon::now()->timestamp . '.' . $file_extension;
        $this->GenerateCategoryThumbnailsImage($image, $file_name);
        $category->image = $file_name;
        $category->save();
        return redirect()->route('admin.categories')->with('status', 'Category has been added successfully');
    }

    public function GenerateCategoryThumbnailsImage($image, $imageName)
    {
        $destinationPath = public_path('uploads/categories');
        $img = Image::read($image->path());
        $img->cover(124, 124, 'top');
        $img->resize(124, 124, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath . '/' . $imageName);
    }

    public function category_edit($id)
    {
        $category = Category::find($id);
        return view('admin.category-edit', compact('category'));
    }

    public function category_update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories,slug',
            'image' => 'mimes:png,jpg,jpeg|max:2048'
        ]);

        $category = Category::find($request->id);
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        if ($request->hasFile('image')) {
            if (File::exists(public_path('uploads/categories') . '/' . $category->image)) {
                File::delete(public_path('uploads/categories') . '/' . $category->image);
            }
            $image = $request->file('image');
            $file_extension = $request->file('image')->extension();
            $file_name = Carbon::now()->timestamp . '.' . $file_extension;
            $this->GenerateCategoryThumbnailsImage($image, $file_name);
            $category->image = $file_name;
        }

        $category->save();
        return redirect()->route('admin.categories')->with('status', 'Category has been updated successfully');
    }

    public function category_delete($id)
    {
        $category = Category::find($id);
        if (File::exists(public_path('uploads/categories') . '/' . $category->image)) {
            File::delete(public_path('uploads/categories') . '/' . $category->image);
        }
        $category->delete();
        return redirect()->route('admin.categories')->with('status', 'Category has been deleted successfully!');
    }

    public function products()
    {
        $products = Product::orderBy('created_at', 'DESC')->paginate(10);
        return view('admin.products', compact('products'));
    }

    public function product_add()
    {
        $categories = Category::select('id', 'name')->orderBy('name')->get();
        $brands = Brand::select('id', 'name')->orderBy('name')->get();
        return view('admin.product-add', compact('categories', 'brands'));
    }

    public function product_store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:products,slug',
            'short_description' => 'required',
            'description' => 'required',
            'regular_price' => 'required',
            'sale_price' => 'required',
            'SKU' => 'required',
            'stock_status' => 'required|in:instock,outofstock',
            'featured' => 'required',
            'quantity' => 'required',
            'image' => 'required|mimes:png,jpg,jpeg|max:2048',
            'images' => 'nullable',
            'images.*' => 'mimes:jpg,jpeg,png|max:2048',
            'category_id' => 'required',
            'brand_id' => 'required',
            'sizes' => 'nullable|array',
            'colors' => 'nullable|array',
            // variant-specific input: array of maps
            'variants' => 'nullable|array',
            'variants.*.size' => 'nullable|string',
            'variants.*.color' => 'nullable|string',
            'variants.*.quantity' => 'required_with:variants.*|integer|min:0',
            'variants.*.SKU' => 'nullable|string',
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price;
        $product->SKU = $request->SKU;
        $product->stock_status = $request->stock_status;
        $product->featured = $request->featured;
        $product->quantity = $request->quantity;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
        $product->sizes = $request->sizes ?? [];
        $product->colors = $request->colors ?? [];

        // **persist basic product first so we have an ID for variants**
        $product->save();

        // sync variants if provided (now that $product->id is available)
        if ($request->filled('variants')) {
            // remove existing variants and recreate
            $product->variants()->delete();
            foreach ($request->variants as $vdata) {
                if (!isset($vdata['quantity'])) {
                    continue;
                }

                \App\Models\ProductVariant::create([
                    'product_id' => $product->id,
                    'size'       => $vdata['size'] ?? null,
                    'color'      => $vdata['color'] ?? null,
                    'SKU'        => $vdata['SKU'] ?? null,
                    'quantity'   => $vdata['quantity'],
                    'stock_status' => $vdata['quantity'] > 0 ? 'instock' : 'outofstock',
                ]);
            }
            // recalc aggregate values
            $agg = $product->variants()->sum('quantity');
            $product->quantity = $agg;
            $product->stock_status = $product->variants()->where('stock_status','instock')->exists() ? 'instock' : 'outofstock';
            // the changed quantities will be saved later after image/gallery handling
        }

        


        $current_timestamp = Carbon::now()->timestamp;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = $current_timestamp . '.' . $image->extension();
            $this->GenerateProductThumbnailImage($image, $imageName);
            $product->image = $imageName;
        }

        $gallery_arr = array();
        $gallery_images = "";
        $counter = 1;

        if ($request->hasFile('images')) {
            $allowedfileExtion = ['jpg', 'png', 'jpeg'];
            $files = $request->file('images');
            foreach ($files as $file) {
                $gextension = $file->getClientOriginalExtension();
                $gcheck = in_array($gextension, $allowedfileExtion);
                if ($gcheck) {
                    $gfileName = $current_timestamp . "-" . $counter . "." . $gextension;
                    $this->GenerateProductThumbnailImage($file, $gfileName);
                    array_push($gallery_arr, $gfileName);
                    $counter = $counter + 1;
                }
            }
            $gallery_images = implode(',', $gallery_arr);
        }
        $product->images = $gallery_images;
        $product->save();

        return redirect()->route('admin.products')->with('status', 'Product has been updated successfully');
    }

    public function GenerateProductThumbnailImage($image, $imageName)
    {
        $destinationPathThumbnail = public_path('uploads/products/thumbnails');
        $destinationPath = public_path('uploads/products');
        $img = Image::read($image->path());
        $img->cover(540, 689, 'top');
        $img->resize(540, 689, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath . '/' . $imageName);

        $img->resize(104, 104, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPathThumbnail . '/' . $imageName);
    }

    public function product_edit($id)
    {
        $product = Product::find($id);
        $categories = Category::select('id', 'name')->orderBy('name')->get();
        $brands = Brand::select('id', 'name')->orderBy('name')->get();
        return view('admin.product-edit', compact('product', 'categories', 'brands'));
    }

    public function product_update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:products,slug,' . $request->id,
            'short_description' => 'required',
            'description' => 'required',
            'regular_price' => 'required',
            'sale_price' => 'required',
            'SKU' => 'required',
            'stock_status' => 'required|in:instock,outofstock',
            'featured' => 'required',
            'quantity' => 'required',
            'image' => '|mimes:png,jpg,jpeg|max:2048',
            'category_id' => 'required',
            'brand_id' => 'required',
            'sizes' => 'nullable|array',
            'colors' => 'nullable|array',
            'variants' => 'nullable|array',
            'variants.*.size' => 'nullable|string',
            'variants.*.color' => 'nullable|string',
            'variants.*.quantity' => 'required_with:variants.*|integer|min:0',
            'variants.*.SKU' => 'nullable|string',
        ]);

        $product = Product::find($request->id);
        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price;
        $product->SKU = $request->SKU;
        $product->stock_status = $request->stock_status;
        $product->featured = $request->featured;
        $product->quantity = $request->quantity;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;

        // sync variants if provided
        if ($request->filled('variants')) {
            // remove existing variants and recreate
            $product->variants()->delete();
            foreach ($request->variants as $vdata) {
                if (!isset($vdata['quantity'])) {
                    continue;
                }

                \App\Models\ProductVariant::create([
                    'product_id' => $product->id,
                    'size'       => $vdata['size'] ?? null,
                    'color'      => $vdata['color'] ?? null,
                    'SKU'        => $vdata['SKU'] ?? null,
                    'quantity'   => $vdata['quantity'],
                    'stock_status' => $vdata['quantity'] > 0 ? 'instock' : 'outofstock',
                ]);
            }
            // recalc aggregate values
            $agg = $product->variants()->sum('quantity');
            $product->quantity = $agg;
            $product->stock_status = $product->variants()->where('stock_status','instock')->exists() ? 'instock' : 'outofstock';
        }

        $current_timestamp = Carbon::now()->timestamp;
        if ($request->hasFile('image')) {
            if (File::exists(public_path('uploads/products') . '/' . $product->image)) {
                File::delete(public_path('uploads/products') . '/' . $product->image);
            }
            if (File::exists(public_path('uploads/products/thumbnails') . '/' . $product->image)) {
                File::delete(public_path('uploads/products/thumbnails') . '/' . $product->image);
            }
            $image = $request->file('image');
            $imageName = $current_timestamp . '.' . $image->extension();
            $this->GenerateProductThumbnailImage($image, $imageName);
            $product->image = $imageName;
        }

        $gallery_arr = array();
        $gallery_images = "";
        $counter = 1;

        if ($request->hasFile('images')) {
            foreach (explode(',', $product->images) as $ofile) {
                if (File::exists(public_path('uploads/products') . '/' . $ofile)) {
                    File::delete(public_path('uploads/products') . '/' . $ofile);
                }
                if (File::exists(public_path('uploads/products/thumbnails') . '/' . $ofile)) {
                    File::delete(public_path('uploads/products/thumbnails') . '/' . $ofile);
                }
            }

            $allowedfileExtion = ['jpg', 'png', 'jpeg'];
            $files = $request->file('images');
            foreach ($files as $file) {
                $gextension = $file->getClientOriginalExtension();
                $gcheck = in_array($gextension, $allowedfileExtion);
                if ($gcheck) {
                    $gfileName = $current_timestamp . "-" . $counter . "." . $gextension;
                    $this->GenerateProductThumbnailImage($file, $gfileName);
                    array_push($gallery_arr, $gfileName);
                    $counter = $counter + 1;
                }
            }
            $gallery_images = implode(',', $gallery_arr);
            $product->images = $gallery_images;
        }

        if ($request->sizes !== null) $product->sizes = $request->sizes;
        if ($request->colors !== null) $product->colors = $request->colors;
        $product->save();
        return redirect()->route('admin.products')->with('status', 'Product has been updated successfully');
    }

    public function product_delete($id)
    {
        $product = Product::find($id);
        if (File::exists(public_path('uploads/products') . '/' . $product->image)) {
            File::delete(public_path('uploads/products') . '/' . $product->image);
        }
        if (File::exists(public_path('uploads/products/thumbnails') . '/' . $product->image)) {
            File::delete(public_path('uploads/products/thumbnails') . '/' . $product->image);
        }

        foreach (explode(',', $product->images) as $ofile) {
            if (File::exists(public_path('uploads/products') . '/' . $ofile)) {
                File::delete(public_path('uploads/products') . '/' . $ofile);
            }
            if (File::exists(public_path('uploads/products/thumbnails') . '/' . $ofile)) {
                File::delete(public_path('uploads/products/thumbnails') . '/' . $ofile);
            }
        }



        $product->delete();
        return redirect()->route('admin.products')->with('status', 'Product has been deleted successfully!');
    }

    public function coupons()
    {
        $coupons = Coupon::orderBy('expiry_date', 'DESC')->paginate(12);
        return view('admin.coupons', compact('coupons'));
    }

    public function coupon_add()
    {
        return view('admin.coupon-add');
    }

    public function coupon_store(Request $request)
    {
        $request->validate([
            'code' => 'required',
            'type' => 'required',
            'value' => 'required|numeric',
            'cart_value' => 'required|numeric',
            'expiry_date' => 'required|date'
        ]);

        $coupon = new Coupon();
        $coupon->code = $request->code;
        $coupon->type = $request->type;
        $coupon->value = $request->value;
        $coupon->cart_value = $request->cart_value;
        $coupon->expiry_date = $request->expiry_date;
        $coupon->save();
        return redirect()->route('admin.coupons')->with('status', 'Coupon has been added successfully!');
    }

    public function coupon_edit($id)
    {
        $coupon = Coupon::find($id);
        return view('admin.coupon-edit', compact('coupon'));
    }

    public function coupon_update(Request $request)
    {
        $request->validate([
            'code' => 'required',
            'type' => 'required',
            'value' => 'required|numeric',
            'cart_value' => 'required|numeric',
            'expiry_date' => 'required|date'
        ]);

        $coupon = Coupon::find($request->id);
        $coupon->code = $request->code;
        $coupon->type = $request->type;
        $coupon->value = $request->value;
        $coupon->cart_value = $request->cart_value;
        $coupon->expiry_date = $request->expiry_date;
        $coupon->save();
        return redirect()->route('admin.coupons')->with('status', 'Coupon has been updated successfully!');
    }

    public function coupon_delete($id)
    {
        $coupon = Coupon::find($id);
        $coupon->delete();
        return redirect()->route('admin.coupons')->with('status', 'Coupon has been deleted successfully!');
    }

    public function orders()
    {
        $orders = Order::orderBy('created_at', 'DESC')->paginate(12);
        return view('admin.orders', compact('orders'));
    }

    public function order_details($order_id)
    {
        $order = Order::find($order_id);
        $orderItems = OrderItem::where('order_id', $order_id)->orderBy('id')->paginate(12);
        $transaction = Transaction::where('order_id', $order_id)->first();
        return view('admin.order-details', compact('order', 'orderItems', 'transaction'));
    }

    public function update_order_status(Request $request)
    {
        #$order = Order::find($request->order_id);

        $order = Order::with('orderItems')->find($request->order_id);

        if (!$order) {
            return back()->with("error", "Order not found.");
        }

        $previousStatus = $order->status;
        $order->status = $request->order_status;

        if ($request->order_status == 'delivered') {
            $order->delivered_date = Carbon::now();
        } else if ($request->order_status == 'canceled') {
            $order->canceled_date = Carbon::now();

            if ($previousStatus != 'canceled') {

                foreach ($order->orderItems as $item) {
                    // handle variant if present
                    $variant = null;
                    if (!empty($item->options)) {
                        $opts = json_decode($item->options, true);
                        if (isset($opts['variant_id'])) {
                            $variant = \App\Models\ProductVariant::find($opts['variant_id']);
                        }
                    }

                    if ($variant) {
                        $variant->quantity += $item->quantity;
                        $variant->stock_status = 'instock';
                        $variant->save();
                        // update parent product aggregate
                        $product = $variant->product;
                        if ($product) {
                            $product->quantity = $product->variants()->sum('quantity');
                            $product->stock_status = $product->variants()->where('stock_status','instock')->exists() ? 'instock' : 'outofstock';
                            $product->save();
                        }
                    } else {
                        $product = Product::find($item->product_id);
                        if ($product) {
                            $product->quantity += $item->quantity;
                            $product->stock_status = 'instock';
                            $product->save();
                        }
                    }
                }
            }
        }
        $order->save();

        if ($request->order_status == 'delivered') {
            if ($previousStatus === 'canceled') {
                foreach ($order->orderItems as $item) {
                    $variant = null;
                    if (!empty($item->options)) {
                        $opts = json_decode($item->options, true);
                        if (isset($opts['variant_id'])) {
                            $variant = \App\Models\ProductVariant::find($opts['variant_id']);
                        }
                    }
                    if ($variant) {
                        $variant->quantity = max(0, $variant->quantity - $item->quantity);
                        if ($variant->quantity > 0) {
                            $variant->stock_status = 'instock';
                        } else {
                            $variant->stock_status = 'outofstock';
                        }
                        $variant->save();
                        // update product aggregate
                        $product = $variant->product;
                        if ($product) {
                            $product->quantity = $product->variants()->sum('quantity');
                            $product->stock_status = $product->variants()->where('stock_status','instock')->exists() ? 'instock' : 'outofstock';
                            $product->save();
                        }
                    } else {
                        $product = Product::find($item->product_id);
                        if ($product) {
                            $product->quantity -= $item->quantity;
                            if ($product->quantity > 0) {
                                $product->stock_status = 'instock';
                            } else {
                                $product->stock_status = 'outofstock';
                            }
                            $product->save();
                        }
                    }
                }
            }
            $transaction = Transaction::where('order_id', $request->order_id)->first();
            $transaction->status = 'approved';
            $transaction->save();
        }
        return back()->with("status", "Status changed successfully!");
    }

    public function slides()
    {
        $slides = Slide::orderby('id', 'DESC')->paginate(12);
        return view('admin.slides', compact('slides'));
    }

    public function slide_add()
    {
        return view('admin.slide-add');
    }

    public function slide_store(Request $request)
    {
        $request->validate([
            'tagline' => 'required',
            'title' => 'required',
            'subtitle' => 'required',
            'link' => 'required',
            'status' => 'required',
            'image' => 'required|mimes:png,jpg,jpeg|max:2048'
        ]);
        $slide = new Slide();
        $slide->tagline = $request->tagline;
        $slide->title = $request->title;
        $slide->subtitle = $request->subtitle;
        $slide->link = $request->link;
        $slide->status = $request->status;

        $image = $request->file('image');
        $file_extension = $request->file('image')->extension();
        $file_name = Carbon::now()->timestamp . '.' . $file_extension;
        $this->GenerateSlideThumbnailsImage($image, $file_name);
        $slide->image = $file_name;
        $slide->save();
        return redirect()->route('admin.slides')->with("status", "Slide added successfully!");
    }

    public function GenerateSlideThumbnailsImage($image, $imageName)
    {
        $destinationPath = public_path('uploads/slides');
        $img = Image::read($image->path());
        $img->cover(400, 690, 'top');
        $img->resize(400, 690, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath . '/' . $imageName);
    }

    public function slide_edit($id)
    {
        $slide = Slide::find($id);
        return view('admin.slide-edit', compact('slide'));
    }

    public function slide_update(Request $request)
    {
        $request->validate([
            'tagline' => 'required',
            'title' => 'required',
            'subtitle' => 'required',
            'link' => 'required',
            'status' => 'required',
            'image' => 'mimes:png,jpg,jpeg|max:2048'
        ]);
        $slide = Slide::find($request->id);
        $slide->tagline = $request->tagline;
        $slide->title = $request->title;
        $slide->subtitle = $request->subtitle;
        $slide->link = $request->link;
        $slide->status = $request->status;

        if ($request->hasFile('image')) {
            if (File::exists(public_path('uploads/slides') . '/' . $slide->image)) {
                File::delete(public_path('uploads/slides') . '/' . $slide->image);
            };
            $image = $request->file('image');
            $file_extension = $request->file('image')->extension();
            $file_name = Carbon::now()->timestamp . '.' . $file_extension;
            $this->GenerateSlideThumbnailsImage($image, $file_name);
            $slide->image = $file_name;
        }
        $slide->save();
        return redirect()->route('admin.slides')->with("status", "Slide edited successfully!");
    }

    public function slide_delete($id)
    {
        $slide = Slide::find($id);
        if (File::exists(public_path('uploads/slides') . '/' . $slide->image)) {
            File::delete(public_path('uploads/slides') . '/' . $slide->image);
        }
        $slide->delete();
        return redirect()->route('admin.slides')->with("status", "Slide deleted successfully!");
    }

    public function contact()
    {
        $contacts = Contact::orderBy('created_at', 'DESC')->paginate(10);
        return view('admin.contacts', compact('contacts'));
    }

    public function contact_delete($id)
    {
        $contact = Contact::find($id);
        $contact->delete();
        return redirect()->route('admin.contacts')->with('status', 'Message has been deleted successfully!');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $results = Product::where('name', 'LIKE', "%$query%")->get()->take(8);
        return response()->json($results);
    }
    
    public function users(Request $request)
{
    $q = $request->query('q');

    $users = User::query()
        ->when($q, function ($query) use ($q) {
            $query->where('name', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%");
        })
        ->withCount('orders')   // remove this line if you don’t have orders() relation
        ->orderBy('id', 'DESC')
        ->paginate(10)
        ->withQueryString();

    return view('admin.users', compact('users', 'q'));
}


public function user_edit(User $user)
{
    $user->load('address');
    return view('admin.user-edit', compact('user'));
}

public function user_update(Request $request, User $user)
{
    $request->validate([
        'name'   => 'required|string|max:255',
        'mobile' => 'nullable|string|max:50',
        'email'  => 'required|email|max:255|unique:users,email,' . $user->id,
        'password' => 'nullable|string|min:8|confirmed',

        'address'  => 'nullable|string|max:255',
        'city'     => 'nullable|string|max:255',
        'county'   => 'nullable|string|max:255',
        'country'  => 'nullable|string|max:255',
        'postcode' => 'nullable|string|max:50',
    ]);

    $user->name = $request->name;
    $user->mobile = $request->mobile;
    $user->email = $request->email;

    if ($request->filled('password')) {
        $user->password = bcrypt($request->password);
    }

    $user->save();

    \App\Models\Address::updateOrCreate(
        ['user_id' => $user->id],
        [
            'address' => $request->address,
            'city'    => $request->city,
            'state'   => $request->county,
            'country' => $request->country,
            'zip'     => $request->postcode,
        ]
    );

    return redirect()->route('admin.users')->with('status', 'User updated successfully!');
}

public function user_delete(User $user)
{
    if ($user->utype === 'ADM' || $user->id === auth()->id()) {
        return back()->with('error', 'Admin users cannot be deleted.');
    }

    $user->delete();

    return redirect()->route('admin.users')->with('status', 'User deleted successfully!');
}
}
