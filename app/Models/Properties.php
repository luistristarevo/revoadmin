<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\Customize;

class Properties extends Model {

    protected $table = 'properties';
    public $timestamps = false;

    function getPaymentType ($idproperty){
        $result=DB::table('payment_type')->select('payment_type_id','payment_type_name','amount','qty','qtymax')->where('property_id',$idproperty)->orderBy('payment_type_name')->get();
        if($result==NULL){
            $ids=  $this->getOnlyIds($idproperty);
            $p_type=  $this->getPropertySettings($idproperty, $ids['id_companies'], $ids['id_partners'], 'DEFAULTPC');
            if(empty($p_type)) $p_type='Payment';
            DB::table('payment_type')->insert(
                ['property_id' => $idproperty, 'payment_type_name' => $p_type]
            );
            $result=DB::table('payment_type')->select('payment_type_id','payment_type_name','amount')->where('property_id',$idproperty)->orderBy('payment_type_name')->get();
        }
        return $result;
    }

    function getReccurringPaymentType ($trans_id){
        $result=DB::table('recurring_trans_categories')->select('amount','category_id')->where('trans_id',$trans_id)->get();
        return $result;
    }

    function getOnlyIds($id_property){
        $var=$this->select('id_partners','id_companies')->where('id',$id_property)->first();
        return $var;
    }

    function getPropertySettings($idproperty,$idcompany,$idpartner, $key) {
        $obj_customize=new Customize();
        // try to get the settings in the property
        $idgroups=$obj_customize->getPropertiesGroup($idproperty);
        if(empty($idgroups)){
            $idgroups=$obj_customize->getCompaniesGroup($idcompany);
            if(empty($idgroups)){
                $id_groups=$obj_customize->getPartnersGroup($idpartner);
                if(!empty($id_groups)){
                    $val=$obj_customize->getSettingsValue($id_groups, $key);
                    return $val;
                }
                return NULL;
            }
            else {
                $val=$obj_customize->getSettingsValue($idgroups, $key);
                if($val!=NULL)return $val;
                $id_groups=$obj_customize->getPartnersGroup($idpartner);
                if(!empty($id_groups)){
                    $val=$obj_customize->getSettingsValue($id_groups, $key);
                    return $val;
                }
                return NULL;
            }
        }
        else {
            $val=$obj_customize->getSettingsValue($idgroups, $key);
            if($val!=NULL)return $val;
            $idgroups=$obj_customize->getCompaniesGroup($idcompany);
            if(empty($idgroups)){
                $id_groups=$obj_customize->getPartnersGroup($idpartner);
                if(!empty($id_groups)){
                    $val=$obj_customize->getSettingsValue($id_groups, $key);
                    return $val;
                }
                return NULL;
            }
            else {
                $val=$obj_customize->getSettingsValue($idgroups, $key);
                if($val!=NULL)return $val;
                $id_groups=$obj_customize->getPartnersGroup($idpartner);
                if(!empty($id_groups)){
                    $val=$obj_customize->getSettingsValue($id_groups, $key);
                    return $val;
                }
                return NULL;
            }
        }
    }

    function getcredRecurringCredentials ($idproperty,$eterm=""){
        $array_cred=array();
        //ech
        $ec=DB::table('merchant_account')->select('high_ticket','low_pay_range','high_pay_range','convenience_fee','convenience_fee_float','convenience_fee_drp','convenience_fee_float_drp')->where('property_id',$idproperty)->where('payment_method',$eterm.'ec')->where('is_recurring',1)->get();
        if(empty($ec)){
            $ec=DB::table('merchant_account')->select('high_ticket','low_pay_range','high_pay_range','convenience_fee','convenience_fee_float','convenience_fee_drp','convenience_fee_float_drp')->where('property_id',$idproperty)->where('payment_method','ec')->where('is_recurring',1)->get();
        }
        //cc
        $cc=DB::table('merchant_account')->select('high_ticket','low_pay_range','high_pay_range','convenience_fee','convenience_fee_float','convenience_fee_drp','convenience_fee_float_drp')->where('property_id',$idproperty)->where('payment_method',$eterm.'cc')->where('is_recurring',1)->get();
        if(empty($cc)){
            $cc=DB::table('merchant_account')->select('high_ticket','low_pay_range','high_pay_range','convenience_fee','convenience_fee_float','convenience_fee_drp','convenience_fee_float_drp')->where('property_id',$idproperty)->where('payment_method','cc')->where('is_recurring',1)->get();
        }
        //amex
        $amex=DB::table('merchant_account')->select('high_ticket','low_pay_range','high_pay_range','convenience_fee','convenience_fee_float','convenience_fee_drp','convenience_fee_float_drp')->where('property_id',$idproperty)->where('payment_method','amex')->where('is_recurring',1)->get();

        if(count($ec)>0)$array_cred['ec']=$ec;
        if(count($cc)>0)$array_cred['cc']=$cc;
        if(count($amex)>0)$array_cred['amex']=$amex;

        return $array_cred;
    }

    function getCredentialtype_isrecurring ($type,$idproperty,$isrecurring){
        if($type=='am') $type="amex";
        $var=DB::table('merchant_account')->select('payment_method','gateway','payment_source_key as key','payment_source_store_id as sid','payment_source_location_id as lid','payment_source_merchant_id as mid','high_ticket','low_pay_range','high_pay_range','convenience_fee','convenience_fee_float','convenience_fee_drp','convenience_fee_float_drp')->where('property_id',$idproperty)->where('payment_method',$type)->where('is_recurring',$isrecurring)->get();

        if(empty($var) && $type=='eterm-cc')
        {
            $type="cc";
            $var=DB::table('merchant_account')->select('payment_method','gateway','payment_source_key as key','payment_source_store_id as sid','payment_source_location_id as lid','payment_source_merchant_id as mid','high_ticket','low_pay_range','high_pay_range','convenience_fee','convenience_fee_float','convenience_fee_drp','convenience_fee_float_drp')->where('property_id',$idproperty)->where('payment_method',$type)->where('is_recurring',$isrecurring)->get();
        }else{
            if(empty($var) && $type=='eterm-ec'){
                $type="ec";
                $var=DB::table('merchant_account')->select('payment_method','gateway','payment_source_key as key','payment_source_store_id as sid','payment_source_location_id as lid','payment_source_merchant_id as mid','high_ticket','low_pay_range','high_pay_range','convenience_fee','convenience_fee_float','convenience_fee_drp','convenience_fee_float_drp')->where('property_id',$idproperty)->where('payment_method',$type)->where('is_recurring',$isrecurring)->get();
            }
        }

        return $var;
    }

    function getFreqAutpay($id_property,$id_companies,$id_partners, $checkRestriction = false){
        $freq= array('monthly'=>'Monthly','quarterly'=>'Quarterly','biannually'=>'Twice a Year','annually'=>'Annually','weekly'=>'Weekly','biweekly'=>'Twice a Month');

        if ($checkRestriction) {
            $cancel_restrictions = $this->getPropertySettings($id_property, $id_companies, $id_partners, 'CANCELRESTRICTIONS');
            if (!empty($cancel_restrictions) && $cancel_restrictions == 1) {
                return $freq;
            }
        }

        $arrayFreq=$this->getPropertySettings($id_property,$id_companies,$id_partners,'FREQAUTOPAY');
        if(empty($arrayFreq))return $freq;

        $explode= explode("|", $arrayFreq);
        $freq_autopay=array();
        for ($i=0; $i<count($explode);$i++){
            if($explode[$i]!='onetime' && $explode[$i]!='untilcancel'){
                $freq_autopay[$explode[$i]]=$freq[$explode[$i]];
            }
        }
        return $freq_autopay;

    }

    function getDaysAutopay($id_property,$id_companies,$id_partners, $checkRestriction = false){
        $daysAuto=$this->getPropertySettings($id_property, $id_companies, $id_partners, 'DAYSAUTOPAY');

        if ($checkRestriction) {
            $cancel_restrictions = $this->getPropertySettings($id_property, $id_companies, $id_partners, 'CANCELRESTRICTIONS');
            if (!empty($cancel_restrictions) && $cancel_restrictions == 1) {
                $daysAuto = '1|31';
            }
        }

        if(empty($daysAuto))$daysAuto= '1|31';
        $explode= explode("|", $daysAuto);
        $days = array();
        for($a = $explode[0]; $a <= $explode[1]; $a++)
        {
            $days[]=$a;
        }
        return $days;
    }

    function get5yearInAdvance($cancel=true, $cmonths = 20){
        $months= array('January','February','March','April','May','June','July','August','September','October','November','December');
        $time= date('m-Y');

        $date= explode("-", $time);
        $cont=0;
        $pos=$date[0]-1;
        $year=$date[1];
        $end_dates= array();
        $end=array();
        if($cancel){
            $end['value']="-1";
            $end['date']="Until Canceled";
            $end_dates[]=$end;
        }
        if($cmonths>0){
            while ($cont++ < $cmonths*12){
                $end['value']=$year.'|'.  str_pad(($pos+1),2,0,STR_PAD_LEFT);
                $end['date']=$months[$pos].', '.$year;
                $end_dates[]=$end;
                //var_dump($end_dates);
                $pos++;
                if($pos==12){$pos=0;$year++;}
            }
        }
        return $end_dates;
    }

    function getPropertyInfo ($idproperty){
        $result=DB::table('properties')->select('id','id_companies','id_partners','compositeID_clients','name_clients','address_clients','city_clients','state_clients','zip_clients','phone_clients','url_clients','email_address_clients','status_pp','logo','subdomain_clients','playout_id','accounting_email_address_clients','lockbox_id','bank_id','misc_field','units','id_api_account','status_clients')->where('id',$idproperty)->first();
        $result['logo']=$this->getPropertyLogo($result['logo'],$result['id_companies'],$result['id_partners']);
        $obj_companies=new Companies();
        $result['company_name']=$obj_companies->getCompanyNameById($result['id_companies']);
        return $result;
    }

    function getPropertyLogo($logo,$id_companies,$id_partners){
        if(!empty($logo) && file_exists('/home/revopay/public_html/public/logos/merchants/'.$logo)){
            return '/public/logos/merchants/'.$logo;
        }else {
            $obj_company= new Companies();
            return $obj_company->getCompanyLogo($id_companies,$id_partners);
        }
    }

    function getTypeCredentialByCycle($property_id,$cycle){
        $value=DB::table('merchant_account')->where('property_id',$property_id)->where('is_recurring',$cycle)->select('payment_method')->get();
        if(empty($value)) return "";
        $pay_method=array();
        for($i=0;$i<count($value);$i++){
            $pay_method[]=$value[$i]['payment_method'];
        }
        return $pay_method;
    }

    public function getMerchantList($idlevel,  $level, $whereCondition = array()){

        $merchants  = array();

        switch (strtoupper($level)){
            case "B":
                $query = DB::table($this->table)
                    ->join('partners', 'partners.id', '=', 'properties.id_partners')
                    ->join('companies', 'companies.id', '=', 'properties.id_companies')
                    ->select('properties.id', 'partners.partner_title as partner', 'companies.company_name as group', 'properties.name_clients as merchant','properties.compositeID_clients as merchant_id', 'properties.units',  DB::raw('\'0\' as a_users'), DB::raw('\'0\' as a2a_users'),'companies.compositeID_companies as group_id',   DB::raw('\'0\' as del_users'), DB::raw('\'0\' as ai_users'), DB::raw('\'0\' as na2a_users'), 'status_pp as status')
                    ->orderBy('merchant','asc');

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
                                if(($valueArray[0] == 'cc_svc') && ($valueArray[1] != '')){
                                    if($valueArray[1] == '0'){
                                        $query->whereNotIn('properties.id', function($query){
                                            $query->from('merchant_account');
                                            $query->select('property_id');
                                            $query->where('payment_method', 'like', '%cc%');
                                        });
                                    }else{
                                        $query->whereIn('properties.id', function($query){
                                            $query->from('merchant_account');
                                            $query->select('property_id');
                                            $query->where('payment_method', 'like', '%cc%');
                                        });
                                    }
                                }else if(($valueArray[0] == 'ec_svc') && ($valueArray[1] != '')){

                                    if($valueArray[1] == '0'){
                                        $query->whereNotIn('properties.id', function($query){
                                            $query->from('merchant_account');
                                            $query->select('property_id');
                                            $query->where('payment_method', 'like', '%ec%');
                                            $query->orWhere('payment_method', '=', 'ebill');
                                        });
                                    }else{
                                        $query->whereIn('properties.id', function($query){
                                            $query->from('merchant_account');
                                            $query->select('property_id');
                                            $query->where('payment_method', 'like', '%ec%');
                                            $query->orWhere('payment_method', '=', 'ebill');
                                        });
                                    }

                                }else if(($valueArray[0] != 'ec_svc') && ($valueArray[0] != 'cc_svc')){
                                    $query->whereRaw($value);
                                }
                            }
                        }

                    }


                }
                $merchants = $query;
                break;
            case "P":
                $query = DB::table($this->table)
                    ->join('partners', 'partners.id', '=', 'properties.id_partners')
                    ->join('companies', 'companies.id', '=', 'properties.id_companies')
                    ->select('properties.id', 'partners.partner_title as partner', 'companies.company_name as group', 'properties.name_clients as merchant','properties.compositeID_clients as merchant_id', 'properties.units',  DB::raw('\'0\' as a_users'), DB::raw('\'0\' as a2a_users'),'companies.compositeID_companies as group_id',   DB::raw('\'0\' as del_users'), DB::raw('\'0\' as ai_users'), DB::raw('\'0\' as na2a_users'), 'status_pp as status')
                    ->orderBy('merchant','asc')
                    ->whereIn('properties.id_partners',explode('!',$idlevel));
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
                                if(($valueArray[0] == 'cc_svc') && ($valueArray[1] != '')){
                                    if($valueArray[1] == '0'){
                                        $query->whereNotIn('properties.id', function($query){
                                            $query->from('merchant_account');
                                            $query->select('property_id');
                                            $query->where('payment_method', 'like', '%cc%');
                                        });
                                    }else{
                                        $query->whereIn('properties.id', function($query){
                                            $query->from('merchant_account');
                                            $query->select('property_id');
                                            $query->where('payment_method', 'like', '%cc%');
                                        });
                                    }
                                }else if(($valueArray[0] == 'ec_svc') && ($valueArray[1] != '')){

                                    if($valueArray[1] == '0'){
                                        $query->whereNotIn('properties.id', function($query){
                                            $query->from('merchant_account');
                                            $query->select('property_id');
                                            $query->where('payment_method', 'like', '%ec%');
                                            $query->orWhere('payment_method', '=', 'ebill');
                                        });
                                    }else{
                                        $query->whereIn('properties.id', function($query){
                                            $query->from('merchant_account');
                                            $query->select('property_id');
                                            $query->where('payment_method', 'like', '%ec%');
                                            $query->orWhere('payment_method', '=', 'ebill');
                                        });
                                    }

                                }else if(($valueArray[0] != 'ec_svc') && ($valueArray[0] != 'cc_svc')){
                                    $query->whereRaw($value);
                                }
                            }
                        }

                    }


                }
                $merchants = $query;
                break;
            case "G":
                $query = DB::table($this->table)
                    ->join('partners', 'partners.id', '=', 'properties.id_partners')
                    ->join('companies', 'companies.id', '=', 'properties.id_companies')
                    ->select('properties.id', 'partners.partner_title as partner', 'companies.company_name as group', 'properties.name_clients as merchant','properties.compositeID_clients as merchant_id', 'properties.units',  DB::raw('\'0\' as a_users'), DB::raw('\'0\' as a2a_users'),'companies.compositeID_companies as group_id',   DB::raw('\'0\' as del_users'), DB::raw('\'0\' as ai_users'), DB::raw('\'0\' as na2a_users'), 'status_pp as status')
                    ->orderBy('merchant','asc')
                    ->whereIn('properties.id_companies',explode('!',$idlevel));
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
                                if(($valueArray[0] == 'cc_svc') && ($valueArray[1] != '')){
                                    if($valueArray[1] == '0'){
                                        $query->whereNotIn('properties.id', function($query){
                                            $query->from('merchant_account');
                                            $query->select('property_id');
                                            $query->where('payment_method', 'like', '%cc%');
                                        });
                                    }else{
                                        $query->whereIn('properties.id', function($query){
                                            $query->from('merchant_account');
                                            $query->select('property_id');
                                            $query->where('payment_method', 'like', '%cc%');
                                        });
                                    }
                                }else if(($valueArray[0] == 'ec_svc') && ($valueArray[1] != '')){

                                    if($valueArray[1] == '0'){
                                        $query->whereNotIn('properties.id', function($query){
                                            $query->from('merchant_account');
                                            $query->select('property_id');
                                            $query->where('payment_method', 'like', '%ec%');
                                            $query->orWhere('payment_method', '=', 'ebill');
                                        });
                                    }else{
                                        $query->whereIn('properties.id', function($query){
                                            $query->from('merchant_account');
                                            $query->select('property_id');
                                            $query->where('payment_method', 'like', '%ec%');
                                            $query->orWhere('payment_method', '=', 'ebill');
                                        });
                                    }

                                }else if(($valueArray[0] != 'ec_svc') && ($valueArray[0] != 'cc_svc')){
                                    $query->whereRaw($value);
                                }
                            }
                        }

                    }


                }
                $merchants = $query;
                break;
            case "M":
                $query = DB::table($this->table)
                    ->join('partners', 'partners.id', '=', 'properties.id_partners')
                    ->join('companies', 'companies.id', '=', 'properties.id_companies')
                    ->select('properties.id', 'partners.partner_title as partner', 'companies.company_name as group', 'properties.name_clients as merchant','properties.compositeID_clients as merchant_id', 'properties.units',  DB::raw('\'0\' as a_users'), DB::raw('\'0\' as a2a_users'),'companies.compositeID_companies as group_id',   DB::raw('\'0\' as del_users'), DB::raw('\'0\' as ai_users'), DB::raw('\'0\' as na2a_users'), 'status_pp as status')
                    ->orderBy('merchant','asc')
                    ->whereIn('properties.id',explode('!',$idlevel));
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
                                if(($valueArray[0] == 'cc_svc') && ($valueArray[1] != '')){
                                    if($valueArray[1] == '0'){
                                        $query->whereNotIn('properties.id', function($query){
                                            $query->from('merchant_account');
                                            $query->select('property_id');
                                            $query->where('payment_method', 'like', '%cc%');
                                        });
                                    }else{
                                        $query->whereIn('properties.id', function($query){
                                            $query->from('merchant_account');
                                            $query->select('property_id');
                                            $query->where('payment_method', 'like', '%cc%');
                                        });
                                    }
                                }else if(($valueArray[0] == 'ec_svc') && ($valueArray[1] != '')){

                                    if($valueArray[1] == '0'){
                                        $query->whereNotIn('properties.id', function($query){
                                            $query->from('merchant_account');
                                            $query->select('property_id');
                                            $query->where('payment_method', 'like', '%ec%');
                                            $query->orWhere('payment_method', '=', 'ebill');
                                        });
                                    }else{
                                        $query->whereIn('properties.id', function($query){
                                            $query->from('merchant_account');
                                            $query->select('property_id');
                                            $query->where('payment_method', 'like', '%ec%');
                                            $query->orWhere('payment_method', '=', 'ebill');
                                        });
                                    }

                                }else if(($valueArray[0] != 'ec_svc') && ($valueArray[0] != 'cc_svc')){
                                    $query->whereRaw($value);
                                }
                            }
                        }

                    }


                }
                $merchants = $query;
                break;
        }

        return $merchants;
    }

    public function getUsersbyStatusProperty($idproperty, $status){

        $webusercount = DB::table('web_users')
            ->select(DB::raw('count(*) as web_user_count'))
            ->where('web_status', '=', $status)
            ->where('property_id', '=', $idproperty)
            ->get();

        if(!empty($webusercount)){
            return $webusercount[0]['web_user_count'];
        }else{
            return 0;
        }

    }

    public function getMerchantDetail($merchant_id){
        $query = DB::table($this->table)
            ->select('*')
            ->where('properties.id', '=', $merchant_id);

        $merchantdetail = $query->get();

        return $merchantdetail;

    }

    public function updateMerchantDetail($merchantdata = array()){

        DB::table($this->table)
            ->where('id', $merchantdata['id'])
            ->update(['last_updated_by' => 'admin', 'name_clients' => str_replace("'",'', $merchantdata['name_clients']), 'compositeID_clients' => str_replace("'",'', $merchantdata['compositeID_clients']), 'address_clients' => str_replace("'",'', $merchantdata['address_clients']), 'city_clients' => str_replace("'",'', $merchantdata['city_clients']), 'state_clients' => $merchantdata['state_clients'], 'zip_clients' => str_replace("'",'',$merchantdata['zip_clients']), 'contact_name_clients' => str_replace("'",'', $merchantdata['contact_name_clients']), 'email_address_clients' => str_replace("'",'', $merchantdata['email_address_clients']),'units' => $merchantdata['units'], 'accounting_email_address_clients' => str_replace("'",'', $merchantdata['accounting_email_address_clients']), 'phone_clients' => $merchantdata['phone_clients']]);

        return true;

    }

    public function getMerchantProfile($merchant_id){

        return $this->getPropertyInfo($merchant_id);

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

    public function getPartnerIDByProperty($merchant_id){

        $partner_id = array();
        $query = DB::table($this->table)
            ->select('id_partners')
            ->where('properties.id', '=', $merchant_id);
        $partner_id = $query->get();
        if(!empty($partner_id)){
            return $partner_id[0]['id_partners'];
        }
        return false;
    }

    function get1PropertyInfo($property_id,$key){
        $info= DB::table('properties')->select($key)->where('id','=',$property_id)->first();
        return $info[$key];
    }

    public function getEventHistoryByPropertyId($idlevel, $propertyId, $level){

        $event_history_list = array();
        $query = DB::table('global_events')
            ->select('global_events.id', 'global_events.description', 'global_events.errortype', 'global_events.date')
            ->where('global_events.id_property', '=', $propertyId);
        $event_history_list = $query;
        return $event_history_list;

    }

    public function getTicketReportByPropertyId($idlevel, $propertyId, $level){

        $ticket_reports = array();
        $query = DB::table('tickets')
            ->select('ticket_id as id', 'ticket_date_submitted as date', 'ticket_name as name', 'ticket_email as email', 'ticket_phone as phone', 'ticket_type as type', 'ticket_status as status', 'ticket_user_type as reqby', 'ticket_user_id as id_user', 'flag')
            ->where('tickets.ticket_property', '=', $propertyId);
        $ticket_reports = $query;
        return $ticket_reports;

    }

    public function getFraudControlConfigByPropertyId($propertyId){

        $fraud_control = DB::table('fraud_control')
            ->select('data')
            ->where('fraud_control.property_id', '=', $propertyId)
            ->orderBy('id', 'desc')
            ->take(1)
            ->get();
        return $fraud_control;

    }

    function set1PropertyInfo($property_id,$key,$value){
        DB::table('properties')->where('id','=',$property_id)->update(array($key=>$value));
    }

    public function getPropertyUrlById($property_id){

        $propertyurl = DB::table($this->table)
            ->where('id', '=', $property_id)
            ->select('url_clients')
            ->get();

        return $propertyurl;

    }

    public function getPropertyNameById($property_id){

        $propertyname = DB::table($this->table)
            ->where('id', '=', $property_id)
            ->select('name_clients')
            ->get();

        return $propertyname;

    }

    function getcredOneTimeCredentials ($idproperty, $eterm=""){
        $array_cred=array();
        //ech
        $ec=DB::table('merchant_account')->select('high_ticket','low_pay_range','high_pay_range','convenience_fee','convenience_fee_float')->where('property_id',$idproperty)->where('payment_method',$eterm.'ec')->where('is_recurring',0)->get();
        if(empty($ec))
        {
            $ec=DB::table('merchant_account')->select('high_ticket','low_pay_range','high_pay_range','convenience_fee','convenience_fee_float')->where('property_id',$idproperty)->where('payment_method','ec')->where('is_recurring',0)->get();
        }
        //cc
        $cc=DB::table('merchant_account')->select('high_ticket','low_pay_range','high_pay_range','convenience_fee','convenience_fee_float')->where('property_id',$idproperty)->where('payment_method',$eterm.'cc')->where('is_recurring',0)->get();
        if(empty($cc))
        {
            $cc=DB::table('merchant_account')->select('high_ticket','low_pay_range','high_pay_range','convenience_fee','convenience_fee_float')->where('property_id',$idproperty)->where('payment_method','cc')->where('is_recurring',0)->get();
        }
        //amex
        $amex=DB::table('merchant_account')->select('high_ticket','low_pay_range','high_pay_range','convenience_fee','convenience_fee_float')->where('property_id',$idproperty)->where('payment_method','amex')->where('is_recurring',0)->get();
        if(count($ec)>0)$array_cred['ec']=$ec;
        if(count($cc)>0)$array_cred['cc']=$cc;
        if(count($amex)>0)$array_cred['amex']=$amex;
        return $array_cred;
    }

    function getLayoutID($property_id){
        $result=DB::table('properties')->select('playout_id')->where('id','=',$property_id)->first();
        if($result['playout_id']>0)return $result['playout_id'];
        $id_company=$this->get1PropertyInfo($property_id,'id_companies');
        $result=DB::table('companies')->select('clayout_id')->where('id','=',$id_company)->first();
        if($result['clayout_id']>0)return $result['clayout_id'];
        $id_partner=$this->get1PropertyInfo($property_id,'id_partners');
        $result=DB::table('partners')->select('layout_id')->where('id','=',$id_partner)->first();
        return $result['layout_id'];
    }
}
