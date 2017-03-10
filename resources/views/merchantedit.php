
					<?php
					//echo '<pre>';
					//print_r($accutransactiondetail); die;
					echo Form::open(array('url' => '', 'files' => true, 'id' => 'editmerchantForm', 'onsubmit' => 'return false;', 'role' => 'form', 'class' => 'form-horizontal'));
					echo Form::hidden('id', $merchantdetail[0]['id']);
					//echo Form::hidden('user_id', $idadmin);
					?>

					<div class="row form-group">
						<div class="col-xs-12">
							<?php echo Form::label('name_clients', 'Name (*)'); ?>
							<?php echo Form::text('name_clients', str_replace(",","", $merchantdetail[0]['name_clients']), array('class' => 'form-control'));?>
						</div>
					</div>

					<div class="row form-group">
						<div class="col-xs-12">
							<?php echo Form::label('compositeID_clients', 'Company ID'); ?>
							<?php echo Form::text('compositeID_clients', str_replace(",","", $merchantdetail[0]['compositeID_clients']), array('class' => 'form-control'));?>
						</div>
					</div>

					<div class="row form-group">
						<div class="col-md-3">
							<?php echo Form::label('address_clients', 'Address'); ?>
							<?php echo Form::text('address_clients', $merchantdetail[0]['address_clients'], array('class' => 'form-control'));?>
						</div>
						<div class="col-md-3">
							<?php echo Form::label('city_clients', 'City');?>
							<?php echo Form::text('city_clients', $merchantdetail[0]['city_clients'], array('class' => 'form-control'));?>
						</div>
						<div class="col-md-3">
							<?php echo Form::label('state_clients', 'State');?>
							<?php echo Form::select('state_clients', array('AL' => 'AL', 'CA' => 'California', 'NY' => 'New York'), $merchantdetail[0]['state_clients'], array('class' => 'form-control'));?>
						</div>
						<div class="col-md-3">
							<?php echo Form::label('zip_clients', 'ZIP Code');?>
							<?php echo Form::text('zip_clients', $merchantdetail[0]['zip_clients'], array('class' => 'form-control'));?>
						</div>
					</div>

					<div class="row form-group">
						<div class="col-sm-6">
							<?php echo Form::label('contact_name_clients', 'Contact Name (*)');?>
							<?php echo Form::text('contact_name_clients', $merchantdetail[0]['contact_name_clients'], array('class' => 'form-control'));?>
						</div>
						<div class="col-sm-6">
							<?php echo Form::label('email_address_clients', 'Email (*)');?>
							<?php echo Form::text('email_address_clients', str_replace(",","", $merchantdetail[0]['email_address_clients']), array('class' => 'form-control'));?>
						</div>
					</div>

					<div class="row form-group">
						<div class="col-xs-12">
							<?php echo Form::label('units', 'Units (*)');?>
							<?php echo Form::text('units', str_replace(",","", $merchantdetail[0]['units']), array('class' => 'form-control'));?>
						</div>
					</div>

					<div class="row form-group">
						<div class="col-md-6">
							<?php echo Form::label('accounting_email_address_clients', 'Accounting Email (*)');?>
							<?php echo Form::text('accounting_email_address_clients', str_replace(",","", $merchantdetail[0]['accounting_email_address_clients']), array('class' => 'form-control'));?>
						</div>
						<div class="col-md-6">
							<?php echo Form::label('phone_clients', 'Phone');?>
							<?php echo Form::text('phone_clients', $merchantdetail[0]['phone_clients'], array('class' => 'form-control'));?>
						</div>
					</div>



						<?php 
						//echo Form::button('Submit', array('class' => 'btn btn-primary', 'id' => 'editMerchantButton'));
						echo Form::close();
						
						echo Form::hidden('isError', 0, array('id' => 'merchantError'));
						?>

    <script type="text/javascript">
		var token = '<?php echo $token; ?>';
		$(document).ready(function() { 
	   
						var options = { 
							target:        '',   // target element(s) to be updated with server response 
							beforeSubmit:  showRequest,  // pre-submit callback 
							success:       showResponse , // post-submit callback 
					 
							// other available options: 
							//url:       '/master/index.php/merchant/mupdate',         // override for form's 'action' attribute
							url:       '/merchant/mupdate',         // override for form's 'action' attribute
							type:      'post',       // 'get' or 'post', override for form's 'method' attribute
							dataType:  'json'        // 'xml', 'script', or 'json' (expected server response type) 
							//clearForm: true        // clear all form fields after successful submit 
							//resetForm: true        // reset the form after successful submit 
					 
							// $.ajax options can be used here too, for example: 
							//timeout:   3000 
						}; 
					 
						// bind to the form's submit event 
						$('#updateMerchantButton').click(function() {
							// inside event callbacks 'this' is the DOM element so we first 
							// wrap it in a jQuery object and then invoke ajaxSubmit 
							//alert('pp');
							$('#myModal_loading').modal();
							$('#myModal_merchantdetail').modal('hide');
							$('#editmerchantForm').ajaxSubmit(options); 
					 
							// !!! Important !!! 
							// always return false to prevent standard browser submit and page navigation 
							return false; 
						}); 
						
					});
    function showRequest(){
		
		//$("#xpopupcontent").html(responseText.msg);
		//return false;
	}

	function showResponse(responseText, statusText, xhr, $form){		
		
		if(responseText.error == -1){
			//alert(responseText.msg); 
			$('#myModal_loading').modal('hide');
			//$('#myModal_merchantdetail').modal('show');
			$('#merchantError').val(0);
			$("#xpopupcontent").html(responseText.msg);
			$('#myModal_success').modal();			
			return false;
		}else if(responseText.error == 1){
			//alert(responseText.msg); 
			//alert('pp');
			$('#myModal_loading').modal('hide');
			//$('#myModal_merchantdetail').modal('show');
			$('#merchantError').val(1);
			$("#xpopupcontent").html(responseText.msg);
			$('#myModal_success').modal();
			return false;
		}
		
		
	} 
	
	$('#myModal_success .row button').click(function(){
				
		//alert('pp');
		if(!parseInt($('#merchantError').val())){
			window.location.href = '/merchant/'+token+'/list';
			return false;
		}else{
			
			$('#myModal_loading').modal('hide');
			$('#myModal_success').modal('hide');
			$('#myModal_merchantdetail').modal('show');
			return false;
			
		}
		
	});
	  
</script>
