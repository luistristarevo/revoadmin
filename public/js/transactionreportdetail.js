function showTransdetail(id){
    var xturl='/transactionReport/transdetail/'+xatoken+'/'+id;
    $.ajax({
        url:xurl+xturl
        }).done(function(data){
            $("#xpopuptranstitle").html("Transaction Detail");
            if(data.errcode==0){
                $("#xpopuptranscontent").html(data.msg);
                $('#myModal_transactionreport').modal();

            }
            else {
                $("#xpopuptranscontent").html("<label>"+data.msg+"</label>");
                $('#myModal_transactionreport').modal();

            }
        });
}
