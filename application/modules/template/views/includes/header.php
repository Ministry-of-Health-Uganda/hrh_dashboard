<!DOCTYPE html>
<html ng-app="hrhApp">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $setting->title ?> - <?php echo (!empty($title)?$title:null) ?></title>
        <!-- Favicon and touch icons -->
  <link rel="shortcut icon" href="<?php echo base_url(!empty($settings->favicon)?$settings->favicon:"assets/images/icons/favicon.png"); ?>">
       
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/fontawesome-free/css/all.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <!-- overlayScrollbars -->
  <!-- <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css"> -->
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bootstrap-datepicker/css/bootstrap-datepicker.min.css">
  
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/adminlte.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  
  <script src="https://code.highcharts.com/highcharts.js"></script>
  <script src="https://code.highcharts.com/highcharts-more.js"></script>
  <script src="https://code.highcharts.com/modules/exporting.js"></script>
  <script src="https://code.highcharts.com/modules/export-data.js"></script>
  <script src="https://code.highcharts.com/modules/accessibility.js"></script>
  <style>
      @media (max-width: 767px) {
        .hidden-mobile {
          display: none;
        }
        }
      html{
  zoom:85%;
        }
  </style>

<div class="facility_block">
  <script>
      var url_string = window.location.href;
      var url = new URL(url_string);
      var district = url.searchParams.get("district");
      console.log(district);
  </script>

  <?php
  if($GET['district']=="hris"){
    $hris_display = "display:none";
    $body_sidebar = "sidebar-collapse";
  }
  ?>

</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed <?php echo $body_sidebar ?>">
<!-- Site wrapper -->
<div class="wrapper">
<div class="base_url" style="display:none;">
<?php echo base_url();



?>

</div> 
<?php echo $GET['district'];



?>
