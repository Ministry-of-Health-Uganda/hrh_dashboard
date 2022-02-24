<style>
  .btn-primary {
    color: #fff;
    background-color: #49878d;
    border-color: #45969e;
    box-shadow: none;
}
 .bg-blue {
    background-color: #096f75!important;
}
.dropdown-item.active, .dropdown-item:active {
  color: #fff;
  text-decoration: none;
  background-color: #348085;
}
.fa-bars{
  background:#FFFFFF !important;

}
.dash-icon{
color:#37989d;
font-size:15px;
margin-right:4px;
}
.fa-circle{
color:#37989d;
font-size:6px !important;
}
.nav-drop{
 font-size:10px;
 font-weight:560;
 text-overflow: ellipsis;
 overflow: hidden; 
 

}
.nav-item{
  font-weight:570;
}


body::-webkit-scrollbar-track
{
	-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
	background-color: #F5F5F5;
}

body::-webkit-scrollbar
{
	width: 0.5em;
	background-color: #F5F5F5;
}

body::-webkit-scrollbar-thumb
{
	background-color: #58a4aa; 
  height:70%;
	border: 1px solid #555555;
  border-radius:4px;
}
.sido{
  clear:both;
  overflow:auto;
  min-height:100% !important;
}
.sido::-webkit-scrollbar-track
{
	-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3) !important;
	background-color: #F5F5F5;
}

.sido::-webkit-scrollbar
{
	width: 0.5em;
	background-color: #F5F5F5 !important;
}

.sido::-webkit-scrollbar-thumb
{
	background-color: #58a4aa; ; 
	border: 1px solid #555555 !important;
  border-radius:4px;
}
</style>
 <!-- Main Sidebar Container -->
 <aside class="main-sidebar sidebar-dark-primary elevation-4" >
    <!-- Brand Logo -->
    <a href="<?php echo base_url();?>" class="brand-link" style="background: linear-gradient( 
135deg , rgba(40,29,31,1) 0%, rgb(56 169 175) 100%); color:#FFFFFF; text-align:center;">
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
          <p ><img src="<?php echo base_url()?>assets/logo.jpg" class="img-circle elevation-2" alt="User Image" style="width:45px; height:45px;"></p>
        </div>
      
    </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2" style="overflow:hidden; font-size:14px;">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item has-treeview">
            <a href="<?php echo base_url(); ?>" class="nav-link">
              <i class="fa fa-tachometer-alt dash-icon" ></i>
              <p>
               Main Dashboard
                </i>
              </p>
            </a>
          </li>
       


    <?php if(!empty($this->session->userdata('fullname'))){ ?>
         <li class="nav-item">
            <a href="<?php echo base_url()?>performance" class="nav-link">
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
            <a href="<?php echo base_url()?>dataprep/absenteesm" class="nav-link">
              <i class="fa fa-history dash-icon"></i>
              <p>
                  Absenteesm Report
              </p>
            </a>
          </li>

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
              <li class="nav-item">
                <a href="<?php echo base_url() ?>audit/auditReport" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p class="nav-drop">Facility Audit Report</p>
                </a>
              </li>
        
            </ul>
          </li>
        <?php }?>

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
                <a href="<?php echo base_url(); ?>councils/allied"   class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p class="nav-drop">Allied Health Professionals Council</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url(); ?>councils/medical"   class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p class="nav-drop">Uganda Medical & Dental Practitioners</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url(); ?>councils/pharmacy"   class="nav-link">
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
                <a href="<?php echo base_url(); ?>councils/pharma_society"  class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p class="nav-drop">License pharmaceutical Society</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item">
            <a href="https://hris2.health.go.ug/iHRIS/dev/chwr"   class="nav-link">
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
                <a href="https://hris2.health.go.ug/ahpc-qualify"  target="_blank" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p class="nav-drop">Allied Health Professionals Council</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="https://hris2.health.go.ug/umdpc"  target="_blank" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p class="nav-drop">Uganda Medical & Dental Practitioners</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="http://196.0.11.102/unmc"  target="_blank" class="nav-link">
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
                <a href="https://hris2.health.go.ug/Train"  target="_blank" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p class="nav-drop">In Service Training</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="https://hris2.health.go.ug/iHRIS/releases/4.1/DES"  target="_blank" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p class="nav-drop">DES</p>
                </a>
              </li>
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
                <a href="http://41.173.3.36/ucmb_manage/login"  target="_blank" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p class="nav-drop">Uganda Catholic Med. Bureau</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="http://hris.upmb.co.ug:8080/upmb"  target="_blank" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p class="nav-drop">Uganda Protestant Med. Bureau</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="https://hris.health.go.ug/national"  target="_blank" class="nav-link">
              <i class="fa fa-flag dash-icon"></i>
              <p>
              National Manage
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="https://hris2.health.go.ug/attendance"  target="_blank" class="nav-link">
              <i class="fa fa-clock dash-icon"></i>
              <p>
              Districts Attendance
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="https://attend.health.go.ug"  target="_blank" class="nav-link">
              <i class="fa fa-clock dash-icon"></i>
              <p>
              HRM Attend
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="https://mobileihris.health.go.ug/"  target="_blank" class="nav-link">
              <i class="fa fa-clock dash-icon"></i>
              <p>
              iHRIS Update Tool
              </p>
            </a>
          </li>
        

                
          <?php if ($this->session->userdata('isAdmin')) { ?>



<li class="nav-item has-treeview  <?php echo (($this->uri->segment(2) == "user" ) ? "active" : null) ?>">
    <a href="<?php echo base_url();?>files/file" class="nav-link">

        <i class="fa fa-upload dash-icon"></i><p><?php echo "Upload KPI Data" ?></p>
    </a>
    <?php }?>
<?php if ($this->session->userdata('isAdmin')) { ?>



<li class="nav-item has-treeview   <?php echo (($this->uri->segment(2) == "user" ||  $this->uri->segment(2) == "language" || $this->uri->segment(2) == "backup_restore" || $this->uri->segment(2) == "setting" ) ? "active" : null) ?>">
    <a href="#"  class="nav-link">

        <i class="fa fa-cog dash-icon"></i><p><?php echo display('setting') ?>
        <i class="fas fa-angle-left right"></i>
       </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item <?php echo (($this->uri->segment(2) == "user") ? "active" : null) ?>">
            <a href="#" class="nav-link">
                <p ><?php echo display('user') ?></p>
          
                    <i class="fa fa-angle-left pull-right"></i>
               
            </a>
            <ul class="nav nav-treeview">
                <li><a class="nav-link " 
                        href="<?php echo base_url('dashboard/user/form') ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <?php echo display('add_user') ?></a>
                </li>
                <li><a class="nav-link "
                        href="<?php echo base_url('dashboard/user/index') ?>">
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
