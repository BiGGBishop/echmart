<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShipDivision;
use App\Models\ShipDistricts;
use App\Models\ShipState;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;

class ShippingAreaController extends Controller
{
    public function allDivision()
    {
        $division = ShipDivision::latest()->get();
        return Response::json(['division' => $division]);
    }

    public function storeDivision(Request $request)
    {
        ShipDivision::insert([
            'division_name' => $request->division_name,
        ]);

        return Response::json(['message' => 'ShipDivision Inserted Successfully']);
    }

    public function updateDivision(Request $request)
    {
        $division_id = $request->id;

        ShipDivision::findOrFail($division_id)->update([
            'division_name' => $request->division_name,
        ]);

        return Response::json(['message' => 'ShipDivision Updated Successfully']);
    }

    public function deleteDivision($id)
    {
        ShipDivision::findOrFail($id)->delete();

        return Response::json(['message' => 'ShipDivision Deleted Successfully']);
    }

    public function allDistrict()
    {
        $district = ShipDistricts::latest()->get();
        return Response::json(['district' => $district]);
    }

    public function storeDistrict(Request $request)
    {
        ShipDistricts::insert([
            'division_id' => $request->division_id,
            'district_name' => $request->district_name,
        ]);

        return Response::json(['message' => 'ShipDistricts Inserted Successfully']);
    }

    public function updateDistrict(Request $request)
    {
        $district_id = $request->id;

        ShipDistricts::findOrFail($district_id)->update([
            'division_id' => $request->division_id,
            'district_name' => $request->district_name,
        ]);

        return Response::json(['message' => 'ShipDistricts Updated Successfully']);
    }

    public function deleteDistrict($id)
    {
        ShipDistricts::findOrFail($id)->delete();

        return Response::json(['message' => 'ShipDistricts Deleted Successfully']);
    }

    public function allState()
    {
        $state = ShipState::latest()->get();
        return Response::json(['state' => $state]);
    }

    public function getDistrict($division_id)
    {
        $dist = ShipDistricts::where('division_id', $division_id)->orderBy('district_name', 'ASC')->get();
        return Response::json($dist);
    }

    public function storeState(Request $request)
    {
        ShipState::insert([
            'division_id' => $request->division_id,
            'district_id' => $request->district_id,
            'state_name' => $request->state_name,
        ]);

        return Response::json(['message' => 'ShipState Inserted Successfully']);
    }

    public function updateState(Request $request)
    {
        $state_id = $request->id;

        ShipState::findOrFail($state_id)->update([
            'division_id' => $request->division_id,
            'district_id' => $request->district_id,
            'state_name' => $request->state_name,
        ]);

        return Response::json(['message' => 'ShipState Updated Successfully']);
    }

    public function deleteState($id)
    {
        ShipState::findOrFail($id)->delete();

        return Response::json(['message' => 'ShipState Deleted Successfully']);
    }
}