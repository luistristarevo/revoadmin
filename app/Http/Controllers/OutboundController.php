<?php
namespace App\Http\Controllers;


use App\Models\Evendorpay;
use Illuminate\Http\Request;
use Crypt;
use Illuminate\Support\Facades\Session;

class OutboundController extends Controller
{
    public function listOutbound($token, Request $request){
        list($data)=explode('|',Crypt::decrypt($token));
        $array_token = json_decode($data,1);
        $idlevel = $array_token['id'];
        $level = $array_token['type'];
        $token = Crypt::encrypt($data.'|'.time().'|'.config('app.appAPIkey'));

        //security
        $objAdminAuth = new AuthAdminController();
        $objAdminAuth->checkAuthPermissions($array_token['iduser']);

        $evendorpay_obj = new Evendorpay();
        $data = $evendorpay_obj->getEvendorRecords($level,$idlevel);


        $filter = \DataFilter::source($data);

        switch (strtoupper($level)){
            case 'B':
                $filter->add('partner_name','Partner','text');
                $filter->add('group_name','Group','text');
                $filter->add('merchant','Merchant','text');
                break;
            case 'P':
                $filter->add('partner_name','Partner','text');
                $filter->add('group_name','Group','text');
                $filter->add('merchant','Merchant','text');
                break;
            case 'G':
                $filter->add('group_name','Group','text');
                $filter->add('merchant','Merchant','text');
                break;
            case 'M':
                $filter->add('merchant','Merchant','text');
                break;
        }

        $filter->add('trans_unique_id','Trans ID','text');
        $filter->add('name','Name','text');
        $filter->submit('Search');
        $filter->reset('Reset');
        $filter->build();

        $grid = \DataGrid::source($filter);
        $grid->add('trans_first_post_date','Date',true);

        switch (strtoupper($level)){
            case 'B':
                $grid->add('partner_title','Partner',true);
                $grid->add('company_name','Group',true);
                $grid->add('name_clients','Merchant',true);
                break;
            case 'P':
                $grid->add('partner_title','Partner',true);
                $grid->add('company_name','Group',true);
                $grid->add('name_clients','Merchant',true);
                break;
            case 'G':
                $grid->add('company_name','Group',true);
                $grid->add('name_clients','Merchant',true);
                break;
            case 'M':
                $grid->add('name_clients','Merchant',true);
                break;
        }

        $grid->add('trans_unique_id','Trans ID');
        $grid->add('name','Name');
        $grid->add('trans_net_amount','Ammount');
        $grid->add('trans_convenience_fee','Fee');
        $grid->add('trans_total_amount','Total');


        $grid->attributes(array("class"=>"table table-striped table-hover"));
        $grid->orderBy('trans_id','desc');
        $grid->paginate(10);
        $sql =  $filter->query->toSql();
        return view('outbound',array('sql'=>$sql,'filter' => $filter, 'grid' => $grid , 'pageTitle'=>"Outbound Payments",'token'=>$token,'atoken'=>$token));
    }
}
