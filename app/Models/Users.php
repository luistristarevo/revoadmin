<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;

class Users extends Model {
    
    protected $table = 'users';

    public function getAdminList($idlevel, $level, $whereCondition = array()){

        $adminlist = array();
        if($level == 'P'){

            $query = DB::table($this->table);
            if($idlevel == '-954581'){

                $query->select('users.id', 'first_name', 'last_name', 'email_address as email', 'phone', 'active as status', 'street as address', 'city', 'state', 'zip', 'login','password as passw', 'users.last_update as updte','users.last_updated_by as updte_by')
                    ->whereNotIn('users.id', function($query){
                        $query->from('users_system');
                        $query->select('users_system.id_user as id');

                    });

            }
            if(!empty($whereCondition)){

                foreach($whereCondition as $key => $value){

                    $valueArray = explode('=', $value);
                    if($valueArray[0] != 'search'){
                        $query->where($valueArray[0], 'like', '%'.$valueArray[1].'%');
                    }

                }

            }
            $query->groupBy('users.id');
            //echo $query->toSql(); die;
            $adminlist = $query;

            //echo '<pre>';
            //print_r($adminlist); die;
        }
        return $adminlist;

    }

    public function getTopAdm($user_id){

        $user_level = DB::table('user_levels')
            ->where('id_users', $user_id)
            ->first();



        return $user_level['user_level'];


    }

    public function getAccess($user_id, $level){

        $resultado = '';
        if($level == 'P'){

            $result = DB::table('partners')
                ->select('partner_title')
                ->whereIn('id', function($query) use ($user_id){

                    $query->from('user_super_admins');
                    $query->select('id_super_admin as id');
                    $query->where('id_users', $user_id);

                })
                ->get();
            if(!empty($result)){

                $resultado .= $result[0]['partner_title']."<br>";

            }

        }else if($level == 'G'){

            $result = DB::table('companies')
                ->select('company_name')
                ->whereIn('id', function($query) use ($user_id){

                    $query->from('user_super_admins');
                    $query->select('id_super_admin as id');
                    $query->where('id_users', $user_id);

                })
                ->get();
            if(!empty($result)){

                $resultado .= $result[0]['company_name']."<br>";

            }

        }else{

            $result = DB::table('properties')
                ->select('name_clients')
                ->whereIn('id', function($query) use ($user_id){

                    $query->from('user_super_admins');
                    $query->select('id_super_admin as id');
                    $query->where('id_users', $user_id);

                })
                ->get();
            if(!empty($result)){

                $resultado .= $result[0]['name_clients']."<br>";

            }

        }

        return $resultado;

    }

    public function getRolesAdm($user_id){

        $user_role_value = array('admin_manager' => '', 'user_manager' => '', 'transaction_manager' => '', 'app_manager' => '', 'profile_manager' => '');
        $user_roles = DB::table('user_roles')
            ->where('id_user', $user_id)
            ->first();
        if(!empty($user_roles)){
            if($user_roles['admin_manager'] == 1){
                $user_role_value['admin_manager'] = 'Admin Manager';
            }
            if($user_roles['user_manager'] == 1){
                $user_role_value['user_manager'] = 'User Manager';
            }
            if($user_roles['transaction_manager'] == 1){
                $user_role_value['transaction_manager'] = 'Transaction Manager';
            }
            if($user_roles['app_manager'] == 1){
                $user_role_value['app_manager'] = 'Application Manager';
            }
            if($user_roles['profile_manager'] == 1){
                $user_role_value['profile_manager'] = 'Profile Manager';
            }
        }
        return $user_role_value;


    }

    public function getUserDetail($idlevel, $level, $user_id){

        $user_details = DB::table($this->table)
            ->select('users.id', 'first_name', 'last_name', 'email_address as email', 'phone', 'active as status', 'street as address', 'city', 'state', 'zip', 'login','password', 'users.last_update as updte','users.last_updated_by as updte_by')
            ->where('users.id', $user_id)
            ->get();

        return $user_details;

    }

    public function updateUserDetail($user_id,$user_details){

        DB::table($this->table)->where('id',$user_id)->update(['last_name' => $user_details['last_name'], 'first_name' => $user_details['first_name'], 'login' => $user_details['login'], 'email_address' => $user_details['email_address'], 'phone' => $user_details['phone'], 'active' => $user_details['status']]);
        return true;

    }

    public function deleteAdminUser($user_id){

        DB::table($this->table)
            ->where('id', $user_id)->delete();
        return true;

    }

}
