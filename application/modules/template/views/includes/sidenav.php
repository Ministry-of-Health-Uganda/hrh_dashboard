<style>
  .btn-primary {
    color: var(--hrh-on-primary);
    background-color: var(--hrh-primary);
    border: none;
    box-shadow: var(--hrh-elevation-1);
  }
  .btn-primary:hover {
    background-color: var(--hrh-primary-dark);
    box-shadow: var(--hrh-elevation-2);
  }
  .bg-blue {
    background-color: var(--hrh-primary) !important;
  }
  .dropdown-item.active,
  .dropdown-item:active {
    color: var(--hrh-on-primary);
    background-color: var(--hrh-primary-dark);
  }
  .dash-icon {
    color: rgba(255,255,255,0.9);
    font-size: 1rem;
    margin-right: 0.5rem;
  }
  .main-sidebar .fa-circle.nav-icon {
    color: rgba(255,255,255,0.6);
    font-size: 6px !important;
  }
  .nav-drop {
    font-size: 0.8125rem;
    font-weight: 500;
    text-overflow: ellipsis;
    overflow: hidden;
  }
  .nav-item {
    font-weight: 500;
  }
  body::-webkit-scrollbar-track {
    background-color: #EEEEEE;
  }
  body::-webkit-scrollbar {
    width: 8px;
    height: 8px;
    background-color: #EEEEEE;
  }
  body::-webkit-scrollbar-thumb {
    background-color: #BDBDBD;
    border-radius: 4px;
  }
  body::-webkit-scrollbar-thumb:hover {
    background-color: #9E9E9E;
  }
  .sido {
    clear: both;
    overflow: auto;
    min-height: 100% !important;
  }
  .sido::-webkit-scrollbar-track {
    background-color: #37474F !important;
  }
  .sido::-webkit-scrollbar {
    width: 6px;
    background-color: #37474F !important;
  }
  .sido::-webkit-scrollbar-thumb {
    background-color: #546E7A;
    border-radius: 3px;
  }
  /* Sidebar brand: same color as top nav (teal + white text) */
  .main-sidebar .brand-link {
    background-color: var(--hrh-primary) !important;
    color: var(--hrh-on-primary) !important;
    text-align: center;
    font-weight: 600;
    box-shadow: none;
  }
</style>
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4" style="<?php echo $hris_display; ?>">
  <!-- Brand Logo -->
  <a href="<?php echo base_url(); ?>" class="brand-link">
    <!-- <img src="../../dist/img/AdminLTELogo.png"
           alt="AdminLTE Logo"
           class="brand-image img-circle elevation-3"
           style="opacity: .8"> -->
    <span class="brand-text  font-weight-bold" style="text-align:center;">HRH DASHBOARD</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar sido" id="style4">
    <!-- Sidebar user (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3" style="text-align:center;">
      <div class="image">
        <p><img src="<?php echo base_url() ?>assets/logo.jpg" class="img-circle elevation-2" alt="User Image" style="width:45px; height:45px;"></p>
      </div>

    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2" style="overflow:hidden; font-size:14px;">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
        <li class="nav-item has-treeview">
          <a href="<?php echo base_url(); ?>" class="nav-link">
            <i class="fa fa-tachometer-alt dash-icon"></i>
            <p>
              Main Dashboard
              </i>
            </p>
          </a>
        </li>



        <?php if (!empty($this->session->userdata('fullname'))) { ?>
          <li class="nav-item">
            <a href="<?php echo base_url() ?>performance" class="nav-link">
              <i class="fas fa-database dash-icon"></i>
              <p>
                iHRIS Dashboard
              </p>
            </a>
          </li>


          <li class="nav-item has-treeview ">
            <a href="#" class="nav-link">
              <i class="fas fa-clock dash-icon"></i>
              <p>
                Attendance
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?php echo base_url() ?>dataprep/rates" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p class="nav-drop">Reporting Rate</p>
                </a>
              </li>

            </ul>
          </li>
          <li class="nav-item">
            <a href="<?php echo base_url() ?>dataprep/absenteesm" class="nav-link">
              <i class="fa fa-history dash-icon"></i>
              <p>
                Absenteesm Report
              </p>
            </a>
          </li>
        <?php } ?>
        <li class="nav-item has-treeview ">
          <a href="#" class="nav-link">
            <i class="fas fa-users dash-icon"></i>
            <p>
              Staffing
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?php echo base_url() ?>audit/auditReport" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p class="nav-drop">Audit Report</p>
              </a>
            </li>
            <!-- <li class="nav-item">
                <a href="<?php echo base_url() ?>audit/lfacAudit" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p class="nav-drop">Facility Audit Report</p>
                </a>
              </li> -->

          </ul>
        </li>


        <li class="nav-item has-treeview ">
          <a href="#" class="nav-link">
            <i class="fas fa-registered dash-icon"></i>
            <p>
              Reg / Licensure Reports
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?php echo base_url(); ?>councils/allied" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p class="nav-drop">Allied Health Professionals Council</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url(); ?>councils/medical" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p class="nav-drop">Uganda Medical & Dental Practitioners</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url(); ?>councils/pharmacy" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p class="nav-drop">Pharmacy Society / Pharmacy Board</p>
              </a>
            </li>
            <!-- <li class="nav-item">
                <a href="https://hris2.health.go.ug/reports/unmc/registration.html"  target="_blank" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p class="nav-drop">Uganda Nurses and Midwives Council</p>
                </a>
              </li> -->
            <li class="nav-item">
              <a href="<?php echo base_url(); ?>councils/pharma_society" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p class="nav-drop">License pharmaceutical Society</p>
              </a>
            </li>
          </ul>
        </li>

        <li class="nav-item">
          <a href="https://hris2.health.go.ug/community_registry/" target="_blank" class="nav-link">
            <i class="fa fa-h-square dash-icon"></i>
            <p>
              CHW Registry
            </p>
          </a>
        </li>

        <li class="nav-item has-treeview ">
          <a href="#" class="nav-link">
            <i class="fas fa-graduation-cap dash-icon"></i>
            <p>
              Professional Councils
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="https://hris2.health.go.ug/allied_council" target="_blank" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p class="nav-drop">Allied Health Professionals Council</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="https://hris2.health.go.ug/umdpc" target="_blank" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p class="nav-drop">Uganda Medical & Dental Practitioners</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="https://hris2.health.go.ug/nurses_council" target="_blank" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p class="nav-drop">Uganda Nurses and Midwives Council</p>
              </a>
            </li>

          </ul>
        </li>

        <li class="nav-item has-treeview ">
          <a href="#" class="nav-link">
            <i class="fas fa-book-reader dash-icon"></i>
            <p>
              Training
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="https://hris2.health.go.ug/national_train" target="_blank" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p class="nav-drop">In Service Training</p>
              </a>
            </li>
            <!-- <li class="nav-item">
                <a href="https://hris2.health.go.ug/iHRIS/releases/4.1/DES"  target="_blank" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p class="nav-drop">DES</p>
                </a>
              </li> -->
          </ul>
        </li>
        <li class="nav-item has-treeview ">
          <a href="#" class="nav-link">
            <i class="fa fa-hospital dash-icon"></i>
            <p>
              PNFP Organisations
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="http://41.173.3.36/ucmb/login" target="_blank" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p class="nav-drop">Uganda Catholic Med. Bureau</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="http://hris.upmb.co.ug:8080/upmb" target="_blank" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p class="nav-drop">Uganda Protestant Med. Bureau</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a href="https://hris.health.go.ug/national" target="_blank" class="nav-link">
            <i class="fa fa-flag dash-icon"></i>
            <p>
              National Manage
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="https://hris2.health.go.ug/attendance" target="_blank" class="nav-link">
            <i class="fa fa-clock dash-icon"></i>
            <p>
              Districts Attendance
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="https://attend.health.go.ug" target="_blank" class="nav-link">
            <i class="fa fa-clock dash-icon"></i>
            <p>
              HRM Attend
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="https://mobileihris.health.go.ug/" target="_blank" class="nav-link">
            <i class="fa fa-clock dash-icon"></i>
            <p>
              iHRIS Update Tool
            </p>
          </a>
        </li>



        <?php if ($this->session->userdata('isAdmin')) { ?>



          <li class="nav-item has-treeview  <?php echo (($this->uri->segment(2) == "user") ? "active" : null) ?>">
            <a href="<?php echo base_url(); ?>files/file" class="nav-link">

              <i class="fa fa-upload dash-icon"></i>
              <p><?php echo "Upload KPI Data" ?></p>
            </a>
          <?php } ?>
          <?php if ($this->session->userdata('isAdmin')) { ?>



          <li class="nav-item has-treeview   <?php echo (($this->uri->segment(2) == "user" ||  $this->uri->segment(2) == "language" || $this->uri->segment(2) == "backup_restore" || $this->uri->segment(2) == "setting") ? "active" : null) ?>">
            <a href="#" class="nav-link">

              <i class="fa fa-cog dash-icon"></i>
              <p><?php echo display('setting') ?>
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item <?php echo (($this->uri->segment(2) == "user") ? "active" : null) ?>">
                <a href="#" class="nav-link">
                  <p><?php echo display('user') ?></p>

                  <i class="fa fa-angle-left pull-right"></i>

                </a>
                <ul class="nav nav-treeview">
                  <li><a class="nav-link " href="<?php echo base_url('dashboard/user/form') ?>">
                      <i class="far fa-circle nav-icon"></i>
                      <?php echo display('add_user') ?></a>
                  </li>
                  <li><a class="nav-link " href="<?php echo base_url('dashboard/user/index') ?>">
                      <i class="far fa-circle nav-icon"></i>
                      <?php echo display('user_list') ?></a>
                  </li>
                </ul>
              </li>

              <li class="nav-item">
                <a class="nav-link " href="<?php echo base_url(); ?>indicator/assessments">
                  <i class="far fa-circle nav-icon"></i>
                  <p><?php echo "iHRIS  Assessment" ?></p>
                </a>
              </li>


              <li class="nav-item">
                <a class="nav-link " href="<?php echo base_url(); ?>indicator/subject">
                  <i class="far fa-circle nav-icon"></i>
                  <p><?php echo "Subject Areas" ?></p>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link " href="<?php echo base_url(); ?>indicator/kpis">
                  <i class="far fa-circle nav-icon"></i>
                  <p><?php echo "Performance Indicators" ?></p>
                </a>
              </li>

              <li class="nav-item">
                <a class="nav-link " href="<?php echo base_url(); ?>indicator/kpiDisplay">
                  <i class="far fa-circle nav-icon"></i>
                  <p><?php echo "Kpi Display Control" ?></p>
                </a>
              </li>

              <li class="nav-item <?php echo (($this->uri->segment(2) == "setting") ? "active" : null) ?>">
                <a class="nav-link " href="<?php echo base_url('dashboard/setting') ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p><?php echo display('application_setting') ?></p>
                </a>
              </li>

              <li class="nav-item <?php echo (($this->uri->segment(2) == "language") ? "active" : null) ?>">
                <a class="nav-link " href="<?php echo base_url('dashboard/language') ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p><?php echo display('language') ?></p>
                </a>
              </li>

              <li class="nav-item <?php echo (($this->uri->segment(2) == "backup_restore") ? "active" : null) ?>">
                <a class="nav-link " href="<?php echo base_url('dashboard/backup_restore/index') ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p><?php echo display('backup_and_restore') ?></p>
                </a>
              </li>



            </ul>

          </li>

        <?php } ?>
        <!-- ends of admin area -->



      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>
