# HR Management System - Quản lý nhân sự PHP MVC

## Giới thiệu

Dự án **HR Management System** là website quản lý nhân sự xây dựng bằng **PHP + MySQL** theo mô hình **MVC**. Hệ thống có trang quản trị cho Admin/HR và trang cá nhân cho nhân viên.

## Công nghệ

- PHP thuần
- MySQL
- HTML5, CSS3, JavaScript
- Mô hình MVC
- PDO Prepared Statement
- Session PHP
- XAMPP hoặc Laragon

## Chức năng

### Admin
- Dashboard thống kê
- CRUD nhân viên
- CRUD phòng ban
- CRUD chức vụ
- CRUD chấm công
- CRUD bảng lương
- CRUD tài khoản
- Phân quyền người dùng

### HR
- Quản lý nhân viên
- Quản lý phòng ban
- Quản lý chức vụ
- Quản lý chấm công
- Quản lý bảng lương

### Employee
- Xem hồ sơ cá nhân
- Cập nhật thông tin liên hệ
- Đổi mật khẩu
- Xem chấm công cá nhân
- Xem bảng lương cá nhân

## Cấu trúc thư mục

```txt
hr-management/
├── app/
│   ├── controllers/
│   ├── models/
│   └── views/
├── config/
├── core/
├── database/
│   └── hr_management.sql
├── docs/
├── public/
│   ├── index.php
│   ├── assets/
│   └── uploads/
├── README.md
└── .htaccess
```

## Cài đặt

Copy thư mục `hr-management` vào:

```txt
C:\xampp\htdocs\hr-management
```

Bật XAMPP:

```txt
Apache
MySQL
```

Vào phpMyAdmin:

```txt
http://localhost/phpmyadmin
```

Import file:

```txt
database/hr_management.sql
```

Chạy website:

```txt
http://localhost/hr-management/public/
```

## Tài khoản mẫu

```txt
Admin:
username: admin
password: admin123

HR:
username: hr
password: hr123

Employee:
username: employee
password: employee123
```

## Giao diện

- Nền xanh nhạt/trắng
- Sidebar xanh đậm
- Nút chính xanh dương
- Nút sửa màu cam
- Nút xóa màu đỏ
- Dashboard card thống kê
- Bảng danh sách, form thêm/sửa
- Bộ lọc, tìm kiếm, phân trang
- Bo góc mềm, đổ bóng nhẹ, hover mượt
- Responsive desktop/tablet/mobile
