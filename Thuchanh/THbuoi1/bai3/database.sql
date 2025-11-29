-- ============================================
-- DATABASE: Hệ thống quản lý Sinh viên
-- Sử dụng với XAMPP (MySQL/MariaDB)
-- ============================================

-- Tạo database
CREATE DATABASE IF NOT EXISTS students_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE students_db;

-- ============================================
-- Bảng: students (Sinh viên)
-- ============================================
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(20) NOT NULL UNIQUE COMMENT 'Mã số sinh viên',
    password VARCHAR(50) NOT NULL COMMENT 'Mật khẩu',
    lastname VARCHAR(100) NOT NULL COMMENT 'Họ đệm',
    firstname VARCHAR(50) NOT NULL COMMENT 'Tên',
    city VARCHAR(20) NOT NULL COMMENT 'Lớp',
    email VARCHAR(100) NOT NULL COMMENT 'Email',
    course1 VARCHAR(50) NOT NULL COMMENT 'Mã học phần',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Bảng: import_history (Lịch sử import)
-- ============================================
CREATE TABLE IF NOT EXISTS import_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    filename VARCHAR(255) NOT NULL COMMENT 'Tên file import',
    total_rows INT DEFAULT 0 COMMENT 'Tổng số dòng',
    imported_rows INT DEFAULT 0 COMMENT 'Số dòng đã import',
    skipped_rows INT DEFAULT 0 COMMENT 'Số dòng bị bỏ qua',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Thêm dữ liệu mẫu
-- ============================================

INSERT INTO students (username, password, lastname, firstname, city, email, course1) VALUES 
('2351160500', 'cse@485A', 'Đinh Thị Lan', 'Anh', '65HTTT', '2351160500@e.tlu.edu.vn', 'CSE485.202401'),
('2151062699', 'cse@485A', 'Đỗ Phạm Hoàng', 'Anh', '63CNTT4', '2151062699@e.tlu.edu.vn', 'CSE485.202401'),
('2351160501', 'cse@485A', 'Đỗ Quang Nam', 'Anh', '65HTTT', '2351160501@e.tlu.edu.vn', 'CSE485.202401'),
('2351160502', 'cse@485A', 'Nguyễn Thái', 'Anh', '65HTTT', '2351160502@e.tlu.edu.vn', 'CSE485.202401'),
('2351160503', 'cse@485A', 'Tạ Thị Ngọc', 'Anh', '65HTTT', '2351160503@e.tlu.edu.vn', 'CSE485.202401');

-- ============================================
-- Kiểm tra dữ liệu
-- ============================================
SELECT 'Tổng số sinh viên:' AS Info, COUNT(*) AS Total FROM students;
