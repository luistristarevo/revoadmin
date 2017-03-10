<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;

class WebUsers extends Model {
    
    protected $table = 'web_users';
    public $timestamps = false;

    function getActAutWebUsersByPropertyId($property_id, $status) {
        $resul = DB::table('web_users')
            ->select('web_user_id', 'web_status')
            ->where('property_id', $property_id)
            ->whereIn('web_status', $status)
            ->get();
        return $resul;
    }

    public function getOClickReminderByIdProperty($property_id, $status){
        $result = DB::table('oneclick_reminder')
            ->select('id', 'status')
            ->where('id_properties',$property_id)
            ->where('status', $status)
            ->get();
        return $result;
    }

    function set1UserInfo($web_user_id,$key,$value){
        DB::table('web_users')->where('web_user_id',$web_user_id)->update(array($key=>$value));
    }

    public function getWebUserList($idlevel, $level, $whereCondition = array()){

        //echo $idlevel.' == '.$level; die;
        $webuserlist  = array();
        $daterangecondition = array();

        switch (strtoupper($level)){
            case "B":
                $query = DB::table($this->table)
                    ->join('properties', 'properties.id', '=', 'web_users.property_id')
                    ->join('companies', 'companies.id', '=', 'properties.id_companies')
                    ->leftJoin('partners', 'partners.id', '=', 'properties.id_partners')
                    ->select('web_user_id as id', 'partners.partner_title as partner', 'companies.company_name as group', 'properties.name_clients as merchant','web_users.companyname', 'account_number as webuser', 'first_name', 'last_name', 'username', 'email_address as email','web_users.phone_number as phone', 'web_users.address', 'address_unit as unit', 'web_users.city', DB::raw('UPPER(web_users.state) as state'), 'web_users.zip', 'balance', 'web_status as status', 'web_users.last_updated', 'web_users.last_updated_by','web_users.suppression','web_users.companyname');
                if(!empty($whereCondition)){

                    foreach($whereCondition as $key => $value){

                        if(stristr($value, 'date')){
                            $valueArray = explode('=', $value);
                            if($valueArray[1] != ''){
                                $date = explode('/', $valueArray[1]);
                                $daterangecondition[] = $date[2].'-'.$date[0].'-'.$date[1];
                            }
                        }else{
                            $valueArray = explode('=', $value);
                            if($valueArray[0] != 'search'){

                                if($valueArray[0] == 'address'){
                                    $query->where('web_users.'.$valueArray[0], 'like', '%'.$valueArray[1].'%');
                                }else if($valueArray[0] == 'state'){
                                    $query->where('web_users.'.$valueArray[0], 'like', '%'.$valueArray[1].'%');
                                }else{
                                    $query->where($valueArray[0], 'like', '%'.$valueArray[1].'%');
                                }

                            }
                        }

                    }

                }
                $webuserlist = $query;
                break;
            case "P":
                $query = DB::table($this->table)
                    ->join('properties', 'properties.id', '=', 'web_users.property_id')
                    ->join('companies', 'companies.id', '=', 'properties.id_companies')
                    ->leftJoin('partners', 'partners.id', '=', 'properties.id_partners')
                    ->select('web_user_id as id', 'partners.partner_title as partner', 'companies.company_name as group', 'properties.name_clients as merchant','web_users.companyname', 'account_number as webuser', 'first_name', 'last_name', 'username', 'email_address as email','web_users.phone_number as phone', 'web_users.address', 'address_unit as unit', 'web_users.city', DB::raw('UPPER(web_users.state) as state'), 'web_users.zip', 'balance', 'web_status as status', 'web_users.last_updated', 'web_users.last_updated_by','web_users.suppression','web_users.companyname');
                $query->where('web_status', '<', 1000);
                $query->whereIn('properties.id_partners', explode('!',$idlevel));
                //building search parameter if any only for export
                if(!empty($whereCondition)){

                    foreach($whereCondition as $key => $value){

                        if(stristr($value, 'date')){
                            $valueArray = explode('=', $value);
                            if($valueArray[1] != ''){
                                $date = explode('/', $valueArray[1]);
                                $daterangecondition[] = $date[2].'-'.$date[0].'-'.$date[1];
                            }
                        }else{
                            $valueArray = explode('=', $value);
                            if($valueArray[0] != 'search'){

                                if($valueArray[0] == 'address'){
                                    $query->where('web_users.'.$valueArray[0], 'like', '%'.$valueArray[1].'%');
                                }else if($valueArray[0] == 'state'){
                                    $query->where('web_users.'.$valueArray[0], 'like', '%'.$valueArray[1].'%');
                                }else{
                                    $query->where($valueArray[0], 'like', '%'.$valueArray[1].'%');
                                }

                            }
                        }

                    }

                }
                $webuserlist = $query;
                break;
            case "G":
                $query = DB::table($this->table)
                    ->join('properties', 'properties.id', '=', 'web_users.property_id')
                    ->join('companies', 'companies.id', '=', 'properties.id_companies')
                    ->leftJoin('partners', 'partners.id', '=', 'properties.id_partners')
                    ->select('web_user_id as id', 'partners.partner_title as partner', 'companies.company_name as group', 'properties.name_clients as merchant', 'web_users.companyname','account_number as webuser', 'first_name', 'last_name', 'username', 'email_address as email','web_users.phone_number as phone', 'web_users.address', 'address_unit as unit', 'web_users.city', DB::raw('UPPER(web_users.state) as state'), 'web_users.zip', 'balance', 'web_status as status', 'web_users.last_updated', 'web_users.last_updated_by','web_users.suppression');
                $query->where('web_status', '<', 1000);
                $query->whereIn('properties.id_companies',  explode('!',$idlevel));
                $webuserlist = $query;
                break;
            case "M":
                //echo '<pre>';
                //print_r($whereCondition);
                $query = DB::table($this->table)
                    ->join('properties', 'properties.id', '=', 'web_users.property_id')
                    ->join('companies', 'companies.id', '=', 'properties.id_companies')
                    ->leftJoin('partners', 'partners.id', '=', 'properties.id_partners')
                    ->select('web_user_id as id', 'partners.partner_title as partner', 'companies.company_name as group', 'properties.name_clients as merchant', 'web_users.companyname','account_number as webuser', 'first_name', 'last_name', 'username', 'email_address as email','web_users.phone_number as phone', 'web_users.address', 'address_unit as unit', 'web_users.city', DB::raw('UPPER(web_users.state) as state'), 'web_users.zip', 'balance', 'web_status as status', 'web_users.last_updated', 'web_users.last_updated_by','web_users.suppression');
                $query->where('web_status', '<', 1000);
                $query->whereIn('web_users.property_id',  explode('!',$idlevel));
                $webuserlist = $query;
                break;
        }

        return $webuserlist;

    }

    public function getPropertyIdByUserId($web_user_id){

        $property_id = DB::table($this->table)
            ->select('property_id')
            ->where('web_users.web_user_id', '=', $web_user_id)
            ->first();

        return $property_id;

    }

    public function getPaymentTypeDetail($property_id){

        $paymenttypedetail = DB::table('payment_type')
            ->where('payment_type.property_id', '=', $property_id)
            ->first();

        return $paymenttypedetail;

    }

    public function isCatChecked($payment_type_id, $web_user_id){

        $web_user_permissions = DB::table('web_users_permissions')
            ->where('web_users_permissions.web_user_id', '=', $web_user_id)
            ->where('web_users_permissions.payment_type_id', '=', $payment_type_id)
            ->first();

        if(!empty($web_user_permissions)){
            return 'checked';
        }else{
            return 0;
        }

    }

    public function getPartnerList(){

        $partnerlist = DB::table('partners')
            ->lists('partners.partner_title', 'id');

        return $partnerlist;

    }

    public function getCompanyList(){

        $companylist = DB::table('companies')
            ->lists('companies.company_name', 'id');

        return $companylist;

    }

    public function getMerchantList(){

        $merchantlist = DB::table('properties')
            ->lists('properties.name_clients', 'id');

        return $merchantlist;

    }

    public function getWebUserdetail($idlevel, $level, $web_user_id){

        $webuserdetail = DB::table($this->table)
            ->join('properties', 'properties.id', '=', 'web_users.property_id')
            ->join('companies', 'companies.id', '=', 'properties.id_companies')
            ->leftJoin('partners', 'partners.id', '=', 'properties.id_partners')
            ->select('web_user_id as id', 'partners.partner_title as partner', 'partners.id as partner_id', 'companies.company_name as group', 'companies.id as company_id', 'properties.name_clients as merchant', 'properties.id as merchant_id','companyname', 'account_number as webuser', 'first_name', 'last_name', 'username', 'email_address as email','web_users.phone_number as phone', 'web_users.address', 'address_unit as unit', 'web_users.city', DB::raw('UPPER(web_users.state) as state'), 'web_users.zip', 'balance', 'web_status as status', 'web_users.last_updated', 'web_users.last_updated_by','web_users.suppression')
            ->where('web_users.web_user_id', '=', $web_user_id)
            ->first();

        return $webuserdetail;

    }

    public function saveWebUser($webuserdata = array()){
        if(isset($webuserdata['_token'])){
            unset($webuserdata['_token']);
        }
        if(isset($webuserdata['isError'])){
            unset($webuserdata['isError']);
        }
        if(isset($webuserdata['name_clients'])){
            $webuserdata['property_id']=$webuserdata['name_clients'];
            unset($webuserdata['name_clients']);
        }
        $webuserdata['last_updated_by']='admin';

        if(isset($webuserdata['id']) && $webuserdata['id']>0){
            $web_user_id=$webuserdata['id'];
            unset($webuserdata['id']);
            //verify account number
            $property_id=$this->get1UserInfo($web_user_id, 'property_id');
            if(isset($webuserdata['account_number']) && trim($webuserdata['account_number'])!=''){
                $ct=DB::table($this->table)->where('web_user_id','!=',$web_user_id)->where('web_status','<',1000)->where('account_number','=',trim($webuserdata['account_number']))->where('property_id','=',$property_id)->count();
                if($ct>0)return -3;
            }
            //verify username
            if(isset($webuserdata['username']) && trim($webuserdata['username'])!=''){
                $ct=DB::table($this->table)->where('web_user_id','!=',$web_user_id)->where('web_status','<',1000)->where('username','=',trim($webuserdata['username']))->where('property_id','=',$property_id)->count();
                if($ct>0)return -6;
            }
            DB::table($this->table)
                ->where('web_user_id', $web_user_id)
                ->update($webuserdata);
            return $web_user_id;

        }else{
            //verify account number
            $property_id=$webuserdata['property_id'];
            if(isset($webuserdata['account_number']) && trim($webuserdata['account_number'])!=''){
                $ct=DB::table($this->table)->where('web_status','<',1000)->where('account_number','=',trim($webuserdata['account_number']))->where('property_id','=',$property_id)->count();
                if($ct>0)return -3;
            }
            //verify username
            if(isset($webuserdata['username']) && trim($webuserdata['username'])!=''){
                $ct=DB::table($this->table)->where('web_status','<',1000)->where('username','=',trim($webuserdata['username']))->where('property_id','=',$property_id)->count();
                if($ct>0)return -6;
            }
            unset($webuserdata['id']);
            $web_user_id=DB::table($this->table)
                ->insertGetId($webuserdata);

            return $web_user_id;
        }

    }

    function get1UserInfo($web_user_id,$key){
        $info= DB::table('web_users')->select($key)->where('web_user_id',$web_user_id)->first();
        return $info[$key];
    }

    public function deleteWebUser($id){

        DB::table($this->table)
            ->where('web_user_id', $id)
            ->update(['web_status' => '9999']);
        return true;

    }



}
