<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>数据库设置 - PHP代码审计靶场</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>数据库设置说明</h1>
        
        <div class="card mt-4">
            <div class="card-header">
                <h5>数据库创建SQL</h5>
            </div>
            <div class="card-body">
                <pre><code>CREATE DATABASE IF NOT EXISTS php_audit_labs CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE php_audit_labs;</code></pre>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5>用户表创建SQL</h5>
            </div>
            <div class="card-body">
                <pre><code>CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;</code></pre>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5>文章表创建SQL</h5>
            </div>
            <div class="card-body">
                <pre><code>CREATE TABLE IF NOT EXISTS articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL,
    author_id INT NOT NULL,
    status ENUM('published', 'draft') DEFAULT 'published',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;</code></pre>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5>测试数据插入SQL</h5>
            </div>
            <div class="card-body">
                <pre><code>-- 插入测试用户数据
INSERT INTO users (username, password, email, role) VALUES 
('admin', MD5('admin123'), 'admin@example.com', 'admin'),
('user1', MD5('user123'), 'user1@example.com', 'user'),
('test', MD5('test123'), 'test@example.com', 'user');

-- 插入测试文章数据
INSERT INTO articles (title, content, author_id) VALUES 
('欢迎来到PHP审计靶场', '这是一个用于学习SQL注入漏洞的靶场环境。', 1),
('SQL注入基础教程', '学习如何识别和利用SQL注入漏洞。', 2),
('ThinkPHP框架安全', '了解ThinkPHP框架中的安全注意事项。', 3);</code></pre>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5>一键安装脚本</h5>
            </div>
            <div class="card-body">
                <a href="install.php" class="btn btn-primary">运行数据库安装脚本</a>
                <p class="mt-2 text-muted">注意：请确保MySQL服务正在运行，并且配置正确的数据库连接信息。</p>
            </div>
        </div>

        <div class="mt-4">
            <a href="index.php" class="btn btn-secondary">返回首页</a>
        </div>
    </div>
</body>
</html>