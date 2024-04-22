<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use Intervention\Image\Facades\Image;


class BannerController extends Controller
{
    public function allBanners()
    {
        $banners = Banner::latest()->get();
        return response()->json(['banners' => $banners]);
    }

    public function storeBanner(Request $request)
    {
        $image = $request->file('banner_image');
        $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
        Image::make($image)->resize(768, 450)->save('upload/banner/' . $name_gen);
        $save_url = 'upload/banner/' . $name_gen;

        Banner::insert([
            'banner_title' => $request->banner_title,
            'banner_url' => $request->banner_url,
            'banner_image' => $save_url,
        ]);

        return response()->json(['message' => 'Banner Inserted Successfully']);
    }

    public function updateBanner(Request $request)
    {
        $banner_id = $request->id;
        $old_img = $request->old_image;

        if ($request->file('banner_image')) {
            $image = $request->file('banner_image');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            Image::make($image)->resize(768, 450)->save('upload/banner/' . $name_gen);
            $save_url = 'upload/banner/' . $name_gen;

            if (file_exists($old_img)) {
                unlink($old_img);
            }

            Banner::findOrFail($banner_id)->update([
                'banner_title' => $request->banner_title,
                'banner_url' => $request->banner_url,
                'banner_image' => $save_url,
            ]);

            return response()->json(['message' => 'Banner Updated with image Successfully']);
        } else {
            Banner::findOrFail($banner_id)->update([
                'banner_title' => $request->banner_title,
                'banner_url' => $request->banner_url,
            ]);

            return response()->json(['message' => 'Banner Updated without image Successfully']);
        }
    }

    public function deleteBanner($id)
    {
        $banner = Banner::findOrFail($id);
        $img = $banner->banner_image;
        unlink($img);

        Banner::findOrFail($id)->delete();

        return response()->json(['message' => 'Banner Deleted Successfully']);
    }
}