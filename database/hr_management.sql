CREATE DATABASE IF NOT EXISTS hr_management CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE hr_management;

SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS payrolls;
DROP TABLE IF EXISTS attendance;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS employees;
DROP TABLE IF EXISTS positions;
DROP TABLE IF EXISTS departments;
SET FOREIGN_KEY_CHECKS=1;

CREATE TABLE departments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    department_code VARCHAR(30) NOT NULL UNIQUE,
    name VARCHAR(150) NOT NULL,
    manager_name VARCHAR(150),
    phone VARCHAR(30),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE positions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    position_code VARCHAR(30) NOT NULL UNIQUE,
    name VARCHAR(150) NOT NULL,
    base_salary DECIMAL(15,2) NOT NULL DEFAULT 0,
    allowance DECIMAL(15,2) NOT NULL DEFAULT 0,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_code VARCHAR(30) NOT NULL UNIQUE,
    full_name VARCHAR(150) NOT NULL,
    email VARCHAR(150) UNIQUE,
    phone VARCHAR(30),
    gender ENUM('male','female','other') DEFAULT 'other',
    birth_date DATE,
    address VARCHAR(255),
    department_id INT,
    position_id INT,
    hire_date DATE,
    status ENUM('working','probation','resigned') DEFAULT 'working',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL,
    FOREIGN KEY (position_id) REFERENCES positions(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    work_date DATE NOT NULL,
    status ENUM('present','late','absent','leave') DEFAULT 'present',
    check_in TIME,
    check_out TIME,
    note VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_attendance (employee_id, work_date),
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE payrolls (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    salary_month VARCHAR(7) NOT NULL,
    base_salary DECIMAL(15,2) NOT NULL DEFAULT 0,
    allowance DECIMAL(15,2) NOT NULL DEFAULT 0,
    bonus DECIMAL(15,2) NOT NULL DEFAULT 0,
    deduction DECIMAL(15,2) NOT NULL DEFAULT 0,
    net_salary DECIMAL(15,2) NOT NULL DEFAULT 0,
    note VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_payroll (employee_id, salary_month),
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(80) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(150) NOT NULL,
    email VARCHAR(150) UNIQUE,
    role ENUM('admin','hr','employee') NOT NULL DEFAULT 'employee',
    employee_id INT,
    status ENUM('active','locked') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE SET NULL
) ENGINE=InnoDB;

INSERT INTO departments (department_code,name,manager_name,phone,description) VALUES
('HCNS','Hành chính - Nhân sự','Nguyễn Thị Lan','0281000001','Tuyển dụng, hồ sơ, chế độ nhân sự.'),
('KD','Kinh doanh','Trần Văn Minh','0281000002','Quản lý khách hàng, hợp đồng, doanh số.'),
('KT','Kế toán','Lê Thu Hà','0281000003','Quản lý tài chính và bảng lương.'),
('IT','Công nghệ thông tin','Phạm Quốc Huy','0281000004','Quản trị hệ thống và phần mềm.');

INSERT INTO positions (position_code,name,base_salary,allowance,description) VALUES
('TP','Trưởng phòng',25000000,3000000,'Quản lý phòng ban.'),
('NV','Nhân viên',12000000,1000000,'Nhân viên chuyên môn.'),
('TTS','Thực tập sinh',5000000,500000,'Hỗ trợ và học việc.'),
('LEAD','Team Leader',18000000,2000000,'Điều phối nhóm.');

INSERT INTO employees (employee_code,full_name,email,phone,gender,birth_date,address,department_id,position_id,hire_date,status) VALUES
('NV001','Nguyễn Văn An','an.nguyen@example.com','0901000001','male','1995-02-12','TP. Hồ Chí Minh',4,4,'2023-01-10','working'),
('NV002','Trần Thị Bình','binh.tran@example.com','0901000002','female','1997-08-21','Đồng Nai',1,2,'2023-03-15','working'),
('NV003','Lê Minh Châu','chau.le@example.com','0901000003','other','1999-01-15','Bình Dương',2,2,'2024-06-01','probation'),
('NV004','Phạm Quốc Dũng','dung.pham@example.com','0901000004','male','1994-11-08','Long An',3,1,'2022-09-20','working'),
('NV005','Hoàng Minh Hà','ha.hoang@example.com','0901000005','female','1998-04-19','TP. Hồ Chí Minh',4,2,'2024-02-05','working');

INSERT INTO attendance (employee_id,work_date,status,check_in,check_out,note) VALUES
(1,CURDATE(),'present','08:00:00','17:05:00','Đủ công'),
(2,CURDATE(),'late','08:35:00','17:15:00','Đi muộn'),
(3,CURDATE(),'present','08:05:00','17:00:00',NULL),
(4,CURDATE(),'leave',NULL,NULL,'Nghỉ phép'),
(5,CURDATE(),'present','07:55:00','17:10:00',NULL),
(1,DATE_SUB(CURDATE(), INTERVAL 1 DAY),'present','08:00:00','17:00:00',NULL),
(2,DATE_SUB(CURDATE(), INTERVAL 1 DAY),'present','08:02:00','17:04:00',NULL);

INSERT INTO payrolls (employee_id,salary_month,base_salary,allowance,bonus,deduction,net_salary,note) VALUES
(1,DATE_FORMAT(CURDATE(), '%Y-%m'),18000000,2000000,1500000,500000,21000000,'Hoàn thành KPI'),
(2,DATE_FORMAT(CURDATE(), '%Y-%m'),12000000,1000000,800000,300000,13500000,NULL),
(3,DATE_FORMAT(CURDATE(), '%Y-%m'),12000000,1000000,0,0,13000000,'Thử việc'),
(4,DATE_FORMAT(CURDATE(), '%Y-%m'),25000000,3000000,2000000,1000000,29000000,'Trưởng phòng'),
(5,DATE_FORMAT(CURDATE(), '%Y-%m'),12000000,1000000,1000000,200000,13800000,NULL);

-- Passwords: admin/admin123, hr/hr123, employee/employee123
INSERT INTO users (username,password,full_name,email,role,employee_id,status) VALUES
('admin','$2y$12$64QHBqeDLTzNwhtMbYCWNuoCQze6Rc2whJFcvRbdBo61Y/krM8vhK','Quản trị viên','admin@example.com','admin',NULL,'active'),
('hr','$2y$12$FerjmM3twXzP68413RouJeKuXOBaRGA08LLJt.bJzvAEpa9FrJM7e','Nhân sự tổng hợp','hr@example.com','hr',2,'active'),
('employee','$2y$12$9thW3r7kh.gxDoD1PezqmebwUiSHD6Qzm5IYJGIyGY5qnhqEfLPAO','Nguyễn Văn An','an.nguyen@example.com','employee',1,'active');
