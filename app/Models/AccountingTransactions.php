<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;

class AccountingTransactions extends Model {

    protected $table = 'accounting_transactions';
    protected $primaryKey = 'trans_id';
    protected $softDelete = false;
    public $timestamps = false;

    public function getAccountingTransactions($idlevel,  $level, $whereCondition = array()){
        $accountingtransactions  = array();
        $daterangecondition = array();
        $level = strtoupper($level);

        if($level == 'B') {
            $accountingtransactions = DB::table($this->table)
                ->join('web_users', 'web_users.web_user_id', '=', 'accounting_transactions.trans_web_user_id')
                ->leftJoin('properties', 'properties.id', '=', 'accounting_transactions.property_id')
                ->leftJoin('companies', 'companies.id', '=', 'properties.id_companies')
                ->join('partners', 'partners.id', '=', 'properties.id_partners')
                ->select('trans_card_type', 'properties.id as idproperty', 'properties.id_companies', 'properties.id_partners', 'trans_net_amount', 'trans_last_post_date', 'accounting_transactions.account_number', 'bank_id', 'misc_field', 'lockbox_id', 'compositeID_clients', 'trans_id', 'partners.partner_title as partner', 'companies.company_name as group', 'properties.name_clients as merchant', 'web_users.address_unit', 'web_users.account_number as webuser', 'trans_first_post_date as trans_date', 'web_users.first_name', 'web_users.last_name', DB::raw('CONCAT(web_users.first_name, \' \', web_users.last_name) as web_user_name'), 'trans_payment_type as pay_method', 'trans_card_type as pay_type', 'trans_net_amount as net_amount', 'trans_convenience_fee as net_fee', 'trans_total_amount as net_charge', 'source as trans_source', 'trans_result_auth_code as auth_code', 'trans_status as status', 'trans_type as stype')
                ->where('is_convenience_fee_trans', '=', 0);

        }
        else if($level == 'P'){
            $query = DB::table($this->table)
                ->join('web_users', 'web_users.web_user_id', '=', 'accounting_transactions.trans_web_user_id')
                ->leftJoin('properties', 'properties.id', '=', 'accounting_transactions.property_id')
                ->leftJoin('companies', 'companies.id', '=', 'properties.id_companies')
                ->join('partners', 'partners.id', '=', 'properties.id_partners')
                ->select('trans_card_type','properties.id as idproperty','properties.id_companies','properties.id_partners','trans_net_amount','trans_last_post_date','accounting_transactions.account_number','bank_id','misc_field','lockbox_id','compositeID_clients','trans_id', 'partners.partner_title as partner', 'companies.company_name as group', 'properties.name_clients as merchant', 'web_users.address_unit', 'web_users.account_number as webuser', 'trans_first_post_date as trans_date', 'web_users.first_name','web_users.last_name', DB::raw('CONCAT(web_users.first_name, \' \', web_users.last_name) as web_user_name'), 'trans_payment_type as pay_method', 'trans_card_type as pay_type', 'trans_net_amount as net_amount', 'trans_convenience_fee as net_fee', 'trans_total_amount as net_charge', 'source as trans_source', 'trans_result_auth_code as auth_code', 'trans_status as status', 'trans_type as stype')
                ->where('is_convenience_fee_trans', '=', 0)
                ->whereIn('partners.id',explode('!',$idlevel));

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

                        $query->where('trans_first_post_date', '>=', $daterangecondition[0]);

                    }else if(($daterangecondition[0] == '' && isset($daterangecondition[0])) && ($daterangecondition[1] != '' && isset($daterangecondition[1]))){
                        $query->where('trans_first_post_date', '<=', $daterangecondition[1]);

                    }else if(($daterangecondition[0] != '' && isset($daterangecondition[0])) && ($daterangecondition[1] != '' && isset($daterangecondition[1]))){
                        $query->whereBetween('trans_first_post_date', [$daterangecondition[0],$daterangecondition[1]]);
                    }

                }

            }

            $accountingtransactions = $query;

        }else if($level == 'G'){
            $accountingtransactions = DB::table($this->table)
                ->join('web_users', 'web_users.web_user_id', '=', 'accounting_transactions.trans_web_user_id')
                ->select('trans_id', 'accounting_transactions.data', 'web_users.address_unit', 'web_users.account_number as webuser', 'trans_first_post_date as trans_date', 'web_users.first_name','web_users.last_name', DB::raw('CONCAT(web_users.first_name, \' \', web_users.last_name) as web_user_name'), 'trans_payment_type as pay_method', 'trans_card_type as pay_type', 'trans_net_amount as net_amount', 'trans_convenience_fee as net_fee', 'trans_total_amount as net_charge', 'source as trans_source', 'trans_result_auth_code as auth_code', 'trans_status as status', 'trans_type as stype')
                ->where('is_convenience_fee_trans', '=', 0)
                ->whereIn('accounting_transactions.company_id', explode('!',$idlevel));
        }else if($level == 'M'){
            $accountingtransactions = DB::table($this->table)
                ->join('web_users', 'web_users.web_user_id', '=', 'accounting_transactions.trans_web_user_id')
                ->select('trans_id', 'accounting_transactions.data', 'web_users.address_unit', 'web_users.account_number as webuser', 'trans_first_post_date as trans_date', 'web_users.first_name','web_users.last_name', DB::raw('CONCAT(web_users.first_name, \' \', web_users.last_name) as web_user_name'), 'trans_payment_type as pay_method', 'trans_card_type as pay_type', 'trans_net_amount as net_amount', 'trans_convenience_fee as net_fee', 'trans_total_amount as net_charge', 'source as trans_source', 'trans_result_auth_code as auth_code', 'trans_status as status', 'trans_type as stype')
                ->where('is_convenience_fee_trans', '=', 0)
                ->where('accounting_transactions.property_id', '=', $idlevel);
        }


        return $accountingtransactions;

    }

    public function getTransactionDetail($trans_id){

        $query = DB::table($this->table)
            ->join('web_users', 'web_users.web_user_id', '=', 'accounting_transactions.trans_web_user_id')
            ->leftJoin('properties', 'properties.id', '=', 'accounting_transactions.property_id')
            ->leftJoin('companies', 'companies.id', '=', 'properties.id_companies')
            ->join('partners', 'partners.id', '=', 'properties.id_partners')
            ->leftjoin('transaction_events', 'transaction_events.trans_id', '=', 'accounting_transactions.trans_id')
            ->select('accounting_transactions.trans_id', 'partners.partner_title as partner', 'transaction_events.event', 'companies.company_name as group', 'properties.name_clients as merchant', 'web_users.address_unit', 'web_users.account_number as webuser', 'trans_first_post_date as trans_date', 'web_users.first_name','web_users.last_name', 'trans_payment_type as pay_method', 'trans_card_type as pay_type', 'trans_net_amount as net_amount', 'trans_convenience_fee as net_fee', 'trans_total_amount as net_charge', 'source as trans_source', 'trans_result_auth_code as auth_code', 'trans_status as status', 'trans_type as stype', 'trans_descr')
            ->where('is_convenience_fee_trans', '=', 0)
            ->where('accounting_transactions.trans_id', '=', $trans_id);
        $acctransactiondetail = $query->get();
        return $acctransactiondetail;

    }

    public function getPropertyEmail($trans_id){

        $propertyemail = DB::table($this->table)
            ->join('properties', 'properties.id', '=', 'accounting_transactions.property_id')
            ->select('properties.accounting_email_address_clients')
            ->where('accounting_transactions.trans_id', '=', $trans_id)
            ->get();

        return $propertyemail;

    }

    public function getReceipt($trans_id) {

        $receiptcontents = DB::table('log_payment_receipt')
            ->select('receipt_content')
            ->where('trans_id', '=', $trans_id)
            ->get();

        if(!empty($receiptcontents)){
            $receiptcontent = $receiptcontents[0]['receipt_content'];
            return $receiptcontent;
        }

    }

    public function getTransactionDetailWithPartner($trans_id){

        $transactiondetail = DB::table($this->table)
            ->leftJoin('properties', 'properties.id', '=', 'accounting_transactions.property_id')
            ->leftJoin('companies', 'companies.id', '=', 'properties.id_companies')
            ->join('partners', 'partners.id', '=', 'properties.id_partners')
            ->select('partners.id as partner_id', 'companies.id as company_id', 'properties.id as merchant_id', 'accounting_transactions.*')
            ->where('trans_id', '=', $trans_id)
            ->get();

        return $transactiondetail;

    }

    public function getWebUserDetail($web_user_id){

        $webuserdetail = DB::table('web_users')
            ->where('web_user_id', '=', $web_user_id)
            ->get();

        return $webuserdetail;

    }

}
