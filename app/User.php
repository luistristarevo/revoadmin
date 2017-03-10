<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

use DB;

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

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    function getPaymentProfiles($web_user_id){
        $result=DB::table('profiles')->where('web_user_id',$web_user_id)->select('id','name','type','token')->orderBy('id', 'desc')->get();
        return $result;
    }

    function getPaymentProfileById($web_user_id,$id_profile){
        $result=DB::table('profiles')->where('web_user_id',$web_user_id)->where('id',$id_profile)->select('id','name','type','token')->first();
        return $result;
    }

    function setPasswordRaw($pass,$id){
        DB::update("update web_users set password = PASSWORD('".$pass."') where web_user_id = ?", [$id]);

    }

}
