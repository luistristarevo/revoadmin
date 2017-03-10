function validate_zip(){
        if(IsEmpty('xzip')){
            showerror('xzip','Zip is required');
            return false;
        }
        if(!ValidateZipNumber('xzip')){
            showerror('xzip','Only numbers allowed in this field');
            return false;
        }
        hideerror('xzip');
        return true;
    }

function validate_zip1(){
        if(IsEmpty('xzip1')){
            showerror('xzip1','Zip is required');
            return false;
        }
        if(!ValidateZipNumber('xzip1')){
            showerror('xzip1','Only numbers allowed in this field');
            return false;
        }
        hideerror('xzip1');
        return true;
    }

function validate_expdate(){
        if(IsEmpty('xexpdate')){
            showerror('xexpdate','Expiration Date is required');
            return false;
        }
        if(!ValidateExpDate('xexpdate')){
            showerror('xexpdate','Only a valid expiration date (MMYY) allowed in this field');
            return false;
        }
        hideerror('xexpdate');
        return true;
    }    
    
function validate_ccname(){
        if(IsEmpty('xcardname')){
            showerror('xcardname','Cardholder name is required');
            return false;
        }
        if(!ValidateText('xcardname')){
            showerror('xcardname','Only letters allowed in this field');
            return false;
        }
        hideerror('xcardname');
        return true;
    }      
    
function validate_ecname(){
        if(IsEmpty('xppec_name')){
            showerror('xppec_name','Account holder name is required');
            return false;
        }
        if(!ValidateText('xppec_name')){
            showerror('xppec_name','Only letters allowed in this field');
            return false;
        }
        hideerror('xppec_name');
        return true;
}    
  
function validate_aba(){
        if(IsEmpty('xppec_routing')){
            showerror('xppec_routing','Routing number is required');
            return false;
        }
        if(!ValidateABANumber('xppec_routing')){
            showerror('xppec_routing','Only a valid routing number allowed in this field');
            return false;
        }
        hideerror('xppec_routing');
        return true;
}

function validate_bank(){
        if(IsEmpty('xppec_acc')){
            showerror('xppec_acc','Bank account number is required');
            return false;
        }
        if(!ValidateUSBankAccount('xppec_acc')){
            showerror('xppec_acc','Only a valid account number (2-17 digits) allowed in this field');
            return false;
        }
        hideerror('xppec_acc');
        if(IsEmpty('xppec_confirm_acc')){
            showerror('xppec_confirm_acc','Bank account number is required');
            return false;
        }
        if(!ValidateUSBankAccount('xppec_confirm_acc')){
            showerror('xppec_confirm_acc','Only a valid account number (2-17 digits) allowed in this field');
            return false;
        }
        if($("#xppec_acc").val()!=$("#xppec_confirm_acc").val()){
            showerror('xppec_confirm_acc','Bank Account number does not match');
            return false;
        }
        hideerror('xppec_confirm_acc');
        
        return true;
}

function validate_ccnumber(){
        if(IsEmpty('xcardnumber')){
            showerror('xcardnumber','Card number is required');
            return false;
        }
        if(!ValidateCCNumber('xcardnumber')){
            showerror('xcardnumber','Only a valid card number allowed in this field');
            return false;
        }
        
        hideerror('xcardnumber');
        return true;
    }    
    
    function validate_cvv(){
        if(IsEmpty('xcvv')){
            showerror('xcvv','Card number is required');
            return false;
        } 
        if(!ValidateCVV('xcvv')){
            showerror('xcvv','Only a valid cvv number allowed in this field');
            return false;
        }
        hideerror('xcvv');
        return true;
    }
    
    function validate_ccard(){
        if(!validate_ccnumber() || !acceptAmex())
            return false;
        return true;
    }
    
    function acceptAmex(){
    if(isrecurring!=null && isrecurring){
            if(rc_fee_amex.length==0){
                if(isAmexCardType('xcardnumber')){
                showerror('xcardnumber','Sorry, cannot pay with American Express');
                return false;
            }
        }
    }else if(isrecurring!=null && !isrecurring){
        if(ot_fee_amex.length==0){
            if(isAmexCardType('xcardnumber')){
                showerror('xcardnumber','Sorry, cannot pay with American Express');
                return false;
            }
        }
    }
    hideerror('xcardnumber');
    return true
}
