<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light" style="background: linear-gradient( 
135deg , rgba(40,29,31,1) 0%, rgb(56 169 175) 100%); color:#FFFFFF; <?php echo $hris_display; ?>" >
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
      <div class="header-title">
              <?php  if(!empty($uptitle)) { echo urldecode( $uptitle); } ?>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        
      </li>
    </ul>

    <!-- SEARCH FORM -->
    <form class="form-inline ml-3" style="display:none;">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" type="submit">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </form>
    
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
   
      
      <li class="nav-item" style="margin-right:2px;">
         <a class="nav-link btn btn-sm btn-default"  target="_blank" href="https://hris.health.go.ug/national" >
         <i class="fas fa-flag"></i><b class="hidden-mobile"> National Manage</b>
        </a> 
      </li>
      <li class="nav-item" style="margin-right:2px;">
         <a class="nav-link btn btn-sm btn-default"  target="_blank" href="https://attend.health.go.ug/">
         <i class="fas fa-clock"></i><b class="hidden-mobile"> HRM Attend</b>
        </a> 
      </li>
      <li class="nav-item" style="margin-right:2px;">
         <a class="nav-link btn btn-sm btn-default"  target="_blank" href="<?php echo base_url()?>assessment/">
         <i class="fa fa-phone"></i><b class="hidden-mobile"> Support</b>
        </a> 
      </li>
    
      <li class="nav-item dropdown">
        <a class="nav-link btn btn-sm btn-default" data-toggle="dropdown" href="#" >
        <i class="fas fa-globe"></i><b class="hidden-mobile">IHRIS Demos</b>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <div class="dropdown-divider"></div>
          <a href="https://hris.health.go.ug/demo" target="_blank" class="dropdown-item">
            <i class="fas fa-globe"></i>iHRIS Manage Demo
          </a>
          <div class="dropdown-divider"></div>
          <a href="https://hris2.health.go.ug/train_demo/login" target="_blank" class="dropdown-item">
            <i class="fas fa-globe"></i> iHRIS Train
          </a>
          <div class="dropdown-divider"></div>
          <a href="https://hris2.health.go.ug/iHRIS/releases/4.1/DES_demo/login" target="_blank" class="dropdown-item">
            <i class="fas fa-globe"></i> DES Demo
          </a>
          <div class="dropdown-divider"></div>
          <a href="https://hris2.health.go.ug/iHRIS/dev/demo-chwr" target="_blank" class="dropdown-item">
            <i class="fas fa-globe"></i> CHWR Demo
          </a>
          <div class="dropdown-divider"></div>
          <a href="https://hris2.health.go.ug/dutyrosterdemo" target="_blank" class="dropdown-item">
            <i class="fas fa-globe"></i> Duty Roster/ Attend
          </a>
         
      </li>

    

    <li class="nav-item dropdown" style="margin-right:20px; margin-left:20px;">
        <a class="nav-link btn btn-sm btn-default" data-toggle="dropdown" href="#" >
        <i class="fas fa-user"></i><b><?php echo $this->session->userdata('fullname'); ?></b>
        </a>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    
    <div class="dropdown-divider"></div>
      <a href="<?php echo base_url('logout') ?>" class="dropdown-item"><i class="pe-7s-key"></i> <?php if($this->session->userdata('fullname')){ echo display('logout');}else{echo display('login');} ?></a>  
     
      <?php if ($this->session->userdata('isAdmin')) { ?>
         <div class="dropdown-divider"></div>
          <a href="<?php echo base_url('dashboard/home/setting') ?>" class="dropdown-item"><i class="pe-7s-settings"></i> <?php echo display('setting') ?></a>
      <div class="dropdown-divider"></div>
       <a href="<?php echo base_url('dashboard/home/profile') ?>" class="dropdown-item"><i class="pe-7s-users"></i> <?php echo display('profile') ?></a>
     <?php } ?>      
    </li>
     
      <!-- <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
        </a>
      </li> -->
      
    </ul>
  </nav>
  <!-- /.navbar -->
