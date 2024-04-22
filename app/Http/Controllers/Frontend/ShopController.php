<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Response;

class ShopController extends Controller
{
    public function index()
    {
        $products = Product::query();

        if (!empty(request('category'))) {
            $slugs = explode(',', request('category'));
            $catIds = Category::select('id')->whereIn('category_slug', $slugs)->pluck('id')->toArray();
            $products = Product::whereIn('category_id', $catIds)->get();
        }

        if (!empty(request('brand'))) {
            $slugs = explode(',', request('brand'));
            $brandIds = Brand::select('id')->whereIn('brand_slug', $slugs)->pluck('id')->toArray();
            $products = Product::whereIn('brand_id', $brandIds)->get();
        } else {
            $products = Product::where('status', 1)->orderBy('id', 'DESC')->get();
        }

        // Price Range
        if (!empty(request('price'))) {
            $price = explode('-', request('price'));
            $products = $products->whereBetween('selling_price', $price);
        }

        $categories = Category::orderBy('category_name', 'ASC')->get();
        $brands = Brand::orderBy('brand_name', 'ASC')->get();
        $newProduct = Product::orderBy('id', 'DESC')->limit(3)->get();

        return Response::json([
            'products' => $products,
            'categories' => $categories,
            'newProduct' => $newProduct,
            'brands' => $brands,
        ]);
    }

    public function filter(Request $request)
    {
        $data = $request->all();

        $catUrl = $this->buildUrl($data['category'], 'category');
        $brandUrl = $this->buildUrl($data['brand'], 'brand');
        $priceRangeUrl = $this->buildUrl($data['price_range'], 'price');

        return Response::json([
            'redirect_url' => route('shop.page', $catUrl . $brandUrl . $priceRangeUrl),
        ]);
    }
}