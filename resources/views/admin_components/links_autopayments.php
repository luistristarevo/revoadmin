<?php $mtoken=$atoken; ?>
<ul class="nav nav-pills hide-xs-screen">
    
    <li <?php if($lactive=='active_autopayments') echo 'class="active"';?>><a href="<?php echo route('recurring',['token'=>$mtoken]);?>">Active Autopayments</a></li>
    <li <?php if($lactive=='completed_autopayments') echo 'class="active"';?>><a href="<?php echo route('completed',['token'=>$mtoken]);?>">Completed Autopayments</a></li>
    <li <?php if($lactive=='cancelled_autopayments') echo 'class="active"';?>><a href="<?php echo route('cancelled',['token'=>$mtoken]);?>">Cancelled Autopayments</a></li>

</ul>

