<?php //$mtoken=\Illuminate\Support\Facades\Crypt::encrypt($idlevel.'|'.$level.'|'.time().'|'.config('app.appAPIkey')); ?>
<?php $mtoken=$token; ?>
<ul class="nav nav-pills">
    <?php
    $permissions = Session::get('user_permissions');
    $permissions_routes = array();
    foreach($permissions as $p){
        $permissions_routes[]=$p['route'];
    }

    //var_dump($lactive);
    //var_dump($permissions_routes);
    ?>

    <?php if(in_array('merchantprofile',$permissions_routes)){?><li <?php if($lactive=='merchantprofile') echo 'class="active"';?>><a href="<?php echo route('merchantprofile',['token'=>$mtoken, 'id' => $propertyId]);?>">Profile</a></li><?php } ?>
    <?php if(in_array('paymentcredentials',$permissions_routes)){?><li <?php if($lactive=='payment_credentials') echo 'class="active"';?>><a href="<?php echo route('paymentcredentials',['token'=>$mtoken, 'id' => $propertyId]);?>">Payment Credentials</a></li><?php } ?>
    <!--li <?php if(''=='application_list') echo 'class="active"';?>><a href="<?php echo route('applications',['token'=>$mtoken, 'id' => $propertyId]);?>">Application List</a></li-->
    <!--li <?php if(''=='contract_history') echo 'class="active"';?>><a href="<?php echo route('contracthistory',['token'=>$mtoken, 'id' => $propertyId]);?>">Contract History</a></li-->
    <!--li <?php if(''=='IVR_account') echo 'class="active"';?>><a href="<?php echo route('ivraccount',['token'=>$mtoken, 'id' => $propertyId]);?>">IVR Account</a></li-->
    <!--li <?php if(''=='velocities') echo 'class="active"';?>><a href="<?php echo route('velocities',['token'=>$mtoken, 'id' => $propertyId]);?>">Velocities</a></li-->
    <?php if(in_array('eventhistory',$permissions_routes)){?><li <?php if($lactive=='event_history') echo 'class="active"';?>><a href="<?php echo route('eventhistory',['token'=>$mtoken, 'id' => $propertyId]);?>">Event History</a></li><?php } ?>
    <?php if(in_array('ticketreport',$permissions_routes)){?><li <?php if($lactive=='ticket_report') echo 'class="active"';?>><a href="<?php echo route('ticketreport',['token'=>$mtoken, 'id' => $propertyId]);?>">Ticket List</a></li><?php } ?>
    <?php if(in_array('fraudcontrol',$permissions_routes)){?><li <?php if($lactive=='fraud_control') echo 'class="active"';?>><a href="<?php echo route('fraudcontrol',['token'=>$mtoken, 'id' => $propertyId]);?>">Fraud Control</a></li><?php } ?>

</ul>
