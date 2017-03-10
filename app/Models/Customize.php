<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Customize extends Model {

    protected $table = 'settings_values';
    protected $softDelete = false;
    public $timestamps = false;

    function getPropertiesGroup($idLevel){
        $id_groups=DB::table('properties_settings_groups')->where('id_properties', '=',$idLevel)->select('id_settings_groups')->first();
        if(empty($id_groups))return 0;
        return $id_groups['id_settings_groups'];
    }

    function getSettingsValue($id_group,$key){
        if($id_group<=0)return NULL;
        $results = $this-> where('id_groups', '=', $id_group)->where('key','like',$key)-> select('value')->first();
        if(empty($results)) return NULL;
        return $results['value'];
    }

    function getCompaniesGroup($idLevel){
        $idgroups=DB::table('companies_settings_groups')->where('id_companies', '=',$idLevel)->select('id_settings_groups')->first();
        if(empty($idgroups))return 0;
        return $idgroups['id_settings_groups'];
    }

    function getPartnersGroup($idlevel){
        $id_groups=DB::table('partners_settings_groups')->where('id_partners', '=',$idlevel)->select('id_settings_groups')->first();
        if(empty($id_groups))return 0;
        return $id_groups['id_settings_groups'];
    }

    function getSettingsValueProperties($id_partner,$id_company,$id_prop_group,$published = 1){
        $id_group=$this->getCompaniesGroup($id_company);
        $groupvalues = $this->getSettingsValuesGroup($id_group, $id_partner);
        if($id_prop_group>0){
            if($published == 'all'){
                $results = DB::table('settings_values') -> where('id_groups', '=', $id_prop_group)->select('id','value','key')->get();
            }
            else{
                $results = DB::table('settings_values') -> where('id_groups', '=', $id_prop_group)->where('published','=',$published)->select('id','value','key')->get();
            }

            $propIdKey=array();
            foreach($results as $idkey){
                $propIdKey[strtoupper($idkey['key'])]=$idkey['value'];
            }
            foreach ($propIdKey as $key=>$value){
                $groupvalues[$key]=$value;
            }
        }
        return $groupvalues;
    }

    function getSettingsValuesGroup($id_group,$id_partner,$published = 1){
        $id_groups=$this->getPartnersGroup($id_partner);
        $partnervalue = $this->getSettingsValuesPartner($id_groups);
        if($id_group>0){
            $groupIdKey = array();
            if($published == 'all'){
                $results = DB::table('settings_values')-> where('id_groups', '=', $id_group)-> select('id','value','key')->get();
            }
            else{
                $results = DB::table('settings_values')-> where('id_groups', '=', $id_group)->where('published','=',$published)-> select('id','value','key')->get();
            }
            //set parnter values to group
            foreach($results as $idkey){
                $groupIdKey[strtoupper($idkey['key'])]=$idkey['value'];
            }
            foreach ($groupIdKey as $key=>$value){
                $partnervalue[$key]=$value;
            }
        }
        return $partnervalue;


    }

    function getSettingsValuesPartner($id_groups,$published = 1){
        $partIdKey = array();

        if($id_groups>0){
            if($published == 'all'){
                $results = DB::table('settings_values')-> where('id_groups','=', $id_groups)-> select('id','value','key')->get();
            }
            else{
                $results = DB::table('settings_values')-> where('id_groups','=', $id_groups)->where('published','=',$published)-> select('id','value','key')->get();
            }

            foreach($results as $idkey){
                $partIdKey[strtoupper($idkey['key'])]=$idkey['value'];
            }
        }
        return $partIdKey;

    }

    function getSettingValueProperty($id_partner,$id_company,$id_prop_group,$key){
        $id_group=$this->getCompaniesGroup($id_company);
        $groupvalues = $this->getSettingValueGroup($id_group, $id_partner,$key);
        if($id_prop_group>0){
            $groupvalue=$this->getSettingsValue($id_prop_group,$key);
            if($groupvalue==NULL)return $groupvalues;
            return $groupvalue;
        }
        return $groupvalues;
    }

    function getSettingValueGroup($id_group,$id_partner,$key){
        $id_groups=$this->getPartnersGroup($id_partner);
        $partnervalue = $this->getSettingsValue($id_groups,$key);
        if($id_group>0){
            $groupvalue=$this->getSettingsValue($id_group,$key);
            if($groupvalue==NULL)return $partnervalue;
            return $groupvalue;
        }
        return $partnervalue;
    }
    
}

