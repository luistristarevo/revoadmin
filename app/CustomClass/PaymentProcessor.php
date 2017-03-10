<?php
namespace App\CustomClass;

require_once __DIR__.'/Gateways/bokf.class.php';
require_once __DIR__.'/Gateways/pstars.class.php';
require_once __DIR__.'/Gateways/fd4.class.php';
require_once __DIR__.'/Gateways/fd4DirectPost.php';
require_once __DIR__.'/Gateways/nmi.class.php';
require_once __DIR__.'/Gateways/nmiDirectPost.class.php';
require_once __DIR__.'/Gateways/nmiCustomerVault.class.php';
require_once __DIR__.'/Gateways/vantiv.class.php';
require_once __DIR__.'/Gateways/litle.class.php';
require_once __DIR__.'/Gateways/express.class.php';

use Illuminate\Support\Facades\DB;
use \App\Models\Transations;
use App\User;

class PaymentProcessor {
    
    public function RunSwipe($paymentInfo,$credential){
        switch ($credential['gateway']){
            case 'express': 
                $obj_express = new Express();
                
                if(isset($paymentInfo['MagneprintData']) && !empty($paymentInfo['MagneprintData'])){
                    $obj_express->setMagneprintData($paymentInfo['MagneprintData']);
                }else if(isset($paymentInfo['EncryptedCardData']) && !empty($paymentInfo['EncryptedCardData'])){
                    $obj_express->setEncryptedCardData($paymentInfo['EncryptedCardData']);
                }else if(isset($paymentInfo['EncryptedFormat']) && !empty($paymentInfo['EncryptedFormat'])){
                    $obj_express->setEncryptedFormat($paymentInfo['EncryptedFormat']);
                    if(isset($paymentInfo['Track1Data']) && !empty($paymentInfo['Track1Data'])){
                        $obj_express->setEncryptedTrack1Data($paymentInfo['Track1Data']);
                    }else if(isset($paymentInfo['Track2Data']) && !empty($paymentInfo['Track2Data'])){
                        $obj_express->setEncryptedTrack2Data($paymentInfo['Track2Data']);
                    }
                }else{
                    if(isset($paymentInfo['Track1Data']) && !empty($paymentInfo['Track1Data'])){
                        $obj_express->setTrack1Data($paymentInfo['Track1Data']);
                    }else if(isset($paymentInfo['Track2Data']) && !empty($paymentInfo['Track2Data'])){
                        $obj_express->setTrack2Data($paymentInfo['Track2Data']);
                    }
                }
                
                if(isset($paymentInfo['CardDataKeySerialNumber']) && !empty($paymentInfo['CardDataKeySerialNumber'])){
                    $obj_express->setCardDataKeySerialNumber($paymentInfo['CardDataKeySerialNumber']);
                }
                
                $obj_express->setTransactionAmount($paymentInfo['total_amount']);
                $obj_express->setConvenienceFeeAmount($paymentInfo['fee']);
                $obj_express->setReferenceNumber($paymentInfo['trans_id']);
                $result = $obj_express->RunSwipe();
                break;
            case 'nmi':
                $source_key=explode(":", $credential['key']);
                if(count($source_key)!=2){
                    $result['response']=195;
                    $result['responsetext']="Invalid Credential, Please contact your payment provider  ";
                    break;
                }
                $nuser=$source_key[0];
                $npasw=$source_key[1];
                //list($nuser,$npasw)= explode(":", $credential['key']);
                $option_nmi= array( 'nmi_user'=>$nuser,'nmi_password'=>$npasw);
                $obj_nmi= new nmiDirectPost($option_nmi);
                
                if(isset($paymentInfo['Track1Data']) || isset($paymentInfo['Track2Data']) || isset($paymentInfo['Track3Data'])){
                    if(isset($paymentInfo['EncryptedFormat']) && $paymentInfo['EncryptedFormat'] == 1){
                       //MagTek
                       if(isset($paymentInfo['Track1Data']) && !empty($paymentInfo['Track1Data'])){
                           $obj_nmi->magnesafe_track_1($paymentInfo['Track1Data']);
                       }
                       if(isset($paymentInfo['Track2Data']) && !empty($paymentInfo['Track2Data'])){
                           $obj_nmi->magnesafe_track_2($paymentInfo['Track2Data']);
                       }
            
                    }else if(isset($paymentInfo['EncryptedFormat']) && $paymentInfo['EncryptedFormat'] == 4){
                       //IDTech M130
                       if(isset($paymentInfo['Track1Data']) && !empty($paymentInfo['Track1Data'])){
                           $obj_nmi->setencrypted_track_1($paymentInfo['Track1Data']);
                       }
                       if(isset($paymentInfo['Track2Data']) && !empty($paymentInfo['Track2Data'])){
                           $obj_nmi->setencrypted_track_2($paymentInfo['Track2Data']);
                       }
                       if(isset($paymentInfo['Track3Data']) && !empty($paymentInfo['Track3Data'])){
                           $obj_nmi->setencrypted_track_3($paymentInfo['Track3Data']);
                       }
                    }else if(isset($paymentInfo['EncryptedFormat']) && $paymentInfo['EncryptedFormat'] > 0){
                       //Others Format
                        $result['response']=997;
                        $result['responsetext']="Sorry! Cannot process EncryptedFormat. System only allow Support Magtek and ID Tech";
                        break;
                    }else{
                       //Unencrypted Retail Magnetic Stripe Data
                       if(isset($paymentInfo['Track1Data']) && !empty($paymentInfo['Track1Data'])){
                           $obj_nmi->setTrack_1($paymentInfo['Track1Data']);
                       }
                       if(isset($paymentInfo['Track2Data']) && !empty($paymentInfo['Track2Data'])){
                           $obj_nmi->setTrack_2($paymentInfo['Track2Data']);
                       }
                       if(isset($paymentInfo['Track3Data']) && !empty($paymentInfo['Track3Data'])){
                           $obj_nmi->setTrack_3($paymentInfo['Track3Data']);
                       }
                    }
                    if(isset($paymentInfo['CardDataKeySerialNumber']) && !empty($paymentInfo['CardDataKeySerialNumber'])){
                        $obj_nmi->setmagnesafe_ksn($paymentInfo['CardDataKeySerialNumber']);
                    }
                }else if(isset($paymentInfo['MagneprintData']) && !empty ($paymentInfo['MagneprintData'])){
                    $obj_nmi->setmagnesafe_magneprint($paymentInfo['MagneprintData']);
                }else if(isset($paymentInfo['EncryptedCardData']) && !empty ($paymentInfo['EncryptedCardData'])){
                    $obj_nmi->setencrypted_data($paymentInfo['EncryptedCardData']);
                }
                
                $obj_nmi->setAmount($paymentInfo['total_amount']);
                $obj_nmi->setOrderDescription($paymentInfo['memo']);
                $obj_nmi->setOrderId($paymentInfo['trans_id']);
                $obj_nmi->sale();
                $result=$obj_nmi->execute();
                break;
            default : 
                $result['response']=632;
                $result['responsetext']="Invalid Gateway";
            break;
                
        }
        $result['txid']=$paymentInfo['trans_id'];
        $result['auto']=0;
        return $result;
    }
       
    public function runToken($paymentInfo,$credential){
        switch ($credential['gateway']){
            case 'express': 
                $obj_express = new Express();
                if(!isset($credential['isFee'])){
                    if(!isset($credential['mid']) || empty($credential['mid'])){
                        return array('response'=>3,'responsetext'=>'Invalid MID');
                    }
                    $obj_express->setAcceptorID($credential['mid']);
                }
                if($credential['payment_method']=="ec"){
                    //no convenience fee, need to make to payment 1-Amount and 2- Convenience Fee
                    $obj_express->setTransactionAmount($paymentInfo['total_amount']);
                    $obj_express->setReferenceNumber($paymentInfo['trans_id']);
                    $obj_express->setBillingName($paymentInfo['token']['ec_account_holder']);
                    $obj_express->setDDAAccountType($paymentInfo['token']['ec_checking_savings']);
                    $obj_express->setRoutingNumber($paymentInfo['token']['ec_routing_number']);
                    $obj_express->setAccountNumber($paymentInfo['token']['ec_account_number']);
                    
                    if(isset($paymentInfo['b2b'])){
                        $obj_express->is_eCheckB2B();
                    }
                    
                    if(isset($paymentInfo['ivr'])){
                        $obj_express->is_eCheckIvr();
                    }
                    
                    $result= $obj_express->eCheckSale();
                    
                }else{
                    $obj_express->setPaymentAccountID($paymentInfo['token']['vid']);
                    $obj_express->setTransactionAmount($paymentInfo['total_amount']);
                    $obj_express->setReferenceNumber($paymentInfo['trans_id']);
                    $obj_express->setConvenienceFeeAmount($paymentInfo['fee']);
                    //$obj_express->setBillingName($paymentInfo['ccname']);
                    //$obj_express->setBillingZipcode($paymentInfo['zip']);
                    
                    //$obj_express->setCVVPresenceCode(1); //CVV Not Provided
                    $result = $obj_express->RunToken();
                }
                break;
            case 'bokf':
                if(!$this->validateAccountNumber($paymentInfo['token']['ec_account_number'])){
                    return array('response'=>3,'responsetext'=>'Invalid Account Number');
                }
                if(!$this->validateRouting($paymentInfo['token']['ec_routing_number'])){
                    return array('response'=>3,'responsetext'=>'Invalid Routing Number');
                }
                $obj_bokf= new \App\CustomClass\bokf();
                $obj_bokf->setAccountNumber($paymentInfo['token']['ec_account_number']);
                $obj_bokf->setRoutingNumber($paymentInfo['token']['ec_routing_number']);
                $obj_bokf->setOrderId($paymentInfo['trans_id']);
                $result=$obj_bokf->auth();
                break;
            case 'profistars':
                $options=array();
                if(isset($credential['mid'])) $options['gw_mid']=$credential['mid'];
                if(isset($credential['lid'])) $options['gw_lid']=$credential['lid'];
                if(isset($credential['sid'])) $options['gw_sid']=$credential['sid'];
                if(isset($credential['key'])) $options['gw_key']=$credential['key'];
                if(!$this->validateAccountNumber($paymentInfo['token']['ec_account_number'])){
                    return array('response'=>3,'responsetext'=>'Invalid Account Number');
                }
                if(!$this->validateRouting($paymentInfo['token']['ec_routing_number'])){
                    return array('response'=>3,'responsetext'=>'Invalid Routing Number');
                }
                $obj_pstars=new \App\CustomClass\pstars($options);
                $obj_pstars->setAccountNumber($paymentInfo['token']['ec_account_number']);
                $obj_pstars->setRoutingNumber($paymentInfo['token']['ec_routing_number']);
                $obj_pstars->setMemo($paymentInfo['memo']);
                $obj_pstars->setOrderId($paymentInfo['trans_id']);
                $obj_pstars->setAmount($paymentInfo['total_amount']);
                if(isset($paymentInfo['ppd'])){
                    $obj_pstars->setPPD();
                }
                if(isset($paymentInfo['b2b'])){
                    $obj_pstars->setPPD();
                    $obj_pstars->setB2B();
                }
                if(isset($paymentInfo['ivr'])){
                    $obj_pstars->setIVR();
                }
                if(substr($paymentInfo['token']['ec_checking_savings'],0,1)=='S'){
                    $obj_pstars->setAccountTypeSavings();
                }
                $obj_pstars->setName($paymentInfo['token']['ec_account_holder']);
                $result=$obj_pstars->Sale();
                
                break;
            case 'litle':    
            //put the credentias to litle components
                $obj_litle = new litle();
                if($credential['payment_method']=="ec"){
                    $obj_litle->setAmount($paymentInfo['total_amount']+$paymentInfo['fee']);
                    $obj_litle->setId($paymentInfo['trans_id']);
                    $obj_litle->setOrderId($paymentInfo['trans_id']);
                    $obj_litle->setOrderSource("ecommerce");
                    
                    $obj_litle->setName($paymentInfo['token']['ec_account_holder']);
                    $obj_litle->setAccType($paymentInfo['token']['ec_checking_savings']);
                    $obj_litle->setRoutingNum($paymentInfo['token']['ec_routing_number']);
                    $obj_litle->setAccNum($paymentInfo['token']['ec_account_number']);
                    
                    if(isset($paymentInfo['ppd'])){
                        $obj_litle->setOrderSource_echeckppd();
                    }
                    if(isset($paymentInfo['b2b'])){
                        $obj_litle->setOrderSource_echeckppd();
                        $obj_litle->setB2B();
                    }
                    if(isset($paymentInfo['ivr'])){
                        $obj_litle->setOrderSource_telephone();
                    }
                    
                    $result= $obj_litle->echeckSale();
                    
                }else{
                    $obj_litle->setAmount($paymentInfo['total_amount']+$paymentInfo['fee']);
                    $obj_litle->setId($paymentInfo['trans_id']);
                    $obj_litle->setOrderId($paymentInfo['trans_id']);
                    $obj_litle->setOrderSource("ecommerce");
                    
                    $obj_litle->setName($paymentInfo['ccname']);
                    $obj_litle->setZip($paymentInfo['zip']);
                    
                    $obj_litle->setLitleToken($paymentInfo['token']['vid']);
                    $obj_litle->setExpDate($paymentInfo['token']['exp_date']);
                    if(isset($paymentInfo['cvv'])){$obj_litle->setCardValidationNum($paymentInfo['cvv']);}
                    $obj_litle->setType(strtoupper(substr($paymentInfo['token']['cc_type'], 0,2)));
                    $result = $obj_litle->runToken();
                    
                }
                break;     
            case 'vantiv':
                $options=array();
                if((!isset($credential['key']) || empty($credential['key'])) && isset($credential['sid']) && isset($credential['mid'])){
                    $options=array('gw_mid'=>$credential['mid'],'gw_sid'=>$credential['sid']);
                }
                else {
                    if(isset($credential['key'])){$options=array('gw_key'=>$credential['key']);}
                }
                if(isset($credential['isFee'])){
                    $options['isFee']=true;
                }
                $obj_vantiv=new vantiv($options);
                if(!isset($credential['isFee'])){
                    if(empty($credential['key'])){
                    //save key to credentials
                    DB::table('merchant_account')->where('property_id','=',$paymentInfo['id_property'])->where('gateway','=','vantiv')->where('payment_source_key','=','')->update(array('payment_source_key'=>$obj_vantiv->getProfileKey()));
                    }
                }
                $obj_vantiv->setAmount($paymentInfo['total_amount']-$paymentInfo['fee']);
                $obj_vantiv->setFeeAmount($paymentInfo['fee']);
                $obj_vantiv->setMemo($paymentInfo['memo']);
                //$obj_vantiv->setName($paymentInfo['ccname']);
                //$obj_vantiv->setZipCode($paymentInfo['zip']);
                $obj_vantiv->setOrderId($paymentInfo['trans_id']);
                $obj_vantiv->setToken($paymentInfo['token']['vid']);
                $obj_vantiv->setCardType($paymentInfo['token']['cc_type']);
                if(isset($paymentInfo['token']['exp_date'])){
                    $obj_vantiv->setExpDate($paymentInfo['token']['exp_date']);
                }
                $result=$obj_vantiv->AuthToken();
                break;
            case 'nmi':
                list($nusr,$npsw)= explode(":", $credential['key']);
                $option_nmi= array( 'nmi_user'=>$nusr,'nmi_password'=>$npsw);
                $obj_nmi_vault= new nmiCustomerVault($option_nmi);
                $obj_nmi_vault->setCustomerVaultId($paymentInfo['token']['vid']);
                $obj_nmi_vault->setAmount($paymentInfo['total_amount']);
                $obj_nmi_vault->setOrderDescription($paymentInfo['memo']);
                $obj_nmi_vault->setOrderId($paymentInfo['trans_id']);
                $result=$obj_nmi_vault->execute();
                $result['reference']=$result['transactionid'];
                break;
            case 'fd4':
            case 'fde4':
                if(strpos($credential['key'], ':|:')>0){
                    list($gid,$pid)=explode(':|:',$credential['key']);
                }
                else {
                    $gid=$credential['sid'];
                    $pid=$credential['key'];
                }
                $options=array('fd4_user'=>$gid,'fd4_password'=>$pid);
                $obj_fd4=new fd4DirectPost($options);
                $obj_fd4->setOrderId($paymentInfo['trans_id']);
                if(isset($paymentInfo['token']['exp_date'])){
                    $obj_fd4->setExpireDate($paymentInfo['token']['exp_date']);
                }
                //if exist setName
                if (isset($paymentInfo['token']['ch_name'])) {
                    $obj_fd4->setName($paymentInfo['token']['ch_name']);
                } else {
                    //if not  setName with User Name
                    if (isset($paymentInfo['web_user_id'])) {
                        $obj_user = new User();
                        $name = $obj_user->getFullNameById($paymentInfo['web_user_id']);
                        $obj_fd4->setName($name);
                    } else {
                        $obj_fd4->setName("Card Holder Name");
                    }
                }
                $obj_fd4->setToken($paymentInfo['token']['vid']);
                $obj_fd4->setAmount($paymentInfo['total_amount']);
                $obj_fd4->setCredit_card_type($paymentInfo['token']['cc_type']);
                $result=$obj_fd4->AuthToken();
                break;
            case 'trans1':
                break;
            case 'ppal':
                break;
            default : 
                break;
                
        }
        if($result['response']==1){// transactions approved
            $obj_trans= new Transations();
            $obj_trans->addApprovedTransEvent($paymentInfo['trans_id'], $paymentInfo['id_property'], $credential['gateway']);
        }
        
        $result['txid']=$paymentInfo['trans_id'];
        $result['auto']=0;
        return $result;
    }
    
    public function getToken($paymentInfo,$credential){
        switch ($credential['gateway']){
            case 'express':
                //put the credentials to express components
                $obj_express = new express();
                if(!isset($credential['mid']) || empty($credential['mid'])){
                    return array('response'=>3,'responsetext'=>'Invalid MID');
                }
                $obj_express->setAcceptorID($credential['mid']);
                if($credential['payment_method']=="ec"){
                    
                }else{
                    $obj_express->setReferenceNumber(time());
                    $obj_express->setCardholderName($paymentInfo['ccnumber']);
                    $obj_express->setPaymentAccountReferenceNumber(time());
                    $obj_express->setCardNumber($paymentInfo['ccnumber']);
                    $obj_express->setExpirationDate($paymentInfo['ccexp']);
                    if(isset($paymentInfo['cvv']) && !empty($paymentInfo['cvv'])){
                        $obj_express->setCvv($paymentInfo['cvv']);
                    }
                    $obj_express->setBillingName($paymentInfo['ccname']);
                    $obj_express->setBillingZipcode($paymentInfo['zip']);
                    $result = $obj_express->GetToken();
                }
                break;
            case 'litle': 
                //put the credentias to litle components
                $obj_litle = new litle();
                if($credential['payment_method']=="ec"){
            
                    
                }else{
                    $time = time();
                    $obj_litle->setId($time);
                    $obj_litle->setOrderId($time);
                    $obj_litle->setAccountNumber($paymentInfo['ccnumber']);
                    if(isset($paymentInfo['cvv']) && !empty($paymentInfo['cvv'])){
                        $obj_litle->setCardValidationNum($paymentInfo['cvv']);
                    }
                    $result=$obj_litle->verify();
                    
                }
                break;  
            case 'vantiv':
                if(empty($credential['key'])){
                    $options=array('gw_mid'=>$credential['mid'],'gw_sid'=>$credential['sid']);
                }
                else {
                    $options=array('gw_key'=>$credential['key']);
                }
                $obj_vantiv=new vantiv($options);
                if(empty($credential['key'])){
                    //save key to credentials
                    DB::table('merchant_account')->where('property_id','=',$paymentInfo['id_property'])->where('gateway','=','vantiv')->where('payment_source_key','=','')->update(array('payment_source_key'=>$obj_vantiv->getProfileKey()));
                }

                $obj_vantiv->setAmount(1);
                $obj_vantiv->setFeeAmount(0);
                //$obj_vantiv->setOrderId($paymentInfo['trans_id']);
                if(isset($paymentInfo['cvv']) && !empty($paymentInfo['cvv'])){
                    $obj_vantiv->setCVV($paymentInfo['cvv']);
                }
                $obj_vantiv->setCardType($paymentInfo['cctype']);
                $obj_vantiv->setExpDate($paymentInfo['ccexp']);
                $obj_vantiv->setCardNumber($paymentInfo['ccnumber']);
                $obj_vantiv->setName($paymentInfo['ccname']);
                $obj_vantiv->setZipCode($paymentInfo['zip']);
                $result=$obj_vantiv->Verify();
                break;
            case 'nmi':
                $source_key=explode(":", $credential['key']);
                if(count($source_key)!=2){
                    $result['response']=195;
                    $result['responsetext']="Invalid Credential, Please contact your payment provider  ";
                    break;
                }
                $nuser=$source_key[0];
                $npasw=$source_key[1];
              //  list($nuser,$npasw)= explode(":", $credential['key']);
                $option_nmi= array( 'nmi_user'=>$nuser,'nmi_password'=>$npasw);
                $obj_nmi_vault= new nmiCustomerVault($option_nmi);
                //optional field, by default allways create a new one
                $obj_nmi_vault->setAccountName($paymentInfo['ccname']);
                $names=explode(' ',$paymentInfo['ccname']);
                if(count($names)<2){
                    $names[1]='NA';
                }
                $obj_nmi_vault->setFirstName($names[0]);
                $obj_nmi_vault->setLastName($names[1]);
                $obj_nmi_vault->add();
                $obj_nmi_vault->setAddress1('9201 Abbot Kinney');
                $obj_nmi_vault->setState('CA');
                $obj_nmi_vault->setCcNumber($paymentInfo['ccnumber']);
                $obj_nmi_vault->setCcExp($paymentInfo['ccexp']);
                if(isset($paymentInfo['cvv']) && !empty($paymentInfo['cvv'])){
                    $obj_nmi_vault->setCvv($paymentInfo['cvv']);
                }
                $obj_nmi_vault->setZip($paymentInfo['zip']);
                $result=$obj_nmi_vault->execute();
                if($result['response']==1){
                    $result['token']=$result['customer_vault_id'];
                } 
                break;
            case 'fd4':
            case 'fde4':
                 if(strpos($credential['key'], ':|:')>0){
                    list($gid,$pid)=explode(':|:',$credential['key']);
                }
                else {
                    $gid=$credential['sid'];
                    $pid=$credential['key'];
                }
                $options=array('fd4_user'=>$gid,'fd4_password'=>$pid);
                $obj_fd4=new fd4DirectPost($options);
                //$obj_fd4->setOrderId($paymentInfo['trans_id']);
                $obj_fd4->setExpireDate($paymentInfo['ccexp']);
                $obj_fd4->setCredit_card_type($paymentInfo['cctype']);
                $obj_fd4->setCcNumber($paymentInfo['ccnumber']);
                if(isset($paymentInfo['cvv']) && !empty($paymentInfo['cvv'])){
                    $obj_fd4->setCvv2($paymentInfo['cvv']);
                }
                $obj_fd4->setName($paymentInfo['ccname']);
                $obj_fd4->setZip($paymentInfo['zip']);
                $result=$obj_fd4->Verify();
                break;
            case 'bokf':
            case 'trans1':
            case 'ppal':
            case 'profistars':
                $result= '';
                break;
                
        }
        return $result;
    }
    
    public function void($transInfo,$credential){
        switch ($credential['gateway']){
            case 'express':
                $obj_express = new Express();
                if(!isset($credential['mid']) || empty($credential['mid'])){
                    return array('response'=>3,'responsetext'=>'Invalid MID');
                }
                $obj_express->setAcceptorID($credential['mid']);
                if($credential['payment_method']=="ec"){
                    $obj_express->setTransactionID($transInfo['trans_result_refnum']); 
                    $obj_express->setReferenceNumber($transInfo['trans_id']);
                    $result = $obj_express->eCheckVoid();
                }else{
                    $obj_express->setTransactionID($transInfo['trans_result_refnum']); 
                    $result = $obj_express->Void();
                }
                break;
            case 'bokf':
                $result['response']=1;
                $result['responsetext']="Transaction Voided Successfully";
                break;
            case 'profistars':
                $options=array();
                if(isset($credential['mid'])) $options['gw_mid']=$credential['mid'];
                if(isset($credential['lid'])) $options['gw_lid']=$credential['lid'];
                if(isset($credential['sid'])) $options['gw_sid']=$credential['sid'];
                if(isset($credential['key'])) $options['gw_key']=$credential['key'];
            
                $obj_pstars=new \App\CustomClass\pstars($options);
                $result = $obj_pstars->Void($transInfo['trans_result_refnum']);
                break;
            case 'vantiv':
                if(empty($credential['key'])){
                    $options=array('gw_mid'=>$credential['mid'],'gw_sid'=>$credential['sid']);
                }
                else {
                    $options=array('gw_key'=>$credential['key']);
                }
                $obj_vantiv=new vantiv($options);
                $result = $obj_vantiv->Void($transInfo['trans_result_refnum']);
                break;
            case 'nmi':
                $opt_expl= explode(":", $credential['key']);
                $option_nmi= array( 'nmi_user'=>$opt_expl[0],'nmi_password'=>$opt_expl[1]);
                $obj_nmi_directPost= new nmiDirectPost($option_nmi);
                $obj_nmi_directPost->void($transInfo['trans_result_refnum']);
                $result = $obj_nmi_directPost->execute();
                break;
            case 'fd4':
            case 'fde4':
            case 'trans1':
            case 'ppal':
            case 'lilte': 
                //put the credentias to litle components
                $obj_litle = new litle();
                if($credential['payment_method']=="ec"){
                    $obj_litle->setLitleTxnId($transInfo['trans_result_refnum']);
                    $obj_litle->setId(time());
                    $result = $obj_litle->echeckVoid();
                }else{
                    $obj_litle->setLitleTxnId($transInfo['trans_result_refnum']);
                    $obj_litle->setId(time());
                    $resutl = $obj_litle->void();
                }
                
                break;
                
        }
        
        return $result;
    }
    
    public function refund($transInfo,$credential){
        switch ($credential['gateway']){
            case 'trans1':
            case 'ppal':
            case 'bokf':
            case 'profistars':
                 $options=array();
                if(isset($credential['mid'])) $options['gw_mid']=$credential['mid'];
                if(isset($credential['lid'])) $options['gw_lid']=$credential['lid'];
                if(isset($credential['sid'])) $options['gw_sid']=$credential['sid'];
                if(isset($credential['key'])) $options['gw_key']=$credential['key'];
                
                $obj_pstars=new \App\CustomClass\pstars($options);
                $result = $obj_pstars->Refund($transInfo['trans_result_refnum']);
                break;
            case 'express':
                $obj_express = new Express();
                if(!isset($credential['mid']) || empty($credential['mid'])){
                    return array('response'=>3,'responsetext'=>'Invalid MID');
                }
                $obj_express->setAcceptorID($credential['mid']);
                if($credential['payment_method']=="ec"){
                    $obj_express->setTransactionAmount($transInfo['trans_net_amount']);
                    $obj_express->setTransactionID($transInfo['trans_result_refnum']); 
                    $obj_express->setReferenceNumber($transInfo['trans_id']);
                    $result = $obj_express->eCheckReturn();
                }else{
                    $obj_express->setTransactionAmount($transInfo['trans_net_amount']);
                    $obj_express->setReferenceNumber($transInfo['trans_id']);
                    $obj_express->setTransactionID($transInfo['trans_result_refnum']); 
                    $result = $obj_express->Refund();
                }
                break;
            case 'vantiv':
                if(empty($credential['key'])){
                    $options=array('gw_mid'=>$credential['mid'],'gw_sid'=>$credential['sid']);
                }
                else {
                    $options=array('gw_key'=>$credential['key']);
                }
                $obj_vantiv=new vantiv($options);
                if(empty($credential['key'])){
                    //save key to credentials
                    DB::table('merchant_account')->where('property_id','=',$paymentInfo['id_property'])->where('gateway','=','vantiv')->where('payment_source_key','=','')->update(array('payment_source_key'=>$obj_vantiv->getProfileKey()));
                }
                
                $result = $obj_vantiv->Refund($transInfo['trans_result_refnum'], $transInfo['trans_net_amount']);
                break;
            case 'nmi':
                $source_key=explode(":", $credential['key']);
                if(count($source_key)!=2){
                    $result['response']=195;
                    $result['responsetext']="Invalid Credential, Please contact your payment provider  ";
                    break;
                }
                $nuser=$source_key[0];
                $npasw=$source_key[1];
                $option_nmi= array( 'nmi_user'=>$nuser,'nmi_password'=>$npasw);
                $obj_nmi_directPost= new nmiDirectPost($option_nmi);
                
                $obj_nmi_directPost->refund($transInfo['trans_result_refnum'], $transInfo['trans_net_amount']);
                $result = $obj_nmi_directPost->execute();
                break;
            case 'fd4':
            case 'fde4':
                    if(strpos($credential['key'], ':|:')>0){
                    list($gid,$pid)=explode(':|:',$credential['key']);
                    }
                    else {
                        $gid=$credential['sid'];
                        $pid=$credential['key'];
                    }
                    $options=array('fd4_user'=>$gid,'fd4_password'=>$pid);
                    $obj_fd4=new fd4DirectPost($options);
                    $result = $obj_fd4->Refund($transInfo['trans_result_refnum']."--".trim($transInfo['trans_result_auth_code']), $transInfo['trans_net_amount']);
                    break;
            case 'litle':
                //put the credentias to litle components
                $obj_litle = new litle();
                if($credential['payment_method']=="ec"){
                    $obj_litle->setLitleTxnId($transInfo['trans_result_refnum']);
                    $obj_litle->setId($transInfo['trans_id']);
                    $result = $obj_litle->echeckCredit();
                }else{
                    $obj_litle->setLitleTxnId($transInfo['trans_result_refnum']);
                    $obj_litle->setId($transInfo['trans_id']);
                    $result = $obj_litle->refund();
                }
                break;
                
        }
        
        return $result;
    }
    
    public function RunTx($paymentInfo,$credential){
        switch ($credential['gateway']){
            case 'express':
                $obj_express = new Express();
                if(!isset($credential['isFee'])){
                    if(!isset($credential['mid']) || empty($credential['mid'])){
                        return array('response'=>3,'responsetext'=>'Invalid MID');
                    }
                    $obj_express->setAcceptorID($credential['mid']);
                }
                if(isset($credential['payment_method']) && $credential['payment_method']=="ec"){
                    //no convenience fee, need to make to payment 1-Amount and 2- Convenience Fee
                    $obj_express->setTransactionAmount($paymentInfo['total_amount']);
                    $obj_express->setReferenceNumber($paymentInfo['trans_id']);
                    $obj_express->setBillingName($paymentInfo['token']['ec_account_holder']);
                    $obj_express->setDDAAccountType($paymentInfo['token']['ec_checking_savings']);
                    $obj_express->setRoutingNumber($paymentInfo['token']['ec_routing_number']);
                    $obj_express->setAccountNumber($paymentInfo['token']['ec_account_number']);
                    
                    if(isset($paymentInfo['b2b'])){
                        $obj_express->is_eCheckB2B();
                    }
                    
                    if(isset($paymentInfo['ivr'])){
                        $obj_express->is_eCheckIvr();
                    }
                    
                    $result= $obj_express->eCheckSale();
                }else{
                    $obj_express->setCardholderName($paymentInfo['ccname']);
                    $obj_express->setCardNumber($paymentInfo['ccnumber']);
                    $obj_express->setExpirationDate($paymentInfo['ccexp']);
                    if(isset($paymentInfo['cvv'])){$obj_express->setCvv($paymentInfo['cvv']);}
                    
                    $obj_express->setReferenceNumber($paymentInfo['trans_id']);
                    
                    $obj_express->setTransactionAmount($paymentInfo['total_amount']);
                    $obj_express->setConvenienceFeeAmount($paymentInfo['fee']);
                    
                    $obj_express->setBillingName($paymentInfo['ccname']);
                    $obj_express->setBillingZipcode($paymentInfo['zip']);
                    $result = $obj_express->Sale();
                }
                break;
            case 'bokf':
            case 'profistars':
                break;
            case 'nmi':
                $source_key=explode(":", $credential['key']);
                if(count($source_key)!=2){
                    $result['response']=195;
                    $result['responsetext']="Invalid Credential, Please contact your payment provider  ";
                    break;
                }
                $nuser=$source_key[0];
                $npasw=$source_key[1];
                //list($nuser,$npasw)= explode(":", $credential['key']);
                $option_nmi= array( 'nmi_user'=>$nuser,'nmi_password'=>$npasw);
                $obj_nmi= new nmiDirectPost($option_nmi);
                
                $obj_nmi->setAmount($paymentInfo['total_amount']);
                $obj_nmi->setAccountName($paymentInfo['ccname']);
                $names=explode(' ',$paymentInfo['ccname']);
                if(count($names)<2){
                    $names[1]='NA';
                }
                $obj_nmi->setFirstName($names[0]);
                $obj_nmi->setLastName($names[1]);
                $obj_nmi->setCcNumber($paymentInfo['ccnumber']);
                $obj_nmi->setCcExp($paymentInfo['ccexp']);
                if(isset($paymentInfo['cvv'])){$obj_nmi->setCvv($paymentInfo['cvv']);}
                $obj_nmi->setZip($paymentInfo['zip']);
                $obj_nmi->setOrderDescription($paymentInfo['memo']);
                $obj_nmi->setOrderId($paymentInfo['trans_id']);
                $obj_nmi->sale();
                $result=$obj_nmi->execute();
                break;
            case 'litle': 
                //put the credentias to litle components
                $obj_litle = new litle();
                if($credential['payment_method']=="ec"){
                    $obj_litle->setAmount($paymentInfo['total_amount']+$paymentInfo['fee']);
                    $obj_litle->setId($paymentInfo['trans_id']);
                    $obj_litle->setOrderId($paymentInfo['trans_id']);
                    $obj_litle->setOrderSource("ecommerce");
                    
                    $obj_litle->setName($paymentInfo['ccname']);
                    $obj_litle->setZip($paymentInfo['zip']);
                }else{
                    $obj_litle->setAmount($paymentInfo['total_amount']+$paymentInfo['fee']);
                    $obj_litle->setId($paymentInfo['trans_id']);
                    $obj_litle->setOrderId($paymentInfo['trans_id']);
                    $obj_litle->setOrderSource("ecommerce");
                    
                    $obj_litle->setName($paymentInfo['ccname']);
                    $obj_litle->setZip($paymentInfo['zip']);
                    
                    $obj_litle->setNumber($paymentInfo['ccnumber']);
                    $obj_litle->setExpDate($paymentInfo['ccexp']);
                    if(isset($paymentInfo['cvv'])){$obj_litle->setCardValidationNum($paymentInfo['cvv']);}
                    $obj_litle->setType(strtoupper(substr($paymentInfo['cctype'], 0,2)));
                    $result=$obj_litle->sale();
                    
                }
                break;    
            case 'vantiv':
                $options=array();
                if(isset($credential['isFee'])){
                    $options['isFee']=true;
                }
                if(empty($credential['key']) && !isset($credential['isFee'])){
                    $options=array('gw_mid'=>$credential['mid'],'gw_sid'=>$credential['sid']);
                }
                else {
                    if(isset($credential['key'])){
                        $options=array('gw_key'=>$credential['key']);
                    }
                }
                
                $obj_vantiv=new vantiv($options);
                if(!isset($credential['isFee'])){
                    if(empty($credential['key'])){
                    //save key to credentials
                    DB::table('merchant_account')->where('property_id','=',$paymentInfo['id_property'])->where('gateway','=','vantiv')->where('payment_source_key','=','')->update(array('payment_source_key'=>$obj_vantiv->getProfileKey()));
                    }
                }

                $obj_vantiv->setAmount($paymentInfo['total_amount']-$paymentInfo['fee']);
                $obj_vantiv->setFeeAmount($paymentInfo['fee']);
                $obj_vantiv->setMemo($paymentInfo['memo']);
                $obj_vantiv->setOrderId($paymentInfo['trans_id']);
                if(isset($paymentInfo['cvv'])){$obj_vantiv->setCVV($paymentInfo['cvv']);}
                $obj_vantiv->setCardType($paymentInfo['cctype']);
                $obj_vantiv->setExpDate($paymentInfo['ccexp']);
                $obj_vantiv->setCardNumber($paymentInfo['ccnumber']);
                $obj_vantiv->setName($paymentInfo['ccname']);
                $obj_vantiv->setZipCode($paymentInfo['zip']);
                $result=$obj_vantiv->Sale();
                break;
            case 'fd4':
            case 'fde4':
                if(strpos($credential['key'], ':|:')>0){
                    list($gid,$pid)=explode(':|:',$credential['key']);
                }
                else {
                    $gid=$credential['sid'];
                    $pid=$credential['key'];
                }
                $options=array('fd4_user'=>$gid,'fd4_password'=>$pid);
                $obj_fd4=new fd4DirectPost($options);
                
                $obj_inv= new \App\Models\Invoices();
                $obj_user= new \App\User();
                
                if(isset($paymentInfo['inv_id'])){
                    $listItems= $obj_inv->getInvoiceItems($paymentInfo['inv_id'],$paymentInfo['id_property']);
                }
                $usrinfo=$obj_user->getUsrInfo($paymentInfo['web_user_id']);
               
                $obj_fd4->setAmount($paymentInfo['total_amount']);
                $obj_fd4->setCcNumber($paymentInfo['ccnumber']);
                $obj_fd4->setExpireDate($paymentInfo['ccexp']);
                $obj_fd4->setName($paymentInfo['ccname']);
                if(isset($paymentInfo['cvv'])){$obj_fd4->setCvv2($paymentInfo['cvv']);}
                $obj_fd4->setAddress($usrinfo['address']);
                $obj_fd4->setCity($usrinfo['city']);
                $obj_fd4->setState($usrinfo['state']);
                $obj_fd4->setZip($paymentInfo['zip']);
                $obj_fd4->setReference_no($paymentInfo['trans_id']);
                $obj_fd4->setOrderId($paymentInfo['trans_id']);
                $obj_fd4->setCredit_card_type($paymentInfo['cctype']);
                if(isset($paymentInfo['inv_id'])){
                    $total_tax=0;
                    for($i=0; $i<count($listItems);$i++){
                        $obj_fd4->setTax_range($listItems[$i]['tax']);
                        $obj_fd4->setDescription($listItems[$i]['description']);
                        $obj_fd4->setDiscount($listItems[$i]['discount']);
                        $obj_fd4->setTotal($listItems[$i]['total']);
                        $obj_fd4->setCode($listItems[$i]['code']);
                        $obj_fd4->setPrice($listItems[$i]['price']);
                        $obj_fd4->setQty($listItems[$i]['qty']);
                        $total_tax=$total_tax+$obj_fd4->getCalculateTax();
                        $obj_fd4->addItem();
                    }
                    $obj_fd4->setTotal_tax_amount($total_tax);
                    $obj_fd4->setShip_to_address($usrinfo['first_name'], $usrinfo['email_address']);
                }
                
                $result=$obj_fd4->Sale();
                break;
            case 'moneysender':
                $options=array();
                if(isset($credential['mid'])) $options['gw_mid']=$credential['mid'];
                if(isset($credential['lid'])) $options['gw_lid']=$credential['lid'];
                if(isset($credential['sid'])) $options['gw_sid']=$credential['sid'];
                if(isset($credential['key'])) $options['gw_key']=$credential['key'];
                if(!$this->validateAccountNumber($paymentInfo['ec_account_number'])){
                    return array('response'=>3,'responsetext'=>'Invalid Account Number');
                }
                if(!$this->validateRouting($paymentInfo['ec_routing_number'])){
                    return array('response'=>3,'responsetext'=>'Invalid Routing Number');
                }
                $obj_pstars=new \App\CustomClass\moneysender($options);
                $obj_pstars->setAccountNumber($paymentInfo['ec_account_number']);
                $obj_pstars->setRoutingNumber($paymentInfo['ec_routing_number']);
                $obj_pstars->setMemo($paymentInfo['memo']);
                $obj_pstars->setOrderId($paymentInfo['trans_unique_id']);
                $obj_pstars->setAmount($paymentInfo['total_amount']);
                if($paymentInfo['ec_checking_savings']=='Savings'){
                    $obj_pstars->setAccountTypeSavings();
                }
                $obj_pstars->setName($paymentInfo['ec_account_holder']);
                if(isset($paymentInfo['state'])){
                    $obj_pstars->setState($paymentInfo['state']);
                }
                if(isset($paymentInfo['email'])){
                    $obj_pstars->setEmail($paymentInfo['email']);
                }
                $result=$obj_pstars->Sale();
                break;
            case 'trans1':
            case 'ppal':
                break;
        }
        $result['txid']=$paymentInfo['trans_id'];
        $result['auto']=0;
        return $result;
    }
    
     public function runCreditTx($paymentInfo,$credential){
        $result=array();    
        switch ($credential['gateway']){
            case 'bokf':
                break;
            case 'profistars':
                $options=array();
                if(isset($credential['mid'])) $options['gw_mid']=$credential['mid'];
                if(isset($credential['lid'])) $options['gw_lid']=$credential['lid'];
                if(isset($credential['sid'])) $options['gw_sid']=$credential['sid'];
                if(isset($credential['key'])) $options['gw_key']=$credential['key'];
                if(!$this->validateAccountNumber($paymentInfo['ec_account_number'])){
                    return array('response'=>3,'responsetext'=>'Invalid Account Number');
                }
                if(!$this->validateRouting($paymentInfo['ec_routing_number'])){
                    return array('response'=>3,'responsetext'=>'Invalid Routing Number');
                }
                $obj_pstars=new \App\CustomClass\pstars($options);
                $obj_pstars->setAccountNumber($paymentInfo['ec_account_number']);
                $obj_pstars->setRoutingNumber($paymentInfo['ec_routing_number']);
                $obj_pstars->setMemo($paymentInfo['memo']);
                $obj_pstars->setOrderId($paymentInfo['trans_unique_id']);
                $obj_pstars->setAmount($paymentInfo['total_amount']);
                if($paymentInfo['ec_checking_savings']=='Savings'){
                    $obj_pstars->setAccountTypeSavings();
                }
                $obj_pstars->setName($paymentInfo['ec_account_holder']);
                if(isset($paymentInfo['address'])){
                    $obj_pstars->setAddress($paymentInfo['address']);
                }
                if(isset($paymentInfo['city'])){
                    $obj_pstars->setCity($paymentInfo['city']);
                }
                if(isset($paymentInfo['zip'])){
                    $obj_pstars->setZip($paymentInfo['zip']);
                }
                if(!isset($paymentInfo['address'])){
                    $result=$obj_pstars->Credit();
                }
                else {
                    $result=$obj_pstars->Credit2();
                }
                break;
            case 'vantiv':
                break;
            case 'nmi':
                $opt_expl= explode(":", $credential['key']);
                $option_nmi= array( 'nmi_user'=>$opt_expl[0],'nmi_password'=>$opt_expl[1]);
                $obj_nmi_directPost= new nmiDirectPost($option_nmi);
                $obj_nmi_directPost->setType('credit ');
                $obj_nmi_directPost->setCcNumber($paymentInfo['ccnumber']);
                $obj_nmi_directPost->setCcExp($paymentInfo['ccexp']);
                if(isset($paymentInfo['cvv'])){$obj_nmi_directPost->setCvv($paymentInfo['cvv']);}
                $obj_nmi_directPost->setZip($paymentInfo['zip']);
                $obj_nmi_directPost->setAmount($paymentInfo['amount']);
                $obj_nmi_directPost->setOrderId($paymentInfo['trans_id']);
                return $obj_nmi_vault->execute();
                break;
            case 'fd4':
            case 'fde4':
                break;
            case 'trans1':
                break;
            case 'ppal':
                break;
            case 'moneysender':
                $options=array();
                if(isset($credential['mid'])) $options['gw_mid']=$credential['mid'];
                if(isset($credential['lid'])) $options['gw_lid']=$credential['lid'];
                if(isset($credential['sid'])) $options['gw_sid']=$credential['sid'];
                if(isset($credential['key'])) $options['gw_key']=$credential['key'];
                if(!$this->validateAccountNumber($paymentInfo['ec_account_number'])){
                    return array('response'=>3,'responsetext'=>'Invalid Account Number');
                }
                if(!$this->validateRouting($paymentInfo['ec_routing_number'])){
                    return array('response'=>3,'responsetext'=>'Invalid Routing Number');
                }
                $obj_pstars=new \App\CustomClass\moneysender($options);
                $obj_pstars->setAccountNumber($paymentInfo['ec_account_number']);
                $obj_pstars->setRoutingNumber($paymentInfo['ec_routing_number']);
                $obj_pstars->setMemo($paymentInfo['memo']);
                $obj_pstars->setOrderId($paymentInfo['trans_unique_id']);
                $obj_pstars->setAmount($paymentInfo['total_amount']);
                if($paymentInfo['ec_checking_savings']=='Savings'){
                    $obj_pstars->setAccountTypeSavings();
                }
                $obj_pstars->setName($paymentInfo['ec_account_holder']);
                if(isset($paymentInfo['state'])){
                    $obj_pstars->setState($paymentInfo['state']);
                }
                if(isset($paymentInfo['email'])){
                    $obj_pstars->setEmail($paymentInfo['email']);
                }
                $result=$obj_pstars->Credit();
                break;
            default : 
                break;
                
        }
        $result['txid']=$paymentInfo['trans_id'];
        $result['auto']=0;
        return $result;
    }
    
    private function validateRouting ($routingNumber=0){
        $routingNumber = preg_replace('[\D]', '', $routingNumber); //only digits
        if(strlen($routingNumber) != 9) {
            return false;  
        }

        $checkSum = 0;
        for ($i = 0, $j = strlen($routingNumber); $i < $j; $i+= 3 ) {
            //loop through routingNumber character by character
            $checkSum += ($routingNumber[$i] * 3);
            $checkSum += ($routingNumber[$i+1] * 7);
            $checkSum += ($routingNumber[$i+2]);
        }

        if($checkSum != 0 and ($checkSum % 10) == 0) {
            return true;
        } else {
            return false;
        }
    }
    
    private function validateAccountNumber($acc){
        if(strlen($acc)<2 || strlen($acc)>17){
            return false;
        }
        return true;
    }
    
    //code added by Pankaj Pandey 02/Nov/2015
    //function to run void transaction
    public function runVoidTransaction($trans_id){
		
		$df = array('rcode'=>99,'txt'=>'');
		//$credential = array('key' => '');
		$accountingtransactions = new \App\Models\AccountingTransactions();
		$accountingtransactionscont = new \App\Http\Controllers\TransactionReportController();
		$transactiondetail = $accountingtransactions->getTransactionDetailWithPartner($trans_id);
		if($transactiondetail[0]['trans_payment_type'] == 'cc'){
			//refund cc
			if(strpos($transactiondetail[0]['trans_source_key'], '|||') > 0){
				//vantiv
				list($merchant,$service) = explode('|||',$transactiondetail[0]['trans_source_key']);		
				
                $options = array('gw_mid'=>$merchant,'gw_sid'=>$service);                
                $obj_vantiv = new vantiv($options);			
				$idtx = $transactiondetail[0]['trans_result_refnum'];
				$amount = $transactiondetail[0]['trans_total_amount'];
				$result = $obj_vantiv->Void($idtx, $amount);
				if($result['response'] == '0'){
					
					$transactiondetail[0]['trans_net_amount'] = '-'.$transactiondetail[0]['trans_net_amount'];
					if($transactiondetail[0]['trans_convenience_fee'] > 0){
						$transactiondetail[0]['trans_convenience_fee'] = '-'.$transactiondetail[0]['trans_convenience_fee'];
					}
					$transactiondetail[0]['trans_total_amount']='-'.$transactiondetail[0]['trans_total_amount'];
					$transactiondetail[0]['trans_type'] = 2;
					$transactiondetail[0]['trans_first_post_date'] = date('Y-m-d H:i:s');
					$transactiondetail[0]['trans_last_post_date'] = $transactiondetail[0]['trans_first_post_date'];
					$transactiondetail[0]['trans_final_post_date'] = $transactiondetail[0]['trans_first_post_date'];
					$transactiondetail[0]['trans_result_refnum'] = $result['authcode'];
					$accountingtransactions->updateTransactionStatusType($trans_id);
					$df['rcode'] = $result['response'];
					$df['txt'] = $result['responsetext'];
					$accountingtransactionscont->createReceiptMessageUndo($transactiondetail[0]['property_id'],$trans_id,'Void',true);
					//end if statements
					
				}else{
					
					$df['rcode'] = $result['response'];
					$df['txt'] = $result['responsetext'];
					if(empty($df['txt']))$df['txt'] = 'Transaction cannot be Voided';
					
				}
				
			}else if(strpos($transactiondetail[0]['trans_source_key'], ':') > 0){ //starts else if statement
				
				//nmi
				list($nuser,$npasw)= explode(":", $transactiondetail[0]['trans_source_key']);
                $option_nmi= array( 'nmi_user'=>$nuser,'nmi_password'=>$npasw);
                $obj_nmi= new nmiDirectPost($option_nmi);				
				$obj_nmi->void($transactiondetail[0]['trans_result_refnum']);
				$result = $obj_nmi->execute();
				//echo '<pre>';
				//print_r($result); die;
				if ($result['response'] == '3' || $result['response'] == 'D') {
					
					$df['txt'] = $result['responsetext'];
					if(empty($df['txt']))$df['txt'] = 'Transaction cannot be Voided';
					
				}else {
					
					$transactiondetail[0]['trans_net_amount']='-'.$transactiondetail[0]['trans_net_amount'];
					if($transactiondetail[0]['trans_convenience_fee'] > 0){
						$transactiondetail[0]['trans_convenience_fee'] = '-'.$transactiondetail[0]['trans_convenience_fee'];
					}
					$transactiondetail[0]['trans_total_amount']='-'.$transactiondetail[0]['trans_total_amount'];
					$transactiondetail[0]['trans_type'] = 2;
					$transactiondetail[0]['trans_first_post_date'] = date('Y-m-d H:i:s');
					$transactiondetail[0]['trans_last_post_date'] = $transactiondetail[0]['trans_first_post_date'];
					$transactiondetail[0]['trans_final_post_date'] = $transactiondetail[0]['trans_first_post_date'];
					$transactiondetail[0]['trans_result_refnum'] = $result['authcode'];
					$accountingtransactions->updateTransactionStatusType($trans_id);
					$df['rcode']='0';
					$df['txt']='Voided';
					$accountingtransactionscont->createReceiptMessageUndo($transactiondetail[0]['property_id'],$trans_id,'Void',true);
					
				}
				
			}else if(!empty($row['trans_source_key'])){
				
				$result1 = $accountingtransactions->getMerchantDetail($transactiondetail[0]['property_id'], $transactiondetail[0]['trans_source_key']);
				$gatew = '';
				$mid = '';
				if(!empty($result1)){
					$gatew = $result1[0]['gateway'];
					$mid =  $result1[0]['payment_source_store_id'];
				}
				if($gatew == 'fde4'){
					
					//firstdata
					$options=array('fd4_user'=>$mid,'fd4_password'=>$transactiondetail[0]['trans_source_key']);
					$obj_fd4=new fd4DirectPost($options);					
					$torefund = trim($transactiondetail[0]['trans_result_refnum']).'--'.trim($transactiondetail[0]['trans_result_auth_code']);
					$result = $obj_fd4->Void($torefund,$transactiondetail[0]['trans_total_amount']);
					
					if($result['response'] == 'E' || $result['response'] == 'D') {
						$df['txt'] = $result['responsetext'];
						if(empty($df['txt']))$df['txt'] = 'Transaction cannot be Voided';
					}else{
						$transactiondetail[0]['trans_net_amount'] = '-'.$transactiondetail[0]['trans_net_amount'];
						if($transactiondetail[0]['trans_convenience_fee'] > 0){
							$transactiondetail[0]['trans_convenience_fee'] = '-'.$transactiondetail[0]['trans_convenience_fee'];
						}
						$transactiondetail[0]['trans_total_amount'] = '-'.$transactiondetail[0]['trans_total_amount'];
						$transactiondetail[0]['trans_type'] = 2;
						$transactiondetail[0]['trans_first_post_date'] = date('Y-m-d H:i:s');
						$transactiondetail[0]['trans_last_post_date'] = $transactiondetail[0]['trans_first_post_date'];
						$transactiondetail[0]['trans_final_post_date'] = $transactiondetail[0]['trans_first_post_date'];
						$transactiondetail[0]['trans_result_refnum'] = $gateway->process_result['authorization_num'];
						$accountingtransactions->updateTransactionStatusType($trans_id);
						$df['rcode'] = $result['response'];
						$df['txt'] = $result['responsetext'];
						$accountingtransactionscont->createReceiptMessageUndo($transactiondetail[0]['property_id'],$trans_id,'Void',true);
					}
					
				}else {    
					//pstar
					$result1 = $accountingtransactions->getMerchantAllDetail($transactiondetail[0]['property_id'], $transactiondetail[0]['trans_source_key']);
					if(!empty($result1)){
						$crd =  $result1;
						$data = array();
						$data['payment_source_merchantID'] = $crd[0]['payment_source_merchant_id'];
						$data['payment_source_locationID'] = $crd[0]['payment_source_location_id'];
						$data['payment_source_storeID'] = $crd[0]['payment_source_store_id'];
						$data['payment_source_key'] = $crd[0]['payment_source_key'];
						$data['type'] = 'cc';
						$gateway = new pstars(false);
						$result = $gateway->Void($idtx);
						if ($result['response'] == 'E' || $result['response'] == 'D') {
							
							$df['txt'] = $result['responsetext'];
							if(empty($df['txt']))$df['txt'] = 'Transaction cannot be Voided';
							
						}else{
							
							$transactiondetail[0]['trans_net_amount'] = '-'.$transactiondetail[0]['trans_net_amount'];
							if($transactiondetail[0]['trans_convenience_fee'] > 0){
								$transactiondetail[0]['trans_convenience_fee'] = '-'.$transactiondetail[0]['trans_convenience_fee'];
							}
							$transactiondetail[0]['trans_total_amount'] = '-'.$transactiondetail[0]['trans_total_amount'];
							$transactiondetail[0]['trans_type'] = 2;
							$transactiondetail[0]['trans_first_post_date'] = date('Y-m-d H:i:s');
							$transactiondetail[0]['trans_last_post_date'] = $transactiondetail[0]['trans_first_post_date'];
							$transactiondetail[0]['trans_final_post_date'] = $transactiondetail[0]['trans_first_post_date'];
							$transactiondetail[0]['trans_result_refnum'] = $result['response'];
							$accountingtransactions->updateTransactionStatusType($trans_id);
							$df['rcode'] = $result['response'];
							$df['txt'] = $result['responsetext'];
							$accountingtransactionscont->createReceiptMessageUndo($transactiondetail[0]['property_id'],$trans_id,'Void',true);
						}
						
					}else {
						
						$df['txt']='Unknown credentials';
						
					} 
				}
				
			}
			
		// end if statements
		}else if($transactiondetail[0]['trans_payment_type'] == 'ec'){
			
			//void ec
			$result1 = $accountingtransactions->getMerchantAllDetail($transactiondetail[0]['property_id'], $transactiondetail[0]['trans_source_key']);
			if(!empty($result1)>0){
				$idtx = $transactiondetail[0]['trans_result_refnum'];
				$crd =  $result1;
				$data = array();
				$data['payment_source_merchantID'] = $crd[0]['payment_source_merchant_id'];
				$data['payment_source_locationID'] = $crd[0]['payment_source_location_id'];
				$data['payment_source_storeID'] = $crd[0]['payment_source_store_id'];
				$data['payment_source_key'] = $crd[0]['payment_source_key'];
				$data['type'] = 'ec';
				if($crd['gateway'] == 'bokf'){
					$gateway = new bokfACHGateway(false);
				}else{
					$gateway = new pstars($data);    
				}				
				$result = $gateway->Void($idtx);
				if ($result['response'] == 'E' || $result['response'] == 'D') {
					$df['txt'] = $result['responsetext'];
					if(empty($df['txt']))$df['txt'] = 'Transaction cannot be Voided';
				}
				else {
					$transactiondetail[0]['trans_net_amount'] = '-'.$transactiondetail[0]['trans_net_amount'];
					if($transactiondetail[0]['trans_convenience_fee'] > 0){
						$transactiondetail[0]['trans_convenience_fee'] = '-'.$transactiondetail[0]['trans_convenience_fee'];
					}
					$transactiondetail[0]['trans_total_amount'] = '-'.$transactiondetail[0]['trans_total_amount'];
					$transactiondetail[0]['trans_type'] = 2;
					$transactiondetail[0]['trans_first_post_date'] = date('Y-m-d H:i:s');
					$transactiondetail[0]['trans_last_post_date'] = $transactiondetail[0]['trans_first_post_date'];
					$transactiondetail[0]['trans_final_post_date'] = $transactiondetail[0]['trans_first_post_date'];
					$transactiondetail[0]['trans_result_refnum'] = $result['response'];
					$accountingtransactions->updateTransactionStatusType($trans_id);
					$df['rcode'] = $result['response'];
					$df['txt'] = $result['responsetext'];
					$accountingtransactionscont->createReceiptMessageUndo($transactiondetail[0]['property_id'],$trans_id,'Void',true);
				}
			}else {
				$df['txt'] = 'Unknown credentials';
			}
		//end else if statements	
		}else{
			
			$df['txt'] = 'Unknown payment method';
			
		}
		return $df;
		
	}
    
    
}
