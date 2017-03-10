function showMerchant(id){
    var xturl='merchant/mdetail/'+xatoken+'/'+id;
    $.ajax({
        url:xurl+xturl
        }).done(function(data){
            if(data.errcode==0){
                $("#xpopuptranscontent").html(data.msg);
                $('#myModal_merchantdetail').modal();
            }
            else {
                $("#xpopuptranscontent").html("<label>"+data.msg+"</label>");
                $('#myModal_merchantdetail').modal();            
            }
        });
}

function showMerchantStatusWarning(id) {
    var xturl = 'merchant/mChangeStatusWarning/' + xatoken + '/' + id;
    $.ajax({
        url: xurl + xturl
    }).done(function (data) {
        if (data.errcode == 0) {
            $("#xpopuptranscontent2").html(data.msg);
            $('#myModal_changeMerchantStatus').modal({backdrop: 'static', keyboard: false});
        } else {
            $("#xpopuptranscontent2").html("<label>" + data.msg + "</label>");
            $('#myModal_changeMerchantStatus').modal({backdrop: 'static', keyboard: false});
        }
    });
}

function editMerchantPayCredential(id){
    var xturl='merchant/viewpaymentcredential/'+xatoken+'/'+id;
    $.ajax({
        url:xurl+xturl
        }).done(function(data){
            if(data.errcode==0){
                $("#xpopuptranscontent").html(data.msg);
                $('#myModal_merchantdetail').modal();
            }
            else {
                $("#xpopuptranscontent").html("<label>"+data.msg+"</label>");
                $('#myModal_merchantdetail').modal();            
            }
        });
}
