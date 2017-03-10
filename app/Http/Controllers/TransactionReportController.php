<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Crypt;
use App\Models\AccountingTransactions;
use Illuminate\Support\Facades\Session;
use App\CustomClass\PaymentProcessor;
use App\CustomClass\Email;


class TransactionReportController extends Controller
{
    public function accountingtransactions($token, Request $request){

        list($data)=explode('|',Crypt::decrypt($token));
        $array_token = json_decode($data,1);
        $idlevel = $array_token['id'];
        $level = $array_token['type'];
        $token = Crypt::encrypt($data.'|'.time().'|'.config('app.appAPIkey'));


        //security
        $objAdminAuth = new AuthAdminController();
        $objAdminAuth->checkAuthPermissions($array_token['iduser']);


        $accountingtransactions = new AccountingTransactions();

        $filter = \DataFilter::source($accountingtransactions->getAccountingTransactions($idlevel, $level));
        $filter->attributes(array("id"=>"transactionRdatafilter"));
        $filter->add('accounting_transactions.trans_first_post_date','Date', 'daterange')->format('m/d/Y', 'en');
        $filter->add('accounting_transactions.trans_id','Transaction Id','text');

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

        $filter->add('trans_payment_type','Pay Method','select')->options(array('' => '--Pay Method--','cc' => 'Credit Card', 'ec' => 'E-Check'));
        $filter->add('web_users.first_name','First Name','text');
        $filter->add('web_users.last_name','Last Name','text');
        $filter->add('accounting_transactions.trans_net_amount','Amount','text');
        $filter->add('accounting_transactions.trans_convenience_fee','Fee','text');
        $filter->add('accounting_transactions.trans_total_amount','Net Charge','text');
        $filter->add('source','Source','select')->options(array('' => '--Source--','web' => 'Web', 'qpay' => 'Quick Pay', 'eterm' => 'E-Terminal', 'ivr' => 'IVR', 'api' => 'API', 'lox' => 'Lockbox', 'rdc' => 'RDC', 'HAX' => 'HAX', 'CDO' => 'CDO', 'NACHA' => 'NACHA'));
        $filter->add('accounting_transactions.trans_type','Type','select')->options(array('' => '--Type--', '0' => 'One Time', '1' => 'AutoPayment', '9' => 'Void', 'Discover' => 'Discover', 'Saving' => 'Saving'));
        $filter->add('accounting_transactions.trans_card_type','Pay Type','select')->options(array('' => '--Pay Type--', 'Visa' => 'Visa', 'Checking' => 'Checking', 'MasterCard' => 'MasterCard', '10' => 'Refunded'))->scope(function($query, $value){
            return $query->where('accounting_transactions.trans_card_type', 'like', '%'.$value.'%');
        });
        $filter->add('accounting_transactions.trans_status','Status','select')->options(array('' => '--Status--', '0'=>'Errored', '1' => 'Approved', '2' => 'Declined', '3' => 'Returned', '4' => 'Voided'));
        $filter->submit('search', 'BL', array('class' => 'btn btn-md btn-primary'));
        $filter->reset('reset', 'BL', array('class' => 'btn btn-md btn-primary'));
        $filter->build();

        $grid = \DataGrid::source($filter);
        $grid->attributes(array("class"=>"table table-striped table-hover"));
        $grid->add($token,'token')->style("display:none;");
        $grid->add('status','Status')->cell( function($value){
            if($value == '0'){
                return '<img src="/img/red_alert.png" alt="Errored" data-original-title="Errored transaction." title="" class="tooltip_hover" data-toggle="tooltip"  />';
            }else if($value == '1'){
                return '<img src="/img/check.png" alt="Approved" data-original-title="Approved transaction." title="" class="tooltip_hover" data-toggle="tooltip"  />';
            }else if($value == '2'){
                return '<img src="/img/declined.png" alt="Declined" data-original-title="Declined transaction." title="" class="tooltip_hover" data-toggle="tooltip"  />';
            }else if($value == '3'){
                return '<img src="/img/returned.png" alt="Returned" data-original-title="Returned transaction." title="" class="tooltip_hover" data-toggle="tooltip"  />';
            }else if($value == '4'){
                return '<img src="/img/void.png" alt="Voided" data-original-title="Voided transaction." title="" class="tooltip_hover" data-toggle="tooltip"  />';
            }
        })->attributes(array("class"=>"trans-column"));
        $grid->add('trans_date','Date', true)->attributes(array("class"=>"trans-column"));
        $grid->add('trans_id','Trans. Id', true)->attributes(array("class"=>"trans-column"));
        //$grid->add('status','Status')->style("display:none;");

        if($level == 'B'){
            $grid->add('partner','Partner', true)->attributes(array("class"=>"trans-column"));
            $grid->add('group','Group', true)->attributes(array("class"=>"trans-column"));
            $grid->add('merchant','Property', true)->attributes(array("class"=>"trans-column"));
            $grid->add('account_number','AN', true)->attributes(array("class"=>"trans-column"));
        }
        if($level == 'P'){
            $grid->add('partner','Partner', true)->attributes(array("class"=>"trans-column"));
            $grid->add('group','Group', true)->attributes(array("class"=>"trans-column"));
            $grid->add('merchant','Property', true)->attributes(array("class"=>"trans-column"));
            $grid->add('account_number','AN', true)->attributes(array("class"=>"trans-column"));
        }
        if($level == 'M'){
            $grid->add('address_unit','Unit', true)->attributes(array("class"=>"trans-column"));
        }
        //$grid->add('trans_date','Date');
        $grid->add('web_user_name','Name', true)->attributes(array("class"=>"trans-column"));
        //$grid->add('last_name','Last Name');
        /*$grid->add('pay_method','Pay Method', true)->cell( function($value){
            if($value == 'ec'){
                return '<img src="/img/echeck.png" alt="E-Check" data-original-title="E-Check" title="" class="tooltip_hover" data-toggle="tooltip"  />';
            }else if($value == 'cc'){
                return '<img src="/img/credit-cards.png" alt="Credit Cards" data-original-title="Credit Cards" title="" class="tooltip_hover" data-toggle="tooltip"  />';
            }else if($value == 'CASH'){
                return '<img src="/img/cash.png" alt="Cash" data-original-title="Cash" title="" class="tooltip_hover" data-toggle="tooltip"  />';
            }else if($value == 'MISC'){
                return '<img src="/img/misc.png" alt="Misc" data-original-title="Miscellaneous" title="" class="tooltip_hover" data-toggle="tooltip"  />';
            }
        });*/
        //$grid->add('pay_type','Pay Type');
        $grid->add('pay_type','Pay Type', true)->cell( function($value){
            $valueArray =  explode(" ", $value);
            if(isset($valueArray[0]) && ($valueArray[0] == 'Visa')){
                return '<img src="/img/visa.png" alt="Visa" data-original-title="'.$value.'" title="" class="tooltip_hover" data-toggle="tooltip"  />';
            }else if(isset($valueArray[0]) && (rtrim($valueArray[0], '(') == 'Checking')){
                return '<img src="/img/echeck.png" alt="Checking" data-original-title="'.$value.'" title="" class="tooltip_hover" data-toggle="tooltip"  />';
            }else if(isset($valueArray[0]) && ($valueArray[0] == 'MasterCard')){
                return '<img src="/img/mastercard.png" alt="Master Card" data-original-title="'.$value.'" title="" class="tooltip_hover" data-toggle="tooltip"  />';
            }else if(isset($valueArray[0]) && ($valueArray[0] == 'Discover')){
                return '<img src="/img/discover.png" alt="Discover" data-original-title="'.$value.'" title="" class="tooltip_hover" data-toggle="tooltip"  />';
            }else if(isset($valueArray[0]) && ($valueArray[0] == 'Saving')){
                return '<img src="/img/saving.png" alt="Saving" data-original-title="'.$value.'" title="" class="tooltip_hover" data-toggle="tooltip"  />';
            }else{
                return $value;
            }
        })->attributes(array("class"=>"trans-column"));

        $grid->add('net_amount','Amount', true)->attributes(array("class"=>"trans-column"));
        $grid->add('net_fee','Fee', true)->attributes(array("class"=>"trans-column"));
        $grid->add('net_charge','Net Charge', true)->attributes(array("class"=>"trans-column"));
        $grid->add('stype','Type')->cell( function($value){
            if($value == '0'){
                return 'One Time';
            }else if($value == '1'){
                return 'Auto Payment';
            }else if($value == '9'){
                return 'Void';
            }else if($value == '10'){
                return 'Refunded';
            }
        })->attributes(array("class"=>"trans-column"));
        $grid->add('trans_source','Source')->attributes(array("class"=>"trans-column"));
        //$grid->add('auth_code','Auth Code');
        $grid->add('actionvalue','Action');
        $grid->row(function ($row) {
            $id = $row->cell('trans_id')->value;
            $token = $row->cells[0]->value;
            $status = $row->cell('status')->value;
            $statusArray = explode(" ", $status);
            $statusValueArray[1] = "";
            if(isset($statusArray[2])){
                $statusValueArray = explode("=", $statusArray[2]);
            }


            $view_link = '';
            foreach(Session::get('user_permissions') as $p){
                if($p['route']=='viewtransaction'){
                    $view_link = '<li><a onclick="showTransdetail(\''.$id.'\');" >View</a></li>';
                    break;
                }
            }

            $void_link = '';
            foreach(Session::get('user_permissions') as $p){
                if($p['route']=='void'){
                    $void_link = '<li><a onclick="voidpr(\''.$id.'\')" >Void</a></li>';
                    break;
                }
            }

            $email_link = '';
            foreach(Session::get('user_permissions') as $p){
                if($p['route']=='emailreceipt'){
                    $email_link = '<li><a onclick="sendpr(\''.$id.'\')" >Email Receipt</a></li>';
                    break;
                }
            }

            if(trim($statusValueArray[1], '"')  == "Approved"){
                $row->cell('actionvalue')->value = ' <div class="dropdown pull-right">
													  <button class="btn btn-xs btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><span style="color:#fff">Actions</span>
													  <span class="caret white-font-color"></span></button>
													  <ul class="dropdown-menu">
														'.$view_link.'
														'.$void_link.'
														'.$email_link.'
													  </ul>
													</div>';
            }else{
                $row->cell('actionvalue')->value = '<div class="dropdown pull-right">
												  <button class="btn btn-xs btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><span style="color:#fff">Actions</span>
												  <span class="caret white-font-color"></span></button>
												  <ul class="dropdown-menu">
													'.$view_link.'
												  </ul>
												</div>';
            }
            // $row->cell('trans_id')->style("display:none;");
            // $row->cell('status')->style("display:none;");
            $row->cells[0]->style("display:none;");
            //$row->cells[1]->style("display:none;");

        });
        //style data grid
        //$grid->edit('/transactionReport/'.$token.'/edit', 'Action','show', 'trans_id');
        $grid->link('/transactionReport/'.$token.'/report', 'Export', "TR", array('id' => 'exportCSV', 'class' => 'btn btn-md btn-success' ));
        $grid->orderBy('trans_date','desc');
        $recordperpage = 10;
        $ord = 'trans_date';
        $page = 1;
        if($request->get("ord") != null){
            $ord = $request->get("ord");
        }
        if($request->get("page") != null){
            $page = $request->get("page");
        }
        if($request->get("recordperpage") != null){
            $grid->paginate($request->get("recordperpage"));
            $recordperpage = $request->get("recordperpage");
        }else{
            $grid->paginate(10);
        }

        $sql =  $filter->query->toSql();

        //$grid->getGrid('rapyd::datagridcustom');
        return view('accountingtransaction',array('sql'=>$sql,'pageTitle'=>'Transaction Report', 'filter' => $filter, 'grid' => $grid, 'atoken' => $token, 'recordperpage' => $recordperpage, 'page' => $page, 'ord' => $ord));
    }

    public function transdetail($token, $trans_id, Request $request){

        list($data)=explode('|',Crypt::decrypt($token));
        $array_token = json_decode($data,1);

        //security
        $objAdminAuth = new AuthAdminController();
        $objAdminAuth->checkAuthPermissions($array_token['iduser']);

        $accountingtransactions = new AccountingTransactions();
        $accutransactiondetail = $accountingtransactions->getTransactionDetail($trans_id);
        $accountingdetailHtml = view('accountingtransactiondetail',array('pageTitle'=>'Transaction Detail', 'accutransactiondetail' => $accutransactiondetail))->render();
        return response()->json( array('errcode' => 0, 'msg' => $accountingdetailHtml) );
    }

    public function voidtransaction(Request $request, $id, $sts){
        //security
        $objAdminAuth = new AuthAdminController();
        $objAdminAuth->checkAuthPermissions(Session::get('user_logged')['id']);

        $paymentprocessor = new PaymentProcessor();
        if($sts == 1){
            $result = $paymentprocessor->runVoidTransaction($id);
        }
        echo json_encode($result); die;

    }

    public function emailreceipt(Request $request){

        //security
        $objAdminAuth = new AuthAdminController();
        $objAdminAuth->checkAuthPermissions(Session::get('user_logged')['id']);
        //echo $request->get("id").' == '.$request->get("eml");
        $email = new Email();
        $result = $email->transactionreceipt($request->get("id"), $request->get("eml"));
        echo json_encode($result); die;

    }
}


