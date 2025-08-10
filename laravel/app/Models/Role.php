<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    public function users() {
        return $this->belongsToMany(User::class, "user_roles");
    }

    public function permissions() {
        return $this->belongsToMany(Permissions::class, "role_permissions");
    }

    public function hasPermission($permission) {
        return $this->permissions()->where("name", $permission)->exists();
    }

    public function givePermission($permission) {
        $permissionModel = Permission::where("name", $permission)->first();
        if ($permissionModel) {
            $this->permissions()->detach($permissionModel->id);
        }
    }
}
