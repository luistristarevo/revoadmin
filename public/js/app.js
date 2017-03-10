var xtoday="";
var DateDisabled=[];
var DateDisabledAutopay=[];
var DateDisabledDRP=[];
var existdrp=0;

function screen_change()
{
    //alert($(".sidebar-nav li").size());
    //ul_size = $(".sidebar-nav li").size() * 48 + 74;

    /*if($(window).height() > ul_size)
        ul_size = $(window).height();
    $('.shadow_cont').css('height',(ul_size) +'px' );*/

    if(($(window).width()) < 1024){
        $('.fixed-width-container').removeClass('fixed-width');
    }
    else
    {
       $('.fixed-width-container').addClass('fixed-width')
    }

    if(($(window).width()) < 850){
        $('.fixed-width-container-2').removeClass('fixed-width-2');
    }
    else
    {
        $('.fixed-width-container-2').addClass('fixed-width-2')
    }


}


$(window).resize(function() {
    screen_change()
});

screen_change();

function toggle(e){
   $("#sidebar-wrapper").css('zIndex','2000');
   if($(window).width() < 768){
       $(".sidebar-cont").css('zIndex','2000');
   }
   else{
       $(".sidebar-cont").css('zIndex','1000');
   }
   $("#wrapper").toggleClass("toggled");
   section_name = $('.section_name');
   button_export = $('.button-export');

    if($("#wrapper").attr('class')=='toggled'){
       section_name.fadeOut();
       button_export.fadeOut();
       section_name.removeClass('show-xs-screen-2');

        if($(window).width() < 768) {
            $(".shadow_cont").removeClass('noshadow');
        }
        else{
            setTimeout(function(){
                $(".shadow_cont").addClass('noshadow');
            }, 500);
        }

   }
   else{

       if($(window).width() < 768) {
           section_name.fadeIn();
           button_export.fadeIn();

           setTimeout(function(){
               $(".shadow_cont").addClass('noshadow');
           }, 500);
       }
        else{
           $(".shadow_cont").removeClass('noshadow');
       }
   }
}


if($(window).width() < 768){
    $(".shadow_cont").addClass('noshadow');
}

$('.container-fluid').click(function(e){
    $("#wrapper").removeClass('toggled');
});


$('.area_action_select').change(function(){
    /*alert($(this).val());
    if($(this).val()!='')
        $('#'+$(this).attr('data')).trigger( "click" );*/
});

var check_click = false;
$('.area_action_check,.area_action_check2').click(function(){
    if(check_click==false)
        $('#'+$(this).attr('data')).trigger( "click" );
    else
        check_click = false;
});
$('.area_action_check input,.area_action_check2 input').click(function(){
    check_click = true;
});

$(document).on('click','.select_checkbox .dropdown-menu li',function(){
    checkbox = $('#'+$(this).parent().parent().parent().parent().parent().parent().attr('data'));
    if(!checkbox.attr('checked'))
        checkbox.trigger( "click" );
});

$(document).on('click','.select_checkbox .dropdown-menu li',function(){
    checkbox = $('#'+$(this).parent().parent().parent().parent().parent().parent().attr('data'));
    if(!checkbox.attr('checked'))
        checkbox.trigger( "click" );
});


$('.tooltip_input').tooltip({
    placement: "top",
    trigger: "focus"
});

$('.tooltip_input_error').tooltip({
    placement: "top",
    trigger: 'click',
    template: '<div class="tooltip tooltip-error"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>'
});

$('.tooltip_input_error').click(function(){
    $(this).tooltip('hide');
});

$('.tooltip_input_error').blur(function(){
    $(this).tooltip('show');
});


$('.tooltip_hover').tooltip({
    placement: "top",
    trigger: "hover",
    container :'body'
});

$('.tooltip_hover_2').tooltip({
    placement: "bottom",
    trigger: "hover",
    container :'body'
});

$('.tooltip_click').tooltip({
    placement: "top",
    trigger: "hover",
    container :'body'
});


$('.tooltip1').click(function(e){
    $('.tooltip2').tooltip('hide');
});

$('.tooltip2').click(function(e){
    $('.tooltip1').tooltip('hide');
});


$('.checkbox input').click(function(){
    if($(this).attr('checked')){
        $(this).attr('checked',false);
    }
    else
    {
        $(this).attr('checked',true);
    }
});

$('.checkbox label').click(function(event){
    event.stopPropagation();
});

$('.group-btn-focused').click(function(){
    $(this).siblings('a').removeClass('btn-primary');
    $(this).addClass('btn-primary');
});


$('.collapse-action1').click(function(){
    $('#collapse2').collapse('hide');
    $('#checkbox5').attr('checked',false);
    $('#xselect_paymethod').attr('checked',false);
});

$('.collapse-action2').click(function(){
    $('#collapse1').collapse('hide');
    $('#checkbox4').attr('checked',false);
    $('#xselect_paymethod').attr('checked',false);
});

$('#xselect_paymethod').click(function(){
    $('#collapse2').collapse('hide');
    $('#collapse1').collapse('hide');
    $('#checkbox4').attr('checked',false);
    $('#checkbox5').attr('checked',false);

});


$('#inlineRadio1').click(function(){

    if($(this).is(':checked'))
    {
        $(this).prop('checked',true);
        $('#payment-details').collapse('hide');
    }
    $('#inlineRadio2').prop('checked',false);
    refreshRecurringDate();
    
});


$('#inlineRadio2').click(function(){
    if($(this).is(':checked'))
    {
        $(this).prop('checked',true);
        $('#payment-details').collapse('show');
    }
    $('#inlineRadio1').prop('checked',false);
    refreshRecurringDate();
});

$('.group-btn1').click(function(){
    $('#recurring-payment').collapse('hide');
    $('#payment-details').collapse('show');
    $('#inlineRadio2').prop('checked',true);
});

$('.group-btn2').click(function(){
    if($('#recurring-payment').hasClass('in')){
        return false;
    }
    else{
        $('#inlineRadio1').prop('checked',true);
        $('#inlineRadio1').trigger( "click" );
        $('#inlineRadio2').prop('checked',false);
        $('#payment-details').collapse('hide');
    }
    
});


$('.only-check').click(function(){
    var obj = $(this);
    $('.only-check').each(function (index, elem) {
        if (obj.attr('id') != $(elem).attr('id')) {
            $(elem).attr('checked', false);
            $(elem).attr('disabled', false);
        }
        else{
            $(elem).attr('disabled', true);
        }
    });

});

$('.head-accordion').click(function(){
    if($(this).children('img').hasClass('up'))
        $(this).children('img').removeClass('up');
    else
        $(this).children('img').addClass('up');
});

$('#date-label').click(function(){
    $(this).siblings('.datepicker-control:visible').trigger('click')
});

$('.dropdown-toggle').click(function(){
    $("#wrapper").removeClass("toggled");
});

$('.check-active').click(function(){
    $('.'+$(this).attr('data-ref')).trigger( "click" );
});

$('.input-active').click(function(){
    //alert('aaaa');
});










