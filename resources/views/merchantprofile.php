
<?php  include_once __DIR__.'/admin_components/admin_header.php'; ?>
<div class="container">
	<div class="panel-shadow many-fields">
			<div class="row">
				<div class="col-lg-12">
					<?php
					$lactive='merchantprofile';
					//include_once __DIR__.'/../admin_components/links_customize.php';
					include_once __DIR__.'/admin_components/links_merchants.php';
					?>
					<hr class="hr-no-margin"/>
					<h1>Profile</h1>
					<br/>
					<div class="row">
						<?php echo Form::open(array('id'=>'datamerchant','action' => array('MerchantController@merchantprofilestore', $token, $propertyId),'files' => true))?>
						<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>" >
						<div class="col-md-6">

							<div class="row">
								<div class="col-xs-12 form-group">
									<label>Title or Name</label>
									<input required type="text" name="name_clients" class="form-control" placeholder="Input the Title for this merchant" value="<?php if(isset($merchantdetail[0]['name_clients'])){echo $merchantdetail[0]['name_clients'];} ?>" >
								</div>
								<div class="col-xs-12">
									<div class="row">
										<div class="col-sm-4 form-group">
											<label>Address</label>
											<input required type="text" class="form-control" name="address_clients" placeholder="address" value="<?php if(isset($merchantdetail[0]['address_clients'])){echo $merchantdetail[0]['address_clients'];} ?>" >
										</div>
										<div class="col-sm-4 form-group">
											<label>City</label>
											<input required type="text" class="form-control" name="city_clients" placeholder="city" value="<?php if(isset($merchantdetail[0]['city_clients'])){echo $merchantdetail[0]['city_clients'];} ?>" >
										</div>
										<div class="col-sm-4 form-group">
											<label>State</label>
											<select required class="form-control"  id="xstate" name="state_clients">
												<option value="">State</option>
												<option value="AL" <?php if(isset($merchantdetail[0]['state_clients']) && $merchantdetail[0]['state_clients']=='AL'){echo 'selected';} ?>>AL</option>
												<option value="AK" <?php if(isset($merchantdetail[0]['state_clients']) && $merchantdetail[0]['state_clients']=='AK'){echo 'selected';} ?>>AK</option>
												<option value="AZ" <?php if(isset($merchantdetail[0]['state_clients']) && $merchantdetail[0]['state_clients']=='AZ'){echo 'selected';} ?>>AZ</option>
												<option value="AR" <?php if(isset($merchantdetail[0]['state_clients']) && $merchantdetail[0]['state_clients']=='AR'){echo 'selected';} ?>>AR</option>
												<option value="CA" <?php if(isset($merchantdetail[0]['state_clients']) && $merchantdetail[0]['state_clients']=='CA'){echo 'selected';} ?>>CA</option>
												<option value="CO" <?php if(isset($merchantdetail[0]['state_clients']) && $merchantdetail[0]['state_clients']=='CO'){echo 'selected';} ?>>CO</option>
												<option value="CT" <?php if(isset($merchantdetail[0]['state_clients']) && $merchantdetail[0]['state_clients']=='CT'){echo 'selected';} ?>>CT</option>
												<option value="DE" <?php if(isset($merchantdetail[0]['state_clients']) && $merchantdetail[0]['state_clients']=='DE'){echo 'selected';} ?>>DE</option>
												<option value="DC" <?php if(isset($merchantdetail[0]['state_clients']) && $merchantdetail[0]['state_clients']=='DC'){echo 'selected';} ?>>DC</option>
												<option value="FL" <?php if(isset($merchantdetail[0]['state_clients']) && $merchantdetail[0]['state_clients']=='FL'){echo 'selected';} ?>>FL</option>
												<option value="GA" <?php if(isset($merchantdetail[0]['state_clients']) && $merchantdetail[0]['state_clients']=='GA'){echo 'selected';} ?>>GA</option>
												<option value="HI" <?php if(isset($merchantdetail[0]['state_clients']) && $merchantdetail[0]['state_clients']=='HI'){echo 'selected';} ?>>HI</option>
												<option value="ID" <?php if(isset($merchantdetail[0]['state_clients']) && $merchantdetail[0]['state_clients']=='ID'){echo 'selected';} ?>>ID</option>
												<option value="IL" <?php if(isset($merchantdetail[0]['state_clients']) && $merchantdetail[0]['state_clients']=='IL'){echo 'selected';} ?>>IL</option>
												<option value="IN" <?php if(isset($merchantdetail[0]['state_clients']) && $merchantdetail[0]['state_clients']=='IN'){echo 'selected';} ?>>IN</option>
												<option value="IA" <?php if(isset($merchantdetail[0]['state_clients']) && $merchantdetail[0]['state_clients']=='IA'){echo 'selected';} ?>>IA</option>
												<option value="KS" <?php if(isset($merchantdetail[0]['state_clients']) && $merchantdetail[0]['state_clients']=='KS'){echo 'selected';} ?>>KS</option>
												<option value="KY" <?php if(isset($merchantdetail[0]['state_clients']) && $merchantdetail[0]['state_clients']=='KY'){echo 'selected';} ?>>KY</option>
												<option value="LA" <?php if(isset($merchantdetail[0]['state_clients']) && $merchantdetail[0]['state_clients']=='LA'){echo 'selected';} ?>>LA</option>
												<option value="MA" <?php if(isset($merchantdetail[0]['state_clients']) && $merchantdetail[0]['state_clients']=='MA'){echo 'selected';} ?>>MA</option>
												<option value="ME" <?php if(isset($merchantdetail[0]['state_clients']) && $merchantdetail[0]['state_clients']=='ME'){echo 'selected';} ?>>ME</option>
												<option value="MD" <?php if(isset($merchantdetail[0]['state_clients']) && $merchantdetail[0]['state_clients']=='MD'){echo 'selected';} ?>>MD</option>
												<option value="MI" <?php if(isset($merchantdetail[0]['state_clients']) && $merchantdetail[0]['state_clients']=='MI'){echo 'selected';} ?>>MI</option>
												<option value="MN" <?php if(isset($merchantdetail[0]['state_clients']) && $merchantdetail[0]['state_clients']=='MN'){echo 'selected';} ?>>MN</option>
												<option value="MS" <?php if(isset($merchantdetail[0]['state_clients']) && $merchantdetail[0]['state_clients']=='MS'){echo 'selected';} ?>>MS</option>
												<option value="MO" <?php if(isset($merchantdetail[0]['state_clients']) && $merchantdetail[0]['state_clients']=='MO'){echo 'selected';} ?>>MO</option>
												<option value="MT" <?php if(isset($merchantdetail[0]['state_clients']) && $merchantdetail[0]['state_clients']=='MT'){echo 'selected';} ?>>MT</option>
												<option value="NE" <?php if(isset($merchantdetail[0]['state_clients']) && $merchantdetail[0]['state_clients']=='NE'){echo 'selected';} ?>>NE</option>
												<option value="NV" <?php if(isset($merchantdetail[0]['state_clients']) && $merchantdetail[0]['state_clients']=='NV'){echo 'selected';} ?>>NV</option>
												<option value="NH" <?php if(isset($merchantdetail[0]['state_clients']) && $merchantdetail[0]['state_clients']=='NH'){echo 'selected';} ?>>NH</option>
												<option value="NJ" <?php if(isset($merchantdetail[0]['state_clients']) && $merchantdetail[0]['state_clients']=='NJ'){echo 'selected';} ?>>NJ</option>
												<option value="NM" <?php if(isset($merchantdetail[0]['state_clients']) && $merchantdetail[0]['state_clients']=='NM'){echo 'selected';} ?>>NM</option>
												<option value="NY" <?php if(isset($merchantdetail[0]['state_clients']) && $merchantdetail[0]['state_clients']=='NY'){echo 'selected';} ?>>NY</option>
												<option value="NC" <?php if(isset($merchantdetail[0]['state_clients']) && $merchantdetail[0]['state_clients']=='NC'){echo 'selected';} ?>>NC</option>
												<option value="ND" <?php if(isset($merchantdetail[0]['state_clients']) && $merchantdetail[0]['state_clients']=='ND'){echo 'selected';} ?>>ND</option>
												<option value="OH" <?php if(isset($merchantdetail[0]['state_clients']) && $merchantdetail[0]['state_clients']=='OH'){echo 'selected';} ?>>OH</option>
												<option value="OK" <?php if(isset($merchantdetail[0]['state_clients']) && $merchantdetail[0]['state_clients']=='OK'){echo 'selected';} ?>>OK</option>
												<option value="OR" <?php if(isset($merchantdetail[0]['state_clients']) && $merchantdetail[0]['state_clients']=='OR'){echo 'selected';} ?>>OR</option>
												<option value="PA" <?php if(isset($merchantdetail[0]['state_clients']) && $merchantdetail[0]['state_clients']=='PA'){echo 'selected';} ?>>PA</option>
												<option value="RI" <?php if(isset($merchantdetail[0]['state_clients']) && $merchantdetail[0]['state_clients']=='RI'){echo 'selected';} ?>>RI</option>
												<option value="SC" <?php if(isset($merchantdetail[0]['state_clients']) && $merchantdetail[0]['state_clients']=='SC'){echo 'selected';} ?>>SC</option>
												<option value="SD" <?php if(isset($merchantdetail[0]['state_clients']) && $merchantdetail[0]['state_clients']=='SD'){echo 'selected';} ?>>SD</option>
												<option value="TN" <?php if(isset($merchantdetail[0]['state_clients']) && $merchantdetail[0]['state_clients']=='TN'){echo 'selected';} ?>>TN</option>
												<option value="TX" <?php if(isset($merchantdetail[0]['state_clients']) && $merchantdetail[0]['state_clients']=='TX'){echo 'selected';} ?>>TX</option>
												<option value="UT" <?php if(isset($merchantdetail[0]['state_clients']) && $merchantdetail[0]['state_clients']=='UT'){echo 'selected';} ?>>UT</option>
												<option value="VT" <?php if(isset($merchantdetail[0]['state_clients']) && $merchantdetail[0]['state_clients']=='VT'){echo 'selected';} ?>>VT</option>
												<option value="VA" <?php if(isset($merchantdetail[0]['state_clients']) && $merchantdetail[0]['state_clients']=='VA'){echo 'selected';} ?>>VA</option>
												<option value="WA" <?php if(isset($merchantdetail[0]['state_clients']) && $merchantdetail[0]['state_clients']=='WA'){echo 'selected';} ?>>WA</option>
												<option value="WV" <?php if(isset($merchantdetail[0]['state_clients']) && $merchantdetail[0]['state_clients']=='WV'){echo 'selected';} ?>>WV</option>
												<option value="WI" <?php if(isset($merchantdetail[0]['state_clients']) && $merchantdetail[0]['state_clients']=='WI'){echo 'selected';} ?>>WI</option>
												<option value="WY" <?php if(isset($merchantdetail[0]['state_clients']) && $merchantdetail[0]['state_clients']=='WY'){echo 'selected';} ?>>WY</option>
												<option value="PR" <?php if(isset($merchantdetail[0]['state_clients']) && $merchantdetail[0]['state_clients']=='PR'){echo 'selected';} ?>>PR</option>
											</select>
										</div>
									</div>
								</div>
								<div class="col-xs-12">
									<div class="row">
										<div class="col-sm-6 form-group">
											<label>Zip</label>
											<input required type="text" class="form-control" name="zip_clients" placeholder="zip" value="<?php if(isset($merchantdetail[0]['zip_clients'])){echo $merchantdetail[0]['zip_clients'];} ?>" >
										</div>
										<div class="col-sm-6 form-group">
											<label>Phone</label>
											<input required type="text" class="form-control" name="phone_clients" placeholder="Phone Number" value="<?php if(isset($merchantdetail[0]['phone_clients'])){echo $merchantdetail[0]['phone_clients'];} ?>" >
										</div>
									</div>
								</div>

								<div class="col-xs-12">
									<div class="row">
										<div class="col-xs-6 form-group">
											<label>Logo</label>
											<input id="logo" type="file" name="logo" >
										</div>
										<div class="col-xs-6 form-group text-right">

											<label>&nbsp;<br/><br/></label>
											<?php if(isset($merchantdetail[0]['logo'])&&$merchantdetail[0]['logo']!=""){ echo '<img style="display: inline-block" class="img-responsive" src="'.$merchantdetail[0]['logo'].'" >';} ?>
										</div>
									</div>
								</div>

								<div class="col-xs-12">
									<div class="row">
										<div class="col-sm-6 form-group">
											<label>Number of units</label>
											<input class="form-control" name="units" placeholder="Number of units" value="<?php if(isset($merchantdetail[0]['units']) && $merchantdetail[0]['units']){ echo $merchantdetail[0]['units'];} ?>" type="text">
										</div>
										<div class="col-sm-6 form-group">
											<label>Old API Account</label>
											<input class="form-control" name="oldapiaccount" placeholder="Old API Account" value="<?php if(isset($merchantdetail[0]['id_api_account']) && $merchantdetail[0]['id_api_account']){ echo $merchantdetail[0]['id_api_account'];} ?>" type="text">
										</div>
									</div>
								</div>

								<div class="col-xs-12">
									<div class="row">
										<div class="col-sm-6 form-group">
											<label><input id="statusclients" name="statusclients" type="checkbox" <?php if(isset($merchantdetail[0]['status_clients']) && $merchantdetail[0]['status_clients'] ==1){ echo 'checked';} ?>/> Active</label>
										</div>
										<div class="col-sm-6 form-group">
											<label><input name="statuspp" type="checkbox" <?php if(isset($merchantdetail[0]['status_pp']) && $merchantdetail[0]['status_pp'] ==1){ echo 'checked';} ?>/> Active Payment Page</label>
										</div>
									</div>
								</div>

								<div class="col-xs-12 form-group">
									<label>Identifier (ID)</label>
									<input required type="text" name="compositeID_clients" class="form-control" placeholder="Input the identifier for this merchant" value="<?php if(isset($merchantdetail[0]['compositeID_clients'])){echo $merchantdetail[0]['compositeID_clients'];} ?>" >
								</div>
								<div class="col-xs-12 form-group hidden">
									<label>Base Layout to apply</label>
									<select  name="playout_id" class="form-control">
										<option value="0" <?php if(isset($playout_id) && $playout_id==0){ echo 'selected';} ?>>Inherit</option>
										<option value="1" <?php if(isset($playout_id) && $playout_id==1){ echo 'selected';} ?>>Property</option>
										<option value="2" <?php if(isset($playout_id) && $playout_id==2){ echo 'selected';} ?>>Academic</option>
										<option value="6" <?php if(isset($playout_id) && $playout_id==6){ echo 'selected';} ?>>B2B</option>
										<option value="13" <?php if(isset($playout_id) && $playout_id==13){ echo 'selected';} ?>>Non-Profit</option>
										<option value="14" <?php if(isset($playout_id) && $playout_id==14){ echo 'selected';} ?>>Utility</option>
										<option value="15" <?php if(isset($playout_id) && $playout_id==15){ echo 'selected';} ?>>B2C</option>
									</select>
								</div>

								<div class="col-xs-12 form-group">
									<br/>
									<label>Additional Identifiers</label>
									<div class="alert alert-warning">
										<p>The parameters below can be inserted in your automated reports.</p>
										<br/>

										<div class="row">
											<div class="col-sm-6 form-group">
												<label>LockBox ID</label>
												<input type="text" class="form-control" name="lockbox_id" placeholder="Lockbox ID" value="<?php if(isset($merchantdetail[0]['lockbox_id'])){echo $merchantdetail[0]['lockbox_id'];} ?>" >
											</div>
											<div class="col-sm-6 form-group">
												<label>Bank ID</label>
												<input type="text" class="form-control" name="bank_id" placeholder="Bank ID" value="<?php if(isset($merchantdetail[0]['bank_id'])){echo $merchantdetail[0]['bank_id'];} ?>" >
											</div>
										</div>

										<div class="form-group">
											<label>Misc.</label>
											<input type="text" class="form-control" name="misc_id" placeholder="Misc Field" value="<?php if(isset($merchantdetail[0]['misc_field'])){echo $merchantdetail[0]['misc_field'];} ?>" >
										</div>
									</div>
								</div>
								<div class="col-xs-12 form-group">
									<label>Contact Name</label>
									<input required type="text" class="form-control" name="contact_name_clients" placeholder="Contact Name" value="<?php if(isset($contact['contact_name_clients'])){echo $contact['contact_name_clients'];} ?>" >
								</div>
								<div class="col-xs-12">
									<div class="row">
										<div class="col-sm-6 form-group">
											<label>Contact Email</label>
											<input required type="text" class="form-control" name="email_address_clients" placeholder="Contact Email" value="<?php if(isset($merchantdetail[0]['email_address_clients'])){echo $merchantdetail[0]['email_address_clients'];} ?>" >
										</div>
										<div class="col-sm-6 form-group">
											<label>Accounting Email(s)</label>
											<input required type="text" class="form-control" name="accounting_email_address_clients" placeholder="Accounting Email(s)" value="<?php if(isset($merchantdetail[0]['accounting_email_address_clients'])){echo $merchantdetail[0]['accounting_email_address_clients'];} ?>" >
										</div>
									</div>
								</div>


								<div class="col-sm-12">
									<button class="btn btn-primary form-control btn-full" type="submit">Save Settings</button>
									<br/>
									<br/>
								</div>
							</div>
						</div>
						</form>
						<div class="col-md-6">
							<label>&nbsp;</label>
							<div class="row">
								<div class="col-xs-6 form-group">
									<a class="btn btn-block btn-primary hidden" href="">Open Application</a>
								</div>
								<div class="col-xs-6 form-group">
									<a target="_blank" href="<?php echo "../../../../../../bis/inc/loadmn.php?admid=$adminid&mnid=$propertyId";?>" class="btn btn-block btn-primary" href="">Open Merchant</a>
								</div>
							</div>

							<div class="panel panel-default">
								<div class="panel-body">
									<?php echo Form::open(array('id'=>'movemerchant','action' => array('MerchantController@movemerchantsubmit', $token,$propertyId)))?>
									<div class="row">
										<div class="col-sm-6 form-group">
											<label>Partner</label>
											<select required name="select_partner" data="select_companies" class="form-control select_p">
												<?php foreach($partners as $p){?>
												<option <?php if($p['id'] == $merchantdetail['id_partners']){ ?>selected<?php }?> value="<?php echo $p['id'];?>"><?php echo $p['partner_title'] ?></option>
												<?php }?>
											</select>
										</div>
										<div class="col-sm-6 form-group">
											<label>Group</label>
											<select required name="select_companies" class="form-control" id="select_companies"></select>
										</div>
										<div class="col-xs-12">
											<button type="submit" class="btn btn-block btn-primary">Move it</button>
											<br/>
											<a href="<?php echo $merchantdetail['url_clients'];?>/index.php" target="_blank">Click here to go to the default landing page</a>
										</div>

									</div>
									</form>
								</div>
							</div>


							<div class="panel panel-default">
								<div class="panel-body">
									<h4>IVR Account</h4>
									<hr/>
									<?php echo Form::open(array('id'=>'ivr','action' => array('MerchantController@merchantivraccountStore', $token,$propertyId)))?>
									<div class="row">
										<div class="col-sm-12 form-group">
											<label>Ivr ID</label>
											<input required name="ivr_id" value="<?php if(isset($ivr['ivr_id']) && $ivr['ivr_id']){echo $ivr['ivr_id'];};?>" type="text" class="form-control ">
										</div>
										<div class="col-sm-12 form-group">
											<label>Validation Type </label>
											<select name="validation_type" class="form-control" required>
												<option value=""></option>
												<option <?php if(isset($ivr['type_validation']) && $ivr['type_validation']==1){echo 'selected';};?> value="1">Account Number </option>
												<option <?php if(isset($ivr['type_validation']) && $ivr['type_validation']==2){echo 'selected';};?> value="2">Invoice Number </option>
											</select>
										</div>

									</div>
									<div class="row">
										<div class="col-sm-12 form-group">
											<label>Phone Number</label>
											<input required name="phone_number" value="<?php if(isset($ivr['phone_number']) && $ivr['phone_number']){echo $ivr['phone_number'];};?>" type="text" class="form-control">
										</div>
										<div class="col-sm-12 form-group">
											<label>
												<input value="1" name="give_balance" <?php if(isset($ivr['give_balance'])){  if($ivr['give_balance']==1) echo  'checked'; }else{ echo 'checked';}?> type="checkbox">
												Give Balance</label>
										</div>

									</div>
									<div class="row">
										<div class="col-sm-12">
											<button type="submit" class="btn btn-primary btn-block">Submit</button>
										</div>
									</div>
									</form>
								</div>
							</div>



						</div>

					</div>
				</div>
			</div>
			</div>
			</div>
<div id="modal_removecredentials" class="modal fade">
	<div class="modal-dialog" style="width: 400px">
		<div class="modal-content">
			<div class="modal-body">
				Please confirm that you want to deactivate this account. All the payment credentials will be deleted and the autopayments will be canceled.
				<br/>
				<br/>
				<b>Do you want deactivate this account?</b>
			</div>
			<div class="modal-footer">
				<div class="row">
					<div class="col-md-6 btn-margin-xs-screen"><button id="btn-no-modal" type="button" class="btn btn-default form-control btn-full" data-dismiss="modal">No</button><br/></div>
					<div class="col-md-6"><button class="btn btn-primary form-control btn-full" data-dismiss="modal">Yes</button> </div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
	
    $popuphdr="";
    $popupcontent="";  
	include_once __DIR__.'/components/popupsuccess.php';
	include_once __DIR__.'/components/loading.php';
    //include_once __DIR__.'/../components/footer.php';
?>

<?php  include_once __DIR__.'/admin_components/admin_footer.php'; ?>

<script type="text/javascript" src="/js/jquery.form.js"></script>
<script src="/js/jquery.validatev2.js"></script>
<script src="/js/jquery.validate.additional.js"></script>
<script type="text/javascript">
	$('#statusclients').change(function () {
		if(!$(this).prop('checked') ) {
			$('#modal_removecredentials').modal('show');
		}
	});

	$('#btn-no-modal').click(function () {
		$('#statusclients').prop("checked", "checked");
	});

	$.validator.addMethod('positiveNumber',
		function (value) {
			if(value){
				return Number(value) > 0;
			}
			else{
				return true;
			}

		}, 'Enter a positive number');

	$("#datamerchant").validate({
		//debug: true,
		ignore: '*:not([name])',
		rules: {
			logo: {
				extension: "jpg|png",
				filesize: 12
			},
			units:{
				positiveNumber:true
			},
			oldapiaccount:{
				maxlength: 50
			}
		}
	});

	$("#movemerchant").validate({
		ignore: '*:not([name])'
	});

	$("#ivr").validate({
		ignore: '*:not([name])'
	});

var token = '<?php echo $token; ?>';


$('.select_p').change(function(){
	id_ref = $(this).attr('data');
	url = "<?php echo route('companiesbypartner',['id_partner'=>'0']) ?>";
	url = url.replace('/0','/'+$(this).val());
	$.ajax({
			method: "GET",
			url: url
		})
		.success(function( msg ) {
			$('#'+id_ref).html(msg);
		});
});


<?php
Session::set('company', '0');
?>
id_partner = $( ".select_p option:selected").val();
url = "<?php echo route('companiesbypartner',['id_partner'=>'0']) ?>";
url = url.replace('/0','/'+id_partner);
$.ajax({
		method: "GET",
		url: url
	})
	.success(function( msg ) {
		$('#select_companies').html(msg);
		$('#select_companies option[value="<?php echo $merchantdetail['id_companies']; ?>"]').attr("selected", "selected")
	});

</script>
