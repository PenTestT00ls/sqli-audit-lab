<?php
require_once 'config/database.php';

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>数据库安装 - PHP代码审计靶场</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>数据库安装脚本</h1>
        
        <?php
        try {
            // 测试数据库连接
            $pdo = new PDO("mysql:host=" . DB_HOST, DB_USER, DB_PASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            echo '<div class="alert alert-success">数据库连接成功！</div>';
            
            // 创建数据库
            $pdo->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            echo '<div class="alert alert-success">数据库创建成功：' . DB_NAME . '</div>';
            
            // 切换到目标数据库
            $pdo->exec("USE " . DB_NAME);
            
            // 创建用户表
            $pdo->exec("
                CREATE TABLE IF NOT EXISTS users (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    username VARCHAR(50) NOT NULL UNIQUE,
                    password VARCHAR(255) NOT NULL,
                    email VARCHAR(100) NOT NULL,
                    role ENUM('admin', 'user') DEFAULT 'user',
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
            ");
            echo '<div class="alert alert-success">用户表创建成功</div>';
            
            // 创建文章表
            $pdo->exec("
                CREATE TABLE IF NOT EXISTS articles (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    title VARCHAR(200) NOT NULL,
                    content TEXT NOT NULL,
                    author_id INT NOT NULL,
                    status ENUM('published', 'draft') DEFAULT 'published',
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (author_id) REFERENCES users(id)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
            ");
            echo '<div class="alert alert-success">文章表创建成功</div>';
            
            // 插入测试数据
            $pdo->exec("
                INSERT IGNORE INTO users (username, password, email, role) VALUES 
                ('admin', MD5('admin123'), 'admin@example.com', 'admin'),
                ('user1', MD5('user123'), 'user1@example.com', 'user'),
                ('test', MD5('test123'), 'test@example.com', 'user')
            ");
            echo '<div class="alert alert-success">测试用户数据插入成功</div>';
            
            $pdo->exec("
                INSERT IGNORE INTO articles (title, content, author_id) VALUES 
                ('欢迎来到PHP审计靶场', '这是一个用于学习SQL注入漏洞的靶场环境。', 1),
                ('SQL注入基础教程', '学习如何识别和利用SQL注入漏洞。', 2),
                ('ThinkPHP框架安全', '了解ThinkPHP框架中的安全注意事项。', 3)
            ");
            echo '<div class="alert alert-success">测试文章数据插入成功</div>';
            
            echo '<div class="alert alert-info">数据库安装完成！</div>';
            
        } catch (PDOException $e) {
            echo '<div class="alert alert-danger">安装失败：' . $e->getMessage() . '</div>';
        }
        ?>
        
        <div class="mt-4">
            <a href="index.php" class="btn btn-primary">返回首页</a>
            <a href="setup_database.php" class="btn btn-secondary">查看SQL脚本</a>
        </div>
    </div>
</body>
</html>