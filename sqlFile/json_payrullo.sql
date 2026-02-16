-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 16, 2026 at 03:47 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `json_payrullo`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `Attendance_ID` int(11) NOT NULL,
  `Employee_ID` int(11) NOT NULL,
  `attendance_date` date NOT NULL,
  `time_in` time DEFAULT NULL,
  `time_out` time DEFAULT NULL,
  `status` enum('present','absent','late','on_leave') NOT NULL,
  `remarks` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`Attendance_ID`, `Employee_ID`, `attendance_date`, `time_in`, `time_out`, `status`, `remarks`) VALUES
(1, 1, '2026-02-10', '09:00:00', '17:00:00', 'present', NULL),
(2, 2, '2026-02-10', '13:00:00', '21:00:00', 'present', NULL),
(3, 3, '2026-02-10', '13:05:00', '21:00:00', 'late', 'Slight delay'),
(4, 4, '2026-02-10', '09:10:00', '17:00:00', 'late', 'Traffic'),
(5, 5, '2026-02-10', '21:00:00', '05:00:00', 'present', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `Department_ID` int(11) NOT NULL,
  `department_code` varchar(10) NOT NULL,
  `name` varchar(80) NOT NULL,
  `department_manager_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`Department_ID`, `department_code`, `name`, `department_manager_id`) VALUES
(1, 'CREAPRO', 'Creative & Production', NULL),
(2, 'CONTSOC', 'Content & Social Media', NULL),
(3, 'ACCCLIE', 'Accounts & Client Services', 2),
(4, 'OPETECH', 'Operations & Technology', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `Employee_ID` int(11) NOT NULL,
  `employee_code` varchar(20) NOT NULL,
  `User_ID` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` varchar(120) NOT NULL,
  `birthdate` date NOT NULL,
  `hire_date` date NOT NULL,
  `employment_status` enum('active','on_leave','resigned','terminated') NOT NULL DEFAULT 'active',
  `employment_type_id` int(11) NOT NULL,
  `Department_ID` int(11) NOT NULL,
  `Position_ID` int(11) NOT NULL,
  `basic_salary` decimal(10,2) NOT NULL DEFAULT 18000.00
) ;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`Employee_ID`, `employee_code`, `User_ID`, `first_name`, `middle_name`, `last_name`, `phone`, `address`, `birthdate`, `hire_date`, `employment_status`, `employment_type_id`, `Department_ID`, `Position_ID`, `basic_salary`) VALUES
(1, 'CREAPRO-2026-001', 1001, 'Shu', NULL, 'Li', '09171234567', '123 Creative St, Manila', '1985-07-12', '2026-02-01', 'active', 1, 1, 1, 45000.00),
(2, 'CREAPRO-2026-002', 1002, 'David', 'K', 'Santos', '09179876543', '456 Creative St, Manila', '1990-05-23', '2026-02-02', 'active', 1, 1, 2, 25000.00),
(3, 'CONTSOC-2026-001', 1003, 'Emma', NULL, 'Sun', '09171239876', '789 Content Ave, Manila', '1992-11-15', '2026-02-03', 'active', 1, 2, 6, 38000.00),
(4, 'ACCCLIE-2026-001', 1004, 'Frank', NULL, 'Yap', '09172345678', '321 Account Rd, Manila', '1988-03-10', '2026-02-04', 'active', 1, 3, 9, 42000.00),
(5, 'OPETECH-2026-001', 1005, 'Grace', 'H', 'Lim', '09173456789', '654 Tech Blvd, Manila', '1995-09-05', '2026-02-05', 'active', 1, 4, 11, 50000.00);

-- --------------------------------------------------------

--
-- Table structure for table `employee_shifts`
--

CREATE TABLE `employee_shifts` (
  `EmployShift_ID` int(11) NOT NULL,
  `Employee_ID` int(11) NOT NULL,
  `Shift_ID` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee_shifts`
--

INSERT INTO `employee_shifts` (`EmployShift_ID`, `Employee_ID`, `Shift_ID`, `start_date`, `end_date`) VALUES
(1, 1, 1, '2026-02-01', NULL),
(2, 2, 2, '2026-02-02', NULL),
(3, 3, 2, '2026-02-03', NULL),
(4, 4, 1, '2026-02-04', NULL),
(5, 5, 3, '2026-02-05', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employment_types`
--

CREATE TABLE `employment_types` (
  `EmployType_ID` int(11) NOT NULL,
  `name` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employment_types`
--

INSERT INTO `employment_types` (`EmployType_ID`, `name`) VALUES
(3, 'Contract / Freelancer'),
(1, 'Full-time'),
(4, 'Intern / Trainee'),
(2, 'Part-time');

-- --------------------------------------------------------

--
-- Table structure for table `leave_balances`
--

CREATE TABLE `leave_balances` (
  `LeaveBal_ID` int(11) NOT NULL,
  `Employee_ID` int(11) NOT NULL,
  `LeaveType_ID` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `allocated_days` int(11) NOT NULL,
  `used_days` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leave_balances`
--

INSERT INTO `leave_balances` (`LeaveBal_ID`, `Employee_ID`, `LeaveType_ID`, `year`, `allocated_days`, `used_days`) VALUES
(1, 2, 2, 2026, 10, 2),
(2, 3, 4, 2026, 5, 0),
(3, 4, 3, 2026, 30, 0),
(4, 5, 1, 2026, 15, 3);

-- --------------------------------------------------------

--
-- Table structure for table `leave_requests`
--

CREATE TABLE `leave_requests` (
  `LeaveReq_ID` int(11) NOT NULL,
  `Employee_ID` int(11) NOT NULL,
  `leave_type_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `reason` varchar(200) DEFAULT NULL,
  `status` enum('pending','approved','rejected','cancelled') NOT NULL DEFAULT 'pending',
  `requested_at` datetime NOT NULL DEFAULT current_timestamp(),
  `reviewed_by` int(11) DEFAULT NULL,
  `reviewed_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leave_requests`
--

INSERT INTO `leave_requests` (`LeaveReq_ID`, `Employee_ID`, `leave_type_id`, `start_date`, `end_date`, `reason`, `status`, `requested_at`, `reviewed_by`, `reviewed_at`) VALUES
(1, 2, 2, '2026-03-10', '2026-03-12', 'Flu', 'approved', '2026-02-15 10:00:00', 2, NULL),
(2, 3, 4, '2026-03-15', '2026-03-15', 'Emergency', 'pending', '2026-02-20 11:00:00', NULL, NULL),
(3, 4, 3, '2026-03-20', '2026-03-25', 'Personal reasons', 'pending', '2026-02-25 14:00:00', NULL, NULL),
(4, 5, 1, '2026-03-05', '2026-03-10', 'Vacation', 'approved', '2026-02-12 09:30:00', 2, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `leave_types`
--

CREATE TABLE `leave_types` (
  `LeaveType_ID` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `max_days_per_year` int(11) NOT NULL,
  `is_paid` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leave_types`
--

INSERT INTO `leave_types` (`LeaveType_ID`, `name`, `max_days_per_year`, `is_paid`) VALUES
(1, 'Vacation', 15, 1),
(2, 'Sick Leave', 10, 1),
(3, 'Unpaid Leave', 30, 0),
(4, 'Emergency', 5, 1);

-- --------------------------------------------------------

--
-- Table structure for table `payroll_allowances`
--

CREATE TABLE `payroll_allowances` (
  `PayrollAllowance_ID` int(11) NOT NULL,
  `PayrollRun_ID` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payroll_allowances`
--

INSERT INTO `payroll_allowances` (`PayrollAllowance_ID`, `PayrollRun_ID`, `name`, `amount`) VALUES
(1, 1, 'Transport', 1000.00),
(2, 1, 'Meal', 1000.00),
(3, 2, 'Transport', 500.00),
(4, 3, 'Bonus', 500.00),
(5, 5, 'Housing', 1500.00);

-- --------------------------------------------------------

--
-- Table structure for table `payroll_deductions`
--

CREATE TABLE `payroll_deductions` (
  `PayrollDeduction_ID` int(11) NOT NULL,
  `PayrollRun_ID` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payroll_deductions`
--

INSERT INTO `payroll_deductions` (`PayrollDeduction_ID`, `PayrollRun_ID`, `name`, `amount`) VALUES
(1, 1, 'Tax', 1500.00),
(2, 2, 'Tax', 500.00),
(3, 3, 'Tax', 800.00),
(4, 4, 'Tax', 1000.00),
(5, 5, 'Tax', 2000.00);

-- --------------------------------------------------------

--
-- Table structure for table `payroll_periods`
--

CREATE TABLE `payroll_periods` (
  `PayrollPeriod_ID` int(11) NOT NULL,
  `period_start` date NOT NULL,
  `period_end` date NOT NULL,
  `pay_date` date NOT NULL,
  `status` enum('open','processed','released') NOT NULL DEFAULT 'open'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payroll_periods`
--

INSERT INTO `payroll_periods` (`PayrollPeriod_ID`, `period_start`, `period_end`, `pay_date`, `status`) VALUES
(1, '2026-02-01', '2026-02-15', '2026-02-20', 'processed'),
(2, '2026-02-16', '2026-02-28', '2026-03-05', 'open');

-- --------------------------------------------------------

--
-- Table structure for table `payroll_runs`
--

CREATE TABLE `payroll_runs` (
  `PayrollRun_ID` int(11) NOT NULL,
  `PayrollPeriod_ID` int(11) NOT NULL,
  `Employee_ID` int(11) NOT NULL,
  `basic_pay` decimal(10,2) NOT NULL DEFAULT 18000.00,
  `overtime_pay` decimal(10,2) NOT NULL DEFAULT 0.00,
  `allowances_total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `deductions_total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `gross_pay` decimal(10,2) GENERATED ALWAYS AS (`basic_pay` + `overtime_pay` + `allowances_total`) STORED,
  `net_pay` decimal(10,2) GENERATED ALWAYS AS (`basic_pay` + `overtime_pay` + `allowances_total` - `deductions_total`) STORED,
  `generated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `generated_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payroll_runs`
--

INSERT INTO `payroll_runs` (`PayrollRun_ID`, `PayrollPeriod_ID`, `Employee_ID`, `basic_pay`, `overtime_pay`, `allowances_total`, `deductions_total`, `generated_at`, `generated_by`) VALUES
(1, 1, 1, 45000.00, 5000.00, 2000.00, 1500.00, '2026-02-20 12:00:00', 1),
(2, 1, 2, 25000.00, 2000.00, 1000.00, 500.00, '2026-02-20 12:05:00', 1),
(3, 1, 3, 38000.00, 1500.00, 500.00, 800.00, '2026-02-20 12:10:00', 1),
(4, 1, 4, 42000.00, 1000.00, 700.00, 1000.00, '2026-02-20 12:15:00', 2),
(5, 1, 5, 50000.00, 0.00, 1500.00, 2000.00, '2026-02-20 12:20:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `payslips`
--

CREATE TABLE `payslips` (
  `Payslip_ID` int(11) NOT NULL,
  `PayrollRun_ID` int(11) NOT NULL,
  `pdf_path` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payslips`
--

INSERT INTO `payslips` (`Payslip_ID`, `PayrollRun_ID`, `pdf_path`, `created_at`) VALUES
(1, 1, '/payslips/2026-02-shu_lli.pdf', '2026-02-16 22:44:35'),
(2, 2, '/payslips/2026-02-david_k.pdf', '2026-02-16 22:44:35'),
(3, 3, '/payslips/2026-02-emma_s.pdf', '2026-02-16 22:44:35'),
(4, 4, '/payslips/2026-02-frank99.pdf', '2026-02-16 22:44:35'),
(5, 5, '/payslips/2026-02-grace_h.pdf', '2026-02-16 22:44:35');

-- --------------------------------------------------------

--
-- Table structure for table `positions`
--

CREATE TABLE `positions` (
  `Position_ID` int(11) NOT NULL,
  `Department_ID` int(11) NOT NULL,
  `title` varchar(80) NOT NULL,
  `default_base_salary` decimal(10,2) NOT NULL DEFAULT 18000.00
) ;

--
-- Dumping data for table `positions`
--

INSERT INTO `positions` (`Position_ID`, `Department_ID`, `title`, `default_base_salary`) VALUES
(1, 1, 'Art Director', 45000.00),
(2, 1, 'Graphic Designer', 25000.00),
(3, 1, 'Video Editor', 30000.00),
(4, 1, 'Copywriter', 28000.00),
(5, 2, 'Social Media Manager', 35000.00),
(6, 2, 'Content Strategist', 38000.00),
(7, 2, 'Community Manager', 22000.00),
(8, 3, 'Account Executive', 26000.00),
(9, 3, 'Account Manager', 42000.00),
(10, 3, 'Client Success Specialist', 30000.00),
(11, 4, 'Web Developer', 40000.00),
(12, 4, 'IT Support Specialist', 24000.00),
(13, 4, 'Operations Manager', 50000.00);

-- --------------------------------------------------------

--
-- Table structure for table `shifts`
--

CREATE TABLE `shifts` (
  `Shift_ID` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `time_start` time NOT NULL,
  `time_end` time NOT NULL,
  `break_minutes` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shifts`
--

INSERT INTO `shifts` (`Shift_ID`, `name`, `time_start`, `time_end`, `break_minutes`) VALUES
(1, 'Day', '09:00:00', '17:00:00', 60),
(2, 'Afternoon', '13:00:00', '21:00:00', 60),
(3, 'Night', '21:00:00', '05:00:00', 45);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `User_ID` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(120) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','manager','employee') NOT NULL DEFAULT 'employee',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`User_ID`, `username`, `email`, `password_hash`, `role`, `is_active`, `created_at`) VALUES
(1001, 'shu_lli', 'shulli@jsoncorp.com', '$2b$10$u26YFBIoHbdPyHEJn96.EuaaehXVIEpQ7/lk8.ywXLD.f5lZJ5Cre', 'admin', 1, '2026-02-16 21:36:24'),
(1002, 'david_k', 'davidk@jsoncorp.com', '$2b$10$lcnjYTHgKA4sK8dhI5ZB1OOH0nbgK.4RBHa4opTD/kksuw29UA2TO', 'employee', 1, '2026-02-16 21:36:24'),
(1003, 'emma_s', 'emma_s@jsoncorp.com', '$2b$10$G.WUOsvgKc0ZRpmzwHyEpOshM6MXHFnEO3orr4qjeu9rhkLfLgIdi', 'employee', 1, '2026-02-16 21:36:24'),
(1004, 'frank99', 'frank99@jsoncorp.com', '$2b$10$As69P3VSuk.nBAQWDd6Piu2nFUigwDVehhsMC7Ficdwe6FcNp2cnq', 'employee', 1, '2026-02-16 21:36:24'),
(1005, 'grace_h', 'grace.h@jsoncorp.com', '$2b$10$jQnAlt5bCj2XEegVZaMw/eV7RBhI4BLOOzDd7rOgIyp3iTgG7Lw2.', 'employee', 1, '2026-02-16 21:36:24');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`Attendance_ID`),
  ADD UNIQUE KEY `uniq_attendance_per_day` (`Employee_ID`,`attendance_date`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`Department_ID`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `fk_departments_manager` (`department_manager_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`Employee_ID`),
  ADD UNIQUE KEY `User_ID` (`User_ID`),
  ADD KEY `employment_type_id` (`employment_type_id`),
  ADD KEY `Department_ID` (`Department_ID`),
  ADD KEY `Position_ID` (`Position_ID`);

--
-- Indexes for table `employee_shifts`
--
ALTER TABLE `employee_shifts`
  ADD PRIMARY KEY (`EmployShift_ID`),
  ADD KEY `Employee_ID` (`Employee_ID`),
  ADD KEY `Shift_ID` (`Shift_ID`);

--
-- Indexes for table `employment_types`
--
ALTER TABLE `employment_types`
  ADD PRIMARY KEY (`EmployType_ID`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `leave_balances`
--
ALTER TABLE `leave_balances`
  ADD PRIMARY KEY (`LeaveBal_ID`),
  ADD UNIQUE KEY `uniq_leave_balance` (`Employee_ID`,`LeaveType_ID`,`year`),
  ADD KEY `LeaveType_ID` (`LeaveType_ID`);

--
-- Indexes for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD PRIMARY KEY (`LeaveReq_ID`),
  ADD KEY `Employee_ID` (`Employee_ID`),
  ADD KEY `leave_type_id` (`leave_type_id`),
  ADD KEY `reviewed_by` (`reviewed_by`);

--
-- Indexes for table `leave_types`
--
ALTER TABLE `leave_types`
  ADD PRIMARY KEY (`LeaveType_ID`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `payroll_allowances`
--
ALTER TABLE `payroll_allowances`
  ADD PRIMARY KEY (`PayrollAllowance_ID`),
  ADD KEY `PayrollRun_ID` (`PayrollRun_ID`);

--
-- Indexes for table `payroll_deductions`
--
ALTER TABLE `payroll_deductions`
  ADD PRIMARY KEY (`PayrollDeduction_ID`),
  ADD KEY `PayrollRun_ID` (`PayrollRun_ID`);

--
-- Indexes for table `payroll_periods`
--
ALTER TABLE `payroll_periods`
  ADD PRIMARY KEY (`PayrollPeriod_ID`),
  ADD UNIQUE KEY `uniq_period` (`period_start`,`period_end`);

--
-- Indexes for table `payroll_runs`
--
ALTER TABLE `payroll_runs`
  ADD PRIMARY KEY (`PayrollRun_ID`),
  ADD UNIQUE KEY `uniq_payroll_per_period` (`PayrollPeriod_ID`,`Employee_ID`),
  ADD KEY `Employee_ID` (`Employee_ID`),
  ADD KEY `generated_by` (`generated_by`);

--
-- Indexes for table `payslips`
--
ALTER TABLE `payslips`
  ADD PRIMARY KEY (`Payslip_ID`),
  ADD UNIQUE KEY `PayrollRun_ID` (`PayrollRun_ID`);

--
-- Indexes for table `positions`
--
ALTER TABLE `positions`
  ADD PRIMARY KEY (`Position_ID`),
  ADD KEY `Department_ID` (`Department_ID`);

--
-- Indexes for table `shifts`
--
ALTER TABLE `shifts`
  ADD PRIMARY KEY (`Shift_ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`User_ID`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `Attendance_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `Department_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `Employee_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_shifts`
--
ALTER TABLE `employee_shifts`
  MODIFY `EmployShift_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `employment_types`
--
ALTER TABLE `employment_types`
  MODIFY `EmployType_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `leave_balances`
--
ALTER TABLE `leave_balances`
  MODIFY `LeaveBal_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `leave_requests`
--
ALTER TABLE `leave_requests`
  MODIFY `LeaveReq_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `leave_types`
--
ALTER TABLE `leave_types`
  MODIFY `LeaveType_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `payroll_allowances`
--
ALTER TABLE `payroll_allowances`
  MODIFY `PayrollAllowance_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `payroll_deductions`
--
ALTER TABLE `payroll_deductions`
  MODIFY `PayrollDeduction_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `payroll_periods`
--
ALTER TABLE `payroll_periods`
  MODIFY `PayrollPeriod_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `payroll_runs`
--
ALTER TABLE `payroll_runs`
  MODIFY `PayrollRun_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `payslips`
--
ALTER TABLE `payslips`
  MODIFY `Payslip_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `positions`
--
ALTER TABLE `positions`
  MODIFY `Position_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shifts`
--
ALTER TABLE `shifts`
  MODIFY `Shift_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `User_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1006;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`Employee_ID`) REFERENCES `employees` (`Employee_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `departments`
--
ALTER TABLE `departments`
  ADD CONSTRAINT `fk_departments_manager` FOREIGN KEY (`department_manager_id`) REFERENCES `employees` (`Employee_ID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `users` (`User_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `employees_ibfk_2` FOREIGN KEY (`employment_type_id`) REFERENCES `employment_types` (`EmployType_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `employees_ibfk_3` FOREIGN KEY (`Department_ID`) REFERENCES `departments` (`Department_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `employees_ibfk_4` FOREIGN KEY (`Position_ID`) REFERENCES `positions` (`Position_ID`) ON UPDATE CASCADE;

--
-- Constraints for table `employee_shifts`
--
ALTER TABLE `employee_shifts`
  ADD CONSTRAINT `employee_shifts_ibfk_1` FOREIGN KEY (`Employee_ID`) REFERENCES `employees` (`Employee_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `employee_shifts_ibfk_2` FOREIGN KEY (`Shift_ID`) REFERENCES `shifts` (`Shift_ID`) ON UPDATE CASCADE;

--
-- Constraints for table `leave_balances`
--
ALTER TABLE `leave_balances`
  ADD CONSTRAINT `leave_balances_ibfk_1` FOREIGN KEY (`Employee_ID`) REFERENCES `employees` (`Employee_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `leave_balances_ibfk_2` FOREIGN KEY (`LeaveType_ID`) REFERENCES `leave_types` (`LeaveType_ID`) ON UPDATE CASCADE;

--
-- Constraints for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD CONSTRAINT `leave_requests_ibfk_1` FOREIGN KEY (`Employee_ID`) REFERENCES `employees` (`Employee_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `leave_requests_ibfk_2` FOREIGN KEY (`leave_type_id`) REFERENCES `leave_types` (`LeaveType_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `leave_requests_ibfk_3` FOREIGN KEY (`reviewed_by`) REFERENCES `employees` (`Employee_ID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `payroll_allowances`
--
ALTER TABLE `payroll_allowances`
  ADD CONSTRAINT `payroll_allowances_ibfk_1` FOREIGN KEY (`PayrollRun_ID`) REFERENCES `payroll_runs` (`PayrollRun_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payroll_deductions`
--
ALTER TABLE `payroll_deductions`
  ADD CONSTRAINT `payroll_deductions_ibfk_1` FOREIGN KEY (`PayrollRun_ID`) REFERENCES `payroll_runs` (`PayrollRun_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payroll_runs`
--
ALTER TABLE `payroll_runs`
  ADD CONSTRAINT `payroll_runs_ibfk_1` FOREIGN KEY (`PayrollPeriod_ID`) REFERENCES `payroll_periods` (`PayrollPeriod_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `payroll_runs_ibfk_2` FOREIGN KEY (`Employee_ID`) REFERENCES `employees` (`Employee_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `payroll_runs_ibfk_3` FOREIGN KEY (`generated_by`) REFERENCES `employees` (`Employee_ID`) ON UPDATE CASCADE;

--
-- Constraints for table `payslips`
--
ALTER TABLE `payslips`
  ADD CONSTRAINT `payslips_ibfk_1` FOREIGN KEY (`PayrollRun_ID`) REFERENCES `payroll_runs` (`PayrollRun_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `positions`
--
ALTER TABLE `positions`
  ADD CONSTRAINT `positions_ibfk_1` FOREIGN KEY (`Department_ID`) REFERENCES `departments` (`Department_ID`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
