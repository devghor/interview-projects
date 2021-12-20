<?php

namespace App\Http\Controllers\Api\Permission;

use App\Http\Controllers\Controller;
use App\Http\Resources\Permission\PermissionCollection;
use App\Http\Resources\Permission\PermissionResource;
use App\Models\Permission;
use App\Models\PermissionRole;
use App\Models\Role;
use App\Values\StatusValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $offset = $request['offset'] ?? 0;
        $limit = $request['limit'] ?? 10;
        $permissions = new PermissionCollection(Permission::orderBy('id', 'DESC')->offset($offset)->limit($limit)->get());
        return response(['data' => $permissions], StatusValue::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'displayName' => 'required',
                'roleId' => 'required'
            ]);

            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first());
            }

            $role = Role::find($request['roleId']);

            if (!$role) {
                throw  new \Exception('Role not found');
            }

            $permission = new Permission();
            $permission->name = $request['name'];
            $permission->display_name = $request['displayName'];
            $permission->description = $request['description'];
            $permission->save();

            $role->attachPermission($permission);

            return response([
                'data' => new PermissionResource($permission)
            ], StatusValue::HTTP_ACCEPTED);
        } catch (\Exception $e) {
            return response([
                'error' => [
                    'message' => $e->getMessage(),
                    'code' => StatusValue::HTTP_UNPROCESSABLE_ENTITY
                ]
            ], StatusValue::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'displayName' => 'required',
                'roleId' => 'required'
            ]);

            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first());
            }

            $role = Role::find($request['roleId']);

            if (!$role) {
                throw new \Exception('No role found');
            }

            $permission = Permission::find($id);
            if (!$permission) {
                throw new \Exception('No permission found');
            }
            $permission->name = $request['name'];
            $permission->display_name = $request['displayName'];
            $permission->description = $request['description'];
            $permission->save();

            PermissionRole::where('permission_id', $permission->id)->update([
                'permission_id' => $permission->id,
                'role_id' => $role->id]);

            return response([
                'data' => new PermissionResource($permission)
            ], StatusValue::HTTP_ACCEPTED);
        } catch (\Exception $e) {
            return response([
                'error' => [
                    'message' => $e->getMessage(),
                    'code' => StatusValue::HTTP_UNPROCESSABLE_ENTITY
                ]
            ], StatusValue::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $permisssoin = Permission::find($id);
            if (!$permisssoin) {
                throw new \Exception('No permission found');
            }
            $permisssoin->delete();
            return response([
                'data' => []
            ], StatusValue::HTTP_ACCEPTED);
        } catch (\Exception $e) {
            return response([
                'error' => [
                    'message' => $e->getMessage(),
                    'code' => StatusValue::HTTP_UNPROCESSABLE_ENTITY
                ]
            ], StatusValue::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
