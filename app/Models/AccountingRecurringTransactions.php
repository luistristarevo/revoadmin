<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;

class AccountingRecurringTransactions extends Model {
     
    protected $table = 'accounting_recurring_transactions';
    protected $softDelete = false;
    public $timestamps = false;

    public function getRecurringPayments($idlevel, $level, $status = 1, $whereCondition = array()){
        $level=strtoupper($level);
        $query = DB::table($this->table)
            ->join('web_users', 'web_users.web_user_id', '=', 'accounting_recurring_transactions.trans_web_user_id')
            ->leftJoin('properties', 'properties.id', '=', 'accounting_recurring_transactions.property_id')
            ->leftJoin('companies', 'companies.id', '=', 'properties.id_companies')
            ->join('partners', 'partners.id', '=', 'properties.id_partners')
            ->select('properties.id as property_id','accounting_recurring_transactions.trans_web_user_id as web_user_id','trans_id', 'partners.partner_title as partner', 'companies.company_name as group', 'properties.name_clients as merchant', 'web_users.address_unit', 'web_users.account_number as webuser', 'trans_next_post_date as trans_next_date', 'trans_numleft as num_left', 'web_users.first_name','web_users.last_name', 'trans_payment_type as pay_method', 'trans_card_type as pay_type', 'trans_recurring_net_amount as net_amount', 'trans_recurring_convenience_fee as net_fee', DB::raw('trans_recurring_net_amount+trans_recurring_convenience_fee as net_charge'), 'dynamic as stype','trans_schedule as frequency')
            ->where('trans_status', '=', $status)
            ->where('properties.status_clients', '=', 1);
        if($level=="M"){
            $query->whereIn('properties.id', explode('!',$idlevel));
        }else if($level=="G"){
            $query->whereIn('companies.id', explode('!',$idlevel));
        }else if($level == 'P'){
            $query->whereIn('partners.id', explode('!',$idlevel));
        }

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
                        $query->where($valueArray[0], 'like', '%'.$valueArray[1].'%');
                    }
                }

            }

            if(!empty($daterangecondition)){

                if(($daterangecondition[0] != '' && isset($daterangecondition[0])) && ($daterangecondition[1] == '' && isset($daterangecondition[1]))){

                    $query->where('trans_next_post_date', '>=', $daterangecondition[0]);

                }else if(($daterangecondition[0] == '' && isset($daterangecondition[0])) && ($daterangecondition[1] != '' && isset($daterangecondition[1]))){
                    $query->where('trans_next_post_date', '<=', $daterangecondition[1]);

                }else if(($daterangecondition[0] != '' && isset($daterangecondition[0])) && ($daterangecondition[1] != '' && isset($daterangecondition[1]))){
                    $query->whereBetween('trans_next_post_date', [$daterangecondition[0],$daterangecondition[1]]);
                }

            }

        }
        $accountingrecurrtransactions = $query;
        return $accountingrecurrtransactions;


    }

    public function getaccountingtransactiondetail($trans_id){

        $accountingrecurrdetail = DB::table($this->table)
            ->select('trans_schedule', 'trans_web_user_id')
            ->where('trans_id', '=', $trans_id)
            ->get();

        return $accountingrecurrdetail;

    }

    public function updateWebUserStatus($web_user_id){

        DB::table('web_users')
            ->where('web_user_id', $web_user_id)
            ->where('web_status', '998')
            ->update(['web_status' => '1']);

        return true;

    }

    public function updateaccountingtransstatus($trans_id,$status=4){
        DB::table($this->table)
            ->where('trans_id', $trans_id)
            ->update(['trans_status' => $status]);
        return true;
    }

    public function getTransactionIdByPropertyId($property_id, $trans_status = 1) {
        $result = DB::table($this->table)
            ->select('trans_id', 'trans_status')
            ->where('property_id', $property_id)
            ->where('trans_status', $trans_status)
            ->get();
        return $result;
    }
}
