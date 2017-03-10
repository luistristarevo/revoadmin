<?php  include_once __DIR__.'/admin_components/admin_header.php'; ?>
<div class="container">
    <div class="panel-shadow many-fields">
        <div class="fadein">
            <div class="row">
                <div class="col-xs-6">
                    <h1 class="header"><?php echo $pageTitle ; ?></h1>
                </div>
                <div class="col-xs-6 text-right">
                    <?php
                    $export_link = false;
                    foreach(Session::get('user_permissions') as $p){
                        if($p['route']=='formatsexport'){
                            $export_link = true;
                            break;
                        }
                    }

                    $bindings = $filter->query->getBindings();
                    $sql_ready =$sql;
                    foreach ($bindings as $replace){
                        $sql_ready = preg_replace('/\?/',"'$replace'", $sql_ready, 1);
                    }
                    $sql_ready = base64_encode($sql_ready);
                    ?>
                    <?php if($export_link){?>
                        <div class="btn-group ">
                            <button style="color: #ffffff!important;" type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="fa fa-upload"></span> Export <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu pull-right">
                                <li><a href="<?php echo route('formatsexport',array('query_base64'=>$sql_ready,'type'=>'csv'))?>">Outbound Payments (CSV)</a></li>
                            </ul>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <?php
            echo $filter ;
            echo $grid ;

            ?>
        </div>
    </div></div>

<?php  include_once __DIR__.'/admin_components/admin_footer.php'; ?>
