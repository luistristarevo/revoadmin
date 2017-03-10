<?php  include_once __DIR__.'/admin_components/admin_header.php'; ?>
<div class="container">
    <div class="panel-shadow many-fields">

                <?php
                $lactive='payment_credentials';
                //include_once __DIR__.'/../admin_components/links_customize.php';
                include_once __DIR__.'/admin_components/links_merchants.php';
                ?>
                <hr class="hr-no-margin"/>
                <h1>Payment Credentials</h1>
                <br/>

                <div class="row">
                    <?php echo Form::open(array('id'=>'echeckform','action' => array('MerchantController@merchantPCEcheckStore', $token, $propertyId)))?>
                    <div class="col-sm-6">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-xs-6"><h4>Echeck</h4></div>
                                    <div class="col-xs-6 text-right"><button data="<?php echo route('removecredentials',array('token'=>$token,'type'=>'echeck', 'id'=>$propertyId));?>" id="removeecheck" type="button" class="btn btn-sm btn-danger"><span class="fa fa-trash-o"></span> Remove Credentials</button></div>
                                </div>
                                <hr/>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <?php
                                        $form_ec=array();
                                        foreach ($data as $d){
                                            if(isset($d['ecgateway'])){
                                                $form_ec = $d;
                                                break;
                                            }
                                        }
                                        ?>

                                        <label>Gateway</label>
                                        <select id="ecgateway" name="ecgateway" required class="form-control">
                                            <option role="option" value=""></option>
                                            <option data-validate="ecmid,ecsourcekey,ecstoreid,eclocationid" <?php if(isset($form_ec['ecgateway']) && $form_ec['ecgateway']=='profistars') echo 'selected'; ?> role="option" value="profistars">Profistars</option>
                                            <option data-validate="" <?php if(isset($form_ec['ecgateway']) && $form_ec['ecgateway']=='bokf') echo 'selected'; ?> role="option" value="bokf">BOKF</option>
                                            <option data-validate="ecmid" <?php if(isset($form_ec['ecgateway']) && $form_ec['ecgateway']=='express') echo 'selected'; ?> role="option" value="express">Express</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>MID</label>
                                        <input <?php if(isset($form_ec['ecmid']) && $form_ec['ecmid']) echo 'value="'.$form_ec['ecmid'].'"'; ?> name="ecmid" type="text" class="form-control optional">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        <label>Source Key</label>
                                        <input <?php if(isset($form_ec['ecsourcekey']) && $form_ec['ecsourcekey']) echo 'value="'.$form_ec['ecsourcekey'].'"'; ?> name="ecsourcekey" type="text" class="form-control optional">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label>Store ID</label>
                                        <input <?php if(isset($form_ec['ecstoreid']) && $form_ec['ecstoreid']) echo 'value="'.$form_ec['ecstoreid'].'"'; ?> name="ecstoreid" type="text" class="form-control optional">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>Location ID</label>
                                        <input <?php if(isset($form_ec['eclocationid']) && $form_ec['eclocationid']) echo 'value="'.$form_ec['eclocationid'].'"'; ?> name="eclocationid" type="text" class="form-control optional">
                                    </div>
                                </div>
                                <br/>
                                <div class="row" >
                                    <div class="col-md-12">
                                        <label>
                                            <input data="ecot-dynamicform" id="ecwot" name="ecwot" class="checkenabled" type="checkbox">
                                            ECheck Web One Time
                                        </label>
                                    </div>
                                    <?php
                                    $check_ecwot = false;
                                    $ecwot_first = array();
                                    foreach ($data as $key => $d) {
                                        if (isset($d['ecwot'])) {
                                            $ecwot_first = $d;
                                            unset($data[$key]);
                                            $check_ecwot = true;
                                            break;
                                        }
                                    }
                                    ?>

                                    <div class="col-md-4 form-group">
                                        <label>Low pay range</label>
                                        <input <?php if(isset($ecwot_first['ecwotlow_pay_range']) && $ecwot_first['ecwotlow_pay_range']) echo 'value="'.$ecwot_first['ecwotlow_pay_range'].'"'; ?> name="ecWOTlpr" type="text"  disabled class="form-control">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label>High pay range</label>
                                        <input <?php if(isset($ecwot_first['ecwothigh_pay_range']) && $ecwot_first['ecwothigh_pay_range']) echo 'value="'.$ecwot_first['ecwothigh_pay_range'].'"'; ?> name="ecWOThpr" type="text" disabled class="form-control">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label>High Ticket</label>
                                        <input <?php if(isset($ecwot_first['ecwothigh_ticket']) && $ecwot_first['ecwothigh_ticket']) echo 'value="'.$ecwot_first['ecwothigh_ticket'].'"'; ?> name="ecWOTht" type="text" disabled class="form-control">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>CFee</label>
                                        <input <?php if(isset($ecwot_first['ecwotconvenience_fee']) && $ecwot_first['ecwotconvenience_fee']) echo 'value="'.$ecwot_first['ecwotconvenience_fee'].'"'; ?> name="ecWOTcf" type="text" disabled class="form-control" value="0.00">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>% CFee</label>
                                        <input <?php if(isset($ecwot_first['ecwotconvenience_fee_float']) && $ecwot_first['ecwotconvenience_fee_float']) echo 'value="'.$ecwot_first['ecwotconvenience_fee_float'].'"'; ?> name="ecWOTlpcf" type="text" disabled class="form-control" value="0.00">
                                    </div>

                                </div>
                                <div id="ecot-dynamicform" class="dynamic-form">
                                    <div class="form-template hidden">
                                        <div>
                                        <h4>Tier #{{tiernumber}}</h4>
                                        <hr/>
                                        <div class="row">
                                            <div class="col-md-4 form-group">
                                                <label>Low pay range</label>
                                                <input name="ecWOTlprDYNAMIC{{tiernumber}}" required disabled="" class="form-control" type="text">
                                            </div>
                                            <div class="col-md-4 form-group">
                                                <label>High pay range</label>
                                                <input name="ecWOThprDYNAMIC{{tiernumber}}" required disabled="" class="form-control" type="text">
                                            </div>
                                            <div class="col-md-4 form-group">
                                                <label>High Ticket</label>
                                                <input name="ecWOThtDYNAMIC{{tiernumber}}" required disabled="" class="form-control" type="text">
                                            </div>
                                            <div class="col-md-4 form-group">
                                                <label>CFee</label>
                                                <input name="ecWOTcfDYNAMIC{{tiernumber}}" required disabled="" class="form-control" value="0.00" type="text">
                                            </div>
                                            <div class="col-md-5 form-group">
                                                <label>% CFee</label>
                                                <input name="ecWOTlpcfDYNAMIC{{tiernumber}}" required disabled="" class="form-control" value="0.00" type="text">
                                            </div>
                                            <div class="col-md-3 form-group">
                                                <label>&nbsp;</label>
                                                <button type="button" class="btn btn-danger btn-block removebtn"><span class="fa fa-trash-o"></span> Remove</button>
                                            </div>

                                        </div>
                                        </div>
                                    </div>
                                    <div class="form-cont">
                                        <?php
                                        $cont = 1;
                                        foreach ($data as $d){
                                            if(isset($d['ecwot'])){
                                                $cont++;
                                            ?>
                                            <div>
                                                <h4>Tier #<?php echo $cont; ?></h4>
                                                <hr/>
                                                <div class="row">
                                                    <div class="col-md-4 form-group">
                                                        <label>Low pay range</label>
                                                        <input <?php if(isset($d['ecwotlow_pay_range']) && $d['ecwotlow_pay_range']) echo 'value="'.$d['ecwotlow_pay_range'].'"'; ?> name="ecWOTlprDYNAMIC<?php echo $cont;?>" disabled="" class="form-control" type="text">
                                                    </div>
                                                    <div class="col-md-4 form-group">
                                                        <label>High pay range</label>
                                                        <input <?php if(isset($d['ecwothigh_pay_range']) && $d['ecwothigh_pay_range']) echo 'value="'.$d['ecwothigh_pay_range'].'"'; ?> name="ecWOThprDYNAMIC<?php echo $cont;?>" disabled="" class="form-control" type="text">
                                                    </div>
                                                    <div class="col-md-4 form-group">
                                                        <label>High Ticket</label>
                                                        <input <?php if(isset($d['ecwothigh_ticket']) && $d['ecwothigh_ticket']) echo 'value="'.$d['ecwothigh_ticket'].'"'; ?> name="ecWOThtDYNAMIC<?php echo $cont;?>" disabled="" class="form-control" type="text">
                                                    </div>
                                                    <div class="col-md-4 form-group">
                                                        <label>CFee</label>
                                                        <input <?php if(isset($d['ecwotconvenience_fee']) && $d['ecwotconvenience_fee']) echo 'value="'.$d['ecwotconvenience_fee'].'"'; ?> name="ecWOTcfDYNAMIC<?php echo $cont;?>" disabled="" class="form-control" value="0.00" type="text">
                                                    </div>
                                                    <div class="col-md-5 form-group">
                                                        <label>% CFee</label>
                                                        <input <?php if(isset($d['ecwotconvenience_fee_float']) && $d['ecwotconvenience_fee_float']) echo 'value="'.$d['ecwotconvenience_fee_float'].'"'; ?> name="ecWOTlpcfDYNAMIC<?php echo $cont;?>" disabled="" class="form-control" value="0.00" type="text">
                                                    </div>
                                                    <div class="col-md-3 form-group">
                                                        <label>&nbsp;</label>
                                                        <button type="button" class="btn btn-danger btn-block removebtn"><span class="fa fa-trash-o"></span> Remove</button>
                                                    </div>

                                                </div>
                                            </div>
                                            <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                    <button data="<?php echo $cont+1; ?>" type="button" class="btn btn-xs btn-default btn-success addtier"><span class="fa fa-plus"></span> Add Tier</button>
                                </div>
                                <br/>


                                <div class="row">
                                    <div class="col-md-12">
                                        <label>
                                            <input id="ecwr" data="ewr-dynamicform" name="ecwr" class="checkenabled" type="checkbox">
                                            ECheck Web Recurring
                                        </label>
                                    </div>

                                    <?php
                                    $check_ecwr = false;
                                    $ecwr_first = array();
                                    foreach ($data as $key => $d) {
                                        if (isset($d['ecwr'])) {
                                            $ecwr_first = $d;
                                            unset($data[$key]);
                                            $check_ecwr = true;
                                            break;
                                        }
                                    }
                                    ?>

                                    <div class="col-md-4 form-group">
                                        <label>Low pay range</label>
                                        <input <?php if(isset($ecwr_first['ecwrlow_pay_range']) && $ecwr_first['ecwrlow_pay_range']) echo 'value="'.$ecwr_first['ecwrlow_pay_range'].'"'; ?> name="ecWRlpr" type="text"  disabled class="form-control">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label>High pay range</label>
                                        <input <?php if(isset($ecwr_first['ecwrhigh_pay_range']) && $ecwr_first['ecwrhigh_pay_range']) echo 'value="'.$ecwr_first['ecwrhigh_pay_range'].'"'; ?> name="ecWRhpr" type="text" disabled class="form-control">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label>High Ticket</label>
                                        <input <?php if(isset($ecwr_first['ecwrhigh_ticket']) && $ecwr_first['ecwrhigh_ticket']) echo 'value="'.$ecwr_first['ecwrhigh_ticket'].'"'; ?> name="ecWRht" type="text" disabled class="form-control">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>CFee</label>
                                        <input <?php if(isset($ecwr_first['ecwrconvenience_fee']) && $ecwr_first['ecwrconvenience_fee']) echo 'value="'.$ecwr_first['ecwrconvenience_fee'].'"'; ?> name="ecWRcf" type="text" disabled class="form-control" value="0.00">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>% CFee</label>
                                        <input <?php if(isset($ecwr_first['ecwrconvenience_fee_float']) && $ecwr_first['ecwrconvenience_fee_float']) echo 'value="'.$ecwr_first['ecwrconvenience_fee_float'].'"'; ?> name="ecWRpcf" type="text" disabled class="form-control" value="0.00">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>CFee DRP</label>
                                        <input <?php if(isset($ecwr_first['ecwrconvenience_fee_drp']) && $ecwr_first['ecwrconvenience_fee_drp']) echo 'value="'.$ecwr_first['ecwrconvenience_fee_drp'].'"'; ?> name="ecWRcfDrp" type="text" disabled class="form-control" value="0.00">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>% CFee DRP</label>
                                        <input <?php if(isset($ecwr_first['ecwrconvenience_fee_float_drp']) && $ecwr_first['ecwrconvenience_fee_float_drp']) echo 'value="'.$ecwr_first['ecwrconvenience_fee_float_drp'].'"'; ?> name="ecWRpcfDrp" type="text" disabled class="form-control" value="0.00">
                                    </div>
                                </div>
                                <div id="ewr-dynamicform" class="dynamic-form">
                                    <div class="form-template hidden">
                                        <div>
                                            <h4>Tier #{{tiernumber}}</h4>
                                            <hr/>
                                            <div class="row">
                                                <div class="col-md-4 form-group">
                                                    <label>Low pay range</label>
                                                    <input name="ecWRlprDYNAMIC{{tiernumber}}" type="text" required  disabled class="form-control">
                                                </div>
                                                <div class="col-md-4 form-group">
                                                    <label>High pay range</label>
                                                    <input name="ecWRhprDYNAMIC{{tiernumber}}" type="text" required disabled class="form-control">
                                                </div>
                                                <div class="col-md-4 form-group">
                                                    <label>High Ticket</label>
                                                    <input name="ecWRhtDYNAMIC{{tiernumber}}" type="text" required disabled class="form-control">
                                                </div>
                                                <div class="col-md-2 form-group">
                                                    <label>CFee</label>
                                                    <input name="ecWRcfDYNAMIC{{tiernumber}}" type="text" required disabled class="form-control" value="0.00">
                                                </div>
                                                <div class="col-md-2 form-group">
                                                    <label>% CFee</label>
                                                    <input name="ecWRpcfDYNAMIC{{tiernumber}}" type="text" required disabled class="form-control" value="0.00">
                                                </div>
                                                <div class="col-md-2 form-group">
                                                    <label>CFee DRP</label>
                                                    <input name="ecWRcfDrpDYNAMIC{{tiernumber}}" type="text" required disabled class="form-control" value="0.00">
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <label>% CFee DRP</label>
                                                    <input name="ecWRpcfDrpDYNAMIC{{tiernumber}}" type="text" required disabled class="form-control" value="0.00">
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <label>&nbsp;</label>
                                                    <button type="button" class="btn btn-danger btn-block removebtn"><span class="fa fa-trash-o"></span> Remove</button>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-cont">
                                        <?php
                                        $cont = 1;
                                        foreach ($data as $d){
                                        if(isset($d['ecwr'])){
                                        $cont++;
                                        ?>
                                            <div>
                                                <h4>Tier #<?php echo $cont; ?></h4>
                                                <hr/>
                                                <div class="row">
                                                    <div class="col-md-4 form-group">
                                                        <label>Low pay range</label>
                                                        <input <?php if(isset($d['ecwrlow_pay_range']) && $d['ecwrlow_pay_range']) echo 'value="'.$d['ecwrlow_pay_range'].'"'; ?> name="ecWRlprDYNAMIC<?php echo $cont;?>" type="text" required  disabled class="form-control">
                                                    </div>
                                                    <div class="col-md-4 form-group">
                                                        <label>High pay range</label>
                                                        <input <?php if(isset($d['ecwrhigh_pay_range']) && $d['ecwrhigh_pay_range']) echo 'value="'.$d['ecwrhigh_pay_range'].'"'; ?> name="ecWRhprDYNAMIC<?php echo $cont;?>" type="text" required disabled class="form-control">
                                                    </div>
                                                    <div class="col-md-4 form-group">
                                                        <label>High Ticket</label>
                                                        <input <?php if(isset($d['ecwrhigh_ticket']) && $d['ecwrhigh_ticket']) echo 'value="'.$d['ecwrhigh_ticket'].'"'; ?> name="ecWRhtDYNAMIC<?php echo $cont;?>" type="text" required disabled class="form-control">
                                                    </div>
                                                    <div class="col-md-2 form-group">
                                                        <label>CFee</label>
                                                        <input <?php if(isset($d['ecwrconvenience_fee']) && $d['ecwrconvenience_fee']) echo 'value="'.$d['ecwrconvenience_fee'].'"'; ?> name="ecWRcfDYNAMIC<?php echo $cont;?>" type="text" required disabled class="form-control" value="0.00">
                                                    </div>
                                                    <div class="col-md-2 form-group">
                                                        <label>% CFee</label>
                                                        <input <?php if(isset($d['ecwrconvenience_fee_float']) && $d['ecwrconvenience_fee_float']) echo 'value="'.$d['ecwrconvenience_fee_float'].'"'; ?> name="ecWRpcfDYNAMIC<?php echo $cont;?>" type="text" required disabled class="form-control" value="0.00">
                                                    </div>
                                                    <div class="col-md-2 form-group">
                                                        <label>CFee DRP</label>
                                                        <input <?php if(isset($d['ecwrconvenience_fee_drp']) && $d['ecwrconvenience_fee_drp']) echo 'value="'.$d['ecwrconvenience_fee_drp'].'"'; ?> name="ecWRcfDrpDYNAMIC<?php echo $cont;?>" type="text" required disabled class="form-control" value="0.00">
                                                    </div>
                                                    <div class="col-md-3 form-group">
                                                        <label>% CFee DRP</label>
                                                        <input <?php if(isset($d['ecwrconvenience_fee_float_drp']) && $d['ecwrconvenience_fee_float_drp']) echo 'value="'.$d['ecwrconvenience_fee_float_drp'].'"'; ?> name="ecWRpcfDrpDYNAMIC<?php echo $cont;?>" type="text" required disabled class="form-control" value="0.00">
                                                    </div>
                                                    <div class="col-md-3 form-group">
                                                        <label>&nbsp;</label>
                                                        <button type="button" class="btn btn-danger btn-block removebtn"><span class="fa fa-trash-o"></span> Remove</button>
                                                    </div>

                                                </div>
                                            </div>
                                        <?php
                                        }
                                        }
                                        ?>

                                    </div>
                                    <button data="<?php echo $cont+1; ?>" type="button" class="btn btn-xs btn-default btn-success addtier"><span class="fa fa-plus"></span> Add Tier</button>
                                </div>
                                <br/>


                                <div class="row hide">
                                    <div class="col-md-12">
                                        <label>
                                            <input id="eceot" name="eceot" class="checkenabled" type="checkbox">
                                            ECheck Eterminal One Time
                                        </label>

                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>Low pay range</label>
                                        <input <?php if(isset($data['eceotlow_pay_range']) && $data['eceotlow_pay_range']) echo 'value="'.$data['eceotlow_pay_range'].'"'; ?> name="ecEOTlpr" type="text"  disabled class="form-control">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>High pay range</label>
                                        <input <?php if(isset($data['eceothigh_pay_range']) && $data['eceothigh_pay_range']) echo 'value="'.$data['eceothigh_pay_range'].'"'; ?> name="ecEOThpr" type="text" disabled class="form-control">
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label>High Ticket</label>
                                        <input <?php if(isset($data['eceothigh_ticket']) && $data['eceothigh_ticket']) echo 'value="'.$data['eceothigh_ticket'].'"'; ?> name="ecEOTht" type="text" disabled class="form-control">
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label>CFee</label>
                                        <input <?php if(isset($data['eceotconvenience_fee']) && $data['eceotconvenience_fee']) echo 'value="'.$data['eceotconvenience_fee'].'"'; ?> name="ecEOTcf" type="text" disabled class="form-control" value="0.00">
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label>% CFee</label>
                                        <input <?php if(isset($data['eceotconvenience_fee_float']) && $data['eceotconvenience_fee_float']) echo 'value="'.$data['eceotconvenience_fee_float'].'"'; ?> name="ecEOTpcf" type="text" disabled class="form-control" value="0.00">
                                    </div>
                                </div>
                                <br/>
                                <div class="row hide">
                                    <div class="col-md-12">
                                        <label>
                                            <input id="ecer" name="ecer" class="checkenabled" type="checkbox">
                                            ECheck Eterminal Recurring
                                        </label>

                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>Low pay range</label>
                                        <input <?php if(isset($data['ecerlow_pay_range']) && $data['ecerlow_pay_range']) echo 'value="'.$data['ecerlow_pay_range'].'"'; ?> name="ecERlpr" type="text"  disabled class="form-control">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>High pay range</label>
                                        <input <?php if(isset($data['ecerhigh_pay_range']) && $data['ecerhigh_pay_range']) echo 'value="'.$data['ecerhigh_pay_range'].'"'; ?> name="ecERhpr" type="text" disabled class="form-control">
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label>High Ticket</label>
                                        <input <?php if(isset($data['ecerhigh_ticket']) && $data['ecerhigh_ticket']) echo 'value="'.$data['ecerhigh_ticket'].'"'; ?> name="ecERht" type="text" disabled class="form-control">
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label>CFee</label>
                                        <input <?php if(isset($data['ecerconvenience_fee']) && $data['ecerconvenience_fee']) echo 'value="'.$data['ecerconvenience_fee'].'"'; ?> name="ecERcf" type="text" disabled class="form-control" value="0.00">
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label>% CFee</label>
                                        <input <?php if(isset($data['ecerconvenience_fee_float']) && $data['ecerconvenience_fee_float']) echo 'value="'.$data['ecerconvenience_fee_float'].'"'; ?> name="ecERpcf" type="text" disabled class="form-control" value="0.00">
                                    </div>
                                </div>
                                <br/>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>
                                            <input id="ecev" name="ecev" class="checkenabled" type="checkbox">
                                            ECheck Evendor
                                        </label>

                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label>Low pay range</label>
                                        <input <?php if(isset($dataaux['ecevlow_pay_range']) && $dataaux['ecevlow_pay_range']) echo 'value="'.$dataaux['ecevlow_pay_range'].'"'; ?> name="ecEVlpr" type="text"  disabled class="form-control">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label>High pay range</label>
                                        <input <?php if(isset($dataaux['ecevhigh_pay_range']) && $dataaux['ecevhigh_pay_range']) echo 'value="'.$dataaux['ecevhigh_pay_range'].'"'; ?> name="ecEVhpr" type="text" disabled class="form-control">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label>High Ticket</label>
                                        <input <?php if(isset($dataaux['ecevhigh_ticket']) && $dataaux['ecevhigh_ticket']) echo 'value="'.$dataaux['ecevhigh_ticket'].'"'; ?> name="ecEVht" type="text" disabled class="form-control">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>CFee</label>
                                        <input <?php if(isset($dataaux['ecevconvenience_fee']) && $dataaux['ecevconvenience_fee']) echo 'value="'.$dataaux['ecevconvenience_fee'].'"'; ?> name="ecEVcf" type="text" disabled class="form-control" value="0.00">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>% CFee</label>
                                        <input <?php if(isset($dataaux['ecevconvenience_fee_float']) && $dataaux['ecevconvenience_fee_float']) echo 'value="'.$dataaux['ecevconvenience_fee_float'].'"'; ?> name="ecEVpcf" type="text" disabled class="form-control" value="0.00">
                                    </div>
                                </div>
                                <br/>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary btn-block">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </form>

                    <?php echo Form::open(array('id'=>'ccform','action' => array('MerchantController@merchantPCCCStore', $token, $propertyId)))?>
                    <div class="col-sm-6">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-xs-6"><h4>Credit Card</h4></div>
                                    <div class="col-xs-6 text-right"><button data="<?php echo route('removecredentials',array('token'=>$token,'type'=>'cc', 'id'=>$propertyId));?>" type="button" class="btn btn-sm btn-danger" id="removecc"><span class="fa fa-trash-o"></span> Remove Credentials</button></div>
                                </div>
                                <hr/>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <?php
                                            $form_cc=array();
                                            foreach ($data as $d){
                                                if(isset($d['ccgateway'])){
                                                    $form_cc = $d;
                                                    break;
                                                }
                                            }
                                        ?>
                                        <label>Gateway</label>
                                        <select id="ccgateway" required name="ccgateway" class="form-control">
                                            <option role="option" value=""></option>

                                            <option data-validate="ccsourcekey" <?php if(isset($form_cc['ccgateway']) && $form_cc['ccgateway']=='nmi') echo 'selected'; ?> role="option" value="nmi">Nmi</option>
                                            <option data-validate="ccsourcekey" <?php if(isset($form_cc['ccgateway']) && $form_cc['ccgateway']=='trans1') echo 'selected'; ?> role="option" value="trans1">TransFirst</option>
                                            <option data-validate="ccmid,ccsourcekey" <?php if(isset($form_cc['ccgateway']) && $form_cc['ccgateway']=='fde4') echo 'selected'; ?> role="option" value="fde4">FirstData</option>
                                            <option data-validate="ccmid" <?php if(isset($form_cc['ccgateway']) && $form_cc['ccgateway']=='express') echo 'selected'; ?> role="option" value="express">Express</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>MID</label>
                                        <input <?php if(isset($form_cc['ccmid']) && $form_cc['ccmid']) echo 'value="'.$form_cc['ccmid'].'"'; ?> name="ccmid" type="text" class="form-control optional2">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        <label>Source Key</label>
                                        <input <?php if(isset($form_cc['ccsourcekey']) && $form_cc['ccsourcekey']) echo 'value="'.$form_cc['ccsourcekey'].'"'; ?> name="ccsourcekey" type="text" class="form-control optional2">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label>Store ID</label>
                                        <input <?php if(isset($form_cc['ccstoreid']) && $form_cc['ccstoreid']) echo 'value="'.$form_cc['ccstoreid'].'"'; ?> name="ccstoreid" type="text" class="form-control optional2">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <br/>
                                        <br/>
                                        <label>
                                            <input id="ccdisabletoken" <?php if(isset($form_cc['ccdisabletoken']) && $form_cc['ccdisabletoken']) echo 'checked="checked"'; ?> name="ccdisabletoken" type="checkbox">
                                            Disable Tokenization
                                        </label>

                                    </div>
                                </div>
                                <br/>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>
                                            <input data="ccot-dynamicform" id="ccwot" name="ccwot" class="checkenabled" type="checkbox">
                                            CC Web One Time
                                        </label>
                                    </div>
                                    <?php
                                    $check_ccwot = false;
                                    $ccwot_first = array();
                                    foreach ($data as $key => $d) {
                                        if (isset($d['ccwot'])) {
                                            $ccwot_first = $d;
                                            unset($data[$key]);
                                            $check_ccwot = true;
                                            break;
                                        }
                                    }
                                    ?>
                                    <div class="col-md-4 form-group">
                                        <label>Low pay range</label>
                                        <input <?php if(isset($ccwot_first['ccwotlow_pay_range']) && $ccwot_first['ccwotlow_pay_range']) echo 'value="'.$ccwot_first['ccwotlow_pay_range'].'"'; ?> name="ccWOTlpr" type="text"  disabled class="form-control">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label>High pay range</label>
                                        <input <?php if(isset($ccwot_first['ccwothigh_pay_range']) && $ccwot_first['ccwothigh_pay_range']) echo 'value="'.$ccwot_first['ccwothigh_pay_range'].'"'; ?> name="ccWOThpr" type="text" disabled class="form-control">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label>High Ticket</label>
                                        <input <?php if(isset($ccwot_first['ccwothigh_ticket']) && $ccwot_first['ccwothigh_ticket']) echo 'value="'.$ccwot_first['ccwothigh_ticket'].'"'; ?> name="ccWOTht" type="text" disabled class="form-control">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>CFee</label>
                                        <input <?php if(isset($ccwot_first['ccwotconvenience_fee']) && $ccwot_first['ccwotconvenience_fee']) echo 'value="'.$ccwot_first['ccwotconvenience_fee'].'"'; ?> name="ccWOTcf" type="text" disabled class="form-control" value="0.00">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>% CFee</label>
                                        <input <?php if(isset($ccwot_first['ccwotconvenience_fee_float']) && $ccwot_first['ccwotconvenience_fee_float']) echo 'value="'.$ccwot_first['ccwotconvenience_fee_float'].'"'; ?> name="ccWOTpcf" type="text" disabled class="form-control" value="0.00">
                                    </div>
                                </div>


                                <div id="ccot-dynamicform" class="dynamic-form">
                                    <div class="form-template hidden">
                                        <div>
                                            <h4>Tier #{{tiernumber}}</h4>
                                            <hr/>
                                            <div class="row">
                                                <div class="col-md-4 form-group">
                                                    <label>Low pay range</label>
                                                    <input name="ccWOTlprDYNAMIC{{tiernumber}}" required disabled="" class="form-control" type="text">
                                                </div>
                                                <div class="col-md-4 form-group">
                                                    <label>High pay range</label>
                                                    <input name="ccWOThprDYNAMIC{{tiernumber}}" required disabled="" class="form-control" type="text">
                                                </div>
                                                <div class="col-md-4 form-group">
                                                    <label>High Ticket</label>
                                                    <input name="ccWOThtDYNAMIC{{tiernumber}}" required disabled="" class="form-control" type="text">
                                                </div>
                                                <div class="col-md-4 form-group">
                                                    <label>CFee</label>
                                                    <input name="ccWOTcfDYNAMIC{{tiernumber}}" required disabled="" class="form-control" value="0.00" type="text">
                                                </div>
                                                <div class="col-md-5 form-group">
                                                    <label>% CFee</label>
                                                    <input name="ccWOTlpcfDYNAMIC{{tiernumber}}" required disabled="" class="form-control" value="0.00" type="text">
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <label>&nbsp;</label>
                                                    <button type="button" class="btn btn-danger btn-block removebtn"><span class="fa fa-trash-o"></span> Remove</button>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-cont">
                                        <?php
                                        $cont = 1;
                                        foreach ($data as $d){
                                            if(isset($d['ccwot'])){
                                                $cont++;
                                                ?>
                                                <div>
                                                    <h4>Tier #<?php echo $cont; ?></h4>
                                                    <hr/>
                                                    <div class="row">
                                                        <div class="col-md-4 form-group">
                                                            <label>Low pay range</label>
                                                            <input <?php if(isset($d['ccwotlow_pay_range']) && $d['ccwotlow_pay_range']) echo 'value="'.$d['ccwotlow_pay_range'].'"'; ?> name="ccWOTlprDYNAMIC<?php echo $cont;?>" disabled="" class="form-control" type="text">
                                                        </div>
                                                        <div class="col-md-4 form-group">
                                                            <label>High pay range</label>
                                                            <input <?php if(isset($d['ccwothigh_pay_range']) && $d['ccwothigh_pay_range']) echo 'value="'.$d['ccwothigh_pay_range'].'"'; ?> name="ccWOThprDYNAMIC<?php echo $cont;?>" disabled="" class="form-control" type="text">
                                                        </div>
                                                        <div class="col-md-4 form-group">
                                                            <label>High Ticket</label>
                                                            <input <?php if(isset($d['ccwothigh_ticket']) && $d['ccwothigh_ticket']) echo 'value="'.$d['ccwothigh_ticket'].'"'; ?> name="ccWOThtDYNAMIC<?php echo $cont;?>" disabled="" class="form-control" type="text">
                                                        </div>
                                                        <div class="col-md-4 form-group">
                                                            <label>CFee</label>
                                                            <input <?php if(isset($d['ccwotconvenience_fee']) && $d['ccwotconvenience_fee']) echo 'value="'.$d['ccwotconvenience_fee'].'"'; ?> name="ccWOTcfDYNAMIC<?php echo $cont;?>" disabled="" class="form-control" value="0.00" type="text">
                                                        </div>
                                                        <div class="col-md-5 form-group">
                                                            <label>% CFee</label>
                                                            <input <?php if(isset($d['ccwotconvenience_fee_float']) && $d['ccwotconvenience_fee_float']) echo 'value="'.$d['ccwotconvenience_fee_float'].'"'; ?> name="ccWOTlpcfDYNAMIC<?php echo $cont;?>" disabled="" class="form-control" value="0.00" type="text">
                                                        </div>
                                                        <div class="col-md-3 form-group">
                                                            <label>&nbsp;</label>
                                                            <button type="button" class="btn btn-danger btn-block removebtn"><span class="fa fa-trash-o"></span> Remove</button>
                                                        </div>

                                                    </div>
                                                </div>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                    <button data="<?php echo $cont+1; ?>" type="button" class="btn btn-xs btn-default btn-success addtier"><span class="fa fa-plus"></span> Add Tier</button>
                                </div>
                                <br/>

                                <div class="row">
                                    <div class="col-md-12">
                                        <label>
                                            <input data="ccwr-dynamicform" id="ccwr" name="ccwr" class="checkenabled" type="checkbox">
                                            CC Web Recurring
                                        </label>
                                    </div>

                                    <?php
                                    $check_ccwr = false;
                                    $ccwr_first = array();
                                    foreach ($data as $key => $d) {
                                        if (isset($d['ccwr'])) {
                                            $ccwr_first = $d;
                                            unset($data[$key]);
                                            $check_ccwr = true;
                                            break;
                                        }
                                    }
                                    ?>

                                    <div class="col-md-4 form-group">
                                        <label>Low pay range</label>
                                        <input <?php if(isset($ccwr_first['ccwrlow_pay_range']) && $ccwr_first['ccwrlow_pay_range']) echo 'value="'.$ccwr_first['ccwrlow_pay_range'].'"'; ?> name="ccWRlpr" type="text"  disabled class="form-control">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label>High pay range</label>
                                        <input <?php if(isset($ccwr_first['ccwrhigh_pay_range']) && $ccwr_first['ccwrhigh_pay_range']) echo 'value="'.$ccwr_first['ccwrhigh_pay_range'].'"'; ?> name="ccWRhpr" type="text" disabled class="form-control">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label>High Ticket</label>
                                        <input <?php if(isset($ccwr_first['ccwrhigh_ticket']) && $ccwr_first['ccwrhigh_ticket']) echo 'value="'.$ccwr_first['ccwrhigh_ticket'].'"'; ?> name="ccWRht" type="text" disabled class="form-control">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>CFee</label>
                                        <input <?php if(isset($ccwr_first['ccwrconvenience_fee']) && $ccwr_first['ccwrconvenience_fee']) echo 'value="'.$ccwr_first['ccwrconvenience_fee'].'"'; ?> name="ccWRcf" type="text" disabled class="form-control" value="0.00">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>% CFee</label>
                                        <input <?php if(isset($ccwr_first['ccwrconvenience_fee_float']) && $ccwr_first['ccwrconvenience_fee_float']) echo 'value="'.$ccwr_first['ccwrconvenience_fee_float'].'"'; ?> name="ccWRpcf" type="text" disabled class="form-control" value="0.00">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>CFee DRP</label>
                                        <input <?php if(isset($ccwr_first['ccwrconvenience_fee_drp']) && $ccwr_first['ccwrconvenience_fee_drp']) echo 'value="'.$ccwr_first['ccwrconvenience_fee_drp'].'"'; ?> name="ccWRcfDrp" type="text" disabled class="form-control" value="0.00">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>% CFee DRP</label>
                                        <input <?php if(isset($ccwr_first['ccwrconvenience_fee_float_drp']) && $ccwr_first['ccwrconvenience_fee_float_drp']) echo 'value="'.$ccwr_first['ccwrconvenience_fee_float_drp'].'"'; ?> name="ccWRpcfDrp" type="text" disabled class="form-control" value="0.00">
                                    </div>
                                </div>

                                <div id="ccwr-dynamicform" class="dynamic-form">
                                    <div class="form-template hidden">
                                        <div>
                                            <h4>Tier #{{tiernumber}}</h4>
                                            <hr/>
                                            <div class="row">
                                                <div class="col-md-4 form-group">
                                                    <label>Low pay range</label>
                                                    <input name="ccWRlprDYNAMIC{{tiernumber}}" type="text" required  disabled class="form-control">
                                                </div>
                                                <div class="col-md-4 form-group">
                                                    <label>High pay range</label>
                                                    <input name="ccWRhprDYNAMIC{{tiernumber}}" type="text" required disabled class="form-control">
                                                </div>
                                                <div class="col-md-4 form-group">
                                                    <label>High Ticket</label>
                                                    <input name="ccWRhtDYNAMIC{{tiernumber}}" type="text" required disabled class="form-control">
                                                </div>
                                                <div class="col-md-2 form-group">
                                                    <label>CFee</label>
                                                    <input name="ccWRcfDYNAMIC{{tiernumber}}" type="text" required disabled class="form-control" value="0.00">
                                                </div>
                                                <div class="col-md-2 form-group">
                                                    <label>% CFee</label>
                                                    <input name="ccWRpcfDYNAMIC{{tiernumber}}" type="text" required disabled class="form-control" value="0.00">
                                                </div>
                                                <div class="col-md-2 form-group">
                                                    <label>CFee DRP</label>
                                                    <input name="ccWRcfDrpDYNAMIC{{tiernumber}}" type="text" required disabled class="form-control" value="0.00">
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <label>% CFee DRP</label>
                                                    <input name="ccWRpcfDrpDYNAMIC{{tiernumber}}" type="text" required disabled class="form-control" value="0.00">
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <label>&nbsp;</label>
                                                    <button type="button" class="btn btn-danger btn-block removebtn"><span class="fa fa-trash-o"></span> Remove</button>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-cont">
                                        <?php
                                        $cont = 1;
                                        foreach ($data as $d){
                                            if(isset($d['ccwr'])){
                                                $cont++;
                                                ?>
                                                <div>
                                                    <h4>Tier #<?php echo $cont; ?></h4>
                                                    <hr/>
                                                    <div class="row">
                                                        <div class="col-md-4 form-group">
                                                            <label>Low pay range</label>
                                                            <input <?php if(isset($d['ccwrlow_pay_range']) && $d['ccwrlow_pay_range']) echo 'value="'.$d['ccwrlow_pay_range'].'"'; ?> name="ccWRlprDYNAMIC<?php echo $cont;?>" type="text" required  disabled class="form-control">
                                                        </div>
                                                        <div class="col-md-4 form-group">
                                                            <label>High pay range</label>
                                                            <input <?php if(isset($d['ccwrhigh_pay_range']) && $d['ccwrhigh_pay_range']) echo 'value="'.$d['ccwrhigh_pay_range'].'"'; ?> name="ccWRhprDYNAMIC<?php echo $cont;?>" type="text" required disabled class="form-control">
                                                        </div>
                                                        <div class="col-md-4 form-group">
                                                            <label>High Ticket</label>
                                                            <input <?php if(isset($d['ccwrhigh_ticket']) && $d['ccwrhigh_ticket']) echo 'value="'.$d['ccwrhigh_ticket'].'"'; ?> name="ccWRhtDYNAMIC<?php echo $cont;?>" type="text" required disabled class="form-control">
                                                        </div>
                                                        <div class="col-md-2 form-group">
                                                            <label>CFee</label>
                                                            <input <?php if(isset($d['ccwrconvenience_fee']) && $d['ccwrconvenience_fee']) echo 'value="'.$d['ccwrconvenience_fee'].'"'; ?> name="ccWRcfDYNAMIC<?php echo $cont;?>" type="text" required disabled class="form-control" value="0.00">
                                                        </div>
                                                        <div class="col-md-2 form-group">
                                                            <label>% CFee</label>
                                                            <input <?php if(isset($d['ccwrconvenience_fee_float']) && $d['ccwrconvenience_fee_float']) echo 'value="'.$d['ccwrconvenience_fee_float'].'"'; ?> name="ccWRpcfDYNAMIC<?php echo $cont;?>" type="text" required disabled class="form-control" value="0.00">
                                                        </div>
                                                        <div class="col-md-2 form-group">
                                                            <label>CFee DRP</label>
                                                            <input <?php if(isset($d['ccwrconvenience_fee_drp']) && $d['ccwrconvenience_fee_drp']) echo 'value="'.$d['ccwrconvenience_fee_drp'].'"'; ?> name="ccWRcfDrpDYNAMIC<?php echo $cont;?>" type="text" required disabled class="form-control" value="0.00">
                                                        </div>
                                                        <div class="col-md-3 form-group">
                                                            <label>% CFee DRP</label>
                                                            <input <?php if(isset($d['ccwrconvenience_fee_float_drp']) && $d['ccwrconvenience_fee_float_drp']) echo 'value="'.$d['ccwrconvenience_fee_float_drp'].'"'; ?> name="ccWRpcfDrpDYNAMIC<?php echo $cont;?>" type="text" required disabled class="form-control" value="0.00">
                                                        </div>
                                                        <div class="col-md-3 form-group">
                                                            <label>&nbsp;</label>
                                                            <button type="button" class="btn btn-danger btn-block removebtn"><span class="fa fa-trash-o"></span> Remove</button>
                                                        </div>

                                                    </div>
                                                </div>
                                                <?php
                                            }
                                        }
                                        ?>

                                    </div>
                                    <button data="<?php echo $cont+1; ?>" type="button" class="btn btn-xs btn-default btn-success addtier"><span class="fa fa-plus"></span> Add Tier</button>
                                </div>
                                <br/>

                                <div class="row hide">
                                    <div class="col-md-12">
                                        <label>
                                            <input id="cceot" name="cceot" class="checkenabled" type="checkbox">
                                            CC Web Eterminal One Time
                                        </label>

                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>Low pay range</label>
                                        <input <?php if(isset($data['cceotlow_pay_range']) && $data['cceotlow_pay_range']) echo 'value="'.$data['cceotlow_pay_range'].'"'; ?> name="ccEOTlpr" type="text"  disabled class="form-control">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>High pay range</label>
                                        <input <?php if(isset($data['cceothigh_pay_range']) && $data['cceothigh_pay_range']) echo 'value="'.$data['cceothigh_pay_range'].'"'; ?> name="ccEOThpr" type="text" disabled class="form-control">
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label>High Ticket</label>
                                        <input <?php if(isset($data['cceothigh_ticket']) && $data['cceothigh_ticket']) echo 'value="'.$data['cceothigh_ticket'].'"'; ?> name="ccEOTht" type="text" disabled class="form-control">
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label>CFee</label>
                                        <input <?php if(isset($data['cceotconvenience_fee']) && $data['cceotconvenience_fee']) echo 'value="'.$data['cceotconvenience_fee'].'"'; ?> name="ccEOTcf" type="text" disabled class="form-control" value="0.00">
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label>% CFee</label>
                                        <input <?php if(isset($data['cceotconvenience_fee_float']) && $data['cceotconvenience_fee_float']) echo 'value="'.$data['cceotconvenience_fee_float'].'"'; ?> name="ccEOTpcf" type="text" disabled class="form-control" value="0.00">
                                    </div>
                                </div>
                                <br/>
                                <div class="row hide">
                                    <div class="col-md-12">
                                        <label>
                                            <input id="ccer" name="ccer" class="checkenabled" type="checkbox">
                                            CC Eterminal Recurring
                                        </label>

                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>Low pay range</label>
                                        <input <?php if(isset($data['ccerlow_pay_range']) && $data['ccerlow_pay_range']) echo 'value="'.$data['ccerlow_pay_range'].'"'; ?> name="ccERlpr" type="text"  disabled class="form-control">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>High pay range</label>
                                        <input <?php if(isset($data['ccerhigh_pay_range']) && $data['ccerhigh_pay_range']) echo 'value="'.$data['ccerhigh_pay_range'].'"'; ?> name="ccERhpr" type="text" disabled class="form-control">
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label>High Ticket</label>
                                        <input <?php if(isset($data['ccerhigh_ticket']) && $data['ccerhigh_ticket']) echo 'value="'.$data['ccerhigh_ticket'].'"'; ?> name="ccERht" type="text" disabled class="form-control">
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label>CFee</label>
                                        <input <?php if(isset($data['ccerconvenience_fee']) && $data['ccerconvenience_fee']) echo 'value="'.$data['ccerconvenience_fee'].'"'; ?> name="ccERcf" type="text" disabled class="form-control" value="0.00">
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label>% CFee</label>
                                        <input <?php if(isset($data['ccerconvenience_fee_float']) && $data['ccerconvenience_fee_float']) echo 'value="'.$data['ccerconvenience_fee_float'].'"'; ?> name="ccERpcf" type="text" disabled class="form-control" value="0.00">
                                    </div>
                                </div>
                                <br/>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>
                                            <input data="ccamex-dynamicform" id="ccamex" name="ccamex" class="checkenabled" type="checkbox">
                                            CC American Express
                                        </label>
                                    </div>
                                    <?php
                                    $check_ccamex = false;
                                    $ccamex_first = array();
                                    foreach ($data as $key => $d) {
                                        if (isset($d['ccamex'])) {
                                            $ccamex_first = $d;
                                            unset($data[$key]);
                                            $check_ccamex = true;
                                            break;
                                        }
                                    }
                                    ?>

                                    <div class="col-md-4 form-group">
                                        <label>Low pay range</label>
                                        <input <?php if(isset($ccamex_first['ccamexlow_pay_range']) && $ccamex_first['ccamexlow_pay_range']) echo 'value="'.$ccamex_first['ccamexlow_pay_range'].'"'; ?> name="ccAElpr" type="text"  disabled class="form-control">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label>High pay range</label>
                                        <input <?php if(isset($ccamex_first['ccamexhigh_pay_range']) && $ccamex_first['ccamexhigh_pay_range']) echo 'value="'.$ccamex_first['ccamexhigh_pay_range'].'"'; ?> name="ccAEhpr" type="text" disabled class="form-control">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label>High Ticket</label>
                                        <input <?php if(isset($ccamex_first['ccamexhigh_ticket']) && $ccamex_first['ccamexhigh_ticket']) echo 'value="'.$ccamex_first['ccamexhigh_ticket'].'"'; ?> name="ccAEht" type="text" disabled class="form-control">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>CFee</label>
                                        <input <?php if(isset($ccamex_first['ccamexconvenience_fee']) && $ccamex_first['ccamexconvenience_fee']) echo 'value="'.$ccamex_first['ccamexconvenience_fee'].'"'; ?> name="ccAEcf" type="text" disabled class="form-control" value="0.00">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>% CFee</label>
                                        <input <?php if(isset($ccamex_first['ccamexconvenience_fee_float']) && $ccamex_first['ccamexconvenience_fee_float']) echo 'value="'.$ccamex_first['ccamexconvenience_fee_float'].'"'; ?> name="ccAEpcf" type="text" disabled class="form-control" value="0.00">
                                    </div>
                                </div>


                                <div id="ccamex-dynamicform" class="dynamic-form">
                                    <div class="form-template hidden">
                                        <div>
                                            <h4>Tier #{{tiernumber}}</h4>
                                            <hr/>
                                            <div class="row">
                                                <div class="col-md-4 form-group">
                                                    <label>Low pay range</label>
                                                    <input name="ccAElprDYNAMIC{{tiernumber}}" required disabled="" class="form-control" type="text">
                                                </div>
                                                <div class="col-md-4 form-group">
                                                    <label>High pay range</label>
                                                    <input name="ccAEhprDYNAMIC{{tiernumber}}" required disabled="" class="form-control" type="text">
                                                </div>
                                                <div class="col-md-4 form-group">
                                                    <label>High Ticket</label>
                                                    <input name="ccAEhtDYNAMIC{{tiernumber}}" required disabled="" class="form-control" type="text">
                                                </div>
                                                <div class="col-md-4 form-group">
                                                    <label>CFee</label>
                                                    <input name="ccAEcfDYNAMIC{{tiernumber}}" required disabled="" class="form-control" value="0.00" type="text">
                                                </div>
                                                <div class="col-md-5 form-group">
                                                    <label>% CFee</label>
                                                    <input name="ccAElpcfDYNAMIC{{tiernumber}}" required disabled="" class="form-control" value="0.00" type="text">
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <label>&nbsp;</label>
                                                    <button type="button" class="btn btn-danger btn-block removebtn"><span class="fa fa-trash-o"></span> Remove</button>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-cont">
                                        <?php
                                        $cont = 1;
                                        foreach ($data as $d){
                                            if(isset($d['ccamex'])){
                                                $cont++;
                                                ?>
                                                <div>
                                                    <h4>Tier #<?php echo $cont; ?></h4>
                                                    <hr/>
                                                    <div class="row">
                                                        <div class="col-md-4 form-group">
                                                            <label>Low pay range</label>
                                                            <input <?php if(isset($d['ccamexlow_pay_range']) && $d['ccamexlow_pay_range']) echo 'value="'.$d['ccamexlow_pay_range'].'"'; ?> name="ccAElprDYNAMIC<?php echo $cont;?>" disabled="" class="form-control" type="text">
                                                        </div>
                                                        <div class="col-md-4 form-group">
                                                            <label>High pay range</label>
                                                            <input <?php if(isset($d['ccamexhigh_pay_range']) && $d['ccamexhigh_pay_range']) echo 'value="'.$d['ccamexhigh_pay_range'].'"'; ?> name="ccAEhprDYNAMIC<?php echo $cont;?>" disabled="" class="form-control" type="text">
                                                        </div>
                                                        <div class="col-md-4 form-group">
                                                            <label>High Ticket</label>
                                                            <input <?php if(isset($d['ccamexhigh_ticket']) && $d['ccamexhigh_ticket']) echo 'value="'.$d['ccamexhigh_ticket'].'"'; ?> name="ccAEhtDYNAMIC<?php echo $cont;?>" disabled="" class="form-control" type="text">
                                                        </div>
                                                        <div class="col-md-4 form-group">
                                                            <label>CFee</label>
                                                            <input <?php if(isset($d['ccamexconvenience_fee']) && $d['ccamexconvenience_fee']) echo 'value="'.$d['ccamexconvenience_fee'].'"'; ?> name="ccAEcfDYNAMIC<?php echo $cont;?>" disabled="" class="form-control" value="0.00" type="text">
                                                        </div>
                                                        <div class="col-md-5 form-group">
                                                            <label>% CFee</label>
                                                            <input <?php if(isset($d['ccamexconvenience_fee_float']) && $d['ccamexconvenience_fee_float']) echo 'value="'.$d['ccamexconvenience_fee_float'].'"'; ?> name="ccAElpcfDYNAMIC<?php echo $cont;?>" disabled="" class="form-control" value="0.00" type="text">
                                                        </div>
                                                        <div class="col-md-3 form-group">
                                                            <label>&nbsp;</label>
                                                            <button type="button" class="btn btn-danger btn-block removebtn"><span class="fa fa-trash-o"></span> Remove</button>
                                                        </div>

                                                    </div>
                                                </div>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                    <button data="<?php echo $cont+1; ?>" type="button" class="btn btn-xs btn-default btn-success addtier"><span class="fa fa-plus"></span> Add Tier</button>
                                </div>

                                <br/>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary btn-block">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
                </div>
                </div>


<div id="myModal_removecredentials" class="modal fade">
    <div class="modal-dialog" style="width: 400px">
        <div class="modal-content">
            <div class="modal-body" id="xbody_autopayMethod">

            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-md-6 btn-margin-xs-screen"><button type="button" class="btn btn-default form-control btn-full" data-dismiss="modal">Cancel</button><br/></div>
                    <div class="col-md-6"><a id="modalbtnremove" href="" class="btn btn-primary form-control btn-full">Remove</a> </div>
                </div>
            </div>
        </div>
    </div>
</div>



<?php  include_once __DIR__.'/admin_components/admin_footer.php'; ?>


    <script type="text/javascript" src="/js/jquery.form.js"></script>
    <script src="/js/jquery.validatev2.js"></script>
    <script src="/js/jquery.validate.additional.js"></script>
    <script>

        jQuery.validator.addMethod("money", function(value, element) {
            return this.optional(element) || /^\d*(\.\d{0,2})?$/.test(value);
        }, "This field has errors.");

        $("#echeckform").validate({
            ignore: '.form-template input[type="text"]',
            rules: {
                ecWOTlpr: { money: true },
                ecWOThpr: { money: true },
                ecWOTht: { money: true },
                ecWOTcf: { money: true },
                ecWOTpcf: { money: true },

                ecWRlpr: { money: true },
                ecWRhpr: { money: true },
                ecWRht: { money: true },
                ecWRcf: { money: true },
                ecWRpcf: { money: true },

                ecEOTlpr: { money: true },
                ecEOThpr: { money: true },
                ecEOTht: { money: true },
                ecEOTcf: { money: true },
                ecEOTpcf: { money: true },

                ecERlpr: { money: true },
                ecERhpr: { money: true },
                ecERht: { money: true },
                ecERcf: { money: true },
                ecERpcf: { money: true },

                ecEVlpr: { money: true },
                ecEVhpr: { money: true },
                ecEVht: { money: true },
                ecEVcf: { money: true },
                ecEVpcf: { money: true },
            }
        });

        $("#ccform").validate({
            ignore: '.form-template input[type="text"]',
            rules: {
                ccWOTlpr: { money: true },
                ccWOThpr: { money: true },
                ccWOTht: { money: true },
                ccWOTcf: { money: true },
                ccWOTpcf: { money: true },

                ccWRlpr: { money: true },
                ccWRhpr: { money: true },
                ccWRht: { money: true },
                ccWRcf: { money: true },
                ccWRpcf: { money: true },

                ccEOTlpr: { money: true },
                ccEOThpr: { money: true },
                ccEOTht: { money: true },
                ccEOTcf: { money: true },
                ccEOTpcf: { money: true },

                ccERlpr: { money: true },
                ccERhpr: { money: true },
                ccERht: { money: true },
                ccERcf: { money: true },
                ccERpcf: { money: true },

                ccAElpr: { money: true },
                ccAEhpr: { money: true },
                ccAEht: { money: true },
                ccAEcf: { money: true },
                ccAEpcf: { money: true },
            }
        });

        $('.checkenabled').click(function(){
            if($(this).prop('checked') ) {
                objs = $(this).parent().parent().parent().find('input[type="text"]')
                objs.removeAttr('disabled');
                objs.attr('required',true);

                objsdynamic = $('#'+$(this).attr('data')).find('input[type="text"]');
                objsdynamic.removeAttr('disabled');
                objsdynamic.attr('required',true);
            }
            else{
                objs =  $(this).parent().parent().parent().find('input[type="text"]');
                objs.attr('disabled',true);
                objs.removeAttr('required');

                objsdynamic = $('#'+$(this).attr('data')).find('input[type="text"]');
                objsdynamic.attr('disabled',true);
                objsdynamic.removeAttr('required');
            }
        });

        $('#ccdisabletoken').click(function(){
            if($(this).prop('checked')){
               if($('#ccwr').prop('checked')){
                   $('#ccwr').trigger('click');
               }
                if($('#ccer').prop('checked')){
                    $('#ccer').trigger('click');
                }

                $('#ccwr').attr('disabled','disabled');
                $('#ccer').attr('disabled','disabled');
            }
            else{
                $('#ccwr').removeAttr('disabled');
                $('#ccer').removeAttr('disabled');
            }
        });

        <?php if($check_ecwot){
        ?>
        $('#ecwot').trigger('click');
        <?php
        }
        ?>
        <?php if($check_ecwr){
        ?>
        $('#ecwr').trigger('click');
        <?php
        }
        ?>

        <?php if(isset($data['eceot']) && $data['eceot']){
        ?>
        $('#eceot').trigger('click');
        <?php
        }
        ?>

        <?php if(isset($data['ecer']) && $data['ecer']){
        ?>
        $('#ecer').trigger('click');
        <?php
        }
        ?>

        <?php if(isset($dataaux['ecev']) && $dataaux['ecev']){
        ?>
        $('#ecev').trigger('click');
        <?php
        }
        ?>

        <?php if($check_ccwot){
        ?>
        $('#ccwot').trigger('click');
        <?php
        }
        ?>

        <?php if($check_ccwr){
        ?>
        $('#ccwr').trigger('click');
        <?php
        }
        ?>

        <?php if(isset($data['cceot']) && $data['cceot']){
        ?>
        $('#cceot').trigger('click');
        <?php
        }
        ?>

        <?php if(isset($data['ccer']) && $data['ccer']){
        ?>
        $('#ccer').trigger('click');
        <?php
        }
        ?>

        <?php if($check_ccamex){
        ?>
        $('#ccamex').trigger('click');
        <?php
        }
        ?>

        $('form').submit(function(e){
            if($(this).find('input[type=checkbox]:checked').length === 0)
            {
                e.preventDefault();
                alert('You need to select at least a payment type (One time or Recurring)');
            }
        });


        function dynamic_validation(element,fields_class) {
            fields = element.find('option:selected').attr('data-validate');
            if(fields){
                fields_array = fields.split(",");
                $('.'+fields_class).removeAttr('required');
                fields_array.forEach(function (element) {
                    $("input[name='"+element+"']").prop('required',true);
                });
            }

        }

        $('#ecgateway').change(function () {
            dynamic_validation($(this),'optional');
        });
        $('#ccgateway').change(function () {
            dynamic_validation($(this),'optional2');
        });

        $('#ecgateway').trigger("change");
        $('#ccgateway').trigger("change");

        $('#removeecheck').click(function () {
            $('#myModal_removecredentials .modal-body').html('Do you want remove Echeck Credentials?');
            $('#modalbtnremove').attr('href',$(this).attr('data'));
            $('#myModal_removecredentials').modal('show');

        });

        $('#removecc').click(function () {
            $('#myModal_removecredentials .modal-body').html('Do you want remove Credit Card Credentials?');
            $('#modalbtnremove').attr('href',$(this).attr('data'));
            $('#myModal_removecredentials').modal('show');

        });

        $('.addtier').click(function () {

            tiercont = $(this).attr('data');
            parent = $(this).parent();
            html = parent.find('.form-template').html();

            do {
                html = html.replace('{{tiernumber}}',tiercont);
            }
            while (html.indexOf("{{tiernumber}}") >= 0);
            console.log(html);
            parent.find('.form-cont').append(html);
            $(this).attr('data',parseInt(tiercont)+1);

        });

        $('body').on('click', '.removebtn', function () {
            $(this).parent().parent().parent().remove();
        });


    </script>
</html>
