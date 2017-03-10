<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccountingRecurringTransactions;
use Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class RecurringReportController extends Controller
{
    public function recurringpayments($token, Request $request){
        list($data)=explode('|',Crypt::decrypt($token));
        $array_token = json_decode($data,1);
        $idlevel = $array_token['id'];
        $level = $array_token['type'];
        $token = Crypt::encrypt($data.'|'.time().'|'.config('app.appAPIkey'));

        var_dump($array_token);
        //security
        $objAdminAuth = new AuthAdminController();
        $objAdminAuth->checkAuthPermissions($array_token['iduser']);

        $accountingrecurrtrans = new AccountingRecurringTransactions();
        $datadb = $accountingrecurrtrans->getRecurringPayments($idlevel, $level);

        $filter = \DataFilter::source($datadb);
        $filter->attributes(array("id"=>"recurringRdatafilter"));
        $filter->add('accounting_recurring_transactions.trans_next_post_date','Date', 'daterange')->format('m/d/Y', 'en');


        switch (strtoupper($level)){
            case 'B':
                $filter->add('partners.partner_title','Partner','text');
                $filter->add('companies.company_name','Group','text');
                $filter->add('properties.name_clients','Merchant','text');
                break;
            case 'P':
                $filter->add('partners.partner_title','Partner','text');
                $filter->add('companies.company_name','Group','text');
                $filter->add('properties.name_clients','Merchant','text');
                break;
            case 'G':
                $filter->add('companies.company_name','Group','text');
                $filter->add('properties.name_clients','Merchant','text');
                break;
            case 'M':
                $filter->add('properties.name_clients','Merchant','text');
                break;
        }


        $filter->add('web_users.account_number','Account #','text');
        $filter->add('web_users.first_name','First Name','text');
        $filter->add('web_users.last_name','Last Name','text');
        $filter->add('accounting_recurring_transactions.trans_payment_type','Pay Method','select')->options(array('' => '--Select--','cc' => 'Credit Card', 'ec' => 'Checking'));
        $filter->submit('search');
        $filter->reset('reset');
        $filter->build();
        $grid = \DataGrid::source($filter);
        //print_r($grid); die;
        // die;
        $grid->attributes(array("class"=>"table table-striped table-hover"));
        //print_r($grid); die;
        // die;
        $grid->add($token,'token')->style("display:none;");
        $grid->add('trans_id','ID')->style("display:none;");
        $grid->add('property_id','property_id')->style("display:none;");
        $grid->add('web_user_id','web_user_id')->style("display:none;");

        $grid->add('trans_next_date','Date', true)->cell( function($value){ return date('Y-m-d', strtotime($value));});

        switch (strtoupper($level)){
            case 'B':
                $grid->add('partner','Partner');
                $grid->add('group','Group');
                $grid->add('merchant','Merchant');
                break;
            case 'P':
                $grid->add('partner','Partner');
                $grid->add('group','Group');
                $grid->add('merchant','Merchant');
                break;
            case 'G':
                $grid->add('group','Group');
                $grid->add('merchant','Merchant');
                break;
            case 'M':
                $grid->add('merchant','Merchant');
                break;
        }



        $grid->add('webuser','Account #');
        $grid->add('first_name','First Name');
        $grid->add('last_name','Last Name');
        $grid->add('pay_type','Pay Type');
        $grid->add('net_amount','Amount');
        $grid->add('net_fee','Fee');
        $grid->add('net_charge','Net Charge');
        $grid->add('stype','Dynamic')->cell(function($value){ if($value == 0){ return '<span class="label label-danger">No</span>';}else{ return '<span class="label label-success">Yes</span>';}});
        $grid->add('actionvalue','Action');

        $grid->row(function ($row) {
            $row->cell('actionvalue')->style("text-align: right;");
            $id = $row->cell('trans_id')->value;
            $dynamic = $row->cell('stype')->value;
            $property_id = $row->cell('property_id')->value;
            $web_user_id = $row->cell('web_user_id')->value;

            $token = $row->cells[0]->value;
            $edit_links =null;
            $tokenpopup=\Illuminate\Support\Facades\Crypt::encrypt($property_id.'|'.$web_user_id.'|'.time().'|'.config('app.appAPIkey'));

            $edit_pd = '';
            foreach(Session::get('user_permissions') as $p){
                if($p['route']=='api2setautopaycat'){
                    $edit_pd = '<li><a onclick="editpaymentdetails(\''.$id.'\',\''.$tokenpopup.'\');" >Edit Payment Details</a></li>';
                    break;
                }
            }

            $edit_fq = '';
            foreach(Session::get('user_permissions') as $p){
                if($p['route']=='api2setautopayfreq'){
                    $edit_fq = '<li><a onclick="editfrequency(\''.$id.'\',\''.$tokenpopup.'\');" >Edit Frequency</a></li>';
                    break;
                }
            }

            $edit_pm = '';
            foreach(Session::get('user_permissions') as $p){
                if($p['route']=='api2setautopaymeth'){
                    $edit_pm = '<li><a onclick="editpaymentmethods(\''.$id.'\',\''.$tokenpopup.'\');" >Edit Payment Methods</a></li>';
                    break;
                }
            }

            $cancel = '';
            foreach(Session::get('user_permissions') as $p){
                if($p['route']=='cancelrecurring'){
                    $cancel = '<li><a href="#" onclick= "cancelrp(\''.$id.'\');" >Cancel</a></li>';
                    break;
                }
            }

            if($dynamic=="No"){
                $edit_links = $edit_pd.$edit_fq.$edit_pm;
            }
            else{
                $edit_links = $edit_fq.$edit_pm;
            }
            $row->cell('actionvalue')->value = '<div class="dropdown">
													<button class="btn btn-xs btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><span style="color:#fff">Action</span>
													<span class="caret"></span></button>
													<ul class="dropdown-menu pull-right">
													 '.$edit_links.'
													   '.$cancel.'
													</ul>
												  </div>';
            $row->cell('trans_id')->style("display:none;");
            $row->cell('property_id')->style("display:none;");
            $row->cell('web_user_id')->style("display:none;");
            $row->cells[0]->style("display:none;");

        });
        $grid->link('/recurringReport/'.$token.'/report',"Export", "TR", array('onClick' => 'return false;', 'id' => 'exportCSV'));
        $grid->orderBy('trans_next_date','desc');
        $grid->paginate(10);

        return view('recurringpayments',array('pageTitle'=>'Autopayments Report', 'filter' => $filter,'grid' => $grid,'level'=>$level,'idlevel'=>$idlevel, 'atoken' => $token));
    }

    public function cancelrecurring(Request $request , $trans_id, $type = 4){

        if(isset($trans_id) && $trans_id != "") {
            $accountingrecurrtrans = new AccountingRecurringTransactions();
            $trans_numleft = 1;
            $schedule = '';
            $msg = '';
            $wuser = 0;
            $accountingtransactiondetail = $accountingrecurrtrans->getaccountingtransactiondetail($trans_id);
            if(!empty($accountingtransactiondetail)){

                $schedule = $accountingtransactiondetail[0]['trans_schedule'];
                $wuser = $accountingtransactiondetail[0]['trans_web_user_id'];

            }
            //activate user
            $accountingrecurrtrans->updateWebUserStatus($wuser);

            switch ($schedule) {
                case 'onetime':
                    $trans_numleft = 1;
                    break;
                case "monthly":
                    $trans_numleft = 12;
                    break;
                case "bimonthly"://every 2 months
                    $trans_numleft = 6;
                    break;
                case "quarterly": //every 3 months
                    $trans_numleft = 4;
                    break;
                case "biannually": //every 6 months
                    $trans_numleft = 2;
                    break;
                case "annually": //every 12 months
                    $trans_numleft = 1;
                    break;
            }
            if($type == 1){
                //tomorrow
                $newdate = date('Y-m-d 00:00:00',strtotime('+1 day'));
                $accountingrecurrtrans->updateAccountingRecurrTransactionDetail($trans_id, $newdate, $trans_numleft);
                $msg = 'Autopay was restarted';
            }else if($type == 2) {

                //next month
                $tdate = '';
                $postdate = $accountingrecurrtrans->getaccountingtransactionpostdatel($trans_id);
                if(!empty($postdate)){
                    $tdate =  $postdate[0]['trans_next_post_date'];
                }
                $dm = substr($tdate, 8,2);
                $newdate = date('Y-m-'.$dm.' 00:00:00',strtotime('+1 month'));
                $accountingrecurrtrans->updateAccountingRecurrTransactionDetail($trans_id, $newdate, $trans_numleft);
                $msg = 'Autopay was restarted';

            }else if($type == 4) {

                $accountingrecurrtrans->updateaccountingtransstatus($trans_id);
                $msg = 'Autopay was stopped';

            }

            //$fullResult['ERROR'] = '-1';
            //$fullResult['RESULT'] = array('txt' => $msg);
            echo json_encode(array("ERROR" => '-1', "RESULT" => $msg));


        }else {
            echo json_encode(array("ERROR" => '1', "RESULT" => "I don't have arguments."));
        }

    }

}
