-- ============================================
-- DATABASE: Hệ thống quản lý các loài hoa
-- Sử dụng với XAMPP (MySQL/MariaDB)
-- ============================================

-- Tạo database
CREATE DATABASE IF NOT EXISTS flowers_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE flowers_db;

-- ============================================
-- Bảng: flowers (Các loài hoa)
-- ============================================
CREATE TABLE IF NOT EXISTS flowers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL COMMENT 'Tên hoa',
    description TEXT NOT NULL COMMENT 'Mô tả',
    image VARCHAR(255) NOT NULL COMMENT 'Đường dẫn hình ảnh',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Thêm dữ liệu mẫu
-- ============================================

INSERT INTO flowers (name, description, image) VALUES 
('Dạ Yến Thảo', 'Hoa dạ yến thảo (Petunia) là loài hoa đẹp với nhiều màu sắc rực rỡ như tím, hồng, trắng, đỏ. Thường được trồng trong chậu treo hoặc bồn hoa, nở rộ vào mùa xuân hè, mang lại vẻ đẹp lãng mạn cho không gian.', 'images/dayenthao.webp'),
('Hoa Cẩm Chướng', 'Hoa cẩm chướng (Carnation) mang ý nghĩa của tình yêu, sự ái mộ và lòng biết ơn. Hoa có nhiều màu sắc phong phú và hương thơm nhẹ nhàng, thích hợp trồng vào mùa xuân.', 'images/hoacamchuong.webp'),
('Hoa Đèn Lồng', 'Hoa đèn lồng (Fuchsia) có hình dáng độc đáo như chiếc đèn lồng nhỏ xinh, với màu sắc kết hợp giữa đỏ, hồng và tím. Thường được trồng làm cảnh trong chậu treo.', 'images/hoadenlong.webp'),
('Hoa Đồng Tiền', 'Hoa đồng tiền (Gerbera) tượng trưng cho sự may mắn, tài lộc và niềm vui. Hoa có nhiều màu sắc tươi sáng như đỏ, vàng, cam, hồng, rất thích hợp để trang trí và làm quà tặng.', 'images/hoadongtien.webp'),
('Hoa Giấy', 'Hoa giấy (Bougainvillea) là loài hoa dễ trồng, có màu sắc rực rỡ như đỏ, hồng, tím, cam. Hoa giấy thường leo giàn và nở hoa quanh năm, đặc biệt đẹp vào mùa hè.', 'images/hoagiay.webp'),
('Hoa Cúc', 'Hoa cúc (Chrysanthemum) là biểu tượng của sự trường thọ và hạnh phúc. Hoa có nhiều loại và màu sắc khác nhau, dễ chăm sóc và nở hoa bền lâu.', 'images/hoacuc.webp'),
('Hoa Hồng', 'Hoa hồng (Rose) được mệnh danh là nữ hoàng của các loài hoa, tượng trưng cho tình yêu và sự lãng mạn. Có rất nhiều giống hoa hồng với đủ màu sắc và hương thơm quyến rũ.', 'images/hoahong.webp'),
('Hoa Lan', 'Hoa lan (Orchid) là loài hoa quý phái, sang trọng, tượng trưng cho sự tinh khiết và cao quý. Lan có nhiều loại như lan hồ điệp, lan dendro, lan mokara rất được ưa chuộng.', 'images/hoalan.webp'),
('Hoa Ly', 'Hoa ly (Lily) có hương thơm nồng nàn và vẻ đẹp kiêu sa. Hoa ly thường được dùng trong các dịp lễ tết, cưới hỏi, mang ý nghĩa của sự thuần khiết và may mắn.', 'images/hoaly.webp'),
('Hoa Mười Giờ', 'Hoa mười giờ (Portulaca) là loài hoa nhỏ xinh, nở rộ vào buổi sáng khi có nắng. Hoa có nhiều màu sắc rực rỡ, dễ trồng và chịu hạn tốt.', 'images/hoamuoigio.webp'),
('Hoa Sen', 'Hoa sen (Lotus) là quốc hoa của Việt Nam, tượng trưng cho sự thanh cao, thuần khiết. Sen nở vào mùa hè với vẻ đẹp thánh thiện và hương thơm dịu nhẹ.', 'images/hoasen.webp'),
('Hoa Súng', 'Hoa súng (Water Lily) là loài hoa thủy sinh đẹp, thường mọc trong ao hồ. Hoa có màu trắng, hồng, tím với những cánh hoa xếp lớp tao nhã.', 'images/hoasung.webp'),
('Hoa Tulip', 'Hoa tulip (Tulip) có nguồn gốc từ Hà Lan, là biểu tượng của mùa xuân. Hoa có hình dáng thanh lịch với nhiều màu sắc tươi đẹp như đỏ, vàng, tím, trắng.', 'images/hoatulip.webp'),
('Hoa Thược Dược', 'Hoa thược dược (Dahlia) có nhiều cánh xếp chồng lên nhau tạo thành bông hoa tròn đầy. Hoa có nhiều màu sắc rực rỡ, thường nở vào mùa hè và thu.', 'images/hoathuocduoc.webp');

-- ============================================
-- Kiểm tra dữ liệu
-- ============================================
SELECT 'Tổng số hoa:' AS Info, COUNT(*) AS Total FROM flowers;
