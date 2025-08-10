<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function roles() {
        return $this->belongsToMany(Role::class, "user_roles");
    }

    public function hasRole($role) {
        return $this->roles()->where("name", $ole)->exists();
    }

    public function hasPermission($permission) {
        return $this->roles()->whereHas("permissions", function($query) use ($permission) {
            $query->where("name", $permission);
        });
    }

    public function assignRole($role) {
        $roleModel = Role::where("name", $role)->first();
        if ($roleModel) {
            $this->roles()->attach($roleModel->id);
        }
    }

    public function removeRole($role) {
        $roleModel = Role::where("name", $role)->first();
        if ($roleModel) {
            $this->role()->detach($roleModel->id);
        }
    }
}
