<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;

class Categories extends Model {
     
    protected $table = 'payment_type';
    protected $softDelete = false;
    public $timestamps = false;

    function getCategoriesPGM($id,$type){
        switch (strtolower($type)){
            case 'b':
                $result=DB::table('trans_categories')
                    ->join('accounting_transactions', 'trans_categories.trans_id', '=', 'accounting_transactions.trans_id')
                    ->join('partners', 'trans_categories.id_partners', '=', 'partners.id')
                    ->join('companies', 'trans_categories.id_companies', '=', 'companies.id')
                    ->join('properties', 'trans_categories.id_properties', '=', 'properties.id')
                    ->select('trans_first_post_date',
                        'partners.partner_title','companies.company_name','name_clients',
                        'account_number','name','qty','amount','category_name');
                return $result;
                break;
            case 'p':
                $result=DB::table('trans_categories')
                    ->join('accounting_transactions', 'trans_categories.trans_id', '=', 'accounting_transactions.trans_id')
                    ->join('partners', 'trans_categories.id_partners', '=', 'partners.id')
                    ->join('companies', 'trans_categories.id_companies', '=', 'companies.id')
                    ->join('properties', 'trans_categories.id_properties', '=', 'properties.id')
                    ->select('trans_first_post_date',
                        'partners.partner_title','companies.company_name','name_clients',
                        'account_number','name','qty','amount','category_name')
                    ->whereIn('trans_categories.id_partners', explode('!',$id));
                return $result;
                break;

            case 'g':
                $result=DB::table('trans_categories')
                    ->join('accounting_transactions', 'trans_categories.trans_id', '=', 'accounting_transactions.trans_id')
                    ->join('partners', 'trans_categories.id_partners', '=', 'partners.id')
                    ->join('companies', 'trans_categories.id_companies', '=', 'companies.id')
                    ->join('properties', 'trans_categories.id_properties', '=', 'properties.id')
                    ->select('trans_first_post_date',
                        'companies.company_name','name_clients',
                        'account_number','name','qty','amount','category_name')
                    ->whereIn('trans_categories.id_partners',  explode('!',$id));
                return $result;
                break;

            case 'm':
                $result=DB::table('trans_categories')
                    ->join('accounting_transactions', 'trans_categories.trans_id', '=', 'accounting_transactions.trans_id')
                    ->join('partners', 'trans_categories.id_partners', '=', 'partners.id')
                    ->join('companies', 'trans_categories.id_companies', '=', 'companies.id')
                    ->join('properties', 'trans_categories.id_properties', '=', 'properties.id')
                    ->select('trans_first_post_date',
                        'name_clients',
                        'account_number','name','qty','amount','category_name')
                    ->whereIn('trans_categories.id_partners', explode('!',$id));
                return $result;
                break;

            default:
                return null;

        }
    }


}