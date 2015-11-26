<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = "role";
    public $timestamps = false;
    //
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function users(){
    	return $this->belongsToMany('App\User', 'user_roles');
    }

    public function scopeDescription($scope, $name){
    	return $scope->where('description', $name);
    }

    public static function getTech(){
        return Role::description('tech')->get()->first();
    }

    public static function getUser(){
        return Role::description('user')->get()->first();
    }

    public static function getAdmin(){
        return Role::description('admin')->get()->first();
    }

}
