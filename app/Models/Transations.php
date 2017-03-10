<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model; 
use DB;

class Transations extends Model {
    
    protected $table = 'accounting_transactions';

    function getAutopayAmount($web_user_id, $trans_id,$property_id){
        $values=DB::table('accounting_recurring_transactions')
            ->where('trans_id',$trans_id)
            ->where('trans_web_user_id',$web_user_id)
            ->where('property_id',$property_id)
            ->where('trans_status',1)
            ->select('trans_recurring_net_amount','trans_recurring_convenience_fee','trans_payment_type')
            ->first();
        if(!empty($values))
            return $values;
        return 0;
    }

    function updateReccuringTransCategories($autopayInfo,$id_property,$web_user_id){

        $obj_properties = new Properties();
        $var= $obj_properties->getOnlyIds($id_property);
        $id_partners=$var['id_partners'];
        $id_companies=$var['id_companies'];
        $total_amount=$autopayInfo['total_amount'];

        $result= DB::table('accounting_recurring_transactions')->select('trans_payment_type','trans_card_type')->where('trans_id',$autopayInfo['trans_id'])->first();
        $type=$result['trans_payment_type'];
        $card_type=$result['trans_card_type'];
        if(strtolower(substr($card_type, 0,1)=='a')){$type='am';}

        $credential= array();
        $credential= $obj_properties->getCredentialtype_isrecurring($type,$id_property,1);

        $conv_fee= $this->getFee($credential, $total_amount);
        if($conv_fee['ERROR']==1){
            return $conv_fee['ERRORCODE'];
        }

        if(isset($autopayInfo['convfee'])){ //using by Api
            $conv_fee['CFEE'] = $autopayInfo['convfee'];
        }

        DB::table('recurring_trans_categories')->where('trans_id',$autopayInfo['trans_id'])->delete();

        for ($i=0;$i<count($autopayInfo['categories']);$i++){
            if($autopayInfo['categories'][$i]['amount']>0.00){
                DB::table('recurring_trans_categories')->insert([
                    'trans_id'=>$autopayInfo['trans_id'],'id_properties'=>$id_property,
                    'id_companies'=>$id_companies,'id_partners'=>$id_partners,
                    'amount'=>$autopayInfo['categories'][$i]['amount'],'category_name'=>$autopayInfo['categories'][$i]['name'],
                    'category_id'=>$autopayInfo['categories'][$i]['id'],'web_user_id'=>$web_user_id
                ]);
            }

        }

        $descr=$this->getPaymentDescr($autopayInfo['categories'], $conv_fee['CFEE'],'','');

        DB::table('accounting_recurring_transactions')->where('trans_id',$autopayInfo['trans_id'])->update([
            'trans_recurring_net_amount'=>$total_amount,
            'trans_descr'=>$descr,
            'trans_recurring_convenience_fee'=>$conv_fee['CFEE']
        ]);

        return 'Amount successfully changed';

    }

    function getFee($credentials,$amount){
        if(count($credentials)<1){
            $result['ERROR']=1;
            $result['ERRORCODE']='Sorry! You are unable to make this type of payment. Please contact your Payment Provider for assistance.';
            return $result;
        }
        $tier=array();
        $result=array();
        $convenience_fee=0;
        //velocities
        $min=$credentials[0]['low_pay_range'];
        $max=$credentials[0]['high_pay_range'];


        $tier_applied=0;

        if($amount>=$min && $amount<=$max){
            $tier['min']=$credentials[0]['low_pay_range'];
            $tier['max']=$credentials[0]['high_pay_range'];
            $tier['ht']=$credentials[0]['high_ticket'];
            $tier['cfee']=$credentials[0]['convenience_fee'];
            $tier['cffee']=$credentials[0]['convenience_fee_float'];
            $tier_applied=0;
        }

        for ($i=1;$i<count($credentials);$i++){
            if($min>$credentials[$i]['low_pay_range'])$min=$credentials[$i]['low_pay_range'];
            if($max<$credentials[$i]['high_pay_range'])$max=$credentials[$i]['high_pay_range'];

            if($amount>=$credentials[$i]['low_pay_range'] && $amount<=$credentials[$i]['high_pay_range']){
                $tier['min']=$credentials[$i]['low_pay_range'];
                $tier['max']=$credentials[$i]['high_pay_range'];
                $tier['ht']=$credentials[$i]['high_ticket'];
                $tier['cfee']=$credentials[$i]['convenience_fee'];
                $tier['cffee']=$credentials[$i]['convenience_fee_float'];
                $tier_applied=$i;
            }

        }

        if($amount<$min*1){// error lower_range
            $result['ERROR']=1;
            $result['ERRORCODE']='The minimum payment amount is '.  number_format($min,2,".",",");
            return $result;
        }

        if($amount>$max*1){//error high_range
            $result['ERROR']=1;
            $result['ERRORCODE']='The maximum payment amount is '.  number_format($max,2,".",",");
            return $result;
        }

        $convenience_fee=$tier['cfee'];
        $convenience_fee+=$tier['cffee']*$amount/100;

        if($amount+$convenience_fee>$tier['ht']*1){// error high ticket
            $result['ERROR']=1;
            $result['ERRORCODE']='The maximum payment amount is '.  number_format($tier['ht'],2,".",",");
            return $result;
        }

        $result['ERROR']=0;
        $result['CFEE']= number_format($convenience_fee,2);
        $result['TIER_APPLIED']=$tier_applied;

        return $result;
    }

    function getAutopayInfoByTrans_id($trans_id,$property_id,$trans_web_user_id){
        $result= DB::table('accounting_recurring_transactions')->select('trans_last_post_date','dynamic','trans_status','trans_payment_type','trans_card_type','trans_next_post_date','trans_schedule','trans_numleft','trans_recurring_net_amount','trans_recurring_convenience_fee')->where('trans_id',$trans_id)->where('property_id',$property_id)->where('trans_web_user_id',$trans_web_user_id)->first();
        return $result;
    }

    function getNumleft($freq,$startdate,$enddate){
        if($enddate==-1){
            return 9999;
        }
        $start= strtotime($startdate);
        $end= strtotime($enddate);
        $numleft=1;

        switch($freq){
            case 'weekly':
                while ($end>$start){
                    if($end>=strtotime('+1 week',  $start)){
                        $numleft++;
                    }
                    $start=strtotime('+1 week',  $start);
                }
                break;
            case 'annually':
            case 'yearly':
                while ($end>$start){
                    if($end>=strtotime('+1 year',  $start)){
                        $numleft++;
                    }
                    $start=strtotime('+1 year',  $start);
                }
                break;
            case 'biannually':
                while ($end>$start){
                    if($end>=strtotime('+6 months',  $start)){
                        $numleft++;
                    }
                    $start=strtotime('+6 months',  $start);
                }
                break;
            case 'quaterly':
            case 'quarterly':
                while ($end>$start){
                    if($end>=strtotime('+3 months',  $start)){
                        $numleft++;
                    }
                    $start=strtotime('+3 months',  $start);
                }
                break;
            case 'biweekly':
                while ($end>$start){
                    if($end>=strtotime('+14 days',  $start)){
                        $numleft++;
                    }
                    $start=strtotime('+14 days',  $start);
                }
                break;
            case 'monthly':
                while ($end>$start){
                    if($end>=strtotime('+1 months',  $start)){
                        $numleft++;
                    }
                    $start=strtotime('+1 months',  $start);
                }
                break;
            default :
                $numleft=0;
                break;
        }

        return $numleft;
    }

    function updateAutopayFreq($autopayInfo){
        DB::table('accounting_recurring_transactions')->where('trans_id',$autopayInfo['trans_id'])->update([
            'trans_next_post_date'=>$autopayInfo['next_day'],'trans_numleft'=>$autopayInfo['trans_numleft'],
            'trans_schedule'=>$autopayInfo['freq'],'trans_status'=>1
        ]);
    }

    function get1recurringInfo($trans_id,$key){
        $info= DB::table('accounting_recurring_transactions')->select($key)->where('trans_id',$trans_id)->first();
        return $info[$key];

    }

    function updateRTxByUser($web_user_id,$fields,$dyn=false){
        $dynamic = 0;
        if($dyn) {$dynamic = 1;}

        //asking if trans_recurring_convenience_fee is comming inside of var $fields
        if(!isset($fields["trans_recurring_convenience_fee"])){
            $objWebUser = new WebUsers();
            $prop_id= $objWebUser->get1UserInfo($web_user_id, 'property_id');

            //ec
            //calculate conv_fee for ec transactions -> return 0 if doesn't have none
            $fields["trans_recurring_convenience_fee"] = $this->getREC_ConvFee($fields['trans_recurring_net_amount'],$prop_id,"ec",$dynamic);
            //validate if user has at least one ec-recurring_payment
            $rec_ec= DB::table('accounting_recurring_transactions')->where('property_id',$prop_id)->where('trans_web_user_id',$web_user_id)->where('trans_payment_type','ec')->where('trans_status','=',1)->where('dynamic',$dynamic)->first();
            //update all ec-recurring_payment
            if(!empty($rec_ec)){
                if(isset($fields['trans_recurring_net_amount'])){
                    $saveB=$fields['trans_recurring_net_amount'];
                    $fields['trans_recurring_net_amount']=$this->verifyRECAmount($fields['trans_recurring_net_amount'],$prop_id,"ec");
                }
                $result = DB::table('accounting_recurring_transactions')->where('property_id',$prop_id)->where('trans_web_user_id',$web_user_id)->where('trans_payment_type','ec')->where('dynamic',$dynamic)->where('trans_status','=',1)->update($fields);
                if(isset($saveB)){
                    $fields['trans_recurring_net_amount']=$saveB;
                }
            }

            //cc
            //calculate conv_fee for cc transactions -> return 0 if doesn't have none
            $fields["trans_recurring_convenience_fee"] = $this->getREC_ConvFee($fields['trans_recurring_net_amount'],$prop_id,"cc",$dynamic);
            //validate if user has at least one cc-recurring_payment
            $rec_cc= DB::table('accounting_recurring_transactions')->where('property_id',$prop_id)->where('trans_web_user_id',$web_user_id)->where('trans_payment_type','cc')->where('trans_status','=',1)->where('dynamic',$dynamic)->first();
            //update all cc-recurring_payment
            if(!empty($rec_cc)){
                if(isset($fields['trans_recurring_net_amount'])){
                    $saveB=$fields['trans_recurring_net_amount'];
                    $fields['trans_recurring_net_amount']=$this->verifyRECAmount($fields['trans_recurring_net_amount'],$prop_id,"cc");
                }
                $result = DB::table('accounting_recurring_transactions')->where('property_id',$prop_id)->where('trans_web_user_id',$web_user_id)->where('trans_payment_type','cc')->where('dynamic',$dynamic)->where('trans_status','=',1)->update($fields);
                if(isset($saveB)){
                    $fields['trans_recurring_net_amount']=$saveB;
                }
            }

            //amex
            //calculate conv_fee for amex transactions -> return 0 if doesn't have none
            $fields["trans_recurring_convenience_fee"] = $this->getREC_ConvFee($fields['trans_recurring_net_amount'],$prop_id,"amex",$dynamic);
            //validate if user has at least one amex-recurring_payment
            $rec_amex= DB::table('accounting_recurring_transactions')->where('property_id',$prop_id)->where('trans_web_user_id',$web_user_id)->where('trans_payment_type','amex')->where('trans_status','=',1)->where('dynamic',$dynamic)->first();
            //update all amex-recurring_payment
            if(!empty($rec_amex)){
                if(isset($fields['trans_recurring_net_amount'])){
                    $saveB=$fields['trans_recurring_net_amount'];
                    $fields['trans_recurring_net_amount']=$this->verifyRECAmount($fields['trans_recurring_net_amount'],$prop_id,"amex");
                }
                $result = DB::table('accounting_recurring_transactions')->where('property_id',$prop_id)->where('trans_web_user_id',$web_user_id)->where('trans_payment_type','amex')->where('dynamic',$dynamic)->where('trans_status','=',1)->update($fields);
                if(isset($saveB)){
                    $fields['trans_recurring_net_amount']=$saveB;
                }
            }
        }else{ // $field has trans_recurring_convenience_fee
            //update all-recurring_payment (amex,cc,ec) with a fix conveniece_fee
            $result=DB::table('accounting_recurring_transactions')->where('property_id',$prop_id)->where('trans_web_user_id',$web_user_id)->where('dynamic',$dynamic)->where('trans_status','=',1)->update($fields);
        }

        //return $result;
    }

    function getREC_ConvFee($net_amount, $prop_id, $type,$dynamic=0){
        //get convenience fee
        $convFee = DB::table('merchant_account')
            ->where('property_id',$prop_id)
            ->where('payment_method',$type)
            ->where('is_recurring',1)
            ->where('low_pay_range','<=',$net_amount)
            ->where('high_pay_range','>=',$net_amount)
            ->select('convenience_fee_float_drp','convenience_fee_drp','convenience_fee_float','convenience_fee')
            ->first();

        if(empty($convFee)) {return 0;}

        if($dynamic){ //calulate DRP convenience_fee
            return (($convFee['convenience_fee_float_drp']/100) * $net_amount) + $convFee['convenience_fee_drp'];
        }else{ //calulate convenience_fee
            return (($convFee['convenience_fee_float']/100) * $net_amount) + $convFee['convenience_fee'];
        }

    }

    function cancelRTxByUser($web_user_id){
        $result=DB::table('accounting_recurring_transactions')->where('trans_web_user_id','=',$web_user_id)->where('trans_status','=',1)->update(array('trans_status'=>4));
    }
    
}
