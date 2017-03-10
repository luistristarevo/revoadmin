<?php
namespace App\Http\Controllers;

use App\Models\AccountingReturned;
use App\Models\Categories;
use Illuminate\Http\Request;
use App\Models\Properties;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class PaymentCategoriesController extends Controller
{

    public function paymentCategories($token, Request $request){

		list($data)=explode('|',Crypt::decrypt($token));
		$array_token = json_decode($data,1);
		$idlevel = $array_token['id'];
		$level = $array_token['type'];
		$idadmin = $array_token['iduser'];
		$token = Crypt::encrypt($data.'|'.time().'|'.config('app.appAPIkey'));

		//security
		$objAdminAuth = new AuthAdminController();
		$objAdminAuth->checkAuthPermissions($array_token['iduser']);

		$catdb = new Categories();
		$filter = \DataFilter::source($catdb->getCategoriesPGM($idlevel,$level));

        switch (strtolower($level)){
            case 'b':
                $filter->add('partners.partner_title','Partner','text');
                $filter->add('companies.company_name','Group','text');
                $filter->add('properties.name_clients','Merchant','text');
                break;
            case 'p':
                $filter->add('partners.partner_title','Partner','text');
                $filter->add('companies.company_name','Group','text');
                $filter->add('properties.name_clients','Merchant','text');
                break;
            case 'g':
                $filter->add('companies.company_name','Group','text');
                $filter->add('properties.name_clients','Merchant','text');
                break;
            case 'm':
                $filter->add('properties.name_clients','Merchant','text');
                break;
        }

        $filter->add('account_number','Account #','text');
        $filter->add('name','Name','text');
        $filter->add('trans_first_post_date', 'Date', 'daterange')->format('m/d/Y', 'en');
        $filter->add('category_name','Description','text');
		$filter->submit('search');
		$filter->reset('reset');
		$filter->build();	

		$grid = \DataGrid::source($filter);		
		$grid->attributes(array("class"=>"table table-striped table-hover"));

        $grid->add('trans_first_post_date','Date',true);
        switch (strtolower($level)){
            case 'b':
                $grid->add('partner_title','Partner');
                $grid->add('company_name','Group');
                $grid->add('name_clients','Merchant');
                break;
            case 'p':
                $grid->add('partner_title','Partner');
                $grid->add('company_name','Group');
                $grid->add('name_clients','Merchant');
                break;
            case 'g':
                $grid->add('company_name','Group');
                $grid->add('name_clients','Merchant');
                break;
            case 'm':
                $grid->add('name_clients','Merchant');
                break;
        }
        $grid->add('account_number','Account #');
        $grid->add('name','Name');
        $grid->add('qty','Quantity',  true);
        $grid->add('amount','Amount',  true);
        $grid->add('category_name','Description')->style("text-align:left");
		$grid->paginate(10);

        $sql =  $filter->query->toSql();

        return view('paymentCategories',array('sql'=>$sql,'pageTitle'=>'Payment Categories', 'filter' => $filter, 'grid' => $grid, 'atoken' => $token));
    
    }

	
}
