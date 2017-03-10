<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Facades\Session;

class UserPermissions extends Model {

    public function getPermissions($idUser){
        $permissions = DB::table('user_permissions')
            ->join('permissions', 'user_permissions.idpermission', '=', 'permissions.id')
            ->where('user_permissions.iduser',$idUser)
            ->get();
        return $permissions;
    }

    public function getAccess($idUser,$idlevel,$level){

        switch(strtolower($level)){
            case 'p':
                $access = DB::table('users_access')
                    ->where('iduser',$idUser)
                    ->where('idp',$idlevel)
                    ->orWhere('ida','>',0)
                    ->first();
                break;
            case 'g':
                $access = DB::table('users_access')
                    ->where('iduser',$idUser)
                    ->where('idc',$idlevel)
                    ->orWhere('idp',$idlevel)
                    ->orWhere('ida','>',0)
                    ->first();
                break;
            case 'm':
                $access = DB::table('users_access')
                    ->where('iduser',$idUser)
                    ->where('idm',$idlevel)
                    ->orWhere('idp',$idlevel)
                    ->orWhere('idc',$idlevel)
                    ->orWhere('ida','>',0)
                    ->first();
                break;
        }

        return $access;
    }
    
    function hasAccess($iduser,$level,$idlevel){
        if($level=='P'){
           $access = DB::table('users_access')
                    ->where('iduser',$iduser)
                    ->where('idp',$idlevel)
                    ->count();
           if($access>0){
               return true;
           }
        }
        elseif($level=='G'){
           $access = DB::table('users_access')
                    ->where('iduser',$iduser)
                    ->where('idc',$idlevel)
                    ->count();
           if($access>0){
               return true;
           }
        }
        elseif($level=='M'){
           $access = DB::table('users_access')
                    ->where('iduser',$iduser)
                    ->where('idm',$idlevel)
                    ->count();
           if($access>0){
               return true;
           } 
        }
        return false;
    }

    public function isAuth($idUser){
        if(!Session::get('user_logged') || Session::get('user_logged')['id']!=$idUser){
           return false;
        }
        else{
            return true;
        }
    }

    public function getAccessWithoutData($idUser){
        $access = DB::table('users_access')
            ->leftJoin('partners', 'users_access.idp', '=', 'partners.id')
            ->leftJoin('companies', 'users_access.idc', '=', 'companies.id')
            ->leftJoin('properties', 'users_access.idm', '=', 'properties.id')
            ->where('iduser',$idUser)
            ->orderBy('ida', 'desc')
            ->orderBy('idb', 'desc')
            ->orderBy('idp', 'desc')
            ->orderBy('idc', 'desc')
            ->orderBy('idm', 'desc')
            ->get();
        return $access;
    }

}
