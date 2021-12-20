<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermissionRole extends Model
{
    public $timestamps = false;

    protected $table = "permission_role";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['role_id', 'permission_id'];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

}
