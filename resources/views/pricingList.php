<?php  include_once __DIR__.'/admin_components/admin_header.php'; ?>
    <div class="container">
    <div class="panel-shadow">
        <?php
        $new_link = '';
        foreach(Session::get('user_permissions') as $p){
            if($p['route']=='adminnewpricing'){
                $new_link = '<a class="btn btn-success" href="'. route('adminnewpricing',array('token'=>$token)) .'"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> New Pricing</a>';
                break;
            }
        }
        ?>
        <div class="row">
            <div class="col-xs-6"><h1 class="header">Pricing Tables</h1></div>
            <div class="col-xs-6 text-right"><?php echo $new_link;?></div>
        </div>


    <?php
    echo $filter;
    echo $grid ;
    ?>

    <div id="delete_modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-md" style="width: 400px;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Confirmation Required</h4>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this Pricing?
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-xs-6"><a id="delete_button" href="" class="btn btn-primary btn-block">Yes</a></div>
                        <div class="col-xs-6"><button type="button" class="btn btn-default btn-block" data-dismiss="modal">No</button></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>


    <script>
    function openApp(p_idapp){
        route = "<?php echo route('adminopenpricing', ['token'=>$token,'id_table' => 0])?>";
        route = route.replace('/0','/'+p_idapp);
        window.location=route;
    }

    function editApp(p_idapp){
        route = "<?php echo route('admineditpricing', ['token'=>$token,'id_table' => 0])?>";
        route = route.replace('/0','/'+p_idapp);
        window.location=route;
    }

    function deleteApp(p_idapp){
        route = "<?php echo route('admindeletepricing', ['token'=>$token,'id_table' => 0])?>";
        route = route.replace('/0','/'+p_idapp);
        $('#delete_button').attr({'href':route});
        $('#delete_modal').modal('show');
    }
    </script>


<?php  include_once __DIR__.'/admin_components/admin_footer.php'; ?>