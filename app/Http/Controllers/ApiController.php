<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transations;
use App\Models\Properties;
use Illuminate\Support\Facades\Session;
use App\User;

class ApiController extends Controller
{

    function setautopaycat($token,$trans_id,Request $request){
        $atoken=\Illuminate\Support\Facades\Crypt::decrypt($token);
        list($property_id,$web_user_id,$time,$apikey)=explode('|',$atoken);
        if(($time+60*20)<time()){
            //return response()->json(array('response'=>261,'responsetext'=>'Invalid Token'));
        }
        if($property_id<=0){
            return response()->json(array('response'=>261,'responsetext'=>'Invalid Token'));
        }

        if($web_user_id<=0){
            return response()->json(array('response'=>261,'responsetext'=>'Invalid Token'));
        }

        if($apikey!= config('app.appAPIkey')){
            return response()->json(array('response'=>261,'responsetext'=>'Invalid Token'));

        }

        $obj_trans= new Transations();
        $obj_property= new Properties();
        $data=array();
        //get payments Categories
        $paymentCategories=$obj_property->getPaymentType($property_id);


        //Bundle config
        $bundle_config= Session::get('bundle_interface_config');
        if($bundle_config){
            if(isset($bundle_config['payment']) && $bundle_config['payment']){
                if(isset($bundle_config['payment']['categories']) && $bundle_config['payment']['categories'])
                {
                    $obj_categories = new Categories();
                    foreach ($bundle_config['payment']['categories'] as $pcitem) {

                        if($pcitem['save']==true){
                            $catdb = $obj_categories->getCatByName($pcitem['name']);
                            $id_cat = 0;
                            if(!$catdb){
                                $id_cat = DB::table('payment_type')->insertGetId([
                                    'payment_type_name' => $pcitem['name'],
                                    'property_id' => $property_id,
                                    'amount' => $pcitem['amount'],
                                    'is_balance' => $pcitem['is_balance'],
                                ]);
                            }
                        }

                        $new_cat['payment_type_id'] = $id_cat;
                        $new_cat['payment_type_name'] = $pcitem['name'];
                        $new_cat['amount'] = strval($pcitem['amount']);

                        $push = true;
                        foreach($paymentCategories as $dataitem){
                            if($dataitem['payment_type_name']==$new_cat['payment_type_name']){
                                $push = false;
                                break;
                            }
                        }

                        if($push)
                            array_push($paymentCategories,$new_cat);


                    }

                }

            }
        }


        //get saved categories by trans_id
        $recurringPaymentCat=$obj_property->getReccurringPaymentType ($trans_id);

        for($i=0;$i<count($paymentCategories);$i++){
            for($j=0;$j<count($recurringPaymentCat);$j++){
                if($paymentCategories[$i]['payment_type_id']==$recurringPaymentCat[$j]['category_id']){
                    $paymentCategories[$i]['amount']=$recurringPaymentCat[$j]['amount'];
                    $paymentCategories[$i]['enabled']=1;
                    break;
                }
            }
        }

        $data['autopay_amount']= $obj_trans->getAutopayAmount($web_user_id, $trans_id,$property_id);
        if(empty($data['autopay_amount'])){
            return response()->json(array('response'=>54,'responsetext'=>'Sorry! We cannot find you AutoPay.'));
        }
        $merchant=$obj_property->getOnlyIds($property_id);

        $nomemo=$obj_property->getPropertySettings($property_id, $merchant['id_companies'], $merchant['id_partners'], 'NOMEMO');
        $data['nomemo']=$nomemo;

        $data['paymentCategories']=$paymentCategories;

        // credentials
        $data['dbcredentials'] = $obj_property->getcredRecurringCredentials($property_id);


        return view('components.editPaymentsType',$data);
    }

    function savecategories($token,$info,Request $request){

        $atoken=\Illuminate\Support\Facades\Crypt::decrypt($token);
        list($property_id,$web_user_id,$time,$apikey)=explode('|',$atoken);
        if(($time+60*20)<time()){
            //return response()->json(array('response'=>261,'responsetext'=>'Invalid Token'));
        }
        if($property_id<=0){
            return response()->json(array('response'=>261,'responsetext'=>'Invalid Token'));
        }

        if($web_user_id<=0){
            return response()->json(array('response'=>261,'responsetext'=>'Invalid Token'));
        }

        if($apikey!= config('app.appAPIkey')){
            return response()->json(array('response'=>261,'responsetext'=>'Invalid Token'));

        }

        $autopayInfo=json_decode($info,true);
        $obj_transactions= new Transations();

        $result=$obj_transactions->updateReccuringTransCategories($autopayInfo, $property_id, $web_user_id);
        return response()->json(array('response'=>0,'responsetext'=>$result));

    }

    function setautopayfreq($token,$info,Request $request){

        $atoken=\Illuminate\Support\Facades\Crypt::decrypt($token);
        list($property_id,$web_user_id,$time,$apikey)=explode('|',$atoken);

        if(($time+60*20)<time()){
            //return response()->json(array('response'=>261,'responsetext'=>'Invalid Token'));
        }
        if($property_id<=0){
            return response()->json(array('response'=>261,'responsetext'=>'Invalid Token'));
        }

        if($web_user_id<=0){
            return response()->json(array('response'=>261,'responsetext'=>'Invalid Token'));
        }

        if($apikey!= config('app.appAPIkey')){
            return response()->json(array('response'=>261,'responsetext'=>'Invalid Token'));

        }

        $data=array();
        $obj_transactions= new Transations();

        $autpayInfo=$obj_transactions->getAutopayInfoByTrans_id($info, $property_id, $web_user_id);

        $selfreq=$autpayInfo['trans_schedule'];
        $data['selfreq']=$selfreq;

        $obj_property = new Properties();
        $ids=$obj_property->getOnlyIds($property_id);

        if($autpayInfo['dynamic']==1){
            $freq=$obj_property->getFreqDrp($property_id, $ids['id_companies'], $ids['id_partners']);
            $days=$obj_property->getDaysDrp($property_id, $ids['id_companies'], $ids['id_partners']);
            $selend=-1;
            $data['isdrp']=1;
        }else{
            $data['isdrp']=0;
            $freq=$obj_property->getFreqAutpay($property_id, $ids['id_companies'], $ids['id_partners']);
            $days=$obj_property->getDaysAutopay($property_id, $ids['id_companies'], $ids['id_partners']);
            $left=$autpayInfo['trans_numleft'];
            if($autpayInfo['trans_numleft']>900){
                $selend=-1;
            }else if($autpayInfo['trans_numleft']!=0){
                $left=$left-1;
            }

            switch($autpayInfo['trans_schedule']) {
                case 'weekly':
                    $selend = date('Y|m',strtotime('+'.$left.' week',strtotime($autpayInfo['trans_next_post_date'])));
                    break;
                case 'yearly':
                    $selend = date('Y|m',strtotime('+'.$left.' year',strtotime($autpayInfo['trans_next_post_date'])));
                    break;
                case 'biannually':
                    $left=6*$left;
                    $selend = date('Y|m',strtotime('+'.$left.' months',strtotime($autpayInfo['trans_next_post_date'])));
                    break;
                case 'quaterly':
                case 'quarterly':
                    $left=3*$left;
                    $selend = date('Y|m',strtotime('+'.$left.' months',strtotime($autpayInfo['trans_next_post_date'])));
                    break;
                case 'biweekly':
                    $left=14*$left;
                    $selend = date('Y|m',strtotime('+'.$left.' days',strtotime($autpayInfo['trans_next_post_date'])));
                    break;
                case 'monthly':
                    $left=$left;
                    $selend = date('Y|m',strtotime('+'.$left.' month',strtotime($autpayInfo['trans_next_post_date'])));
                    break;
                default :
                    $selend = date('Y|m',strtotime($autpayInfo['trans_next_post_date']));
                    break;
            }

        }

        $data['selday']=date('j',strtotime($autpayInfo['trans_next_post_date']));
        $data['selstart']=date('Y|m',strtotime($autpayInfo['trans_next_post_date']));
        $data['selend']=$selend;
        $data['freq']=$freq;
        $data['days']=$days;

        //adding 5 years in advance to end and start day on the autopayments
        $data['y5inadvance']=$obj_property->get5yearInAdvance();

        //Bundle config
        $bundle_config= Session::get('bundle_interface_config');
        if($bundle_config){
            if(isset($bundle_config['methods']['autopayOptions']['daysAllowed'])){
                if(isset($bundle_config['methods']['autopayOptions']['daysAllowed'][0])){
                    $data['days'][0] = $bundle_config['methods']['autopayOptions']['daysAllowed'][0];
                    $data['days'][1] = $bundle_config['methods']['autopayOptions']['daysAllowed'][count($bundle_config['methods']['autopayOptions']['daysAllowed'])-1];
                }

            }
            if(isset($bundle_config['methods']['autopayOptions']['freq'])){
                $data['freq'] = $bundle_config['methods']['autopayOptions']['freq'];
            }
            if(isset($bundle_config['methods']['autopayOptions']['yearsCount'])){
                $data['y5inadvance']=$obj_property->get5yearInAdvance(true,$bundle_config['methods']['autopayOptions']['yearsCount']);
            }


        }


        return view('components.autopayeditfreq',$data);
    }

    function savefrequence($token,$info,Request $request){

        $atoken=\Illuminate\Support\Facades\Crypt::decrypt($token);

        list($property_id,$web_user_id,$time,$apikey)=explode('|',$atoken);
        if(($time+60*20)<time()){
            //return response()->json(array('response'=>261,'responsetext'=>'Invalid Token'));
        }
        if($property_id<=0){
            return response()->json(array('response'=>261,'responsetext'=>'Invalid Token'));
        }

        if($web_user_id<=0){
            return response()->json(array('response'=>261,'responsetext'=>'Invalid Token'));
        }

        if($apikey!= config('app.appAPIkey')){
            return response()->json(array('response'=>261,'responsetext'=>'Invalid Token'));

        }

        $obj_transactions= new Transations();

        $data=array();
        $autopayInfo=  json_decode($info,true);

        if(isset($autopayInfo['next_api_date']) && !empty($autopayInfo['next_api_date'])){ //using by api
            $autopayInfo['next_day'] = $autopayInfo['next_api_date'];
            $autopayInfo['end_date']=-1;
        }else{
            $tmp_day= explode("|", $autopayInfo['start_date']);
            $autopayInfo['next_day']=$tmp_day[0]."-".$tmp_day[1]."-".$autopayInfo['day'];
            $today=date("Y-m-d");
            $autopayInfo['next_day']=date("Y-m-d",  strtotime($autopayInfo['next_day']));

            if($autopayInfo['next_day']<=$today){
                return response()->json(array('response'=>876,'responsetext'=>'Next Payment Date should be in the future'));
            }
        }

        if($autopayInfo['end_date']!=-1){
            $tmp_day= explode("|", $autopayInfo['end_date']);
            $autopayInfo['end_date']=$tmp_day[0]."-".$tmp_day[1]."-".$autopayInfo['day'];
            if($autopayInfo['end_date']<$autopayInfo['next_day']){
                return response()->json(array('response'=>877,'responsetext'=>'Next Payment Date should be greater than End Payment Date'));
            }
        }
        $autopayInfo['trans_numleft']=$obj_transactions->getNumleft($autopayInfo['freq'], $autopayInfo['next_day'], $autopayInfo['end_date']);

        if(isset($autopayInfo['runcycle']) && !empty($autopayInfo['runcycle'])){ //using only by the API
            $autopayInfo['trans_numleft']=$autopayInfo['runcycle'];
        }


        $obj_transactions->updateAutopayFreq($autopayInfo);

        return response()->json(array('response'=>1,'responsetext'=>'Autopayment succefully updated'));

    }

    function setautopaymeth($token,$info,Request $request){
        $atoken=\Illuminate\Support\Facades\Crypt::decrypt($token);

        list($property_id,$web_user_id,$time,$apikey)=explode('|',$atoken);
        if(($time+60*20)<time()){
            //return response()->json(array('response'=>261,'responsetext'=>'Invalid Token'));
        }
        if($property_id<=0){
            return response()->json(array('response'=>261,'responsetext'=>'Invalid Token'));
        }

        if($web_user_id<=0){
            return response()->json(array('response'=>261,'responsetext'=>'Invalid Token'));
        }

        if($apikey!= config('app.appAPIkey')){
            return response()->json(array('response'=>261,'responsetext'=>'Invalid Token'));

        }

        $data=array();
        $obj_property= new Properties();
        $obj_trans= new Transations();
        $data['credRecurring']=$obj_property->getcredRecurringCredentials($property_id);
        $merchant = $obj_property->getPropertyInfo($property_id);
        $merchant['property_id']=$property_id;
        $drpmethod=$obj_property->getPropertySettings($property_id, $merchant['id_companies'], $merchant['id_partners'], 'DRPMETHODS');
        $datadrpmethods=explode('|',$drpmethod);
        $array_method=$obj_property->getTypeCredentialByCycle($property_id, 1);

        $drp=$obj_trans->get1recurringInfo($info, 'dynamic');
        if($drp==1 && $drpmethod!=''){
            if(!in_array('cc',$datadrpmethods)){
                if(($key = array_search('cc', $array_method)) !== false) {
                    unset($array_method[$key]);
                }
                if(($key = array_search('amex', $array_method)) !== false) {
                    unset($array_method[$key]);
                }
                unset($data['credRecurring']['cc']);
                unset($data['credRecurring']['amex']);
            }
            if(!in_array('ec',$datadrpmethods)){
                if(($key = array_search('ec', $array_method)) !== false) {
                    unset($array_method[$key]);
                }
                unset($data['credRecurring']['ec']);
            }
        }

        //Bundle config
        $bundle_config= Session::get('bundle_interface_config');
        if($bundle_config){
            if(isset($bundle_config['methods']['cc']) && $bundle_config['methods']['cc'] == false){
                unset($data['credRecurring']['cc']);
            }

            if(isset($bundle_config['methods']['ec']) && $bundle_config['methods']['ec'] == false){
                unset($data['credRecurring']['ec']);
            }

            if(isset($bundle_config['methods']['amex']) && $bundle_config['methods']['amex'] == false){
                unset($data['credRecurring']['amex']);
            }
        }


        //get payments profiles
        $obj_user= new User();

        $profiles=$obj_user->getPaymentProfiles($web_user_id);
        $id_profile=$obj_trans->get1recurringInfo($info, 'profile_id');
        $pro_sel=$obj_user->getPaymentProfileById($web_user_id, $id_profile);

        $data['profiles']=array();
        if(!empty($pro_sel)){
            $data['profiles'][0]=$pro_sel;
        }


        for($i=0;$i<count($profiles);$i++){
            if(in_array(substr($profiles[$i]["type"],0,2), $array_method)){
                if($profiles[$i]['id']!=$pro_sel["id"]){
                    $data['profiles'][]=$profiles[$i];
                }
            }
        }

        $data['dbcredentials'] = $obj_property->getcredRecurringCredentials($property_id);

        return view('components.autopayeditMethod',$data);
    }
}

