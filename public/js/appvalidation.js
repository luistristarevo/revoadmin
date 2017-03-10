var lowercase= new RegExp('[a-z]');
var uppercase= new RegExp('[A-Z]');
var anycase= new RegExp('[A-z]');
var special = new RegExp('([@,#,!,_,$])');
var numbers = new RegExp('[0-9]');
var non_special = new RegExp('([&,%,^,*,?,~,.,?,/,+,-,|,<,>])');
var semi_special = new RegExp('([&,%,^,*,#,!,$,?,~,?,/,+,|,<,>])');
var ex_special = new RegExp('([%,^,*,#,!,$,?,~,?,/,+,|,<,>])');

function ValidateSemiText(opc){
    var field = document.getElementById(opc);
    if(typeof field == "undefined")return true;
    if(field==null)return true;
    if(field.value.length==0)return true;
    if($.trim(field.value)=='')return true;
    if(semi_special.test(field.value))return false;
    return true;
}

function ValidateText(opc){
    var field = document.getElementById(opc);
    if(typeof field == "undefined")return true;
    if(field==null)return true;
    if(field.value.length==0)return true;
    if($.trim(field.value)=='')return true;
    if(special.test(field.value))return false;
    if(non_special.test(field.value))return false;
    if(numbers.test(field.value))return false;
    return true;
}

function ValidateAlphaNum(opc){
    var field = document.getElementById(opc);
    if(typeof field == "undefined")return true;
    if(field==null)return true;
    if(field.value.length==0)return true;
    if($.trim(field.value)=='')return true;
    var letters = /^[0-9a-zA-Z,.,@,\-,_]+$/;
    var newStr = field.value.replace(/\s+/g, '');
    if(newStr.match(letters)) return true;
    return false;
}

function ValidateAlphaNumEx(opc){
    var field = document.getElementById(opc);
    if(typeof field == "undefined")return true;
    if(field==null)return true;
    if(field.value.length==0)return true;
    if($.trim(field.value)=='')return true;
    var letters = /^[0-9a-zA-Z,&,#,.,@]+$/;
    var newStr = field.value.replace(/\s+/g, '');
    if(newStr.match(letters)) return true;
    return false;
}

function ValidateNumValue(opc){
    var nrRegExp = /^([0-9]+(\.[0-9]{1,2})?)$|^(\.[0-9]{1,2})$/;
    var field = document.getElementById(opc);
    if(typeof field == "undefined")return true;
    if(field==null)return true;
    if(field.value.length==0)return true;
    if($.trim(field.value)=='')return true;
    return nrRegExp.test(field.value);
}

function ValidateEmail(opc){
    var field = document.getElementById(opc);
    var RegExp = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if(typeof field == "undefined")return true;
    if(field==null)return true;
    if(field.value.length==0)return true;
    if($.trim(field.value)=='')return true;
    return RegExp.test(field.value);
}

function ValidateZipNumber(opc) {
    var field = document.getElementById(opc);
    var zipRegExp = /^[0-9]{5}(-[0-9]{4})?$/;
    if(typeof field == "undefined")return true;
    if(field==null)return true;
    if(field.value.length==0)return true;
    if($.trim(field.value)=='')return true;
    return zipRegExp.test(field.value);
}

function ValidateUSPhoneNumber(opc) {
    var field = document.getElementById(opc);
    if(typeof field == "undefined")return true;
    if(field==null)return true;
    if(field.value.length == 0) return true;
    var phoneRegExp = /^([0-9]{3}-[0-9]{3}-[0-9]{4})?$/;
    var s1=phoneRegExp.test(field.value);
    if(s1)return true;
    var phoneRegExp1 = /^([0-9]{10})?$/;
    var s2=phoneRegExp1.test(field.value);
    return s2;
}

function ValidatePassword(opc){
    var field = document.getElementById(opc);
    var pwd= field.value;
    var lowercase= new RegExp('[a-z]');
    var uppercase= new RegExp('[A-Z]');
    var special = new RegExp('([@,#,!,_,$])');
    var numbers = new RegExp('[0-9]');
    var non_special = new RegExp('([&,%,^,*,?,~,.,?,/,+,-,|,<,>])');
        var size=false;
        var begin=false;
        var numc =false; 
        var specialc=false;
        var uppc =false;
        var lowc= false;

        if(pwd.length<8 || pwd.length>20){size=false;}else {size=true;}
        if(lowercase.test(pwd[0]) || uppercase.test(pwd[0]) || numbers.test(pwd[0])){begin=true; }else {begin=false;}
        if(uppercase.test(pwd)){uppc=true; }else {uppc=false;}
        if(lowercase.test(pwd)){lowc=true; }else {lowc=false;}
        if(numbers.test(pwd)){numc=true;}else {numc=false;}
        if(special.test(pwd)){specialc=true;}else {specialc=false;}

    if(size && begin && numc && specialc && uppc && lowc && !non_special.test(pwd)){
            return true;
        }else {
             return false;
        }       
 }
 
 function ValidateCVV(opc) {
                var field = document.getElementById(opc);
                var cvv= field.value;
	if (cvv.length < 3 || cvv.length > 4){
		return false;
            }
	else {
		return numbers.test(cvv);
            }
}

function ValidateExpDate(opc){
                var field = document.getElementById(opc);
                var exdate= field.value;
	var month, year, presentyear, presentmonth;
	var d = new Date();
	
	if (exdate.length != 4)
		return false;
		
	month = parseInt(exdate.substr(0,2),10);
	year = parseInt(exdate.substr(2,2),10);
	presentyear = d.getFullYear();
	presentyear = presentyear % 100;
	presentmonth = d.getMonth();
	presentmonth++;
	
	if (month > 12 || month < 1)
		return false;
	
	if (year < presentyear || (year == presentyear && month < presentmonth)) 
		return false;
		
	return true;

}

function ValidateUSBankAccount(opc){
                var field = document.getElementById(opc);
                var bank= field.value;
                if(bank.length>17)return false;
                if(bank.length<2)return false;
                return numbers.test(bank);
}

function ValidateCCNumber(opc) {  // v2.0
                var field = document.getElementById(opc);
                var ccNumb= field.value;
	var valid = "0123456789"  // Valid digits in a credit card number
	var len = ccNumb.length;  // The length of the submitted cc number
	var iCCN = parseInt(ccNumb);  // integer of ccNumb
	var sCCN = ccNumb.toString();  // string of ccNumb
	sCCN = sCCN.replace (/^\s+|\s+$/g,'');  // strip spaces
	var iTotal = 0;  // integer total set at zero
	var bNum = true;  // by default assume it is a number
	var bResult = false;  // by default assume it is NOT a valid cc
	var temp;  // temp variable for parsing string
	var calc;  // used for calculation of each digit

	// Determine if the ccNumb is in fact all numbers
	for (var j=0; j < len; j++) {
		temp = "" + sCCN.substring(j, j+1);
		if (valid.indexOf(temp) == "-1") {
			bNum = false;
		}
	}

	// if it is NOT a number, you can either alert to the fact, or just pass a failure
	if (!bNum) {
		/*alert("Not a Number");*/
		bResult = false;
	}

	// Determine if it is the proper length 
	if ((len == 0) && (bResult)) {  // nothing, field is blank AND passed above # check
		bResult = false;
	} else {  // ccNumb is a number and the proper length - let's see if it is a valid card number
		if (len >= 15) {  // 15 or 16 for Amex or V/MC
			for (var i=len; i > 0; i--) {  // LOOP throught the digits of the card
				calc = parseInt(iCCN) % 10;  // right most digit
				calc = parseInt(calc);  // assure it is an integer
				iTotal += calc;  // running total of the card number as we loop - Do Nothing to first digit
				i--;  // decrement the count - move to the next digit in the card
				iCCN = iCCN / 10;                               // subtracts right most digit from ccNumb
				calc = parseInt(iCCN) % 10 ;    // NEXT right most digit
				calc = calc *2;                                 // multiply the digit by two
				// Instead of some screwy method of converting 16 to a string and then parsing 1 and 6 and then adding them to make 7,
				// I use a simple switch statement to change the value of calc2 to 7 if 16 is the multiple.
				switch (calc) {
					case 10: calc = 1; break;       //5*2=10 & 1+0 = 1
					case 12: calc = 3; break;       //6*2=12 & 1+2 = 3
					case 14: calc = 5; break;       //7*2=14 & 1+4 = 5
					case 16: calc = 7; break;       //8*2=16 & 1+6 = 7
					case 18: calc = 9; break;       //9*2=18 & 1+8 = 9
					default: calc = calc;           //4*2= 8 &   8 = 8  -same for all lower numbers
				}                                               
				iCCN = iCCN / 10;  // subtracts right most digit from ccNum
				iTotal += calc;  // running total of the card number as we loop
			}  // END OF LOOP
		
			if ((iTotal%10) == 0) {  // check to see if the sum Mod 10 is zero
				bResult = true;  // This IS (or could be) a valid credit card number.
			} else {
				bResult = false;  // This could NOT be a valid credit card number
			}
		}
	}
	
	return bResult; // Return the results
}

function ValidateABANumber(opc) { //v2.0
                var field = document.getElementById(opc);
                var aba= field.value;
	var valid = "0123456789";
	var len = aba.length;
	var bNum = true;
	var iABA = parseInt(aba);
	var sABA = aba.toString();
	var url = "abaDisplay2.asp?aba=" + sABA;
	var iTotal = 0;
	var bResult = false;
	var temp;
	var first_two = aba.substr(0,2);
	
	/* El routing number tiene que estar en alguno de estos intervalos:
	 *	01-15
	 *	21-32
	 *	61-72
	 */
	first_two = parseInt(first_two, 10);
	if (!((first_two >= 01 && first_two <= 15) || (first_two >= 21 && first_two <= 32) || (first_two >= 61 && first_two <= 72))) {
		return false;
	}
		
	//alert(aba);
	for (var j=0; j<len; j++) {
		temp = "" + aba.substring(j, j+1);
		if (valid.indexOf(temp) == "-1") 
			bNum = false;
	}
  
	if (!bNum) {
		bResult = false;
		//alert("Not a Number");
	}
  
	if(len !=0) {  // incase they omit the number entirely.
		if (len != 9) {
			bResult = false;
			//alert("This is not a proper ABA length");
		} else {
			for (var i=0; i<len; i += 3) {
				iTotal += parseInt(sABA.charAt(i),     10) * 3
						+ parseInt(sABA.charAt(i + 1), 10) * 7
						+ parseInt(sABA.charAt(i + 2), 10);
			}
			if (iTotal != 0 && iTotal % 10 == 0) {
				bResult = true;
				// used for AJAX posting of data
				// get(this.parentNode);
			} else {
				//alert("This is NOT a valid ABA Routing Number!");
				bResult = false;
			}
		}
  } else {
    // zero length do nothing
  }
  return bResult;
}


function IsEmpty(opc){
    var field = document.getElementById(opc);
    if(typeof field != "undefined"){
        if(field!=null){
            if(field.value.length==0)return true;
            if($.trim(field.value)=='')return true;
            return false;
        }
        else {
            return false;
        }
    }
    else {
        return false;
    }
}

function showerror(opc,msg){
    $('#'+opc).tooltip('destroy'); 
    if(msg!=''){
        $("#"+opc).attr('data-original-title',msg);
    }
    $("#"+opc).removeClass('tooltip_input');
    $("#"+opc).addClass('tooltip_input_error');
    $("#"+opc).addClass('input-error');
    refreshErrorTooltip();
    $("#"+opc).tooltip('show');
}

function showerrorattr(opc,msg){
    $("[data-id*='"+opc+"']").tooltip('destroy'); 
    if(msg!=''){
        $("[data-id*='"+opc+"']").attr('data-original-title',msg);
    }
    $("[data-id*='"+opc+"']").removeClass('tooltip_input');
    $("[data-id*='"+opc+"']").addClass('tooltip_input_error');
    $("[data-id*='"+opc+"']").addClass('input-error');
    refreshErrorTooltip();
    $("[data-id*='"+opc+"']").tooltip('show');
}

function refreshErrorTooltip(){
    $('.tooltip_input_error').tooltip({
    placement: "top",
    template: '<div class="tooltip tooltip-error"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>'
});
$('.tooltip_input_error').click(function(){
    $(this).tooltip('show');
});
$('.tooltip_input_error').blur(function(){
    $(this).tooltip('show');
});
}

function hideerror(opc){
    $("#"+opc).attr('data-original-title',"");
    $("#"+opc).removeClass('tooltip_input_error');
    $("#"+opc).removeClass('input-error');
    $("#"+opc).tooltip('hide');
}

function hideerrorattr(opc){
    $("[data-id*='"+opc+"']").attr('data-original-title',"");
    $("[data-id*='"+opc+"']").removeClass('tooltip_input_error');
    $("[data-id*='"+opc+"']").removeClass('input-error');
    $("[data-id*='"+opc+"']").tooltip('hide');
}

function isAmexCardType(opc) {
    var field = document.getElementById(opc);
    var num= field.value;
    var st = '^3[47][0-9]{13}$'; 
    if (num.match(st) != null)
	return true;
    else	
	return false;			
}