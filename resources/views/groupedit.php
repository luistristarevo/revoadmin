
					<?php
					//echo '<pre>';
					//print_r($accutransactiondetail); die;
					echo Form::open(array('url' => '', 'files' => true, 'id' => 'editgroupForm', 'onsubmit' => 'return false;', 'role' => 'form', 'class' => 'form-horizontal'));
					echo Form::hidden('id', $groupdetail[0]['id']);
					//echo Form::hidden('user_id', $idadmin);
					?>


						<div class="row form-group">
							<div class="col-xs-12">
								<?php echo Form::label('company_name', 'Group Name'); ?>
								<?php echo Form::text('company_name', $groupdetail[0]['group'], array('class' => 'form-control'));?>
							</div>
						</div>
						<div class="row form-group">
							<div class="col-xs-12">
								<?php echo Form::label('group_id', 'Group ID'); ?>
								<?php echo Form::text('group_id', $groupdetail[0]['group_id'], array('class' => 'form-control'));?>
							</div>
						</div>


						<div class="row form-group">
							<div class="col-md-3">
								<?php echo Form::label('address', 'Address'); ?>
								<?php echo Form::text('address', $groupdetail[0]['address'], array('class' => 'form-control'));?>
							</div>
							<div class="col-md-3">
								<?php echo Form::label('city', 'City');?>
								<?php echo Form::text('city', $groupdetail[0]['city'], array('class' => 'form-control'));?>
							</div>
							<div class="col-md-3">
								<?php echo Form::label('state', 'State');?>
								<?php echo Form::select('state', array('CA' => 'California', 'NY' => 'New York'), $groupdetail[0]['state'], array('class' => 'form-control'));?>
							</div>
							<div class="col-md-3">
								<?php echo Form::label('zip', 'Zip');?>
								<?php echo Form::text('zip', $groupdetail[0]['zip'], array('class' => 'form-control'));?>
							</div>
						</div>


						<div class="row form-group">
							<div class="col-xs-12"><!--<b>Logo</b>-->
								<?php echo Form::label('contact_name', 'Contact Name');?>
								<?php echo Form::text('contact_name', $groupdetail[0]['cname'], array('class' => 'form-control'));?>
							</div>
						</div>


						<div class="row form-group">
							<div class="col-sm-6"><!--<b>Logo</b>-->
								<?php echo Form::label('contact_email', 'Contact Email');?>
								<?php echo Form::text('contact_email', $groupdetail[0]['cemail'], array('class' => 'form-control'));?>
							</div>
							<div class="col-sm-6">
								<?php echo Form::label('phone', 'Phone');?>
								<?php echo Form::text('phone_number', $groupdetail[0]['phone'], array('class' => 'form-control'));?>
							</div>
						</div>


						

						<?php 
						//echo Form::button('Submit', array('class' => 'btn btn-primary', 'id' => 'editGroupButton'));
						echo Form::close();
						
						echo Form::hidden('isError', 0, array('id' => 'groupError'));
						?>


    <script type="text/javascript">
		var token = '<?php echo $token; ?>';
		$(document).ready(function() { 
	   
						var options = { 
							target:        '',   // target element(s) to be updated with server response 
							beforeSubmit:  showRequest,  // pre-submit callback 
							success:       showResponse , // post-submit callback 
					 
							// other available options: 
							url:       '/group/gupdate',         // override for form's 'action' attribute
							type:      'post',       // 'get' or 'post', override for form's 'method' attribute 
							dataType:  'json'        // 'xml', 'script', or 'json' (expected server response type) 
							//clearForm: true        // clear all form fields after successful submit 
							//resetForm: true        // reset the form after successful submit 
					 
							// $.ajax options can be used here too, for example: 
							//timeout:   3000 
						}; 
					 
						// bind to the form's submit event 
						$('#updateGroupButton').click(function() { 
							// inside event callbacks 'this' is the DOM element so we first 
							// wrap it in a jQuery object and then invoke ajaxSubmit 
							//alert('pp');
							$('#myModal_loading').modal();
							$('#myModal_groupdetail').modal('hide');
							$('#editgroupForm').ajaxSubmit(options); 
					 
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
			//$('#myModal_groupdetail').modal('show');
			$('#groupError').val(0);
			$("#xpopupcontent").html(responseText.msg);
			$('#myModal_success').modal();			
			return false;
		}else if(responseText.error == 1){
			//alert(responseText.msg); 
			//alert('pp');
			$('#myModal_loading').modal('hide');
			//$('#myModal_groupdetail').modal('show');
			$('#groupError').val(1);
			$("#xpopupcontent").html(responseText.msg);
			$('#myModal_success').modal();
			return false;
		}
		
		
	} 
	
	$('#myModal_success .row button').click(function(){
				
		//alert('pp');
		if(!parseInt($('#groupError').val())){
			window.location.href = '/group/'+token+'/list';
			return false;
		}else{
			
			$('#myModal_loading').modal('hide');
			$('#myModal_success').modal('hide');
			$('#myModal_groupdetail').modal('show');
			return false;
			
		}
		
	});
	  
</script>
