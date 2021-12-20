<?php

namespace App\Http\Resources\Permission;

use App\Http\Resources\Role\RoleResource;
use App\Models\PermissionRole;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $permissonRole = PermissionRole::where('permission_id', $this->id)->first();

        return [
            'id' => $this->id,
            'roleId' => $permissonRole ? $permissonRole->role_id : null,
            'name' => $this->name,
            'displayName' => $this->display_name,
            'description' => $this->description,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
