<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\Properties;
use Illuminate\Support\Facades\Session;
use Validator;
use App\Models\Partners;
use App\Models\Ivr;
use App\Models\MerchantAccount;
use DB;
use Illuminate\Support\Facades\Redirect;

class MerchantController extends Controller
{

    public function merchantlist($token, Request $request){

        list($data)=explode('|',Crypt::decrypt($token));
        $array_token = json_decode($data,1);
        $idlevel = $array_token['id'];
        $level = $array_token['type'];
        $idadmin = $array_token['iduser'];
        $token = Crypt::encrypt($data.'|'.time().'|'.config('app.appAPIkey'));

        //security
        $objAdminAuth = new AuthAdminController();
        $objAdminAuth->checkAuthPermissions($array_token['iduser'],null, ["B","P","G","M"]);

        $merchants = new Properties();
        $filter = \DataFilter::source($merchants->getMerchantList($idlevel, $level));
        $filter->attributes(array("id"=>"merchantRdatafilter"));

        switch (strtoupper($level)){
            case "B":
                $filter->add('partners.partner_title','Partner','text');
                $filter->add('companies.company_name','Groups Name','text');
                $filter->add('properties.name_clients','Merchant Name','text');
                break;
            case "P":
                $filter->add('partners.partner_title','Partner','text');
                $filter->add('companies.company_name','Groups Name','text');
                $filter->add('properties.name_clients','Merchant Name','text');
                break;
            case "G":
                $filter->add('companies.company_name','Groups Name','text');
                $filter->add('properties.name_clients','Merchant Name','text');
                break;
            case "M":
                $filter->add('properties.name_clients','Merchant Name','text');
                break;
        }

        $filter->add('properties.compositeID_clients','Merchant ID','text');
        $filter->add('properties.cc_svc','CC','select')->options(array('' => '--Has CC--','1' => 'Yes', '0' => 'No'))->scope(function($query, $value){
            if(!$value){
                return $query;
            }
            else{
                if($value == '0'){
                    return $query->whereNotIn('properties.id', function($query){
                        $query->from('merchant_account');
                        $query->select('property_id');
                        $query->where('payment_method', 'like', '%cc%');
                    });
                }else{
                    return $query->whereIn('properties.id', function($query){
                        $query->from('merchant_account');
                        $query->select('property_id');
                        $query->where('payment_method', 'like', '%cc%');
                    });
                }
            }

        });
        $filter->add('properties.ec_svc','EC','select')->options(array('' => '--Has EC--','1' => 'Yes', '0' => 'No'))->scope(function($query, $value){
            if(!$value){
                return $query;
            }
            else {
                if ($value == '0') {
                    return $query->whereNotIn('properties.id', function ($query) {
                        $query->from('merchant_account');
                        $query->select('property_id');
                        $query->where('payment_method', 'like', '%ec%');
                        $query->orWhere('payment_method', '=', 'ebill');
                    });
                } else {
                    return $query->whereIn('properties.id', function ($query) {
                        $query->from('merchant_account');
                        $query->select('property_id');
                        $query->where('payment_method', 'like', '%ec%');
                        $query->orWhere('payment_method', '=', 'ebill');
                    });
                }
            }

        });
        $filter->add('partners.status_pp','Status','select')->options(array('' => '--Status--','1' => 'Active', '0' => 'Inactive'));

        $filter->submit('search');
        $filter->reset('reset');
        $filter->build();
        //echo '<pre>';
        //print_r($filter); die;
        // die;
        $grid = \DataGrid::source($filter);
        $grid->attributes(array("class"=>"table table-striped table-hover"));
        //print_r($grid); die;
        $grid->add($token,'token')->style("display:none;");
        $grid->add($idlevel,'idlevel')->style("display:none;");
        $grid->add('id','ID')->style("display:none;");
        //$grid->add('name','Name', true)->style("width:100px");

        switch (strtoupper($level)){
            case "B":
                $grid->add('partner','Partner',  true);
                $grid->add('group','Groups Name');
                $grid->add('merchant','Merchant Name');
                break;
            case "P":
                $grid->add('partner','Partner',  true);
                $grid->add('group','Groups Name');
                $grid->add('merchant','Merchant Name');
                break;
            case "G":
                $grid->add('group','Groups Name');
                $grid->add('merchant','Merchant Name');
                break;
            case "M":
                $grid->add('merchant','Merchant Name');
                break;
        }

        $grid->add('merchant_id','Merchant ID');
        $grid->add('units','Units');
        $grid->add('a_users','Active Users');
        $grid->add('a2a_users','Authorized');
        $grid->add('ec_svc','EC Svc');
        $grid->add('cc_svc','CC Svc');
        $grid->add('status','Status')->cell( function($value){
            if($value == 1){
                return '<span class="label alert-success">Active</span>';
            }else if($value == 0){
                return '<span class="label alert-danger">Inactive</span>';
            }
        });
        //$grid->add('services','Services');
        //$grid->add('subaction','Actions');
        $grid->add('actionvalue','Action');
        $grid->row(function ($row) use ($level,$idlevel,$token,$idadmin){
            $id = $row->cell('id')->value;
            //$token = $row->cells[0]->value;
            $idlevel = $row->cells[1]->value;
            $status = $row->cell('status')->value;


            $edit_link = '';
            foreach(Session::get('user_permissions') as $p){
                if($p['route']=='mdetail'){
                    $edit_link = '<li><a href="#" onclick="showMerchant(\''.$id.'\')" >Edit</a></li>';
                    break;
                }
            }

            //to get group count
            $merchants = new Properties();
            $row->cell('a_users')->value = $merchants->getUsersbyStatusProperty($id, '1');
            $row->cell('a2a_users')->value = $merchants->getUsersbyStatusProperty($id, '998');
            if($idlevel == '-954581'){

                if($merchants->hasCC($id))$row->cell('cc_svc')->value = 'Active';
                else $row->cell('cc_svc')->value = 'Inactive';
                if($merchants->hasEC($id))$row->cell('ec_svc')->value = 'Active';
                else $row->cell('ec_svc')->value = 'Inactive';

            }else{

                $row->cell('a_users')->value = $merchants->getUsersbyStatusProperty($id, '1');
                $row->cell('a2a_users')->value = $merchants->getUsersbyStatusProperty($id, '998');

            }
            //$status = $row->cell('status')->value;
            //$row->cell('services')->value = $this->getPropertyServicesValues($id);
            //$row->cell('subaction')->value = $this->getPropertyActionsValues($id, $status);

            $smtoken =  \Illuminate\Support\Facades\Crypt::encrypt($level.'|'.$idlevel.'|'.time());
            $route_settings = route('smgeneral',['token'=>$smtoken]);
            //$link_sm = '<li><a href="'.$route_settings.'" >Settings Manager</a></li>';
            if($idlevel !='-954581')
            {
                $link_sm ='';
            }

            $pos = strpos($status, 'inactive');
            if($pos === false){
                $link_cms = '<li><a href="#" onclick="showMerchantStatusWarning(\''.$id.'\')" >Deactivate</a></li>';
            } else {
                $link_cms = '<li><a href="#" onclick="showMerchantStatusWarning(\''.$id.'\')" >Activate</a></li>';
            }

            $row->cell('actionvalue')->value = ' <div class="dropdown pull-right">
													  <button class="btn btn-xs btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><span style="color:#fff">Actions</span>
													  <span class="caret"></span></button>
													  <ul class="dropdown-menu">
														<li><a href="#" onclick="openg(\''.$id.'\',\''.$idadmin.'\');" >Open</a></li>
														'.$edit_link.'
														<li><a href=" '.route('merchantprofile', array('token'=>$token, 'id'=>$id)).'" >Profile</a></li>
														'.$link_sm.'
                                                                                                                '.$link_cms.'
													  </ul>
													</div>';

            $row->cell('id')->style("display:none;");
            $row->cells[0]->style("display:none;");
            $row->cells[1]->style("display:none;");


        });

        $grid->link('#',"Export", "TR", array('id' => 'exportMerchant', 'rel' => '/master/index.php/merchant/'.$token.'/export'));
        $grid->orderBy('properties.id','DESC');
        $grid->paginate(10);

        $sql =  $filter->query->toSql();

        return view('merchantlist',array('sql'=>$sql,'pageTitle'=>'Merchant Manager', 'filter' => $filter, 'grid' => $grid, 'atoken' => $token));

    }

    public function merchantdetail($token, $merchant_id){
        list($data)=explode('|',Crypt::decrypt($token));
        $array_token = json_decode($data,1);
        $idlevel = $array_token['id'];
        $level = $array_token['type'];
        $token = Crypt::encrypt($data.'|'.time().'|'.config('app.appAPIkey'));

        //security
        $objAdminAuth = new AuthAdminController();
        $objAdminAuth->checkAuthPermissions($array_token['iduser'],null,['B','P','G','M']);

        $merchants = new Properties();
        $merchantdetail = $merchants->getMerchantDetail($merchant_id);
        $merchantdetailHtml = view('merchantedit',array('pageTitle'=>'Edit Merchant Detail', 'merchantdetail' => $merchantdetail, 'token' => $token, 'idlevel' => $idlevel))->render();
        return response()->json( array('errcode' => 0, 'msg' => $merchantdetailHtml) );


    }

    public function updatemerchant(Request $request){

        $validator = Validator::make($request->all(),[
            'name_clients' => 'required',
            'contact_name_clients' => 'required',
            'email_address_clients' => 'required|email',
            'accounting_email_address_clients' => 'required|email',
            'units' => 'required|min:1'
        ]);
        if($validator->fails()){

            $validationMsg = '';
            $messages = $validator->messages();
            //echo '<pre>';
            //print_r($messages); die;
            if(!empty($messages)){
                foreach($messages->all() as $key => $error){
                    //echo '<pre>';
                    //print_r($error);
                    $validationMsg .=  $error.'<br/>';
                }
            }

            return json_encode(array('error' => 1, 'msg' => $validationMsg));

        }else{

            $merchants = new Properties();
            if($merchants->updateMerchantDetail($request->all())){
                return json_encode(array( 'error' => -1, 'msg' => 'Merchant information has been updated successfully.'));
            }
        }

    }

    public function merchantprofile($token, $merchantid, Request $request){
        list($data)=explode('|',Crypt::decrypt($token));
        $array_token = json_decode($data,1);
        $idlevel = $array_token['id'];
        $level = $array_token['type'];
        $idadmin = $array_token['iduser'];
        $token = Crypt::encrypt($data.'|'.time().'|'.config('app.appAPIkey'));

        //security
        $objAdminAuth = new AuthAdminController();
        $objAdminAuth->checkAuthPermissions($array_token['iduser'], null, ['B','P','G']);

        $partner_obj =  new Partners();
        $merchant_account_obj =  new Partners();
        $merchants = new Properties();
        $ivr_obj = new Ivr();
        $merchantdetail = $merchants->getMerchantProfile($merchantid);
        $ivr = $ivr_obj->getIvrByProperty($merchantdetail['id']);
        $merchant_groups = $merchants->getGroupsfromProperty($merchantid);
        $contact['contact_name_clients'] = $merchants->get1PropertyInfo($merchantid, 'contact_name_clients');
        $partners = $partner_obj->getAllPartners();
        //var_dump($merchantdetail); exit();
        return view('merchantprofile',array('adminid'=>$idadmin,'ivr'=>$ivr,'partners'=>$partners,'contact'=> $contact,'pageTitle'=>'Merchant Profile', 'merchantdetail' => $merchantdetail, 'merchant_groups' => $merchant_groups,'level'=>$level,'idlevel'=>$idlevel, 'propertyId' => $merchantid,  'token' => $token));
    }

    public function merchantprofilestore($token, $merchantid, Request $request){
        //$atoken=\Illuminate\Support\Facades\Crypt::decrypt($token);
        $name_clients ="";if(null !==$request->input('name_clients'))$name_clients=trim($request->input('name_clients'));
        $compositeID_clients ="";if(null !==$request->input('compositeID_clients'))$compositeID_clients=trim($request->input('compositeID_clients'));
        $playout_id ="";if(null !==$request->input('playout_id'))$playout_id=trim($request->input('playout_id'));
        $lockbox_id ="";if(null !==$request->input('lockbox_id'))$lockbox_id=trim($request->input('lockbox_id'));
        $bank_id ="";if(null !==$request->input('bank_id'))$bank_id=trim($request->input('bank_id'));
        $misc_id ="";if(null !==$request->input('misc_id'))$misc_id=trim($request->input('misc_id'));
        $contact_name_clients ="";if(null !==$request->input('contact_name_clients'))$contact_name_clients=trim($request->input('contact_name_clients'));
        $email_address_clients ="";if(null !==$request->input('email_address_clients'))$email_address_clients=trim($request->input('email_address_clients'));
        $accounting_email_address_clients ="";if(null !==$request->input('accounting_email_address_clients'))$accounting_email_address_clients=trim($request->input('accounting_email_address_clients'));
        $address_clients ="";if(null !==$request->input('address_clients'))$address_clients=trim($request->input('address_clients'));
        $city_clients ="";if(null !==$request->input('city_clients'))$city_clients=trim($request->input('city_clients'));
        $state_clients ="";if(null !==$request->input('state_clients'))$state_clients=trim($request->input('state_clients'));
        $zip_clients ="";if(null !==$request->input('zip_clients'))$zip_clients=trim($request->input('zip_clients'));
        $phone_clients ="";if(null !==$request->input('phone_clients'))$phone_clients=trim($request->input('phone_clients'));
        $units =0;if(null !==$request->input('units'))$units=trim($request->input('units'));
        $id_api_account =null;if(null !==$request->input('oldapiaccount'))$id_api_account=trim($request->input('oldapiaccount'));

        $statuspp =1;
        if(null==$request->input('statuspp'))
            $statuspp=0;

        $statusclients =1;
        if(null==$request->input('statusclients')){
            $statusclients=0;
            $statuspp=0;

            DB::table('merchant_account')
                ->where('property_id', $merchantid)
                ->where('payment_method', 'ec')->delete();
            DB::table('merchant_account')
                ->where('property_id', $merchantid)
                ->where('payment_method', 'eterm-ec')->delete();
            DB::table('merchant_account')
                ->where('property_id', $merchantid)
                ->where('payment_method', 'ebill')
                ->delete();

            DB::table('merchant_account')
                ->where('property_id', $merchantid)
                ->where('payment_method', 'cc')->delete();
            DB::table('merchant_account')
                ->where('property_id', $merchantid)
                ->where('payment_method', 'eterm-cc')->delete();
            DB::table('merchant_account')
                ->where('property_id', $merchantid)
                ->where('payment_method', 'amex')
                ->delete();
        }




        $newlogof ='';
        if($request->file('logo')){
            $newlogof=$merchantid.'_'.time().'_logo.jpg';
            Image::make($request->file('logo')->getRealPath())->widen(255, function ($constraint) {$constraint->upsize();})->heighten(72, function ($constraint) {$constraint->upsize();})->resizeCanvas(255, 72, 'center', false, 'ffffff')->save('/home/revopay/public_html/admin/public/logos/merchants/'.$newlogof,100);
        }

        DB::table('properties')
            ->where('id', $merchantid)
            ->update([
                'name_clients'=>$name_clients,
                'compositeID_clients'=>$compositeID_clients,
                'playout_id'=>$playout_id,
                'lockbox_id'=>$lockbox_id,
                'bank_id'=>$bank_id,
                'misc_field'=>$misc_id,
                'contact_name_clients'=>$contact_name_clients,
                'email_address_clients'=>$email_address_clients,
                'accounting_email_address_clients'=>$accounting_email_address_clients,
                'address_clients'=>$address_clients,
                'city_clients'=>$city_clients,
                'state_clients'=>$state_clients,
                'zip_clients'=>$zip_clients,
                'phone_clients'=>$phone_clients,
                'logo'=>$newlogof,
                'units'=>$units,
                'id_api_account'=>$id_api_account,
                'status_clients'=>$statusclients,
                'status_pp'=>$statuspp
            ]);

        return Redirect::back()->with('success', 'The element was saved successfully');
    }


    public function merchantivraccountStore($token,$id,Request $request){
        var_dump($request->all());

        $validation_type ="";if(null !==$request->input('validation_type'))$validation_type=trim($request->input('validation_type'));
        $phone_number ="";if(null !==$request->input('phone_number'))$phone_number=trim($request->input('phone_number'));
        $give_balance ="";if(null !==$request->input('give_balance'))$give_balance=trim($request->input('give_balance'));
        $ivr_id ="";if(null !==$request->input('ivr_id'))$ivr_id=trim($request->input('ivr_id'));

        $obj_ivr = new Ivr();
        $ivr = $obj_ivr->getIvrByProperty($id);
        if($ivr){
            DB::table('ivr')
                ->where('id_property', $id)
                ->update([
                    'ivr_id' => $ivr_id,
                    'give_balance' => $give_balance,
                    'type_validation' => $validation_type,
                    'phone_number' => $phone_number,
                ]);
        }
        else
        {
            DB::table('ivr')->insert([
                'id_property' => $id,
                'ivr_id' => $ivr_id,
                'give_balance' => $give_balance,
                'type_validation' => $validation_type,
                'phone_number' => $phone_number,
            ]);
        }

        return Redirect::back()->with('success', 'The element was saved successfully');

    }

    public function merchantpaymentcredentials($token, $merchantid, Request $request){

        list($data)=explode('|',Crypt::decrypt($token));
        $array_token = json_decode($data,1);
        $idlevel = $array_token['id'];
        $level = $array_token['type'];
        $token = Crypt::encrypt($data.'|'.time().'|'.config('app.appAPIkey'));

        //security
        $objAdminAuth = new AuthAdminController();
        $objAdminAuth->checkAuthPermissions($array_token['iduser']);

        $obj_ma = new MerchantAccount();
        $records = $obj_ma->getByProperty($merchantid);
        $data_all=array();
        $data_aux = array();

        foreach($records as $item){
            $data=array();
            if($item['payment_method']=='ec' || $item['payment_method']=='eterm-ec' || $item['payment_method']=='ebill'){
                $data['ecgateway']= $item['gateway'];
                $data['ecmid']= $item['payment_source_merchant_id'];
                $data['ecsourcekey']= $item['payment_source_key'];
                $data['ecstoreid']= $item['payment_source_store_id'];
                $data['eclocationid']= $item['payment_source_location_id'];
            }

            if($item['payment_method']=='ec' && $item['is_recurring']=='0'){
                $data['ecwot']=true;
                $data['ecwotlow_pay_range']=$item['low_pay_range'];
                $data['ecwothigh_pay_range']=$item['high_pay_range'];
                $data['ecwothigh_ticket']=$item['high_ticket'];
                $data['ecwotconvenience_fee']=$item['convenience_fee'];
                $data['ecwotconvenience_fee_float']=$item['convenience_fee_float'];
            }

            if($item['payment_method']=='ec' && $item['is_recurring']=='1'){
                $data['ecwr']=true;
                $data['ecwrlow_pay_range']=$item['low_pay_range'];
                $data['ecwrhigh_pay_range']=$item['high_pay_range'];
                $data['ecwrhigh_ticket']=$item['high_ticket'];
                $data['ecwrconvenience_fee']=$item['convenience_fee'];
                $data['ecwrconvenience_fee_float']=$item['convenience_fee_float'];
                $data['ecwrconvenience_fee_drp']=$item['convenience_fee_drp'];
                $data['ecwrconvenience_fee_float_drp']=$item['convenience_fee_float_drp'];
            }

            if($item['payment_method']=='eterm-ec' && $item['is_recurring']=='0'){
                $data['eceot']=true;
                $data['eceotlow_pay_range']=$item['low_pay_range'];
                $data['eceothigh_pay_range']=$item['high_pay_range'];
                $data['eceothigh_ticket']=$item['high_ticket'];
                $data['eceotconvenience_fee']=$item['convenience_fee'];
                $data['eceotconvenience_fee_float']=$item['convenience_fee_float'];
            }

            if($item['payment_method']=='eterm-ec' && $item['is_recurring']=='1'){
                $data['ecer']=true;
                $data['ecerlow_pay_range']=$item['low_pay_range'];
                $data['ecerhigh_pay_range']=$item['high_pay_range'];
                $data['ecerhigh_ticket']=$item['high_ticket'];
                $data['ecerconvenience_fee']=$item['convenience_fee'];
                $data['ecerconvenience_fee_float']=$item['convenience_fee_float'];
            }

            if($item['payment_method']=='ebill' && $item['is_recurring']=='0'){
                $data['ecev']=true;
                $data['ecevlow_pay_range']=$item['low_pay_range'];
                $data['ecevhigh_pay_range']=$item['high_pay_range'];
                $data['ecevhigh_ticket']=$item['high_ticket'];
                $data['ecevconvenience_fee']=$item['convenience_fee'];
                $data['ecevconvenience_fee_float']=$item['convenience_fee_float'];
            }




            if($item['payment_method']=='cc' || $item['payment_method']=='eterm-cc' || $item['payment_method']=='amex'){
                $data['ccgateway']= $item['gateway'];
                $data['ccmid']= $item['payment_source_merchant_id'];
                $data['ccsourcekey']= $item['payment_source_key'];
                $data['ccstoreid']= $item['payment_source_store_id'];
                $data['ccdisabletoken']= $item['novault'];

            }

            if($item['payment_method']=='cc' && $item['is_recurring']=='0'){
                $data['ccwot']=true;
                $data['ccwotlow_pay_range']=$item['low_pay_range'];
                $data['ccwothigh_pay_range']=$item['high_pay_range'];
                $data['ccwothigh_ticket']=$item['high_ticket'];
                $data['ccwotconvenience_fee']=$item['convenience_fee'];
                $data['ccwotconvenience_fee_float']=$item['convenience_fee_float'];
            }

            if($item['payment_method']=='cc' && $item['is_recurring']=='1'){
                $data['ccwr']=true;
                $data['ccwrlow_pay_range']=$item['low_pay_range'];
                $data['ccwrhigh_pay_range']=$item['high_pay_range'];
                $data['ccwrhigh_ticket']=$item['high_ticket'];
                $data['ccwrconvenience_fee']=$item['convenience_fee'];
                $data['ccwrconvenience_fee_float']=$item['convenience_fee_float'];
                $data['ccwrconvenience_fee_drp']=$item['convenience_fee_drp'];
                $data['ccwrconvenience_fee_float_drp']=$item['convenience_fee_float_drp'];
            }

            if($item['payment_method']=='eterm-cc' && $item['is_recurring']=='0'){
                $data['cceot']=true;
                $data['cceotlow_pay_range']=$item['low_pay_range'];
                $data['cceothigh_pay_range']=$item['high_pay_range'];
                $data['cceothigh_ticket']=$item['high_ticket'];
                $data['cceotconvenience_fee']=$item['convenience_fee'];
                $data['cceotconvenience_fee_float']=$item['convenience_fee_float'];
            }

            if($item['payment_method']=='eterm-cc' && $item['is_recurring']=='1'){
                $data['ccer']=true;
                $data['ccerlow_pay_range']=$item['low_pay_range'];
                $data['ccerhigh_pay_range']=$item['high_pay_range'];
                $data['ccerhigh_ticket']=$item['high_ticket'];
                $data['ccerconvenience_fee']=$item['convenience_fee'];
                $data['ccerconvenience_fee_float']=$item['convenience_fee_float'];
            }

            if($item['payment_method']=='amex' && $item['is_recurring']=='0'){
                $data['ccamex']=true;
                $data['ccamexlow_pay_range']=$item['low_pay_range'];
                $data['ccamexhigh_pay_range']=$item['high_pay_range'];
                $data['ccamexhigh_ticket']=$item['high_ticket'];
                $data['ccamexconvenience_fee']=$item['convenience_fee'];
                $data['ccamexconvenience_fee_float']=$item['convenience_fee_float'];
            }
            $data_all[]=$data;
            $data_aux = array_merge($data_aux, $data);
        }


        return view('merchant_payment_credentials',array('dataaux'=>$data_aux,'data'=>$data_all,'pageTitle'=>'Payment Credentials','level'=>$level,'idlevel'=>$idlevel, 'propertyId' => $merchantid, 'token' => $token));
    }

    public function merchantPCEcheckStore($token,$id,Request $request){


        $ecgateway ="";if(null !==$request->input('ecgateway'))$ecgateway=trim($request->input('ecgateway'));
        $ecmid ="";if(null !==$request->input('ecmid'))$ecmid=trim($request->input('ecmid'));
        $ecsourcekey ="";if(null !==$request->input('ecsourcekey'))$ecsourcekey=trim($request->input('ecsourcekey'));
        $ecstoreid ="";if(null !==$request->input('ecstoreid'))$ecstoreid=trim($request->input('ecstoreid'));
        $eclocationid ="";if(null !==$request->input('eclocationid'))$eclocationid=trim($request->input('eclocationid'));

        $ecwot ="";if(null !==$request->input('ecwot'))$ecwot=trim($request->input('ecwot'));
        $ecWOTlpr ="";if(null !==$request->input('ecWOTlpr'))$ecWOTlpr=trim($request->input('ecWOTlpr'));
        $ecWOThpr ="";if(null !==$request->input('ecWOThpr'))$ecWOThpr=trim($request->input('ecWOThpr'));
        $ecWOTht ="";if(null !==$request->input('ecWOTht'))$ecWOTht=trim($request->input('ecWOTht'));
        $ecWOTcf ="";if(null !==$request->input('ecWOTcf'))$ecWOTcf=trim($request->input('ecWOTcf'));
        $ecWOTlpcf ="";if(null !==$request->input('ecWOTlpcf'))$ecWOTlpcf=trim($request->input('ecWOTlpcf'));

        $ecwr ="";if(null !==$request->input('ecwr'))$ecwr=trim($request->input('ecwr'));
        $ecWRlpr ="";if(null !==$request->input('ecWRlpr'))$ecWRlpr=trim($request->input('ecWRlpr'));
        $ecWRhpr ="";if(null !==$request->input('ecWRhpr'))$ecWRhpr=trim($request->input('ecWRhpr'));
        $ecWRht ="";if(null !==$request->input('ecWRht'))$ecWRht=trim($request->input('ecWRht'));
        $ecWRcf ="";if(null !==$request->input('ecWRcf'))$ecWRcf=trim($request->input('ecWRcf'));
        $ecWRpcf ="";if(null !==$request->input('ecWRpcf'))$ecWRpcf=trim($request->input('ecWRpcf'));
        $ecWRcfDRP ="";if(null !==$request->input('ecWRcfDrp'))$ecWRcfDRP=trim($request->input('ecWRcfDrp'));
        $ecWRpcfDRP ="";if(null !==$request->input('ecWRpcfDrp'))$ecWRpcfDRP=trim($request->input('ecWRpcfDrp'));

        $eceot ="";if(null !==$request->input('eceot'))$eceot=trim($request->input('eceot'));
        $ecEOTlpr ="";if(null !==$request->input('ecEOTlpr'))$ecEOTlpr=trim($request->input('ecEOTlpr'));
        $ecEOThpr ="";if(null !==$request->input('ecEOThpr'))$ecEOThpr=trim($request->input('ecEOThpr'));
        $ecEOTht ="";if(null !==$request->input('ecEOTht'))$ecEOTht=trim($request->input('ecEOTht'));
        $ecEOTcf ="";if(null !==$request->input('ecEOTcf'))$ecEOTcf=trim($request->input('ecEOTcf'));
        $ecEOTpcf ="";if(null !==$request->input('ecEOTpcf'))$ecEOTpcf=trim($request->input('ecEOTpcf'));

        $ecer ="";if(null !==$request->input('ecer'))$ecer=trim($request->input('ecer'));
        $ecERlpr ="";if(null !==$request->input('ecERlpr'))$ecERlpr=trim($request->input('ecERlpr'));
        $ecERhpr ="";if(null !==$request->input('ecERhpr'))$ecERhpr=trim($request->input('ecERhpr'));
        $ecERht ="";if(null !==$request->input('ecERht'))$ecERht=trim($request->input('ecERht'));
        $ecERcf ="";if(null !==$request->input('ecERcf'))$ecERcf=trim($request->input('ecERcf'));
        $ecERpcf ="";if(null !==$request->input('ecERpcf'))$ecERpcf=trim($request->input('ecERpcf'));

        $ecev ="";if(null !==$request->input('ecev'))$ecev=trim($request->input('ecev'));
        $ecEVlpr ="";if(null !==$request->input('ecEVlpr'))$ecEVlpr=trim($request->input('ecEVlpr'));
        $ecEVhpr ="";if(null !==$request->input('ecEVhpr'))$ecEVhpr=trim($request->input('ecEVhpr'));
        $ecEVht ="";if(null !==$request->input('ecEVht'))$ecEVht=trim($request->input('ecEVht'));
        $ecEVcf ="";if(null !==$request->input('ecEVcf'))$ecEVcf=trim($request->input('ecEVcf'));
        $ecEVpcf ="";if(null !==$request->input('ecEVpcf'))$ecEVpcf=trim($request->input('ecEVpcf'));

        $obj_ma = new MerchantAccount();
        $records = $obj_ma->getByProperty($id);
        $all_submit = $request->all();


        if($records){
            DB::table('merchant_account')
                ->where('property_id', $id)
                ->where('payment_method', 'ec')->delete();
            DB::table('merchant_account')
                ->where('property_id', $id)
                ->where('payment_method', 'eterm-ec')->delete();
            DB::table('merchant_account')
                ->where('property_id', $id)
                ->where('payment_method', 'ebill')
                ->delete();
        }

        if($ecwot){
            DB::table('merchant_account')->insert([
                'property_id' => $id,
                'gateway' => $ecgateway,
                'low_pay_range' => $ecWOTlpr,
                'high_pay_range' => $ecWOThpr,
                'high_ticket' => $ecWOTht,
                'convenience_fee' => $ecWOTcf,
                'payment_source_key' => $ecsourcekey,
                'payment_source_store_id' => $ecstoreid,
                'payment_source_merchant_id' => $ecmid,
                'payment_source_location_id' => $eclocationid,
                'convenience_fee_float' => $ecWOTlpcf,
                'payment_method' => 'ec',
                'is_recurring' => '0',
            ]);
        }

        if($ecwr){
            DB::table('merchant_account')->insert([
                'property_id' => $id,
                'gateway' => $ecgateway,
                'low_pay_range' => $ecWRlpr,
                'high_pay_range' => $ecWRhpr,
                'high_ticket' => $ecWRht,
                'convenience_fee' => $ecWRcf,
                'payment_source_key' => $ecsourcekey,
                'payment_source_store_id' => $ecstoreid,
                'payment_source_merchant_id' => $ecmid,
                'payment_source_location_id' => $eclocationid,
                'convenience_fee_float' => $ecWRpcf,
                'convenience_fee_drp' => $ecWRcfDRP,
                'convenience_fee_float_drp' => $ecWRpcfDRP,
                'payment_method' => 'ec',
                'is_recurring' => '1',
            ]);
        }

        //dynamic form
        foreach ($all_submit as $field => $val){
            if(strpos($field,'ecWOTlprDYNAMIC')===0 && strpos($field,'ecWOTlprDYNAMIC{')===false ){
                $number = str_replace('ecWOTlprDYNAMIC','',$field);
                if($ecwot){
                    DB::table('merchant_account')->insert([
                        'property_id' => $id,
                        'payment_source_merchant_id' => $ecmid,
                        'gateway' => $ecgateway,
                        'payment_source_key' => $ecsourcekey,
                        'payment_source_store_id' => $ecstoreid,
                        'payment_source_location_id' => $eclocationid,
                        'low_pay_range' => $all_submit['ecWOTlprDYNAMIC'.$number],
                        'high_pay_range' => $all_submit['ecWOThprDYNAMIC'.$number],
                        'high_ticket' => $all_submit['ecWOThtDYNAMIC'.$number],
                        'convenience_fee' => $all_submit['ecWOTcfDYNAMIC'.$number],
                        'convenience_fee_float' => $all_submit['ecWOTlpcfDYNAMIC'.$number],
                        'payment_method' => 'ec',
                        'is_recurring' => '0',
                    ]);
                }
            }

            if(strpos($field,'ecWRlprDYNAMIC')===0 && strpos($field,'ecWRlprDYNAMIC{')===false ){
                $number = str_replace('ecWRlprDYNAMIC','',$field);
                if($ecwr){
                    DB::table('merchant_account')->insert([
                        'property_id' => $id,
                        'gateway' => $ecgateway,
                        'low_pay_range' => $all_submit['ecWRlprDYNAMIC'.$number],
                        'high_pay_range' => $all_submit['ecWRhprDYNAMIC'.$number],
                        'high_ticket' => $all_submit['ecWRhtDYNAMIC'.$number],
                        'convenience_fee' => $all_submit['ecWRcfDYNAMIC'.$number],
                        'convenience_fee_float' => $all_submit['ecWRpcfDYNAMIC'.$number],
                        'convenience_fee_drp' => $all_submit['ecWRcfDrpDYNAMIC'.$number],
                        'convenience_fee_float_drp' => $all_submit['ecWRpcfDrpDYNAMIC'.$number],
                        'payment_source_key' => $ecsourcekey,
                        'payment_source_store_id' => $ecstoreid,
                        'payment_source_merchant_id' => $ecmid,
                        'payment_source_location_id' => $eclocationid,
                        'payment_method' => 'ec',
                        'is_recurring' => '1',
                    ]);
                }
            }

        }




        if($eceot){
            DB::table('merchant_account')->insert([
                'property_id' => $id,
                'gateway' => $ecgateway,
                'low_pay_range' => $ecEOTlpr,
                'high_pay_range' => $ecEOThpr,
                'high_ticket' => $ecEOTht,
                'convenience_fee' => $ecEOTcf,
                'payment_source_key' => $ecsourcekey,
                'payment_source_store_id' => $ecstoreid,
                'payment_source_merchant_id' => $ecmid,
                'payment_source_location_id' => $eclocationid,
                'convenience_fee_float' => $ecEOTpcf,
                'payment_method' => 'eterm-ec',
                'is_recurring' => '0',
            ]);
        }

        if($ecer){
            DB::table('merchant_account')->insert([
                'property_id' => $id,
                'gateway' => $ecgateway,
                'low_pay_range' => $ecERlpr,
                'high_pay_range' => $ecERhpr,
                'high_ticket' => $ecERht,
                'convenience_fee' => $ecERcf,
                'payment_source_key' => $ecsourcekey,
                'payment_source_store_id' => $ecstoreid,
                'payment_source_merchant_id' => $ecmid,
                'payment_source_location_id' => $eclocationid,
                'convenience_fee_float' => $ecERpcf,
                'payment_method' => 'eterm-ec',
                'is_recurring' => '1',
            ]);
        }


        if($ecev){
            DB::table('merchant_account')->insert([
                'property_id' => $id,
                'gateway' => $ecgateway,
                'low_pay_range' => $ecEVlpr,
                'high_pay_range' => $ecEVhpr,
                'high_ticket' => $ecEVht,
                'convenience_fee' => $ecEVcf,
                'payment_source_key' => $ecsourcekey,
                'payment_source_store_id' => $ecstoreid,
                'payment_source_merchant_id' => $ecmid,
                'payment_source_location_id' => $eclocationid,
                'convenience_fee_float' => $ecEVpcf,
                'payment_method' => 'ebill',
                'is_recurring' => '0',
            ]);
        }


        return Redirect::back()->with('success', 'The element was saved successfully');
    }

    public function merchantPCCCStore($token,$id,Request $request){


        $ccgateway ="";if(null !==$request->input('ccgateway'))$ccgateway=trim($request->input('ccgateway'));
        $ccmid ="";if(null !==$request->input('ccmid'))$ccmid=trim($request->input('ccmid'));
        $ccsourcekey ="";if(null !==$request->input('ccsourcekey'))$ccsourcekey=trim($request->input('ccsourcekey'));
        $ccstoreid ="";if(null !==$request->input('ccstoreid'))$ccstoreid=trim($request->input('ccstoreid'));
        $ccdisabletoken ="";if(null !==$request->input('ccdisabletoken'))$ccdisabletoken=trim($request->input('ccdisabletoken'));



        $ccwot ="";if(null !==$request->input('ccwot'))$ccwot=trim($request->input('ccwot'));
        $ccWOTlpr ="";if(null !==$request->input('ccWOTlpr'))$ccWOTlpr=trim($request->input('ccWOTlpr'));
        $ccWOThpr ="";if(null !==$request->input('ccWOThpr'))$ccWOThpr=trim($request->input('ccWOThpr'));
        $ccWOTht ="";if(null !==$request->input('ccWOTht'))$ccWOTht=trim($request->input('ccWOTht'));
        $ccWOTcf ="";if(null !==$request->input('ccWOTcf'))$ccWOTcf=trim($request->input('ccWOTcf'));
        $ccWOTpcf ="";if(null !==$request->input('ccWOTpcf'))$ccWOTpcf=trim($request->input('ccWOTpcf'));



        $ccwr ="";if(null !==$request->input('ccwr'))$ccwr=trim($request->input('ccwr'));
        $ccWRlpr ="";if(null !==$request->input('ccWRlpr'))$ccWRlpr=trim($request->input('ccWRlpr'));
        $ccWRhpr ="";if(null !==$request->input('ccWRhpr'))$ccWRhpr=trim($request->input('ccWRhpr'));
        $ccWRht ="";if(null !==$request->input('ccWRht'))$ccWRht=trim($request->input('ccWRht'));
        $ccWRcf ="";if(null !==$request->input('ccWRcf'))$ccWRcf=trim($request->input('ccWRcf'));
        $ccWRpcf ="";if(null !==$request->input('ccWRpcf'))$ccWRpcf=trim($request->input('ccWRpcf'));
        $ccWRcfDrp ="";if(null !==$request->input('ccWRcfDrp'))$ccWRcfDrp=trim($request->input('ccWRcfDrp'));
        $ccWRpcfDrp ="";if(null !==$request->input('ccWRpcfDrp'))$ccWRpcfDrp=trim($request->input('ccWRpcfDrp'));

        $cceot ="";if(null !==$request->input('cceot'))$cceot=trim($request->input('cceot'));
        $ccEOTlpr ="";if(null !==$request->input('ccEOTlpr'))$ccEOTlpr=trim($request->input('ccEOTlpr'));
        $ccEOThpr ="";if(null !==$request->input('ccEOThpr'))$ccEOThpr=trim($request->input('ccEOThpr'));
        $ccEOTht ="";if(null !==$request->input('ccEOTht'))$ccEOTht=trim($request->input('ccEOTht'));
        $ccEOTcf ="";if(null !==$request->input('ccEOTcf'))$ccEOTcf=trim($request->input('ccEOTcf'));
        $ccEOTpcf ="";if(null !==$request->input('ccEOTpcf'))$ccEOTpcf=trim($request->input('ccEOTpcf'));

        $ccer ="";if(null !==$request->input('ccer'))$ccer=trim($request->input('ccer'));
        $ccERlpr ="";if(null !==$request->input('ccERlpr'))$ccERlpr=trim($request->input('ccERlpr'));
        $ccERhpr ="";if(null !==$request->input('ccERhpr'))$ccERhpr=trim($request->input('ccERhpr'));
        $ccERht ="";if(null !==$request->input('ccERht'))$ccERht=trim($request->input('ccERht'));
        $ccERcf ="";if(null !==$request->input('ccERcf'))$ccERcf=trim($request->input('ccERcf'));
        $ccERpcf ="";if(null !==$request->input('ccERpcf'))$ccERpcf=trim($request->input('ccERpcf'));

        $ccamex ="";if(null !==$request->input('ccamex'))$ccamex=trim($request->input('ccamex'));
        $ccAElpr ="";if(null !==$request->input('ccAElpr'))$ccAElpr=trim($request->input('ccAElpr'));
        $ccAEhpr ="";if(null !==$request->input('ccAEhpr'))$ccAEhpr=trim($request->input('ccAEhpr'));
        $ccAEht ="";if(null !==$request->input('ccAEht'))$ccAEht=trim($request->input('ccAEht'));
        $ccAEcf ="";if(null !==$request->input('ccAEcf'))$ccAEcf=trim($request->input('ccAEcf'));
        $ccAEpcf ="";if(null !==$request->input('ccAEpcf'))$ccAEpcf=trim($request->input('ccAEpcf'));

        $obj_ma = new MerchantAccount();
        $records = $obj_ma->getByProperty($id);
        $all_submit = $request->all();

        if($ccdisabletoken){
            $ccdisabletoken = 1;
        }
        else{
            $ccdisabletoken = 0;
        }


        if($records){
            DB::table('merchant_account')
                ->where('property_id', $id)
                ->where('payment_method', 'cc')->delete();
            DB::table('merchant_account')
                ->where('property_id', $id)
                ->where('payment_method', 'eterm-cc')->delete();
            DB::table('merchant_account')
                ->where('property_id', $id)
                ->where('payment_method', 'amex')
                ->delete();
        }

        if($ccwot){
            DB::table('merchant_account')->insert([
                'property_id' => $id,
                'gateway' => $ccgateway,
                'low_pay_range' => $ccWOTlpr,
                'high_pay_range' => $ccWOThpr,
                'high_ticket' => $ccWOTht,
                'convenience_fee' => $ccWOTcf,
                'payment_source_key' => $ccsourcekey,
                'payment_source_store_id' => $ccstoreid,
                'payment_source_merchant_id' => $ccmid,
                'novault' => $ccdisabletoken,
                'convenience_fee_float' => $ccWOTpcf,
                'payment_method' => 'cc',
                'is_recurring' => '0',
            ]);
        }

        if($ccwr){

            DB::table('merchant_account')->insert([
                'property_id' => $id,
                'gateway' => $ccgateway,
                'low_pay_range' => $ccWRlpr,
                'high_pay_range' => $ccWRhpr,
                'high_ticket' => $ccWRht,
                'convenience_fee' => $ccWRcf,
                'payment_source_key' => $ccsourcekey,
                'payment_source_store_id' => $ccstoreid,
                'payment_source_merchant_id' => $ccmid,
                'novault' => $ccdisabletoken,
                'convenience_fee_float' => $ccWRpcf,
                'convenience_fee_float_drp' => $ccWRpcfDrp,
                'convenience_fee_drp' => $ccWRcfDrp,
                'payment_method' => 'cc',
                'is_recurring' => '1',
            ]);
        }

        if($ccamex){
            DB::table('merchant_account')->insert([
                'property_id' => $id,
                'gateway' => $ccgateway,
                'low_pay_range' => $ccAElpr,
                'high_pay_range' => $ccAEhpr,
                'high_ticket' => $ccAEht,
                'convenience_fee' => $ccAEcf,
                'payment_source_key' => $ccsourcekey,
                'payment_source_store_id' => $ccstoreid,
                'payment_source_merchant_id' => $ccmid,
                'novault' => $ccdisabletoken,
                'convenience_fee_float' => $ccAEpcf,
                'payment_method' => 'amex',
                'is_recurring' => '0',
            ]);
        }


        //dynamic form
        foreach ($all_submit as $field => $val){

            if(strpos($field,'ccWOTlprDYNAMIC')===0 && strpos($field,'ccWOTlprDYNAMIC{')===false ){
                $number = str_replace('ccWOTlprDYNAMIC','',$field);
                if($ccwot){
                    DB::table('merchant_account')->insert([
                        'property_id' => $id,
                        'gateway' => $ccgateway,
                        'low_pay_range' => $all_submit['ccWOTlprDYNAMIC'.$number],
                        'high_pay_range' => $all_submit['ccWOThprDYNAMIC'.$number],
                        'high_ticket' => $all_submit['ccWOThtDYNAMIC'.$number],
                        'convenience_fee' => $all_submit['ccWOTcfDYNAMIC'.$number],
                        'convenience_fee_float' => $all_submit['ccWOTlpcfDYNAMIC'.$number],
                        'payment_source_key' => $ccsourcekey,
                        'payment_source_store_id' => $ccstoreid,
                        'payment_source_merchant_id' => $ccmid,
                        'novault' => $ccdisabletoken,
                        'payment_method' => 'cc',
                        'is_recurring' => '0',
                    ]);
                }
            }

            if(strpos($field,'ccWRlprDYNAMIC')===0 && strpos($field,'ccWRlprDYNAMIC{')===false ) {
                $number = str_replace('ccWRlprDYNAMIC', '', $field);
                if ($ccwr) {
                    DB::table('merchant_account')->insert([
                        'property_id' => $id,
                        'gateway' => $ccgateway,
                        'low_pay_range' => $all_submit['ccWRlprDYNAMIC'.$number],
                        'high_pay_range' => $all_submit['ccWRhprDYNAMIC'.$number],
                        'high_ticket' => $all_submit['ccWRhtDYNAMIC'.$number],
                        'convenience_fee' => $all_submit['ccWRcfDYNAMIC'.$number],
                        'convenience_fee_float' => $all_submit['ccWRpcfDYNAMIC'.$number],
                        'convenience_fee_drp' => $all_submit['ccWRcfDrpDYNAMIC'.$number],
                        'convenience_fee_float_drp' => $all_submit['ccWRpcfDrpDYNAMIC'.$number],
                        'payment_source_key' => $ccsourcekey,
                        'payment_source_store_id' => $ccstoreid,
                        'payment_source_merchant_id' => $ccmid,
                        'novault' => $ccdisabletoken,
                        'payment_method' => 'cc',
                        'is_recurring' => '1',
                    ]);
                }
            }

            if(strpos($field,'ccAElprDYNAMIC')===0 && strpos($field,'ccAElprDYNAMIC{')===false ) {
                $number = str_replace('ccAElprDYNAMIC', '', $field);
                if($ccamex){
                    DB::table('merchant_account')->insert([
                        'property_id' => $id,
                        'gateway' => $ccgateway,
                        'low_pay_range' => $all_submit['ccAElprDYNAMIC'.$number],
                        'high_pay_range' => $all_submit['ccAEhprDYNAMIC'.$number],
                        'high_ticket' =>$all_submit['ccAEhtDYNAMIC'.$number],
                        'convenience_fee' => $all_submit['ccAEcfDYNAMIC'.$number],
                        'convenience_fee_float' => $all_submit['ccAElpcfDYNAMIC'.$number],
                        'payment_source_key' => $ccsourcekey,
                        'payment_source_store_id' => $ccstoreid,
                        'payment_source_merchant_id' => $ccmid,
                        'novault' => $ccdisabletoken,
                        'payment_method' => 'amex',
                        'is_recurring' => '0',
                    ]);
                }
            }
        }




        if($cceot){
            DB::table('merchant_account')->insert([
                'property_id' => $id,
                'gateway' => $ccgateway,
                'low_pay_range' => $ccEOTlpr,
                'high_pay_range' => $ccEOThpr,
                'high_ticket' => $ccEOTht,
                'convenience_fee' => $ccEOTcf,
                'payment_source_key' => $ccsourcekey,
                'payment_source_store_id' => $ccstoreid,
                'payment_source_merchant_id' => $ccmid,
                'novault' => $ccdisabletoken,
                'convenience_fee_float' => $ccEOTpcf,
                'payment_method' => 'eterm-cc',
                'is_recurring' => '0',
            ]);
        }

        if($ccer){
            DB::table('merchant_account')->insert([
                'property_id' => $id,
                'gateway' => $ccgateway,
                'low_pay_range' => $ccERlpr,
                'high_pay_range' => $ccERhpr,
                'high_ticket' => $ccERht,
                'convenience_fee' => $ccERcf,
                'payment_source_key' => $ccsourcekey,
                'payment_source_store_id' => $ccstoreid,
                'payment_source_merchant_id' => $ccmid,
                'novault' => $ccdisabletoken,
                'convenience_fee_float' => $ccERpcf,
                'payment_method' => 'eterm-cc',
                'is_recurring' => '1',
            ]);
        }



        return Redirect::back()->with('success', 'The element was saved successfully');
    }

    public function merchanteventhistory($token, $merchantid, Request $request){
        list($data)=explode('|',Crypt::decrypt($token));
        $array_token = json_decode($data,1);
        $idlevel = $array_token['id'];
        $level = $array_token['type'];
        $token = Crypt::encrypt($data.'|'.time().'|'.config('app.appAPIkey'));

        //security
        $objAdminAuth = new AuthAdminController();
        $objAdminAuth->checkAuthPermissions($array_token['iduser']);

        $merchants = new Properties();
        $grid = \DataGrid::source($merchants->getEventHistoryByPropertyId($idlevel, $merchantid, $level));
        //print_r($grid); die;
        $grid->attributes(array("class"=>"table table-striped table-hover"));
        //print_r($grid); die;
        //$grid->add($idlevel,'idlevel')->style("display:none;");
        $grid->add('id','ID')->style("display:none;");
        //$grid->add('name','Name', true)->style("width:100px");
        $grid->add('errortype','Type');
        $grid->add('description','Description');
        $grid->add('date','Date');
        $grid->orderBy('id','DESC');
        $grid->paginate(10);
        return view('merchant_events',array('pageTitle'=>'Event History', 'grid' => $grid,'level'=>$level,'idlevel'=>$idlevel, 'propertyId' => $merchantid, 'token' => $token));

    }

    public function merchantticketreport($token, $merchantid, Request $request){

        list($data)=explode('|',Crypt::decrypt($token));
        $array_token = json_decode($data,1);
        $idlevel = $array_token['id'];
        $level = $array_token['type'];
        $token = Crypt::encrypt($data.'|'.time().'|'.config('app.appAPIkey'));

        //security
        $objAdminAuth = new AuthAdminController();
        $objAdminAuth->checkAuthPermissions($array_token['iduser']);

        //echo $idlevel.' == '.$level; die;
        $merchants = new Properties();
        $grid = \DataGrid::source($merchants->getTicketReportByPropertyId($idlevel, $merchantid, $level));
        //print_r($grid); die;
        $grid->attributes(array("class"=>"table table-striped table-hover"));
        //print_r($grid); die;
        //$grid->add($idlevel,'idlevel')->style("display:none;");
        $grid->add('id','ID')->style("display:none;");
        //$grid->add('name','Name', true)->style("width:100px");
        $grid->add('date','Date');
        $grid->add('name','Name');
        $grid->add('email','Email');
        $grid->add('phone','Phone');
        $grid->add('type','Type');
        $grid->add('status','Status')->cell( function($value){
            switch ($value){
                case 0:
                    return '<span class="label alert-danger">Open</span>';
                    break;
                case 1:
                    return '<span class="label alert-success">Closed</span>';
                    break;
                default:
                    return '<span class="label alert-warning">unknown</span>';
                    break;
            }
        });
        $grid->add('reqby','Request by')->cell( function($value){
            switch ($value){
                case 0:
                    return 'User';
                    break;
                case 1:
                    return 'Admin';
                    break;
            }
        });
        $grid->add('myac','');
        $grid->orderBy('id','DESC');
        $grid->paginate(10);
        return view('merchant_ticket_report',array('pageTitle'=>'Ticket Report', 'grid' => $grid,'level'=>$level,'idlevel'=>$idlevel, 'propertyId' => $merchantid, 'token' => $token));

    }

    public function merchantfraudcontrol($token, $merchantid, Request $request){
        list($data)=explode('|',Crypt::decrypt($token));
        $array_token = json_decode($data,1);
        $idlevel = $array_token['id'];
        $level = $array_token['type'];
        $token = Crypt::encrypt($data.'|'.time().'|'.config('app.appAPIkey'));

        //security
        $objAdminAuth = new AuthAdminController();
        $objAdminAuth->checkAuthPermissions($array_token['iduser']);

        $merchants = new Properties();
        $fraud_control_array = array();
        $fraud_control_config = $merchants->getFraudControlConfigByPropertyId($merchantid);
        if(!empty($fraud_control_config)){
            $fraud_control_array = (array) json_decode($fraud_control_config[0]['data']);
        }

        return view('merchant_fraud_alert_config',array('pageTitle'=>'Fraud Control Config', 'fraud_control_config' => $fraud_control_array,'level'=>$level,'idlevel'=>$idlevel, 'propertyId' => $merchantid, 'token' => $token));

    }

    public function merchantfraudcontrolstore($token, $merchantid, Request $request){
        $atoken=\Illuminate\Support\Facades\Crypt::decrypt($token);
        list($idlevel,$level) = explode('|',$atoken);
        $submit = $request->all();
        unset($submit['_token']);
        DB::table('fraud_control')->where('property_id','=',$merchantid)->update([
            'data' => json_encode($submit),
        ]);
        return Redirect::back()->with('success', 'The element was saved successfully');
    }

    public function changeMerchantStatusWarning($ftoken, $merchant_id) {
        list($data) = explode('|', Crypt::decrypt($ftoken));
        $array_token = json_decode($data, 1);
        $idlevel = $array_token['id'];
        $level = $array_token['type'];

        $token = Crypt::encrypt($data . '|' . time() . '|' . config('app.appAPIkey'));

        //security
        $objAdminAuth = new AuthAdminController();
        $objAdminAuth->checkAuthPermissions($array_token['iduser'],null,['B','P','G','M']);

        $merchants = new Properties();
        $merchantdetail = $merchants->getMerchantDetail($merchant_id);

        $merchantdetailHtml = view('changeMerchantStatusView', array('pageTitle' => 'Change Merchant Status', 'merchantdetail' => $merchantdetail, 'token' => $token, 'idlevel' => $idlevel))->render();
        return response()->json(array('errcode' => 0, 'msg' => $merchantdetailHtml));
    }

    public function updateMerchantStatus(Request $request) {
        $objMerchant = new Properties();
        $property_id = $request->id;
        $statusMerchant = $objMerchant->get1PropertyInfo($property_id, 'status_pp');
        $obWebUser = new \App\Models\WebUsers();
        $objAccTrans = new \App\Models\AccountingRecurringTransactions;
        $usersChanged = [];
        $autopaysChanged = [];
        $oneClickChanged = [];
        if (1 == $statusMerchant) {
            // Change all the autopays from status 1 to 5
            $accTransList = $objAccTrans->getTransactionIdByPropertyId($property_id, 1);
            foreach ($accTransList as $accTrans) {
                $accTrans['after'] = 5;
                $objAccTrans->updateTx($accTrans['trans_id'], ['trans_status' => $accTrans['after']]);
                array_push($autopaysChanged, $accTrans);
            }
            // Change all the users from status 1, 46 or 998 to 1001, 1046 or 1998
            $webUserList = $obWebUser->getActAutWebUsersByPropertyId($property_id, ['1', '46', '998']);
            foreach ($webUserList as $webUser) {
                $webUser['after'] = $webUser['web_status'] + 1000;
                $obWebUser->set1UserInfo($webUser['web_user_id'], 'web_status', $webUser['after']);
                array_push($usersChanged, $webUser);
            }
            // Change all the one click reminder status from 1 to 1001
            $oneClickReminderList = $obWebUser->getOClickReminderByIdProperty($property_id, 1);
            foreach($oneClickReminderList as $oneClick){
                $oneClick['after'] = 1001;
                $obWebUser->set1ClickReminderInfo($oneClick['id'], 'status', $oneClick['after']);
                array_push($oneClickChanged, $oneClick);
            }
            // Change the merchant status to 0
            $objMerchant->set1PropertyInfo($request->id, 'status_pp', 0);
        } else if (0 == $statusMerchant) {
            // Change all the autopays from status 5 to 1
            $accTransList = $objAccTrans->getTransactionIdByPropertyId($property_id, 5);
            foreach ($accTransList as $accTrans) {
                $accTrans['after'] = 1;
                $objAccTrans->updateTx($accTrans['trans_id'], ['trans_status' => $accTrans['after']]);
                array_push($autopaysChanged, $accTrans);
            }
            // Change all the users from status 1001, 1046 or 1998 to 1, 46 or 998
            $webUserList = $obWebUser->getActAutWebUsersByPropertyId($request->id, ['1001', '1046', '1998']);
            foreach ($webUserList as $webUser) {
                $webUser['after'] = $webUser['web_status'] - 1000;
                $obWebUser->set1UserInfo($webUser['web_user_id'], 'web_status', $webUser['after']);
                array_push($usersChanged, $webUser);
            }
            // Change all the one click reminder from status 1001 to 1
            $oneClickReminderList = $obWebUser->getOClickReminderByIdProperty($property_id, 1001);
            foreach($oneClickReminderList as $oneClick){
                $oneClick['after'] = 1;
                $obWebUser->set1ClickReminderInfo($oneClick['id'], 'status', $oneClick['after']);
                array_push($oneClickChanged, $oneClick);
            }
            // Change the merchant status to 1
            $objMerchant->set1PropertyInfo($request->id, 'status_pp', 1);
        }

        $this->sendEmailChangedStatus($property_id, $usersChanged, $autopaysChanged, $oneClickChanged);

        return json_encode(array('error' => -1, 'msg' => 'Merchant status has been updated successfully.'));
    }

    private function sendEmailChangedStatus($property_id, $usersChanged, $autopaysChanged, $oneClickChanged){
        $objMerchant = new Properties();
        $obWebUser = new \App\Models\WebUsers();
        $name_clients = $objMerchant->get1PropertyInfo($property_id, 'name_clients');

        $loggedNameUser = 'not specified';
        $web_user_id = Session::get('web_user_id');
        if (!empty($web_user_id)) {
            $firstName = $obWebUser->get1UserInfo($web_user_id, 'first_name');
            $lastName = $obWebUser->get1UserInfo($web_user_id, 'last_name');
            $loggedNameUser = $firstName . ' ' . $lastName;
        }

        $data = [];
        $data['subject'] = 'The merchant ' . $name_clients . ' status has been changed';
        $data['from'] = 'do_not_reply@revopayments.com';
        $data['to'] = 'customerservice@revopay.com';
        $data['body'] = '
                        <p>You have received this email because a merchant status has been changed by ' . $loggedNameUser . '. All the data changed are on the attached files.</p>
                        <p style="color: #666666; font-size: 12px;">Please do not reply to this email message, as this email was sent from a notification-only address.</p>';
        $data['files'] = [];

        $characters = ['\\', '\r', '\n', '<', '>', ':' , '/', '|', '¿' , '?', '*', ' '];
        $folderName = str_replace($characters, "", $name_clients);
        $path = public_path() . '/uploads/csv/merchants/statusChanged/' . $folderName . '/';
        $fileName = '';
        $headers = '';
        $fileExt = 'csv';
        // creates the users changed file
        if (!empty($usersChanged)) {
            $fileName = 'Users' . date("m-d-Y_h:i:s");
            $headers = ['User Id', 'Status before', 'Status after'];
            array_push($data['files'], $this->create_file($path, $fileName, $fileExt, $headers, $usersChanged));
        }
        // creates the autopays changed file
        if (!empty($autopaysChanged)) {
            $fileName = 'Autopays' . date("m-d-Y_h:i:s");
            $headers = ['Autopay Id', 'Status before', 'Status after'];
            array_push($data['files'], $this->create_file($path, $fileName, $fileExt, $headers, $autopaysChanged));
        }
        // creates the one click reminder changed file
        if (!empty($oneClickChanged)) {
            $fileName = 'OneClickReminder' . date("m-d-Y_h:i:s");
            $headers = ['OneClicReminder Id', 'Status before', 'Status after'];
            array_push($data['files'], $this->create_file($path, $fileName, $fileExt, $headers, $oneClickChanged));
        }
        // Send the emails
        \Illuminate\Support\Facades\Mail::send([], [], function($message) use ($data) {
            $message->from($data['from'])
                ->to($data['to'])
                ->subject($data['subject'])
                ->setBody($data['body'], 'text/html');

            foreach ($data['files'] as $file) {
                $message->attach($file);
            }
        });
    }
    
}
