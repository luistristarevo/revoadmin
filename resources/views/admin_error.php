<?php  include_once __DIR__.'/admin_components/admin_header.php'; ?>
<div class="container">
    <div class="row">
        <div class="col-sm-6 col-sm-push-3">
            <div class="panel-shadow">
                <div class="fadein">
                    <div class="row">
                        <div class="col-xs-2 text-center">
                            <span class="fa fa-user-secret" style="font-size: 55px"></span>
                        </div>
                        <div class="col-xs-10">
                            <h3 class="no-margin" style="margin-bottom: 5px!important;"><?php echo $name;?></h3>
                            <p style="font-size: 12px"><?php echo $description;?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php  include_once __DIR__.'/admin_components/admin_footer.php'; ?>
