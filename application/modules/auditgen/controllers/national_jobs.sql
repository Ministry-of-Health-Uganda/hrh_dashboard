-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 30, 2026 at 12:35 AM
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
-- Table structure for table `national_jobs`
--

CREATE TABLE `national_jobs` (
  `month` varchar(100) DEFAULT NULL,
  `year` varchar(100) DEFAULT NULL,
  `facility_id` varchar(100) NOT NULL,
  `dhis_facility_id` varchar(100) NOT NULL,
  `facility_name` varchar(200) NOT NULL,
  `facility_type_name` varchar(100) NOT NULL,
  `region_name` varchar(200) NOT NULL,
  `institution_type` varchar(100) NOT NULL,
  `ownership` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT 'Public',
  `district_name` varchar(50) NOT NULL,
  `job_id` varchar(100) NOT NULL,
  `dhis_job_id` varchar(100) NOT NULL,
  `job_name` varchar(200) NOT NULL,
  `job_category` varchar(100) NOT NULL,
  `job_classification` varchar(100) NOT NULL,
  `cadre_name` varchar(50) NOT NULL,
  `salary_scale` varchar(10) NOT NULL,
  `approved` int NOT NULL,
  `male` int NOT NULL,
  `female` int NOT NULL,
  `total` int NOT NULL,
  `excess` int NOT NULL DEFAULT '0',
  `vacant` int NOT NULL DEFAULT '0',
  `date_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `national_jobs`
--

INSERT INTO `national_jobs` (`month`, `year`, `facility_id`, `dhis_facility_id`, `facility_name`, `facility_type_name`, `region_name`, `institution_type`, `ownership`, `district_name`, `job_id`, `dhis_job_id`, `job_name`, `job_category`, `job_classification`, `cadre_name`, `salary_scale`, `approved`, `male`, `female`, `total`, `excess`, `vacant`, `date_time`) VALUES
('January', '2026', 'facility|1002', '', 'RUHOKO Health Centre IV', 'HCIV', 'South Western (Ankole & Kigezi)', 'Municipality, Local Government (LG)', 'Public', 'IBANDA', 'job|33130104', 'mQxy282WLWQ', 'Accounts Assistant', 'Accountants & Credit Officers', 'Administration Staff', 'Non Health Professionals', 'U7U', 1, 1, 0, 1, 0, 0, '2026-01-30 03:03:57'),
('January', '2026', 'facility|1002', '', 'RUHOKO Health Centre IV', 'HCIV', 'South Western (Ankole & Kigezi)', 'Municipality, Local Government (LG)', 'Public', 'IBANDA', 'job|22400304', 'k38zf9oEGu2', 'Anaesthetic Assistant', 'Anaesthesia Staff', 'Anaesthetic Officers', 'Allied Health Professionals', 'U7U', 2, 0, 0, 0, 0, 0, '2026-01-30 03:03:57'),
('January', '2026', 'facility|1002', '', 'RUHOKO Health Centre IV', 'HCIV', 'South Western (Ankole & Kigezi)', 'Municipality, Local Government (LG)', 'Public', 'IBANDA', 'job|22400303', '', 'Anaesthetic Officer', 'Anaesthesia Staff', 'Anaesthetic Officers', 'Allied Health Professionals', 'U5(SC)', 1, 0, 0, 0, 0, 0, '2026-01-30 03:03:57'),
('January', '2026', 'facility|1002', '', 'RUHOKO Health Centre IV', 'HCIV', 'South Western (Ankole & Kigezi)', 'Municipality, Local Government (LG)', 'Public', 'IBANDA', 'job|54140103', 'wU4FCUYGCO0', 'Askari (Security Guard)', 'Security Officers & Guards', 'Security Staff', 'Support Staffs', 'U8L', 3, 2, 0, 2, 0, 0, '2026-01-30 03:03:57'),
('January', '2026', 'facility|1002', '', 'RUHOKO Health Centre IV', 'HCIV', 'South Western (Ankole & Kigezi)', 'Municipality, Local Government (LG)', 'Public', 'IBANDA', 'job|61230104', '', 'Assistant Entomological Officer', 'Public Health Staff', 'Entomological Officers & Assistants', 'Allied Health Professionals', 'U5(SC)', 1, 0, 0, 0, 0, 0, '2026-01-30 03:03:57'),
('January', '2026', 'facility|1002', '', 'RUHOKO Health Centre IV', 'HCIV', 'South Western (Ankole & Kigezi)', 'Municipality, Local Government (LG)', 'Public', 'IBANDA', 'job|32530106', 'SxxYDmrIz7x', 'Assistant Health Educator', 'Public Health Staff', 'Health Educators', 'Allied Health Professionals', 'U5(SC)', 1, 0, 0, 0, 0, 0, '2026-01-30 03:03:57'),
('January', '2026', 'facility|1002', '', 'RUHOKO Health Centre IV', 'HCIV', 'South Western (Ankole & Kigezi)', 'Municipality, Local Government (LG)', 'Public', 'IBANDA', 'job|96290132', '', 'Assistant Nursing Officer - Midwifery', 'Midwifery Staff', 'Midwifery Officers', 'Midwifery Professionals', 'U5(SC)', 1, 0, 0, 0, 0, 0, '2026-01-30 03:03:57'),
('January', '2026', 'facility|1002', '', 'RUHOKO Health Centre IV', 'HCIV', 'South Western (Ankole & Kigezi)', 'Municipality, Local Government (LG)', 'Public', 'IBANDA', 'job|96290139', 'uyMeKyp9wm7', 'Assistant Nursing Officer - Nursing', 'Nursing Staff', 'Nursing Officers', 'Nursing Professionals', 'U5(SC)', 1, 0, 0, 0, 0, 0, '2026-01-30 03:03:57'),
('January', '2026', 'facility|1002', '', 'RUHOKO Health Centre IV', 'HCIV', 'South Western (Ankole & Kigezi)', 'Municipality, Local Government (LG)', 'Public', 'IBANDA', 'job|22210304', '', 'Assistant Nursing Officer - Psychiatry', 'Nursing Staff', 'Nursing Officers', 'Nursing Professionals', 'U5(SC)', 1, 0, 0, 0, 0, 0, '2026-01-30 03:03:57'),
('January', '2026', 'facility|1002', '', 'RUHOKO Health Centre IV', 'HCIV', 'South Western (Ankole & Kigezi)', 'Municipality, Local Government (LG)', 'Public', 'IBANDA', 'job|83220101', 'NFjSRpf91wi', 'Car Driver', 'Drivers', 'Support Staff', 'Support Staffs', 'U8U', 1, 1, 0, 1, 0, 0, '2026-01-30 03:03:57'),
('January', '2026', 'facility|1002', '', 'RUHOKO Health Centre IV', 'HCIV', 'South Western (Ankole & Kigezi)', 'Municipality, Local Government (LG)', 'Public', 'IBANDA', 'job|71270104', 'Cxi40IBqjEt', 'Cold Chain Assistant', 'Engineering Staff', 'Cold Chain Officers & Technicians', 'Allied Health Professionals', 'U7L', 1, 0, 0, 0, 0, 0, '2026-01-30 03:03:57'),
('January', '2026', 'facility|1002', '', 'RUHOKO Health Centre IV', 'HCIV', 'South Western (Ankole & Kigezi)', 'Municipality, Local Government (LG)', 'Public', 'IBANDA', 'job|22620203', 'wZfskUHqdTh', 'Dispenser', 'Dispensers', 'Dispensers', 'Allied Health Professionals', 'U5(SC)', 1, 0, 0, 0, 0, 0, '2026-01-30 03:03:57'),
('January', '2026', 'facility|1002', '', 'RUHOKO Health Centre IV', 'HCIV', 'South Western (Ankole & Kigezi)', 'Municipality, Local Government (LG)', 'Public', 'IBANDA', 'job|32220102', 'ETleambbNAO', 'Enrolled Midwife', 'Enrolled Midwives', 'Midwifery Staff', 'Midwifery Professionals', 'U7U', 3, 5, 0, 5, 0, 0, '2026-01-30 03:03:57'),
('January', '2026', 'facility|1002', '', 'RUHOKO Health Centre IV', 'HCIV', 'South Western (Ankole & Kigezi)', 'Municipality, Local Government (LG)', 'Public', 'IBANDA', 'job|32210102', 'cYnVlBJBDWC', 'Enrolled Nurse', 'Enrolled Nurses', 'Nursing Staff', 'Nursing Professionals', 'U7U', 3, 0, 8, 8, 0, 0, '2026-01-30 03:03:57'),
('January', '2026', 'facility|1002', '', 'RUHOKO Health Centre IV', 'HCIV', 'South Western (Ankole & Kigezi)', 'Municipality, Local Government (LG)', 'Public', 'IBANDA', 'job|32210202', 'jLPWW3E9wzq', 'Enrolled Nurse - Psychiatry', 'Nursing Staff', 'Enrolled Nurses', 'Nursing Professionals', 'U7U', 1, 0, 0, 0, 0, 0, '2026-01-30 03:03:57'),
('January', '2026', 'facility|1002', '', 'RUHOKO Health Centre IV', 'HCIV', 'South Western (Ankole & Kigezi)', 'Municipality, Local Government (LG)', 'Public', 'IBANDA', 'job|32530503', 'yWaNprObMhT', 'Health Assistant', 'Public Health Staff', 'Health Assistants', 'Allied Health Professionals', 'U7U', 1, 0, 0, 0, 0, 0, '2026-01-30 03:03:57'),
('January', '2026', 'facility|1002', '', 'RUHOKO Health Centre IV', 'HCIV', 'South Western (Ankole & Kigezi)', 'Municipality, Local Government (LG)', 'Public', 'IBANDA', 'job|32520304', 'lZGNvL7ki6v', 'Health Information Assistant', 'Health Information Officers', 'Data & Records Staff', 'Non Health Professionals', 'U7U', 1, 0, 1, 1, 0, 0, '2026-01-30 03:03:57'),
('January', '2026', 'facility|1002', '', 'RUHOKO Health Centre IV', 'HCIV', 'South Western (Ankole & Kigezi)', 'Municipality, Local Government (LG)', 'Public', 'IBANDA', 'job|32570103', 'VwEgiWGqKqf', 'Health Inspector', 'Health Inspectors', 'Public Health Staff', 'Allied Health Professionals', 'U5(SC)', 2, 1, 0, 1, 0, 0, '2026-01-30 03:03:57'),
('January', '2026', 'facility|1002', '', 'RUHOKO Health Centre IV', 'HCIV', 'South Western (Ankole & Kigezi)', 'Municipality, Local Government (LG)', 'Public', 'IBANDA', 'job|22400703', 'LXDBi4uO3TM', 'Medical Clinical Officer', 'Clinical Officers', 'Clinical Officers', 'Allied Health Professionals', 'U5(SC)', 2, 0, 0, 0, 0, 0, '2026-01-30 03:03:57'),
('January', '2026', 'facility|1002', '', 'RUHOKO Health Centre IV', 'HCIV', 'South Western (Ankole & Kigezi)', 'Municipality, Local Government (LG)', 'Public', 'IBANDA', 'job|32120305', 'x1xXrkziYPx', 'Medical Laboratory Assistant', 'Laboratory Officers & Technicians', 'Laboratory Staff', 'Allied Health Professionals', 'U7U', 1, 0, 1, 1, 0, 0, '2026-01-30 03:03:57'),
('January', '2026', 'facility|1002', '', 'RUHOKO Health Centre IV', 'HCIV', 'South Western (Ankole & Kigezi)', 'Municipality, Local Government (LG)', 'Public', 'IBANDA', 'job|32120403', 'oXIGpkW7f31', 'Medical Laboratory Technician', 'Laboratory Officers & Technicians', 'Laboratory Staff', 'Allied Health Professionals', 'U5(SC)', 1, 2, 0, 2, 0, 0, '2026-01-30 03:03:57'),
('January', '2026', 'facility|1002', '', 'RUHOKO Health Centre IV', 'HCIV', 'South Western (Ankole & Kigezi)', 'Municipality, Local Government (LG)', 'Public', 'IBANDA', 'job|22110103', 'zw4n5C0SX3B', 'Medical Officer', 'Doctors', 'Medical Officers', 'Medical Professionals', 'U4(SC)', 3, 0, 0, 0, 0, 0, '2026-01-30 03:03:57'),
('January', '2026', 'facility|1002', '', 'RUHOKO Health Centre IV', 'HCIV', 'South Western (Ankole & Kigezi)', 'Municipality, Local Government (LG)', 'Public', 'IBANDA', 'job|53210101', 's11EeOr3Syh', 'Nursing Assistant', 'Nursing Staff', 'Nursing Assistants', 'Nursing Professionals', 'U8U', 5, 0, 0, 0, 0, 0, '2026-01-30 03:03:57'),
('January', '2026', 'facility|1002', '', 'RUHOKO Health Centre IV', 'HCIV', 'South Western (Ankole & Kigezi)', 'Municipality, Local Government (LG)', 'Public', 'IBANDA', 'job|22210104', 'lbhM2ZFiTuj', 'Nursing Officer - Nursing', 'Nursing Officers', 'Nursing Staff', 'Nursing Professionals', 'U5(SC)', 1, 3, 0, 3, 0, 0, '2026-01-30 03:03:57'),
('January', '2026', 'facility|1002', '', 'RUHOKO Health Centre IV', 'HCIV', 'South Western (Ankole & Kigezi)', 'Municipality, Local Government (LG)', 'Public', 'IBANDA', 'job|41310103', 'XtoLoE5cEyj', 'Office Typist', 'Administration Staff', 'Office Typists', 'Support Staffs', 'U7L', 1, 0, 0, 0, 0, 0, '2026-01-30 03:03:57'),
('January', '2026', 'facility|1002', '', 'RUHOKO Health Centre IV', 'HCIV', 'South Western (Ankole & Kigezi)', 'Municipality, Local Government (LG)', 'Public', 'IBANDA', 'job|22400103', 'FpyNqtTMb0w', 'Ophthalmic Clinical Officer', 'Clinical Officers', 'Ophthalmology Staff', 'Allied Health Professionals', 'U5(SC)', 1, 1, 0, 1, 0, 0, '2026-01-30 03:03:57'),
('January', '2026', 'facility|1002', '', 'RUHOKO Health Centre IV', 'HCIV', 'South Western (Ankole & Kigezi)', 'Municipality, Local Government (LG)', 'Public', 'IBANDA', 'job|96110101', 'Rv3mDvyNzvi', 'Porter', 'Porters', 'Support Staff', 'Support Staffs', 'U8L', 3, 0, 2, 2, 0, 0, '2026-01-30 03:03:57'),
('January', '2026', 'facility|1002', '', 'RUHOKO Health Centre IV', 'HCIV', 'South Western (Ankole & Kigezi)', 'Municipality, Local Government (LG)', 'Public', 'IBANDA', 'job|32510103', 'NKDffCN6o2j', 'Public Health Dental Officer', 'Dental Officers', 'Dental Staff', 'Allied Health Professionals', 'U5(SC)', 1, 0, 1, 1, 0, 0, '2026-01-30 03:03:57'),
('January', '2026', 'facility|1002', '', 'RUHOKO Health Centre IV', 'HCIV', 'South Western (Ankole & Kigezi)', 'Municipality, Local Government (LG)', 'Public', 'IBANDA', 'job|22210105', '', 'Senior Assistant Nursing Officer', 'Nursing Staff', 'Nursing Officers', 'Nursing Professionals', 'U4(SC)', 1, 0, 0, 0, 0, 0, '2026-01-30 03:03:57'),
('January', '2026', 'facility|1002', '', 'RUHOKO Health Centre IV', 'HCIV', 'South Western (Ankole & Kigezi)', 'Municipality, Local Government (LG)', 'Public', 'IBANDA', 'job|22110102', 'YIkhtlR1CVj', 'Senior Medical Officer', 'Doctors', 'Medical Officers', 'Medical Professionals', 'U3(SC)', 1, 0, 0, 0, 0, 0, '2026-01-30 03:03:57'),
('January', '2026', 'facility|1002', '', 'RUHOKO Health Centre IV', 'HCIV', 'South Western (Ankole & Kigezi)', 'Municipality, Local Government (LG)', 'Public', 'IBANDA', 'job|22210103', 'bhSV7g7H1oI', 'Senior Nursing Officer', 'Nursing Staff', 'Nursing Officers', 'Nursing Professionals', 'U4(SC)', 1, 0, 0, 0, 0, 0, '2026-01-30 03:03:57'),
('January', '2026', 'facility|1002', '', 'RUHOKO Health Centre IV', 'HCIV', 'South Western (Ankole & Kigezi)', 'Municipality, Local Government (LG)', 'Public', 'IBANDA', 'job|43210104', 'UgNCDeoXxbo', 'Stores Assistant (Assistant inventory management Officer)', 'Inventory & Stores Officers', 'Administration Staff', 'Non Health Professionals', 'U7U', 1, 0, 1, 1, 0, 0, '2026-01-30 03:03:57'),
('January', '2026', 'facility|1002', '', 'RUHOKO Health Centre IV', 'HCIV', 'South Western (Ankole & Kigezi)', 'Municipality, Local Government (LG)', 'Public', 'IBANDA', 'job|53210202', 'woAknbdWq8N', 'Theatre Assistant', 'Theatre Officers', 'Theatre Staff', 'Allied Health Professionals', 'U6U', 2, 0, 1, 1, 0, 0, '2026-01-30 03:03:57'),
('January', '2026', 'facility|1002', '', 'RUHOKO Health Centre IV', 'HCIV', 'South Western (Ankole & Kigezi)', 'Municipality, Local Government (LG)', 'Public', 'IBANDA', 'job|96290948', '', 'Assistant Public Health Nurse', '', 'Public Health Nurses', 'Allied Health Professionals', 'U5(SC)', 1, 0, 0, 0, 0, 0, '2026-01-30 03:03:57'),
('January', '2026', 'facility|1002', '', 'RUHOKO Health Centre IV', 'HCIV', 'South Western (Ankole & Kigezi)', 'Municipality, Local Government (LG)', 'Public', 'IBANDA', 'job|22400702', 'B39fQCf4xgN', 'Senior Medical Clinical Officer', 'Clinical Officers', 'Clinical Officers', 'Allied Health Professionals', 'U4(SC)', 0, 1, 0, 1, 0, 0, '2026-01-30 03:03:57'),
('January', '2026', 'facility|1002', '', 'RUHOKO Health Centre IV', 'HCIV', 'South Western (Ankole & Kigezi)', 'Municipality, Local Government (LG)', 'Public', 'IBANDA', 'job|75440103', '', 'Vector Control Officer', 'Vector Control Officers', 'Public Health Staff', 'Allied Health Professionals', 'U4(SC)', 0, 0, 1, 1, 0, 0, '2026-01-30 03:03:57'),
('January', '2026', 'facility|1002', '', 'RUHOKO Health Centre IV', 'HCIV', 'South Western (Ankole & Kigezi)', 'Municipality, Local Government (LG)', 'Public', 'IBANDA', 'job|91120102', 'rFuHI0EefGU', 'Theatre Attendant', 'Attendants', 'Support Staff', 'Support Staffs', 'U8L', 0, 1, 0, 1, 0, 0, '2026-01-30 03:03:57');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
