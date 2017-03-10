		<?php
		if(!empty($accutransactiondetail)){?>
		<div class="table-responsive">
			<table class="table table-striped">
				<thead>
					<tr>
						
					</tr>
				</thead>
				<tbody>
				  <tr align="left">
					<td>Transaction Id</td>
					<td><?php if(isset($accutransactiondetail[0]['trans_id'])){ echo $accutransactiondetail[0]['trans_id'] ;}?></td>							
				  </tr>
				  <tr align="left">
					<td>Partner</td>
					<td><?php if(isset($accutransactiondetail[0]['partner'])){ echo $accutransactiondetail[0]['partner'] ; }?></td>							
				  </tr>
				  <tr align="left">
					<td>Group</td>
					<td><?php if(isset($accutransactiondetail[0]['group'])){ echo $accutransactiondetail[0]['group'] ;}?></td>
				 </tr>
				  <tr align="left">
					<td>Account#</td>
					<td><?php if(isset($accutransactiondetail[0]['merchant'])){ echo $accutransactiondetail[0]['merchant'] ;}?></td>							
				  </tr>
				  <tr align="left">
					<td>Name</td>
					<td><?php if(isset($accutransactiondetail[0]['first_name'])){ echo $accutransactiondetail[0]['first_name']." ".$accutransactiondetail[0]['last_name'] ; }?></td>							
				  </tr>				  
				  <tr align="left">
					<td>Pay Method</td>
					<td><?php if(isset($accutransactiondetail[0]['pay_method']) && ($accutransactiondetail[0]['pay_method'] == "ec")){echo '<img src="/img/echeck.png" alt="E-Check" data-original-title="E-Check" title="" class="tooltip_hover" data-toggle="tooltip"  />';}else if($accutransactiondetail[0]['pay_method'] == "cc"){echo '<img src="/img/credit-cards.png" alt="Credit Cards" data-original-title="Credit Cards" title="" class="tooltip_hover" data-toggle="tooltip"  />';}else if($accutransactiondetail[0]['pay_method'] == "CASH"){ echo '<img src="/img/cash.png" alt="Cash" data-original-title="Cash" title="" class="tooltip_hover" data-toggle="tooltip"  />';}else if($accutransactiondetail[0]['pay_method'] == "MISC"){ echo '<img src="/img/misc.png" alt="Misc" data-original-title="Miscellaneous" title="" class="tooltip_hover" data-toggle="tooltip"  />';}?></td>							
				  </tr>
				  <tr align="left">
					<td>Pay Type</td>
					<td><?php 
							
							$valueArray = array();
							if(isset($accutransactiondetail[0]['pay_method'])){
								$valueArray =  explode(" ", $accutransactiondetail[0]['pay_type']);
							}
							if(isset($valueArray[0]) && ($valueArray[0] == 'Visa')){
								echo '<img src="/img/visa.png" alt="Visa" data-original-title="'.$accutransactiondetail[0]['pay_type'].'" title="" class="tooltip_hover" data-toggle="tooltip"  />';
							}else if(isset($valueArray[0]) && ($valueArray[0] == 'Checking')){
								echo '<img src="/img/checking.png" alt="Checking" data-original-title="'.$accutransactiondetail[0]['pay_type'].'" title="" class="tooltip_hover" data-toggle="tooltip"  />';
							}else if(isset($valueArray[0]) && ($valueArray[0] == 'MasterCard')){
								echo '<img src="/img/mastercard.png" alt="Master Card" data-original-title="'.$accutransactiondetail[0]['pay_type'].'" title="" class="tooltip_hover" data-toggle="tooltip"  />';
							}else if(isset($valueArray[0]) && ($valueArray[0] == 'Discover')){
								echo '<img src="/img/discover.png" alt="Discover" data-original-title="'.$accutransactiondetail[0]['pay_type'].'" title="" class="tooltip_hover" data-toggle="tooltip"  />';
							}else if(isset($valueArray[0]) && ($valueArray[0] == 'Saving')){
								echo '<img src="/img/saving.png" alt="Saving" data-original-title="'.$accutransactiondetail[0]['pay_type'].'" title="" class="tooltip_hover" data-toggle="tooltip"  />';
							}
					
					?></td>							
				  </tr>
				  <tr align="left">
					<td>Amount</td>
					<td><?php if(isset($accutransactiondetail[0]['net_amount'])){ echo $accutransactiondetail[0]['net_amount'] ;}?></td>							
				  </tr>
				  <tr align="left">
					<td>Net Fee</td>
					<td><?php if(isset($accutransactiondetail[0]['net_fee'])){ echo $accutransactiondetail[0]['net_fee'] ;}?></td>							
				  </tr>
				  <tr align="left">
					<td>Net Charge</td>
					<td><?php if(isset($accutransactiondetail[0]['net_charge'])){ echo $accutransactiondetail[0]['net_charge'] ;}?></td>							
				  </tr>
				  <tr align="left">
					<td>Source</td>
					<td><?php if(isset($accutransactiondetail[0]['trans_source'])){ echo $accutransactiondetail[0]['trans_source'] ; }?></td>							
				  </tr>
				  <tr align="left">
					<td>Transaction Decription</td>
					<td><?php if(isset($accutransactiondetail[0]['trans_descr'])){ echo $accutransactiondetail[0]['trans_descr'] ;}?></td>							
				  </tr>
				  <tr align="left">
					<td>Transaction Events</td>
					<td><?php if(isset($accutransactiondetail[0]['event'])){ echo $accutransactiondetail[0]['event'] ;}?></td>						
				  </tr>
				  <tr align="left">
					<td>Auth Code</td>
					<td><?php if(isset($accutransactiondetail[0]['auth_code'])){ echo $accutransactiondetail[0]['auth_code'] ;}?></td>							
				  </tr>
				</tbody>				
			</table>
		</div><?php
		}else{?>
				<div class="alert alert-info">No Details found for this transaction</div>
		<?php
		}?>


