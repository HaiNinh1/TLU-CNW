<?php
// Đọc file CSV
$filename = '65HTTT_Danh_sach_diem_danh.csv';
$students = [];
$headers = [];

if (file_exists($filename)) {
    $file = fopen($filename, 'r');
    
    // Đọc dòng header
    if (($header = fgetcsv($file)) !== false) {
        $headers = $header;
    }
    
    // Đọc các dòng dữ liệu
    while (($row = fgetcsv($file)) !== false) {
        if (count($row) == count($headers)) {
            $students[] = array_combine($headers, $row);
        }
    }
    
    fclose($file);
}

$totalStudents = count($students);

// Tìm kiếm
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
$filteredStudents = $students;

if (!empty($searchTerm)) {
    $filteredStudents = array_filter($students, function($student) use ($searchTerm) {
        foreach ($student as $value) {
            if (stripos($value, $searchTerm) !== false) {
                return true;
            }
        }
        return false;
    });
}

// Lọc theo lớp
$classFilter = isset($_GET['class']) ? $_GET['class'] : '';
if (!empty($classFilter)) {
    $filteredStudents = array_filter($filteredStudents, function($student) use ($classFilter) {
        return $student['city'] === $classFilter;
    });
}

// Lấy danh sách các lớp để filter
$classes = array_unique(array_column($students, 'city'));
sort($classes);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Tài Khoản Sinh Viên</title>
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
        
        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 25px;
            font-size: 1em;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-search {
            background: linear-gradient(135deg, #2c5364 0%, #203a43 100%);
            color: white;
        }
        
        .btn-search:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(44, 83, 100, 0.4);
        }
        
        .btn-reset {
            background: #e74c3c;
            color: white;
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
        }
        
        .btn-view {
            background: #3498db;
            color: white;
        }
        
        .btn-edit {
            background: #f39c12;
            color: white;
        }
        
        .btn-delete {
            background: #e74c3c;
            color: white;
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
        
        /* Highlight search term */
        .highlight {
            background: yellow;
            padding: 2px 4px;
            border-radius: 3px;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>📋 Danh Sách Tài Khoản <span>Sinh Viên</span></h1>
            <p>Đọc từ file CSV - Tiền đề cho hoạt động lưu vào CSDL</p>
            
            <div class="stats-bar">
                <div class="stat-item">
                    📁 File: <?php echo $filename; ?>
                </div>
                <div class="stat-item highlight">
                    👥 Tổng: <?php echo $totalStudents; ?> sinh viên
                </div>
                <div class="stat-item">
                    🏫 <?php echo count($classes); ?> lớp học
                </div>
            </div>
        </header>
        
        <!-- Filter Section -->
        <div class="filter-section">
            <form method="GET" class="filter-form">
                <input type="text" name="search" placeholder="🔍 Tìm kiếm theo tên, MSSV, email..." 
                       value="<?php echo htmlspecialchars($searchTerm); ?>">
                
                <select name="class">
                    <option value="">-- Tất cả lớp --</option>
                    <?php foreach ($classes as $class): ?>
                        <option value="<?php echo $class; ?>" <?php echo $classFilter === $class ? 'selected' : ''; ?>>
                            <?php echo $class; ?>
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
                    Hiển thị: <?php echo count($filteredStudents); ?> / <?php echo $totalStudents; ?> sinh viên
                </span>
            </div>
            
            <?php if (count($filteredStudents) > 0): ?>
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
                    foreach ($filteredStudents as $student): 
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
                                <button class="btn-action btn-view" onclick="viewStudent('<?php echo $student['username']; ?>')" title="Xem">👁️</button>
                                <button class="btn-action btn-edit" onclick="editStudent('<?php echo $student['username']; ?>')" title="Sửa">✏️</button>
                                <button class="btn-action btn-delete" onclick="deleteStudent('<?php echo $student['username']; ?>')" title="Xóa">🗑️</button>
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
            <p>© 2025 - Bài tập PHP: Đọc và Hiển thị File CSV</p>
            <p>Tiền đề cho hoạt động lưu vào CSDL (MySQL)</p>
        </footer>
    </div>
    
    <button class="scroll-top" id="scrollTop" onclick="scrollToTop()">⬆️</button>
    
    <script>
        // View student details
        function viewStudent(username) {
            alert('Xem chi tiết sinh viên: ' + username + '\n\n(Tính năng sẽ được phát triển khi kết nối CSDL)');
        }
        
        // Edit student
        function editStudent(username) {
            alert('Sửa thông tin sinh viên: ' + username + '\n\n(Tính năng sẽ được phát triển khi kết nối CSDL)');
        }
        
        // Delete student
        function deleteStudent(username) {
            if (confirm('Bạn có chắc chắn muốn xóa sinh viên ' + username + '?')) {
                alert('Xóa sinh viên: ' + username + '\n\n(Tính năng sẽ được phát triển khi kết nối CSDL)');
            }
        }
        
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
