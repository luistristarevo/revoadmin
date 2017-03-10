<?php
namespace App\CustomClass;

use App\Models\Properties;
use App\Models\Transations;
use \Illuminate\Support\Facades\Mail;
use App\User;
 
class Email {
    
    /* variable to email
            subject 
            header_h4 
            from 
            to 

            pstatus 
            pmethod 
            pday 
            pcategories 
            pnetamount 
            pconvenience_fee 
            pgrandtotal 

            mname 
            mcompany_name 

            uname 
            uaccount_number 
            uaddress 
            ucity 
            ustate 
            uzip 
        */
    
    //SUCCESSFULEMAIL_SUBJECT && SUCCESSFULEMAIL
    public function PaymentReceipt ($response,$paymentInfo){
        
        $obj_property= new Properties();
        $obj_user= new User();
        $ids= $obj_property->getOnlyIds($paymentInfo['id_property']);
        $email_template =array();
        $web_user_id=$paymentInfo['web_user_id'];
        $property_id=$paymentInfo['id_property'];
        
        
        if($response['response']!=1){ //Transaction Error/Declined
            //notunsuccessfulemail (Property Setting)
            $notpropertyemail= $obj_property->getPropertySettings($paymentInfo['id_property'], $ids['id_companies'], $ids['id_partners'], 'notunsuccessfulemail');
            //DECLINEDPAYMENT (User Setting)
            $notusremail=$obj_user->getUserSettings($web_user_id,'DECLINEDPAYMENT');
            if($notpropertyemail==1 && $notusremail==1) return true;
            
            $subject= $obj_property->getPropertySettings($paymentInfo['id_property'], $ids['id_companies'], $ids['id_partners'], 'UNSUCCESSFULEMAIL_SUBJECT');
            if(empty($subject))$subject="Payment Transaction was Declined or had errors";
            $template= $obj_property->getPropertySettings($paymentInfo['id_property'], $ids['id_companies'], $ids['id_partners'], 'UNSUCCESSFULEMAIL');
            if(empty($template)){
            $template=\Illuminate\Support\Facades\View::make('mail.paymentDeclined')->__toString();}
            else{
                $vartmp=  explode("|", $template);
                $subject=$vartmp[0];
                $template=$vartmp[1];
            }
        }else{
            //notsuccessfulemail (Property Setting)
            $notpropertyemail= $obj_property->getPropertySettings($paymentInfo['id_property'], $ids['id_companies'], $ids['id_partners'], 'notsuccessfulemail');
            //DECLINEDPAYMENT (User Setting)
            $notusremail=$obj_user->getUserSettings($web_user_id,'SUCCESSFULPAYMENT');
            if($notpropertyemail==1 && $notusremail==1) return true;
            
            $subject= $obj_property->getPropertySettings($paymentInfo['id_property'], $ids['id_companies'], $ids['id_partners'], 'SUCCESSFULEMAIL_SUBJECT');
            if(empty($subject))$subject="Payment Transaction was Approved";
            $template= $obj_property->getPropertySettings($paymentInfo['id_property'], $ids['id_companies'], $ids['id_partners'], 'SUCCESSFULEMAIL');
            if(empty($template)){
            $template=\Illuminate\Support\Facades\View::make('mail.paymentApproved')->__toString();
            }else{
                $vartmp=  explode("|", $template);
                $subject=$vartmp[0];
                $template=$vartmp[1];
            }
        }
        
        $merchant_name=$obj_property->get1PropertyInfo($property_id, 'name_clients');
        
        //get setting -> 'donotreplyemail' FROM
        $donotreply= $obj_property->getPropertySettings($paymentInfo['id_property'], $ids['id_companies'], $ids['id_partners'], 'donotreplyemail');
        if($donotreply==1){$from ='do_not_reply@revopayments.com';
        }else{$from = $obj_property->get1PropertyInfo($paymentInfo['id_property'], 'email_address_clients');}
           
        $list_from=  explode(";",$from);
        $from=$this->UnvailableEmail($list_from[0]);
        
        $accounting_email="";
        //get accounting_email
        if($notpropertyemail!=1){
            $accounting_email= $obj_property->get1PropertyInfo($paymentInfo['id_property'], 'accounting_email_address_clients');
        }else{$notpropertyemail=1;}
        
        if($notpropertyemail!=1 && empty($accounting_email))$notpropertyemail=1;
            else{
                $accounting_email=  str_replace(" ","",$accounting_email);
                $list_account_email=  explode(";", $accounting_email);
            }
        
        //get usr email
        $usr_email=$obj_user->get1UserInfo($web_user_id, 'email_address');
        if(empty($usr_email))$notusremail=1; 
         
        if(!filter_var($from, FILTER_VALIDATE_EMAIL)){
            $from="do_not_reply@revopay.com";
        }
         
        
        $template=$this->prepareTemplate($template, $subject, $property_id, $response, $paymentInfo);
        $info['to']=$usr_email;
        $info['from']=$from;
        $info['name']=$merchant_name;
        $info['subject'] = $subject;
        $email_template['body']=$template;
        
        if($notusremail!=1){
            if(filter_var($usr_email, FILTER_VALIDATE_EMAIL)){
                Mail::send('mail.genericEmail',$email_template, function($message) use($info){
                    $message->from($info['from'],$info['name']);
                    $message->to($info['to'])->subject($info['subject']);
                });
            }    
        }
        
        if($notpropertyemail!=1){
            for($e=0;$e<count($list_account_email);$e++){
                $info['to']=$list_account_email[$e];
                if(filter_var( $info['to'], FILTER_VALIDATE_EMAIL)){
                    Mail::send('mail.genericEmail',$email_template, function($message) use($info){
                        $message->from($info['from'],$info['name']);
                        $message->to($info['to'])->subject($info['subject']);
                    });
                }
            }
        }
        
    }
    
    public function ScheduleReceipt ($response,$paymentInfo){
        
        $obj_property= new Properties();
        $obj_user= new User();
        $ids= $obj_property->getOnlyIds($paymentInfo['id_property']);
        $email_template =array();
        $web_user_id=$paymentInfo['web_user_id'];
        $property_id=$paymentInfo['id_property'];
        
        
        if($response['response']!=1){ //Transaction Error/Declined 
            //no msg for now
            return true;
            //notunsuccessfulemail (Property Setting)
            $notpropertyemail= $obj_property->getPropertySettings($paymentInfo['id_property'], $ids['id_companies'], $ids['id_partners'], 'notunsuccessfulemail');
            //DECLINEDPAYMENT (User Setting)
            $notusremail=$obj_user->getUserSettings($web_user_id,'DECLINEDPAYMENT');
            if($notpropertyemail==1 && $notusremail==1) return true;
            
            $subject= $obj_property->getPropertySettings($paymentInfo['id_property'], $ids['id_companies'], $ids['id_partners'], 'UNSUCCESSFULEMAIL_SUBJECT');
            if(empty($subject))$subject="Schedule Payment was Declined or had errors";
            $template= $obj_property->getPropertySettings($paymentInfo['id_property'], $ids['id_companies'], $ids['id_partners'], 'UNSUCCESSFULEMAIL');
            if(empty($template)){
            $template=\Illuminate\Support\Facades\View::make('mail.paymentDeclined')->__toString();}else{
                $vartmp=  explode("|", $template);
                $subject=$vartmp[0];
                $template=$vartmp[1];
            }
        }else{
            //notsuccessfulemail (Property Setting)
            $notpropertyemail= $obj_property->getPropertySettings($paymentInfo['id_property'], $ids['id_companies'], $ids['id_partners'], 'notsuccessfulemailrec');
            //DECLINEDPAYMENT (User Setting)
            $notusremail=$obj_user->getUserSettings($web_user_id,'SUCCESSFULPAYMENT');
            if($notpropertyemail==1 && $notusremail==1) return true;
            
            $subject= $obj_property->getPropertySettings($paymentInfo['id_property'], $ids['id_companies'], $ids['id_partners'], 'RECSUCCESSFULEMAIL_SUBJECT');
            if(empty($subject))$subject="Schedule Payment was Approved";
            $template= $obj_property->getPropertySettings($paymentInfo['id_property'], $ids['id_companies'], $ids['id_partners'], 'RECSUCCESSFULEMAIL');
            if(empty($template)){
            $template=\Illuminate\Support\Facades\View::make('mail.scheduleApproved')->__toString();}
            else{
                $vartmp=  explode("|", $template);
                $subject=$vartmp[0];
                $template=$vartmp[1];
            }
        }
        
        
        $merchant_name=$obj_property->get1PropertyInfo($property_id, 'name_clients');
        
        //get setting -> 'donotreplyemail' FROM
        $donotreply= $obj_property->getPropertySettings($paymentInfo['id_property'], $ids['id_companies'], $ids['id_partners'], 'donotreplyemail');
        if($donotreply==1){$from ='do_not_reply@revopayments.com';
        }else{$from = $obj_property->get1PropertyInfo($paymentInfo['id_property'], 'email_address_clients');}
           
        $list_from=  explode(";",$from);
        $from=$this->UnvailableEmail($list_from[0]);
        
        $accounting_email="";
        //get accounting_email
        if($notpropertyemail!=1){
            $accounting_email= $obj_property->get1PropertyInfo($paymentInfo['id_property'], 'accounting_email_address_clients');
        }else{$notpropertyemail=1;}
        
        if($notpropertyemail!=1 && empty($accounting_email))$notpropertyemail=1;
            else{
                $accounting_email=  str_replace(" ","",$accounting_email);
                $list_account_email=  explode(";", $accounting_email);
            }
        
        //get usr email
        $usr_email=$obj_user->get1UserInfo($web_user_id, 'email_address');
        if(empty($usr_email))$notusremail=1; 
         
        if(!filter_var($from, FILTER_VALIDATE_EMAIL)){
            $from="do_not_reply@revopay.com";
        }
        
        $template=$this->prepareTemplate($template, $subject, $property_id, $response, $paymentInfo);
        $info['to']=$usr_email;
        $info['from']=$from;
        $info['name']=$merchant_name;
        $info['subject'] = $subject;
        $email_template['body']=$template;
        
        if($notusremail!=1){
            if(filter_var($usr_email, FILTER_VALIDATE_EMAIL)){
                Mail::send('mail.genericEmail',$email_template, function($message) use($info){
                    $message->from($info['from'],$info['name']);
                    $message->to($info['to'])->subject($info['subject']);
                });
            }    
        }
        
        if($notpropertyemail!=1){
            for($e=0;$e<count($list_account_email);$e++){
                $info['to']=$list_account_email[$e];
                if(filter_var( $info['to'], FILTER_VALIDATE_EMAIL)){
                    Mail::send('mail.genericEmail',$email_template, function($message) use($info){
                        $message->from($info['from'],$info['name']);
                        $message->to($info['to'])->subject($info['subject']);
                    });
                }
            }
        }
    }
 
    public function FraudControlmail($web_user_id,$property_id,$fraudText){
        $obj_user= new User();
        $obj_properties= new Properties();
        $data= array();
        
        $user_info= $obj_user->getUsrInfo($web_user_id);
        $property_info= $obj_properties->getPropertyInfo($property_id);
        
        $to="tech@revopayments.com";
        $from = "info@revopayments.com";
        $time= date("m-d-Y H:i:s");
        $data['to']=$to;
        
        
        $data['time']=$time;
        $data['from']=$from;
        $data['fraudtext']=$fraudText;
        $data['usrName']=$user_info['first_name']." ".$user_info['last_name'];
        $data['username']=$user_info['username'];
        $data['propertyName']=$property_info['name_clients'];
        
        Mail::send('mail.fraudcontrol', $data, function($message){});
        $data['to']="risk@revopayments.com";
        Mail::send('mail.fraudcontrol', $data, function($message){});
                
    }
    
    public function FraudControlmailtoMerchant($web_user_id,$property_id,$fraudText){
        $obj_user= new User();
        $obj_properties= new Properties();
        $data= array();
        
        $user_info= $obj_user->getUsrInfo($web_user_id);
        $property_info= $obj_properties->getPropertyInfo($property_id);
        $to=$property_info['email_address_clients'];
        $from = "info@revopayments.com";
        $time= date("m-d-Y H:i:s");
        $data['to']=$to;
              
        $data['time']=$time;
        $data['from']=$from;
        $data['fraudtext']=$fraudText;
        $data['usrName']=$user_info['first_name']." ".$user_info['last_name'];
        $data['username']=$user_info['username'];
        $data['propertyName']=$property_info['name_clients'];
        
        Mail::send('mail.alertcontrol', $data, function($message){});
        $data['to']="dsilveira@revopay.com";
        Mail::send('mail.alertcontrol', $data, function($message){});
        $data['to']="sjhosin@revopay.com";
        Mail::send('mail.alertcontrol', $data, function($message){});
        $data['to']="mcorbera@revopay.com";
        Mail::send('mail.alertcontrol', $data, function($message){});
        $data['to']="mlance@revopay.com";
        Mail::send('mail.alertcontrol', $data, function($message){});
                      
    }
        /* 
        Function to send email to Refund action
        Danieyis Santiago
        */ 
    public function RefundReceipt(){
        $obj_user= new User();
        $obj_properties= new Properties();
        $data= array();
        
        //$user_info= $obj_user->getUsrInfo($web_user_id);
        //$property_info= $obj_properties->getPropertyInfo($property_id);
        $to='dsantiago@revopay.com';
        $from = "info@revopayments.com";
        $time= date("m-d-Y H:i:s");
        $data['to']=$to;
              
        $data['time']=$time;
        $data['from']=$from;
        $data['fraudtext']='Refund Payment';
        $data['usrName']='Danieyis';
        $data['username']='dsantiago';
        $data['propertyName']='Test';
        
        Mail::send('mail.alertcontrol', $data, function($message){});
                     
    }
        
    public function prepareTemplate($template,$subject,$idproperty,$response,$paymentInfo){
        /*
        * replace the brackets fields
        */
        if(empty($template))return '';
        $obj_property=new Properties();
               
        $obj_company=new \App\Models\Companies();
        $obj_partner=new \App\Models\Partners();
        $merchant=$obj_property->getPropertyInfo($idproperty);
        $company=$obj_company->getCompanyInfo($merchant['id_companies']);
        $partner=$obj_partner->getDomain($merchant['id_partners']);
        $hostname=  config("app.hostName");
        $obj_user=new User();
        $usr=$obj_user->getUsrInfo($paymentInfo['web_user_id']);
        $disclaimer=$obj_property->getPropertySettings($idproperty, $merchant['id_companies'], $merchant['id_partners'], 'SETTLMENT_DISCLAIMER');
        //get autopay info
        $obj_trans= new Transations();
        $trans_result_auth_code="";
        $startpay=date("M d, Y");
        $frequency="One Time";
        if($response['response']==1){
            if(isset($response['authcode'])){
                $trans_result_auth_code=$response['authcode'];
            }
        }
        if(!isset($paymentInfo['freq'])){
            $trans_descr=$obj_trans->get1TransInfo($response['txid'], 'trans_descr');
            $nextpay="";
            $dynamic=0;
        }else{
            if ($paymentInfo['start_date'] == date("Y-m-d")) 
            {
                $trans_descr = $obj_trans->get1TransInfo($response['txid'], 'trans_descr');
            } else {
                $trans_descr = $obj_trans->get1recurringInfo($response['txid'], 'trans_descr');
            }
            $nextpay = $obj_trans->get1recurringInfo($response['txid'], 'trans_next_post_date');
            $frequency = $obj_trans->get1recurringInfo($response['txid'], 'trans_schedule');
            $startpay = $obj_trans->get1recurringInfo($response['txid'], 'trans_first_post_date');
            $dynamic = $obj_trans->get1recurringInfo($response['txid'], 'dynamic');
            $type= $obj_trans->get1recurringInfo($response['txid'], 'trans_payment_type');
        }
        $trans_descr=str_replace("\n","<br>",$trans_descr);
        if($dynamic==1)
        {
            //get the drp fee
            $drpfee= $obj_property->getConvenienceFeeDRP($idproperty, 1 , $type);
            $trans_descr= "<b> Grand Total: </b>  Balance Owed";
            if($drpfee!="" && !empty($drpfee))
            {
             $trans_descr= $trans_descr."+".$drpfee;  
            }
            
        }
        $chain=str_replace('[:LOGO:]',"<img src='".$hostname.$merchant['logo']."'>",$template);
        if(isset($response['responsetext'])){
            $chain=str_replace('[:ERRORMSG:]',$response['responsetext'],$chain);
        }
        $baseUrl = $hostname . "/master/index.php/" . $partner . "/properties/" . $merchant['subdomain_clients'];
        $chain = str_replace('[:TICKETLINK:]', "<a href='" . $baseUrl . '/newhelp' . "'>Click here</a>", $chain);
        $chain=str_replace('[:TRANS_DATE:]',date('M d, Y'),$chain);
        $chain=str_replace('[:DBA_NAME:]',$merchant['name_clients'],$chain);
        $chain=str_replace('[:LOGINLINK:]', $baseUrl . '/login' ,$chain);
        $chain=str_replace('[:CONTACTNAME:]',$company['contact_name'],$chain);
        $chain=str_replace('[:GROUPNAME:]',$company['company_name'],$chain);
        if(!isset($usr['companyname']))$usr['companyname']='';
        $chain=str_replace('[:COMPANYNAME:]',$usr['companyname'],$chain);
        $chain=str_replace('[:FIRSTNAME:]',$usr['first_name'],$chain);
        $chain=str_replace('[:LASTNAME:]',$usr['last_name'],$chain);
        $chain=str_replace('[:ACCOUNT_NUMBER:]',$usr['account_number'],$chain);
        $chain=str_replace('[:ACCOUNTNUMBER:]',$usr['account_number'],$chain);
        $chain=str_replace('[:UNIT:]',$usr['address_unit'],$chain);
        $chain=str_replace('[:UADDR:]',$usr['address'],$chain);
        $chain=str_replace('[:UCITY:]',$usr['city'],$chain);
        $chain=str_replace('[:USTATE:]',$usr['state'],$chain);
        $chain=str_replace('[:UZIP:]',$usr['zip'],$chain);
        $chain=str_replace('[:UPHONE:]',$usr['phone_number'],$chain);
        $chain=str_replace('[:UEMAIL:]',$usr['email_address'],$chain);
        $chain=str_replace('[:DISCLAIMER:]',$disclaimer,$chain);
        $chain=str_replace('[:REFNUM:]',$response['txid'],$chain);
        $chain=str_replace('[:AUTHNUM:]',$trans_result_auth_code,$chain);
        $chain=str_replace('[:NETAMOUNT:]',$paymentInfo['net_amount'],$chain);
        $chain=str_replace('[:NEXTDATE:]',date('M d, Y',  strtotime($nextpay)),$chain);
        $chain=str_replace('[:STARTDATE:]',date('M d, Y',  strtotime($startpay)),$chain);
        $chain=str_replace('[:FREQUENCY:]',$frequency,$chain);
        $chain=str_replace('[:DESCRIPTION:]',$trans_descr,$chain);
        $chain=  str_replace('[:PAYMENT_INFO:]', $paymentInfo['card_info']['card_type'], $chain);
        return $chain;
        /*
            <div >[:AUTHNUM:]</div><br>
            <div >[:DESCRIPTION:]</div><br>
         * 
         */

    }
    
    public function ReplaceRecOpenAPITemplate($template,$subject,$TransInfo){
        /*
        * replace the brackets fields
        */
        if(empty($template))return '';
        $obj_property=new Properties();
        $obj_company=new \App\Models\Companies();
        $obj_partner=new \App\Models\Partners();
        $idproperty = $TransInfo['property_id'];
        $merchant=$obj_property->getPropertyInfo($idproperty);
        $company=$obj_company->getCompanyInfo($merchant['id_companies']);
        $partner=$obj_partner->getDomain($merchant['id_partners']);
        $hostname=  config("app.hostName");
        $obj_user=new User();
        $usr=$obj_user->getUsrInfo($TransInfo['trans_web_user_id']);
        $disclaimer=$obj_property->getPropertySettings($idproperty, $merchant['id_companies'], $merchant['id_partners'], 'SETTLMENT_DISCLAIMER');
        //get autopay info
        $obj_trans= new Transations();
        $startpay=date("M d, Y");
        $frequency="One Time";
        $trans_descr=$obj_trans->get1recurringInfo($TransInfo['trans_id'], 'trans_descr');
        $trans_descr=str_replace("\n","<br>",$trans_descr);
        $nextpay=$obj_trans->get1recurringInfo($TransInfo['trans_id'], 'trans_next_post_date');
        $frequency=$obj_trans->get1recurringInfo($TransInfo['trans_id'], 'trans_schedule');
        $startpay=$obj_trans->get1recurringInfo($TransInfo['trans_id'], 'trans_first_post_date');
        
        $baseUrl = $hostname . "/master/index.php/" . $partner . "/properties/" . $merchant['subdomain_clients'];
        $chain=str_replace('[:LOGO:]',"<img src='".$hostname.$merchant['logo']."'>",$template);
        $chain=str_replace('[:TICKETLINK:]', "<a href='" . $baseUrl . '/newhelp' . "'>Click here</a>", $chain);
        $chain=str_replace('[:TRANS_DATE:]',date('M d, Y'),$chain);
        $chain=str_replace('[:DBA_NAME:]',$merchant['name_clients'],$chain);
        $chain=str_replace('[:LOGINLINK:]', $baseUrl . '/login' , $chain);
        $chain=str_replace('[:CONTACTNAME:]',$company['contact_name'],$chain);
        $chain=str_replace('[:GROUPNAME:]',$company['company_name'],$chain);
        if(!isset($usr['companyname']))$usr['companyname']='';
        $chain=str_replace('[:COMPANYNAME:]',$usr['companyname'],$chain);
        $chain=str_replace('[:FIRSTNAME:]',$usr['first_name'],$chain);
        $chain=str_replace('[:LASTNAME:]',$usr['last_name'],$chain);
        $chain=str_replace('[:ACCOUNT_NUMBER:]',$usr['account_number'],$chain);
        $chain=str_replace('[:ACCOUNTNUMBER:]',$usr['account_number'],$chain);
        $chain=str_replace('[:UNIT:]',$usr['address_unit'],$chain);
        $chain=str_replace('[:UADDR:]',$usr['address'],$chain);
        $chain=str_replace('[:UCITY:]',$usr['city'],$chain);
        $chain=str_replace('[:USTATE:]',$usr['state'],$chain);
        $chain=str_replace('[:UZIP:]',$usr['zip'],$chain);
        $chain=str_replace('[:UPHONE:]',$usr['phone_number'],$chain);
        $chain=str_replace('[:UEMAIL:]',$usr['email_address'],$chain);
        $chain=str_replace('[:DISCLAIMER:]',$disclaimer,$chain);
        $chain=str_replace('[:REFNUM:]',$TransInfo['trans_id'],$chain);
        $chain=str_replace('[:AUTHNUM:]',"N/A",$chain);
        $chain=str_replace('[:NETAMOUNT:]',$TransInfo['trans_recurring_net_amount'],$chain);
        $chain=str_replace('[:NEXTDATE:]',date('M d, Y',  strtotime($nextpay)),$chain);
        $chain=str_replace('[:STARTDATE:]',date('M d, Y',  strtotime($startpay)),$chain);
        $chain=str_replace('[:FREQUENCY:]',$frequency,$chain);
        $chain=str_replace('[:DESCRIPTION:]',$trans_descr,$chain);
        $chain=  str_replace('[:PAYMENT_INFO:]', $TransInfo['trans_card_type'], $chain);
        return $chain;
            

    }
    
    public function ReplacePayOpenAPITemplate($template,$subject,$TransInfo){
        /*
        * replace the brackets fields
        */
        if(empty($template))return '';
        $obj_trans = new Transations();
        $obj_property=new Properties();
        $obj_company=new \App\Models\Companies();
        $obj_partner=new \App\Models\Partners();
        $idproperty= $TransInfo['property_id'];
        $web_user_id = $TransInfo['trans_web_user_id'];
        $merchant=$obj_property->getPropertyInfo($idproperty);
        $company=$obj_company->getCompanyInfo($merchant['id_companies']);
        $partner=$obj_partner->getDomain($merchant['id_partners']);
        $hostname=  config("app.hostName");
        $obj_user=new User();
        $usr=$obj_user->getUsrInfo($web_user_id);
        $disclaimer=$obj_property->getPropertySettings($idproperty, $merchant['id_companies'], $merchant['id_partners'], 'SETTLMENT_DISCLAIMER');
        //get autopay info
        
        $trans_result_auth_code="";
        
        $trans_descr=$obj_trans->get1TransInfo($TransInfo['trans_id'], 'trans_descr');
        $trans_descr=str_replace("\n","<br>",$trans_descr);
        $nextpay="";
        
        $baseUrl=$hostname . "/master/index.php/" . $partner . "/properties/" . $merchant['subdomain_clients'];
        $chain=str_replace('[:LOGO:]',"<img src='".$hostname.$merchant['logo']."'>",$template);
        $chain=str_replace('[:ERRORMSG:]',$TransInfo['trans_result_error_desc'],$chain);
        $chain=str_replace('[:TICKETLINK:]', "<a href='" . $baseUrl . '/newhelp' . "'>Click here</a>", $chain);
        $chain=str_replace('[:TRANS_DATE:]',date('M d, Y'),$chain);
        $chain=str_replace('[:DBA_NAME:]',$merchant['name_clients'],$chain);
        $chain=str_replace('[:LOGINLINK:]', $baseUrl . '/login' ,$chain);
        $chain=str_replace('[:CONTACTNAME:]',$company['contact_name'],$chain);
        $chain=str_replace('[:GROUPNAME:]',$company['company_name'],$chain);
        if(!isset($usr['companyname']))$usr['companyname']='';
        $chain=str_replace('[:COMPANYNAME:]',$usr['companyname'],$chain);
        $chain=str_replace('[:FIRSTNAME:]',$usr['first_name'],$chain);
        $chain=str_replace('[:LASTNAME:]',$usr['last_name'],$chain);
        $chain=str_replace('[:ACCOUNT_NUMBER:]',$usr['account_number'],$chain);
        $chain=str_replace('[:ACCOUNTNUMBER:]',$usr['account_number'],$chain);
        $chain=str_replace('[:UNIT:]',$usr['address_unit'],$chain);
        $chain=str_replace('[:UADDR:]',$usr['address'],$chain);
        $chain=str_replace('[:UCITY:]',$usr['city'],$chain);
        $chain=str_replace('[:USTATE:]',$usr['state'],$chain);
        $chain=str_replace('[:UZIP:]',$usr['zip'],$chain);
        $chain=str_replace('[:UPHONE:]',$usr['phone_number'],$chain);
        $chain=str_replace('[:UEMAIL:]',$usr['email_address'],$chain);
        $chain=str_replace('[:DISCLAIMER:]',$disclaimer,$chain);
        $chain=str_replace('[:REFNUM:]',$TransInfo['trans_id'],$chain);
        $chain=str_replace('[:AUTHNUM:]',$TransInfo['trans_result_auth_code'],$chain);
        $chain=str_replace('[:NETAMOUNT:]',$TransInfo['trans_net_amount'],$chain);
        //$chain=str_replace('[:NEXTDATE:]',date('M d, Y',  strtotime($nextpay)),$chain);
        //$chain=str_replace('[:STARTDATE:]',date('M d, Y',  strtotime($startpay)),$chain);
        $chain=str_replace('[:DESCRIPTION:]',$trans_descr,$chain);
        $chain=  str_replace('[:PAYMENT_INFO:]', $TransInfo['trans_card_type'], $chain);
        return $chain;
    }
    
    public function UnvailableEmail($email){
        if(substr_count($email,'@yahoo')){
            $email="info@revopayments.com";
        }elseif(substr_count($email,'@aol')){
            $email="info@revopayments.com";
        }elseif(substr_count($email,'@hotmail')){
            $email="info@revopayments.com";
        }elseif(substr_count($email,'@outlook')){
            $email="info@revopayments.com";
        }
        
        return $email;
    }
    
    //code added by Pankaj Pandey 30/10/2015
    public function transactionreceipt($trans_id, $eml){
		
		//echo $trans_id.' == '.$eml; echo '<br/>';
		$accountingtransactions = new \App\Models\AccountingTransactions();
		if($trans_id != ''){
			//echo $trans_id.' IN IF == '.$eml; die;
			if(empty($eml)){
				//read user email
				
				$webuserdetail = $accountingtransactions->getWebUserEmail($trans_id);
				//echo '<pre>';
				//print_r($webuserdetail); die;
				if(!empty($webuserdetail)){
					
					$uid = $webuserdetail[0]['trans_web_user_id'];
					$pid = $webuserdetail[0]['property_id'];
					$email = $webuserdetail[0]['email_address'];
					//list($uid,$pid,$email)=  $webuserdetail;
					if(!empty($email))$eml=$email;
					
			    }
			}
           //echo $eml; die;
           if(empty($eml)){
				return array('error' => '1', 'txt'=>'Missing recipient email address');
		   }else{
				
				//$accountingtransactions = new AccountingTransactions();
				$propertyemail = $accountingtransactions->getPropertyEmail($trans_id);
				
				if(!empty($propertyemail)){
					$acco_email =  $propertyemail[0]['accounting_email_address_clients'];;
					
					if($acco_email == ""){
						$acco_email = 'info@revopayments.com';
					} else {
						 $accd = explode(";",$acco_email);
						 $acco_email = $accd[0];
					}
					if(strpos($acco_email,'yahoo')>0)$acco_email='info@revopayments.com';  
					
					if(strpos($acco_email,'aol.com')>0)$acco_email='info@revopayments.com';
					$receiptcontent = $accountingtransactions->getReceipt($trans_id);
					$subject =  'Payment Receipt Id '.$trans_id.'(Copy)';
					//echo $receiptcontent; die;
					if($receiptcontent){
						
						//$eml = 'PPandey@revopay.com';
						$info['from'] = $acco_email;
						$info['to'] = $eml;
						$info['subject'] = $subject;
						$receiptcontentarray = array('receiptcontent' => $receiptcontent);
						Mail::send('mail.receipttransactionemail', $receiptcontentarray,function($messageinfo) use($info){
							$messageinfo->from($info['from']);
							$messageinfo->to($info['to'])->subject($info['subject']);
						});				
						
					}else{
						
						//to create email text
						//@todo
						$disclaimer = '';
						$customize = new \App\Models\Customize();
						$properties = new Properties();
						$transactiondetail = $accountingtransactions->getTransactionDetailWithPartner($trans_id);
						//echo '<pre>';
						//print_r($transactiondetail); die;
						$id_property = $transactiondetail[0]['property_id'];
						$user_info = $accountingtransactions->getWebUserDetail($transactiondetail[0]['trans_web_user_id']);
						$error_msg = $transactiondetail[0]['trans_result_error_desc'];    
						$message = "";
						$propertysettingvalues = $customize->getSettingsValueProperties($transactiondetail[0]['partner_id'],  $transactiondetail[0]['company_id'], $transactiondetail[0]['merchant_id']);
						$subject =  'Payment Receipt Id '.$trans_id.'(Copy)';
						$org_url = $properties->getPropertyUrlById($id_property);
						$org_name = $properties->getPropertyNameById($id_property);
						$logourl = public_path()."/media/images/revopay-web2.png";
						//echo 'pp'; die;
						//echo '<pre>';
						//print_r($propertysettingvalues); die;
						if(!empty($propertysettingvalues)){
							$disclaimer = $propertysettingvalues['SETTLMENT_DISCLAIMER'];				
						}
						if (empty($message)) {
							//echo 'INNER IF'; die;
								$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
											<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
											<head>
											<style> td {font-family: arial,helvetica,sans-serif; font-size: 10pt; color: #000;} </style>
											</head>
											<body><table border="0" cellpadding="1" cellspacing="2" width="90%">
												<tr>
													<td><img src='.$org_url[0]['url_clients'].'/media/images/logo.jpg" alt="Revo Payments" title="Revo Payments"/></td>
													<td><h3>Thank you for using Revo Payments, your online Payment Provider.</h3></td>
												</tr>
												<tr><td colspan="2"><br />For customer service please <a href="'.$org_url[0]['url_clients'].'/newhelp.php">contact us</a><br /></td></tr>
												<tr><td bgcolor="#C4C7D4" colspan="2"><b>Transaction Result</b></td></tr>
												<tr><td><b>Date:</b></td><td>'.$transactiondetail[0]['trans_last_post_date'].'</td></tr>
												<tr><td><b>Reference #:</b></td><td>'.$transactiondetail[0]['trans_result_refnum'].'</td></tr>
												<tr><td><b>Authorization:</b></td><td>'.$transactiondetail[0]['trans_result_auth_code'].'</td></tr>
												<tr><td bgcolor="#C4C7D4" colspan="2"><b>Transaction Details</b></td></tr>
												<tr><td><b>Paying in:</b></td><td>'.$org_name[0]['name_clients'].'</td></tr>
												<tr><td><b>Type:</b></td><td>Sale</td></tr>
												<tr><td><b>Source:</b></td><td>WEB</td></tr>
												<tr><td><b>Account #:</b></td><td>'.$user_info[0]['account_number']."/".$user_info[0]['first_name']." ".$user_info[0]['last_name']."/".$user_info[0]['username'].'</td></tr>
												<tr><td><b>Net Payment:</b></td><td>'.number_format($transactiondetail[0]['trans_net_amount'], 2, ".", ",").'</td></tr>
												<tr><td><b>Convenience Fee:</b></td><td>'.number_format($transactiondetail[0]['trans_convenience_fee'], 2, ".", ",").'</td></tr>
												<tr><td><b>Total Payment Amount:</b></td><td>'.number_format($transactiondetail[0]['trans_total_amount'], 2, ".", ",").'</td></tr>
												<tr><td valign="top"><b>Payment Details:</b></td><td><pre>'.str_replace('$','&#36;',$transactiondetail[0]['trans_descr']).'</pre></td></tr>
												<tr><td><b>Card Holder:</b></td><td>'.$user_info[0]['first_name'].' '.$user_info[0]['last_name'].'</td></tr>
												<tr><td><b>Card Number:</b></td><td>'.$transactiondetail[0]['trans_card_type'].'</td></tr>
												<tr><td><b>Payment Type:</b></td><td>'.$transactiondetail[0]['trans_card_type'].'</td></tr>
												<tr><td bgcolor="#C4C7D4" colspan="2"><b>Billing Information</td></tr>
												<tr><td><b>Customer ID:</b></td><td>'.$user_info[0]['account_number']."/".$user_info[0]['first_name'].' '.$user_info[0]['last_name']."/".$user_info[0]['username'].'</td></tr>
												<tr><td><b>First Name:</b></td><td>'.$user_info[0]['first_name'].'</td></tr>
												<tr><td><b>Last Name:</b></td><td>'.$user_info[0]['last_name'] .'</td></tr>
												<tr><td><b>Street:</b></td><td>'.$user_info[0]['address'].'</td></tr>
												<tr><td><b>Street2:</b></td><td>n/a</td></tr>
												<tr><td><b>City:</b></td><td>'.$user_info[0]['city'].'</td></tr>
												<tr><td><b>State:</b></td><td>'.$user_info[0]['state'].'</td></tr>
												<tr><td><b>Zip:</b></td><td>'.$user_info[0]['zip'].'</td></tr>
												<tr><td><b>Country:</b></td><td>USA</td></tr>
												<tr><td><b>Phone:</b></td><td>'.$user_info[0]['phone_number'].'</td></tr>
												<tr><td><b>Email:</b></td><td>'.$user_info[0]['email_address'].'</td></tr>
												<tr><td colspan="2">'.$disclaimer.'</td></tr>
												<tr><td colspan="2"><p style="color: #666666; font-size: 12px"> Please do not reply to this email message, as this email was sent from a notification-only address.</p></td></tr>
											</table>
											</body>
											</html>';
							}else{
								
								$companies = new \App\Models\Companies();
								$tmp = explode("|", $message);
								if (isset($tmp[1])) {
									$subject = $tmp[0].'(Copy)';
									$message = $tmp[1];
								}
								$company_id = $companies->getCompanyIdByPropertyId($id_property);
								$company_name = $companies->getCompanyNameById($company_id[0]['id_companies']);
								$description="<pre>".str_replace('$','&#36;',$transactiondetail[0]['trans_descr'])."</pre>";
								$replacedata = array ("LOGINLINK"=>"<a href=\"" . $org_url[0]['url_clients'] . "/login.php\">" . $org_name[0]['name_clients'] . "</a>","TICKETLINK"=>"<a href=\"".$org_url[0]['url_clients']."/newhelp.php\">contact us</a>","DESCRIPTION"=>$description, "LOGO"=>'<img src="'.$logourl.'">', "DISCLAIMER"=>$disclaimer, "COMPANYNAME"=>$company_name[0]['company_name'], "COMPANYID"=>$id_property, "DBA_NAME"=>$org_name[0]['name_clients'], "FIRSTNAME"=>$user_info[0]['first_name'], "LASTNAME"=>$user_info[0]['last_name'], "ACCOUNTNUMBER"=>$user_info[0]['account_number'], "NETAMOUNT"=>number_format($transactiondetail[0]['trans_net_amount'], 2, ".", ","), "TRANS_DATE"=>$transactiondetail[0]['trans_last_post_date'], "AUTHNUM"=>$transactiondetail[0]['trans_result_auth_code'], "ERRORMSG"=>$error_msg, "new_trans_id"=>$transactiondetail[0]['trans_refnum'], "REFNUM"=>$transactiondetail[0]['trans_refnum']);
								foreach ($replacedata as $key => $value) {
									$message = preg_replace('/\[:'.$key.':\]/', $value, $message);
								}
								
							}
						
						//$eml = 'PPandey@revopay.com';
						$info['from'] = $acco_email;
						$info['to'] = $eml;
						$info['subject'] = $subject;
						$receiptcontentarray = array('receiptcontent' => $message);
						Mail::send('mail.receipttransactionemail', $receiptcontentarray , function($messageinfo) use($info){
							$messageinfo->from($info['from']);
							$messageinfo->to($info['to'])->subject($info['subject']);
						});
						$accountingtransactions->saveReceipt($id_property, $user_info[0]['web_user_id'], $trans_id, $message, 'info@revopayments.com', $eml);
						
					}//end if
					
					return array('error' => '-1' , 'txt'=>'Copy of Payment Receipt was sent');
					
			  }//end inner if
			  
		   }//outer else
		   
		}else{//outer if
			
			 return array('error' => '1' , "I don't have arguments.");
			 
		}
		
		
	}
    
    public function oneClickRemainder($token,$ocCancel,$web_user_id,$id_properties,$trans_id){
        $obj_user= new User();
        $obj_properties= new Properties();
        $obj_trans= new Transations();
        $data= array();
        
        $user_info= $obj_user->getUsrInfo($web_user_id);
        $property_info= $obj_properties->getPropertyInfo($id_properties);
        
       
        
        
        if(empty($user_info['email_address']) || trim($user_info['email_address'])==''){
            $data['to']="customerservice@revopay.com";
        }else{
            $data['to']=$user_info['email_address'];
        }
        
        $data['cancel_token']=$ocCancel;
        $data['token']=$token;
        $data['amount']=$obj_trans->get1TransInfo($trans_id, 'trans_total_amount');
        $data['lastpayment']=$obj_trans->get1TransInfo($trans_id, 'trans_first_post_date');
        $data['time']=date("m-d-Y H:i:s");
        $data['from']="do_not_reply@revopay.com";
        $data['usrName']=$user_info['first_name']." ".$user_info['last_name'];
        $data['propertyName']=$property_info['name_clients'];
        
        Mail::send('mail.oneclick_template', $data, function($message){});
        
    }
    
    public function sendAutoPayExtend($token,$web_user_id,$id_properties){
        $obj_user= new User();
        $obj_property= new Properties();
        
        $first_name=$obj_user->get1UserInfo($web_user_id, 'first_name');
        $last_name=$obj_user->get1UserInfo($web_user_id, 'last_name');
        $usr_email=$obj_user->get1UserInfo($web_user_id, 'email_address');
        
        $merchantname= $obj_property->get1PropertyInfo($id_properties, 'name_clients');
        $usrName = $first_name." ".$last_name;
        $data= array();
        $info['to']=$usr_email;
        $info['from']='info@revopay.com';
        $info['merchantname']=$merchantname;
        $data['token']=$token;
        $data['userName']=$usrName;
        $data['merchantname']=$merchantname;
        
        if(filter_var( $info['to'], FILTER_VALIDATE_EMAIL)){
            Mail::send('mail.autopayex',$data, function($message) use($info){
                $message->from($info['from'],'RevoPay');
                $message->to($info['to'])->subject('Extend Your AutoPay!');
            });
        }
    }
    
    public function NewAutoPayForgotPassw($paymentInfo){
        $obj_user= new User();
        $obj_property= new Properties();
        $nameuser=$obj_user->getFullNameById($paymentInfo['web_user_id']);
        $email=$obj_user->get1UserInfo($paymentInfo['web_user_id'], 'email_address');
        $username=$obj_user->get1UserInfo($paymentInfo['web_user_id'], 'username');
        $merchantName= $obj_property->get1PropertyInfo($paymentInfo['id_property'], 'name_clients');
        $merchantEmail= $obj_property->get1PropertyInfo($paymentInfo['id_property'], 'email_address_clients');
        $mlogo= $obj_property->get1PropertyInfo($paymentInfo['id_property'], 'email_address_clients');
        $ids= $obj_property->getOnlyIds($paymentInfo['id_property']);
        $logo=$obj_property->getPropertyLogo($mlogo, $ids['id_companies'], $ids['id_partners']);
        
        $hostname=config("app.hostName");
        $data=array();
        $data['logo']=$hostname.$logo;
        $obj_property= new Properties();
        $randnum=rand(10000000, 99999999);
        $randnum=$obj_property->getUniqAuthCode($randnum);
        $data['hashlink']=$hostname.'/master/index.php/password/'.\Illuminate\Support\Facades\Crypt::encrypt($paymentInfo['web_user_id'].'|'.$paymentInfo['id_property'].'|'.time().'|'.$randnum.'|'.config('app.appAPIkey'));
        $obj_user->set1UserInfo($paymentInfo['web_user_id'], 'password', $randnum);
        $data['merchantname']=$merchantName;
        $data['name']=$nameuser;
        $data['to']=$email;
        $data['user_name']=$username;
        $data['from']=$obj_property->getCustomerServiceFrom($paymentInfo['id_property'],$ids['id_companies'], $ids['id_partners']);
        
        if(filter_var( $data['to'], FILTER_VALIDATE_EMAIL)){
            Mail::send('mail.qp_autopay',$data, function($message){});
        }
    }
    
    public function sendOpenApiReceipt($data){
        
        $obj_trans = new Transations();
        $transInfo = $obj_trans->getTransInfo($data['TransID']);
        $obj_property= new Properties();
        $obj_user= new User();
        $ids= $obj_property->getOnlyIds($transInfo['property_id']);
        $email_template =array();
        $web_user_id=$transInfo['trans_web_user_id'];
        $property_id=$transInfo['property_id'];
        $transStatus=$transInfo['trans_status'];
        
        $subject ="";
        $htmlTemplate="";
                
        $TemplateSetting = "UNSUCCESSFULEMAIL";
        $TransType = 0;
        /*
         * Type of Transactions "TransType"
         *  0 => Errored or Declined
         *  1 => Approved
         *  2 => Returned
         *  3 => Refunded
         *  4 => Voided
         *  
         */
        
        switch ($transStatus){
            case 1: //Approved or 2Returned
                    if($transInfo['trans_type']==2){ //Returned Transactions 
                        $TemplateSetting = "INSFEMAIL";
                        $TransType = 2;
                    }else{
                        if($transInfo['trans_type']==5){//Refund Transactions
                             $TemplateSetting = "SUCCESSREFUND";
                             $TransType = 3;
                        }else{//Approved
                        $TemplateSetting = "SUCCESSFULEMAIL";
                        $TransType = 1;
                        }}
            break;        
            case 4: //Refund or Void 
                    if($transInfo['trans_type']== 9){ //Voided Transactions
                        $TemplateSetting = "SUCCESSFULEMAIL";
                        $TransType = 4;
                    }
            break;
            
            default : //Declined or Errored Transactions
            break;
        }
        
            if(empty($htmlTemplate)){
                $template = $obj_property->getPropertySettings($transInfo['property_id'], $ids['id_companies'], $ids['id_partners'], $TemplateSetting);
                if(!empty($template)){
                    $vartmp=  explode("|", $template);
                    $subject=$vartmp[0];
                    $htmlTemplate=$vartmp[1];
                }
            }
            
            if(empty($subject)){
                $subject = $obj_property->getPropertySettings($transInfo['property_id'], $ids['id_companies'], $ids['id_partners'], $TemplateSetting.'_SUBJECT');
            }
            
            if(empty($subject) || empty($htmlTemplate)){
                switch ($TransType){
                    case 1: // Approved
                        if(empty($subject)) $subject = "Payment Transaction was Approved";
                        if(empty($htmlTemplate)) $htmlTemplate= \Illuminate\Support\Facades\View::make('mail.paymentApproved')->__toString();
                        break;
                    case 2: // Returned
                        if(empty($subject)) $subject = "Payment Transaction Returned";
                        if(empty($htmlTemplate)) $htmlTemplate= \Illuminate\Support\Facades\View::make('mail.paymentReturned')->__toString();
                        break;
                    case 3: // Refunded
                        if(empty($subject)) $subject = "Payment Transaction Refunded";
                        if(empty($htmlTemplate)) $htmlTemplate= \Illuminate\Support\Facades\View::make('mail.paymentRefunded')->__toString();
                        break;
                    case 4: // Voided
                        if(empty($subject)) $subject = "Payment Transaction was Voided or Refunded";
                        if(empty($htmlTemplate)) $htmlTemplate= \Illuminate\Support\Facades\View::make('mail.paymentApproved')->__toString();
                        break;
                    default : // Errored or Declined
                        if(empty($subject)) $subject = "Payment Transaction was Declined or had errors";
                        if(empty($htmlTemplate)) $htmlTemplate= \Illuminate\Support\Facades\View::make('mail.paymentDeclined')->__toString();
                        break;
                }
            }
        
        $merchant_name=$obj_property->get1PropertyInfo($property_id, 'name_clients');
        
        //SendFrom
        if(isset($data['SendFrom']) && !empty($data['SendFrom'])){
            $from=$data['SendFrom'];
        }else{
            $from = $obj_property->get1PropertyInfo($property_id, 'email_address_clients');
        }
        $from=$this->UnvailableEmail($from);
        if(empty($from)){
            $from="info@revopayments.com";
        }
        
        //SendTo
        if(isset($data['SendTo']) && !empty($data['SendTo'])){
           $usr_email = $data['SendTo'];
        }else{
            $usr_email=$obj_user->get1UserInfo($web_user_id, 'email_address');
        }
        
        //SendName
        if(isset($data['SendName']) && !empty($data['SendName'])){
            $merchant_name = $data['SendName'];
        } 
        
        //Subject
        if(isset($data['Subject']) && !empty($data['Subject'])){
            $subject = $data['Subject'];
        }
        
        //HtmlTemplate
        if(isset($data['HtmlTemplate']) && !empty($data['HtmlTemplate'])){
            $htmlTemplate = $data['HtmlTemplate'];
        }            
        
        $template=$this->ReplacePayOpenAPITemplate($htmlTemplate, $subject, $transInfo);
        $info['to']=$usr_email;
        $info['from']=$from;
        $info['name']=$merchant_name;
        $info['subject'] = $subject;
        $email_template['body']=$template;
        
        if(filter_var($usr_email, FILTER_VALIDATE_EMAIL)){
            Mail::send('mail.genericEmail',$email_template, function($message) use($info){
                $message->from($info['from'],$info['name']);
                $message->to($info['to'])->subject($info['subject']);
            });
        }else{
            return array('errorcode'=> 942,'msg'=>"Invalid User'email address");
        }    
        
        return array('errorcode'=> 1, 'msg'=> 'Successful request');
    } 
    
    public function sendRecOpenApiReceipt($data){
        
        $obj_trans = new Transations();
        $transInfo = $obj_trans->getRecTransData($data['TransID']);
        $obj_property= new Properties();
        $obj_user= new User();
        $ids= $obj_property->getOnlyIds($transInfo['property_id']);
        $email_template =array();
        $web_user_id=$transInfo['trans_web_user_id'];
        $property_id=$transInfo['property_id'];
        $transStatus=$transInfo['trans_status'];
        
        $subject ="";
        $htmlTemplate="";
        
        $TemplateSetting = "SUCCESSFULEMAIL";
                    
        switch ($transStatus){
            case 3: //Completed
                    $TemplateSetting="RECCURRINGENDEMAIL";
            break;        
            case 1: //Active 
            break;
            default : //Cancelled
                $TemplateSetting="UNSUCCESSFULEMAIL";
            break;
        }
        
            if(empty($htmlTemplate)){
                $template = $obj_property->getPropertySettings($transInfo['property_id'], $ids['id_companies'], $ids['id_partners'], $TemplateSetting);
                if(!empty($template)){
                    $vartmp=  explode("|", $template);
                    $subject=$vartmp[0];
                    $htmlTemplate=$vartmp[1];
                }
            }
            
            if(empty($subject)){
                $subject = $obj_property->getPropertySettings($transInfo['property_id'], $ids['id_companies'], $ids['id_partners'], $TemplateSetting.'_SUBJECT');
            }
            
            if(empty($subject) || empty($htmlTemplate)){
                switch ($transStatus){
                    case 1: // Active
                        if(empty($subject)) $subject = "Payment Successfully Schedule";
                        if(empty($htmlTemplate)) $htmlTemplate= \Illuminate\Support\Facades\View::make('mail.paymentApproved')->__toString();
                        break;
                    case 3: // Completed
                        if(empty($subject)) $subject = "Notification: AutoPay Completed";
                        if(empty($htmlTemplate)) $htmlTemplate= \Illuminate\Support\Facades\View::make('mail.paymentApproved')->__toString();
                        break;
                    default : // Cancelled
                        if(empty($subject)) $subject = "Notification: AutoPay Cancelled";
                        if(empty($htmlTemplate)) $htmlTemplate= \Illuminate\Support\Facades\View::make('mail.paymentDeclined')->__toString();
                        break;
                }
            }
        
        $merchant_name=$obj_property->get1PropertyInfo($property_id, 'name_clients');
        
        //SendFrom
        if(isset($data['SendFrom']) && !empty($data['SendFrom'])){
            $from=$data['SendFrom'];
        }else{
            $from = $obj_property->get1PropertyInfo($property_id, 'email_address_clients');
        }
        $from=$this->UnvailableEmail($from);
        if(empty($from)){
            $from="info@revopayments.com";
        }
        
        //SendTo
        if(isset($data['SendTo']) && !empty($data['SendTo'])){
           $usr_email = $data['SendTo'];
        }else{
            $usr_email=$obj_user->get1UserInfo($web_user_id, 'email_address');
        }
        
        //SendName
        if(isset($data['SendName']) && !empty($data['SendName'])){
            $merchant_name = $data['SendName'];
        } 
        
        //Subject
        if(isset($data['Subject']) && !empty($data['Subject'])){
            $subject = $data['Subject'];
        }
        
        //HtmlTemplate
        if(isset($data['HtmlTemplate']) && !empty($data['HtmlTemplate'])){
            $htmlTemplate = $data['HtmlTemplate'];
        }  
        
        $template=$this->ReplaceRecOpenAPITemplate($htmlTemplate, $subject, $transInfo);
        $info['to']=$usr_email;
        $info['from']=$from;
        $info['name']=$merchant_name;
        $info['subject'] = $subject;
        $email_template['body']=$template;
        
        if(filter_var($usr_email, FILTER_VALIDATE_EMAIL)){
            Mail::send('mail.genericEmail',$email_template, function($message) use($info){
                $message->from($info['from'],$info['name']);
                $message->to($info['to'])->subject($info['subject']);
            });
        }else{
            return array('errorcode'=> 942,'msg'=>"Invalid User'email address");
        }    
        
        return array('errorcode'=> 1, 'msg'=> 'Successful request');
    } 
    
    function sendCanceledNotification($web_user_id,$property_id,$trans_id,$reason,$admin=false){
        $obj_property= new Properties();
        $obj_user= new User();
        $obj_trans= new Transations();
        if(!$admin){
            $usr_email=$obj_user->get1UserInfo($web_user_id, 'email_address');
            if(filter_var($usr_email, FILTER_VALIDATE_EMAIL)){
                $merchant_name=$obj_property->get1PropertyInfo($property_id, 'name_clients');
                $user_name=$obj_user->get1UserInfo($web_user_id, 'first_name').' '.$obj_user->get1UserInfo($web_user_id, 'last_name');
                $amount=$obj_trans->get1recurringInfo($trans_id, 'trans_recurring_net_amount');
                $cfee=$obj_trans->get1recurringInfo($trans_id, 'trans_recurring_convenience_fee');
                $tmethod=trim(str_replace(array('(',')')," ",$obj_trans->get1recurringInfo($trans_id, 'trans_card_type')));
                $freq=$obj_trans->get1recurringInfo($trans_id, 'trans_schedule');
                Mail::send('mail.cancelauto',['uname'=>$user_name,'mname'=>$merchant_name,'amount'=>$amount+$cfee,'freq'=>$freq,'method'=>$tmethod,'usr_email'=>$usr_email,'reason'=>$reason], function($message) {
                    $message->from('do_not_reply@revopay.com','Revopay');
                });
            }else{
                return array('errorcode'=> 942,'msg'=>"Invalid User'email address");
            }
        }
        else {
            $eml_email=explode(';',$obj_property->get1PropertyInfo($property_id, 'accounting_email_address_clients'));
            foreach($eml_email as $usr_email){
                if(filter_var($usr_email, FILTER_VALIDATE_EMAIL)){
                    $merchant_name=$obj_property->get1PropertyInfo($property_id, 'name_clients');
                    $user_name=$obj_user->get1UserInfo($web_user_id, 'first_name').' '.$obj_user->get1UserInfo($web_user_id, 'last_name');
                    $amount=$obj_trans->get1recurringInfo($trans_id, 'trans_recurring_net_amount');
                    $cfee=$obj_trans->get1recurringInfo($trans_id, 'trans_recurring_convenience_fee');
                    $tmethod=trim(str_replace(array('(',')')," ",$obj_trans->get1recurringInfo($trans_id, 'trans_card_type')));
                    $freq=$obj_trans->get1recurringInfo($trans_id, 'trans_schedule');
                    Mail::send('mail.cancelautoadmin',['uname'=>$user_name,'mname'=>$merchant_name,'amount'=>$amount+$cfee,'freq'=>$freq,'method'=>$tmethod,'usr_email'=>$usr_email,'reason'=>$reason], function($message) {
                        $message->from('do_not_reply@revopay.com','Revopay');
                    });
                }
            }
        }
        return array('errorcode'=> 1, 'msg'=> 'Successful request');
    }
    
    function sendNotCanceledNotification($web_user_id,$property_id,$trans_id,$reason,$admin=false){
        $obj_property= new Properties();
        $obj_user= new User();
        $obj_trans= new Transations();
        if(!$admin){
            $usr_email=$obj_user->get1UserInfo($web_user_id, 'email_address');
            if(filter_var($usr_email, FILTER_VALIDATE_EMAIL)){
                $merchant_name=$obj_property->get1PropertyInfo($property_id, 'name_clients');
                $user_name=$obj_user->get1UserInfo($web_user_id, 'first_name').' '.$obj_user->get1UserInfo($web_user_id, 'last_name');
                $amount=$obj_trans->get1recurringInfo($trans_id, 'trans_recurring_net_amount');
                $cfee=$obj_trans->get1recurringInfo($trans_id, 'trans_recurring_convenience_fee');
                $tmethod=trim(str_replace(array('(',')')," ",$obj_trans->get1recurringInfo($trans_id, 'trans_card_type')));
                $freq=$obj_trans->get1recurringInfo($trans_id, 'trans_schedule');
                Mail::send('mail.nocancelauto',['uname'=>$user_name,'mname'=>$merchant_name,'amount'=>$amount+$cfee,'freq'=>$freq,'method'=>$tmethod,'usr_email'=>$usr_email,'reason'=>$reason], function($message) {
                    $message->from('do_not_reply@revopay.com','Revopay');
                });
            }else{
                return array('errorcode'=> 942,'msg'=>"Invalid User'email address");
            }
        }
        else {
            $eml_email=explode(';',$obj_property->get1PropertyInfo($property_id, 'accounting_email_address_clients'));
            foreach($eml_email as $usr_email){
                if(filter_var($usr_email, FILTER_VALIDATE_EMAIL)){
                    $merchant_name=$obj_property->get1PropertyInfo($property_id, 'name_clients');
                    $user_name=$obj_user->get1UserInfo($web_user_id, 'first_name').' '.$obj_user->get1UserInfo($web_user_id, 'last_name');
                    $amount=$obj_trans->get1recurringInfo($trans_id, 'trans_recurring_net_amount');
                    $cfee=$obj_trans->get1recurringInfo($trans_id, 'trans_recurring_convenience_fee');
                    $tmethod=trim(str_replace(array('(',')')," ",$obj_trans->get1recurringInfo($trans_id, 'trans_card_type')));
                    $freq=$obj_trans->get1recurringInfo($trans_id, 'trans_schedule');
                    Mail::send('mail.nocancelautoadmin',['uname'=>$user_name,'mname'=>$merchant_name,'amount'=>$amount+$cfee,'freq'=>$freq,'method'=>$tmethod,'usr_email'=>$usr_email,'reason'=>$reason], function($message) {
                        $message->from('do_not_reply@revopay.com','Revopay');
                    });
                }
            }
        }
        return array('errorcode'=> 1, 'msg'=> 'Successful request');
        
    }
    
    public function AdminPinmail($email,$pincode){
        $data= array();
        $to=$email;
        $from = "info@revopayments.com";
        $time= date("m-d-Y H:i:s");
        $data['to']=$to;
        
        $data['time']=$time;
        $data['from']=$from;
        $data['pincode']=$pincode;
        
        Mail::send('mail.mobilepincode', $data, function($message){});
                
    }
    
    /**
     * Send an email as reminder for an Autopayment
     * @param string $web_user_id
     * @param string $property_id
     * @param string $trans_id
     */    
    function sendAutopayReminder($web_user_id, $property_id, $trans_id) {
        $obj_user = new User();
        $user_email = $obj_user->get1UserInfo($web_user_id, 'email_address');
        if (filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
            $obj_property = new Properties();
            $obj_trans = new Transations();
            $obj_partner = new \App\Models\Partners();
            $id_partners = $obj_property->get1PropertyInfo($property_id, 'id_partners');
            $subd_clients = $obj_property->get1PropertyInfo($property_id, 'subdomain_clients');
            $partner = $obj_partner->getDomain($id_partners);
            $hostname = config("app.hostName");
            //@TODO Ask if this is the correct login url to this email
            $loginUrl = $hostname . "/master/index.php/" . $partner . "/properties/" . $subd_clients . '/login';
            $m_name = $obj_property->get1PropertyInfo($property_id, 'name_clients');
            $user_name = $obj_user->get1UserInfo($web_user_id, 'first_name') . ' ' . $obj_user->get1UserInfo($web_user_id, 'last_name');
            $amount = $obj_trans->get1recurringInfo($trans_id, 'trans_recurring_net_amount');
            $cfee = $obj_trans->get1recurringInfo($trans_id, 'trans_recurring_convenience_fee');
            $method = $obj_trans->get1recurringInfo($trans_id, 'trans_card_type');
            $trans_next_post_date = $obj_trans->get1recurringInfo($trans_id, 'trans_next_post_date');
            if (!empty($trans_next_post_date)) {
                $date = date("m-d-Y", strtotime($trans_next_post_date));
            }
            $data = [
                'user_name' => $user_name,
                'm_name' => $m_name,
                'amount' => number_format($amount, 2, '.', ','),
                'cfee' => number_format($cfee, 2, '.', ','),
                'total' => number_format($amount + $cfee, 2, '.', ','),
                'method' => $method,
                'loginUrl' => $loginUrl,
                'date' => $date,
                'user_email' => $user_email
            ];
            Mail::send('mail.autopayReminderEmail', $data, function($message) {
                $message->from('do_not_reply@revopay.com', 'Revopay');
            });
        }
    }

}
