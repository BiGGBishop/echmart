<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\MultiImg;
use App\Models\Brand;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Response;

class IndexController extends Controller
{

    public function index()
    {
        $skip_category_0 = Category::skip(0)->first();
        $skip_product_0 = Product::where('status', 1)->where('category_id', $skip_category_0->id)->orderBy('id', 'DESC')->limit(5)->get();

        $skip_category_2 = Category::skip(2)->first();
        $skip_product_2 = Product::where('status', 1)->where('category_id', $skip_category_2->id)->orderBy('id', 'DESC')->limit(5)->get();

        $skip_category_7 = Category::skip(7)->first();
        $skip_product_7 = Product::where('status', 1)->where('category_id', $skip_category_7->id)->orderBy('id', 'DESC')->limit(5)->get();

        $hot_deals = Product::where('hot_deals', 1)->where('discount_price', '!=', NULL)->orderBy('id', 'DESC')->limit(3)->get();

        $special_offer = Product::where('special_offer', 1)->orderBy('id', 'DESC')->limit(3)->get();

        $new = Product::where('status', 1)->orderBy('id', 'DESC')->limit(3)->get();

        $special_deals = Product::where('special_deals', 1)->orderBy('id', 'DESC')->limit(3)->get();

        return Response::json([
            'skip_category_0' => $skip_category_0,
            'skip_product_0' => $skip_product_0,
            'skip_category_2' => $skip_category_2,
            'skip_product_2' => $skip_product_2,
            'skip_category_7' => $skip_category_7,
            'skip_product_7' => $skip_product_7,
            'hot_deals' => $hot_deals,
            'special_offer' => $special_offer,
            'new' => $new,
            'special_deals' => $special_deals,
        ]);
    }

    public function productDetails($id, $slug)
    {
        $product = Product::findOrFail($id);

        $color = $product->product_color;
        $product_color = explode(',', $color);

        $size = $product->product_size;
        $product_size = explode(',', $size);

        $multiImage = MultiImg::where('product_id', $id)->get();

        $cat_id = $product->category_id;
        $relatedProduct = Product::where('category_id', $cat_id)->where('id', '!=', $id)->orderBy('id', 'DESC')->limit(4)->get();

        return Response::json([
            'product' => $product,
            'product_color' => $product_color,
            'product_size' => $product_size,
            'multiImage' => $multiImage,
            'relatedProduct' => $relatedProduct,
        ]);
    }

    public function vendorDetails($id)
    {
        $vendor = User::findOrFail($id);
        $vproduct = Product::where('vendor_id', $id)->get();
        return Response::json(['vendor' => $vendor, 'vproduct' => $vproduct]);
    }

    public function vendorAll()
    {
        $vendors = User::where('status', 'active')->where('role', 'vendor')->orderBy('id', 'DESC')->get();
        return Response::json(['vendors' => $vendors]);
    }

    // ... (your existing code for other methods)

    public function productViewAjax($id)
    {
        $product = Product::with('category', 'brand')->findOrFail($id);
        $color = $product->product_color;
        $product_color = explode(',', $color);

        $size = $product->product_size;
        $product_size = explode(',', $size);

        return Response::json([
            'product' => $product,
            'color' => $product_color,
            'size' => $product_size,
        ]);
    }

    public function productSearch(Request $request)
    {
        $request->validate(['search' => "required"]);

        $item = $request->search;
        $categories = Category::orderBy('category_name', 'ASC')->get();
        $products = Product::where('product_name', 'LIKE', "%$item%")->get();
        $newProduct = Product::orderBy('id', 'DESC')->limit(3)->get();

        return Response::json([
            'products' => $products,
            'item' => $item,
            'categories' => $categories,
            'newProduct' => $newProduct,
        ]);
    }

    public function searchProduct(Request $request)
    {
        $request->validate(['search' => "required"]);

        $item = $request->search;
        $products = Product::where('product_name', 'LIKE', "%$item%")->select('product_name', 'product_slug', 'product_thambnail', 'selling_price', 'id')->limit(6)->get();

        return Response::json(['products' => $products]);
    }
}