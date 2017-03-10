<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\Customize;

class MerchantAccount extends Model {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'merchant_account';
    public $timestamps = false;
    
    function getNMIAll(){
        $result=DB::table($this->table)->where('gateway','LIKE','nmi')
                ->join('properties','properties.id','=','merchant_account.property_id')
                ->select('merchant_account.payment_source_key','properties.name_clients','properties.id')
                ->groupBy('merchant_account.payment_source_key')
                ->get();
        return $result;
    }

    function getByProperty($idproperty){
        $result=DB::table($this->table)
            ->where('property_id','=',$idproperty)
            ->orderBy('merchant_account_id','ASC')
            ->get();
        return $result;
    }
    
}