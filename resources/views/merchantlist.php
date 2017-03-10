<?php  include_once __DIR__.'/admin_components/admin_header.php'; ?>
<script src="<?php echo asset('../js/bootstrap_aux.min.js'); ?>"></script>
<div class="container">
    <div class="panel-shadow many-fields">
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
    $popuphdr="Edit Merchant";
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
      include_once __DIR__.'/components/popupmerchantdetail.php';
      include_once __DIR__.'/components/popupChangeMerchantStatus.php';
     ?>
     <script type="text/javascript" src="/js/jquery.form.js"></script>
    <script type="text/javascript">		
						
		//export to csv code
		$('#exportMerchant').click( function(){
		
			//alert($(this).attr('href')); return false;
			//alert($("#transactionRdatafilter").serialize());
			$.get( $(this).attr('rel'), { formparam:$("#merchantRdatafilter").serialize()})
			  .done(function( data ) {
				window.location.href = '/master/index.php/merchant/downloadmerchantlist';
				return false;
			  });			  			
			return false;	

		});		
		
		/*function addGroup(){
			
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
			
		}*/

        function openg(merchant_id, adminid){
            route = '/bis/inc/loadmn.php?admid='+adminid+'&mnid='+merchant_id;
            window.open(route,'_blank');
        }
			
    </script>
    <?php echo Rapyd::scripts(); ?>
    <script type="text/javascript">
    var xurl = '<?php echo URL::to('/').'/'; ?>';
    var xatoken = '<?php echo $atoken; ?>';
    
    </script>
   <script type="text/javascript" src="/js/merchantdetail.js"></script>

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
