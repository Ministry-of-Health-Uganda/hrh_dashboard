<form class="form-horizontal row searchForm" method="post" action="<?php echo base_url(); ?>audit/printfacAudit?districts=<?php echo isset($district_param) ? rawurlencode($district_param) : ''; ?>&display=<?php echo isset($display) ? rawurlencode($display) : 'ihris'; ?>">
  <input type="hidden" name="getPdf" id="print" value="0" />
  <?php
  $filters = isset($filters) ? $filters : (object) array('institutions' => array(), 'jobs' => array(), 'job_categories' => array(), 'job_classifications' => array(), 'job_cadres' => array(), 'ownership' => array());
  $search = isset($search) ? $search : (object) array();
  ?>
  <div class="form-group col-md-3">
    <label>Institution Type</label>
    <select class="select form-control select2" name="institution">
      <option value="">All</option>
      <?php if (!empty($filters->institutions)) foreach ($filters->institutions as $inst): ?>
        <option <?php echo (isset($search->institution) && $search->institution == $inst->institution_type) ? 'selected' : ''; ?> value="<?php echo htmlspecialchars($inst->institution_type); ?>"><?php echo htmlspecialchars($inst->institution_type); ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="form-group col-md-3">
    <label>Job Name</label>
    <select class="select form-control" name="job">
      <option value="">All</option>
      <?php if (!empty($filters->jobs)) foreach ($filters->jobs as $job): ?>
        <option <?php echo (isset($search->job) && $search->job == $job->job) ? 'selected' : ''; ?> value="<?php echo htmlspecialchars($job->job); ?>"><?php echo htmlspecialchars($job->job); ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="form-group col-md-3">
    <label>Job Category</label>
    <select class="select form-control select2" name="job_category">
      <option value="">All</option>
      <?php if (!empty($filters->job_categories)) foreach ($filters->job_categories as $jobCat): ?>
        <option <?php echo (isset($search->job_category) && $search->job_category == $jobCat->job_category) ? 'selected' : ''; ?> value="<?php echo htmlspecialchars($jobCat->job_category); ?>"><?php echo htmlspecialchars($jobCat->job_category); ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="form-group col-md-3">
    <label>Job Classification</label>
    <select class="select form-control select2" name="job_class">
      <option value="">All</option>
      <?php if (!empty($filters->job_classifications)) foreach ($filters->job_classifications as $jobClass): ?>
        <option <?php echo (isset($search->job_class) && $search->job_class == $jobClass->job_class) ? 'selected' : ''; ?> value="<?php echo htmlspecialchars($jobClass->job_class); ?>"><?php echo htmlspecialchars($jobClass->job_class); ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="form-group col-md-3">
    <label>Job Cadre</label>
    <select class="select form-control select2" name="cadre">
      <option value="">All</option>
      <?php if (!empty($filters->job_cadres)) foreach ($filters->job_cadres as $cadre): ?>
        <option <?php echo (isset($search->cadre) && $search->cadre == $cadre->cadre_name) ? 'selected' : ''; ?> value="<?php echo htmlspecialchars($cadre->cadre_name); ?>"><?php echo htmlspecialchars($cadre->cadre_name); ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="form-group col-md-3">
    <label>Ownership</label>
    <select class="select form-control" name="ownership">
      <option value="">All</option>
      <?php if (!empty($filters->ownership)) foreach ($filters->ownership as $owner): ?>
        <option <?php echo (isset($search->ownership) && $search->ownership == $owner->ownership) ? 'selected' : ''; ?> value="<?php echo htmlspecialchars($owner->ownership); ?>"><?php echo htmlspecialchars($owner->ownership); ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="form-group col-md-3">
    <label>Aggregate By</label>
    <select class="select form-control" name="aggregate">
      <option value="job_name" <?php echo (isset($search->aggregate) && $search->aggregate === 'cadre_name') ? '' : 'selected'; ?>>Job</option>
      <option value="cadre_name" <?php echo (isset($search->aggregate) && $search->aggregate === 'cadre_name') ? 'selected' : ''; ?>>Cadre</option>
    </select>
  </div>

  <div class="form-group col-12 d-flex justify-content-end align-items-center mt-2">
    <button type="reset" class="btn btn-default btn-sm mr-2">Reset Filters</button>
    <button type="submit" class="btn btn-success btn-sm">Apply Filter</button>
  </div>
</form>
