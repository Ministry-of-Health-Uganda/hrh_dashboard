<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2023-03-16 14:16:45 --> Could not find the specified $config['composer_autoload'] path: C:\wamp64\www\hrh_dashboard-1\vendor/autoload.php
ERROR - 2023-03-16 14:16:46 --> Severity: Warning --> mysqli::real_connect(): (HY000/1130): Host '_gateway' is not allowed to connect to this MariaDB server C:\wamp64\www\hrh_dashboard-1\system\database\drivers\mysqli\mysqli_driver.php 203
ERROR - 2023-03-16 14:16:46 --> Unable to connect to the database
ERROR - 2023-03-16 14:16:47 --> Severity: Warning --> mysqli::real_connect(): (HY000/1130): Host '_gateway' is not allowed to connect to this MariaDB server C:\wamp64\www\hrh_dashboard-1\system\database\drivers\mysqli\mysqli_driver.php 203
ERROR - 2023-03-16 14:16:47 --> Severity: Warning --> ini_set(): A session is active. You cannot change the session module's ini settings at this time C:\wamp64\www\hrh_dashboard-1\system\libraries\Session\Session_driver.php 188
ERROR - 2023-03-16 14:16:47 --> Severity: Warning --> session_start(): Failed to initialize storage module: user (path: c:/wamp64/tmp) C:\wamp64\www\hrh_dashboard-1\system\libraries\Session\Session.php 143
ERROR - 2023-03-16 14:16:47 --> Severity: Warning --> mysqli::real_connect(): (HY000/1130): Host '_gateway' is not allowed to connect to this MariaDB server C:\wamp64\www\hrh_dashboard-1\system\database\drivers\mysqli\mysqli_driver.php 203
ERROR - 2023-03-16 14:16:47 --> Unable to connect to the database
ERROR - 2023-03-16 14:16:47 --> Query error: Host '_gateway' is not allowed to connect to this MariaDB server - Invalid query: SELECT *
FROM `setting`
ERROR - 2023-03-16 14:16:47 --> Severity: error --> Exception: Call to a member function row() on bool C:\wamp64\www\hrh_dashboard-1\application\modules\dashboard\models\Home_model.php 15
