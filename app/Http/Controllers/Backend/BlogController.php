<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;

class BlogController extends Controller
{
    // Blog Category Methods
    public function allBlogCategories()
    {
        $blogcategories = BlogCategory::latest()->get();
        return response()->json(['blogcategories' => $blogcategories]);
    }

    public function storeBlogCategory(Request $request)
    {
        BlogCategory::insert([
            'blog_category_name' => $request->blog_category_name,
            'blog_category_slug' => strtolower(str_replace(' ', '-', $request->blog_category_name)),
            'created_at' => Carbon::now(),
        ]);

        return response()->json(['message' => 'Blog Category Inserted Successfully']);
    }

    public function updateBlogCategory(Request $request)
    {
        $blog_id = $request->id;

        BlogCategory::findOrFail($blog_id)->update([
            'blog_category_name' => $request->blog_category_name,
            'blog_category_slug' => strtolower(str_replace(' ', '-', $request->blog_category_name)),
        ]);

        return response()->json(['message' => 'Blog Category Updated Successfully']);
    }

    public function deleteBlogCategory($id)
    {
        BlogCategory::findOrFail($id)->delete();

        return response()->json(['message' => 'Blog Category Deleted Successfully']);
    }

    // Blog Post Methods
    public function allBlogPosts()
    {
        $blogposts = BlogPost::latest()->get();
        return response()->json(['blogposts' => $blogposts]);
    }

    public function storeBlogPost(Request $request)
    {
        $image = $request->file('post_image');
        $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
        Image::make($image)->resize(1103, 906)->save('upload/blog/' . $name_gen);
        $save_url = 'upload/blog/' . $name_gen;

        BlogPost::insert([
            'category_id' => $request->category_id,
            'post_title' => $request->post_title,
            'post_slug' => strtolower(str_replace(' ', '-', $request->post_title)),
            'post_short_description' => $request->post_short_description,
            'post_long_description' => $request->post_long_description,
            'post_image' => $save_url,
            'created_at' => Carbon::now(),
        ]);

        return response()->json(['message' => 'Blog Post Inserted Successfully']);
    }

    public function editBlogPost($id)
    {
        $blogcategories = BlogCategory::latest()->get();
        $blogpost = BlogPost::findOrFail($id);
        return response()->json(['blogcategories' => $blogcategories, 'blogpost' => $blogpost]);
    }

    public function updateBlogPost(Request $request)
    {
        $post_id = $request->id;
        $old_img = $request->old_image;

        if ($request->file('post_image')) {
            $image = $request->file('post_image');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            Image::make($image)->resize(1103, 906)->save('upload/blog/' . $name_gen);
            $save_url = 'upload/blog/' . $name_gen;

            if (file_exists($old_img)) {
                unlink($old_img);
            }

            BlogPost::findOrFail($post_id)->update([
                'category_id' => $request->category_id,
                'post_title' => $request->post_title,
                'post_slug' => strtolower(str_replace(' ', '-', $request->post_title)),
                'post_short_description' => $request->post_short_description,
                'post_long_description' => $request->post_long_description,
                'post_image' => $save_url,
                'updated_at' => Carbon::now(),
            ]);

            return response()->json(['message' => 'Blog Post Updated with image Successfully']);
        } else {
            BlogPost::findOrFail($post_id)->update([
                'category_id' => $request->category_id,
                'post_title' => $request->post_title,
                'post_slug' => strtolower(str_replace(' ', '-', $request->post_title)),
                'post_short_description' => $request->post_short_description,
                'post_long_description' => $request->post_long_description,
                'updated_at' => Carbon::now(),
            ]);

            return response()->json(['message' => 'Blog Post Updated without image Successfully']);
        }
    }

    public function deleteBlogPost($id)
    {
        $blogpost = BlogPost::findOrFail($id);
        $img = $blogpost->post_image;
        unlink($img);

        BlogPost::findOrFail($id)->delete();

        return response()->json(['message' => 'Blog Post Deleted Successfully']);
    }

    // Frontend Blog Methods
    public function allBlog()
    {
        $blogcategories = BlogCategory::latest()->get();
        $blogposts = BlogPost::latest()->get();
        return response()->json(['blogcategories' => $blogcategories, 'blogposts' => $blogposts]);
    }

    public function blogDetails($id, $slug)
    {
        $blogcategories = BlogCategory::latest()->get();
        $blogdetails = BlogPost::findOrFail($id);
        $breadcat = BlogCategory::where('id', $id)->get();
        return response()->json(['blogcategories' => $blogcategories, 'blogdetails' => $blogdetails, 'breadcat' => $breadcat]);
    }

    public function blogPostCategory($id, $slug)
    {
        $blogcategories = BlogCategory::latest()->get();
        $blogposts = BlogPost::where('category_id', $id)->get();
        $breadcat = BlogCategory::where('id', $id)->get();
        return response()->json(['blogcategories' => $blogcategories, 'blogposts' => $blogposts, 'breadcat' => $breadcat]);
    }
}