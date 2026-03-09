-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 30, 2026 at 12:34 AM
-- Server version: 9.2.0
-- PHP Version: 8.2.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hrh_dashboard`
--

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `person_id` varchar(50) NOT NULL,
  `job_id` varchar(20) NOT NULL,
  `job_name` varchar(100) NOT NULL,
  `job_classification` varchar(100) NOT NULL,
  `job_category` varchar(100) NOT NULL,
  `cadre_id` varchar(30) NOT NULL,
  `cadre_name` varchar(100) NOT NULL,
  `salary_scale_id` varchar(50) NOT NULL,
  `salary_scale` varchar(50) NOT NULL,
  `district_id` varchar(20) NOT NULL,
  `district_name` varchar(100) NOT NULL,
  `region_id` varchar(50) NOT NULL,
  `region_name` varchar(50) NOT NULL,
  `facility_type_id` varchar(30) NOT NULL,
  `facility_type_name` varchar(100) NOT NULL,
  `facility_id` varchar(50) NOT NULL,
  `facility_name` varchar(100) NOT NULL,
  `gender` varchar(11) NOT NULL,
  `age` int NOT NULL,
  `institution_type` varchar(100) NOT NULL,
  `approved` int NOT NULL,
  `filled` int NOT NULL,
  `dhis_facility_id` varchar(100) NOT NULL,
  `dhis_job_id` varchar(100) NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`person_id`, `job_id`, `job_name`, `job_classification`, `job_category`, `cadre_id`, `cadre_name`, `salary_scale_id`, `salary_scale`, `district_id`, `district_name`, `region_id`, `region_name`, `facility_type_id`, `facility_type_name`, `facility_id`, `facility_name`, `gender`, `age`, `institution_type`, `approved`, `filled`, `dhis_facility_id`, `dhis_job_id`, `last_update`) VALUES
('person|1450688', 'job|32210102', 'Enrolled Nurse', 'Enrolled Nurses', 'Nursing Staff', 'cadre|Nurse', 'Nursing Professionals', 'salary_grade|071', 'U7U', 'district|417', 'IBANDA', 'region|UG-W', 'South Western (Ankole & Kigezi)', 'facility_type|HCIV', 'HCIV', 'facility|1002', 'RUHOKO Health Centre IV', 'Female', 61, 'Municipality, Local Government (LG)', 3, 8, '', 'cYnVlBJBDWC', '2026-01-29 14:07:34'),
('person|1450702', 'job|32210102', 'Enrolled Nurse', 'Enrolled Nurses', 'Nursing Staff', 'cadre|Nurse', 'Nursing Professionals', 'salary_grade|071', 'U7U', 'district|417', 'IBANDA', 'region|UG-W', 'South Western (Ankole & Kigezi)', 'facility_type|HCIV', 'HCIV', 'facility|1002', 'RUHOKO Health Centre IV', 'Male', 56, 'Municipality, Local Government (LG)', 3, 8, '', 'cYnVlBJBDWC', '2026-01-29 14:07:34'),
('person|1450709', 'job|32220102', 'Enrolled Midwife', 'Enrolled Midwives', 'Midwifery Staff', 'cadre|Midwife', 'Midwifery Professionals', 'salary_grade|071', 'U7U', 'district|417', 'IBANDA', 'region|UG-W', 'South Western (Ankole & Kigezi)', 'facility_type|HCIV', 'HCIV', 'facility|1002', 'RUHOKO Health Centre IV', 'Male', 56, 'Municipality, Local Government (LG)', 3, 5, '', 'ETleambbNAO', '2026-01-29 14:07:34'),
('person|1450742', 'job|43210104', 'Stores Assistant (Assistant inventory management Officer)', 'Inventory & Stores Officers', 'Administration Staff', 'cadre|Non_health', 'Non Health Professionals', 'salary_grade|071', 'U7U', 'district|417', 'IBANDA', 'region|UG-W', 'South Western (Ankole & Kigezi)', 'facility_type|HCIV', 'HCIV', 'facility|1002', 'RUHOKO Health Centre IV', 'Female', 56, 'Municipality, Local Government (LG)', 1, 1, '', 'UgNCDeoXxbo', '2026-01-29 14:07:34'),
('person|1450798', 'job|22210104', 'Nursing Officer - Nursing', 'Nursing Officers', 'Nursing Staff', 'cadre|Nurse', 'Nursing Professionals', 'salary_grade|050', 'U5(SC)', 'district|417', 'IBANDA', 'region|UG-W', 'South Western (Ankole & Kigezi)', 'facility_type|HCIV', 'HCIV', 'facility|1002', 'RUHOKO Health Centre IV', 'Male', 56, 'Municipality, Local Government (LG)', 0, 3, '', 'lbhM2ZFiTuj', '2026-01-29 14:07:34'),
('person|1450805', 'job|32210102', 'Enrolled Nurse', 'Enrolled Nurses', 'Nursing Staff', 'cadre|Nurse', 'Nursing Professionals', 'salary_grade|071', 'U7U', 'district|417', 'IBANDA', 'region|UG-W', 'South Western (Ankole & Kigezi)', 'facility_type|HCIV', 'HCIV', 'facility|1002', 'RUHOKO Health Centre IV', 'Male', 56, 'Municipality, Local Government (LG)', 3, 8, '', 'cYnVlBJBDWC', '2026-01-29 14:07:34'),
('person|1450843', 'job|22400702', 'Senior Medical Clinical Officer', 'Clinical Officers', 'Clinical Officers', 'cadre|AHPC', 'Allied Health Professionals', 'salary_grade|040', 'U4(SC)', 'district|417', 'IBANDA', 'region|UG-W', 'South Western (Ankole & Kigezi)', 'facility_type|HCIV', 'HCIV', 'facility|1002', 'RUHOKO Health Centre IV', 'Male', 22, 'Municipality, Local Government (LG)', 0, 1, '', 'B39fQCf4xgN', '2026-01-29 14:07:34'),
('person|1450857', 'job|32210102', 'Enrolled Nurse', 'Enrolled Nurses', 'Nursing Staff', 'cadre|Nurse', 'Nursing Professionals', 'salary_grade|071', 'U7U', 'district|417', 'IBANDA', 'region|UG-W', 'South Western (Ankole & Kigezi)', 'facility_type|HCIV', 'HCIV', 'facility|1002', 'RUHOKO Health Centre IV', 'Female', 56, 'Municipality, Local Government (LG)', 3, 8, '', 'cYnVlBJBDWC', '2026-01-29 14:07:34'),
('person|1450888', 'job|83220101', 'Car Driver', 'Drivers', 'Support Staff', 'cadre|Support', 'Support Staffs', 'salary_grade|081', 'U8U', 'district|417', 'IBANDA', 'region|UG-W', 'South Western (Ankole & Kigezi)', 'facility_type|HCIV', 'HCIV', 'facility|1002', 'RUHOKO Health Centre IV', 'Male', 58, 'Municipality, Local Government (LG)', 1, 1, '', 'NFjSRpf91wi', '2026-01-29 14:07:34'),
('person|1450895', 'job|96110101', 'Porter', 'Porters', 'Support Staff', 'cadre|Support', 'Support Staffs', 'salary_grade|082', 'U8L', 'district|417', 'IBANDA', 'region|UG-W', 'South Western (Ankole & Kigezi)', 'facility_type|HCIV', 'HCIV', 'facility|1002', 'RUHOKO Health Centre IV', 'Female', 56, 'Municipality, Local Government (LG)', 3, 2, '', 'Rv3mDvyNzvi', '2026-01-29 14:07:34'),
('person|1450922', 'job|32120403', 'Medical Laboratory Technician', 'Laboratory Officers & Technicians', 'Laboratory Staff', 'cadre|AHPC', 'Allied Health Professionals', 'salary_grade|050', 'U5(SC)', 'district|417', 'IBANDA', 'region|UG-W', 'South Western (Ankole & Kigezi)', 'facility_type|HCIV', 'HCIV', 'facility|1002', 'RUHOKO Health Centre IV', 'Male', 41, 'Municipality, Local Government (LG)', 1, 2, '', 'oXIGpkW7f31', '2026-01-29 14:07:34'),
('person|1450929', 'job|32220102', 'Enrolled Midwife', 'Enrolled Midwives', 'Midwifery Staff', 'cadre|Midwife', 'Midwifery Professionals', 'salary_grade|071', 'U7U', 'district|417', 'IBANDA', 'region|UG-W', 'South Western (Ankole & Kigezi)', 'facility_type|HCIV', 'HCIV', 'facility|1002', 'RUHOKO Health Centre IV', 'Female', 36, 'Municipality, Local Government (LG)', 3, 5, '', 'ETleambbNAO', '2026-01-29 14:07:34'),
('person|1450936', 'job|32210102', 'Enrolled Nurse', 'Enrolled Nurses', 'Nursing Staff', 'cadre|Nurse', 'Nursing Professionals', 'salary_grade|071', 'U7U', 'district|417', 'IBANDA', 'region|UG-W', 'South Western (Ankole & Kigezi)', 'facility_type|HCIV', 'HCIV', 'facility|1002', 'RUHOKO Health Centre IV', 'Female', 56, 'Municipality, Local Government (LG)', 3, 8, '', 'cYnVlBJBDWC', '2026-01-29 14:07:34'),
('person|1450943', 'job|32210102', 'Enrolled Nurse', 'Enrolled Nurses', 'Nursing Staff', 'cadre|Nurse', 'Nursing Professionals', 'salary_grade|071', 'U7U', 'district|417', 'IBANDA', 'region|UG-W', 'South Western (Ankole & Kigezi)', 'facility_type|HCIV', 'HCIV', 'facility|1002', 'RUHOKO Health Centre IV', 'Female', 44, 'Municipality, Local Government (LG)', 3, 8, '', 'cYnVlBJBDWC', '2026-01-29 14:07:34'),
('person|1450950', 'job|53210202', 'Theatre Assistant', 'Theatre Officers', 'Theatre Staff', 'cadre|AHPC', 'Allied Health Professionals', 'salary_grade|061', 'U6U', 'district|417', 'IBANDA', 'region|UG-W', 'South Western (Ankole & Kigezi)', 'facility_type|HCIV', 'HCIV', 'facility|1002', 'RUHOKO Health Centre IV', 'Female', 56, 'Municipality, Local Government (LG)', 2, 1, '', 'woAknbdWq8N', '2026-01-29 14:07:34'),
('person|1450986', 'job|33130104', 'Accounts Assistant', 'Accountants & Credit Officers', 'Administration Staff', 'cadre|Non_health', 'Non Health Professionals', 'salary_grade|071', 'U7U', 'district|417', 'IBANDA', 'region|UG-W', 'South Western (Ankole & Kigezi)', 'facility_type|HCIV', 'HCIV', 'facility|1002', 'RUHOKO Health Centre IV', 'Male', 43, 'Municipality, Local Government (LG)', 1, 1, '', 'mQxy282WLWQ', '2026-01-29 14:07:34'),
('person|1451015', 'job|32520304', 'Health Information Assistant', 'Health Information Officers', 'Data & Records Staff', 'cadre|Non_health', 'Non Health Professionals', 'salary_grade|071', 'U7U', 'district|417', 'IBANDA', 'region|UG-W', 'South Western (Ankole & Kigezi)', 'facility_type|HCIV', 'HCIV', 'facility|1002', 'RUHOKO Health Centre IV', 'Female', 56, 'Municipality, Local Government (LG)', 1, 1, '', 'lZGNvL7ki6v', '2026-01-29 14:07:34'),
('person|1451029', 'job|32220102', 'Enrolled Midwife', 'Enrolled Midwives', 'Midwifery Staff', 'cadre|Midwife', 'Midwifery Professionals', 'salary_grade|071', 'U7U', 'district|417', 'IBANDA', 'region|UG-W', 'South Western (Ankole & Kigezi)', 'facility_type|HCIV', 'HCIV', 'facility|1002', 'RUHOKO Health Centre IV', 'Female', 56, 'Municipality, Local Government (LG)', 3, 5, '', 'ETleambbNAO', '2026-01-29 14:07:34'),
('person|1451036', 'job|22400103', 'Ophthalmic Clinical Officer', 'Clinical Officers', 'Ophthalmology Staff', 'cadre|AHPC', 'Allied Health Professionals', 'salary_grade|050', 'U5(SC)', 'district|417', 'IBANDA', 'region|UG-W', 'South Western (Ankole & Kigezi)', 'facility_type|HCIV', 'HCIV', 'facility|1002', 'RUHOKO Health Centre IV', 'Male', 56, 'Municipality, Local Government (LG)', 1, 1, '', 'FpyNqtTMb0w', '2026-01-29 14:07:34'),
('person|1451071', 'job|96110101', 'Porter', 'Porters', 'Support Staff', 'cadre|Support', 'Support Staffs', 'salary_grade|082', 'U8L', 'district|417', 'IBANDA', 'region|UG-W', 'South Western (Ankole & Kigezi)', 'facility_type|HCIV', 'HCIV', 'facility|1002', 'RUHOKO Health Centre IV', 'Male', 36, 'Municipality, Local Government (LG)', 3, 2, '', 'Rv3mDvyNzvi', '2026-01-29 14:07:34'),
('person|1451118', 'job|32220102', 'Enrolled Midwife', 'Enrolled Midwives', 'Midwifery Staff', 'cadre|Midwife', 'Midwifery Professionals', 'salary_grade|071', 'U7U', 'district|417', 'IBANDA', 'region|UG-W', 'South Western (Ankole & Kigezi)', 'facility_type|HCIV', 'HCIV', 'facility|1002', 'RUHOKO Health Centre IV', 'Female', 56, 'Municipality, Local Government (LG)', 3, 5, '', 'ETleambbNAO', '2026-01-29 14:07:34'),
('person|1451133', 'job|75440103', 'Vector Control Officer', 'Vector Control Officers', 'Public Health Staff', 'cadre|AHPC', 'Allied Health Professionals', 'salary_grade|040', 'U4(SC)', 'district|417', 'IBANDA', 'region|UG-W', 'South Western (Ankole & Kigezi)', 'facility_type|HCIV', 'HCIV', 'facility|1002', 'RUHOKO Health Centre IV', 'Female', 56, 'Municipality, Local Government (LG)', 0, 1, '', '', '2026-01-29 14:07:34'),
('person|1451140', 'job|91120102', 'Theatre Attendant', 'Attendants', 'Support Staff', 'cadre|Support', 'Support Staffs', 'salary_grade|082', 'U8L', 'district|417', 'IBANDA', 'region|UG-W', 'South Western (Ankole & Kigezi)', 'facility_type|HCIV', 'HCIV', 'facility|1002', 'RUHOKO Health Centre IV', 'Male', 46, 'Municipality, Local Government (LG)', 0, 1, '', 'rFuHI0EefGU', '2026-01-29 14:07:34'),
('person|1451162', 'job|22210104', 'Nursing Officer - Nursing', 'Nursing Officers', 'Nursing Staff', 'cadre|Nurse', 'Nursing Professionals', 'salary_grade|050', 'U5(SC)', 'district|417', 'IBANDA', 'region|UG-W', 'South Western (Ankole & Kigezi)', 'facility_type|HCIV', 'HCIV', 'facility|1002', 'RUHOKO Health Centre IV', 'Female', 46, 'Municipality, Local Government (LG)', 0, 3, '', 'lbhM2ZFiTuj', '2026-01-29 14:07:34'),
('person|1451177', 'job|32120305', 'Medical Laboratory Assistant', 'Laboratory Officers & Technicians', 'Laboratory Staff', 'cadre|AHPC', 'Allied Health Professionals', 'salary_grade|071', 'U7U', 'district|417', 'IBANDA', 'region|UG-W', 'South Western (Ankole & Kigezi)', 'facility_type|HCIV', 'HCIV', 'facility|1002', 'RUHOKO Health Centre IV', 'Female', 56, 'Municipality, Local Government (LG)', 1, 1, '', 'x1xXrkziYPx', '2026-01-29 14:07:34'),
('person|1451203', 'job|32210102', 'Enrolled Nurse', 'Enrolled Nurses', 'Nursing Staff', 'cadre|Nurse', 'Nursing Professionals', 'salary_grade|071', 'U7U', 'district|417', 'IBANDA', 'region|UG-W', 'South Western (Ankole & Kigezi)', 'facility_type|HCIV', 'HCIV', 'facility|1002', 'RUHOKO Health Centre IV', 'Female', 56, 'Municipality, Local Government (LG)', 3, 8, '', 'cYnVlBJBDWC', '2026-01-29 14:07:34'),
('person|1451210', 'job|32120403', 'Medical Laboratory Technician', 'Laboratory Officers & Technicians', 'Laboratory Staff', 'cadre|AHPC', 'Allied Health Professionals', 'salary_grade|050', 'U5(SC)', 'district|417', 'IBANDA', 'region|UG-W', 'South Western (Ankole & Kigezi)', 'facility_type|HCIV', 'HCIV', 'facility|1002', 'RUHOKO Health Centre IV', 'Male', 56, 'Municipality, Local Government (LG)', 1, 2, '', 'oXIGpkW7f31', '2026-01-29 14:07:34'),
('person|1451231', 'job|22210104', 'Nursing Officer - Nursing', 'Nursing Officers', 'Nursing Staff', 'cadre|Nurse', 'Nursing Professionals', 'salary_grade|050', 'U5(SC)', 'district|417', 'IBANDA', 'region|UG-W', 'South Western (Ankole & Kigezi)', 'facility_type|HCIV', 'HCIV', 'facility|1002', 'RUHOKO Health Centre IV', 'Female', 56, 'Municipality, Local Government (LG)', 0, 3, '', 'lbhM2ZFiTuj', '2026-01-29 14:07:34'),
('person|1451243', 'job|32220102', 'Enrolled Midwife', 'Enrolled Midwives', 'Midwifery Staff', 'cadre|Midwife', 'Midwifery Professionals', 'salary_grade|071', 'U7U', 'district|417', 'IBANDA', 'region|UG-W', 'South Western (Ankole & Kigezi)', 'facility_type|HCIV', 'HCIV', 'facility|1002', 'RUHOKO Health Centre IV', 'Female', 56, 'Municipality, Local Government (LG)', 3, 5, '', 'ETleambbNAO', '2026-01-29 14:07:34'),
('person|1451304', 'job|54140103', 'Askari (Security Guard)', 'Security Officers & Guards', 'Security Staff', 'cadre|Support', 'Support Staffs', 'salary_grade|082', 'U8L', 'district|417', 'IBANDA', 'region|UG-W', 'South Western (Ankole & Kigezi)', 'facility_type|HCIV', 'HCIV', 'facility|1002', 'RUHOKO Health Centre IV', 'Male', 39, 'Municipality, Local Government (LG)', 3, 2, '', 'wU4FCUYGCO0', '2026-01-29 14:07:34'),
('person|1451311', 'job|54140103', 'Askari (Security Guard)', 'Security Officers & Guards', 'Security Staff', 'cadre|Support', 'Support Staffs', 'salary_grade|082', 'U8L', 'district|417', 'IBANDA', 'region|UG-W', 'South Western (Ankole & Kigezi)', 'facility_type|HCIV', 'HCIV', 'facility|1002', 'RUHOKO Health Centre IV', 'Male', 48, 'Municipality, Local Government (LG)', 3, 2, '', 'wU4FCUYGCO0', '2026-01-29 14:07:34'),
('person|1451332', 'job|32210102', 'Enrolled Nurse', 'Enrolled Nurses', 'Nursing Staff', 'cadre|Nurse', 'Nursing Professionals', 'salary_grade|071', 'U7U', 'district|417', 'IBANDA', 'region|UG-W', 'South Western (Ankole & Kigezi)', 'facility_type|HCIV', 'HCIV', 'facility|1002', 'RUHOKO Health Centre IV', 'Female', 40, 'Municipality, Local Government (LG)', 3, 8, '', 'cYnVlBJBDWC', '2026-01-29 14:07:34'),
('person|1451354', 'job|32570103', 'Health Inspector', 'Health Inspectors', 'Public Health Staff', 'cadre|AHPC', 'Allied Health Professionals', 'salary_grade|050', 'U5(SC)', 'district|417', 'IBANDA', 'region|UG-W', 'South Western (Ankole & Kigezi)', 'facility_type|HCIV', 'HCIV', 'facility|1002', 'RUHOKO Health Centre IV', 'Male', 56, 'Municipality, Local Government (LG)', 2, 1, '', 'VwEgiWGqKqf', '2026-01-29 14:07:34'),
('person|56369', 'job|32510103', 'Public Health Dental Officer', 'Dental Officers', 'Dental Staff', 'cadre|AHPC', 'Allied Health Professionals', 'salary_grade|050', 'U5(SC)', 'district|417', 'IBANDA', 'region|UG-W', 'South Western (Ankole & Kigezi)', 'facility_type|HCIV', 'HCIV', 'facility|1002', 'RUHOKO Health Centre IV', 'Female', 44, 'Municipality, Local Government (LG)', 1, 1, '', 'NKDffCN6o2j', '2026-01-29 14:07:34');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
