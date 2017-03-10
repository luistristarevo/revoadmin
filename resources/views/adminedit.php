<?php  include_once __DIR__.'/admin_components/admin_header.php'; ?>
	<script src="<?php echo asset('../js/bootstrap_aux.min.js'); ?>"></script>
<div class="container">
	<div class="panel-shadow">
		<div class="fadein">
						<h1 class="margin-t-0"><?php echo $pageTitle ; ?></h1>
						<hr/>
						<div class="row">
								<div class="col-sm-6"><?php
								echo Form::open(array('url' => '', 'id' => 'editAdminForm', 'onsubmit' => 'return false;', 'role' => 'form', 'class' => 'form-horizontal'));
								echo Form::hidden('id', $user_detail[0]['id'], array('id' => 'admin_user_id'));
								?>
									<div class="col-xs-12">
									<div class="row form-group">
											<div class="col-xs-4">
												<?php echo Form::label('first_name', 'First Name'); ?>
											</div>
											<div class="col-xs-8">
												<?php echo Form::text('first_name', $user_detail[0]['first_name'], array('class' => 'form-control'));?>
											</div>
									</div>


										<div class="row form-group">
											<div class="col-xs-4">
												<!--<b></b>-->
												<?php echo Form::label('last_name', 'Last Name'); ?>
											</div>
											<div class="col-xs-8">
												<?php echo Form::text('last_name', $user_detail[0]['last_name'], array('class' => 'form-control'));?>
											</div>
										</div>



										<div class="row form-group">
											<div class="col-xs-4">
												<!--<b>Status</b>-->
												<?php echo Form::label('email', 'Email'); ?>
											</div>
											<div class="col-xs-8">
												<?php echo Form::text('email_address', $user_detail[0]['email'], array('class' => 'form-control'));?>
											</div>
										</div>


										<div class="row form-group">
											<div class="col-xs-4"><!--<b>Logo</b>-->
												<?php echo Form::label('username', 'Login');?>
											</div>
											<div class="col-xs-8">
												<?php echo Form::text('login', $user_detail[0]['login'], array('class' => 'form-control'));?>									
											</div>
										</div>


										<div class="row form-group">
											<div class="col-xs-4"><!--<b>Logo</b>-->
												<?php echo Form::label('phone', 'Phone');?>
											</div>
											<div class="col-xs-8">
												<?php echo Form::text('phone', $user_detail[0]['phone'], array('class' => 'form-control'));?>									
											</div>
										</div>

									<!--<div class="row" style="text-align:left;">
										<div class="form-group">
											<div class="col-xs-6"><b>Logo</b>
												<?php //echo Form::label('password', 'Password');?>
											</div>
											<div class="col-xs-6">										
												<?php //echo Form::text('password', $user_detail[0]['password'], array('class' => 'form-control'));?>									
											</div>
										</div>
									</div>-->

										<div class="row form-group">
											<div class="col-xs-4"><!--<b>Logo</b>-->
												<?php echo Form::label('status', 'Status');?>
											</div>
											<div class="col-xs-8">
												<?php echo Form::select('status', array('1' => 'Active', '0' => 'Inactive'), $user_detail[0]['status'], array('class' => 'form-control'));?>
											</div>
										</div>

									<div class="row">
										<div class="form-group">
											<div class="col-xs-12">
												<div class="col-xs-6">
													<button type="button" class="btn btn-primary btn-block" id="updateAdminButton">Save</button>
												</div>												
												<div class="col-xs-6">

													<a href="<?php echo Illuminate\Support\Facades\URL::previous(); ?>" type="button" class="btn btn-block btn-primary" id="cancelEditButton">Cancel</a>
												</div>
											</div>
										</div>
									</div>

									<?php 
									//echo Form::button('Submit', array('class' => 'btn btn-primary', 'id' => 'editGroupButton'));
									echo Form::close();						
									echo Form::hidden('isError', 0, array('id' => 'adminError'));
									?>																	
								</div>
								</div>
								<div class="col-sm-6">
									<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
										<div class="panel panel-default">	
												<div class="col-xs-12 panel-heading">
													<div class="col-xs-8"><a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">Has privileges</a></div>
													<div class="col-xs-4">
														
													</div>
												</div>
											<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
												<div id="user-payment-info" class="panel-body">
														<hr/>
														<span id="usr-payment-loading" style="display:none;">Loading.....</span>
												</div>
											</div>    
										</div>								
									</div>
									<div class="panel-group" id="accordionTwo" role="tablist" aria-multiselectable="true">
										<div class="panel panel-default">
											<div class="col-xs-12 panel-heading">
												<div class="col-xs-8"><a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">Could access</a></div>
												<div class="col-xs-4">

												</div>
											</div>
											<div id="collapseTwo" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
												<div id="admin-access-info" class="panel-body">
													<hr/>
													<span id="admin-access-loading" style="display:none;">Loading.....</span>
												</div>
											</div>
										</div>
									</div>
									<div class="panel-group" id="accordionThree" role="tablist" aria-multiselectable="true">
										<div class="panel panel-default">
											<div class="col-xs-12 panel-heading">
												<div class="col-xs-8"><a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">Assign Roles</a></div>
												<div class="col-xs-4">

												</div>
											</div>
											<div id="collapseThree" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
												<div id="admin-assign-role-info" class="panel-body">
													<hr/>
													<span id="admin-assign-role-loading" style="display:none;">Loading.....</span>
												</div>
											</div>
										</div>
									</div>
									<div class="panel-group" id="accordionFour" role="tablist" aria-multiselectable="true">
										<div class="panel panel-default">
											<div class="col-xs-12 panel-heading">
												<div class="col-xs-8"><a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">Assign Privileges</a></div>
												<div class="col-xs-4">

												</div>
											</div>
											<div id="collapseFour" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
												<div id="admin-assign-priviledge-info" class="panel-body">
													<hr/>
													<span id="admin-assign-priviledge-loading" style="display:none;">Loading.....</span>
												</div>
											</div>
										</div>
									</div>
								</div>

						</div>
						</div>
	</div>
</div>

		<?php
			$popuphdr="Success!";
			$popupcontent="";						
			include_once __DIR__.'/components/popupsuccess.php';
			include_once __DIR__.'/components/loading.php';
			include_once __DIR__.'/admin_components/popupwebuserforgetpassword.php';
			include_once __DIR__.'/components/footer.php';
		 
		?>
		
		

    <script type="text/javascript" src="/js/jquery.form.js"></script>
    <script src="/js/appvalidation.js"></script>
    <script src="/js/appvalidate.js"></script>
    <script type="text/javascript">
		var token = '<?php echo $token; ?>';
		$(document).ready(function() { 
	   
						var options = { 
							target:        '',   // target element(s) to be updated with server response 
							beforeSubmit:  showRequest,  // pre-submit callback 
							success:       showResponse , // post-submit callback 
					 
							// other available options: 
							url:       '/admins/adminedit',         // override for form's 'action' attribute
							type:      'post',       // 'get' or 'post', override for form's 'method' attribute 
							dataType:  'json'        // 'xml', 'script', or 'json' (expected server response type) 
							//clearForm: true        // clear all form fields after successful submit 
							//resetForm: true        // reset the form after successful submit 
					 
							// $.ajax options can be used here too, for example: 
							//timeout:   3000 
						}; 
					 
						// bind to the form's submit event 
						$('#updateAdminButton').click(function() { 
							// inside event callbacks 'this' is the DOM element so we first 
							// wrap it in a jQuery object and then invoke ajaxSubmit 
							//alert('pp');
							$('#myModal_loading').modal();
							$('#myModal_admindetail').modal('hide');
							$('#editAdminForm').ajaxSubmit(options); 
					 
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
			$('#adminError').val(0);
			$("#xpopupcontent").html(responseText.msg);
			$('#myModal_success').modal();			
			return false;
		}else if(responseText.error == 1){
			//alert(responseText.msg); 
			//alert('pp');
			$('#myModal_loading').modal('hide');
			//$('#myModal_groupdetail').modal('show');
			$('#adminError').val(1);
			$("#xpopupcontent").html(responseText.msg);
			$('#myModal_success').modal();
			return false;
		}
		
		
	} 
	
	$('#myModal_success .row button').click(function(){
				
		//alert('pp');
		if(!parseInt($('#adminError').val())){
			window.location.href = '/admins/'+token+'/list';
			return false;
		}else{
			
			$('#myModal_loading').modal('hide');
			$('#myModal_success').modal('hide');
			$('#myModal_admindetail').modal('show');
			return false;
			
		}
		
	});	
	
	window.parent.$('#adminusersframe').load( function(){
		
			$("#user-payment-info").addClass("in");
			$('#usr-payment-loading').css('display', 'block');
			var user_id = $('#admin_user_id').val();
			$.get( '/master/index.php/admins/'+token+'/accessto/'+user_id)
			.done(function( data ) {
				if(data.errcode == 0){
					$("#user-payment-info").html(data.msg);
					$('#usr-payment-loading').css('display', 'none');
					window.parent.$("html,body").animate({
						scrollTop: 0
					}, 0);	
				}
				return false;
			});
			
			
			$("#admin-access-info").addClass("in");
			$('#admin-access-loading').css('display', 'block');
			var user_id = $('#admin_user_id').val();
			$.get( '/master/index.php/admins/'+token+'/accesslevel/'+user_id)
			.done(function( data ) {
				if(data.errcode == 0){
					$("#admin-access-info").html(data.msg);
					$('#admin-access-loading').css('display', 'none');
					window.parent.$("html,body").animate({
						scrollTop: 0
					}, 0);	
				}
				return false;
			});
			
			$("#admin-assign-priviledge-info").addClass("in");
			$('#admin-assign-priviledge-loading').css('display', 'block');
			var user_id = $('#admin_user_id').val();
			$.get( '/master/index.php/admins/'+token+'/adminassignprivilege/'+user_id)
			.done(function( data ) {
				if(data.errcode == 0){
					$("#admin-assign-priviledge-info").html(data.msg);
					$('#admin-assign-priviledge-loading').css('display', 'none');
					window.parent.$("html,body").animate({
						scrollTop: 0
					}, 0);	
				}
				return false;
			});
			
			$("#admin-assign-role-info").addClass("in");
			$('#admin-assign-role-loading').css('display', 'block');
			var user_id = $('#admin_user_id').val();
			$.get( '/master/index.php/admins/'+token+'/adminassignrole/'+user_id)
			.done(function( data ) {
				if(data.errcode == 0){
					$("#admin-assign-role-info").html(data.msg);
					$('#admin-assign-role-loading').css('display', 'none');
					window.parent.$("html,body").animate({
						scrollTop: 0
					}, 0);	
				}
				return false;
			});
			
			
		});
	  
</script>

<?php  include_once __DIR__.'/admin_components/admin_footer.php'; ?>