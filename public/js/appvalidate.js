function validate_lname(){
        if(IsEmpty('xlastname')){
            showerror('xlastname','Last name is required');
            return false;
        }
        if(!ValidateText('xlastname')){
            showerror('xlastname','Only letters allowed in this field');
            return false;
        }
        hideerror('xlastname');
        return true;
    }
    
function validate_lname1(){
        if(!IsEmpty('xlastname')){
            if(!ValidateText('xlastname')){
                showerror('xlastname','Only letters allowed in this field');
                return false;
            }
        }
        hideerror('xlastname');
        return true;
    }    
    
function validate_fname(){
        if(IsEmpty('xfirstname')){
            showerror('xfirstname','First name is required');
            return false;
        }
        if(!ValidateAlphaNum('xfirstname')){
            showerror('xfirstname','Only alpha numeric allowed in this field');
            return false;
        }
        hideerror('xfirstname');
        return true;
    }

       function validate_address(){
        if(IsEmpty('xaddress')){
            showerror('xaddress','Address is required');
            return false;
        }
        if(!ValidateAlphaNum('xaddress')){
            showerror('xaddress','Only alpha numeric allowed in this field');
            return false;
        }
        hideerror('xaddress');
        return true;
    }

    function validate_address1(){
        if(!IsEmpty('xaddress')){
            if(!ValidateAlphaNum('xaddress')){
                showerror('xaddress','Only alpha numeric allowed in this field');
                return false;
            }
        }
        hideerror('xaddress');
        return true;
    }

        function validate_addressunit(){
        if(!ValidateAlphaNum('xaddressunit')){
            showerror('xaddressunit','Only alpha numeric allowed in this field');
            return false;
        }
        hideerror('xaddressunit');
        return true;
    }

    function validate_city(){
        if(IsEmpty('xcity')){
            showerror('xcity','City is required');
            return false;
        }
        if(!ValidateText('xcity')){
            showerror('xcity','Only letters allowed in this field');
            return false;
        }
        hideerror('xcity');
        return true;
    }
    
    function validate_city1(){
        if(!IsEmpty('xcity')){
            if(!ValidateText('xcity')){
                showerror('xcity','Only letters allowed in this field');
                return false;
            }
        }
        hideerror('xcity');
        return true;
    }
    
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
        if(!IsEmpty('xzip')){
            if(!ValidateZipNumber('xzip')){
                showerror('xzip','Only numbers allowed in this field');
                return false;
            }
        }
        hideerror('xzip');
        return true;
    }

    function validate_phone(){
        if(IsEmpty('xphone')){
            showerror('xphone','Phone is required');
            return false;
        }
        if(!ValidateUSPhoneNumber('xphone')){
            showerror('xphone','Only numbers allowed in this field');
            return false;
        }
        hideerror('xphone');
        return true;
    }

    function validate_phone1(){
        if(!IsEmpty('xphone')){
            if(!ValidateUSPhoneNumber('xphone')){
                showerror('xphone','Only numbers allowed in this field');
                return false;
            }
        }
        hideerror('xphone');
        return true;
    }


    function validate_email(){
        if(IsEmpty('xemail')){
            showerror('xemail','Email is required');
            return false;
        }
        if(!ValidateEmail('xemail')){
            showerror('xemail','Only a valid email address allowed in this field');
            return false;
        }
        hideerror('xemail');
        return true;
    }
    
    function validate_email1(){
        if(!IsEmpty('xemail')){
            if(!ValidateEmail('xemail')){
                showerror('xemail','Only a valid email address allowed in this field');
                return false;
            }
        }
        hideerror('xemail');
        return true;
    }

    
    function validate_state(){
        if(IsEmpty('xstate')){
            showerrorattr('xstate','State is required');
            return false;
        }
        hideerrorattr('xstate');
        return true;
    }
    
    function validate_inv(){
        if(IsEmpty('xqinv')){
            if(xsi==2 || xsi==3){ 
                showerror('xqinv','Invoice number is required');
                return false;
            }
            return true;
        }
        if(!ValidateAlphaNum('xqinv')){
            showerror('xqinv','Only alpha numeric allowed in this field');
            return false;
        }
        hideerror('xqinv');
        return true;
    }
    
     function validate_acc(){
        if(IsEmpty('xqacc')){
            if(xsa==2 || xsa==3){ 
                showerror('xqacc','Account number is required');
                return false;
            }
            return true;
        }
        if(!ValidateAlphaNum('xqacc')){
            showerror('xqacc','Only alpha numeric allowed in this field');
            return false;
        }
        hideerror('xqacc');
        return true;
    }
    
    function validate_memo(){
        if(IsEmpty('xmemo')){
            hideerror('xmemo');
            return true;
        }
        if(!ValidateAlphaNum('xmemo')){
            showerror('xmemo','Only alphanumeric or these special characters allowed ( - _ . @ )');
            return false;
        }
        hideerror('xmemo');
        return true;
    }
    
    function validate_memo1(){
        if(IsEmpty('xmemo1')){
            hideerror('xmemo1');
            return true;
        }
        if(!ValidateAlphaNum('xmemo1')){
            showerror('xmemo1','Only alphanumeric or these special characters allowed ( - _ . @ )');
            return false;
        }
        hideerror('xmemo1');
        return true;
    }
     
    