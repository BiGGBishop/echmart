<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Support\Facades\Response;

class SubCategoryController extends Controller
{
    public function allSubCategory()
    {
        $subcategories = SubCategory::latest()->get();
        return Response::json(['subcategories' => $subcategories]);
    }

    public function storeSubCategory(Request $request)
    {
        SubCategory::insert([
            'category_id' => $request->category_id,
            'subcategory_name' => $request->subcategory_name,
            'subcategory_slug' => strtolower(str_replace(' ', '-', $request->subcategory_name)),
        ]);

        return Response::json(['message' => 'SubCategory Inserted Successfully']);
    }

    public function updateSubCategory(Request $request)
    {
        $subcat_id = $request->id;

        SubCategory::findOrFail($subcat_id)->update([
            'category_id' => $request->category_id,
            'subcategory_name' => $request->subcategory_name,
            'subcategory_slug' => strtolower(str_replace(' ', '-', $request->subcategory_name)),
        ]);

        return Response::json(['message' => 'SubCategory Updated Successfully']);
    }

    public function deleteSubCategory($id)
    {
        SubCategory::findOrFail($id)->delete();
        return Response::json(['message' => 'SubCategory Deleted Successfully']);
    }

    public function getSubCategory($category_id)
    {
        $subcat = SubCategory::where('category_id', $category_id)->orderBy('subcategory_name', 'ASC')->get();
        return Response::json(['subcategories' => $subcat]);
    }
}