<div class="row">
<div class="alert alert-danger">
    <strong>Warning!</strong> You are going to change the status of the merchant bellow and all its users and autopays!
</div>
</div>
<?php

echo Form::open(array('url' => '', 'files' => true, 'id' => 'changeMerchantStatusForm', 'onsubmit' => 'return false;', 'role' => 'form', 'class' => 'form-horizontal'));
echo Form::hidden('id', $merchantdetail[0]['id']);

?>

<div class="row form-group">
    <div class="col-xs-12">
        <?php echo Form::label('name_clients', 'Name (*)'); ?>
        <?php echo Form::text('name_clients', str_replace(",", "", $merchantdetail[0]['name_clients']), array('class' => 'form-control', 'disabled')); ?>
    </div>
</div>

<div class="row form-group">
    <div class="col-xs-12">
        <?php echo Form::label('compositeID_clients', 'Company ID'); ?>
        <?php echo Form::text('compositeID_clients', str_replace(",", "", $merchantdetail[0]['compositeID_clients']), array('class' => 'form-control', 'disabled')); ?>
    </div>
</div>


<?php
//echo Form::button('Submit', array('class' => 'btn btn-primary', 'id' => 'editMerchantButton'));
echo Form::close();
echo Form::hidden('isError', 0, array('id' => 'merchantError'));
?>

<script type="text/javascript">
    var token = '<?php echo $token; ?>';
    $(document).ready(function () {
        var options = {
            target: '', // target element(s) to be updated with server response 
            beforeSubmit: showRequest, // pre-submit callback 
            success: showResponse, // post-submit callback 

            // other available options: 
            url: '/merchant/mUpdateStatus', // override for form's 'action' attribute
            type: 'post', // 'get' or 'post', override for form's 'method' attribute 
            dataType: 'json'        // 'xml', 'script', or 'json' (expected server response type) 
        };
        $("#changeMerchantStatusButton").unbind(); // Remove a previously-attached event handler
        // bind to the form's submit event 
        $('#changeMerchantStatusButton').click(function () {
            $('#myModal_loading').modal();
            $('#myModal_changeMerchantStatus').modal('hide');
            $('#changeMerchantStatusForm').ajaxSubmit(options);

            // !!! Important !!! 
            // always return false to prevent standard browser submit and page navigation 
            return false;
        });

    });
    function showRequest() {
    }

    function showResponse(responseText, statusText, xhr, $form) {
        if (responseText.error == -1) {
            //alert(responseText.msg); 
            $('#myModal_loading').modal('hide');
            //$('#myModal_changeMerchantStatus').modal('show');
            $('#merchantError').val(0);
            $("#xpopupcontent").html(responseText.msg);
            $('#myModal_success').modal();
            return false;
        } else if (responseText.error == 1) {
            //alert(responseText.msg); 
            //alert('pp');
            $('#myModal_loading').modal('hide');
            //$('#myModal_changeMerchantStatus').modal('show');
            $('#merchantError').val(1);
            $("#xpopupcontent").html(responseText.msg);
            $('#myModal_success').modal();
            return false;
        }
    }

    $('#myModal_success .row button').click(function () {
        //alert('pp');
        if (!parseInt($('#merchantError').val())) {
            window.location.href = '/merchant/' + token + '/list';
            return false;
        } else {
            $('#myModal_loading').modal('hide');
            $('#myModal_success').modal('hide');
            $('#myModal_changeMerchantStatus').modal('show');
            return false;
        }
    });
</script>
