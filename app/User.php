<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use App\Role;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';
    public $timestamps = false;
    protected $primary = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['first_name', 'last_name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */

    public static function defaultUser(){
        return User::first();
    }

    protected $hidden = ['password', 'remember_token'];

    public static function techs(){
        return Role::getTech()->users()->get();
    }

    public function tickets(){
        return $this->hasMany('App\Ticket', 'placed_by');
    }
    public function getNameAttribute(){
        return ucfirst($this->first_name)." ".ucfirst($this->last_name);
    }

    public function messages(){
        return $this->hasMany('App\Message', 'user_id');
    }

    public function assignedTickets(){
        return $this->hasMany('App\Ticket', 'assigned_to');
    }

    public function roles(){
        return $this->belongsToMany('App\Role', 'user_roles');
    }

    public function is($roleName){
        foreach($this->roles as $role){
            if($role->description === $roleName) {
                return true;
            }
        }
        return false;
    }


}
