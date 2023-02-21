<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RoleAndPermissionController extends Controller
{

    public function listRole()
    {
        $roles = Role::latest('id', 'desc')->paginate(10);

        return response()->json([
            'status' => true,
            'msg' => 'Role listing',
            'data' => $roles
        ]);
    }

    public function storeRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'slug' => ['required', 'unique:roles'],
        ]);

        if ($validator->fails()){
            return response()->json([
                'status' => false,
                'msg' => $validator->errors()->first(),
                'data' => []
            ]);
        }

        $role = Role::create($request->only('name','slug'));

        return response()->json([
            'status' => true,
            'msg' => 'Role created.',
            'data' => $role
        ]);
    }

    public function showRole(Role $role)
    {
        return response()->json([
            'status' => true,
            'msg' => 'Role view.',
            'data' => $role
        ]);
    }

    public function updateRole(Request $request, Role $role)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'slug' => [
                'required',
                Rule::unique('roles')->whereNot('id', $role->id)
            ],
        ]);

        if ($validator->fails()){
            return response()->json([
                'status' => false,
                'msg' => $validator->errors()->first(),
                'data' => []
            ]);
        }

        $role->fill($request->only('name','slug'))->save();

        return response()->json([
            'status' => true,
            'msg' => 'Role updated.',
            'data' => $role
        ]);
    }

    public function destroyRole(Role $role)
    {
        $role->delete();

        return response()->json([
            'status' => true,
            'msg' => 'Role deleted.',
            'data' => []
        ]);
    }

    public function listPermission()
    {
        $permissions = Permission::latest('id', 'desc')->paginate(10);

        return response()->json([
            'status' => true,
            'msg' => 'Permission listing',
            'data' => $permissions
        ]);
    }

    public function storePermission(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'slug' => ['required', 'unique:permissions'],
        ]);

        if ($validator->fails()){
            return response()->json([
                'status' => false,
                'msg' => $validator->errors()->first(),
                'data' => []
            ]);
        }

        $permission = Permission::create($request->only('name','slug'));

        return response()->json([
            'status' => true,
            'msg' => 'Permission created.',
            'data' => $permission
        ]);
    }

    public function showPermission(Permission $permission)
    {
        return response()->json([
            'status' => true,
            'msg' => 'Permission view.',
            'data' => $permission
        ]);
    }

    public function updatePermission(Request $request, Permission $permission)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'slug' => [
                'required',
                Rule::unique('permissions')->whereNot('id', $permission->id)
            ],
        ]);

        if ($validator->fails()){
            return response()->json([
                'status' => false,
                'msg' => $validator->errors()->first(),
                'data' => []
            ]);
        }

        $permission->fill($request->only('name','slug'))->save();

        return response()->json([
            'status' => true,
            'msg' => 'Permission updated.',
            'data' => $permission
        ]);
    }

    public function destroyPermission(Permission $permission)
    {
        $permission->delete();

        return response()->json([
            'status' => true,
            'msg' => 'Permission deleted.',
            'data' => []
        ]);
    }

    public function assignRoles(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => ['required','exists:users,id'],
            'roles' => ['required', 'array'],
            'roles.*' => ['exists:roles,id'],
        ],[
            'roles.*.exists' => 'The selected roles is invalid.'
        ]);

        if ($validator->fails()){
            return response()->json([
                'status' => false,
                'msg' => $validator->errors()->first(),
                'data' => []
            ]);
        }

        $user = User::find($request->user_id);

        $data = $user->assignRoles($request->roles);

        return response()->json([
            'status' => true,
            'msg' => 'Roles assigned.',
            'data' => $data
        ]);

    }
}
