<?php
if(!isset($atoken)){
    $atoken = $token;
};
?>

<header class="header-color">
    <div class="container">
        <div class="row">
            <div class="col-xs-2">
                <div id="logocont">
                    <img src="<?php echo asset('..'.Session::get('logo')); ?>">
                </div>
            </div>
            <div class="col-xs-6 padding"></div>
            <div class="col-xs-4 text-right usermenu">
                <div class="dropdown" >
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">Hello <?php echo session('user_logged')['username'];?> <span class="caret"></span></a>
                    <ul class="dropdown-menu  dropdown-menu-right" aria-labelledby="dropdownMenu1">
                        <li><a href="<?php echo route('adminprofile',array('token'=>$atoken));?>"><span class="fa fa-user"></span> Profile</a></li>
                        <li><a href="<?php echo route('adminlogout');?>"><span class="fa fa-power-off"></span> Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>

<?php
$permissions = Session::get('user_permissions');
$route_name = Route::getCurrentRoute()->getName();
$menu =0;
foreach($permissions as $p){
    if($p['route']==$route_name){
        $menu = $p['menu_focus'];
        break;
    }
}
?>
<div id="affix_main_cont">
<div id="affix_cont">
    <nav class="navbar navbar-default">
        <div class="container">
            <div class="navbar-header">
                <div id="logocontresponsive">
                    <img src="<?php echo asset('..'.Session::get('user_logo')); ?>">
                </div>
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li class="<?php if($menu == 1){?>active<?php } ?>"><a href="<?php
                        echo route('admindashboard',array('token'=>$atoken));?>">Dashboard</a></li>
                    <li><a href="#about">Favorites</a></li>
                    <li class="dropdown <?php if($menu == 3){?>active<?php } ?>">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Reports <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li class="<?php if($route_name == 'transactions'){?>active<?php } ?>"><a href="<?php echo route('transactions',['token'=>$atoken]);?>">Transactions</a></li>
                            <li class="<?php if($route_name == 'deposits'){?>active<?php } ?>"><a href="<?php echo route('deposits',['token'=>$atoken]);?>">Deposits Batch</a></li>
                            <li class="<?php if($route_name == 'recurring'){?>active<?php } ?>"><a href="<?php echo route('recurring',['token'=>$atoken]);?>">Autopayments</a></li>
                            <li class="<?php if($route_name == 'outbound'){?>active<?php } ?>"><a href="<?php echo route('outbound',['token'=>$atoken]);?>">Outbound</a></li>
                            <?php if(strtoupper((Session::get('user_app_data')['type']=='B'))){ ?><li class="<?php if($route_name == 'emaf'){?>active<?php } ?>"><a href="<?php echo route('emaf',['token'=>$atoken]);?>">EMAF</a></li><?php } ?>
                            <?php if(strtoupper((Session::get('user_app_data')['type']=='B'))){ ?><li class="<?php if($route_name == 'account'){?>active<?php } ?>"><a href="<?php echo route('account',['token'=>$atoken]);?>">Active Account</a></li><?php } ?>
                            <li class="<?php if($route_name == 'returned'){?>active<?php } ?>"><a href="<?php echo route('returned',['token'=>$atoken]);?>">Returned</a></li>
                            <li class="<?php if($route_name == 'paymentcategories'){?>active<?php } ?>"><a href="<?php echo route('paymentcategories',['token'=>$atoken]);?>">Payment Categories</a></li>
                        </ul>
                    </li>
                    <li class="dropdown <?php if($menu == 4){?>active<?php } ?>">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Tools <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li class="<?php if($route_name == 'applicationadmin'){?>active<?php } ?>"><a href="<?php echo route('applicationadmin',['token'=>$atoken]);?>">Application</a></li>
                            <li class="<?php if($route_name == 'promotion'){?>active<?php } ?>"><a href="<?php echo route('promotion',['token'=>$atoken]);?>">Promotions</a></li>
                            <li class="<?php if($route_name == 'importbatch'){?>active<?php } ?>"><a href="<?php echo route('importbatch',['token'=>$atoken]);?>">Import Batch</a></li>
                            <li class="<?php if($route_name == 'einvoice'){?>active<?php } ?>"><a href="<?php echo route('einvoice',['token'=>$atoken]);?>">EInvoice</a></li>
                        </ul>
                    </li>
                    <li class="dropdown <?php if($menu == 5){?>active<?php } ?>">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Manager <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li class="<?php if($route_name == 'plist'){?>active<?php } ?>"><a href="<?php echo route('plist',['token'=>$atoken]);?>">Verticals</a></li>
                            <li class="<?php if($route_name == 'glist'){?>active<?php } ?>"><a href="<?php echo route('glist',['token'=>$atoken]);?>">Groups</a></li>
                            <li class="<?php if($route_name == 'mlist'){?>active<?php } ?>"><a href="<?php echo route('mlist',['token'=>$atoken]);?>">Merchants</a></li>
                            <li role="separator" class="divider"></li>
                            <li class="<?php if($route_name == 'wulist'){?>active<?php } ?>"><a href="<?php echo route('wulist',['token'=>$atoken]);?>">Users</a></li>
                            <?php if(strtoupper((Session::get('user_app_data')['type']=='B'))){?>
                            <li class="<?php if($route_name == 'adminpricing'){?>active<?php } ?>"><a href="<?php echo route('adminpricing',['token'=>$atoken]);?>">Pricing</a></li>
                            <?php } ?>
                            <li class="<?php if($route_name == 'adminlist'){?>active<?php } ?>"><a href="<?php echo route('adminlist',['token'=>$atoken]);?>">Administrators</a></li>
                        </ul>
                    </li>
                    <li class="<?php if($menu == 6){?>active<?php } ?>">
                        <a href="<?php echo route('settings',['token'=>$atoken]);?>"  role="button" aria-haspopup="true" aria-expanded="false"><span class=""></span> Settings</a>
                    </li>

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Support <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li class="<?php if($route_name == 'tckhistory'){?>active<?php } ?>"><a href="<?php echo route('tckhistory',['token'=>$atoken,'adm'=>Session::get('user_logged')['id']]);?>">Ticket History</a></li>
                        </ul>
                    </li>
                </ul>
                <div id="toolname" class="hide-xs-screen">
                    Master Toolbox
                </div>
            </div>
        </div>
    </nav>
    <nav class="breadcrumbs">
        <div class="container"><?php echo Session::get('pgm_name');?></div>
    </nav>
</div>
</div>