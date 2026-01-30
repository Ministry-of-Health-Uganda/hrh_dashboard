<style>
  /* Top navbar: single color, Material Design */
  .navbar-hrh {
    background: var(--hrh-primary) !important;
    min-height: 3.5rem;
    box-shadow: var(--hrh-elevation-2);
  }
  .navbar-hrh .navbar-nav .nav-link {
    color: var(--hrh-on-primary) !important;
    font-weight: 500;
    padding: 0.5rem 0.75rem;
    border-radius: 4px;
    transition: background 0.2s ease, color 0.2s ease;
  }
  .navbar-hrh .navbar-nav .nav-link:hover {
    background: var(--hrh-primary-dark);
    color: var(--hrh-on-primary) !important;
  }
  .navbar-hrh .nav-page-title {
    color: var(--hrh-on-primary);
    font-size: 1.125rem;
    font-weight: 600;
    margin: 0;
    padding-left: 0.5rem;
    letter-spacing: 0.01em;
  }
  .navbar-hrh .nav-divider {
    width: 1px;
    background: rgba(255,255,255,0.25);
    margin: 0 0.5rem;
    align-self: stretch;
  }
  .navbar-hrh .dropdown-menu {
    border-radius: 4px;
    box-shadow: var(--hrh-elevation-4);
    border: none;
    padding: 0.5rem 0;
  }
  .navbar-hrh .dropdown-item {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    transition: background 0.15s ease;
  }
  .navbar-hrh .dropdown-item:hover {
    background: var(--hrh-primary-light);
  }
  .navbar-hrh .dropdown-item i {
    width: 1.25rem;
    margin-right: 0.5rem;
    opacity: 0.85;
  }
  .navbar-hrh .dropdown-header {
    font-size: 0.6875rem;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: #78909C;
    padding: 0.5rem 1rem;
    font-weight: 600;
  }
</style>
<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light navbar-hrh" style="<?php echo $hris_display; ?>">
  <!-- Left: menu + page title -->
  <ul class="navbar-nav align-items-center">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button" aria-label="Toggle menu"><i class="fas fa-bars"></i></a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <h1 class="nav-page-title">
        <?php echo !empty($uptitle) ? htmlspecialchars(urldecode($uptitle)) : 'HRH Dashboard'; ?>
      </h1>
    </li>
  </ul>

  <!-- Right: external links, demos, user -->
  <ul class="navbar-nav ml-auto align-items-center flex-wrap">
    <!-- External links group -->
    <li class="nav-item">
      <span class="nav-divider d-none d-md-inline-block" aria-hidden="true"></span>
    </li>
    <li class="nav-item">
      <a class="nav-link" target="_blank" rel="noopener" href="https://hris.health.go.ug/national">
        <i class="fas fa-flag"></i><span class="d-none d-lg-inline ml-1">National Manage</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" target="_blank" rel="noopener" href="https://attend.health.go.ug/">
        <i class="fas fa-clock"></i><span class="d-none d-lg-inline ml-1">HRM Attend</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="<?php echo base_url(); ?>assessment/">
        <i class="fas fa-headset"></i><span class="d-none d-lg-inline ml-1">Support</span>
      </a>
    </li>

    <li class="nav-item">
      <span class="nav-divider d-none d-md-inline-block" aria-hidden="true"></span>
    </li>

    <!-- IHRIS Demos dropdown -->
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-globe"></i><span class="d-none d-lg-inline ml-1">IHRIS Demos</span>
      </a>
      <div class="dropdown-menu dropdown-menu-right">
        <h6 class="dropdown-header">Demo systems</h6>
        <a href="https://hris.health.go.ug/demo" target="_blank" rel="noopener" class="dropdown-item">
          <i class="fas fa-globe text-muted"></i> iHRIS Manage Demo
        </a>
        <a href="https://hris2.health.go.ug/train_demo/login" target="_blank" rel="noopener" class="dropdown-item">
          <i class="fas fa-globe text-muted"></i> iHRIS Train
        </a>
        <a href="https://hris2.health.go.ug/community_registry_demo" target="_blank" rel="noopener" class="dropdown-item">
          <i class="fas fa-globe text-muted"></i> CHWR Demo
        </a>
      </div>
    </li>

    <li class="nav-item">
      <span class="nav-divider d-none d-md-inline-block" aria-hidden="true"></span>
    </li>

    <!-- User dropdown -->
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle d-flex align-items-center" data-toggle="dropdown" href="#" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-user-circle mr-1"></i>
        <span class="d-none d-md-inline text-truncate" style="max-width: 140px;"><?php echo htmlspecialchars($this->session->userdata('fullname') ?: 'Account'); ?></span>
      </a>
      <div class="dropdown-menu dropdown-menu-right">
        <h6 class="dropdown-header">Account</h6>
        <a href="<?php echo base_url('logout'); ?>" class="dropdown-item">
          <i class="fas fa-sign-out-alt text-muted"></i> <?php echo $this->session->userdata('fullname') ? display('logout') : display('login'); ?>
        </a>
        <?php if ($this->session->userdata('isAdmin')) { ?>
        <div class="dropdown-divider"></div>
        <a href="<?php echo base_url('dashboard/home/setting'); ?>" class="dropdown-item">
          <i class="fas fa-cog text-muted"></i> <?php echo display('setting'); ?>
        </a>
        <a href="<?php echo base_url('dashboard/home/profile'); ?>" class="dropdown-item">
          <i class="fas fa-user text-muted"></i> <?php echo display('profile'); ?>
        </a>
        <?php } ?>
      </div>
    </li>
  </ul>
</nav>
<!-- /.navbar -->
