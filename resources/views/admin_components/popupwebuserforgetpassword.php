<div id="myModal_wuforgetpassword" class="modal fade">
        <div class="modal-dialog" style="width: 700px">
            <div class="modal-content">
                <div class="modal-body">
                    <br/>
                    <h4 class="no-margin" id="wuforgetpassword_xpopuptransheader">Reset Password</h4>
                    <div id="wuforgetpassword_xpopuptranscontent">
                    
						<div id="success_msg_map" class="alert alert-success" style="display:none;"></div>
                                                <br>
						<?php
							echo Form::open(array('url' => '', 'id' => 'frmWebUserForgetPasswordFrm', 'onsubmit' => 'return false;', 'role' => 'form', 'name' => 'frmWebUserForgetPasswordFrm', 'class' => 'form-horizontal'));
							echo Form::hidden('wuforgetpasswordid', 0, array("id"=> 'wuforgetpasswordid'));
							?>					
						<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>" > 
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <br class="show-xs-screen"/>
                                                        <label>Password</label>
                                                        <input type="password" id="xpassword" name="xpassword" class="form-control tooltip_input" placeholder="Password" value="" onkeyup="passRequirements()">
                                                        <br>
                                                        <label>Repeat Password</label>
                                                        <input type="password" class="form-control tooltip_input" id="xpassword2" placeholder="Confirm Password" value="" onkeyup="confirmPass()">
                                                        <br/>
                                                        <br/>
                                                    </div>
                                                    <div class="col-sm-6 requirements" style="text-align: left;">
                                                        <br class="show-xs-screen"/>
                                                        <label><b>Your password must:</b></label><br/>
                                                        <label id="xsize"><span class="fa fa-close fa-sm fa-red" id="xspan_size"></span> Contain between 8 - 20 characters</label><br/>
                                                        <label id="xbegin"><span class="fa fa-close fa-sm fa-red" id="xspan_begin"></span> Begin with a letter or number</label><br/>
                                                        <label id="xuppercase"><span class="fa fa-close fa-sm fa-red" id="xspan_uppercase"></span> Include at least 1 UPPERCASE letter</label><br/>
                                                        <label id="xlowercase"><span class="fa fa-close fa-sm fa-red" id="xspan_lowercase"></span> Include at least 1 lowercase letter</label><br/>
                                                        <label id="xnumber"><span class="fa fa-close fa-sm fa-red" id="xspan_number"></span> Include at least 1 number</label><br/>
                                                        <label id="xspecial"><span class="fa fa-close fa-sm fa-red" id="xspan_special"></span> Include at least one of the following allowable special characters: # $ @ _ !</label><br/>
                                                    </div>
                                                </div>	
						<?php echo Form::close();?>
                    
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row"> 
						<div class="col-xs-6"><button type="button" style="padding: 6px" class="btn btn-md btn-primary btn-block" disabled id="xupdate">Update Password</button></div>
                        <div class="col-xs-6"><button type="button" style="padding: 6px" class="btn btn-md btn-primary btn-block" data-dismiss="modal">Cancel</button></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="/js/jquery-2.1.4.min.js"></script>
    <script type="text/javascript" src="/js/jquery.form.js"></script>
    <script src="/js/password.js"></script>
	<script type="text/javascript">
	
	$(document).ready(function() {
			var options = { 
				target:        '',   // target element(s) to be updated with server response 
				beforeSubmit:  showRequestfp,  // pre-submit callback 
				success:       showResponsefp , // post-submit callback 
		 
				// other available options: 
				url:       '/webuser/webuserforgetpassword',         // override for form's 'action' attribute
				type:      'post',       // 'get' or 'post', override for form's 'method' attribute 
				dataType:  'json'        // 'xml', 'script', or 'json' (expected server response type) 
				//clearForm: true        // clear all form fields after successful submit 
				//resetForm: true        // reset the form after successful submit 
		 
				// $.ajax options can be used here too, for example: 
				//timeout:   3000 
			}; 
		 
			// bind to the form's submit event 
			$('#xupdate').click(function() { 
				// inside event callbacks 'this' is the DOM element so we first 
				// wrap it in a jQuery object and then invoke ajaxSubmit 
				//alert('pp');
				$('#myModal_loading').modal();
				$('#frmWebUserForgetPasswordFrm').ajaxSubmit(options); 
		 
				// !!! Important !!! 
				// always return false to prevent standard browser submit and page navigation 
				return false;
				 
			}); 
			
		});
		function showRequestfp(){
			
			//$("#xpopupcontent").html(responseText.msg);
			//return false;
		}

		function showResponsefp(responseText, statusText, xhr, $form){		
			
			if(responseText.error == -1){
				//alert(responseText.msg); 
				$('#myModal_loading').modal('hide');
				$("#success_msg_map").html(responseText.msg);
				$("#success_msg_map").css('display', 'block');
						
				return false;
			}else if(responseText.error == 1){
				//alert(responseText.msg); 
				//alert('pp');
				$('#myModal_loading').modal('hide');
				var errorMsg = responseText.msg.split("||");
				var errorMsgStr = '';
				for(var i = 0;i < errorMsg.length;i++){
					errorMsgStr += errorMsg[i]+'<br/>';
				}
				$("#success_msg_map").html(errorMsgStr);
				$("#success_msg_map").css('display', 'block');
				
				return false;
			}
			
			
		}
	
	
	</script>
