<?php  include_once __DIR__.'/admin_components/admin_header.php'; ?>
<script src="<?php echo asset('../js/bootstrap_aux.min.js'); ?>"></script>
<div class="container">
	<div class="panel-shadow">
		<div class="fadein">
			<div class="row">
				<div class="col-xs-6">
					<h1 class="header"><?php echo $pageTitle ; ?></h1>
				</div>
				<div class="col-xs-6 text-right">
					<?php
					$export_link = false;
					foreach(Session::get('user_permissions') as $p){
						if($p['route']=='formatsexport'){
							$export_link = true;
							break;
						}
					}

					$bindings = $filter->query->getBindings();
					$sql_ready =$sql;
					foreach ($bindings as $replace){
						$sql_ready = preg_replace('/\?/',"'$replace'", $sql_ready, 1);
					}
					$sql_ready = base64_encode($sql_ready);
					?>
					<?php if($export_link){?>
						<div class="btn-group ">
							<button style="color: #ffffff!important;" type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<span class="fa fa-upload"></span> Export <span class="caret"></span>
							</button>
							<ul class="dropdown-menu pull-right">
								<li><a href="<?php echo route('formatsexport',array('query_base64'=>$sql_ready,'type'=>'csv'))?>">Merchants (CSV)</a></li>
							</ul>
						</div>
					<?php } ?>
				</div>
			</div>
	<?php

			echo $filter ;
			echo $grid ;

	 ?>
		</div>
</div></div>
    <?php
    $popuphdr="Edit Partner!";
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
    
    include_once __DIR__.'/components/popuppartnerdetail.php';
     ?>
     <!--<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>-->
     <script type="text/javascript" src="/js/jquery.form.js"></script>

    <script type="text/javascript">		
						
		//export to csv code
		$('#exportPartner').click( function(){
		
			//alert($(this).attr('href')); return false;
			//alert($("#transactionRdatafilter").serialize());
			$.get( $(this).attr('rel'), { formparam:$("#partnerRdatafilter").serialize()})
			  .done(function( data ) {
				window.location.href = '/master/index.php/partner/downloadpartnerreport';
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
		
		function addPartner(){
			
			$('#myModal_loading').modal('hide');
			$.get( $("#AddPartner").attr('rel'))
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
			
		}

		function openg(partner_id, adminid){
			route = '../../../../bis/inc/loadprn.php?admid='+adminid+'&prnid='+partner_id;
			window.open(route,'_blank');
		}
			
    </script>
    <?php echo Rapyd::scripts(); ?>
    <script type="text/javascript">
    var xurl = '<?php echo URL::to('/').'/'; ?>';
    var xatoken = '<?php echo $atoken; ?>';
    
    </script>
    <script type="text/javascript" src="/js/partnerdetail.js"></script>

	<script type="text/javascript">
	$.ajaxSetup({
	   headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
	});
	</script>
<?php  include_once __DIR__.'/admin_components/admin_footer.php'; ?>
