<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\Companies;
use Illuminate\Support\Facades\Session;
use Validator;

class GroupController extends Controller
{


    public function grouplist($token, Request $request){

        $idadmin =1;

        list($data)=explode('|',Crypt::decrypt($token));
        $array_token = json_decode($data,1);
        $idlevel = $array_token['id'];
        $level = $array_token['type'];
        $idadmin = $array_token['iduser'];
        $token = Crypt::encrypt($data.'|'.time().'|'.config('app.appAPIkey'));

        //security
        $objAdminAuth = new AuthAdminController();
        $objAdminAuth->checkAuthPermissions($array_token['iduser'], null, ["B","P","G"]);

        $companies = new Companies();

        $filter = \DataFilter::source($companies->getGroupList($idlevel, $level));
        $filter->attributes(array("id" => "groupRdatafilter"));

        switch (strtoupper($level)){
            case "B":
                $filter->add('partners.partner_title','Partner','text');
                $filter->add('companies.company_name','Company','text');
                break;
            case "P":
                $filter->add('partners.partner_title','Partner','text');
                $filter->add('companies.company_name','Company','text');
                break;
            case "G":
                $filter->add('companies.company_name','Company','text');
                break;
        }

        $filter->add('companies.contact_name','Contact Name','text');
        $filter->add('companies.contact_email','Contact Email','text');
        $filter->add('companies.compositeID_companies','Group ID','text');
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
        //$grid->add($idadmin,'idadmin')->style("display:none;");
        $grid->add('status','Status')->style("display:none;");
        $grid->add('id','ID')->style("display:none;");
        //$grid->add('name','Name', true)->style("width:100px");

        switch (strtoupper($level)){
            case "B":
                $grid->add('partner','Partner',  true);
                $grid->add('group','Company');
                $grid->add('merchants','Merchant');
                break;
            case "P":
                $grid->add('partner','Partner',  true);
                $grid->add('group','Company');
                $grid->add('merchants','Merchant');
                break;
            case "G":
                $grid->add('group','Company');
                $grid->add('merchants','Merchant');
                break;
        }


        $grid->add('group_id','Group ID');
        $grid->add('user_active','Users Active');
        $grid->add('authorized','Authorized');
        $grid->add('inactive','Inactive');
        $grid->add('unauthorized','UnAuthorized');
        //$grid->add('last_name','Last Name');
        //$grid->add('pay_method','Pay Method');
        $grid->add('deleted','Deleted');
        $grid->add('actionvalue','Action');
        $grid->row(function ($row) use ($level,$idlevel,$token,$idadmin){
            $id = $row->cell('id')->value;
            $status = $row->cell('status')->value;
            //$idadmin = $row->cells[1]->value;
            //to get group count
            //$groups = new Groups();

            $smtoken =  \Illuminate\Support\Facades\Crypt::encrypt($level.'|'.$idlevel.'|'.time());
            $route_settings = route('smgeneral',['token'=>$smtoken]);
            $link_sm = '<li><a href="'.$route_settings.'" >Settings Manager</a></li>';
            if($idlevel !='-954581')
            {
                $link_sm ='';
            }

            $edit_link = '';
            foreach(Session::get('user_permissions') as $p){
                if($p['route']=='gdetail'){
                    $edit_link = '<li><a href="#" onclick="showGroup(\''.$id.'\')" >Edit</a></li>';
                    break;
                }
            }

            $companies = new Companies();
            $row->cell('merchants')->value = $companies->getMerchantCountByGroupId($id);
            $row->cell('user_active')->value = $companies->getUserCountByGroupId($id, 1);
            $row->cell('authorized')->value = $companies->getUserCountByGroupId($id, 998);
            $row->cell('inactive')->value = $companies->getUserCountByGroupId($id, 0);
            $row->cell('unauthorized')->value = $companies->getUserCountByGroupId($id, 999);
            $row->cell('deleted')->value = $companies->getUserCountByGroupId($id, 1000);
            //$status = $row->cell('status')->value;
            $row->cell('actionvalue')->style("text-align: right;");
            $row->cell('actionvalue')->value = ' <div class="dropdown">
													  <button class="btn btn-xs btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><span style="color:#fff">Action</span>
													  <span class="caret"></span></button>
													  <ul class="dropdown-menu pull-right">
														<li><a href="#" onclick="openg(\''.$id.'\',\''.$idadmin.'\');" >Open</a></li>
														'.$edit_link.'
														'.$link_sm.'
													  </ul>
													</div>';

            $row->cell('id')->style("display:none;");
            $row->cells[0]->style("display:none;");
            $row->cell('status')->style("display:none;");

        });
        //$grid->edit('/transactionReport/'.$token.'/edit', 'Action','show', 'trans_id');
        $grid->link('#',"Add", "TR", array('onClick' => 'addGroup()', 'id' => 'AddGroup', 'rel' => '/master/index.php/group/'.$token.'/add'));
        /*if($idlevel != -954581){
            $grid->link('#',"Import", "TR", array('id' => 'importGroup', 'rel' => '/master/index.php/group/'.$token.'/import'));
        }*/
        $grid->link('#',"Export", "TR", array('id' => 'exportGroup', 'rel' => '/master/index.php/group/'.$token.'/export'));
        $grid->orderBy('companies.id','asc');
        $grid->paginate(10);



        return view('grouplist',array('pageTitle'=>'Group Manager', 'filter' => $filter, 'grid' => $grid, 'atoken' => $token));

    }

    public function groupdetail($token, $group_id){

        list($data)=explode('|',Crypt::decrypt($token));
        $array_token = json_decode($data,1);
        $idlevel = $array_token['id'];
        $level = $array_token['type'];
        $token = Crypt::encrypt($data.'|'.time().'|'.config('app.appAPIkey'));

        //security
        $objAdminAuth = new AuthAdminController();
        $objAdminAuth->checkAuthPermissions($array_token['iduser'], null, ['B','P','G']);

        $companies = new Companies();
        $groupdetail = $companies->getGroupDetail($group_id);
        $groupdetailHtml = view('groupedit',array('pageTitle'=>'Edit Group Detail', 'groupdetail' => $groupdetail, 'token' => $token, 'idlevel' => $idlevel))->render();
        return response()->json( array('errcode' => 0, 'msg' => $groupdetailHtml) );
    }

    public function updategroup(Request $request){

        $validator = Validator::make($request->all(),[
            'company_name' => 'required',
            'group_id' => 'required'
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
        $companies = new Companies();
        if($companies->updateGroupDetail($request->all())){
            return json_encode(array( 'error' => -1, 'msg' => 'Group information has been updated successfully.'));
        }else{
            return json_encode(array( 'error' => 1, 'msg' => 'Error updating group information.'));
        }

    }
}
