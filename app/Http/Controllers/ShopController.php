<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Review;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $size = $request->query('size') ? $request->query('size') : 12;
        $o_column = "";
        $o_order = "";
        $order = $request->query('order') ? $request->query('order') : -1;
        $f_brands = $request->query('brands');
        $f_categories = $request->query('categories');
        $fsizes = $request->query('sizes');
        $fcolors = $request->query('colors');
        $min_price = $request->query('min') ? $request->query('min') : 1;
        $max_price = $request->query('max') ? $request->query('max') : 500;
        switch ($order) {
            case 1:
                $o_column = 'created_at';
                $o_order = 'DESC';
                break;
            case 2:
                $o_column = 'created_at';
                $o_order = 'ASC';
                break;
            case 3:
                $o_column = 'sale_price';
                $o_order = 'ASC';
                break;
            case 4:
                $o_column = 'sale_price';
                $o_order = 'DESC';
                break;
            default:
                $o_column = 'id';
                $o_order = 'DESC';
        }
        $brands = Brand::orderBy('name', 'ASC')->get();
        $categories = Category::orderBy('name', 'ASC')->get();

        $brandIds = array_values(array_filter(array_map('intval', explode(',', (string) $f_brands))));
        $categoryIds = array_values(array_filter(array_map('intval', explode(',', (string) $f_categories))));
        $sizes = array_values(array_filter(array_map('trim', explode(',', (string) $fsizes))));
        $colors = array_values(array_filter(array_map('trim', explode(',', (string) $fcolors))));

        $min_price = is_numeric($min_price) ? (float) $min_price : 1;
        $max_price = is_numeric($max_price) ? (float) $max_price : 500;

        $products = Product::query()
            ->with('reviews')
            ->when($brandIds, function ($query) use ($brandIds) {
                $query->whereIn('brand_id', $brandIds);
            })
            ->when($categoryIds, function ($query) use ($categoryIds) {
                $query->whereIn('category_id', $categoryIds);
            })
            ->where(function ($query) use ($min_price, $max_price) {
                $query->whereBetween('regular_price', [$min_price, $max_price])
                    ->orWhereBetween('sale_price', [$min_price, $max_price]);
            })
            // require that product (or its variants) actually has stock
            ->where(function ($query) {
                $query->where('quantity', '>', 0)
                      ->orWhereHas('variants', function ($q) {
                          $q->where('quantity', '>', 0);
                      });
            })
            ->when($colors, function ($query) use ($colors) {
                // when filtering by colors we should make sure at least one variant matching color has stock
                $query->whereHas('variants', function ($q) use ($colors) {
                    $q->where(function ($q2) use ($colors) {
                        foreach ($colors as $color) {
                            $q2->orWhere('color', $color);
                        }
                    })->where('quantity', '>', 0);
                });
            })
            ->when($sizes, function ($query) use ($sizes) {
                $query->whereHas('variants', function ($q) use ($sizes) {
                    $q->where(function ($q2) use ($sizes) {
                        foreach ($sizes as $s) {
                            $q2->orWhere('size', $s);
                        }
                    })->where('quantity', '>', 0);
                });
            })
            ->orderBy($o_column, $o_order)
            ->paginate($size);

        return view('shop', compact(
            'products',
            'size',
            'order',
            'brands',
            'f_brands',
            'categories',
            'f_categories',
            'fsizes',
            'fcolors',
            'min_price',
            'max_price'
        ));
    }

    public function product_details($product_slug)
    {
        $product = Product::with(['reviews','variants'])->where('slug',$product_slug)->first();
        $rproducts = Product::where('slug','<>',$product_slug)->take(8)->get();
        return view('details',compact('product','rproducts'));
    }

    public function store_review(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'name' => 'required',
            'email' => 'required|email',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required'
        ]);

        Review::create([
            'product_id' => $request->product_id,
            'name' => $request->name,
            'email' => $request->email,
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        return back()->with('success', 'Review submitted successfully!');
    }
}
