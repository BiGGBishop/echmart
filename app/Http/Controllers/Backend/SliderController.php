<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use Illuminate\Support\Facades\Response;
use Intervention\Image\Facades\Image;

class SliderController extends Controller
{
    public function allSlider()
    {
        $sliders = Slider::latest()->get();
        return Response::json(['sliders' => $sliders]);
    }

    public function storeSlider(Request $request)
    {
        $image = $request->file('slider_image');
        $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
        Image::make($image)->resize(2376, 807)->save('upload/slider/' . $name_gen);
        $save_url = 'upload/slider/' . $name_gen;

        Slider::insert([
            'slider_title' => $request->slider_title,
            'short_title' => $request->short_title,
            'slider_image' => $save_url,
        ]);

        return Response::json(['message' => 'Slider Inserted Successfully']);
    }

    public function updateSlider(Request $request)
    {
        $slider_id = $request->id;
        $old_img = $request->old_image;

        if ($request->file('slider_image')) {
            $image = $request->file('slider_image');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            Image::make($image)->resize(2376, 807)->save('upload/slider/' . $name_gen);
            $save_url = 'upload/slider/' . $name_gen;

            if (file_exists($old_img)) {
                unlink($old_img);
            }

            Slider::findOrFail($slider_id)->update([
                'slider_title' => $request->slider_title,
                'short_title' => $request->short_title,
                'slider_image' => $save_url,
            ]);

            return Response::json(['message' => 'Slider Updated with image Successfully']);
        } else {
            Slider::findOrFail($slider_id)->update([
                'slider_title' => $request->slider_title,
                'short_title' => $request->short_title,
            ]);

            return Response::json(['message' => 'Slider Updated without image Successfully']);
        }
    }

    public function deleteSlider($id)
    {
        $slider = Slider::findOrFail($id);
        $img = $slider->slider_image;
        unlink($img);

        Slider::findOrFail($id)->delete();

        return Response::json(['message' => 'Slider Deleted Successfully']);
    }
}