<?php  include_once __DIR__.'/admin_components/admin_header.php'; ?>
<div class="container">
    <div class="panel-shadow many-fields">


				<div class="row">
                    <div class="col-lg-12">
                        <?php 
                            $lactive='ticket_report';
                            //include_once __DIR__.'/../admin_components/links_customize.php';
                            include_once __DIR__.'/admin_components/links_merchants.php';
                        ?>

                        <hr class="hr-no-margin"/>
                        <h1><?php echo $pageTitle ; ?></h1>
                        <br/>
                    </div>
                 </div>                


					<?php //echo ''; print_r($grid);
							//echo $filter;
							echo $grid ;						    
					
					 ?>

 </div></div>

<?php  include_once __DIR__.'/admin_components/admin_footer.php'; ?>

    <script>
            $('#myModal_success').modal();
    </script>        
    <?php echo Rapyd::scripts(); ?>

