<?php
// Kết nối Database
require_once 'config.php';

$message = '';
$messageType = '';

// Xử lý upload và import CSV
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv_file'])) {
    if ($_FILES['csv_file']['error'] === UPLOAD_ERR_OK) {
        $tmpName = $_FILES['csv_file']['tmp_name'];
        $fileName = $_FILES['csv_file']['name'];
        
        // Kiểm tra file CSV
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        if ($fileExt === 'csv') {
            $file = fopen($tmpName, 'r');
            $headers = fgetcsv($file);
            
            $totalRows = 0;
            $importedRows = 0;
            $skippedRows = 0;
            
            // Chuẩn bị câu lệnh INSERT với ON DUPLICATE KEY UPDATE
            $stmt = $pdo->prepare("INSERT INTO students (username, password, lastname, firstname, city, email, course1) 
                                   VALUES (?, ?, ?, ?, ?, ?, ?)
                                   ON DUPLICATE KEY UPDATE 
                                   password = VALUES(password),
                                   lastname = VALUES(lastname),
                                   firstname = VALUES(firstname),
                                   city = VALUES(city),
                                   email = VALUES(email),
                                   course1 = VALUES(course1)");
            
            while (($row = fgetcsv($file)) !== false) {
                $totalRows++;
                if (count($row) == count($headers)) {
                    $data = array_combine($headers, $row);
                    try {
                        $stmt->execute([
                            $data['username'],
                            $data['password'],
                            $data['lastname'],
                            $data['firstname'],
                            $data['city'],
                            $data['email'],
                            $data['course1']
                        ]);
                        $importedRows++;
                    } catch (PDOException $e) {
                        $skippedRows++;
                    }
                } else {
                    $skippedRows++;
                }
            }
            
            fclose($file);
            
            // Lưu lịch sử import
            $historyStmt = $pdo->prepare("INSERT INTO import_history (filename, total_rows, imported_rows, skipped_rows) VALUES (?, ?, ?, ?)");
            $historyStmt->execute([$fileName, $totalRows, $importedRows, $skippedRows]);
            
            $message = "Đã import thành công! Tổng: $totalRows dòng | Thành công: $importedRows | Bỏ qua: $skippedRows";
            $messageType = "success";
        } else {
            $message = "Vui lòng chọn file CSV!";
            $messageType = "error";
        }
    } else {
        $message = "Lỗi khi upload file!";
        $messageType = "error";
    }
}

// Xử lý xóa sinh viên
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    try {
        $stmt = $pdo->prepare("DELETE FROM students WHERE id = ?");
        $stmt->execute([$id]);
        $message = "Đã xóa sinh viên thành công!";
        $messageType = "success";
    } catch (PDOException $e) {
        $message = "Lỗi khi xóa: " . $e->getMessage();
        $messageType = "error";
    }
}

// Xử lý thêm/sửa sinh viên
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $lastname = trim($_POST['lastname'] ?? '');
    $firstname = trim($_POST['firstname'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $course1 = trim($_POST['course1'] ?? '');
    $editId = intval($_POST['edit_id'] ?? 0);
    
    if (!empty($username) && !empty($password) && !empty($lastname) && !empty($firstname)) {
        try {
            if ($editId > 0) {
                $stmt = $pdo->prepare("UPDATE students SET username=?, password=?, lastname=?, firstname=?, city=?, email=?, course1=? WHERE id=?");
                $stmt->execute([$username, $password, $lastname, $firstname, $city, $email, $course1, $editId]);
                $message = "Đã cập nhật sinh viên thành công!";
            } else {
                $stmt = $pdo->prepare("INSERT INTO students (username, password, lastname, firstname, city, email, course1) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$username, $password, $lastname, $firstname, $city, $email, $course1]);
                $message = "Đã thêm sinh viên mới thành công!";
            }
            $messageType = "success";
        } catch (PDOException $e) {
            $message = "Lỗi: " . $e->getMessage();
            $messageType = "error";
        }
    } else {
        $message = "Vui lòng điền đầy đủ thông tin bắt buộc!";
        $messageType = "error";
    }
}

// Lấy thông tin sinh viên cần sửa
$editStudent = null;
if (isset($_GET['edit'])) {
    $editId = intval($_GET['edit']);
    $stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
    $stmt->execute([$editId]);
    $editStudent = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Lấy danh sách sinh viên từ database
try {
    // Tìm kiếm
    $searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
    $classFilter = isset($_GET['class']) ? $_GET['class'] : '';
    
    $sql = "SELECT * FROM students WHERE 1=1";
    $params = [];
    
    if (!empty($searchTerm)) {
        $sql .= " AND (username LIKE ? OR lastname LIKE ? OR firstname LIKE ? OR email LIKE ?)";
        $searchParam = "%$searchTerm%";
        $params = array_merge($params, [$searchParam, $searchParam, $searchParam, $searchParam]);
    }
    
    if (!empty($classFilter)) {
        $sql .= " AND city = ?";
        $params[] = $classFilter;
    }
    
    $sql .= " ORDER BY id DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Lấy tổng số sinh viên
    $totalStmt = $pdo->query("SELECT COUNT(*) FROM students");
    $totalStudents = $totalStmt->fetchColumn();
    
    // Lấy danh sách lớp
    $classStmt = $pdo->query("SELECT DISTINCT city FROM students ORDER BY city");
    $classes = $classStmt->fetchAll(PDO::FETCH_COLUMN);
    
    $dbConnected = true;
} catch (PDOException $e) {
    $students = [];
    $totalStudents = 0;
    $classes = [];
    $dbConnected = false;
    $errorMessage = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Tài Khoản Sinh Viên - Database</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
        }
        
        header {
            text-align: center;
            padding: 30px;
            background: white;
            border-radius: 15px;
            margin-bottom: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        
        header h1 {
            color: #2c5364;
            font-size: 2em;
            margin-bottom: 10px;
        }
        
        header h1 span {
            color: #e74c3c;
        }
        
        header p {
            color: #666;
            font-size: 1.1em;
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
        
        .stats-bar {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
            flex-wrap: wrap;
        }
        
        .stat-item {
            background: linear-gradient(135deg, #2c5364 0%, #203a43 100%);
            color: white;
            padding: 12px 25px;
            border-radius: 25px;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .stat-item.highlight {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        }
        
        /* Upload Section */
        .upload-section {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        }
        
        .upload-section h3 {
            color: #2c5364;
            margin-bottom: 15px;
            text-align: center;
        }
        
        .upload-form {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            align-items: center;
            justify-content: center;
        }
        
        .upload-form input[type="file"] {
            padding: 12px;
            border: 2px dashed #2c5364;
            border-radius: 10px;
            background: #f8f9fa;
            cursor: pointer;
        }
        
        .upload-form button {
            padding: 12px 30px;
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            color: white;
            border: none;
            border-radius: 25px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .upload-form button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(39, 174, 96, 0.4);
        }
        
        /* Student Form */
        .student-form {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        }
        
        .student-form h3 {
            color: #2c5364;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        
        .form-group {
            margin-bottom: 10px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
        
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 1em;
        }
        
        .form-group input:focus {
            border-color: #2c5364;
            outline: none;
        }
        
        .form-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 20px;
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 25px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-submit {
            background: linear-gradient(135deg, #2c5364 0%, #203a43 100%);
            color: white;
        }
        
        .btn-cancel {
            background: #6c757d;
            color: white;
        }
        
        /* Filter Section */
        .filter-section {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        }
        
        .filter-form {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            align-items: center;
            justify-content: center;
        }
        
        .filter-form input[type="text"] {
            padding: 12px 20px;
            border: 2px solid #ddd;
            border-radius: 25px;
            font-size: 1em;
            width: 300px;
            transition: all 0.3s ease;
        }
        
        .filter-form input[type="text"]:focus {
            border-color: #2c5364;
            outline: none;
            box-shadow: 0 0 10px rgba(44, 83, 100, 0.3);
        }
        
        .filter-form select {
            padding: 12px 20px;
            border: 2px solid #ddd;
            border-radius: 25px;
            font-size: 1em;
            background: white;
            cursor: pointer;
            min-width: 150px;
        }
        
        .filter-form select:focus {
            border-color: #2c5364;
            outline: none;
        }
        
        .btn-search {
            background: linear-gradient(135deg, #2c5364 0%, #203a43 100%);
            color: white;
            padding: 12px 25px;
        }
        
        .btn-search:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(44, 83, 100, 0.4);
        }
        
        .btn-reset {
            background: #e74c3c;
            color: white;
            padding: 12px 25px;
        }
        
        .btn-reset:hover {
            background: #c0392b;
        }
        
        /* Table Container */
        .table-container {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        
        .table-header {
            background: linear-gradient(135deg, #2c5364 0%, #203a43 100%);
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .table-header h2 {
            font-size: 1.3em;
        }
        
        .result-count {
            background: rgba(255,255,255,0.2);
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.9em;
        }
        
        /* Table Styles */
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .data-table thead {
            background: linear-gradient(135deg, #34495e 0%, #2c3e50 100%);
            color: white;
            position: sticky;
            top: 0;
        }
        
        .data-table th {
            padding: 15px 12px;
            text-align: left;
            font-weight: 600;
            font-size: 0.95em;
            white-space: nowrap;
        }
        
        .data-table th:first-child {
            text-align: center;
            width: 60px;
        }
        
        .data-table tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid #eee;
        }
        
        .data-table tbody tr:hover {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            transform: scale(1.01);
        }
        
        .data-table tbody tr:nth-child(even) {
            background: #f8f9fa;
        }
        
        .data-table tbody tr:nth-child(even):hover {
            background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
        }
        
        .data-table td {
            padding: 12px;
            color: #333;
            font-size: 0.9em;
        }
        
        .data-table td:first-child {
            text-align: center;
            font-weight: bold;
            color: #2c5364;
        }
        
        /* Column Specific Styles */
        .col-username {
            font-family: 'Courier New', monospace;
            font-weight: bold;
            color: #2c5364 !important;
        }
        
        .col-password {
            font-family: 'Courier New', monospace;
            color: #e74c3c !important;
            background: #fff5f5;
            border-radius: 5px;
            padding: 5px 10px !important;
        }
        
        .col-name {
            font-weight: 600;
            color: #2c3e50 !important;
        }
        
        .col-class {
            display: inline-block;
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white !important;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 0.85em;
            font-weight: bold;
        }
        
        .col-email {
            color: #2980b9 !important;
        }
        
        .col-email a {
            color: #2980b9;
            text-decoration: none;
        }
        
        .col-email a:hover {
            text-decoration: underline;
        }
        
        .col-course {
            display: inline-block;
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            color: white !important;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 0.85em;
            font-weight: bold;
        }
        
        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 5px;
        }
        
        .btn-action {
            padding: 6px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.8em;
            transition: all 0.3s ease;
            text-decoration: none;
            color: white;
        }
        
        .btn-edit {
            background: #f39c12;
        }
        
        .btn-delete {
            background: #e74c3c;
        }
        
        .btn-action:hover {
            transform: scale(1.1);
        }
        
        /* No Results */
        .no-results {
            text-align: center;
            padding: 50px;
            color: #666;
        }
        
        .no-results i {
            font-size: 4em;
            margin-bottom: 20px;
            color: #ddd;
        }
        
        /* Footer */
        footer {
            text-align: center;
            padding: 20px;
            color: white;
            margin-top: 20px;
        }
        
        /* Responsive */
        @media (max-width: 1200px) {
            .table-container {
                overflow-x: auto;
            }
            
            .data-table {
                min-width: 1000px;
            }
        }
        
        @media (max-width: 768px) {
            header h1 {
                font-size: 1.5em;
            }
            
            .filter-form {
                flex-direction: column;
            }
            
            .filter-form input[type="text"] {
                width: 100%;
            }
            
            .stats-bar {
                flex-direction: column;
                align-items: center;
            }
            
            .form-grid {
                grid-template-columns: 1fr;
            }
        }
        
        /* Scroll to top button */
        .scroll-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: linear-gradient(135deg, #2c5364 0%, #203a43 100%);
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: none;
            cursor: pointer;
            font-size: 1.5em;
            box-shadow: 0 5px 20px rgba(0,0,0,0.3);
            transition: all 0.3s ease;
            display: none;
        }
        
        .scroll-top:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>📋 Danh Sách Tài Khoản <span>Sinh Viên</span></h1>
            <p>Dữ liệu từ MySQL Database - Hỗ trợ Upload CSV</p>
            
            <?php if ($dbConnected): ?>
            <div class="stats-bar">
                <div class="stat-item">
                    🗄️ Database: students_db
                </div>
                <div class="stat-item highlight">
                    👥 Tổng: <?php echo $totalStudents; ?> sinh viên
                </div>
                <div class="stat-item">
                    🏫 <?php echo count($classes); ?> lớp học
                </div>
            </div>
            <?php endif; ?>
        </header>
        
        <?php if (!$dbConnected): ?>
        <div class="db-status db-error">
            ❌ <strong>Lỗi kết nối Database!</strong><br>
            <?php echo htmlspecialchars($errorMessage ?? 'Không thể kết nối MySQL'); ?><br>
            <small>Hãy chắc chắn đã chạy file <code>database.sql</code> trong phpMyAdmin</small>
        </div>
        <?php else: ?>
        <div class="db-status db-success">
            ✅ <strong>Kết nối Database thành công!</strong>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($message)): ?>
        <div class="message <?php echo $messageType; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
        <?php endif; ?>
        
        <!-- Upload CSV Section -->
        <div class="upload-section">
            <h3>📤 Upload File CSV để Import vào Database</h3>
            <form method="POST" enctype="multipart/form-data" class="upload-form">
                <input type="file" name="csv_file" accept=".csv" required>
                <button type="submit">📥 Import CSV</button>
            </form>
            <p style="text-align: center; margin-top: 10px; color: #666;">
                <small>File CSV phải có các cột: username, password, lastname, firstname, city, email, course1</small>
            </p>
        </div>
        
        <!-- Student Form (Add/Edit) -->
        <div class="student-form">
            <h3><?php echo $editStudent ? '✏️ Sửa Thông Tin Sinh Viên' : '➕ Thêm Sinh Viên Mới'; ?></h3>
            <form method="POST">
                <input type="hidden" name="action" value="save">
                <input type="hidden" name="edit_id" value="<?php echo $editStudent['id'] ?? 0; ?>">
                
                <div class="form-grid">
                    <div class="form-group">
                        <label>MSSV *:</label>
                        <input type="text" name="username" value="<?php echo htmlspecialchars($editStudent['username'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Mật khẩu *:</label>
                        <input type="text" name="password" value="<?php echo htmlspecialchars($editStudent['password'] ?? 'cse@485A'); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Họ đệm *:</label>
                        <input type="text" name="lastname" value="<?php echo htmlspecialchars($editStudent['lastname'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Tên *:</label>
                        <input type="text" name="firstname" value="<?php echo htmlspecialchars($editStudent['firstname'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Lớp:</label>
                        <input type="text" name="city" value="<?php echo htmlspecialchars($editStudent['city'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label>Email:</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($editStudent['email'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label>Mã học phần:</label>
                        <input type="text" name="course1" value="<?php echo htmlspecialchars($editStudent['course1'] ?? ''); ?>">
                    </div>
                </div>
                
                <div class="form-buttons">
                    <button type="submit" class="btn btn-submit"><?php echo $editStudent ? '💾 Cập Nhật' : '➕ Thêm Mới'; ?></button>
                    <?php if ($editStudent): ?>
                    <a href="bai3.php" class="btn btn-cancel">❌ Hủy</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
        
        <!-- Filter Section -->
        <div class="filter-section">
            <form method="GET" class="filter-form">
                <input type="text" name="search" placeholder="🔍 Tìm kiếm theo tên, MSSV, email..." 
                       value="<?php echo htmlspecialchars($searchTerm); ?>">
                
                <select name="class">
                    <option value="">-- Tất cả lớp --</option>
                    <?php foreach ($classes as $class): ?>
                        <option value="<?php echo htmlspecialchars($class); ?>" <?php echo $classFilter === $class ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($class); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                
                <button type="submit" class="btn btn-search">🔍 Tìm kiếm</button>
                <a href="bai3.php" class="btn btn-reset">🔄 Reset</a>
            </form>
        </div>
        
        <!-- Data Table -->
        <div class="table-container">
            <div class="table-header">
                <h2>📊 Bảng Dữ Liệu Sinh Viên</h2>
                <span class="result-count">
                    Hiển thị: <?php echo count($students); ?> / <?php echo $totalStudents; ?> sinh viên
                </span>
            </div>
            
            <?php if (count($students) > 0): ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Username (MSSV)</th>
                        <th>Password</th>
                        <th>Họ đệm</th>
                        <th>Tên</th>
                        <th>Lớp</th>
                        <th>Email</th>
                        <th>Mã học phần</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $stt = 1;
                    foreach ($students as $student): 
                    ?>
                    <tr>
                        <td><?php echo $stt++; ?></td>
                        <td class="col-username"><?php echo htmlspecialchars($student['username']); ?></td>
                        <td class="col-password"><?php echo htmlspecialchars($student['password']); ?></td>
                        <td class="col-name"><?php echo htmlspecialchars($student['lastname']); ?></td>
                        <td class="col-name"><?php echo htmlspecialchars($student['firstname']); ?></td>
                        <td><span class="col-class"><?php echo htmlspecialchars($student['city']); ?></span></td>
                        <td class="col-email">
                            <a href="mailto:<?php echo htmlspecialchars($student['email']); ?>">
                                <?php echo htmlspecialchars($student['email']); ?>
                            </a>
                        </td>
                        <td><span class="col-course"><?php echo htmlspecialchars($student['course1']); ?></span></td>
                        <td>
                            <div class="action-buttons">
                                <a href="bai3.php?edit=<?php echo $student['id']; ?>" class="btn-action btn-edit" title="Sửa">✏️</a>
                                <a href="bai3.php?delete=<?php echo $student['id']; ?>" class="btn-action btn-delete" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa sinh viên <?php echo htmlspecialchars($student['username']); ?>?')">🗑️</a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <div class="no-results">
                <div style="font-size: 4em;">😔</div>
                <h3>Không tìm thấy kết quả</h3>
                <p>Thử tìm kiếm với từ khóa khác hoặc <a href="bai3.php">xem tất cả</a></p>
            </div>
            <?php endif; ?>
        </div>
        
        <footer>
            <p>© 2025 - Bài tập PHP: Quản lý Sinh viên với MySQL Database</p>
            <p>Hỗ trợ upload file CSV và import vào CSDL</p>
        </footer>
    </div>
    
    <button class="scroll-top" id="scrollTop" onclick="scrollToTop()">⬆️</button>
    
    <script>
        // Scroll to top functionality
        window.onscroll = function() {
            const scrollTopBtn = document.getElementById('scrollTop');
            if (document.body.scrollTop > 300 || document.documentElement.scrollTop > 300) {
                scrollTopBtn.style.display = 'block';
            } else {
                scrollTopBtn.style.display = 'none';
            }
        };
        
        function scrollToTop() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    </script>
</body>
</html>
