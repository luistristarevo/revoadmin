<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;

class Deposits extends Model {

    protected $table = 'settlement_report';

    function getDepositsGroup(){
        $result= DB::table($this->table)
            ->select('trans_id','date','customer_id','property_id','id_property','batch','date_formatted','company_name','property_name', DB::raw('sum(credit) as credit'),DB::raw('sum(debit) as debit'),'transaction_type')
            ->groupBy('date_formatted')
            ->groupBy('id_property')
            ->groupBy('transaction_type');
        return $result;
    }

    function getDepositDetails($idp, $date, $batch, $get = true){
        if($get){
            $result= DB::table($this->table)
                ->where('id_property',$idp)
                ->where('date_formatted',$date)
                ->get()
            ;
        }
        else{
            $result= DB::table($this->table)
                ->where('id_property',$idp)
                ->where('date_formatted',$date)
            ;
        }

        return $result;
    }
}