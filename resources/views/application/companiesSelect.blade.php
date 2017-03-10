<?php

    foreach($companies as $c){
        $selected = null;
        if($c['subdomain_companies']==$company){
            $selected = 'selected';
        }

        echo '<option  data="'. $c['contact_email'].'" '.$selected.'  value="'.$c['id'].'">'.$c['company_name'].'</option>';
    }
?>