<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class ApplicationController extends Controller
{

    public function companiesByPartner($partner_id){
        $company = Session::get('company');
        $companies = DB::table('companies')
            ->where('id_partners',$partner_id)
            ->get();
        return view('application.companiesSelect',array('companies'=>$companies,'company'=>$company));
    }

}
