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
    $popuphdr="Edit Web User!";
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
    include_once __DIR__.'/admin_components/popupwebuserforgetpassword.php';
    include_once __DIR__.'/components/loading.php';
    include_once __DIR__.'/components/footer.php';
     
    ?>
    <?php if(isset($msgCode)) : ?>
    <script>
            $('#myModal_success').modal();
            
            
    </script>
    <?php endif;

    //include_once __DIR__.'/../components/popupgroupdetail.php';
     ?>

     <script type="text/javascript" src="/js/jquery.form.js"></script>
     <script type="text/javascript">
    var xurl = '<?php echo URL::to('/').'/'; ?>';
    var xatoken = '<?php echo $atoken; ?>';
    
    </script>
    <script type="text/javascript">		
						
		$('.partnerlogopopup').on('click', function(){
			
			var logourl = $(this).attr('rel');
			$("#xpopupheader").html('View Partner Logo');
			$("#xpopupcontent").html('<img src="'+logourl+'" height="100px;" width="100px;" />');
			$('#myModal_success').modal();
			
		});		
		
		function resetpasswordwu(wuid){
			
			$('#wuforgetpasswordid').val(wuid);
                        $('#xpassword').val('');
                        $('#xpassword2').val('');
			$("#myModal_wuforgetpassword").modal();			
			
		}
		
		
		
		
		function deleteWebUser(id, target_url){
			window.parent.$("html,body").animate({scrollTop: 0}, 0);
			bootbox.confirm("Do you want to delete selected web user?", function(result) {
						
						if(!result) {							
							
							return false;
							
						}else{
							
							$.get(target_url+id)
							.done(function( data ) {
								if(data.errcode == 0){
									//$("#myModal_wupayimport").modal();
									//bootbox.alert(data.message);
									//$('#myModal_success').css('display', 'none');
									window.parent.$("#webuserRdatafilter").submit();
										
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
		
		/*$("#importWebUser").click(function(){
		  
			$('#myModal_success').css('display', 'block');
			var target_url = $(this).attr("rel");
			$.get(target_url)
			.done(function( data ) {
				if(data.errcode == 0){
					$("#myModal_wupayimport").modal();
					$("#myModal_wupayimport").html(data.msg);
					$('#myModal_success').css('display', 'none');
					window.parent.$("html,body").animate({
						scrollTop: 0
					}, 2000);	
				}
				return false;
			});
			
			
	  });*/
			
        $(document).ready(function() {
            window.$("html,body").animate({scrollTop: 0	}, 0);
        });                    
    </script>
    <?php echo Rapyd::scripts(); ?>
	<script type="text/javascript" src="/js/bootbox.js"></script>

<?php  include_once __DIR__.'/admin_components/admin_footer.php'; ?>
