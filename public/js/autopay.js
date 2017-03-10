function showCat(params){

    $("#xhide_autopaycat").val(params);
    $("#myModal_loading").modal();
    var txurl="editautocat/"+xatoken+"/"+params;
    $.ajax({
            url:xurl+txurl
            //url:'http://localhost:8000/api2/autopay/'+txurl
           }).done(function(data){
                $("#body_autopayCat").html(data);
                CalculateAmount();
                $("#myModal_loading").modal('hide'); 
                $("#myModal_autopayCat").modal();
    });
}

function showFreq(params){
    $("#xhide_autopayfreq").val(params); 
    $("#myModal_loading").modal(); 
    var txurl="editautofreq/"+xatoken+"/"+params;
    $.ajax({
            url:xurl+txurl
           //url:'http://localhost:8000/api2/autopay/'+txurl
           }).done(function(data){
                $("#body_autopayFreq").html(data);
                $(".selectpicker").selectpicker();
                $("#myModal_loading").modal('hide'); 
                $("#myModal_autopayFreq").modal();
    });
}

function showMethod(params){
    $("#xhide_autopaymethod").val(params);
   $("#myModal_loading").modal(); 
   var txurl="editautometh/"+xatoken+"/"+params;
    $.ajax({
            url:xurl+txurl
            //url:'http://localhost:8000/api2/autopay/'+txurl
           }).done(function(data){
                $("#xbody_autopayMethod").html(data);
                $(".selectpicker").selectpicker();
                $("#myModal_loading").modal('hide'); 
                $("#myModal_autopayMethod").modal();
    });
}

function cancelAutopay(id){
    $("#xcancelautopay").val(id);
    $('#myModal_confirm').modal();
}

function ConfirmCancel(){
   var params=$("#xcancelautopay").val();
   $("#myModal_confirm").modal('hide'); 
   $("#myModal_loading").modal(); 
   var txurl="cancelautopay/"+xatoken+"/"+params;
    $.ajax({
           url:xurl+txurl
           }).done(function(data){
            if(data.response==1){ //cancelled
                $("#xpopupheader").html("Autopay");
                $("#xpopupcontent").html(data.responsetext);
                $("#myModal_loading").modal("hide"); 
                $("#myModal_success").modal();
                $('#myModal_success').on('hide.bs.modal',function refresh(){
                   window.location.reload();
               });
            }else{ //get error
                $("#xpopupheader").html("Error");
                $("#xpopupcontent").html(data.responsetext);
                $("#myModal_loading").modal("hide"); 
                $("#myModal_success").modal();
                $('#myModal_success').on('hide.bs.modal',function refresh(){
                   window.location.reload();
               });
            }
    });
}

function showAutoPayDetails(){
    $("#myModal_autopayDetails").modal();
}

function saveAutoCategories(){
    var params= $("#xhide_autopaycat").val();
    var total_amount=0;
    var txurl="savecategories/"+xatoken+"/";
    var categories=[];
    for (var i=0; tmp_obj = document.getElementById("xcheckpay_" + i); i++) {
        if (tmp_obj.checked) {
            tmp_obj = document.getElementById("xinputpay_" + i);
            tmp = tmp_obj.value.replace(/,/g, "");
            tmp = parseFloat(tmp);
            if (!isNaN(tmp) && tmp > 0) {
                total_amount+=parseFloat(tmp);
                var xname=$("#xinputpay_"+i).attr("xname");
                var xid=tmp_obj.name;
                categories.push({'amount':tmp,'id':xid,'name':xname});
            }
        }
    }
    if(total_amount<=0){
        $("#xerror").show();
        return false;
    }
    $("#myModal_autopayCat").modal('hide');
    $("#myModal_loading").modal();
    var xcontent= {'trans_id':params};
    xcontent.total_amount=total_amount;
    xcontent.categories=categories;
    txurl+=JSON.stringify(xcontent);
    
    $.ajax({
            url:xurl+txurl
           }).done(function(data){
                $("#xpopupheader").html("Autopay");
                $("#xpopupcontent").html(data.responsetext);
                $("#myModal_loading").modal("hide"); 
                $("#myModal_success").modal();
                $('#myModal_success').on('hide.bs.modal',function refresh(){
                   window.location.reload();
               });
    });
}

function saveAutoFrequence(){
    $("#myModal_autopayFreq").modal('hide');
    $("#myModal_loading").modal();
    var params= $("#xhide_autopayfreq").val();
    var txurl="savefrequence/"+xatoken+"/";
     
    var xcontent= {'trans_id':params};
    xcontent.day=$("#xday").val(); 
    xcontent.freq=$("#xfreq").val();
    xcontent.start_date=$("#xstartdate").val();
    xcontent.end_date=$("#xenddate").val();
    
    txurl+=JSON.stringify(xcontent);
    
    $.ajax({
            url:xurl+txurl
           }).done(function(data){
                $("#xpopupheader").html("Autopay");
                $("#xpopupcontent").html(data.responsetext);
                $("#myModal_loading").modal("hide"); 
                $("#myModal_success").modal();
                $('#myModal_success').on('hide.bs.modal',function refresh(){
                   window.location.reload();
               });
    });
}

function CalculaFeeX(check){
    $("#xalertdiv").show();
    $("#xerror").hide();
    //here it use this function tu re-use a component who has this function defined
    // and it'll use to select which payment method gonna be apply
    switch (check){
        case 3:
                if($("#xselect_paymethod").is(":checked")){
                    $(".styled").prop("checked",false);
                    $("#xselect_paymethod").prop("checked",true);
                }else
                $(".styled").prop("checked",false); break;
        case 2:
                if($("#checkbox5").is(":checked")){
                    $(".styled").prop("checked",false);
                    $("#checkbox5").prop("checked",true);
                }else
                $(".styled").prop("checked",false); break;
        case 1:
                if($("#checkbox4").is(":checked")){
                    $(".styled").prop("checked",false);
                    $("#checkbox4").prop("checked",true);
                }else
                $(".styled").prop("checked",false); break;
        default : break;
    }
}

function saveAutopayMethod(){
    var isgood=true;
    var txurl="saveautopay_method/"+xatoken+"/";
    var params= $("#xhide_autopaymethod").val();
    var xcontent= {'trans_id':params};
    if($("#xselect_paymethod").is(":checked")){//id_profile
        txurl+='prf/';
        xcontent.profile_id=$("#xselected_profile").val();
    }else if($("#checkbox4").is(":checked")){ //ec
        //validation
        if(!validate_ecname()){
            isgood=false;
        }
        if(!validate_aba()){
            isgood=false;
        }
        if(!validate_bank()){
            isgood=false;
        }
        if(!isgood)return;
        txurl+='ec/';
        xcontent.ec_account_holder = $("#xppec_name").val();
        xcontent.ec_account_lholder='';
        xcontent.ec_routing_number=$("#xppec_routing").val();
        xcontent.ec_account_number=$("#xppec_acc").val();
        xcontent.ec_checking_savings = $("#xppec_type").val();
    }else if($("#checkbox5").is(":checked")){//cc
        
        //validation
        if(!validate_ccname()){
            isgood=false;
        }
        if(!validate_ccard()){
            isgood=false;
        }
        if(!validate_cvv()){
            isgood=false;
        }
        
        if(!validate_expdate()){
            isgood=false;
        }
        if(!validate_zip1()){
            isgood=false;
        }
        if(!isgood) return;
        txurl+='cc/';
        
        xcontent.ccname = $("#xcardname").val();
        xcontent.ccnumber=$("#xcardnumber").val();
        xcontent.ccexp= $("#xexpdate").val();
        xcontent.cvv=$("#xcvv").val();
        xcontent.zip=$("#xzip1").val();
    }else{
        $("#xerror").show();
        return false;
    }
    $("#myModal_autopayMethod").modal('hide');
    $("#myModal_loading").modal();
    
    txurl+=JSON.stringify(xcontent);
    $.ajax({
            url:xurl+txurl
           }).done(function(data){
               if(data.response==1){ //changed
                $("#xpopupheader").html("Autopay");
                $("#xpopupcontent").html(data.responsetext);
                $("#myModal_loading").modal("hide"); 
                $("#myModal_success").modal();
                $('#myModal_success').on('hide.bs.modal',function refresh(){
                   window.location.reload();
               });
            }else{ //get error
                $("#xpopupheader").html("Error");
                $("#xpopupcontent").html(data.responsetext);
                $("#myModal_loading").modal("hide"); 
                $("#myModal_success").modal();
                $('#myModal_success').on('hide.bs.modal',function refresh(){
                   window.location.reload();
               });
            }
               
    });
}

function CalculateFee(type){
    var amount=parseFloat(CalculateAmount());
    if(amount==0){
        $("#xservice_fee").hide();
        $("#xtotal").html("$0.00");
    }
    var fee=0;
    var flat_fee=0
    var porcent_fee=0
    
        if(type=='ec'){
                if(rc_fee_ec.length==1){
                    fee+=parseFloat(rc_fee_ec[0]['convenience_fee']);
                    flat_fee=parseFloat(rc_fee_ec[0]['convenience_fee']);
                    porcent_fee=rc_fee_ec[0]['convenience_fee_float'];
                    if(rc_fee_ec[0]['convenience_fee_float']>0){
                        var ffee=(amount*rc_fee_ec[0]['convenience_fee_float'])/100;
                        fee=fee+parseFloat(ffee);
                    }
                }else if(rc_fee_ec.length>1){
                    for (var i=0; i< rc_fee_ec.length;i++){
                        if(rc_fee_ec[i]['low_pay_range']<=amount && rc_fee_ec[i]['high_pay_range']>=amount){
                            fee+=parseFloat(rc_fee_ec[i]['convenience_fee']);
                            flat_fee=parseFloat(rc_fee_ec[i]['convenience_fee']);
                            porcent_fee=rc_fee_ec[i]['convenience_fee_float'];
                            if(rc_fee_ec[i]['convenience_fee_float']>0){
                                var ffee=(amount*rc_fee_ec[i]['convenience_fee_float'])/100;
                                fee=fee+parseFloat(ffee);
                            }
                            break;
                        }
                    }
                }
                
            }else if(type=='cc'){
                if(rc_fee_cc.length==1){
                    fee+=parseFloat(rc_fee_cc[0]['convenience_fee']);
                    flat_fee=parseFloat(rc_fee_cc[0]['convenience_fee']);
                    porcent_fee=rc_fee_cc[0]['convenience_fee_float'];
                    if(rc_fee_cc[0]['convenience_fee_float']>0){
                        var ffee=(amount*rc_fee_cc[0]['convenience_fee_float'])/100;
                        fee=fee+parseFloat(ffee);
                    }
                }else if(rc_fee_cc.length>1){
                    for (var i=0; i< rc_fee_cc.length;i++){
                        if(rc_fee_cc[i]['low_pay_range']<=amount && rc_fee_cc[i]['high_pay_range']>=amount){
                            fee+=parseFloat(rc_fee_cc[i]['convenience_fee']);
                            flat_fee=parseFloat(rc_fee_cc[i]['convenience_fee']);
                            porcent_fee=rc_fee_cc[i]['convenience_fee_float'];
                            if(rc_fee_cc[i]['convenience_fee_float']>0){
                                var ffee=(amount*rc_fee_cc[i]['convenience_fee_float'])/100;
                                fee=fee+parseFloat(ffee);
                            }
                            break;
                        }
                    }
                }
            }else if(type=='am'){
                if(rc_fee_amex.length==1){
                    fee+=parseFloat(rc_fee_amex[0]['convenience_fee']);
                    flat_fee=parseFloat(rc_fee_amex[0]['convenience_fee']);
                    porcent_fee=rc_fee_amex[0]['convenience_fee_float'];
                    if(rc_fee_amex[0]['convenience_fee_float']>0){
                        var ffee=(amount*rc_fee_amex[0]['convenience_fee_float'])/100;
                        fee=fee+parseFloat(ffee);
                    }
                }else if(rc_fee_amex.length>1){
                    for (var i=0; i< rc_fee_amex.length;i++){
                        if(rc_fee_amex[i]['low_pay_range']<=amount && rc_fee_amex[i]['high_pay_range']>=amount){
                            fee+=parseFloat(rc_fee_amex[i]['convenience_fee']);
                            flat_fee=parseFloat(rc_fee_amex[i]['convenience_fee']);
                            porcent_fee=rc_fee_amex[i]['convenience_fee_float'];
                            if(rc_fee_amex[i]['convenience_fee_float']>0){
                                var ffee=(amount*rc_fee_amex[i]['convenience_fee_float'])/100;
                                fee=fee+parseFloat(ffee);
                            }
                            break;
                        }
                    }
                }
            } 
    
    
    if(fee==0){
        $("#xalertdiv").hide();
        $("#xservice_fee").hide();
        $("#xtotal").html("$"+(parseFloat(fee)+parseFloat(amount)).toFixed(2));
    }else{
        $("#xalertdiv").show();
        $("#xservice_fee").show();
        $("#xconvfee").html("$"+parseFloat(fee).toFixed(2));
        $("#xtotal").html("$"+(parseFloat(fee)+parseFloat(amount)).toFixed(2));
    }
}

function ChangeIcon(){
    if ($('#xselected_profile').length){
       var p=$("#xselected_profile option:selected").attr("id").split("|");
       if(p.length<1){
           return false;
       }
       switch (p[1]){
           case 'visa':
                       $("#xicon").attr("src","/img/visa.png");
                       break;
           case 'mastercard':
                       $("#xicon").attr("src","/img/mastercard.png");
                       break;
           case 'discover':
                       $("#xicon").attr("src","/img/discover.png");
                       break;
           case 'am':
                       $("#xicon").attr("src","/img/american.png");
                       break;
           case 'ec':
                       $("#xicon").attr("src","/img/echeck.png");
                       break;
           default: break;//unknown profile
       }
    }
}


