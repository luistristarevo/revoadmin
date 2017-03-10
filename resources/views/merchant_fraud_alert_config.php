<?php  include_once __DIR__.'/admin_components/admin_header.php'; ?>
<script src="<?php echo asset('../js/bootstrap_aux.min.js'); ?>"></script>

<div class="container">
	<div class="panel-shadow many-fields">
				<div class="row">
                    <div class="col-lg-12">
                        <?php 
                            $lactive='fraud_control';
                            //include_once __DIR__.'/../admin_components/links_customize.php';
                            include_once __DIR__.'/admin_components/links_merchants.php';
                        ?>

                        <hr class="hr-no-margin"/>
						<h1><?php echo $pageTitle ; ?></h1>
						<br/>
                    </div>


                 </div>


				<?php
				//if(!empty($fraud_control_config)){
					?>

				<?php echo Form::open(array('id'=>'echeckform','action' => array('MerchantController@merchantfraudcontrolstore', $token, $propertyId)))?>
				<div class="row">
					<div class="col-md-6">

						<input type="checkbox" <?php if(isset($fraud_control_config['fraud10a'])){ echo 'checked="checked"'; } ?> name="fraud10a" id="fraud10a" class="check">
						<label style="margin-left: 20px">Units for properties without value in our table</label>
						<input type="text" class="form-control" name="fraud10" id="fraud10" value="<?php if(isset($fraud_control_config['fraud10'])){ echo $fraud_control_config['fraud10']; } ?>">
						<br/>
						<br/>
						<h6 style="font-weight: bold;text-decoration: underline;">Fraud Controls Alerts and Parameters</h6>
						<br/>
						<input type="checkbox" name="fraud1a" id="fraud1a" <?php if(isset($fraud_control_config['fraud1a'])){ echo 'checked="checked"'; } ?> class="check"/>
						<label style="margin-left: 20px">% of Unit Count is Max # of Authorization Requests allowed in 1 day</label>
						<select id="fraud1" name="fraud1" class="form-control">
							<option value="30" <?php if(isset($fraud_control_config['fraud1']) && ( $fraud_control_config['fraud1']==30))echo 'selected';  ?> >30</option>
							<option value="50" <?php if(isset($fraud_control_config['fraud1']) && ($fraud_control_config['fraud1']==50))echo 'selected';  ?>>50</option>
							<option value="70" <?php if(isset($fraud_control_config['fraud1']) && ($fraud_control_config['fraud1'] == 70))echo 'selected';  ?>>70</option>
							<option value="90" <?php if(isset($fraud_control_config['fraud1']) && ( $fraud_control_config['fraud1']==90))echo 'selected';  ?>>90</option>
							<option value="100" <?php if(isset($fraud_control_config['fraud1']) && ( $fraud_control_config['fraud1'])==100)echo 'selected';  ?>>100</option>
							<option value="150" <?php if(isset($fraud_control_config['fraud1']) && ( $fraud_control_config['fraud1']>=150))echo 'selected';  ?>>150</option>
						</select>

						<br/>
						<input type="checkbox" class="check" name="fraud2a" id="fraud2a" <?php if(isset($fraud_control_config['fraud2a'])){ echo 'checked="checked"'; } ?>/>
						<label style="margin-left: 20px">Max # of repeated Authorization Requests within 1 min for <br/>the same Cardholder Account</label>
						<input type="text" name="fraud2" class="form-control" id="fraud2"  value="<?php if(isset($fraud_control_config['fraud2'])){ echo $fraud_control_config['fraud2'];} ?>"/>
						<br/>

						<input type="checkbox" class="check" name="fraud3a" id="fraud3a" <?php if(isset($fraud_control_config['fraud3a'])){ echo 'checked="checked"'; } ?>/> &nbsp;
						<label style="margin-left: 20px">% increase in Max Authorization Requests allowed over highest <br/>historical month</label>
						<select name="fraud3" id="fraud3" class="form-control">
							<option value="150" <?php if(isset($fraud_control_config['fraud3']) && ( $fraud_control_config['fraud3'] == 150))echo 'selected';  ?>>150</option>
							<option value="300" <?php if(isset($fraud_control_config['fraud3']) && ( $fraud_control_config['fraud3'] == 300))echo 'selected';  ?>>300</option>
							<option value="350" <?php if(isset($fraud_control_config['fraud3']) && ( $fraud_control_config['fraud3'] == 350))echo 'selected';  ?>>350</option>
							<option value="400" <?php if(isset($fraud_control_config['fraud3']) && ( $fraud_control_config['fraud3'] == 400))echo 'selected';  ?>>400</option>
							<option value="450" <?php if(isset($fraud_control_config['fraud3']) && ( $fraud_control_config['fraud3'] == 450 ))echo 'selected';  ?>>450</option>
							<option value="500" <?php if(isset($fraud_control_config['fraud3']) && ( $fraud_control_config['fraud3'] == 500) )echo 'selected';  ?>>500</option>
						</select>
						<br/>
						<input type="checkbox" class="check" name="fraud4a" id="fraud4a" <?php if(isset($fraud_control_config['fraud4a'])){ echo 'checked="checked"'; } ?>/> &nbsp;
						<label style="margin-left: 20px">% increase in Max Merchant Monthly Deposit Volume allowed over <br/> highest historical month</label>
						<select name="fraud4" id="fraud4" class="form-control">
							<option value="150" <?php if(isset($fraud_control_config['fraud4']) && ( $fraud_control_config['fraud4'] == 150))echo 'selected';  ?>>150</option>
							<option value="300" <?php if(isset($fraud_control_config['fraud4']) && ( $fraud_control_config['fraud4'] == 300))echo 'selected';  ?>>300</option>
							<option value="350" <?php if(isset($fraud_control_config['fraud4']) && ( $fraud_control_config['fraud4'] == 350))echo 'selected';  ?>>350</option>
							<option value="400" <?php if(isset($fraud_control_config['fraud4']) && ( $fraud_control_config['fraud4'] == 400 ))echo 'selected';  ?>>400</option>
							<option value="450" <?php if(isset($fraud_control_config['fraud4']) && ( $fraud_control_config['fraud4'] == 450))echo 'selected';  ?>>450</option>
							<option value="500" <?php if(isset($fraud_control_config['fraud4']) && ( $fraud_control_config['fraud4'] == 500))echo 'selected';  ?>>500</option>
						</select>
						<br/>

						<input type="checkbox" class="check" name="fraud5a" id="fraud5a" <?php if(isset($fraud_control_config['fraud5a'])){ echo 'checked="checked"'; } ?>/> &nbsp;
						<label style="margin-left: 20px">% increase in Merchants Historical Average Ticket Size</label>
						<select id="fraud5" name="fraud5" class="form-control">
							<option value="150" <?php if(isset($fraud_control_config['fraud5']) && ( $fraud_control_config['fraud5'] == 150))echo 'selected';  ?>>150</option>
							<option value="300" <?php if(isset($fraud_control_config['fraud5']) && ( $fraud_control_config['fraud5'] == 300 ))echo 'selected';  ?>>300</option>
							<option value="350" <?php if(isset($fraud_control_config['fraud5']) && ( $fraud_control_config['fraud5'] == 350))echo 'selected';  ?>>350</option>
							<option value="400" <?php if(isset($fraud_control_config['fraud5']) && ( $fraud_control_config['fraud5'] == 400))echo 'selected';  ?>>400</option>
							<option value="450" <?php if(isset($fraud_control_config['fraud5']) && ( $fraud_control_config['fraud5'] == 450) )echo 'selected';  ?>>450</option>
							<option value="500" <?php if(isset($fraud_control_config['fraud5']) && ( $fraud_control_config['fraud5'] == 500 ))echo 'selected';  ?>>500</option>
						</select>
						<br/>
						<input type="checkbox" name="fraud6a" class="check" id="fraud6a" <?php if(isset($fraud_control_config['fraud6a'])){ echo 'checked="checked"'; } ?>/>
						<label style="margin-left: 20px">Create Alert for the first transaction of in an inactive Merchant <br/>with 90 days of inactivity</label>
						<br/>
						<input type="checkbox" class="check" name="fraud7a" id="fraud7a" <?php if(isset($fraud_control_config['fraud7a'])){ echo 'checked="checked"'; } ?>/>
						<label style="margin-left: 20px">Max # of permitted transactions per month</label>
						<select id="fraud7" name="fraud7" class="form-control">
							<option value="30" <?php if(isset($fraud_control_config['fraud7']) && ( $fraud_control_config['fraud7'] == 30))echo 'selected';  ?> >30% of Unit Count</option>
							<option value="50" <?php if(isset($fraud_control_config['fraud7']) && ( $fraud_control_config['fraud7'] == 50))echo 'selected';  ?>>50% of Unit Count</option>
							<option value="70" <?php if(isset($fraud_control_config['fraud7']) && ( $fraud_control_config['fraud7'] == 70))echo 'selected';  ?>>70% of Unit Count</option>
							<option value="90" <?php if(isset($fraud_control_config['fraud7']) && ( $fraud_control_config['fraud7'] == 90 ))echo 'selected';  ?>>90% of Unit Count</option>
							<option value="100" <?php if(isset($fraud_control_config['fraud7']) && ( $fraud_control_config['fraud7'] == 100))echo 'selected';  ?>>100% of Unit Count</option>
							<option value="150" <?php if(isset($fraud_control_config['fraud7']) && ( $fraud_control_config['fraud7'] >= 150))echo 'selected';  ?>>150% of Unit Count</option>
						</select>
						<br/>
						<input type="checkbox" class="check" name="fraud8a" id="fraud8a" <?php if(isset($fraud_control_config['fraud8a'])){ echo 'checked="checked"'; } ?>/>
						<label style="margin-left: 20px">Max # of permitted transactions per Deposit (Batch)</label>
						<select id="fraud8" name="fraud8" class="form-control">
							<option value="30" <?php if(isset($fraud_control_config['fraud8']) && ( $fraud_control_config['fraud8'] == 30) )echo 'selected';  ?> >30</option>
							<option value="50" <?php if(isset($fraud_control_config['fraud8']) && ( $fraud_control_config['fraud8'] == 50))echo 'selected';  ?>>50</option>
							<option value="70" <?php if(isset($fraud_control_config['fraud8']) && ( $fraud_control_config['fraud8'] == 70))echo 'selected';  ?>>70</option>
							<option value="90" <?php if(isset($fraud_control_config['fraud8']) && ( $fraud_control_config['fraud8'] == 90))echo 'selected';  ?>>90</option>
							<option value="100" <?php if(isset($fraud_control_config['fraud8']) && ( $fraud_control_config['fraud8'] == 100))echo 'selected';  ?>>100</option>
							<option value="150" <?php if(isset($fraud_control_config['fraud8']) && ( $fraud_control_config['fraud8'] >= 150))echo 'selected';  ?>>150</option>% of Unit Count
						</select>
						<br/>
						<input type="checkbox" class="check" name="fraud9a" id="fraud9a" <?php if(isset($fraud_control_config['fraud9a'])){ echo 'checked="checked"'; } ?>/>
						<label style="margin-left: 20px">Create Alert for any transaction below this minimum transaction<br/> (Amount $)</label>
						<input type="text" class="form-control" name="fraud9" id="fraud9" value="<?php if(isset($fraud_control_config['fraud9'])){ echo $fraud_control_config['fraud9']; }?>">
						<br/>
						<input type="checkbox" class="check" name="fraud11a" id="fraud11a" <?php if(isset($fraud_control_config['fraud11a'])){ echo 'checked="checked"'; } ?>/>
						<label style="margin-left: 20px">Max # of payments a web user can submit within an hour</label>
						<input type="text" class="form-control" name="fraud11" id="fraud11" value="<?php if(isset($fraud_control_config['fraud11'])){ echo $fraud_control_config['fraud11'];} ?>">
						<br/>
						<input type="checkbox" class="check" name="fraud12a" id="fraud12a" <?php if(isset($fraud_control_config['fraud12a'])){ echo 'checked="checked"'; } ?>/>
						<label style="margin-left: 20px">Max # of payments a web user can submit within an day</label>
						<input type="text" class="form-control" name="fraud12" id="fraud12" value="<?php if(isset($fraud_control_config['fraud12'])){ echo $fraud_control_config['fraud12'];} ?>">
                                                <br/>
                                                <input type="checkbox" class="check" name="fraud13a" id="fraud13a" <?php if(isset($fraud_control_config['fraud13a'])){ echo 'checked="checked"'; } ?>/>
						<label style="margin-left: 20px">Max # of declined payments allowed by merchant within an hour</label>
						<input type="text" class="form-control" name="fraud13" id="fraud13" value="<?php if(isset($fraud_control_config['fraud13'])){ echo $fraud_control_config['fraud13'];} ?>">
						<div class="row">
							<div class="col-md-12">
								<br/>
								<button type="submit" class="btn btn-primary btn-block">Update Alerts</button>

							</div>
						</div>
					</div>
				</div>
				</form>

				<?php
				//}

				?>
</div>
</div>

<?php  include_once __DIR__.'/admin_components/admin_footer.php'; ?>


    <?php
		include_once __DIR__.'/components/footer.php';
    ?>
    <script>
            $('#myModal_success').modal();

			$.validator.addMethod('positiveNumber',
				function (value) {
					if(value){
						return Number(value) > 0;
					}
					else{
						return true;
					}

				}, 'Enter a positive number');

			$("#echeckform").validate({
				ignore: '*:not([name])',
				rules: {
					fraud10:{
						digits: true,
						positiveNumber: true
					},
					fraud2:{
						digits: true,
						positiveNumber: true
					},
					fraud9:{
						digits: true,
						positiveNumber: true
					},
					fraud11:{
						digits: true,
						positiveNumber: true
					},
					fraud12:{
						digits: true,
						positiveNumber: true
					},
					fraud13:{
						digits: true,
						positiveNumber: true
					},
				}
			});

    </script>        


