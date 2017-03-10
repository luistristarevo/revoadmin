function changePassword1(){
    if(!passRequirements()) return false;
    if(!confirmPass()) return false;
    $("#xidform").submit();
}

function changePassword(){
    if(!passRequirements()) return false;
    if(!confirmPass()) return false;
    $("#xforgotp").submit();
}

function changePasswordRegistration(){
     if(!passRequirements()) return false;
    if(!confirmPass()) return false;
    $("#xformff").submit();
}

function passRequirements(){
              
                    var pwd= $("#xpassword").val();
                    
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
                    
                    if(size){
                        $("#xsize").css("color", "green");
                        $("#xspan_size").removeClass("fa-close");
                        $("#xspan_size").removeClass("fa-red");
                        $("#xspan_size").addClass("fa-check");
                        $("#xspan_size").addClass("fa-green");
                    }else{
                        $("#xsize").css("color", "#D22C15");
                        $("#xspan_size").removeClass("fa-check");
                        $("#xspan_size").removeClass("fa-green");
                        $("#xspan_size").addClass("fa-close");
                        $("#xspan_size").addClass("fa-red");
                    }
                    
                    if(begin && pwd.length>0){
                        $("#xbegin").css("color", "green");
                        $("#xspan_begin").removeClass("fa-close");
                        $("#xspan_begin").removeClass("fa-red");
                        $("#xspan_begin").addClass("fa-check");
                        $("#xspan_begin").addClass("fa-green");
                    }else{
                        $("#xbegin").css("color", "#D22C15");
                        $("#xspan_begin").addClass("fa-close");
                        $("#xspan_begin").addClass("fa-red");
                        $("#xspan_begin").removeClass("fa-check");
                        $("#xspan_begin").removeClass("fa-green");
                    }
                    
                    if(uppc){
                        $("#xuppercase").css("color", "green");
                        $("#xspan_uppercase").removeClass("fa-close");
                        $("#xspan_uppercase").removeClass("fa-red");
                        $("#xspan_uppercase").addClass("fa-check");
                        $("#xspan_uppercase").addClass("fa-green");
                    }else{
                        $("#xuppercase").css("color", "#D22C15");
                        $("#xspan_uppercase").addClass("fa-close");
                        $("#xspan_uppercase").addClass("fa-red");
                        $("#xspan_uppercase").removeClass("fa-check");
                        $("#xspan_uppercase").removeClass("fa-green");
                    }
                    
                    if(lowc){
                        $("#xlowercase").css("color", "green");
                        $("#xspan_lowercase").removeClass("fa-close");
                        $("#xspan_lowercase").removeClass("fa-red");
                        $("#xspan_lowercase").addClass("fa-check");
                        $("#xspan_lowercase").addClass("fa-green");
                    }else{
                        $("#xlowercase").css("color", "#D22C15");
                        $("#xspan_lowercase").addClass("fa-close");
                        $("#xspan_lowercase").addClass("fa-red");
                        $("#xspan_lowercase").removeClass("fa-check");
                        $("#xspan_lowercase").removeClass("fa-green");
                    }
                    
                    if(numc){
                        $("#xnumber").css("color", "green");
                        $("#xspan_number").removeClass("fa-close");
                        $("#xspan_number").removeClass("fa-red");
                        $("#xspan_number").addClass("fa-check");
                        $("#xspan_number").addClass("fa-green");
                    }else{
                        $("#xnumber").css("color", "#D22C15");
                        $("#xspan_number").addClass("fa-close");
                        $("#xspan_number").addClass("fa-red");
                        $("#xspan_number").removeClass("fa-check");
                        $("#xspan_number").removeClass("fa-green");
                    }
                    
                    if(specialc && !non_special.test(pwd)){
                        $("#xspecial").css("color", "green");
                        $("#xspan_special").removeClass("fa-close");
                        $("#xspan_special").removeClass("fa-red");
                        $("#xspan_special").addClass("fa-check");
                        $("#xspan_special").addClass("fa-green");
                    }else{
                        $("#xspecial").css("color", "#D22C15");
                        $("#xspan_special").addClass("fa-close");
                        $("#xspan_special").addClass("fa-red");
                        $("#xspan_special").removeClass("fa-check");
                        $("#xspan_special").removeClass("fa-green");
                    }
                    
                if(size && begin && numc && specialc && uppc && lowc && !non_special.test(pwd)){
                        $("#xpassword").css("border-color","green"); 
                        var pwd2=$("#xpassword2").val();
                        if(pwd!==pwd2){
                            $("#xpassword2").css("border-color","red");
                            $("#xupdate").prop("disabled",true);
                        }else{
                            $("#xpassword2").css("border-color","green");
                            $("#xupdate").prop("disabled",false); 
                        } 
                        return true;
                    }else {
                         $("#xpassword").css("border-color","red");
                         $("#xpassword2").css("border-color","red");
                         $("#xupdate").prop("disabled",true);
                         return false;
                         
                    }       
                }
                
                function confirmPass(){
                    var pwd= $("#xpassword").val();
                    var pwd2=$("#xpassword2").val();
                    if(pwd===pwd2){
                        $("#xpassword2").css("border-color","green");
                        if(passRequirements()){
                           $("#xupdate").attr("disabled",false); 
                        }else{
                             $("#xupdate").attr("disabled",true);
                        }
                    }else{
                        $("#xpassword2").css("border-color","red");
                        $("#xupdate").attr("disabled",true);
                    }
                    return true;
                }
		
                  
		function passForm() {
						
			if ($.trim($("#xpassword").val()) == "") {
				$("#alert_msg").html("Please input your password. Thank you.");	
				$("#myModal").modal();
				return false;
			}
                        if ($.trim($("#xpassword2").val()) == "") {
				$("#alert_msg").html("Please input your password confirmation. Thank you.");	
				$("#myModal").modal();
				return false;
			}
			 var pwd1=$("#xpassword").val(); 
                         var pwd2=$("#xpassword2").val();
                        if (pwd1!= pwd2 ){
                            $("#alert_msg").html("These passwords do not match. Please try again.");	
                            $("#myModal").modal();
                            return false;
                        }
                        
                        if(!passRequirements()){
                            $("#alert_msg").html("The password should be between 8-20 characters.  It must include at least one of each: uppercase letter, lowercase letter, number and special character (choose from: #, $, @, _, !).");	
                            $("#myModal").modal();
                            return false;
                        }       
                        
                        var gresponse=$("#g-recaptcha-response").val();
                        if(gresponse==""){
                            setModalValues ("Please confirm that you are not a robot by checking the box!", "", true);
                            $('#myModal').modal();
                            return false;
                        }
                        
                        $("#loginForm").submit();
			 	
		}
		 
