<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Crypt;
use App\Models\WebUsers;
use Illuminate\Support\Facades\Session;
use Validator;
use App\Models\Transations;
use App\User;

class WebUserController extends Controller
{
    public function webuserlist($token, Request $request){

        list($data)=explode('|',Crypt::decrypt($token));
        $array_token = json_decode($data,1);
        $idlevel = $array_token['id'];
        $level = $array_token['type'];
        $idadmin = $array_token['iduser'];
        $token = Crypt::encrypt($data.'|'.time().'|'.config('app.appAPIkey'));

        //security
        $objAdminAuth = new AuthAdminController();
        $objAdminAuth->checkAuthPermissions($array_token['iduser']);

        $webusers = new WebUsers();
        $obj_customize=new \App\Models\Customize();
        $filter = \DataFilter::source($webusers->getWebUserList($idlevel, $level));
        $filter->attributes(array("id"=>"webuserRdatafilter"));
        $showcn=false;
        $layoutid=1;
        $acctext='Account Number';
        if($level=='P'){
            $dp=explode('!',$idlevel);
            foreach($dp as $ddp){
                $idgroup=$obj_customize->getPartnersGroup($ddp);
                if(!empty($idgroup)){
                    $df=$obj_customize->getSettingsValue($idgroup, 'SHOWCOMPANYNAME');
                    $acctext=$obj_customize->getSettingsValue($idgroup, 'PAYMENT_NUMBER_REG_NUMBER');
                    if($df==1)$showcn=true;

                }
            }
            $idpar=$dp[0];
            $obj_partner=new \App\Models\Partners();
            $layoutid=$obj_partner->get1PartnerInfo($idpar, 'layout_id');
        }
        elseif($level=='G'){
            $idgroup=$obj_customize->getCompaniesGroup($idlevel);
            $obj_group=new \App\Models\Companies();
            $idpartner=$obj_group->get1CompanyInfo($idlevel, 'id_partners');
            $df=$obj_customize->getSettingValueGroup($idgroup, $idpartner, 'SHOWCOMPANYNAME');
            $acctext=$obj_customize->getSettingValueGroup($idgroup, $idpartner, 'PAYMENT_NUMBER_REG_NUMBER');
            if($df==1)$showcn=true;
            $layoutid=$obj_group->getLayoutID($idlevel);
        }
        elseif($level=='M'){
            $idgroup=$obj_customize->getPropertiesGroup($idlevel);
            $obj_prop=new \App\Models\Properties();
            $idpartner=$obj_prop->get1PropertyInfo($idlevel, 'id_partners');
            $idcompany=$obj_prop->get1PropertyInfo($idlevel, 'id_companies');
            $df=$obj_customize->getSettingValueProperty($idpartner, $idcompany, $idgroup, 'SHOWCOMPANYNAME');
            $acctext=$obj_customize->getSettingValueProperty($idpartner, $idcompany, $idgroup, 'PAYMENT_NUMBER_REG_NUMBER');
            if($df==1)$showcn=true;
            $layoutid=$obj_prop->getLayoutID($idlevel);
        }
        $obj_layout=new \App\Models\Layout();
        $layouts=$obj_layout->getLayoutValues($layoutid);
        if($level=='P'){
            $filter->add('partners.partner_title',$layouts['layout_partner_partner'],'text');
            $filter->add('companies.company_name',$layouts['layout_partner_companies'],'text');
        }
        if($level!='M'){
            if($level=='P'){
                $filter->add('properties.name_clients',$layouts['layout_partner_property_name'],'text');
            }
            else {
                $filter->add('properties.name_clients',$layouts['layout_company_property_name'],'text');
            }
        }
        if($showcn){
            $cmptext='Company Name';
            if($level=='P'&&isset($layouts['layout_partner_users_companyname'])){
                $cmptext=$layouts['layout_partner_users_companyname'];
            }
            elseif($level=='G'&&isset($layouts['layout_company_users_companyname'])){
                $cmptext=$layouts['layout_company_users_companyname'];
            }
            elseif($level=='M'&&isset($layouts['layout_property_users_companyname'])){
                $cmptext=$layouts['layout_property_users_companyname'];
            }
            $filter->add('web_users.companyname',$cmptext,'text');
        }
        $filter->add('web_users.account_number',$acctext,'text');
        $filter->add('web_users.first_name','First Name','text');
        $filter->add('web_users.last_name','Last Name','text');
        $filter->add('web_users.address','Address','text')->scope(function($query, $value){
            return $query->where('web_users.address', 'like', '%'.$value.'%');
        });
        $filter->add('web_users.username','Username','text')->scope(function($query, $value){
            return $query->where('web_users.username', 'like', '%'.$value.'%');
        });
        $filter->add('web_users.email_address','Email','text')->scope(function($query, $value){
            return $query->where('web_users.email_address', 'like', '%'.$value.'%');
        });
        $filter->add('web_users.web_status','Status','select')->options(array('' => '--Status--','1' => 'Active', '0' => 'Inactive','46'=>'Locked', '998' => 'Authorized', '999' => 'Unauthorized'));
        $filter->submit('search', 'BL', array('class' => 'btn btn-md btn-primary'));
        $filter->reset('reset', 'BL', array('class' => 'btn btn-md btn-primary'));
        $filter->build();

        $grid = \DataGrid::source($filter);
        $grid->attributes(array("class"=>"table table-striped table-hover"));
        //print_r($grid); die;
        $grid->add($token,'token')->style("display:none;");
        $grid->add($idlevel,'idlevel')->style("display:none;");
        $grid->add('id','ID')->style("display:none;");

        switch (strtoupper($level)){
            case "B":
                $grid->add('partner',$layouts['layout_partner_partner'],  true);
                $grid->add('group',$layouts['layout_partner_companies']);
                $grid->add('merchant',$layouts['layout_partner_property_name']);
                break;
            case "P":
                $grid->add('partner',$layouts['layout_partner_partner'],  true);
                $grid->add('group',$layouts['layout_partner_companies']);
                $grid->add('merchant',$layouts['layout_partner_property_name']);
                break;
            case "G":
                $grid->add('group',$layouts['layout_partner_companies']);
                $grid->add('merchant',$layouts['layout_partner_property_name']);
                break;
            case "M":
                $grid->add('merchant',$layouts['layout_partner_property_name']);
                break;
        }

        if($showcn){
            $grid->add('companyname',$cmptext);
        }
        $grid->add('webuser',$acctext);
        $grid->add('first_name','First Name');
        $grid->add('last_name','Last Name');
        $grid->add('username','Username');
        $grid->add('email','Email');
        $grid->add('address','Address');
        $grid->add('balance','Balance');
        $grid->add('suppression','Paper bill')->cell( function($value){
            if($value==0){
                return 'yes';
            }else if($value == 1){
                return 'no';
            }
        });
        //$grid->add('last_updated','Date Updated');
        //$grid->add('last_updated_by','Last Updated By');
        $grid->add('status','Status')->cell( function($value){
            if($value == 1){
                return '<span class="label alert-success">Active</span>';
            }else if($value == 0){
                return '<span class="label alert-danger">Inactive</span>';
            }else if($value == 998){
                return '<span class="label alert-info">Authorize</span>';
            }else if($value == 46){
                return '<span class="label alert-warning">Locked</span>';
            }else{
                return '<span class="label alert-info">Unauthorize</span>';
            }
        });
        //$grid->add('services','Services');
        //$grid->add('subaction','Actions');
        $grid->add('actionvalue','Action');
        $grid->row(function ($row) {
            $id = $row->cell('id')->value;
            $token = $row->cells[0]->value;
            $idlevel = $row->cells[1]->value;
            $status = $row->cell('status')->value;
            $uname = trim($row->cell('username')->value);


            $edit_link = '';
            foreach(Session::get('user_permissions') as $p){
                if($p['route']=='edit'){
                    $edit_link = '<li><a href="/webuser/'.$token.'/edit/'.$id.'" >View Profile</a></li>';
                    break;
                }
            }

            $delete_link = '';
            foreach(Session::get('user_permissions') as $p){
                if($p['route']=='delwebuserdetail'){
                    $delete_link = '<li><a href="#" onclick="deleteWebUser(\''.$id.'\', \'/webusers/deleteWebUser/\');return false;" >Delete</a></li>';
                    break;
                }
            }

            $resetpassword_link = '';
            foreach(Session::get('user_permissions') as $p){
                if($p['route']=='wuresetpasseord'){
                    $resetpassword_link = '<li><a href="#" onclick="resetpasswordwu(\''.$id.'\');return false;" >Reset Password</a></li>';
                    break;
                }
            }



            if($status != '<span class="label alert-success">Active</span>' && $status!='<span class="label alert-warning">Locked</span>'){

                $row->cell('actionvalue')->value = ' <div class="dropdown pull-right">
														  <button class="btn btn-xs btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><span style="color:#fff">Actions</span>
														  <span class="caret white-font-color" ></span></button>
														  <ul class="dropdown-menu">
                                                            '.$edit_link.'
                                                            '.$delete_link.'
														  </ul>
														</div>';


            }else{
                if($uname!=''){
                    $row->cell('actionvalue')->value = ' <div class="dropdown pull-right">
														  <button class="btn btn-xs btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><span style="color:#fff">Actions</span>
														  <span class="caret white-font-color" ></span></button>
														  <ul class="dropdown-menu">
															'.$edit_link.'
															'.$delete_link.'
															'.$resetpassword_link.'
														  </ul>
														</div>';
                }
                else {
                    $row->cell('actionvalue')->value = ' <div class="dropdown pull-right">
														  <button class="btn btn-xs btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><span style="color:#fff">Actions</span>
														  <span class="caret white-font-color" ></span></button>
														  <ul class="dropdown-menu">
															'.$edit_link.'
															'.$delete_link.'
														  </ul>
														</div>';
                }

            }

            $row->cell('id')->style("display:none;");
            $row->cells[0]->style("display:none;");
            $row->cells[1]->style("display:none;");

        });
        if($level == 'M'){
            $grid->link('/webuser/'.$token.'/addwebuser',$layouts['layout_property_add_user'], "TR", array('id' => 'addWebUserBtn', 'class' => 'btn btn-md btn-success'));
        }
        $grid->link('/webuser/'.$token.'/export','Export', "TR", array('id' => 'exportWebUser', 'class' => 'btn btn-md btn-success'));
        $grid->link('/webuser/'.$token.'/viewimport','Import', "TR", array('id' => 'importWebUser', 'class' => 'btn btn-md btn-success btn-margin-left'));
        $grid->orderBy('partner','asc');
        $grid->paginate(10);



        return view('webuserlist',array('pageTitle'=>$layouts['layout_property_manage_user'], 'filter' => $filter, 'grid' => $grid, 'atoken' => $token));

    }

    public function editwebuser($token, $web_user_id){

        list($data)=explode('|',Crypt::decrypt($token));
        $array_token = json_decode($data,1);
        $idlevel = $array_token['id'];
        $level = $array_token['type'];
        $idadmin = $array_token['iduser'];
        $token = Crypt::encrypt($data.'|'.time().'|'.config('app.appAPIkey'));

        //security
        $objAdminAuth = new AuthAdminController();
        $objAdminAuth->checkAuthPermissions($array_token['iduser']);

        $webusers = new WebUsers();
        $property_id = $webusers->getPropertyIdByUserId($web_user_id);

        $user_payment_type = array();
        $showcn=false;
        $count=0;
        $countr=0;
        $einv=false;
        $acctext='Account Number';
        if(!empty($property_id)){
            $obj_prop=new \App\Models\Properties();
            $idpartner=$obj_prop->get1PropertyInfo($property_id, 'id_partners');
            $idcompany=$obj_prop->get1PropertyInfo($property_id, 'id_companies');
            $ot=$obj_prop->getcredOneTimeCredentials($property_id);
            if(!empty($ot))$count=count($ot);
            $ot=$obj_prop->getcredRecurringCredentials($property_id);
            if(!empty($ot))$countr=count($ot);
            $obj_customize=new \App\Models\Customize();
            $idgroup=$obj_customize->getPropertiesGroup($property_id);
            $ddf=$obj_customize->getSettingValueProperty($idpartner, $idcompany, $idgroup, 'SHOWCOMPANYNAME');
            if($ddf==1)$showcn=true;
            $ddf=$obj_customize->getSettingValueProperty($idpartner, $idcompany, $idgroup, 'EINVOICE');
            if($ddf==1)$einv=true;
            $acctext=$obj_customize->getSettingValueProperty($idpartner, $idcompany, $idgroup, 'PAYMENT_NUMBER_REG_NUMBER');
            $property_type_detail = $webusers->getPaymentTypeDetail($property_id['property_id']);
            $payment_is_checked = $webusers->isCatChecked($property_type_detail['payment_type_id'], $web_user_id);
            $user_payment_type = array('text' => $property_type_detail['payment_type_name'], 'ptid' => $property_type_detail['payment_type_id'], 'chk' => $payment_is_checked);
            $layoutid=$obj_prop->getLayoutID($property_id);
            $obj_layout=new \App\Models\Layout();
            $layouts=$obj_layout->getLayoutValues($layoutid);
            if(!isset($layouts['layout_property_users_companyname'])){
                $layouts['layout_property_users_companyname']='Company Name';
            }
        }
        $partnerlist = $webusers->getPartnerList();
        $companylist = $webusers->getCompanyList();
        $merchantlist = $webusers->getMerchantList();
        $webuserdetail = $webusers->getWebUserdetail($idlevel, $level, $web_user_id);
        $data=array('pageTitle'=>$layouts['layout_property_edit_web_user'], 'webuserdetail' => $webuserdetail, 'partnerlist' => $partnerlist, 'companylist' => $companylist, 'merchantlist' => $merchantlist, 'web_user_id' => $web_user_id, 'token' => $token, 'level' => $level, 'idlevel' => $idlevel, 'user_payment_type' => $user_payment_type,'showcn'=>$showcn,'property_id'=>$property_id,'otc'=>$count,'rtc'=>$countr,'layouts'=>$layouts,'acctext'=>$acctext);
        if($einv){
            $data['einv']=true;
        }
        $data['atoken']=$token;
        return view('webuseredit',$data);
    }

    public function savewebuser(Request $request){
        $validator = Validator::make($request->all(),[
            'first_name' => 'required',
            'email_address' => 'email'
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

            $webusers = new WebUsers();
            $web_user_id=$webusers->saveWebUser($request->all());
            if($web_user_id>0){
                $idproperty=$webusers->getPropertyIdByUserId($web_user_id);
                $obj_prop=new \App\Models\Properties();
                $idproperty=$idproperty['property_id'];
                $idcompany=$obj_prop->get1PropertyInfo($idproperty, 'id_companies');
                $idpartner=$obj_prop->get1PropertyInfo($idproperty, 'id_partners');
                $esdi=$obj_prop->getPropertySettings($idproperty, $idcompany, $idpartner, 'ESDI');
                if($esdi==1){
                    $obj_paperinvoice= new \App\CustomClass\PaperInvoiceConnection();
                    $params=array();
                    $params['accountNumber']=trim($webusers->get1UserInfo($web_user_id, 'account_number'));
                    $params['street']=trim($webusers->get1UserInfo($web_user_id, 'address'));
                    $params['optOut']=trim($webusers->get1UserInfo($web_user_id, 'suppression'));
                    $result=$obj_paperinvoice->SDIconnectionSet($params);
                }
                $objtx=new Transations();
                if($request->get('balance') !== null){
                    $balance=$request->get('balance');
                    if(empty($balance))$balance=0;
                    if($balance<0)$balance=0;
                    $balance=$balance*1;

                    //update autopayments
                    $fld=array();
                    $fld['trans_recurring_net_amount']=$balance;
                    $fld['trans_descr']='Payment:      '.number_format($balance,2);
                    $objtx->updateRTxByUser($web_user_id, $fld,true);
                }
                if($request->get('web_status') !== null){
                    $web_status=$request->get('web_status');
                    if($web_status==0||$web_status==999){
                        $objtx->cancelRTxByUser($web_user_id);
                    }
                }
                if($request->get('id') !== null){
                    return json_encode(array( 'error' => -1, 'msg' => 'Web user information has been updated successfully.'));
                }else{
                    return json_encode(array( 'error' => -1, 'msg' => 'Web user information has been added successfully.'));
                }
            }
            else {
                if($web_user_id==-3){
                    return json_encode(array( 'error' => -3, 'msg' => 'A payor with this account number exists in the system already. Please verify and try again'));
                }
                else {
                    return json_encode(array( 'error' => -6, 'msg' => 'A payor with this username exists in the system already. Please verify and try again'));
                }
            }
        }

    }

    public function deleteWebUser(Request $request, $id){

        $admin = Session::get('user_logged');
        if($admin && isset($admin['id'])){

            $objAdminAuth = new AuthAdminController();
            $objAdminAuth->checkAuthPermissions($admin['id']);

            $webusers = new WebUsers();
            if($webusers->deleteWebUser($id)){
                return array( 'errcode' => '0', 'message' =>'Web user deleted successfully.');
            }else{
                return array( 'errcode' => '1', 'message' =>'Error deleting record.');
            }
        }
        else{
            return array( 'errcode' => '1', 'message' =>'Error deleting record.');
        }



    }

    public function resetpasswordwu(Request $request){

        $admin = Session::get('user_logged');
        if($admin && isset($admin['id'])) {
            $objAdminAuth = new AuthAdminController();
            $objAdminAuth->checkAuthPermissions($admin['id']);

            $validator = Validator::make($request->all(), [
                'xpassword' => 'required|min:8'
            ]);
            if ($validator->fails()) {

                $validationMsg = '';
                $messages = $validator->messages();
                //echo '<pre>';
                //print_r($messages->all()); die;
                if (!empty($messages)) {
                    foreach ($messages->all() as $key => $error) {
                        //echo '<pre>';
                        //print_r($error);

                        //echo $key;
                        $validationMsg .= $error . '||';
                    }
                }
                //die;
                return json_encode(array('error' => 1, 'msg' => $validationMsg));

            } else {

                $webusers = new User();
                $npass = $request->get('xpassword');
                $webusers->setPasswordRaw($npass, $request->get('wuforgetpasswordid'));
                return json_encode(array('error' => -1, 'msg' => 'Password has been changed successfully.'));
            }
        }
        else{
            return json_encode(array('error' => 1, 'msg' => "Access Denied"));
        }
    }

}
