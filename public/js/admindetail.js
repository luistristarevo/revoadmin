function showUser(id){
    var xturl='admins/admindetail/'+xatoken+'/'+id;
    $.ajax({
        url:xurl+xturl
        }).done(function(data){
            if(data.errcode==0){
                $("#xpopuptranscontent_admindetail").html(data.msg);
                $('#myModal_admindetail').modal();
            }
            else {
                $("#xpopuptranscontent_admindetail").html("<label>"+data.msg+"</label>");
                $('#myModal_admindetail').modal();            
            }
        });
}
