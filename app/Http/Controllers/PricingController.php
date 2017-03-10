<?php

namespace App\Http\Controllers;

use Crypt;
use App\Models\Pricing;
use Illuminate\Support\Facades\Session;
use DB;

class PricingController extends Controller
{
    public function adminPricing($token){

        list($data)=explode('|',Crypt::decrypt($token));
        $array_token = json_decode($data,1);
        $token = Crypt::encrypt($data.'|'.time().'|'.config('app.appAPIkey'));

        //security
        $objAdminAuth = new AuthAdminController();
        $objAdminAuth->checkAuthPermissions($array_token['iduser'], null,["B"]);

        $pricing = new Pricing();
        $pricing_all = $pricing->getAllPricing();
        $filter = \DataFilter::source($pricing_all);

        $filter->add('idpt','ID','text');
        $filter->add('tlabel','Pricing Code','text');
        $filter->submit('search');
        $filter->reset('reset');
        $filter->build();
        $grid = \DataGrid::source($filter);
        $grid->attributes(array("class"=>"table table-striped table-hover"));
        $grid->add('id_table','IDT')->style("display:none;");
        $grid->add('idpt','ID');
        $grid->add('tlabel','Pricing Code');
        $grid->add('created_at','Date Created');
        $grid->add('actionvalue','Action');
        $grid->row(function ($row) {
            $id_table = $row->cell('id_table')->value;

            $open_link = '';
            foreach(Session::get('user_permissions') as $p){
                if($p['route']=='adminopenpricing'){
                    $open_link = '<li><a href="#" onclick="openApp(\''.$id_table.'\');">Open</a></li>';
                    break;
                }
            }

            $edit_link = '';
            foreach(Session::get('user_permissions') as $p){
                if($p['route']=='admineditpricing'){
                    $edit_link = '<li><a href="#" onclick="editApp(\''.$id_table.'\');">Edit</a></li>';
                    break;
                }
            }

            $delete_link = '';
            foreach(Session::get('user_permissions') as $p){
                if($p['route']=='admindeletepricing'){
                    $delete_link = '<li><a href="#" onclick="deleteApp(\''.$id_table.'\');">Delete</a></li>';
                    break;
                }
            }

            $row->cell('actionvalue')->value = ' <div class="dropdown pull-right">
													  <button class="btn btn-xs btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><span style="color:#fff">Actions</span>
													  <span class="caret"></span></button>
													  <ul class="dropdown-menu">
														'.$open_link.'
														'.$edit_link.'
														'.$delete_link.'
													  </ul>
													</div>';
            $row->cell('id_table')->style("display:none;");
        });

        $grid->orderBy('pricing.id_p','desc');
        $grid->paginate(10);
        return view('pricingList',array('token'=>$token,'pageTitle' => 'Pricing','filter' => $filter, 'grid' => $grid));
    }

    public function adminNewPricing($token){
        list($data)=explode('|',Crypt::decrypt($token));
        $array_token = json_decode($data,1);
        $token = Crypt::encrypt($data.'|'.time().'|'.config('app.appAPIkey'));

        //security
        $objAdminAuth = new AuthAdminController();
        $objAdminAuth->checkAuthPermissions($array_token['iduser'], null,["B"]);

        $partners= DB::table('partners')->get();
        $bills= DB::table('bill_item')->get();
        $frequences= DB::table('bill_cycle')->get();
        $bill_units= DB::table('bill_unit')->get();
        $price_table = DB::table('price_table')->get();
        $array_bills['']='';
        foreach($bills as $bill){
            $array_bills[$bill['id_bill']]= $bill['description'];
        }
        $array_frequences['']='';
        foreach($frequences as $frequence){
            $array_frequences[$frequence['id']]= $frequence['description_cycle'];
        }
        $bill_units_array['']='';
        foreach($bill_units as $bill_unit){
            $bill_units_array[$bill_unit['id_unit']]= $bill_unit['udescription'];
        }

        $array_partners['']='';
        foreach($partners as $partner){
            $array_partners[$partner['id']]= $partner['partner_title'];
        }

        $billto_array = [''=>'','partner'=>'Partner','group'=>'Group','merchant'=>'Merchant','payor'=>'Payor','collected'=>'Collected'];
        $radiocheck_array = [''=>'','2'=>'Radio button','1'=>'Checkbox'];

        return view('pricing.pricingNew',array(
            'bills'=>$bills,
            'bills_array'=>$array_bills,
            'frequences'=>$frequences,
            'frequences_array'=>$array_frequences,
            'bill_units'=>$bill_units,
            'bill_units_array'=>$bill_units_array,
            'partners'=>$partners,
            'partners_array'=>$array_partners,
            'bill_to_array'=>$billto_array,
            'radio_check_array'=>$radiocheck_array,
            'price_table'=>$price_table,
            'pageTitle'=>'New Pricing',
            'token'=>$token
        ));
    }
}

