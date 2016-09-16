<?php

namespace App;

use App\Api\V1\Utils\PermissionLevel;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'login', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'pivot', 'created_at', 'updated_at'
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT
     *
     * @return mixed
     */
    public function getJWTIdentifier() {
        return $this->attributes['id'];
    }
    /**
     * Return a key value array, containing any custom claims to be added to the JWT
     *
     * @return array
     */
    public function getJWTCustomClaims() {
        return [];
    }

    /**
     * Get the companies associated with the given user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function companies() {
        return $this->belongsToMany('App\Models\Company', 'company_user')
            ->withPivot('permissions')
            ->withTimestamps();
    }

    public function companiesWithPermission($permissionLevel, $companies = null)
    {
        $query = $this->companies()->where('permissions', '<=', $permissionLevel);

        if ($companies!=null) {
            if (is_numeric($companies)) $companies = [$companies];
            $query->whereIn('company_id', $companies);
        }

        return $query;
    }

    public function allowedToAccessCompany($id)
    {
        return $this->companiesWithPermission(PermissionLevel::USER, $id)->exists();
    }

    public function allowedToEditCompany($id)
    {
        return $this->companiesWithPermission(PermissionLevel::TECHNICAL, $id)->exists();
    }

    public function allowedToDestroyCompany($id)
    {
        return $this->companiesWithPermission(PermissionLevel::ADMIN, $id)->exists();
    }
}
