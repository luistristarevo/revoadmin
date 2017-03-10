<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;

class AccountingReturned extends Model {
     
    protected $table = 'accounting_returned';
    protected $softDelete = false;
    public $timestamps = false;


    function getReturnedByLevelIdlevel($level, $idlevel){
        $level=strtoupper($level);
        $query = DB::table('accounting_returned')
            ->join('partners', 'partners.id', '=', 'accounting_returned.id_partner')
            ->join('companies', 'companies.id', '=', 'accounting_returned.id_company')
            ->join('properties', 'properties.id', '=', 'accounting_returned.id_property')
            ->select('partners.partner_title as partner', 'companies.company_name as group', 'properties.name_clients as merchant',
                'useraccount','username','amount','reason','rdate')
            ->orderBy('rdate','DESC');
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
