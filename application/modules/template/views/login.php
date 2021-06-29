<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title><?php echo (!empty($setting->title)?$setting->title:null) ?> :: <?php echo (!empty($title)?$title:null) ?></title>

        <!-- Favicon and touch icons -->
        <link rel="shortcut icon" href="<?php echo base_url((!empty($setting->favicon)?$setting->favicon:'assets/img/icons/favicon.png')) ?>" type="image/x-icon">
        
        <!-- Start Global Mandatory Style -->
        <!-- Bootstrap -->
           <!-- Favicon and touch icons -->
        <link rel="shortcut icon" href="<?php echo (!empty($setting->favicon)?$setting->favicon:null) ?>">

        <!-- Bootstrap --> 
        <link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <?php if (!empty($setting->site_align) && $setting->site_align == "RTL") {  ?>
            <!-- THEME RTL -->
            <link href="<?php echo base_url(); ?>assets/css/bootstrap-rtl.min.css" rel="stylesheet" type="text/css"/>
            <link href="<?php echo base_url('assets/css/custom-rtl.css') ?>" rel="stylesheet" type="text/css"/>
        <?php } ?>
        <!-- 7 stroke css -->
        <link href="<?php echo base_url(); ?>assets/css/pe-icon-7-stroke.css" rel="stylesheet" type="text/css"/>
        <!-- style css -->
        <link href="<?php echo base_url(); ?>assets/css/custom.css" rel="stylesheet" type="text/css"/>
        <!-- Theme style rtl -->
    </head>

<style>
@import 'https://fonts.googleapis.com/css?family=Open+Sans|Quicksand:400,700';

/*--------------------
General Style
---------------------*/
*,
*::before,
*::after {
  box-sizing: border-box;
}

body,
html {

  font-family: 'Quicksand', sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}

body {
  background: rgba(30,29,31,1);
    background: -moz-linear-gradient(-45deg, rgba(30,29,31,1) 0%, rgba(223,64,90,1) 100%);
    background: -webkit-gradient(left top, right bottom, color-stop(0%, rgba(30,29,31,1)), color-stop(100%, rgba(223,64,90,1)));
    background: -webkit-linear-gradient(
-45deg
, rgba(30,29,31,1) 0%, rgb(64 223 186) 100%);
    background: -o-linear-gradient(-45deg, rgba(30,29,31,1) 0%, rgba(223,64,90,1) 100%);
    background: -ms-linear-gradient(-45deg, rgba(30,29,31,1) 0%, rgba(223,64,90,1) 100%);
    background: linear-gradient(
135deg
, rgba(30,29,31,1) 0%, rgb(56 169 175) 100%);
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#1e1d1f', endColorstr='#df405a', GradientType=1 );
}

/*--------------------
Text
---------------------*/

h2, h3 {
  font-size: 16px;
	letter-spacing: -1px;
}

h2 {
	color: #747474;
	text-align: center;
}

h3 {
	color: #032942;
	text-align: right;
}




label {
	float: left;
  width: 100%;
	top: 0px;
	color: #032942;
	font-size: 13px;
	font-weight: 700;
	text-align: left;
	line-height: 1.5;
}


input[type=text],
input[type=password] {
    width: 100%;
    height: 32px;
 
    background-color: rgba(0,0,0,0.03);
    border: none;
    display: inline;
    color: #303030;
    font-size: 16px;
    font-weight: 400;

    
    -webkit-box-shadow: inset 1px 1px 0px rgba(0,0,0,0.05), 1px 1px 0px rgba(255,255,255,1);
    -moz-box-shadow: inset 1px 1px 0px rgba(0,0,0,0.05), 1px 1px 0px rgba(255,255,255,1);
    box-shadow: inset 1px 1px 0px rgba(0,0,0,0.05), 1px 1px 0px rgba(255,255,255,1);
}

input[type=text]:focus,
input[type=password]:focus {
    background-color: #f8f8c6;
    outline: none;
}



input[type=submit]:hover {
	background-color: rgb(56 169 175);
  border: 1px #FFFFFF solid;
}

input[type=submit]:focus {
	outline: none;
}


.box-form, .box-info, .b, .b-support, .b-cta,
input[type=submit], p.field span.i {
    
	-webkit-transition: all 0.3s;
     -moz-transition: all 0.3s;
      -ms-transition: all 0.3s;
       -o-transition: all 0.3s;
          transition: all 0.3s;
}

</style>
    <body>
        <!-- Content Wrapper -->
        <div class="login-wrapper"> 
            <div class="container-center">
                <div class="panel" style="border-radius:7px;">
                    <div class="panel-heading">
                        <div class="view-header">
                           
                             <div class="header-title" style="text-align:center; margin: 0 auto;">
                                <h4><?php echo (!empty($setting->title)?$setting->title:null)." ".display('login'); ?></h4>
                            </div>
                        </div>
                       <div class="row">
                            <!-- alert message -->
                            <?php if ($this->session->flashdata('message') != null) {  ?>
                            <div class="alert alert-info alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <?php echo $this->session->flashdata('message'); ?>
                            </div> 
                            <?php } ?>
                            
                            <?php if ($this->session->flashdata('exception') != null) {  ?>
                            <div class="alert alert-danger alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <?php echo $this->session->flashdata('exception'); ?>
                            </div>
                            <?php } ?>
                            
                            <?php if (validation_errors()) {  ?>
                            <div class="alert alert-danger alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <?php echo validation_errors(); ?>
                            </div>
                            <?php } ?> 
                        </div>
                    </div>


                       <div class="panel-body">
                        <?php echo form_open('login','id="loginForm" novalidate'); ?>
                            <div class="form-group">
                                <label class="control-label" for="email"><?php echo display('email') ?></label>
                                <input type="text" placeholder="<?php echo display('email') ?>" name="email" id="email" class="form-control"> 
                            </div>
                            <div class="form-group" style="margin-bottom:10px;">
                                <label class="control-label" for="password"><?php echo display('password') ?></label>
                                <input type="password"  placeholder="<?php echo display('password') ?>" name="password" id="password" class="form-control"> 
                            </div>
                          

                   
                            <div style="margin-top:10px !important;"> 
                                 
                                <button  type="submit" class="btn btn-success" style="width:100%; margin-bottom:7px; border-radius:4px;"><?php echo display('login') ?></button> 
                                <button  type="reset" class="btn btn-info" style="width:100%; border-radius:4px;"><?php echo display('reset') ?></button>
                            </div>
                       <?php echo form_close();?>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>