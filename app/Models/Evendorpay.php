<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;

class Evendorpay extends Model {
     
    protected $table = 'evendorpay_transactions';
    protected $softDelete = false;
    public $timestamps = false;
    
    function insertPayment($data){
        $result=$this->insertGetId($data);
        return $result;
    }
    
    function set1Payment($txid,$field,$value){
        $this->where('trans_id','=',$txid)->update(array($field=>$value));
    }

    function getAllReport(){
        return DB::table($this->table)
            ->select('trans_first_post_date','partner_title','company_name','name_clients','trans_unique_id','name','trans_net_amount','trans_convenience_fee','trans_total_amount')
            ->join('properties', 'properties.id', '=', 'evendorpay_transactions.property_id')
            ->join('partners', 'properties.id_partners', '=', 'partners.id')
            ->join('vendor', 'evendorpay_transactions.vendor_id', '=', 'vendor.id')
            ->join('companies', 'properties.id_companies', '=', 'companies.id');
    }

    function getEvendorRecords($level, $idlevel){
        $idlevel=strtoupper($idlevel);
        $query = DB::table($this->table)
            ->select('trans_first_post_date','partner_title','company_name','name_clients','trans_unique_id','name','trans_net_amount','trans_convenience_fee','trans_total_amount')
            ->join('properties', 'properties.id', '=', 'evendorpay_transactions.property_id')
            ->join('partners', 'properties.id_partners', '=', 'partners.id')
            ->join('vendor', 'evendorpay_transactions.vendor_id', '=', 'vendor.id')
            ->join('companies', 'properties.id_companies', '=', 'companies.id');
            if($level=="M"){
                $query->whereIn('properties.id', explode('!',$idlevel));
            }else if($level=="G"){
                $query->whereIn('companies.id', explode('!',$idlevel));
            }else if($level == 'P'){
                $query->whereIn('partners.id', explode('!',$idlevel));
            }

        return $query;
    }

}   
    
