function showGroup(id){
    var xturl='group/gdetail/'+xatoken+'/'+id;
    $.ajax({
        url:xurl+xturl
        }).done(function(data){
            if(data.errcode==0){
                $("#xpopuptranscontent").html(data.msg);
                $('#myModal_groupdetail').modal();
            }
            else {
                $("#xpopuptranscontent").html("<label>"+data.msg+"</label>");
                $('#myModal_groupdetail').modal();            
            }
        });
}
