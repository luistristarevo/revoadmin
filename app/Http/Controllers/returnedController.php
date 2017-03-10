<?php
namespace App\Http\Controllers;

use App\Models\AccountingReturned;
use Illuminate\Http\Request;
use App\Models\Properties;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class returnedController extends Controller
{

    public function returned($token, Request $request){

		list($data)=explode('|',Crypt::decrypt($token));
		$array_token = json_decode($data,1);
		$idlevel = $array_token['id'];
		$level = $array_token['type'];
		$idadmin = $array_token['iduser'];
		$token = Crypt::encrypt($data.'|'.time().'|'.config('app.appAPIkey'));

		//security
		$objAdminAuth = new AuthAdminController();
		$objAdminAuth->checkAuthPermissions($array_token['iduser']);

		$returneddb = new AccountingReturned();
		$filter = \DataFilter::source($returneddb->getReturnedByLevelIdlevel($level,$idlevel));

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

        $filter->add('useraccount','User Account','text');
        $filter->add('username','Username','text');
        $filter->add('rdate', 'Date', 'daterange')->format('m/d/Y', 'en');

		$filter->submit('search');
		$filter->reset('reset');
		$filter->build();	

		$grid = \DataGrid::source($filter);		
		$grid->attributes(array("class"=>"table table-striped table-hover"));

        switch (strtoupper($level)){
            case 'B':
                $grid->add('partner','Partner',  true);
                $grid->add('group','Group');
                $grid->add('merchant','Merchant');
                break;
            case 'P':
                $grid->add('partner','Partner',  true);
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

		$grid->add('useraccount','User Account');
		$grid->add('username','Username');
		$grid->add('amount','Amount');
		$grid->add('reason','Reason');
		$grid->add('rdate','Date',true)->style("text-align:left");
		$grid->paginate(10);

        $sql =  $filter->query->toSql();

        return view('returnedlist',array('sql'=>$sql,'pageTitle'=>'Returned', 'filter' => $filter, 'grid' => $grid, 'atoken' => $token));
    
    }
    

	
}
