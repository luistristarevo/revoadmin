<?php  include_once __DIR__.'/admin_components/admin_header.php'; ?>


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
						<li><a href="<?php echo route('formatsexport',array('query_base64'=>$sql_ready,'type'=>'topstransactions'))?>">Transaction (TOPS)</a></li>
						<li><a href="<?php echo route('formatsexport',array('query_base64'=>$sql_ready,'type'=>'csv'))?>">Transaction (CSV)</a></li>
						<li><a href="<?php echo route('formatsexport',array('query_base64'=>$sql_ready,'type'=>'caliber'))?>">Transactions (CALIBER)</a></li>
						<li><a href="<?php echo route('formatsexport',array('query_base64'=>$sql_ready,'type'=>'lockboxtransactions'))?>">Transactions (Regular Lockbox file)</a></li>
						<li><a href="<?php echo route('formatsexport',array('query_base64'=>$sql_ready,'type'=>'jenark'))?>">Transactions (JENARK)</a></li>
					</ul>
				</div>
				<?php } ?>
			</div>
		</div>
		<?php
				echo $filter;
				echo $grid;
		 ?>
</div>
</div>
</div>

    <?php
    $popuphdr="Success!";
    $popupcontent="";
    if(isset($msgCode)){
        include_once __DIR__.'/../components/messages.php';
        $popuphdr="Success!";
        $popupcontent="";
        if(isset($global_messages[$msgCode])){
            $popupcontent=$global_messages[$msgCode];
        }
        
    }
    include_once __DIR__.'/components/popupsuccess.php';
    include_once __DIR__.'/components/loading.php';
    include_once __DIR__.'/components/footer.php';
    include_once __DIR__.'/components/popuptransactionreportdetail.php';
    ?>
    <?php if(isset($msgCode)) : ?>

    <?php endif; ?>
    <script type="text/javascript">
    var xurl = '<?php echo URL::to('/').'/'; ?>';
    var xatoken = '<?php echo $atoken; ?>';
		
    </script>
    <script type="text/javascript">
		
		//export to csv code    			
			
			function voidpr(trans_id){
				var str='Please Confirm that you want to Void the transaction with ID '+trans_id+' by clicking here <br/><br/><button class="btn btn-success" onclick="procevoid(\''+trans_id+'\')">Proceed</button>';
				$("#xpopupcontent").html("Void Transaction"+"<br/>"+str);
				$('#myModal_success').modal();
				return false;
			}
            function sendpr(trans_id){
				var str='Input the recipient email address or leave blank to use the user email address in database<br><input type="hidden" name="emailreciepterror" id="emailreciepterror" value="0" /><br/><div class="row"><div class="col-xs-8"><input class="form-control" type="email" id="srpemail" ></div><div class="col-xs-4"><button class="btn btn-success btn-block" onclick="procespr(\''+trans_id+'\',$(\'#srpemail\').val())">Proceed</button></div></div>';
				$("#xpopupheader").html('<h4 style="margin-top: 10px; margin-bottom: 15px; font-size: 24px;">Payment Receipt</h4>');
				$("#xpopupcontent").html("Send Payment Receipt. Transaction ID "+trans_id+"<br/>"+str);
				$('#myModal_success').modal();
				return false;
			}
			
			function procevoid(trans_id){
				
				$('#myModal_success').modal('hide');
				$('#myModal_loading').modal();
				$.get("/transactionReport/void/"+trans_id+"/1")
				  .done(function( data ) {
					var msg = $.parseJSON(data);
					//setModalValues("Void Transaction", (msg.txt == null ? "Your request was successfully processed." : msg.txt), true);
					var msgText = (msg.txt == null ? "Your request was successfully processed." : msg.txt);
					$('#myModal_loading').modal('hide');
					$("#xpopupcontent").html("Void Transaction <br/>"+msgText);
					$("#is_page_ref").val(1);
					$('#myModal_success').modal();
					return false;
				  });			  			
				return false;			
			}

			function procespr(trans_id,elm){
				
				//alert(elm); return false;
				var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
				if(elm!=''){
					if(!re.test(elm)){
							//alert("Please provide a valid email address");
							var str='Input the recipient email address or leave blank to use the user email address in database<br><br><span style="color:red; float: left">Please provide a valid email address</span><div class="row"><div class="col-xs-8"><input class="form-control" type="email" id="srpemail" ></div><div class="col-xs-4"><button class="btn btn-success btn-block" onclick="procespr(\''+trans_id+'\',$(\'#srpemail\').val())">Proceed</button></div></div>';
							$("#xpopupheader").html('<h4 style="margin-top: 10px; margin-bottom: 15px; font-size: 24px;"> Payment Receipt</h4>');
							$("#xpopupcontent").html("Send Payment Receipt. Transaction ID "+trans_id+"<br/>"+str);
							$('#myModal_success').modal();
							//$('.modal-backdrop').remove();							
							//$('.modal-open').append('<div class="modal-backdrop fade in"></div>');
							$("#is_page_ref").val(0);
							//$('#myModal_success').css("display", "block");							
							return false;
						}
				}
				$('#myModal_success').modal('hide');
				$('#myModal_loading').modal();
				$.get( "/transactionReport/emailreceipt/?id="+trans_id+"&eml="+elm)
				  .done(function( data ) {
					var msg = $.parseJSON(data);
						//setModalValues("Email Reciept", (msg.txt == null ? "Your request was successfully processed." : msg.txt), true);
						//setModalValues("Send Payment Receipt. Transaction ID "+trans_id, msg.txt, true);
						$('#myModal_loading').modal('hide');
						//alert(msg.error);return false;
						var msgText = (msg.txt == null ? "Your request was successfully processed." : msg.txt);
						if(parseInt(msg.error) == 1){							
							
							var str='Input the recipient email address or leave blank to use the user email address in database<br><input style="margin-bottom:5px;" type="email" id="srpemail" ><br/><span style="color:red;">'+msgText+'</span><br><button class="btn btn-success" onclick="procespr(\''+trans_id+'\',$(\'#srpemail\').val())">Proceed</button>';
							$("#xpopupheader").html('<h4 style="margin-top: 10px; margin-bottom: 15px; font-size: 24px;">  Payment Receipt</h4>');
							$("#xpopupcontent").html("Send Payment Receipt. Transaction ID "+trans_id+"<br/>"+str);
							//$('#myModal_success').addClass("in");
							$('#myModal_success').modal();
							//$('.modal-backdrop').remove();
							//$('.modal-open').append('<div class="modal-backdrop fade in"></div>');
							//$('#myModal_success').css("display", "block");	
							$("#is_page_ref").val(0);						
							return false;
						}else{
							var str='Input the recipient email address or leave blank to use the user email address in database<br><input style="margin-bottom:5px;" type="email" id="srpemail" ><br/><span style="color:red;">'+msgText+'</span><br><button class="btn btn-success" onclick="procespr(\''+trans_id+'\',$(\'#srpemail\').val())">Proceed</button>';
							$("#xpopupheader").html('<h4 style="margin-top: 10px; margin-bottom: 15px; font-size: 24px;">  Payment Receipt</h4>');
							$("#xpopupcontent").html("Send Payment Receipt. Transaction ID "+trans_id+"<br/>"+str);
							$('#myModal_success').modal();
							//$('#myModal_success').addClass("in");
							//$('#myModal_success').css("display", "block");
							//$('.modal-backdrop').remove();
							//$('.modal-open').append('<div class="modal-backdrop fade in"></div>');
							$("#is_page_ref").val(1);
							//window.location.href = '/master/index.php/transactionReport/'+xatoken+'/transactions';
							return false;
						}
					
				  });			  			
				return false;  		
				
		   }

		$('#myModal_success .row button').click(function(){				
			
			//alert('pp');
			$('#myModal_success').modal('hide');
			//$('#myModal_success').removeClass('in');
			//$('.modal-backdrop').remove();
			//$('#myModal_success').css('display', 'none');
			if(parseInt($("#is_page_ref").val())){
				window.location.href = '/transactionReport/'+xatoken+'/transactions';
			}
			//$('#myModal_success').slideUp();
			return false;	
			
		});
		
			
    </script>

    
    <?php echo Rapyd::scripts(); ?>
    
    <script type="text/javascript" src="/js/transactionreportdetail.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>


	<?php  include_once __DIR__.'/admin_components/admin_footer.php'; ?>