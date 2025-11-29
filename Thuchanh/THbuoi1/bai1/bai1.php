<?php
// Kết nối Database
require_once 'config.php';

// Xử lý các action CRUD
$message = '';
$messageType = '';

// Xử lý xóa hoa
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    try {
        $stmt = $pdo->prepare("DELETE FROM flowers WHERE id = ?");
        $stmt->execute([$id]);
        $message = "Đã xóa hoa thành công!";
        $messageType = "success";
    } catch (PDOException $e) {
        $message = "Lỗi khi xóa: " . $e->getMessage();
        $messageType = "error";
    }
}

// Xử lý thêm/sửa hoa
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $image = trim($_POST['image'] ?? '');
    $editId = intval($_POST['edit_id'] ?? 0);
    
    // Xử lý upload hình ảnh
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'images/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $fileName = time() . '_' . basename($_FILES['image_file']['name']);
        $targetPath = $uploadDir . $fileName;
        
        if (move_uploaded_file($_FILES['image_file']['tmp_name'], $targetPath)) {
            $image = $targetPath;
        }
    }
    
    if (!empty($name) && !empty($description) && !empty($image)) {
        try {
            if ($editId > 0) {
                // Cập nhật hoa
                $stmt = $pdo->prepare("UPDATE flowers SET name = ?, description = ?, image = ? WHERE id = ?");
                $stmt->execute([$name, $description, $image, $editId]);
                $message = "Đã cập nhật hoa thành công!";
            } else {
                // Thêm hoa mới
                $stmt = $pdo->prepare("INSERT INTO flowers (name, description, image) VALUES (?, ?, ?)");
                $stmt->execute([$name, $description, $image]);
                $message = "Đã thêm hoa mới thành công!";
            }
            $messageType = "success";
        } catch (PDOException $e) {
            $message = "Lỗi: " . $e->getMessage();
            $messageType = "error";
        }
    } else {
        $message = "Vui lòng điền đầy đủ thông tin!";
        $messageType = "error";
    }
}

// Lấy thông tin hoa cần sửa
$editFlower = null;
if (isset($_GET['edit'])) {
    $editId = intval($_GET['edit']);
    $stmt = $pdo->prepare("SELECT * FROM flowers WHERE id = ?");
    $stmt->execute([$editId]);
    $editFlower = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Lấy danh sách hoa từ database
try {
    $stmt = $pdo->query("SELECT * FROM flowers ORDER BY id");
    $flowers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $dbConnected = true;
} catch (PDOException $e) {
    $flowers = [];
    $dbConnected = false;
    $errorMessage = $e->getMessage();
}

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
        
        .db-status {
            padding: 10px 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .db-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .db-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .message {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
        }
        
        .message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
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
        
        /* Form Styles */
        .flower-form {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        
        .flower-form h3 {
            color: #764ba2;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
        
        .form-group input[type="text"],
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 1em;
            transition: border-color 0.3s;
        }
        
        .form-group input[type="text"]:focus,
        .form-group textarea:focus {
            border-color: #764ba2;
            outline: none;
        }
        
        .form-group input[type="file"] {
            padding: 10px;
            border: 2px dashed #ddd;
            border-radius: 8px;
            width: 100%;
            cursor: pointer;
        }
        
        .form-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 20px;
        }
        
        .btn-submit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 30px;
            font-size: 1.1em;
        }
        
        .btn-cancel {
            background: #6c757d;
            color: white;
            padding: 12px 30px;
            font-size: 1.1em;
            text-decoration: none;
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
            <h1>🌸 Các Loại Hoa Tuyệt Đẹp 🌸</h1>
            <p>Thích hợp trồng để khoe hương sắc dịp xuân hè | 🗄️ Dữ liệu từ MySQL</p>
        </header>
        
        <?php if (!$dbConnected): ?>
        <div class="db-status db-error">
            ❌ <strong>Lỗi kết nối Database!</strong><br>
            <?php echo htmlspecialchars($errorMessage ?? 'Không thể kết nối MySQL'); ?><br>
            <small>Hãy chắc chắn đã chạy file <code>database.sql</code> trong phpMyAdmin</small>
        </div>
        <?php else: ?>
        <div class="db-status db-success">
            ✅ <strong>Kết nối Database thành công!</strong> - Đang hiển thị <?php echo count($flowers); ?> loại hoa từ MySQL
        </div>
        <?php endif; ?>
        
        <?php if (!empty($message)): ?>
        <div class="message <?php echo $messageType; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
        <?php endif; ?>
        
        <nav class="nav-switch">
            <a href="bai1.php" class="<?php echo !$isAdmin ? 'active' : ''; ?>">👤 Trang Khách</a>
            <a href="bai1.php?admin=1" class="<?php echo $isAdmin ? 'active' : ''; ?>">🔧 Trang Quản Trị</a>
        </nav>
        
        <?php if ($isAdmin): ?>
            <!-- Form thêm/sửa hoa -->
            <div class="flower-form">
                <h3><?php echo $editFlower ? '✏️ Sửa Thông Tin Hoa' : '➕ Thêm Hoa Mới'; ?></h3>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="edit_id" value="<?php echo $editFlower['id'] ?? 0; ?>">
                    
                    <div class="form-group">
                        <label>Tên hoa:</label>
                        <input type="text" name="name" value="<?php echo htmlspecialchars($editFlower['name'] ?? ''); ?>" placeholder="Nhập tên hoa..." required>
                    </div>
                    
                    <div class="form-group">
                        <label>Mô tả:</label>
                        <textarea name="description" rows="4" placeholder="Nhập mô tả về hoa..." required><?php echo htmlspecialchars($editFlower['description'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Đường dẫn hình ảnh (hoặc upload file mới):</label>
                        <input type="text" name="image" value="<?php echo htmlspecialchars($editFlower['image'] ?? ''); ?>" placeholder="images/tenhoa.webp">
                    </div>
                    
                    <div class="form-group">
                        <label>Hoặc tải lên hình ảnh:</label>
                        <input type="file" name="image_file" accept="image/*">
                    </div>
                    
                    <div class="form-buttons">
                        <button type="submit" class="btn btn-submit"><?php echo $editFlower ? '💾 Cập Nhật' : '➕ Thêm Mới'; ?></button>
                        <?php if ($editFlower): ?>
                        <a href="bai1.php?admin=1" class="btn btn-cancel">❌ Hủy</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
            
            <div class="admin-table">
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
                                <img src="<?php echo htmlspecialchars($flower['image']); ?>" alt="<?php echo htmlspecialchars($flower['name']); ?>">
                            </td>
                            <td><strong><?php echo htmlspecialchars($flower['name']); ?></strong></td>
                            <td style="max-width: 400px;"><?php echo htmlspecialchars(substr($flower['description'], 0, 100)); ?>...</td>
                            <td>
                                <a href="bai1.php?admin=1&edit=<?php echo $flower['id']; ?>" class="btn btn-edit">✏️ Sửa</a>
                                <a href="bai1.php?admin=1&delete=<?php echo $flower['id']; ?>" class="btn btn-delete" onclick="return confirm('Bạn có chắc muốn xóa <?php echo htmlspecialchars($flower['name']); ?>?')">🗑️ Xóa</a>
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
                    <img src="<?php echo htmlspecialchars($flower['image']); ?>" alt="<?php echo htmlspecialchars($flower['name']); ?>">
                    <div class="content">
                        <h2><?php echo htmlspecialchars($flower['name']); ?></h2>
                        <p><?php echo htmlspecialchars($flower['description']); ?></p>
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
