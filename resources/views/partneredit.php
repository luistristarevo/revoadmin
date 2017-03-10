
					<?php
					echo Form::open(array('url' => '', 'files' => true, 'id' => 'editpartnerForm', 'onsubmit' => 'return false;', 'role' => 'form'));
					echo Form::hidden('id', $partnerdetail[0]['id']);
					echo Form::hidden('user_id', $idadmin);
					?>

						<div class="row form-group">
								<div class="col-xs-12">
									<?php echo Form::label('partner_title', 'Title'); ?>
									<?php echo Form::text('partner_title', $partnerdetail[0]['title'], array('class' => 'form-control'));?>
								</div>
						</div>
						
						<div class="row form-group">
								<div class="col-sm-6">
									<?php echo Form::label('partner_id', 'ID'); ?>
									<?php echo Form::text('partner_id', $partnerdetail[0]['partner_id'], array('class' => 'form-control'));?>
								</div>
							<div class="col-sm-6">
								<?php echo Form::label('status', 'Status'); ?>
								<?php echo Form::select('status', array('1' => 'Active', '0' => 'Inactive'), $partnerdetail[0]['status'], array('class' => 'form-control'));?>
							</div>
						</div>


						<div class="row form-group">
								<div class="col-xs-12">
									<?php echo Form::label('layout', 'Vertical'); ?>
									<?php echo Form::select('layout', array('' => '--Select--','1' => 'Property', '2' => 'Academic', '6' => 'Business', '13' => 'Non-Profit', '14' => 'Utilities'), $partnerdetail[0]['layout'], array('class' => 'form-control'));?>
								</div>
						</div>

					<?php
					if($idlevel == -954581){?>
						<div class="row form-group">
						<div class="col-sm-6">
							<?php echo Form::label('logo', 'Logo');?>
							<?php
							echo Form::file('logo', array('id' => 'partnerLogo'));
							//$partnerdetail[0]['logo']; ?>
						</div>
						<div class="col-sm-6">
							<?php
							if(($partnerdetail[0]['logo'] != '') && file_exists(public_path().'/uploads/logos/partner/'.$partnerdetail[0]['logo'])){ ?>
								<img class="img-responsive" src="/master/uploads/logos/partner/<?php echo $partnerdetail[0]['logo']; ?>" /><?php
							}else{?>
								<span class="label">No Logo</span><?php
							}?>
						</div>
						</div><?php
					}?>
						
						</div>
						<?php 
						//echo Form::button('Submit', array('class' => 'btn btn-primary', 'id' => 'editPartnerButton'));
						echo Form::close();
						
						echo Form::hidden('isError', 0, array('id' => 'partnerError'));
						?>


    <script type="text/javascript">
		var token = '<?php echo $token; ?>';
		$(document).ready(function() { 
	   
						var options = { 
							target:        '',   // target element(s) to be updated with server response 
							beforeSubmit:  showRequest,  // pre-submit callback 
							success:       showResponse , // post-submit callback 
					 
							// other available options: 
							url:       '/partner/pupdate',         // override for form's 'action' attribute
							type:      'post',       // 'get' or 'post', override for form's 'method' attribute 
							dataType:  'json'        // 'xml', 'script', or 'json' (expected server response type) 
							//clearForm: true        // clear all form fields after successful submit 
							//resetForm: true        // reset the form after successful submit 
					 
							// $.ajax options can be used here too, for example: 
							//timeout:   3000 
						}; 
					 
						// bind to the form's submit event 
						$('#updatePartnerButton').click(function() { 
							// inside event callbacks 'this' is the DOM element so we first 
							// wrap it in a jQuery object and then invoke ajaxSubmit 
							//alert('pp');
							$('#myModal_loading').modal();
							$('#myModal_partnerdetail').modal('hide');
							$('#editpartnerForm').ajaxSubmit(options); 
					 
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
			//$('#myModal_partnerdetail').modal('show');
			$('#partnerError').val(0);
			$("#xpopupcontent").html(responseText.msg);
			$('#myModal_success').modal();			
			return false;
		}else if(responseText.error == 1){
			//alert(responseText.msg); 
			//alert('pp');
			$('#myModal_loading').modal('hide');
			//$('#myModal_partnerdetail').modal('show');
			$('#partnerError').val(1);
			$("#xpopupcontent").html(responseText.msg);
			$('#myModal_success').modal();
			return false;
		}
		
		
	} 
	
	$('#myModal_success .row button').click(function(){
				
		//alert('pp');
		if(!parseInt($('#partnerError').val())){
			window.location.href = '/partner/'+token+'/list';
			return false;
		}else{
			
			$('#myModal_loading').modal('hide');
			$('#myModal_success').modal('hide');
			$('#myModal_partnerdetail').modal('show');
			return false;
			
		}
		
	});
	  
</script>
