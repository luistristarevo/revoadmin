function showPartner(id){
    var xturl='partner/pdetail/'+xatoken+'/'+id;
    $.ajax({
        url:xurl+xturl
        }).done(function(data){
            if(data.errcode==0){
                $("#xpopuptranscontent").html(data.msg);
                $('#myModal_partnerdetail').modal();
            }
            else {
                $("#xpopuptranscontent").html("<label>"+data.msg+"</label>");
                $('#myModal_partnerdetail').modal();            
            }
        });
}
