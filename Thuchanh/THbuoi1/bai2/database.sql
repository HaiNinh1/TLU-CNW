-- ============================================
-- DATABASE: Hệ thống Câu hỏi Trắc nghiệm
-- Sử dụng với XAMPP (MySQL/MariaDB)
-- ============================================

-- Tạo database
CREATE DATABASE IF NOT EXISTS quiz_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE quiz_db;

-- ============================================
-- Bảng 1: QUESTIONS (Câu hỏi)
-- ============================================
CREATE TABLE IF NOT EXISTS questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question_text TEXT NOT NULL COMMENT 'Nội dung câu hỏi',
    is_multiple TINYINT(1) DEFAULT 0 COMMENT '0: Một đáp án, 1: Nhiều đáp án',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Bảng 2: OPTIONS (Các lựa chọn đáp án)
-- ============================================
CREATE TABLE IF NOT EXISTS options (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question_id INT NOT NULL COMMENT 'ID câu hỏi',
    option_letter CHAR(1) NOT NULL COMMENT 'Ký tự đáp án (A, B, C, D, E)',
    option_text TEXT NOT NULL COMMENT 'Nội dung đáp án',
    is_correct TINYINT(1) DEFAULT 0 COMMENT '0: Sai, 1: Đúng',
    FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Thêm dữ liệu mẫu (10 câu hỏi đầu tiên)
-- ============================================

-- Câu 1
INSERT INTO questions (question_text, is_multiple) VALUES 
('Thành phần nào sau đây KHÔNG phải là một thành phần giao diện người dùng (UI) trong Android?', 0);
INSERT INTO options (question_id, option_letter, option_text, is_correct) VALUES 
(1, 'A', 'TextView', 0),
(1, 'B', 'Button', 0),
(1, 'C', 'Service', 1),
(1, 'D', 'ImageView', 0);

-- Câu 2
INSERT INTO questions (question_text, is_multiple) VALUES 
('Layout nào thường được sử dụng để sắp xếp các thành phần UI theo chiều dọc hoặc chiều ngang?', 0);
INSERT INTO options (question_id, option_letter, option_text, is_correct) VALUES 
(2, 'A', 'RelativeLayout', 0),
(2, 'B', 'LinearLayout', 1),
(2, 'C', 'FrameLayout', 0),
(2, 'D', 'ConstraintLayout', 0);

-- Câu 3
INSERT INTO questions (question_text, is_multiple) VALUES 
('Intent trong Android được sử dụng để làm gì?', 0);
INSERT INTO options (question_id, option_letter, option_text, is_correct) VALUES 
(3, 'A', 'Hiển thị thông báo cho người dùng.', 0),
(3, 'B', 'Lưu trữ dữ liệu.', 0),
(3, 'C', 'Khởi chạy Activity.', 1),
(3, 'D', 'Xử lý sự kiện chạm.', 0);

-- Câu 4
INSERT INTO questions (question_text, is_multiple) VALUES 
('Vòng đời của một Activity bắt đầu bằng phương thức nào?', 0);
INSERT INTO options (question_id, option_letter, option_text, is_correct) VALUES 
(4, 'A', 'onStart()', 0),
(4, 'B', 'onResume()', 0),
(4, 'C', 'onCreate()', 1),
(4, 'D', 'onPause()', 0);

-- Câu 5
INSERT INTO questions (question_text, is_multiple) VALUES 
('Để xử lý sự kiện click chuột cho một Button, bạn cần sử dụng phương thức nào?', 0);
INSERT INTO options (question_id, option_letter, option_text, is_correct) VALUES 
(5, 'A', 'onClick()', 1),
(5, 'B', 'onTouch()', 0),
(5, 'C', 'onLongClick()', 0),
(5, 'D', 'onFocusChange()', 0);

-- Câu 6
INSERT INTO questions (question_text, is_multiple) VALUES 
('Kiểu dữ liệu nào sau đây được sử dụng để lưu trữ giá trị đúng hoặc sai?', 0);
INSERT INTO options (question_id, option_letter, option_text, is_correct) VALUES 
(6, 'A', 'int', 0),
(6, 'B', 'float', 0),
(6, 'C', 'String', 0),
(6, 'D', 'boolean', 1);

-- Câu 7
INSERT INTO questions (question_text, is_multiple) VALUES 
('SharedPreferences trong Android được sử dụng để làm gì?', 0);
INSERT INTO options (question_id, option_letter, option_text, is_correct) VALUES 
(7, 'A', 'Lưu trữ dữ liệu có cấu trúc.', 0),
(7, 'B', 'Truy cập cơ sở dữ liệu SQLite.', 0),
(7, 'C', 'Lưu trữ dữ liệu dạng key-value.', 1),
(7, 'D', 'Gửi dữ liệu qua mạng.', 0);

-- Câu 8
INSERT INTO questions (question_text, is_multiple) VALUES 
('Toast trong Android được sử dụng để làm gì?', 0);
INSERT INTO options (question_id, option_letter, option_text, is_correct) VALUES 
(8, 'A', 'Hiển thị một hộp thoại.', 0),
(8, 'B', 'Hiển thị một thông báo ngắn gọn cho người dùng.', 1),
(8, 'C', 'Phát nhạc.', 0),
(8, 'D', 'Chụp ảnh màn hình.', 0);

-- Câu 9: Nhiều đáp án
INSERT INTO questions (question_text, is_multiple) VALUES 
('Để tạo một ứng dụng Android, bạn cần sử dụng ngôn ngữ lập trình nào?', 1);
INSERT INTO options (question_id, option_letter, option_text, is_correct) VALUES 
(9, 'A', 'C++', 0),
(9, 'B', 'Python', 0),
(9, 'C', 'Java', 1),
(9, 'D', 'Kotlin', 1);

-- Câu 10
INSERT INTO questions (question_text, is_multiple) VALUES 
('Adapter trong Android được sử dụng để làm gì?', 0);
INSERT INTO options (question_id, option_letter, option_text, is_correct) VALUES 
(10, 'A', 'Kết nối dữ liệu với ListView hoặc RecyclerView.', 1),
(10, 'B', 'Tạo hiệu ứng động.', 0),
(10, 'C', 'Xử lý sự kiện cảm ứng.', 0),
(10, 'D', 'Lưu trữ dữ liệu.', 0);

-- Câu 11
INSERT INTO questions (question_text, is_multiple) VALUES 
('Fragment trong Android là gì?', 0);
INSERT INTO options (question_id, option_letter, option_text, is_correct) VALUES 
(11, 'A', 'Một Activity con.', 0),
(11, 'B', 'Một thành phần UI có thể tái sử dụng.', 1),
(11, 'C', 'Một dịch vụ chạy nền.', 0),
(11, 'D', 'Một kiểu dữ liệu.', 0);

-- Câu 12
INSERT INTO questions (question_text, is_multiple) VALUES 
('RecyclerView là gì?', 0);
INSERT INTO options (question_id, option_letter, option_text, is_correct) VALUES 
(12, 'A', 'Một thành phần UI để hiển thị danh sách.', 1),
(12, 'B', 'Một layout để sắp xếp các thành phần UI.', 0),
(12, 'C', 'Một lớp để xử lý sự kiện.', 0),
(12, 'D', 'Một kiểu dữ liệu.', 0);

-- Câu 13
INSERT INTO questions (question_text, is_multiple) VALUES 
('Manifest file trong Android được sử dụng để làm gì?', 0);
INSERT INTO options (question_id, option_letter, option_text, is_correct) VALUES 
(13, 'A', 'Khai báo các thành phần của ứng dụng.', 1),
(13, 'B', 'Lưu trữ dữ liệu.', 0),
(13, 'C', 'Xử lý sự kiện.', 0),
(13, 'D', 'Tạo giao diện người dùng.', 0);

-- Câu 14
INSERT INTO questions (question_text, is_multiple) VALUES 
('Gradle là gì?', 0);
INSERT INTO options (question_id, option_letter, option_text, is_correct) VALUES 
(14, 'A', 'Một công cụ để quản lý dependencies.', 1),
(14, 'B', 'Một ngôn ngữ lập trình.', 0),
(14, 'C', 'Một IDE để phát triển ứng dụng Android.', 0),
(14, 'D', 'Một framework.', 0);

-- Câu 15
INSERT INTO questions (question_text, is_multiple) VALUES 
('AsyncTask được sử dụng để làm gì?', 0);
INSERT INTO options (question_id, option_letter, option_text, is_correct) VALUES 
(15, 'A', 'Xử lý các tác vụ chạy nền.', 1),
(15, 'B', 'Tạo hiệu ứng động.', 0),
(15, 'C', 'Vẽ đồ họa.', 0),
(15, 'D', 'Lưu trữ dữ liệu.', 0);

-- Câu 16: Nhiều đáp án
INSERT INTO questions (question_text, is_multiple) VALUES 
('Những thành phần nào sau đây có thể được sử dụng để hiển thị danh sách trong Android? (Chọn 2 đáp án)', 1);
INSERT INTO options (question_id, option_letter, option_text, is_correct) VALUES 
(16, 'A', 'TextView', 0),
(16, 'B', 'ListView', 1),
(16, 'C', 'ImageView', 0),
(16, 'D', 'RecyclerView', 1);

-- Câu 17: Nhiều đáp án
INSERT INTO questions (question_text, is_multiple) VALUES 
('Những phát biểu nào sau đây đúng về Intent? (Chọn 2 đáp án)', 1);
INSERT INTO options (question_id, option_letter, option_text, is_correct) VALUES 
(17, 'A', 'Intent có thể được sử dụng để truyền dữ liệu giữa các Activity.', 1),
(17, 'B', 'Intent chỉ có thể được sử dụng để khởi chạy Activity.', 0),
(17, 'C', 'Intent có thể được sử dụng để khởi chạy Service.', 1),
(17, 'D', 'Intent không thể chứa dữ liệu.', 0);

-- Câu 18
INSERT INTO questions (question_text, is_multiple) VALUES 
('MVVM là gì?', 0);
INSERT INTO options (question_id, option_letter, option_text, is_correct) VALUES 
(18, 'A', 'Một kiến trúc phần mềm.', 1),
(18, 'B', 'Một ngôn ngữ lập trình.', 0),
(18, 'C', 'Một framework.', 0),
(18, 'D', 'Một IDE.', 0);

-- Câu 19
INSERT INTO questions (question_text, is_multiple) VALUES 
('Retrofit là gì?', 0);
INSERT INTO options (question_id, option_letter, option_text, is_correct) VALUES 
(19, 'A', 'Một thư viện để thực hiện các request HTTP.', 1),
(19, 'B', 'Một hệ quản trị cơ sở dữ liệu.', 0),
(19, 'C', 'Một framework.', 0),
(19, 'D', 'Một IDE.', 0);

-- Câu 20
INSERT INTO questions (question_text, is_multiple) VALUES 
('Firebase là gì?', 0);
INSERT INTO options (question_id, option_letter, option_text, is_correct) VALUES 
(20, 'A', 'Một nền tảng di động của Google.', 1),
(20, 'B', 'Một hệ quản trị cơ sở dữ liệu.', 0),
(20, 'C', 'Một framework.', 0),
(20, 'D', 'Một IDE.', 0);

-- ============================================
-- Kiểm tra dữ liệu
-- ============================================
SELECT 'Tổng số câu hỏi:' AS Info, COUNT(*) AS Total FROM questions;
SELECT 'Tổng số đáp án:' AS Info, COUNT(*) AS Total FROM options;
