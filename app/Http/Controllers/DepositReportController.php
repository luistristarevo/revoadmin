<?php

namespace App\Http\Controllers;

use App\Models\Deposits;
use Crypt;
use Illuminate\Support\Facades\Session;

class DepositReportController extends Controller
{
    public function deposits($token){
        list($data)=explode('|',Crypt::decrypt($token));
        $array_token = json_decode($data,1);
        $idlevel = $array_token['id'];
        $level = $array_token['type'];
        $token = Crypt::encrypt($data.'|'.time().'|'.config('app.appAPIkey'));

        //security
        $objAdminAuth = new AuthAdminController();
        $objAdminAuth->checkAuthPermissions($array_token['iduser']);

        $deposit_obj = new Deposits();
        $datagrid = $deposit_obj->getDepositsGroup();

        $view_link = '';
        foreach(Session::get('user_permissions') as $p){
            if($p['route']=='depositbatchdetails'){
                $view_link = true;
                break;
            }
        }


        $filter = \DataFilter::source($datagrid);
        $filter->add('company_name','Group','text');
        $filter->add('property_name','Property','text');
        $filter->add('transaction_type','Pay Type','select')->options(array(
            '' => '--Pay Method--',
            'eCheck' => 'eCheck',
            'Credit Card' => 'Credit Card'
        ));
        $filter->submit('Search');
        $filter->reset('Reset');
        $filter->build();



        $grid = \DataGrid::source($filter);
        $grid->add('id_property','IDP')->style("display:none;");
        $grid->add('batch','BATCH')->style("display:none;");
        $grid->add('date_formatted','Date');
        $grid->add('company_name','Group',true);
        $grid->add('property_name','Property',true);
        $grid->add('transaction_type','Pay Type', true)->cell( function($value){
            if(($value == 'Credit Card')){
                return '<img src="/img/credit-cards.png" alt="Visa" data-original-title="'.$value.'" title="" class="tooltip_hover" data-toggle="tooltip"  />';
            }else if($value == 'eCheck'){
                return '<img src="/img/echeck.png" alt="Checking" data-original-title="'.$value.'" title="" class="tooltip_hover" data-toggle="tooltip"  />';
            }else if(isset($valueArray[0]) && ($valueArray[0] == 'MasterCard')){
                return $value;
            }
        })->attributes(array("class"=>"trans-column"))->style("text-align:center");
        $grid->add('credit','Credit',true);
        $grid->add('debit','Debit',true);
        $grid->add('total','Total');
        if($view_link) {
            $grid->add('actionvalue', 'Action');
        }
        $grid->row(function ($row) use ($token,$view_link) {
            $idp = $row->cell('id_property')->value;
            $batch = $row->cell('batch')->value;
            $date = $row->cell('date_formatted')->value;
            $row->cell('id_property')->style("display:none;");
            $row->cell('batch')->style("display:none;");
            if($view_link){
                $row->cell('actionvalue')->value = ' <div class="dropdown pull-right">
													  <button class="btn btn-xs btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><span style="color:#fff">Actions</span>
													  <span class="caret"></span></button>
													  <ul class="dropdown-menu">
                                                        <!--li><a href="#" onclick="showDepositDetails('.$idp.','."'$batch'".','."'$date'".');" >View</a></li-->
                                                        <li><a href="'.route('depositbatchdetails',array('token'=>$token,'idp'=>$idp,'batch'=>$batch,'date'=>base64_encode($date))).'" >View</a></li>
													  </ul>
													</div>';
            }


            $row->cell('total')->value($row->cell('credit')->value - $row->cell('debit')->value);
            $row->cell('transaction_type')->style("text-align:center");
        });

        $grid->attributes(array("class"=>"table table-striped table-hover"));
        $grid->orderBy('id','desc');
        $grid->paginate(10);
        $sql =  $filter->query->toSql();
        return view('deposits',array('sql'=>$sql,'filter' => $filter, 'grid' => $grid , 'pageTitle'=>"Deposits Batch Report",'token'=>$token));

    }
}
