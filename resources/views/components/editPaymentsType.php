<div class="alert alert-danger" style="display: none" id="xerror"><b>Error! </b>Please select a payment category before proceeding.</div><br>
<label class="blue">My Payment Details</label>
    <?php for ($j = 0; $j < count($paymentCategories); $j++) {?>
                        <div class="row check">
                            <div class="col-xs-6">
                                <span class="text check-active" data-ref="<?php echo 'checkbox'.$j; ?>"><?php echo $paymentCategories[$j]['payment_type_name']; ?></span>
                                <div class="checkbox checkbox-info pull-right">
                                    <input type="checkbox" <?php if (isset($paymentCategories[$j]['enabled']) && $paymentCategories[$j]['enabled']==1) echo "checked";?> aria-label="Single checkbox Two" onchange="CalculateFee('<?php echo $autopay_amount['trans_payment_type'];?>')" value="option2" id="xcheckpay_<?php echo $j;?>" name="xcheckpay_<?php echo $j;?>" class="styled styled-primary <?php echo 'checkbox'.$j; ?> checkbox-active-input""><label></label>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input  type="text" <?php if($paymentCategories[$j]['amount']!="0.00" && !isset($paymentCategories[$j]['enabled'])) echo "disabled";?> onkeyup="onKeyUp(<?php echo $j; ?>)" type="text" aria-describedby="basic-addon1" onchange="CalculateFee('<?php echo $autopay_amount['trans_payment_type'];?>')" value="<?php echo $paymentCategories[$j]['amount'];?>" id="xinputpay_<?php echo $j;?>" name="<?php echo $paymentCategories[$j]['payment_type_id'];?>" class="form-control text-right input-active" xname="<?php echo $paymentCategories[$j]['payment_type_name']; ?>">
                                </div>
                            </div>
                        </div>
                        <br>
    <?php }?> 
                        <div class="alert alert-success" id="xalertdiv" style="display: none">
                        NOTE: A standard convenience fee will be added to the total amount deducted from your payment method.
                        </div>
                        <div class="row total">
                            <div class="col-xs-6">
                                <label>Net Amount:</label>
                            </div>
                            <div class="col-xs-6 text-right">
                                <label class="price" id="xpretotal">$<?php echo $autopay_amount['trans_recurring_net_amount'];?></label>
                            </div>
                        </div>
                        <div class="row total" id="xservice_fee">
                            <div class="col-xs-6">
                                <label>Convenience Fee:</label>
                            </div>
                            <div class="col-xs-6 text-right">
                                <label class="price" id="xconvfee">$<?php echo $autopay_amount['trans_recurring_convenience_fee'];?></label>
                            </div>
                        </div>
                        <div class="row total">
                            <div class="col-xs-6">
                                <label>Total Payment Amount:</label>
                            </div>
                            <div class="col-xs-6 text-right">
                                <label class="price" id="xtotal">$<?php echo $autopay_amount['trans_recurring_net_amount']+$autopay_amount['trans_recurring_convenience_fee'];?></label>
                            </div>
                        </div>
                        <br>
                        <div class="row check" style="<?php if(isset($nomemo) && $nomemo==1) echo "display:none";?>">
                            <div class="col-xs-4">
                                <span class="text">Memo (optional):</span>
                            </div>
                            <div class="col-xs-8 ">
                                <input class="form-control" placeholder="Enter your note / memo here" id="xmemo">
                            </div>
                        </div>
<br/>
<br/>
<script src="/js/app.js"></script>

<script>
    <?php
    if(isset($dbcredentials['ec'])){
        echo 'var aux_rc_fee_ec=' . json_encode($dbcredentials['ec']) . ';';
    }else echo 'var aux_rc_fee_ec="";';

    if(isset($dbcredentials['cc'])){
        echo 'var aux_rc_fee_cc=' . json_encode($dbcredentials['cc']) . ';';
    }else echo 'var aux_rc_fee_cc="";';

    if(isset($dbcredentials['amex'])){
        echo 'var aux_rc_fee_amex=' .  json_encode($dbcredentials['amex']) . ';';
    }else echo 'var aux_rc_fee_amex="";';
    ?>

</script>
