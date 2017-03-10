<?php  include_once __DIR__.'/admin_components/admin_header.php'; ?>
<script src="<?php echo asset('../js/bootstrap_aux.min.js'); ?>"></script>

        <?php 
        $state_list = array('AL'=>"Alabama",  
                            'AK'=>"Alaska",  
                            'AZ'=>"Arizona",  
                            'AR'=>"Arkansas",  
                            'CA'=>"California",  
                            'CO'=>"Colorado",  
                            'CT'=>"Connecticut",  
                            'DE'=>"Delaware",  
                            'DC'=>"District Of Columbia",  
                            'FL'=>"Florida",  
                            'GA'=>"Georgia",  
                            'HI'=>"Hawaii",  
                            'ID'=>"Idaho",  
                            'IL'=>"Illinois",  
                            'IN'=>"Indiana",  
                            'IA'=>"Iowa",  
                            'KS'=>"Kansas",  
                            'KY'=>"Kentucky",  
                            'LA'=>"Louisiana",  
                            'ME'=>"Maine",  
                            'MD'=>"Maryland",  
                            'MA'=>"Massachusetts",  
                            'MI'=>"Michigan",  
                            'MN'=>"Minnesota",  
                            'MS'=>"Mississippi",  
                            'MO'=>"Missouri",  
                            'MT'=>"Montana",
                            'NE'=>"Nebraska",
                            'NV'=>"Nevada",
                            'NH'=>"New Hampshire",
                            'NJ'=>"New Jersey",
                            'NM'=>"New Mexico",
                            'NY'=>"New York",
                            'NC'=>"North Carolina",
                            'ND'=>"North Dakota",
                            'OH'=>"Ohio",  
                            'OK'=>"Oklahoma",  
                            'OR'=>"Oregon",  
                            'PA'=>"Pennsylvania",  
                            'RI'=>"Rhode Island",  
                            'SC'=>"South Carolina",  
                            'SD'=>"South Dakota",
                            'TN'=>"Tennessee",  
                            'TX'=>"Texas",  
                            'UT'=>"Utah",  
                            'VT'=>"Vermont",  
                            'VA'=>"Virginia",  
                            'WA'=>"Washington",  
                            'WV'=>"West Virginia",  
                            'WI'=>"Wisconsin",
                            'WY'=>"Wyoming");
        ?>

<div class="container">
	<div class="panel-shadow">
					<?php
					$chpass_link = false;
					foreach(Session::get('user_permissions') as $p){
						if($p['route']=='wuresetpasseord'){
							$chpass_link = true;
							break;
						}
					}

					$chpassemail_link = false;
					foreach(Session::get('user_permissions') as $p){
						if($p['route']=='resetpswemail'){
							$chpassemail_link = true;
							break;
						}
					}

					?>
					<div class="row">
						<div class="col-sm-5"><h1 class="margin-t-0"><?php echo $pageTitle ; ?></h1></div>
						<div class="col-sm-7 text-right">

                                                    <?php if($webuserdetail['status']==1||$webuserdetail['status']==46): ?>
							<a target="_blank" type="button" id="login-as-this-user" class="btn btn-md btn-success btn-margin-top" href="<?php echo route('sso2',array('token'=>Illuminate\Support\Facades\Crypt::encrypt($web_user_id.'|'.$property_id['property_id'].'|9501|'.config('app.appAPIkey')))) ?>" >Login as this user</a>
                                                    <?php endif; ?>    
                                                    <?php if(($webuserdetail['status']==1||$webuserdetail['status']==46) && trim($webuserdetail['username'])!=''): ?>
														<?php if($chpass_link){ ?>
							<button type="button" id="change-user-password" onclick="resetpasswordwu('<?php echo $webuserdetail['id']; ?>');return false;" class="btn btn-md btn-success btn-margin-top" >Change Password</button>
														<?php } ?>
                                                    <?php endif; ?>    
                                                    <?php if(($webuserdetail['status']==1||$webuserdetail['status']==46) && trim($webuserdetail['username'])!=''): ?>
														<?php if($chpassemail_link){ ?>
                                                        <button type="button" id="send-user-password" onclick="resetpasswordwu1('<?php echo $webuserdetail['id']; ?>');return false;" class="btn btn-md btn-success btn-margin-top" >Send reset password email</button>
														<?php } ?>
                                                    <?php endif; ?>    
						</div>
					</div>
					<hr/>

					<div class="row">

							<div class="col-sm-6">
																<?php
					echo Form::open(array('url' => route('saveusr'), 'files' => false, 'id' => 'editWebUserForm', 'onsubmit' => 'return false;', 'role' => 'form', 'class' => 'form-horizontal'));
					echo Form::hidden('id', $webuserdetail['id'], array('id' => 'web_user_id'));
					?>
																<?php
							if($level == 'P'){?>

									<div class="form-group">
										<div class="col-xs-12">
											<div class="col-xs-4">
												<?php echo Form::label('partner_title', $layouts['layout_partner_partner']); ?>
											</div>
											<div class="col-xs-8">
												<?php echo Form::select('partner_title', $partnerlist, $webuserdetail['partner_id'], array('class' => 'form-control', 'disabled' => true));?>

											</div>
										</div>
									</div>


									<div class="form-group">
										<div class="col-xs-12">
											<div class="col-xs-4">
												<?php echo Form::label('company_name', $layouts['layout_partner_companies']); ?>
											</div>
											<div class="col-xs-8">
												<?php echo Form::select('company_name', $companylist, $webuserdetail['company_id'], array('class' => 'form-control', 'disabled' => true));?>

											</div>
										</div>
									</div>
								<?php
								}
								if($level != 'M'){?>

									<div class="form-group">
										<div class="col-xs-12">
											<div class="col-xs-4">
												<?php echo Form::label('name_clients', $layouts['layout_company_property_name']); ?>
											</div>
											<div class="col-xs-8">
												<?php echo Form::select('name_clients', $merchantlist, $webuserdetail['merchant_id'], array('class' => 'form-control', 'disabled' => true));?>

											</div>
										</div>
									</div>
								<?php
								}
																	else {
																		echo Form::hidden('name_clients', $property_id['property_id'], array('name_clients' => 'property_id'));
																	}
																	?>
																<?php if($showcn): ?>

									<div class="form-group">
										<div class="col-xs-12">
											<div class="col-xs-4">
												<?php echo Form::label('companyname', $layouts['layout_property_users_companyname']);?>
											</div>
											<div class="col-xs-8">
												<?php echo Form::text('companyname', $webuserdetail['companyname'], array('class' => 'form-control'));?>
											</div>
										</div>
									</div>

																<?php endif; ?>

									<div class="form-group">
										<div class="col-xs-12">
											<div class="col-xs-4">
												<?php echo Form::label('account_number', $acctext);?>
											</div>
											<div class="col-xs-8">
												<?php echo Form::text('account_number', $webuserdetail['webuser'], array('class' => 'form-control'));?>
											</div>
										</div>
									</div>


									<div class="form-group">
										<div class="col-xs-12">
											<div class="col-xs-4">
												<?php echo Form::label('first_name', 'First Name');?>
											</div>
											<div class="col-xs-8">
												<?php echo Form::text('first_name', $webuserdetail['first_name'], array('class' => 'form-control'));?>
											</div>
										</div>
									</div>


									<div class="form-group">
										<div class="col-xs-12">
											<div class="col-xs-4">
												<?php echo Form::label('last_name', 'Last Name');?>
											</div>
											<div class="col-xs-8">
												<?php echo Form::text('last_name', $webuserdetail['last_name'], array('class' => 'form-control'));?>
											</div>
										</div>
									</div>


									<div class="form-group">
										<div class="col-xs-12">
											<div class="col-xs-4">
												<?php echo Form::label('username', 'Username');?>
											</div>
											<div class="col-xs-8">
												<?php echo Form::text('username', $webuserdetail['username'], array('class' => 'form-control'));?>
											</div>
										</div>
									</div>


									<div class="form-group">
										<div class="col-xs-12">
											<div class="col-xs-4">
												<?php echo Form::label('email_address', 'Email');?>
											</div>
											<div class="col-xs-8">
												<?php echo Form::email('email_address', $webuserdetail['email'], array('class' => 'form-control'));?>
											</div>
										</div>
									</div>


									<div class="form-group">
										<div class="col-xs-12">
											<div class="col-xs-4">
												<?php echo Form::label('address', 'Address');?>
											</div>
											<div class="col-xs-8">
												<?php echo Form::textarea('address', $webuserdetail['address'], array('class' => 'form-control'));?>
											</div>
										</div>
									</div>


									<div class="form-group">
										<div class="col-xs-12">
											<div class="col-xs-4">
												<?php echo Form::label('phone_number', 'Phone Number');?>
											</div>
											<div class="col-xs-8">
												<?php echo Form::text('phone_number', $webuserdetail['phone'], array('class' => 'form-control'));?>
											</div>
										</div>
									</div>


									<div class="form-group">
										<div class="col-xs-12">
											<div class="col-xs-4">
												<?php echo Form::label('city', 'City');?>
											</div>
											<div class="col-xs-8">
												<?php echo Form::text('city', $webuserdetail['city'], array('class' => 'form-control'));?>
											</div>
										</div>
									</div>


									<div class="form-group">
										<div class="col-xs-12">
											<div class="col-xs-4">
												<?php echo Form::label('state', 'State');?>
											</div>
											<div class="col-xs-8">
												<?php echo Form::select('state', $state_list, $webuserdetail['state'], array('class' => 'form-control'));?>
											</div>
										</div>
									</div>


									<div class="form-group">
										<div class="col-xs-12">
											<div class="col-xs-4">
												<?php echo Form::label('zip', 'Zip');?>
											</div>
											<div class="col-xs-8">
												<?php echo Form::text('zip', $webuserdetail['zip'], array('class' => 'form-control'));?>
											</div>
										</div>
									</div>


									<div class="form-group">
										<div class="col-xs-12">
											<div class="col-xs-4">
												<?php echo Form::label('balance', 'Balance');?>
											</div>
											<div class="col-xs-8">
												<?php echo Form::text('balance', $webuserdetail['balance'], array('class' => 'form-control', 'rows' => '2'));?>
											</div>
										</div>
									</div>


									<div class="form-group">
										<div class="col-xs-12">
											<div class="col-xs-4">
												<?php echo Form::label('status', 'Status');?>
											</div>
											<div class="col-xs-8">
												<?php echo Form::select('web_status', array('1' => 'Active', '0' => 'Inactive','46'=>'Locked','998'=>'Authorized','999'=>'Unauthorized'), $webuserdetail['status'], array('class' => 'form-control'));?>

											</div>
										</div>
									</div>


									<div class="form-group">
										<div class="col-xs-12">
											<div class="col-xs-4">
												<?php echo Form::label('suppression', 'Bill Suppression');?>
											</div>
											<div class="col-xs-8">
												<?php echo Form::select('suppression', array('1' => 'Suppressed', '0' => 'Not suppressed'), $webuserdetail['suppression'], array('class' => 'form-control'));?>
											</div>
										</div>
									</div>


									<div class="form-group">
										<div class="col-xs-12">
											<div class="col-xs-6">
												<?php echo Form::button('Submit', array('class' => 'btn btn-md btn-primary btn-block', 'id' => 'updateWebuserButton')); ?>
											</div>
											<div class="col-xs-6">

												<a href="<?php echo Illuminate\Support\Facades\URL::previous(); ?>" type="button" class="btn btn-md btn-primary btn-block" id="cancelEditButton">Cancel</a>

											</div>
										</div>
									</div>

								<?php
						echo Form::close();
						echo Form::hidden('isError', 0, array('id' => 'webuserError'));
						?>
							</div>
							<div class="col-sm-6">
																<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
																	<div class="panel panel-default">
																			<div class="panel-heading" role="tab" >
																				<div class="row">
																				<div class="col-xs-8"><a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">Payment History</a></div>
																				<div class="col-xs-4">
																					<?php if($otc>0 && $webuserdetail['status']!=0 && $webuserdetail['status']!=999): ?>
																					<form method="post" action="<?php echo route('etermFind1Usr'); ?>">
																						<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>" >
																						<input type="hidden" name="xtoken" value="<?php echo Illuminate\Support\Facades\Crypt::encrypt($property_id['property_id'].'|'.$web_user_id.'|'.time().'|'.config('app.appAPIkey')); ?>" >
																						<button type="submit" id="user-make-autopayment" class="btn btn-md btn-primary" >Make a Payment</button>
																					</form>
																					<?php endif; ?>
																				</div>
																				</div>
																			</div>
																		<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
																			<div id="user-payment-info" class="panel-body">
																					<hr/>
																					<span id="usr-payment-loading" style="display:none;">Loading.....</span>
																			</div>
																		</div>
																	</div>
																	<br>
																	<?php if($rtc>0): ?>
																	<div class="panel panel-default">
																			<div class="panel-heading">
																				<div class="row">
																				<div class="col-xs-8" role="tab" ><a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">Scheduled Payments</a></div>
																				<div class="col-xs-4">
																					<?php if($rtc>0 && $webuserdetail['status']!=0 && $webuserdetail['status']!=999): ?>
																					<form method="post" action="<?php echo route('etermFind1Usr'); ?>">
																						<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>" >
																						<input type="hidden" name="xtoken" value="<?php echo Illuminate\Support\Facades\Crypt::encrypt($property_id['property_id'].'|'.$web_user_id.'|'.time().'|'.config('app.appAPIkey')); ?>" >
																						<input type="hidden" name="auto" value="1" >
																						<button type="submit" id="user-make-autopayment" class="btn btn-md btn-primary" >New Autopay</button>
																					</form>
																					<?php endif; ?>
																				</div>
																				</div>
																			</div>
																		<div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
																			<div id="auto-payment-info" class="panel-body">
																					<span id="usr-auto-payment-loading" style="display:none;">Loading.....</span>
																			</div>
																		</div>
																	</div>
																	<br>
																	<?php endif; ?>
																	<?php if(isset($einv)): ?>
																		<div class="panel panel-default">
																				<div class="panel-heading" role="tab" >
																					<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">Invoices</a>
																				</div>
																			<div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
																				<div id="usr-invoice-history-info" class="panel-body">
																						<span id="usr-invoice-history-loading" style="display:none;">Loading.....</span>
																				</div>
																			</div>
																		</div>
																	<br>
																	<?php endif; ?>
																		<div class="panel panel-default">
																				<div class="panel-heading" role="tab">
																					<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">Ticket History</a>
																				</div>
																			<div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
																				<div id="usr-ticket-history-info" class="panel-body">
																						<span id="usr-ticket-history-loading" style="display:none;">Loading.....</span>
																				</div>
																			</div>
																		</div>
																	<br>
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
                        include_once __DIR__.'/admin_components/popupwebuserpayhistorydetail.php';
			include_once __DIR__.'/components/footer.php';
		 
		?>
		
		

    <script type="text/javascript" src="/js/jquery.form.js"></script>
    <script src="/js/appvalidation.js"></script>
    <script src="/js/appvalidate.js"></script>
    <script type="text/javascript">
		var token = '<?php echo $token; ?>';
                var burl="";
		$(document).ready(function() { 					
						
						
						var options = { 
							target:        '',   // target element(s) to be updated with server response 
							beforeSubmit:  showRequest,  // pre-submit callback 
							success:       showResponse , // post-submit callback 
					 
							// other available options: 
							url:       burl+'/webuser/save',         // override for form's 'action' attribute
							type:      'post',       // 'get' or 'post', override for form's 'method' attribute 
							dataType:  'json'        // 'xml', 'script', or 'json' (expected server response type) 
							//clearForm: true        // clear all form fields after successful submit 
							//resetForm: true        // reset the form after successful submit 
					 
							// $.ajax options can be used here too, for example: 
							//timeout:   3000 
						}; 
					 
						// bind to the form's submit event 
						$('#updateWebuserButton').click(function() { 
							// inside event callbacks 'this' is the DOM element so we first 
							// wrap it in a jQuery object and then invoke ajaxSubmit 
							//alert('pp');
							$('#myModal_loading').modal();
							$('#editWebUserForm').ajaxSubmit(options); 
					 
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
			$('#webuserError').val(0);
			$('#xpopupheader').html("Success");
			$("#xpopupcontent").html(responseText.msg);
			$('#myModal_success').modal();	
			window.parent.$("html,body").animate({
				scrollTop: 0
			}, 0);		
			return false;
		}else if(responseText.error == 1){
			//alert(responseText.msg); 
			//alert('pp');
			$('#myModal_loading').modal('hide');
			$('#webuserError').val(1);
			$('#xpopupheader').html("Error");
			$("#xpopupcontent").html(responseText.msg);
			$('#myModal_success').modal();
			window.parent.$("html,body").animate({
				scrollTop: 0
			}, 0);
			return false;
		}
		
		
	} 
	
	function resetpasswordwu(wuid){
		
		$('#wuforgetpasswordid').val(wuid);
                $('#xpassword').val('');
                $('#xpassword2').val('');
		$("#myModal_wuforgetpassword").modal();			
		
	}
        
        function resetpasswordwu1(wuid){
            $('#myModal_loading').modal();
            var web_user_id = $('#web_user_id').val();
            $.get( burl+'/webuser/'+token+'/resetpswemail/'+web_user_id)
			.done(function( responseText ) {
				if(responseText.errcode == 1){
                                        //alert(responseText.msg); 
                                        $('#myModal_loading').modal('hide');
                                        $('#webuserError').val(0);
                                        $('#xpopupheader').html("Success");
                                        $("#xpopupcontent").html(responseText.msg);
                                        $('#myModal_success').modal();	
                                        window.parent.$("html,body").animate({
                                                scrollTop: 0
                                        }, 0);		
                                        return false;
                                }else {
                                        //alert(responseText.msg); 
                                        //alert('pp');
                                        $('#myModal_loading').modal('hide');
                                        $('#webuserError').val(1);
                                        $('#xpopupheader').html("Error");
                                        $("#xpopupcontent").html(responseText.msg);
                                        $('#myModal_success').modal();
                                        window.parent.$("html,body").animate({
                                                scrollTop: 0
                                        }, 0);
                                        return false;
                                }
			});
        }
	
	$('#myModal_success .row button').click(function(){
				
		//alert('pp');
		if(!parseInt($('#webuserError').val())){
			window.location.href = burl+'/webuser/'+token+'/list';
			return false;
		}else{
			
			$('#myModal_loading').modal('hide');
			$('#myModal_success').modal('hide');
			return false;
			
		}
		
	});	

	
	window.parent.$('#webusersframe').load( function(){
		
			$("#user-payment-info").addClass("in");
			$('#usr-payment-loading').css('display', 'block');
			var web_user_id = $('#web_user_id').val();
			$.get( burl+'/webuser/'+token+'/payhistory/'+web_user_id)
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
			
			$("#auto-payment-info").addClass("in");
			$('#usr-auto-payment-loading').css('display', 'block');
			var web_user_id = $('#web_user_id').val();
			$.get( burl+'/webuser/'+token+'/autopayhistory/'+web_user_id)
			.done(function( data ) {
				if(data.errcode == 0){
					$("#auto-payment-info").html(data.msg);
					$('#usr-auto-payment-loading').css('display', 'none');
					window.parent.$("html,body").animate({
						scrollTop: 0
					}, 0);	
				}
				return false;
			});	
			
			$("#usr-ticket-history-info").addClass("in");
			$('#usr-ticket-history-loading').css('display', 'block');
			var web_user_id = $('#web_user_id').val();
			$.get( burl+'/webuser/'+token+'/wutickethistory/'+web_user_id)
			.done(function( data ) {
				if(data.errcode == 0){
					$("#usr-ticket-history-info").html(data.msg);
					$('#usr-ticket-history-loading').css('display', 'none');
					window.parent.$("html,body").animate({
						scrollTop: 0
					}, 0);	
				}
				return false;
			});	
			
                        $("#usr-invoice-history-info").addClass("in");
			$('#usr-invoice-history-loading').css('display', 'block');
			var web_user_id = $('#web_user_id').val();
			$.get( burl+'/webuser/'+token+'/wuinvoicehistory/'+web_user_id)
			.done(function( data ) {
				if(data.errcode == 0){
					$("#usr-invoice-history-info").html(data.msg);
					$('#usr-invoice-history-loading').css('display', 'none');
					window.parent.$("html,body").animate({
						scrollTop: 0
					}, 0);	
				}
				return false;
			});
			
		});	
	  
</script>
<?php  include_once __DIR__.'/admin_components/admin_footer.php'; ?>
