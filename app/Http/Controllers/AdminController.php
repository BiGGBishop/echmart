<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission; 

use App\Notifications\VendorApproveNotification;

class AdminController extends Controller
{
    public function AdminProfile(){

        $id = Auth::user()->id;
        $adminData = User::find($id);
        return response()->json(['data' => $adminData]);
    }

    public function AdminProfileStore(Request $request){

        $id = Auth::user()->id;
        $data = User::find($id);
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address; 


        if ($request->file('photo')) {
            $file = $request->file('photo');
            @unlink(public_path('upload/admin_images/'.$data->photo));
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/admin_images'),$filename);
            $data['photo'] = $filename;
        }

        $data->save();

        return response()->json(['message' => 'Admin Profile Updated Successfully']);
    }

    public function AdminUpdatePassword(Request $request){
        // Validation 
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed', 
        ]);

        // Match The Old Password
        if (!Hash::check($request->old_password, auth::user()->password)) {
            return back()->with("error", "Old Password Doesn't Match!!");
        }

        // Update The new password 
        User::whereId(auth()->user()->id)->update([
            'password' => Hash::make($request->new_password)

        ]);
        return response()->json(['message' => 'Password Changed Successfully']);
    }

    public function InactiveVendor(){
        $inActiveVendor = User::where('status','inactive')->where('role','vendor')->latest()->get();
        return response()->json(['data' => $inActiveVendor]);
    }

    public function ActiveVendor(){
        $ActiveVendor = User::where('status','active')->where('role','vendor')->latest()->get();
        return response()->json(['data' => $ActiveVendor]);
    }

    public function InactiveVendorDetails($id){

        $inactiveVendorDetails = User::findOrFail($id);
        return response()->json(['data' => $inactiveVendorDetails]);

    }

    public function ActiveVendorApprove(Request $request){

        $verdor_id = $request->id;
        $user = User::findOrFail($verdor_id)->update([
            'status' => 'active',
        ]);

        $notification = array(
            'message' => 'Vendor Active Successfully',
            'alert-type' => 'success'
        );

         $vuser = User::where('role','vendor')->get();
        Notification::send($vuser, new VendorApproveNotification($request));
        return response()->json($notification);    
    }

    public function ActiveVendorDetails($id){

        $activeVendorDetails = User::findOrFail($id);
        return response()->json(['data' => $activeVendorDetails]);
    }


     public function InActiveVendorApprove(Request $request){

        $verdor_id = $request->id;
        $user = User::findOrFail($verdor_id)->update([
            'status' => 'inactive',
        ]);

        $notification = array(
            'message' => 'Vendor InActive Successfully',
            'alert-type' => 'success'
        );

        return response()->json($notification);
     }

    public function AllAdmin(){
        $alladminuser = User::where('role','admin')->latest()->get();
        return response()->json(['alladminuser' => $alladminuser]);
    }

    public function AddAdmin(){
        $roles = Role::all();
        return response()->json(['roles' => $roles]);
    }

    public function AdminUserStore(Request $request){

        $user = new User();
        $user->username = $request->username;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->password = Hash::make($request->password);
        $user->role = 'admin';
        $user->status = 'active';
        $user->save();

        if ($request->roles) {
            $user->assignRole($request->roles);
        }

         $notification = array(
            'message' => 'New Admin User Inserted Successfully',
            'alert-type' => 'success'
        );

        return response()->json($notification);
    } 




    public function EditAdminRole($id){

        $user = User::findOrFail($id);
        $roles = Role::all();
        return response()->json(['user' => $user, 'roles' => $roles]);
    }


    public function AdminUserUpdate(Request $request,$id){

        $user = User::findOrFail($id);
        $user->username = $request->username;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address; 
        $user->role = 'admin';
        $user->status = 'active';
        $user->save();

        $user->roles()->detach();
        if ($request->roles) {
            $user->assignRole($request->roles);
        }

         $notification = array(
            'message' => 'New Admin User Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.admin')->with($notification);
    }


    public function DeleteAdminRole($id){

        $user = User::findOrFail($id);
        if (!is_null($user)) {
            $user->delete();
        }
 
         $notification = array(
            'message' => 'Admin User Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
}