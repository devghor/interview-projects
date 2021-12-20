<?php

namespace App\Http\Controllers\Api\Role;

use App\Http\Controllers\Controller;
use App\Http\Resources\Role\RoleResource;
use App\Models\Role;
use App\Values\RoleValue;
use App\Values\StatusValue;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class RoleController extends Controller
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
        $roles = RoleResource::collection(Role::offset($offset)->limit($limit)->get());
        return response(['data' => $roles], StatusValue::HTTP_OK);
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
            ]);

            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first());
            }

            $role = new Role();
            $role->name = $request['name'];
            $role->display_name = $request['displayName'];
            $role->save();

            return response([
                'data'=> new RoleResource($role)
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
            ]);

            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first());
            }

            $role =  Role::find($id);

            if(!$role){
                throw new \Exception('No role found');
            }

            $role->name = $request['name'];
            $role->display_name = $request['displayName'];
            $role->save();

            return response([
                'data'=> new RoleResource($role)
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
            $role =  Role::find($id);
            if(!$role){
                throw new \Exception('No role found');
            }
            if(RoleValue::SUPER_ADMIN_ID == $id){
                throw  new Exception("Superadmin can not be deleted.");
            }
            $role->delete();
            return response([
                'data'=> []
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
