<?php  include_once __DIR__.'/admin_components/admin_header.php'; ?>
<script src="<?php echo asset('../js/bootstrap_aux.min.js'); ?>"></script>
<link rel="stylesheet" href="/css/bootstrap-select.css">
<div class="container">
    <div class="panel-shadow">
        <div class="row">
            <div class="col-xs-6"><h1 class="header"><?php echo $pageTitle; ?></h1></div>
            <div class="col-xs-6 text-right"></div>
        </div>
<?php
$lactive='active_autopayments';
include_once __DIR__.'/admin_components/links_autopayments.php';
?>
        <hr class="hr-no-margin"/>

<?php //echo ''; print_r($grid);
        echo $filter;
        echo $grid ;
        $grid->row(function ($row) {
           if ($row->cell('public')->value < 1) {
               $row->cell('trans_payment_type')->style("color:Gray");
               $row->style("background-color:#CCFF66");
           }
        });

 ?>
    </div>
    </div>

<div id="myModal_autopayFreq" class="modal fade">
    <input id="xhide_autopayfreq" value="" type="hidden">
    <input id="xhide_token" value="" type="hidden">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Edit Frequency</h4>
            </div>
            <div class="modal-body check" id="body_autopayFreq">

            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-sm-6"><button type="button" class="btn btn-default form-control btn-full" data-dismiss="modal">Cancel</button><br></div>
                    <div class="col-sm-6"><button type="button" class="btn btn-primary form-control btn-full" onclick="saveAutoFrequenceAux()">Save changes</button></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="myModal_autopayCat" class="modal fade">
    <input id="xhide_autopaycat" value="" type="hidden">
    <input id="xhide_utopaycat_token" value="" type="hidden">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Edit Payment Details</h4>
            </div>
            <div class="modal-body" id="body_autopayCat"><div class="alert alert-danger" style="display: none" id="xerror"><b>Error! </b>Please select a payment category before proceeding.</div><br>

            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-sm-6 btn-margin-xs-screen"><button type="button" class="btn btn-default form-control btn-full" data-dismiss="modal">Cancel</button><br></div>
                    <div class="col-sm-6"><button type="button" class="btn btn-primary form-control btn-full" onclick="saveAutoCategoriesAux()">Save changes</button></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="myModal_autopayMethod" class="modal fade" style="">
    <input id="xhide_autopaymethod" value="" type="hidden">
    <input id="xhide_autopaymethod_token" value="" type="hidden">
    <div class="modal-dialog" style="border: none!important;">
        <div class="modal-content">
            <div class="modal-body" id="xbody_autopayMethod"><button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>

            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-md-6 btn-margin-xs-screen"><button type="button" class="btn btn-default form-control btn-full" data-dismiss="modal">Cancel</button><br></div>
                    <div class="col-md-6"><button type="button" class="btn btn-primary form-control btn-full" onclick="saveAutopayMethodAux()">Add Payment Method</button></div>
                </div>
            </div>
        </div>
    </div>
</div>

    <?php
    $popuphdr="Success!";
    $popupcontent="";
    if(isset($msgCode)){
        include_once __DIR__.'/../components/messages.php';
        $popuphdr="Success!";
        $popupcontent="";
        if(isset($global_messages[$msgCode])){
            $popupcontent=$global_messages[$msgCode];
        }
        include_once __DIR__.'/components/popupsuccess.php';
    }
    include_once __DIR__.'/components/popupsuccess.php';
    include_once __DIR__.'/components/loading.php';
    include_once __DIR__.'/components/popuptransactionreportdetail.php';
    ?>
    <?php if(isset($msgCode)) : ?>
    <script>
            $('#myModal_success').modal();
            $('#myModal_autopayFreq').modal();

    </script>
    <?php endif; ?>
    <script type="text/javascript">
    var xurl = '<?php echo URL::to('/').'/'; ?>';
    var xatoken = '';
    
    </script>
   
    <script type="text/javascript">

		//export to csv code
            $('#exportCSV').click( function(){
			
				//alert($(this).attr('href')); return false;
				$.get( $(this).attr('href'), { formparam:$("#recurringRdatafilter").serialize()})
				  .done(function( data ) {
					window.location.href = '/master/index.php/recurringReport/downloadrecurringreport';
					return false;
				  });			  			
				return false;	
	
			});			
			
			function cancelrp(trans_id){
				var goahead = confirm("Are you sure you want to cancel an active autopayment?");
				if (goahead) {
					$('#myModal_success').modal('hide');
					$('#myModal_loading').modal();
					$.ajax({
							type: "GET",
							url: "/recurringReport/cancel/"+trans_id+"/4",
						}).done(function( msg ) {
							var resultTxt = $.parseJSON(msg);
							//alert(resultTxt.RESULT);
							$('#myModal_loading').modal('hide');
							$("#xpopupcontent").html("Cancel Autopayment"+"<br/>"+resultTxt.RESULT);
							$('#myModal_success').modal();							
						});
				}
			}
			
			function editpaymentdetails(id,token) {
                $('#xhide_autopaycat').val(id);
                $('#xhide_utopaycat_token').val(token);
                url = "<?php echo route('api2setautopaycat',array('token'=>'000','trans_id'=>-1));?>";
                url = url.replace("/000/", "/"+token+"/");
                url = url.replace("/-1", "/"+id);

                $.ajax({
                    type: "GET",
                    url: url,
                }).done(function( msg ) {
                    $('#body_autopayCat').html(msg);
                    rc_fee_ec = aux_rc_fee_ec;
                    rc_fee_cc = aux_rc_fee_cc;
                    rc_fee_amex = aux_rc_fee_amex;
                    $(".selectpicker").selectpicker();
                    $('#myModal_autopayCat').modal('show');
                });
            }
            
            function editfrequency(id,token) {
                $('#xhide_autopayfreq').val(id);
                $('#xhide_token').val(token);
                url = "<?php echo route('api2setautopayfreq',array('token'=>'000','trans_id'=>-1));?>";
                url = url.replace("/000/", "/"+token+"/");
                url = url.replace("/-1", "/"+id);

                $.ajax({
                    type: "GET",
                    url: url,
                }).done(function( msg ) {
                    $('#body_autopayFreq').html(msg);
                    $(".selectpicker").selectpicker();
                    $('#myModal_autopayFreq').modal('show');
                });
            }
            
            function editpaymentmethods(id,token) {
                $('#xhide_autopaymethod').val(id);
                $('#xhide_autopaymethod_token').val(token);
                url = "<?php echo route('api2setautopaymeth',array('token'=>'000','trans_id'=>-1));?>";
                url = url.replace("/000/", "/"+token+"/");
                url = url.replace("/-1", "/"+id);

                $.ajax({
                    type: "GET",
                    url: url,
                }).done(function( msg ) {
                    $('#xbody_autopayMethod').html(msg);
                    rc_fee_ec = aux_rc_fee_ec;
                    rc_fee_cc = aux_rc_fee_cc;
                    rc_fee_amex = aux_rc_fee_amex;
                    $(".selectpicker").selectpicker();
                    $('#myModal_autopayMethod').modal('show');
                });
            }


        function saveAutoFrequenceAux(){
            $("#myModal_autopayFreq").modal('hide');
            $("#myModal_loading").modal();
            token = $('#xhide_token').val();
            var params= $("#xhide_autopayfreq").val();
            var txurl="api2/autopay/savefrequence/"+token+"/";

            var xcontent= {'trans_id':params};
            xcontent.day=$("#xday").val();
            xcontent.freq=$("#xfreq").val();
            xcontent.start_date=$("#xstartdate").val();
            xcontent.end_date=$("#xenddate").val();

            txurl+=JSON.stringify(xcontent);

            $.ajax({
                url:xurl+txurl
            }).done(function(data){
                $("#xpopupheader").html("Autopay");
                $("#xpopupcontent").html(data.responsetext);
                $("#myModal_loading").modal("hide");
                $("#myModal_success").modal();
                $('#myModal_success').on('hide.bs.modal',function refresh(){
                    window.location.reload();
                });
            });
        }

        function saveAutoCategoriesAux(){
            var params= $("#xhide_autopaycat").val();
            var total_amount=0;
            token = $('#xhide_utopaycat_token').val();
            var txurl="api2/autopay/savecategories/"+token+"/";
            var categories=[];
            for (var i=0; tmp_obj = document.getElementById("xcheckpay_" + i); i++) {
                if (tmp_obj.checked) {
                    tmp_obj = document.getElementById("xinputpay_" + i);
                    tmp = tmp_obj.value.replace(/,/g, "");
                    tmp = parseFloat(tmp);
                    if (!isNaN(tmp) && tmp > 0) {
                        total_amount+=parseFloat(tmp);
                        var xname=$("#xinputpay_"+i).attr("xname");
                        var xid=tmp_obj.name;
                        categories.push({'amount':tmp,'id':xid,'name':xname});
                    }
                }
            }
            if(total_amount<=0){
                $("#xerror").show();
                return false;
            }
            $("#myModal_autopayCat").modal('hide');
            $("#myModal_loading").modal();
            var xcontent= {'trans_id':params};
            xcontent.total_amount=total_amount;
            xcontent.categories=categories;
            txurl+=JSON.stringify(xcontent);

            $.ajax({
                url:xurl+txurl
            }).done(function(data){
                $("#xpopupheader").html("Autopay");
                $("#xpopupcontent").html(data.responsetext);
                $("#myModal_loading").modal("hide");
                $("#myModal_success").modal();
                $('#myModal_success').on('hide.bs.modal',function refresh(){
                    window.location.reload();
                });
            });
        }

        function saveAutopayMethodAux(){
            var isgood=true;
            token = $('#xhide_autopaymethod_token').val();
            var txurl="api2/autopay/saveautopay_method/"+token+"/";
            var params= $("#xhide_autopaymethod").val();
            var xcontent= {'trans_id':params};
            if($("#xselect_paymethod").is(":checked")){//id_profile
                txurl+='prf/';
                xcontent.profile_id=$("#xselected_profile").val();
            }else if($("#checkbox4").is(":checked")){ //ec
                //validation
                if(!validate_ecname()){
                    isgood=false;
                }
                if(!validate_aba()){
                    isgood=false;
                }
                if(!validate_bank()){
                    isgood=false;
                }
                if(!isgood)return;
                txurl+='ec/';
                xcontent.ec_account_holder = $("#xppec_name").val();
                xcontent.ec_account_lholder='';
                xcontent.ec_routing_number=$("#xppec_routing").val();
                xcontent.ec_account_number=$("#xppec_acc").val();
                xcontent.ec_checking_savings = $("#xppec_type").val();
            }else if($("#checkbox5").is(":checked")){//cc

                //validation
                if(!validate_ccname()){
                    isgood=false;
                }
                if(!validate_ccard()){
                    isgood=false;
                }
                if(!validate_cvv()){
                    isgood=false;
                }

                if(!validate_expdate()){
                    isgood=false;
                }
                if(!validate_zip1()){
                    isgood=false;
                }
                if(!isgood) return;
                txurl+='cc/';

                xcontent.ccname = $("#xcardname").val();
                xcontent.ccnumber=$("#xcardnumber").val();
                xcontent.ccexp= $("#xexpdate").val();
                xcontent.cvv=$("#xcvv").val();
                xcontent.zip=$("#xzip1").val();
            }else{
                $("#xerror").show();
                return false;
            }
            $("#myModal_autopayMethod").modal('hide');
            $("#myModal_loading").modal();

            txurl+=JSON.stringify(xcontent);
            $.ajax({
                url:xurl+txurl
            }).done(function(data){
                if(data.response==1){ //changed
                    $("#xpopupheader").html("Autopay");
                    $("#xpopupcontent").html(data.responsetext);
                    $("#myModal_loading").modal("hide");
                    $("#myModal_success").modal();
                    $('#myModal_success').on('hide.bs.modal',function refresh(){
                        window.location.reload();
                    });
                }else{ //get error
                    $("#xpopupheader").html("Error");
                    $("#xpopupcontent").html(data.responsetext);
                    $("#myModal_loading").modal("hide");
                    $("#myModal_success").modal();
                    $('#myModal_success').on('hide.bs.modal',function refresh(){
                        window.location.reload();
                    });
                }

            });
        }

		
    </script>
    <script>
        var oTmr=1;
        var isrecurring = 1;
        var rc_fee_ec = 0;
        var rc_fee_cc = 0;
        var rc_fee_amex = 0;
    </script>
    <?php echo Rapyd::scripts(); ?>
    <script type="text/javascript" src="/js/editrecurringdetail.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/bootstrap-select.min.js"></script>

    <script src="/js/autopay.js"></script>
    <script src="/js/paymentsType.js"></script>
    <script src="/js/appvalidation.js"></script>
    <script src="/js/appvalidate.js"></script>
    <script src="/js/apptimer.js"></script>
    <script src="/js/appmethod.js"></script>
    <?php  include_once __DIR__.'/admin_components/admin_footer.php'; ?>
