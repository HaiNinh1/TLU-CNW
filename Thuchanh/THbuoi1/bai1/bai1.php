<?php
$flowers = [
    [
        'id' => 1,
        'name' => 'Dạ Yến Thảo',
        'description' => 'Hoa dạ yến thảo (Petunia) là loài hoa đẹp với nhiều màu sắc rực rỡ như tím, hồng, trắng, đỏ. Thường được trồng trong chậu treo hoặc bồn hoa, nở rộ vào mùa xuân hè, mang lại vẻ đẹp lãng mạn cho không gian.',
        'image' => 'images/dayenthao.webp'
    ],
    [
        'id' => 2,
        'name' => 'Hoa Cẩm Chướng',
        'description' => 'Hoa cẩm chướng (Carnation) mang ý nghĩa của tình yêu, sự ái mộ và lòng biết ơn. Hoa có nhiều màu sắc phong phú và hương thơm nhẹ nhàng, thích hợp trồng vào mùa xuân.',
        'image' => 'images/hoacamchuong.webp'
    ],
    [
        'id' => 3,
        'name' => 'Hoa Đèn Lồng',
        'description' => 'Hoa đèn lồng (Fuchsia) có hình dáng độc đáo như chiếc đèn lồng nhỏ xinh, với màu sắc kết hợp giữa đỏ, hồng và tím. Thường được trồng làm cảnh trong chậu treo.',
        'image' => 'images/hoadenlong.webp'
    ],
    [
        'id' => 4,
        'name' => 'Hoa Đồng Tiền',
        'description' => 'Hoa đồng tiền (Gerbera) tượng trưng cho sự may mắn, tài lộc và niềm vui. Hoa có nhiều màu sắc tươi sáng như đỏ, vàng, cam, hồng, rất thích hợp để trang trí và làm quà tặng.',
        'image' => 'images/hoadongtien.webp'
    ],
    [
        'id' => 5,
        'name' => 'Hoa Giấy',
        'description' => 'Hoa giấy (Bougainvillea) là loài hoa dễ trồng, có màu sắc rực rỡ như đỏ, hồng, tím, cam. Hoa giấy thường leo giàn và nở hoa quanh năm, đặc biệt đẹp vào mùa hè.',
        'image' => 'images/hoagiay.webp'
    ],
    [
        'id' => 6,
        'name' => 'Hoa Cúc',
        'description' => 'Hoa cúc (Chrysanthemum) là biểu tượng của sự trường thọ và hạnh phúc. Hoa có nhiều loại và màu sắc khác nhau, dễ chăm sóc và nở hoa bền lâu.',
        'image' => 'images/hoacuc.webp'
    ],
    [
        'id' => 7,
        'name' => 'Hoa Hồng',
        'description' => 'Hoa hồng (Rose) được mệnh danh là nữ hoàng của các loài hoa, tượng trưng cho tình yêu và sự lãng mạn. Có rất nhiều giống hoa hồng với đủ màu sắc và hương thơm quyến rũ.',
        'image' => 'images/hoahong.webp'
    ],
    [
        'id' => 8,
        'name' => 'Hoa Lan',
        'description' => 'Hoa lan (Orchid) là loài hoa quý phái, sang trọng, tượng trưng cho sự tinh khiết và cao quý. Lan có nhiều loại như lan hồ điệp, lan dendro, lan mokara rất được ưa chuộng.',
        'image' => 'images/hoalan.webp'
    ],
    [
        'id' => 9,
        'name' => 'Hoa Ly',
        'description' => 'Hoa ly (Lily) có hương thơm nồng nàn và vẻ đẹp kiêu sa. Hoa ly thường được dùng trong các dịp lễ tết, cưới hỏi, mang ý nghĩa của sự thuần khiết và may mắn.',
        'image' => 'images/hoaly.webp'
    ],
    [
        'id' => 10,
        'name' => 'Hoa Mười Giờ',
        'description' => 'Hoa mười giờ (Portulaca) là loài hoa nhỏ xinh, nở rộ vào buổi sáng khi có nắng. Hoa có nhiều màu sắc rực rỡ, dễ trồng và chịu hạn tốt.',
        'image' => 'images/hoamuoigio.webp'
    ],
    [
        'id' => 11,
        'name' => 'Hoa Sen',
        'description' => 'Hoa sen (Lotus) là quốc hoa của Việt Nam, tượng trưng cho sự thanh cao, thuần khiết. Sen nở vào mùa hè với vẻ đẹp thánh thiện và hương thơm dịu nhẹ.',
        'image' => 'images/hoasen.webp'
    ],
    [
        'id' => 12,
        'name' => 'Hoa Súng',
        'description' => 'Hoa súng (Water Lily) là loài hoa thủy sinh đẹp, thường mọc trong ao hồ. Hoa có màu trắng, hồng, tím với những cánh hoa xếp lớp tao nhã.',
        'image' => 'images/hoasung.webp'
    ],
    [
        'id' => 13,
        'name' => 'Hoa Tulip',
        'description' => 'Hoa tulip (Tulip) có nguồn gốc từ Hà Lan, là biểu tượng của mùa xuân. Hoa có hình dáng thanh lịch với nhiều màu sắc tươi đẹp như đỏ, vàng, tím, trắng.',
        'image' => 'images/hoatulip.webp'
    ],
    [
        'id' => 14,
        'name' => 'Hoa Thược Dược',
        'description' => 'Hoa thược dược (Dahlia) có nhiều cánh xếp chồng lên nhau tạo thành bông hoa tròn đầy. Hoa có nhiều màu sắc rực rỡ, thường nở vào mùa hè và thu.',
        'image' => 'images/hoathuocduoc.webp'
    ]
];

$isAdmin = isset($_GET['admin']) && $_GET['admin'] == '1';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>14 Loại Hoa Tuyệt Đẹp - Xuân Hè</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        header {
            text-align: center;
            padding: 30px 0;
            color: white;
        }
        
        header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        header p {
            font-size: 1.2em;
            opacity: 0.9;
        }
        
        .nav-switch {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .nav-switch a {
            display: inline-block;
            padding: 12px 25px;
            margin: 5px;
            background: white;
            color: #764ba2;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        
        .nav-switch a:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.3);
        }
        
        .nav-switch a.active {
            background: #ff6b6b;
            color: white;
        }
        
        /* Style cho trang khách */
        .flower-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
        }
        
        .flower-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .flower-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.3);
        }
        
        .flower-card img {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }
        
        .flower-card .content {
            padding: 20px;
        }
        
        .flower-card h2 {
            color: #764ba2;
            margin-bottom: 10px;
            font-size: 1.5em;
        }
        
        .flower-card p {
            color: #666;
            line-height: 1.6;
        }
        
        /* Style cho trang quản trị */
        .admin-table {
            width: 100%;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        
        .admin-table table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .admin-table th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            text-align: left;
            font-size: 1.1em;
        }
        
        .admin-table td {
            padding: 15px;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
        }
        
        .admin-table tr:hover {
            background: #f8f9fa;
        }
        
        .admin-table img {
            width: 80px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }
        
        .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9em;
            margin: 2px;
            transition: all 0.3s ease;
        }
        
        .btn-add {
            background: #28a745;
            color: white;
            padding: 12px 25px;
            font-size: 1em;
            margin-bottom: 20px;
            display: inline-block;
        }
        
        .btn-add:hover {
            background: #218838;
        }
        
        .btn-edit {
            background: #ffc107;
            color: #333;
        }
        
        .btn-edit:hover {
            background: #e0a800;
        }
        
        .btn-delete {
            background: #dc3545;
            color: white;
        }
        
        .btn-delete:hover {
            background: #c82333;
        }
        
        .btn-view {
            background: #17a2b8;
            color: white;
        }
        
        .btn-view:hover {
            background: #138496;
        }
        
        footer {
            text-align: center;
            padding: 30px;
            color: white;
            margin-top: 30px;
        }
        
        @media (max-width: 768px) {
            .flower-grid {
                grid-template-columns: 1fr;
            }
            
            .admin-table {
                overflow-x: auto;
            }
            
            header h1 {
                font-size: 1.8em;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>🌸 14 Loại Hoa Tuyệt Đẹp 🌸</h1>
            <p>Thích hợp trồng để khoe hương sắc dịp xuân hè</p>
        </header>
        
        <nav class="nav-switch">
            <a href="bai1.php" class="<?php echo !$isAdmin ? 'active' : ''; ?>">👤 Trang Khách</a>
            <a href="bai1.php?admin=1" class="<?php echo $isAdmin ? 'active' : ''; ?>">🔧 Trang Quản Trị</a>
        </nav>
        
        <?php if ($isAdmin): ?>
            <div class="admin-table">
                <div style="padding: 20px;">
                    <button class="btn btn-add" onclick="alert('Chức năng thêm hoa mới!')">➕ Thêm Hoa Mới</button>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Hình Ảnh</th>
                            <th>Tên Hoa</th>
                            <th>Mô Tả</th>
                            <th>Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($flowers as $flower): ?>
                        <tr>
                            <td><strong>#<?php echo $flower['id']; ?></strong></td>
                            <td>
                                <img src="<?php echo $flower['image']; ?>" alt="<?php echo $flower['name']; ?>">
                            </td>
                            <td><strong><?php echo $flower['name']; ?></strong></td>
                            <td style="max-width: 400px;"><?php echo substr($flower['description'], 0, 100); ?>...</td>
                            <td>
                                <button class="btn btn-view" onclick="alert('Xem chi tiết: <?php echo $flower['name']; ?>')">👁️ Xem</button>
                                <button class="btn btn-edit" onclick="alert('Sửa: <?php echo $flower['name']; ?>')">✏️ Sửa</button>
                                <button class="btn btn-delete" onclick="if(confirm('Bạn có chắc muốn xóa <?php echo $flower['name']; ?>?')) alert('Đã xóa!')">🗑️ Xóa</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="flower-grid">
                <?php foreach ($flowers as $flower): ?>
                <article class="flower-card">
                    <img src="<?php echo $flower['image']; ?>" alt="<?php echo $flower['name']; ?>">
                    <div class="content">
                        <h2><?php echo $flower['name']; ?></h2>
                        <p><?php echo $flower['description']; ?></p>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <footer>
            <p>© 2025 - Bài tập PHP: Hiển thị Ảnh từ Thư mục</p>
        </footer>
    </div>
</body>
</html>
