function editrecurring(id){
    var xturl='recurringReport/editrecurring/'+xatoken+'/'+id;
    $.ajax({
        url:xurl+xturl
        }).done(function(data){
            if(data.errcode==0){
                $("#xpopuptranscontent").html(data.msg);
                $("#xpopuptranstitle").html('AutoPay Edit');
                $('#myModal_transactionreport').modal();
            }
            else {
                $("#xpopuptranstitle").html('AutoPay Edit');
                $("#xpopuptranscontent").html("<label>"+data.msg+"</label>");
                $('#myModal_transactionreport').modal();            
            }
        });
}
