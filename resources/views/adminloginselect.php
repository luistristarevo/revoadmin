<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Master Toolbox</title>
    <link href="<?php echo asset('css/bootstrap.css');?>" rel="stylesheet">
    <link href="<?php echo asset('css/adminui.css');?>" rel="stylesheet">
    <link href="<?php echo asset('css/vegas.css');?>" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
        <?php
        if(isset($css)){
            echo $css;
        }
        ?>
    </style>
</head>
<body id="login-body" <?php if(!isset($backgrounds) && isset($bgcolor)){?> style="background-color: <?php echo $bgcolor;?>" <?php }?>>

<div class="container">
    <div class="col-sm-4 col-sm-push-4 text-center">
        <?php echo  Form::open(array('route' => array('loginselect', $type))) ?>
        <input type="hidden" name="tokendata" value="<?php echo $token;?>">
        <img src="<?php echo asset('img/revopay-white.png');?>">
        <div class="panel-shadow">
            <h3>Select a <?php
                switch(strtolower($type)){
                    case 'p':?>Partner<?php  break;
                    case 'g':?>Group<?php  break;
                    case 'm':?>Merchant<?php  break;
                }?></h3>
            <select name="select" required class="form-control form-group">
            <option value=""></option>
            <?php
            foreach($access as $item){
                switch(strtolower($type)){
                    case 'p':
                        if($item['idp']>0){
                            ?>
                            <option value="<?php echo $item['idp']; ?>"><?php echo $item['partner_title']; ?></option>
                            <?php
                        }
                        break;
                    case 'g':
                        if($item['idc']>0){
                            ?>
                            <option value="<?php echo $item['idc']; ?>"><?php echo $item['company_name']; ?></option>
                            <?php
                        }
                        break;
                    case 'm':
                        if($item['idm']>0){
                            ?>
                            <option value="<?php echo $item['idm']; ?>"><?php echo $item['name_clients']; ?></option>
                            <?php
                        }
                        break;
                }
            }
            ?>
            </select>

            <input id="submit" type="submit" class="btn btn-block btn-success btn-lg" value="Select">
            <a href="<?php echo route('admindashboard',array('token'=>$token)); ?>" class="btn btn-block btn-default btn-lg">Continue</a>
        </div>
        </form>
    </div>
</div>
<?php if(!isset($bgcolor)){?>
<img class="slide-left" src="<?php echo asset('img/slide-arrow.png');?>">
<img class="slide-right" src="<?php echo asset('img/slide-arrow.png');?>">
<?php }?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="<?php echo asset('js/bootstrap.min.js');?>"></script>
<script src="<?php echo asset('js/vegas.min.js');?>"></script>
<script>
    $('#submit').click(function(){
        if($("form")[0].checkValidity()==false){
            $('.panel-shadow').addClass('error-anim');
            setTimeout(function(){
                $('.panel-shadow').removeClass('error-anim');
            }, 1000);
        }
    });




            <?php
            if(isset($backgrounds)){
                ?>
                bodyvegas = $("#example, body");
                bodyvegas.vegas({
                            slides: [
                <?php
                foreach($backgrounds as $bg){
                ?>
                { src: "<?php echo asset('img/customlogin/background/'.$bg);?>" },
                <?php
                }
                ?> ],
                    delay : 10000,

                });
                    <?php
            }
            else{
                if(!isset($bgcolor)){
                ?>
            bodyvegas = $("#example, body");
            bodyvegas.vegas({
                slides: [
                { src: "<?php echo asset('img/customlogin/background/slide1.jpg');?>" },
                { src: "<?php echo asset('img/customlogin/background/slide2.jpg');?>" },
                { src: "<?php echo asset('img/customlogin/background/slide3.jpg');?>" },
                ],
                delay : 10000,

            });
                <?php
                }
            }
            ?>





    $('.slide-right').click(function(){
        bodyvegas.vegas('next');
    });
    $('.slide-left').click(function(){
        bodyvegas.vegas('previous');
    });


</script>
</body>
</html>