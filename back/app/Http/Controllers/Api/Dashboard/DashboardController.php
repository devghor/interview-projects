<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Values\StatusValue;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $userCount = User::count();
            $roleCount = Role::count();
            $permissionCount = Permission::count();
            return response([
                'data'=>[
                    'totalUser' => $userCount,
                    'totalRole' => $roleCount,
                    'totalPermission' => $permissionCount,
                ]
            ], StatusValue::HTTP_OK);
        } catch (\Exception $e) {
            return response([
                'error'=>[
                    'message' => $e->getMessage(),
                    'code' => StatusValue::HTTP_UNPROCESSABLE_ENTITY
                ]
            ], StatusValue::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

}
