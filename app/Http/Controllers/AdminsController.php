<?php
namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Http\Request;
use Crypt;
use Illuminate\Support\Facades\Session;
use Validator;

class AdminsController extends Controller
{

    public function adminlist($token, Request $request){

		list($data)=explode('|',Crypt::decrypt($token));
		$array_token = json_decode($data,1);
		$idlevel = $array_token['id'];
		$level = $array_token['type'];
		$token = Crypt::encrypt($data.'|'.time().'|'.config('app.appAPIkey'));

		//security
		$objAdminAuth = new AuthAdminController();
		$objAdminAuth->checkAuthPermissions($array_token['iduser']);

		$adminusers = new Users();

		$filter = \DataFilter::source($adminusers->getAdminList($idlevel, $level));
		$filter->attributes(array("id" => "adminRdatafilter"));		
		$filter->add('users.first_name','First Name','text');
		$filter->add('users.last_name','Last Name','text');
		$filter->add('users.email_address','Email','text');
		$filter->add('users.phone','Phone','text');
		$filter->add('users.active','Status','select')->options(array('1' => 'Active', '0' => 'Inactive'));		
		$filter->submit('search', 'BL', array('class' => 'btn btn-md btn-primary'));
		$filter->reset('reset', 'BL', array('class' => 'btn btn-md btn-primary'));
		$filter->build();	
		//echo '<pre>';
		//print_r($filter); die;
		// die;		 
		$grid = \DataGrid::source($filter);		
		$grid->attributes(array("class"=>"table table-striped table-hover"));
		//print_r($grid); die;
		$grid->add($token,'token')->style("display:none;");
		$grid->add($level,'level')->style("display:none;");
		$grid->add('id','ID')->style("display:none;");
		$grid->add('first_name','First Name',  true);
		$grid->add('last_name','Last Name');
		$grid->add('email','Email');
		$grid->add('phone','Phone');
		$grid->add('login','Username');
		$grid->add('level','Level');
		$grid->add('access_to','Access to');
		$grid->add('updte','Date Updated');
		$grid->add('updte_by','Updated By');
		$grid->add('privilages','Privilages');
		$grid->add('status','Status')->cell(function($value){
			
			if($value == 1){
				return '<span class="label label-success">active</span>';
			}else if($value == 0){
				return '<span class="label">inactive</span>';
			}
			
		});
		$grid->add('actionvalue','Action');
		$grid->row(function ($row) {
			 $id = $row->cell('id')->value;
			 $token = $row->cells[0]->value;
			 $level = $row->cells[1]->value;
			 $status = $row->cell('status')->value;
			 $adminusers = new Users();
			   $adminlevel = $adminusers->getTopAdm($id);
			   $row->cell('level')->value = $adminlevel;
			   $row->cell('access_to')->value = $adminusers->getAccess($id, $adminlevel); 
			   $roles = $adminusers->getRolesAdm($id, $adminlevel);
				$string_priv = '';
				if($roles['admin_manager'])
					$string_priv.= $roles['admin_manager'].'<br/>';
				if($roles['user_manager'])
					$string_priv.= $roles['user_manager'].'<br/>';
				if($roles['transaction_manager'])
					$string_priv.= $roles['transaction_manager'].'<br/>';
				if($roles['app_manager'])
					$string_priv.= $roles['app_manager'].'<br/>';
				if($roles['profile_manager'])
					$string_priv.= $roles['app_manager'].'<br/>';
			   $row->cell('privilages')->value = $string_priv;


			$delete_link = '';
			foreach(Session::get('user_permissions') as $p){
				if($p['route']=='deladminuserdetail'){
					$delete_link = '<li><a href="#" onclick="deleteAdminUser(\''.$id.'\', \'/admins/'.$token.'/deleteAdminUser/\');" >Delete</a></li>';
					break;
				}
			}

			$edit_link = '';
			foreach(Session::get('user_permissions') as $p){
				if($p['route']=='adminedit'){
					$edit_link = '<li><a href="/admins/admindetail/'.$token.'/'.$id.'" >Edit</a></li>';
					break;
				}
			}
			 //to get group count		 
			$row->cell('actionvalue')->value = ' <div class="dropdown pull-right">
													  <button class="btn btn-xs btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><span style="color:#fff">Action</span>
													  <span class="caret white-font-color" ></span></button>
													  <ul class="dropdown-menu">
														'.$edit_link.'
														<!--<li><a href="#" onclick="assignRoleAndPrivilege();" rel="/master/index.php/admins/'.$token.'/adminroleprivilege/'.$id.'" id="AdminAssignRolesPrivilige" >Assign Roles and Privileges</a></li>-->
														'.$delete_link.'
													  </ul>
													</div>'; 
			 
			 $row->cell('id')->style("display:none;");			 
			 $row->cells[0]->style("display:none;");
			 $row->cells[1]->style("display:none;");
			// $row->cell('status')->style("display:none;");
		
		});	
				
		//$grid->edit('/transactionReport/'.$token.'/edit', 'Action','show', 'trans_id');
		//$grid->add('<a href="#" id="AddGroup" class="btn btn-default" rel = "/master/index.php/admins/"'.$token.'"/add" onclick="addAdmin()"><h6>Add</h6></a>');
		$grid->link('#', \Illuminate\Html\HtmlFacade::entities("Add"), "TR", array('onclick' => 'addAdmin();', 'id' => 'AddAdmin', 'class' => 'btn btn-md btn-success', 'rel' => '/master/index.php/admins/'.$token.'/add'));
		$grid->link('#',"Export", "TR", array('id' => 'exportAdminUser', 'class' => 'btn btn-md btn-success', 'rel' => '/master/index.php/admins/'.$token.'/report'));
		$grid->orderBy('first_name','asc');
		$grid->paginate(10);
		
			
		      
        return view('adminlist',array('pageTitle'=>'Administrators Manager', 'filter' => $filter, 'grid' => $grid, 'atoken' => $token));
    
    }

    public function admindetail($token, $user_id){

        list($data)=explode('|',Crypt::decrypt($token));
        $array_token = json_decode($data,1);
        $idlevel = $array_token['id'];
        $level = $array_token['type'];
        $token = Crypt::encrypt($data.'|'.time().'|'.config('app.appAPIkey'));

        //security
        $objAdminAuth = new AuthAdminController();
        $objAdminAuth->checkAuthPermissions($array_token['iduser']);

        $admins = new Users();
        $userdetail = $admins->getUserDetail($idlevel, $level, $user_id);
        return view('adminedit',array('pageTitle'=>'Edit Admin', 'user_detail' => $userdetail, 'atoken'=>$token,'token' => $token, 'idlevel' => $idlevel));


    }

    public function updateadmin(Request $request){

        //echo '<pre>';
        //print_r($request->all()); die;
        $validator = Validator::make($request->all(),[
            'first_name' => 'required',
            'email_address' => 'required|email|unique:users,email_address,'.$request->get('id').',id',
            'login' => 'required|unique:users,login,'.$request->get('id').',id'
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
        $admins = new Users();
        if($admins->updateUserDetail($request->get('id'), $request->all())){
            return json_encode(array( 'error' => -1, 'msg' => 'User information has been updated successfully.'));
        }else{
            return json_encode(array( 'error' => 1, 'msg' => 'Error updating user information.'));
        }

    }

    public function deleteAdminUser($token, $user_id,Request $request){
        list($data)=explode('|',Crypt::decrypt($token));
        $array_token = json_decode($data,1);

        //security
        $objAdminAuth = new AuthAdminController();
        $objAdminAuth->checkAuthPermissions($array_token['iduser']);

        $admins = new Users();
        if($admins->deleteAdminUser($user_id)){
            return array( 'errcode' => '0', 'message' =>'User has deleted successfully.');
        }else{
            return array( 'errcode' => '1', 'message' =>'Error deleting record.');
        }
    }

}
