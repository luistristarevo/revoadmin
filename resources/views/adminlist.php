<?php  include_once __DIR__.'/admin_components/admin_header.php'; ?>
	<script src="<?php echo asset('../js/bootstrap_aux.min.js'); ?>"></script>

<div class="container">
	<div class="panel-shadow">
		<div class="fadein">
			<h1 class="margin-t-0"><?php echo $pageTitle ; ?></h1>
			<?php
			echo $filter ;
			echo $grid ;

			?>
		</div>
	</div></div>
<?php


    $popuphdr="Edit Admin!";
    $popupcontent="";
    if(isset($msgCode)){
        include_once __DIR__.'/components/messages.php';
        $popuphdr="Success!";
        $popupcontent="";
        if(isset($global_messages[$msgCode])){
            $popupcontent=$global_messages[$msgCode];
        }
        
    }
    include_once __DIR__.'/components/popupsuccess.php';
    include_once __DIR__.'/components/loading.php';
    include_once __DIR__.'/components/footer.php';
     
    ?>
    <?php if(isset($msgCode)) : ?>
    <script>
            $('#myModal_success').modal();
            
            
    </script>
    <?php endif;
    
    include_once __DIR__.'/admin_components/popupadmindetail.php';
     ?>
     <!--<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>-->
     <script type="text/javascript" src="/js/jquery.form.js"></script>
     <script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <script type="text/javascript">		
						
		//export to csv code
		$('#exportAdminUser').click( function(){
		
			//alert($(this).attr('href')); return false;
			//alert($("#transactionRdatafilter").serialize());
			$.get( $(this).attr('rel'), { formparam:$("#adminRdatafilter").serialize()})
			  .done(function( data ) {
				window.location.href = '/master/index.php/admins/downloadadminreport';
				return false;
			  });			  			
			return false;	

		});
		
		function assignRoleAndPrivilege(){
			
			window.location.href = $('#AdminAssignRolesPrivilige').attr('rel');		  			  			
			return false;
			
		}
		
		function deleteAdminUser(id, target_url){
			window.parent.$("html,body").animate({scrollTop: 0}, 0);
			bootbox.confirm("Do you want to delete selected user?", function(result) {
						
						if(!result) {							
							
							return false;
							
						}else{
							
							$.get(target_url+id)
							.done(function( data ) {
								if(data.errcode == 0){
									//$("#myModal_wupayimport").modal();
									//bootbox.alert(data.message);
									//$('#myModal_success').css('display', 'none');
									window.parent.$("#adminRdatafilter").submit();
										
								}else{
									bootbox.alert(data.message);
									//$('#myModal_success').css('display', 'none');
									window.parent.$("html,body").animate({
										scrollTop: 0
									}, 0);
								}
								return false;
							});
							
						}
			
			
			});
		
	}
		
		$('.partnerlogopopup').on('click', function(){
			
			var logourl = $(this).attr('rel');
			$("#xpopupheader").html('View Partner Logo');
			$("#xpopupcontent").html('<img src="'+logourl+'" height="100px;" width="100px;" />');
			$('#myModal_success').modal();
			
		});	
		
		$("#importPartner").click( function(){
			$('#myModal_loading').modal('hide');
			$.get( $("#importPartner").attr('rel'))
			  .done(function( data ) {
				if(data.errcode==0){
					$('#myModal_loading').modal('hide');
					$("#xpopuptranscontent").html(data.msg);
					$('#myModal_partnerdetail').modal('show');
				}else {
					$('#myModal_loading').modal('hide');
					$("#xpopuptranscontent").html("<label>"+data.msg+"</label>");
					$('#myModal_partnerdetail').modal('show');            
				}
				return false;
			  });			  			
			return false;
		});
		
		function addAdmin(){
			
			$('#myModal_loading').modal('hide');
			$.get( $("#AddAdmin").attr('rel'))
			  .done(function( data ) {
				if(data.errcode==0){
					$('#myModal_loading').modal('hide');
					$("#xpopuptranscontent_admindetail").html(data.msg);
					$('#myModal_admindetail').modal('show');
				}else {
					$('#myModal_loading').modal('hide');
					$("#xpopuptranscontent_admindetail").html("<label>"+data.msg+"</label>");
					$('#myModal_admindetail').modal('show');            
				}
				return false;
			  });			  			
			return false;
			
		}
		
		function openg(partner_id, adminid){
			$("#admid").val(adminid);
			$("#prnid").val(partner_id);
			$("#omnt").submit();
		}
			
    </script>
    <?php echo Rapyd::scripts(); ?>
    <script type="text/javascript">
    var xurl = '<?php echo URL::to('/').'/'; ?>';
    var xatoken = '<?php echo $atoken; ?>';
    
    </script>
    <script type="text/javascript" src="/js/admindetail.js"></script>
   <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
   	<script type="text/javascript" src="/js/bootbox.js"></script>	

	<script type="text/javascript">
	$.ajaxSetup({
	   headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
	});
	</script>
<?php  include_once __DIR__.'/admin_components/admin_footer.php'; ?>