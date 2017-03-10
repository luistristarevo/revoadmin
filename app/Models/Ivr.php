<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;

class Ivr extends Model {
    
    protected $table;
    
    function __construct() {
        $this->table='ivr';
    }

    function getIvrByProperty($id_property){
        $result= DB::table('ivr')->where('id_property',$id_property)->first();
        return $result;
    }

    public function getGroupsfromProperty($merchant_id){

        $merchant_groups = array();
        $partner_id = $this->getPartnerIDByProperty($merchant_id);
        if($partner_id){

            $query = DB::table('companies')
                ->select('id', 'company_name')
                ->where('companies.id_partners', '=', $partner_id);

            $merchant_groups = $query->get();
            if(!empty($merchant_groups)){

                return $merchant_groups;

            }

        }
        return $merchant_groups;

    }
}
