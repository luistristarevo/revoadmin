<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\Partners;
use Illuminate\Support\Facades\Session;
use Validator;

class PartnerController extends Controller
{

    private $validExtensions = array('jpg', 'jpeg', 'png', 'png');

    public function partnerlist($token, Request $request){
        list($data)=explode('|',Crypt::decrypt($token));
        $array_token = json_decode($data,1);
        $idlevel = $array_token['id'];
        $level = $array_token['type'];
        $idadmin = $array_token['iduser'];
        $token = Crypt::encrypt($data.'|'.time().'|'.config('app.appAPIkey'));

        //security
        $objAdminAuth = new AuthAdminController();
        $objAdminAuth->checkAuthPermissions($array_token['iduser'], null, ["B","P"]);

        $partners = new Partners();
        $filter = \DataFilter::source($partners->getPartnerList($idlevel, $level));
        $filter->attributes(array("id"=>"partnerRdatafilter"));

        $filter->add('partner.layout_id','Layout','select')->options(array('' => '--Layout--','1' => 'Property', '2' => 'Academic','6' => 'Bussiness', '13' => 'Non Profit', '14' => 'Utilities'));
        $filter->add('partner.partner_name','Partner Name','text');
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
        $grid->add($idadmin,'idadmin')->style("display:none;");
        $grid->add('id','ID')->style("display:none;");
        //$grid->add('name','Name', true)->style("width:100px");
        $grid->add('title','Title',  true);
        $grid->add('partner_id','ID');
        $grid->add('name','Subdomain');
        $grid->add('groups','Groups');
        $grid->add('merchants','Merchants');
        $grid->add('logo','Logo')->cell( function($value){

            if(($value != '') && file_exists(public_path().'/uploads/logos/partner/'.$value)){
                return '<a href="javascript:;" rel="/master/uploads/logos/partner/'.$value.'" class="partnerlogopopup">View</a>';
            }else{
                return '<span class="label">No Logo</span>';
            }
        });
        $grid->add('status','Status')->cell( function($value){
            if($value == 1){
                return '<span class="label label-success">Active</span>';
            }else if($value == 0){
                return '<span class="label">Inactive</span>';
            }
        });
        $grid->add('layout','Vertical')->cell( function($value){
            if($value == '1'){
                return '<i class="icon-home"></i>Property';
            }else if($value == '2'){
                return '<i class="icon-book"></i>Academic';
            }else if($value == '6'){
                return '<i class="icon-briefcase"></i>Business';
            }else if($value == '13'){
                return '<i class="icon-gift"></i>Non-Profit';
            }else if($value == '14'){
                return '<i class="icon-tint"></i>Utilities';
            }else{
                return '<i ></i>Generic';
            }
        });

        $grid->add('update_date','Date Updated');
        $grid->add('update_by','Updated By');


        $grid->add('actionvalue','Action');
        $grid->row(function ($row) use ($level,$idlevel,$token) {
            $id = $row->cell('id')->value;
            $idadmin = $row->cells[1]->value;

            $edit_link = '';
            foreach(Session::get('user_permissions') as $p){
                if($p['route']=='pdetail'){
                    $edit_link = '<li><a href="#" onclick="showPartner(\''.$id.'\')" >Edit</a></li>';
                    break;
                }
            }


            //to get group count
            $partners = new Partners();
            $row->cell('groups')->value = $partners->getGroupCount($id);
            $row->cell('merchants')->value = $partners->getMerchantCount($id);
            //$status = $row->cell('status')->value;


            $smtoken =  \Illuminate\Support\Facades\Crypt::encrypt($level.'|'.$idlevel.'|'.time());
            $route_settings = route('smgeneral',['token'=>$smtoken]);
            $link_sm = '<li><a href="'.$route_settings.'" >Settings Manager</a></li>';
            if($idlevel !='-954581')
            {
                $link_sm ='';
            }
            $row->cell('actionvalue')->style("text-align: right;");
            $row->cell('actionvalue')->value = ' <div class="dropdown">
													  <button class="btn btn-xs btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><span>Action</span>
													  <span class="caret"></span></button>
													  <ul class="dropdown-menu pull-right">
														<li><a href="#" onclick="openg(\''.$id.'\',\''.$idadmin.'\');" >Open</a></li>
														'.$edit_link.'
														'.$link_sm.'
													  </ul>
													</div>';

            $row->cell('id')->style("display:none;");
            $row->cells[0]->style("display:none;");
            $row->cells[1]->style("display:none;");

        });
        //$grid->edit('/transactionReport/'.$token.'/edit', 'Action','show', 'trans_id');
        $grid->link('#',"Add", "TR", array('onClick' => 'addPartner()', 'id' => 'AddPartner', 'rel' => '/master/index.php/partner/'.$token.'/add'));
        if($idlevel != -954581){
            $grid->link('#',"Import", "TR", array('id' => 'importPartner', 'rel' => '/master/index.php/partner/'.$token.'/import'));
        }
        $grid->link('#',"Export", "TR", array('id' => 'exportPartner', 'rel' => '/master/index.php/partner/'.$token.'/report'));
        $grid->orderBy('title','asc');
        $grid->paginate(10);
        return view('partnerlist',array('pageTitle'=>'Vertical Manager', 'filter' => $filter, 'grid' => $grid, 'atoken' => $token));

    }

    public function partnerdetail($token, $partner_id){
        list($data)=explode('|',Crypt::decrypt($token));
        $array_token = json_decode($data,1);
        $idlevel = $array_token['id'];
        $level = $array_token['type'];
        $idadmin = $array_token['iduser'];
        $token = Crypt::encrypt($data.'|'.time().'|'.config('app.appAPIkey'));

        //security
        $objAdminAuth = new AuthAdminController();
        $objAdminAuth->checkAuthPermissions($array_token['iduser'], null, ['P','B']);


        $partners = new Partners();
        $partnerdetail = $partners->getPartnerDetail($partner_id);
        $partnerdetailHtml = view('partneredit',array('pageTitle'=>'Edit Partner Detail', 'partnerdetail' => $partnerdetail, 'token' => $token, 'idadmin' => $idadmin, 'idlevel' => $idlevel))->render();
        return response()->json( array('errcode' => 0, 'msg' => $partnerdetailHtml) );


    }

    public function updatepartner(Request $request){

        //echo $partner_id; die;
        //echo '<pre>';
        //print_r($request->all()); die;
        $destinationPath = public_path().'/uploads/logos/partner/';
        $validator = Validator::make($request->all(),[
            'partner_title' => 'required',
            'partner_id' => 'required'
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
        }
        if($request->hasFile('logo')){

            $fileExtension = $request->file('logo')->getClientOriginalExtension();
            $filename = $request->file('logo')->getClientOriginalName();
            $maxFileUploadSize = $request->file('logo')->getMaxFilesize();
            $fileSize = $request->file('logo')->getClientSize();
            //echo $maxFileUploadSize; die;
            //check for valid uploadedfile
            if(!in_array($fileExtension, $this->validExtensions)){
                return json_encode(array('error' => 1, 'msg' => 'Please upload valid file.'));
            }else if($fileSize > $maxFileUploadSize){
                return json_encode(array('error' => 1, 'msg' => 'Max upload limit is '.$maxFileUploadSize.' bytes.'));
            }else if($fileSize == 0){
                return json_encode(array('error' => 1, 'msg' => 'File should be greater than zero byte.'));
            }else{

                $partners = new Partners();
                $filename = time().$filename;
                if(!$request->file('logo')->move($destinationPath, $filename)){
                    $filename = '';
                }
                if($partners->updatePartnerDetail($request->all(), $filename)){
                    return json_encode(array( 'error' => -1, 'msg' => 'Partner information has been updated successfully.'));
                }

            }

        }else{
            $partners = new Partners();
            if($partners->updatePartnerDetail($request->all(), '')){
                return json_encode(array( 'error' => -1, 'msg' => 'Partner information has been updated successfully.'));
            }
        }


    }
}
