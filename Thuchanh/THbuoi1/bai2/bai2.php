<?php
// Kết nối Database
require_once 'config.php';

// Lấy câu hỏi từ database
function getQuestionsFromDB($pdo) {
    $questions = [];
    
    // Lấy tất cả câu hỏi
    $stmt = $pdo->query("SELECT * FROM questions ORDER BY id");
    $questionsData = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($questionsData as $q) {
        // Lấy các đáp án cho mỗi câu hỏi
        $stmtOptions = $pdo->prepare("SELECT * FROM options WHERE question_id = ? ORDER BY option_letter");
        $stmtOptions->execute([$q['id']]);
        $optionsData = $stmtOptions->fetchAll(PDO::FETCH_ASSOC);
        
        $options = [];
        $correctAnswers = [];
        
        foreach ($optionsData as $opt) {
            $options[$opt['option_letter']] = $opt['option_text'];
            if ($opt['is_correct'] == 1) {
                $correctAnswers[] = $opt['option_letter'];
            }
        }
        
        $questions[] = [
            'id' => $q['id'],
            'text' => $q['question_text'],
            'is_multiple' => $q['is_multiple'],
            'options' => $options,
            'answer' => $correctAnswers
        ];
    }
    
    return $questions;
}

// Lấy câu hỏi từ database
try {
    $questions = getQuestionsFromDB($pdo);
    $dbConnected = true;
} catch (Exception $e) {
    $questions = [];
    $dbConnected = false;
    $errorMessage = $e->getMessage();
}

$totalQuestions = count($questions);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Câu Hỏi Trắc Nghiệm - Android Development</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            padding: 20px;
            line-height: 1.6;
        }
        
        .container {
            max-width: 900px;
            margin: 0 auto;
        }
        
        header {
            text-align: center;
            padding: 20px;
            background: #2196F3;
            color: white;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        header h1 {
            font-size: 1.8em;
            margin-bottom: 5px;
        }
        
        .question-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            border-left: 4px solid #2196F3;
        }
        
        .question-header {
            display: flex;
            align-items: flex-start;
            margin-bottom: 15px;
        }
        
        .question-number {
            background: #2196F3;
            color: white;
            width: 30px;
            height: 30px;
            line-height: 30px;
            text-align: center;
            border-radius: 50%;
            font-weight: bold;
            font-size: 0.9em;
            margin-right: 10px;
            flex-shrink: 0;
        }
        
        .question-text {
            font-size: 1em;
            color: #333;
            font-weight: 500;
        }
        
        .options-list {
            list-style: none;
            margin-bottom: 15px;
        }
        
        .option-item {
            padding: 10px 15px;
            margin: 5px 0;
            background: #f8f9fa;
            border-radius: 5px;
            display: flex;
            align-items: center;
        }
        
        .option-letter {
            background: #e0e0e0;
            color: #333;
            width: 25px;
            height: 25px;
            line-height: 25px;
            text-align: center;
            border-radius: 50%;
            font-weight: bold;
            font-size: 0.85em;
            margin-right: 10px;
            flex-shrink: 0;
        }
        
        .option-text {
            color: #555;
        }
        
        .answer-box {
            background: #e8f5e9;
            border: 1px solid #4CAF50;
            border-radius: 5px;
            padding: 10px 15px;
            display: flex;
            align-items: center;
        }
        
        .answer-label {
            font-weight: bold;
            color: #2e7d32;
            margin-right: 10px;
        }
        
        .answer-value {
            color: #1b5e20;
            font-weight: 600;
        }
        
        footer {
            text-align: center;
            padding: 20px;
            color: #666;
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
        
        .multi-badge {
            background: #ff9800;
            color: white;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 0.75em;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>📱 Câu Hỏi Trắc Nghiệm Android</h1>
            <p>Tổng số: <?php echo $totalQuestions; ?> câu hỏi | 🗄️ Dữ liệu từ MySQL</p>
        </header>
        
        <?php if (!$dbConnected): ?>
        <div class="db-status db-error">
            ❌ <strong>Lỗi kết nối Database!</strong><br>
            <?php echo htmlspecialchars($errorMessage ?? 'Không thể kết nối MySQL'); ?><br>
            <small>Hãy chắc chắn đã chạy file <code>database.sql</code> trong phpMyAdmin</small>
        </div>
        <?php else: ?>
        <div class="db-status db-success">
            ✅ <strong>Kết nối Database thành công!</strong> - Đang hiển thị <?php echo $totalQuestions; ?> câu hỏi từ MySQL
        </div>
        <?php endif; ?>
        
        <?php foreach ($questions as $q): ?>
        <div class="question-card">
            <div class="question-header">
                <span class="question-number"><?php echo $q['id']; ?></span>
                <span class="question-text">
                    <?php echo htmlspecialchars($q['text']); ?>
                    <?php if ($q['is_multiple']): ?>
                        <span class="multi-badge">Nhiều đáp án</span>
                    <?php endif; ?>
                </span>
            </div>
            
            <ul class="options-list">
                <?php foreach ($q['options'] as $letter => $optionText): ?>
                <li class="option-item">
                    <span class="option-letter"><?php echo $letter; ?></span>
                    <span class="option-text"><?php echo htmlspecialchars($optionText); ?></span>
                </li>
                <?php endforeach; ?>
            </ul>
            
            <div class="answer-box">
                <span class="answer-label">✅ Đáp án:</span>
                <span class="answer-value"><?php echo implode(', ', $q['answer']); ?></span>
            </div>
        </div>
        <?php endforeach; ?>
        
        <footer>
            <p>© 2025 - Câu Hỏi Trắc Nghiệm Android Development</p>
        </footer>
    </div>
</body>
</html>
