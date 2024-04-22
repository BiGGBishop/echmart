<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class RoleController extends Controller
{
    public function allPermission()
    {
        $permissions = Permission::all();
        return Response::json(['permissions' => $permissions]);
    }

    public function storePermission(Request $request)
    {
        $role = Permission::create([
            'name' => $request->name,
            'group_name' => $request->group_name,
        ]);

        return Response::json(['message' => 'Permission Inserted Successfully']);
    }

    public function updatePermission(Request $request)
    {
        $per_id = $request->id;

        Permission::findOrFail($per_id)->update([
            'name' => $request->name,
            'group_name' => $request->group_name,
        ]);

        return Response::json(['message' => 'Permission Updated Successfully']);
    }

    public function deletePermission($id)
    {
        Permission::findOrFail($id)->delete();

        return Response::json(['message' => 'Permission Deleted Successfully']);
    }

    public function allRoles()
    {
        $roles = Role::all();
        return Response::json(['roles' => $roles]);
    }

    public function storeRoles(Request $request)
    {
        $role = Role::create([
            'name' => $request->name,
        ]);

        return Response::json(['message' => 'Roles Inserted Successfully']);
    }

    public function updateRoles(Request $request)
    {
        $role_id = $request->id;

        Role::findOrFail($role_id)->update([
            'name' => $request->name,
        ]);

        return Response::json(['message' => 'Roles Updated Successfully']);
    }

    public function deleteRoles($id)
    {
        Role::findOrFail($id)->delete();

        return Response::json(['message' => 'Roles Deleted Successfully']);
    }

    public function addRolesPermission()
    {
        $roles = Role::all();
        $permissions = Permission::all();
        $permission_groups = User::getpermissionGroups();
        return Response::json(['roles' => $roles, 'permissions' => $permissions, 'permission_groups' => $permission_groups]);
    }

    public function rolePermissionStore(Request $request)
    {
        $data = array();
        $permissions = $request->permission;

        foreach ($permissions as $key => $item) {
            $data['role_id'] = $request->role_id;
            $data['permission_id'] = $item;

            DB::table('role_has_permissions')->insert($data);
        }

        return Response::json(['message' => 'Role Permission Added Successfully']);
    }

    public function allRolesPermission()
    {
        $roles = Role::all();
        return Response::json(['roles' => $roles]);
    }

    public function adminRolesUpdate(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $permissions = $request->permission;

        if (!empty($permissions)) {
            $role->syncPermissions($permissions);
        }

        return Response::json(['message' => 'Role Permission Updated Successfully']);
    }

    public function adminRolesDelete($id)
    {
        $role = Role::findOrFail($id);
        if (!is_null($role)) {
            $role->delete();
        }

        return Response::json(['message' => 'Role Permission Deleted Successfully']);
    }
}