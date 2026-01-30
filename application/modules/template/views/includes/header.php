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
    /* Theme: HRH Dashboard – single color, Material Design, eye-friendly */
    :root {
      --hrh-primary: #00796B;
      --hrh-primary-dark: #00695C;
      --hrh-primary-light: #B2DFDB;
      --hrh-surface: #FAFAFA;
      --hrh-on-primary: #FFFFFF;
      --hrh-elevation-1: 0 1px 3px rgba(0,0,0,0.08);
      --hrh-elevation-2: 0 2px 6px rgba(0,0,0,0.1);
      --hrh-elevation-4: 0 4px 12px rgba(0,0,0,0.1);
    }
    @media (max-width: 767px) {
      .hidden-mobile { display: none; }
    }
    @media (min-width: 992px) {
      html { font-size: 14px; }
    }
    body {
      -webkit-font-smoothing: antialiased;
      background-color: var(--hrh-surface);
    }
    .hrh-theme .content-wrapper { background-color: var(--hrh-surface); }
    .hrh-theme .card {
      border: none;
      border-radius: 8px;
      box-shadow: var(--hrh-elevation-1);
    }
  </style>

<div class="facility_block">

  <?php
  if (!isset($body_sidebar)) { $body_sidebar = ''; }
  if (!isset($hris_display)) { $hris_display = ''; }
  if (!empty($_GET['display']) && $_GET['display'] == 'ihris') {
    $hris_display = 'display:none';
    $body_sidebar = 'sidebar-collapse';
  ?>
  <style>
    .btn { font-size: 9px; }
  </style>
  <?php
  }
  ?>

</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed hrh-theme <?php echo $body_sidebar ?>">
<!-- Site wrapper -->
<div class="wrapper">
<div class="base_url" style="display:none;">
<?php echo base_url();

?>

</div> 
