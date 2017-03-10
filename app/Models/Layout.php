<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;

class Layout extends Model {

    function getLayoutValues($id){
        $result=DB::table('layout_values')->where('id_groups','=',$id)->get();
        $resultx=array();
        foreach($result as $rx){
            $resultx[$rx['key']]=$rx['value'];
        }
        if(!isset($resultx['layout_partner_partner'])){
            $resultx['layout_partner_partner']='Partner';
        }
        if(!isset($resultx['layout_company_company'])){
            $resultx['layout_company_company']='Group';
        }
        if(!isset($resultx['layout_property_property'])){
            $resultx['layout_property_property']='Merchant';
        }
        return $resultx;
    }

}