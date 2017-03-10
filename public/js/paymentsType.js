function CalculateAmount(){

        var tmp_obj;
        var amount = 0;
        var tmp;

        for (var i=0; tmp_obj = document.getElementById("xcheckpay_" + i); i++) {
            if (tmp_obj.checked) {
                tmp_obj = document.getElementById("xinputpay_" + i);
                tmp_dd_obj = document.getElementById("qtypay_" + i);
                tmp_dd_obj_val = 1;
                if(tmp_dd_obj){
                    tmp_dd_obj_val = tmp_dd_obj.value;
                }

                tmp = tmp_obj.value.replace(/,/g, "");
                //tmp = tmp_obj.value.replace(/$/g, "");
                tmp = parseFloat(tmp);
                if (!isNaN(tmp) && tmp > 0) {
                    amount += tmp * tmp_dd_obj_val;
                    tmp_obj.value=tmp.toFixed(2);
                }
            }
        }
        amount=parseFloat(amount).toFixed(2);
        
        $("#xpretotal").html("$"+amount);
        $(".pprofile:checked").each(function (index,value) {
             xamount=amount;
             ChangeServiceFee(this);
         });
         
        return amount;
    }

    function onKeyUp(id){
        var input_id="#xinputpay_"+id;
        var check_id="#xcheckpay_"+id;
        var tmp=$(input_id).val();
        //tmp=tmp.replace(/e/g,"");
        if(tmp.length>0 && tmp>0){
            $(check_id).prop("checked", "checked");
          //  $(input_id).val(parseFloat(tmp).toFixed(2));
        }else{
            $(check_id).prop("checked", "");
            
        }
    }

function checkPType(type){
    var ctdiv=0;
    if(type==1){
        if(typeof ot_velocity_cc != 'undefined'){
            if(!jQuery.isEmptyObject(ot_velocity_cc)){
                if(ot_velocity_cc!=""){
                    $(".mccmethod").show();
                    ctdiv++;
                }
                else {
                    $(".mccmethod").hide();
                    if(typeof appqpay != 'undefined'){
                        $("#checkbox5").prop('checked',false);
                    }
                }
            }
            else {
                $(".mccmethod").hide();
                if(typeof appqpay != 'undefined'){
                    $("#checkbox5").prop('checked',false);
                }
            }
        }
        if(typeof ot_velocity_amex != 'undefined' && ctdiv==0){
            if(!jQuery.isEmptyObject(ot_velocity_amex)){
                if(ot_velocity_amex!=""){
                    $(".mccmethod").show();
                    ctdiv++;
                }
                else {
                    $(".mccmethod").hide();
                    if(typeof appqpay != 'undefined'){
                        $("#checkbox5").prop('checked',false);
                    }
                }
            }
            else {
                $(".mccmethod").hide();
                if(typeof appqpay != 'undefined'){
                    $("#checkbox5").prop('checked',false);
                }
            }
        }
        if(typeof ot_velocity_ec != 'undefined'){
            if(!jQuery.isEmptyObject(ot_velocity_ec)){
                if(ot_velocity_ec!=""){
                    $(".mecmethod").show();
                    ctdiv++;
                }
                else {
                    $(".mecmethod").hide();
                    if(typeof appqpay != 'undefined'){
                        $("#checkbox4").prop('checked',false);
                    }
                }
            }
            else {
                $(".mecmethod").hide();
                if(typeof appqpay != 'undefined'){
                    $("#checkbox4").prop('checked',false);
                }
            }
        }
    }
    else {
        if(typeof rc_velocity_cc != 'undefined'){
            if(!jQuery.isEmptyObject(rc_velocity_cc)){
                if(rc_velocity_cc!=""){
                    $(".mccmethod").show();
                    ctdiv++;
                }
                else {
                    $(".mccmethod").hide();
                    if(typeof appqpay != 'undefined'){
                        $("#checkbox5").prop('checked',false);
                    }
                }
            }
            else {
                $(".mccmethod").hide();
                if(typeof appqpay != 'undefined'){
                    $("#checkbox5").prop('checked',false);
                }
            }
        }
        if(typeof rc_velocity_ec != 'undefined'){
            if(!jQuery.isEmptyObject(rc_velocity_ec)){
                if(rc_velocity_ec!=""){
                    $(".mecmethod").show();
                    ctdiv++;
                }
                else {
                    $(".mecmethod").hide();
                    if(typeof appqpay != 'undefined'){
                        $("#checkbox4").prop('checked',false);
                    }
                }
            }
            else {
                $(".mecmethod").hide();
                if(typeof appqpay != 'undefined'){
                    $("#checkbox4").prop('checked',false);
                }
            }
        }
    }
    if(ctdiv>1){
        $(".mcdiv").show();
    }
    else {
        $(".mcdiv").hide();
    }
}
