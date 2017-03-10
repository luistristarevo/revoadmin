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
    $popuphdr="Edit Group!";
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
    
    include_once __DIR__.'/components/popupgroupdetail.php';
     ?>
     <script type="text/javascript" src="/js/jquery.form.js"></script>
    <script type="text/javascript">		
						
		//export to csv code
		$('#exportGroup').click( function(){
		
			//alert($(this).attr('href')); return false;
			//alert($("#transactionRdatafilter").serialize());
			$.get( $(this).attr('rel'), { formparam:$("#groupRdatafilter").serialize()})
			  .done(function( data ) {
				window.location.href = '/master/index.php/group/downloadgroupreport';
				return false;
			  });			  			
			return false;	

		});
		
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
		
		function addGroup(){
			
			$('#myModal_loading').modal('hide');
			$.get( $("#AddGroup").attr('rel'))
			  .done(function( data ) {
				if(data.errcode==0){
					$('#myModal_loading').modal('hide');
					$("#xpopuptranscontent").html(data.msg);
					$('#myModal_groupdetail').modal('show');
				}else {
					$('#myModal_loading').modal('hide');
					$("#xpopuptranscontent").html("<label>"+data.msg+"</label>");
					$('#myModal_groupdetail').modal('show');            
				}
				return false;
			  });			  			
			return false;
			
		}

		function openg(group_id, adminid){
			route = '../../../../bis/inc/loadgrp.php?admid='+adminid+'&grpid='+group_id;
			window.open(route,'_blank');
		}
			
    </script>
    <?php echo Rapyd::scripts(); ?>
    <script type="text/javascript">
    var xurl = '<?php echo URL::to('/').'/'; ?>';
    var xatoken = '<?php echo $atoken; ?>';
    
    </script>
    <script type="text/javascript" src="/js/groupdetail.js"></script>

	<!--<div id="myModal" class="modal fade" aria-labelledby="Message" aria-hidden="true">
		<div class="modal-body" style="padding: 8px;">
			<div id="alert_msg" style="margin: 10px; font-size: 18px"></div>
			<div id="other_msg"></div>
		</div>
		<div id="modal-bottom-div" class="modal1-footer" style="padding-top: 5px; padding-bottom: 5px;">
			<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
		</div>
	</div>-->
	<script type="text/javascript">
	$.ajaxSetup({
	   headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
	});
	</script>
<?php  include_once __DIR__.'/admin_components/admin_footer.php'; ?>
