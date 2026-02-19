<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Africa/Kampala');
require_once('includes/header.php');
// No navtop or sidenav so the page is suitable for iframe embedding
?>
<div class="content-wrapper" style="margin-left: 0;">
  <div class="content-top-spacer" style="padding-top: 0.75rem;"></div>
  <section class="content" style="font-size: 0.875rem;">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card shadow-sm">
            <div class="card-body">
              <?php $this->load->view($module . '/' . $page); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<?php require_once('includes/footer.php'); ?>
