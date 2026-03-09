<?php 

/*
Manual fill
INSERT into `attendance_rate` (entry_id,facility_id,month,year,staff_count,facility_name) SELECT CONCAT(facility_id,2020,01), facility_id, 2020, 01, count(staff.person_id),facility_name FROM staff GROUP BY staff.facility_id/*

?>