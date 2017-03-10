<?php
namespace App\Http\Controllers;
use Request;
use Illuminate\Support\Facades\DB;
use App\Models\UserEvents;
use App\Models\UserPermissions;
use Illuminate\Support\Facades\Session;
use App\Models\Partners;
use Crypt;

class AuthAdminController extends Controller
{
    public function login()
    {
        return view('adminlogin',array('type'=>'','name'=>'','id'=>''));
    }

    public function check(Request $request)
    {
        $submit = $request::all();

        $user = DB::table('users_admin')->where('username',$submit['username'])->where('userpassw',sha1($submit['password']))->first();

        $event = new UserEvents();
        if($user){
            $perm_obj = new UserPermissions();
            list($data)=explode('|',Crypt::decrypt($submit['tokendata']));
            $data_token=json_decode($data,1);
            $permissions = $perm_obj->getPermissions($user['id']);
            $customize = DB::table('users_customize')->where('iduser',$user['id'])->get();
            Session::put('user_logged', $user);
            Session::put('user_permissions', $permissions);
            Session::put('user_customize', $customize);

            if(empty($data_token['id']) && empty($data_token['type'])){
                $access = $perm_obj->getAccessWithoutData($user['id']);
                if($access){
                    if($access[0]['ida']>0){
                        //BIS
                        $data_token['type']='B';
                        $data_token['iduser']=$user['id'];

                        Session::put('pgm_name', "Business Information Manager");
                        Session::put('user_app_data', $data_token);

                        $partner_obj = new Partners();
                        Session::put('logo', $partner_obj->getPartnerLogo(-1));

                        $newtoken = Crypt::encrypt(json_encode($data_token).'|'.time().'|'.config('app.appAPIkey'));
                        return redirect()->route('admindashboard',['token'=>$newtoken]);

                    }
                    else if($access[0]['idb']>0){
                        $partners_db =  DB::table('partners')->where('branch_id',$access[0]['idb'])->get();

                        if($partners_db){
                            $data_token['type']='P';
                            $data_token['iduser']=$user['id'];
                            $ids_string ='';
                            $accessux = array();
                            foreach ($partners_db as $p){
                                $ids_string .= $p['id'].'!';
                                $accessux[]=['idp'=>$p['id'],'partner_title'=>$p['partner_title']];
                            }
                            $data_token['id'] = substr($ids_string, 0, -1);
                            $branch =  DB::table('branch')->where('id',$access[0]['idb'])->first();
                            $pgm_name=$branch["branch_name"];
                            Session::put('logo', $branch['branch_logo']);
                            Session::put('pgm_name', $pgm_name );
                            Session::put('user_app_data', $data_token);
                            $newtoken = Crypt::encrypt(json_encode($data_token).'|'.time().'|'.config('app.appAPIkey'));
                            return view('adminloginselect',array('type'=>'p','access'=>$accessux,'token'=>$newtoken));
                        }
                        else{
                            exit('No Branch found.');
                        }
                    }
                    else if($access[0]['idp']>0){

                        // The same Branch or delete
                        if(isset($access[0]['branch_id'])){
                            $default_branch = $access[0]['branch_id'];
                            foreach ($access as $key => $a){
                                if($a['branch_id']!=$default_branch){
                                    unset($access[$key]);
                                }
                            }
                        }

                        $data_token['type']='P';
                        $data_token['iduser']=$user['id'];
                        $ids_string ='';
                        foreach ($access as $acc){
                            $ids_string .= $acc['idp'].'!';
                        }
                        $data_token['id'] = substr($ids_string, 0, -1);

                        $pgm_name = $access[0]['partner_title'];

                        if($access[0]['branch_id']){
                            $branch =  DB::table('branch')->where('id',$access[0]['branch_id'])->first();
                            if($branch){
                                $pgm_name=$branch["branch_name"];
                                Session::put('logo', $branch['branch_logo']);

                            }
                        }
                        else{
                            $partner_obj = new Partners();
                            Session::put('logo', $partner_obj->getPartnerLogo($access[0]['idp']));
                        }


                        Session::put('pgm_name', $pgm_name );
                        Session::put('user_app_data', $data_token);
                        $newtoken = Crypt::encrypt(json_encode($data_token).'|'.time().'|'.config('app.appAPIkey'));
                        return view('adminloginselect',array('type'=>'p','access'=>$access,'token'=>$newtoken));
                    }
                    else if($access[0]['idc']>0){
                        $data_token['type']='G';
                        $data_token['iduser']=$user['id'];
                        $ids_string ='';
                        foreach ($access as $acc){
                            $ids_string .= $acc['idc'].'!';
                        }
                        $data_token['id'] = substr($ids_string, 0, -1);

                        $pgm_name =  $access[0]['company_name'];
                        $company =  DB::table('companies')->join('partners', 'companies.id_partners', '=', 'partners.id')->where('companies.id',$access[0]['idc'])->first();
                        if($company)
                            $pgm_name=$company['partner_title']. ' > '.$pgm_name;

                        if(count($access) > 1){
                            $partner_obj = new Partners();
                            if($company)
                                Session::put('logo', $partner_obj->getPartnerLogo($company['id_partners']));
                        }
                        else{
                            $company_obj=new Companies();
                            Session::put('logo', $company_obj->getCompanyLogo($access[0]['idc'],$company['id_partners']));
                        }

                        Session::put('pgm_name', $pgm_name);
                        Session::put('user_app_data', $data_token);
                        $newtoken = Crypt::encrypt(json_encode($data_token).'|'.time().'|'.config('app.appAPIkey'));
                        return view('adminloginselect',array('type'=>'g','access'=>$access,'token'=>$newtoken));
                    }
                    else if($access[0]['idm']>0){

                        $data_token['type']='M';
                        $data_token['iduser']=$user['id'];
                        $ids_string ='';
                        foreach ($access as $acc){
                            $ids_string .= $acc['idm'].'!';
                        }
                        $data_token['id'] = substr($ids_string, 0, -1);

                        $pgm_name =  $access[0]['name_clients'];
                        $merchant =  DB::table('properties')->join('partners', 'properties.id_partners', '=', 'partners.id')->join('companies', 'properties.id_companies', '=', 'companies.id')->where('properties.id',$access[0]['idm'])->first();

                        if($merchant)
                            $pgm_name=$merchant['partner_title']. ' > '.$merchant['company_name']. ' > '.$pgm_name;


                        if(count($access) > 1){
                            $company_obj=new Companies();
                            if($merchant)
                                Session::put('logo', $company_obj->getCompanyLogo($merchant['id_companies'],$merchant['id_partners']));
                        }
                        else{
                            $property_obj=new Properties();
                            Session::put('logo', $property_obj->getPropertyLogo($merchant['logo'],$merchant['id_companies'],$merchant['id_partners']));
                        }

                        Session::put('pgm_name', $pgm_name);
                        Session::put('user_app_data', $data_token);
                        $newtoken = Crypt::encrypt(json_encode($data_token).'|'.time().'|'.config('app.appAPIkey'));
                        return view('adminloginselect',array('type'=>'m','access'=>$access,'token'=>$newtoken));
                    }
                }
                else{
                    $event->newEvent(['iduser'=>$user['id'],'username'=>$submit['username'],'event'=>'Failed Login','ip'=>$request::ip()]);
                    return Redirect::back()->with('error', 'This user can\'t access to this section');
                }
            }
            else{
                $access = $perm_obj->getAccess($user['id'],$data_token['id'],$data_token['type']);
            }

            if(!$access){
                $event->newEvent(['iduser'=>$user['id'],'username'=>$submit['username'],'event'=>'Failed Login','ip'=>$request::ip()]);
                return Redirect::back()->with('error', 'This user can\'t access to this section');
            }

            $event->newEvent(['iduser'=>$user['id'],'username'=>$submit['username'],'event'=>'Logued in','ip'=>$request::ip()]);
            $newtoken = Crypt::encrypt(json_encode($data_token).'|'.time().'|'.config('app.appAPIkey'));
            Session::put('user_app_data', $data_token);

            return redirect()->route('admindashboard',['token'=>$newtoken]);
        }
        else{
            $event->newEvent(['username'=>$submit['username'],'event'=>'Failed Login','ip'=>$request::ip()]);
            return Redirect::back()->with('error', 'User or Password are not correct');
        }
    }

    public function dashboard($token){
        list($data)=explode('|',Crypt::decrypt($token));
        $data_token = json_decode($data,1);

        //security
        $this->checkAuthPermissions($data_token['iduser']);
        var_dump($data_token);
        $logo =  null;


        return view('dashboard',array('atoken'=>$token,'token'=>$token,'pageTitle'=>'Dashboard'));
    }

    public function logout(Request $request){
        $event = new UserEvents();
        if(Session::get('user_logged')){
            $event->newEvent(['iduser'=>Session::get('user_logged')['id'],'username'=>Session::get('user_logged')['username'],'event'=>'Logged out','ip'=>$request::ip()]);
            Session::forget('user_logged');
            Session::forget('user_permissions');
            Session::forget('user_access');
            Session::forget('user_customize');
        }
        return redirect()->route('adminlogin');
    }

    public function checkAuthPermissions($idUser,$noCheckPermissions = 0, $levelsAuth = null){
        $data_token = Session::get('user_app_data');
        $newtoken = Crypt::encrypt(json_encode($data_token).'|'.time().'|'.config('app.appAPIkey'));
        $authLevel = false;
        if($levelsAuth){
            foreach ($levelsAuth as $lev) {
                if(strtoupper($lev) == strtoupper($data_token['type'])){
                    $authLevel = true;
                    break;
                }
            }
            if(!$authLevel){
                header("Location: " . app('url')->route('adminerror',array('token'=>$newtoken,'type'=>1)));
                exit();
            }
        }

        $objsecurity = new UserPermissions();
        if(!$objsecurity->isAuth($idUser)){
            header("Location: " . app('url')->route('adminlogin'));
            exit();
        }
        if($noCheckPermissions){
            return;
        }

        $routeName = \Request::route()->getName();
        $permissions = Session::get('user_permissions');

        foreach($permissions as $p){
            if($p['route']==$routeName){
                return;
            }
        }


        header("Location: " . app('url')->route('adminerror',array('token'=>$newtoken,'type'=>1)));
        exit();
    }

    public function error($token,$type){
        $pageTitle ='Access Error';
        $name = $description = '';
        switch($type){
            case 1:
                $name = 'Access Denied';
                $description = 'Sorry, you don\'t have privileges to access this area';
                break;
        }
        return view('admin_error',array('description'=>$description,'name'=>$name,'pageTitle'=>$pageTitle,'atoken'=>$token));
    }


}
