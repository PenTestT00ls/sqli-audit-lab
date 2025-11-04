-- PHP代码审计靶场 - 数据库建表SQL脚本
-- 创建时间: 2024年
-- 用途: SQL注入漏洞学习靶场

-- 创建数据库
CREATE DATABASE IF NOT EXISTS php_audit_labs 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

-- 使用数据库
USE php_audit_labs;

-- 创建用户表
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE COMMENT '用户名',
    password VARCHAR(255) NOT NULL COMMENT '密码(MD5加密)',
    email VARCHAR(100) NOT NULL COMMENT '邮箱',
    role ENUM('admin', 'user') DEFAULT 'user' COMMENT '用户角色',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户表';

-- 创建文章表
CREATE TABLE IF NOT EXISTS articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL COMMENT '文章标题',
    content TEXT NOT NULL COMMENT '文章内容',
    author_id INT NOT NULL COMMENT '作者ID',
    status ENUM('published', 'draft') DEFAULT 'published' COMMENT '文章状态',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='文章表';

-- 插入测试用户数据
INSERT IGNORE INTO users (username, password, email, role) VALUES 
('admin', MD5('admin123'), 'admin@example.com', 'admin'),
('user1', MD5('user123'), 'user1@example.com', 'user'),
('test', MD5('test123'), 'test@example.com', 'user'),
('demo', MD5('demo123'), 'demo@example.com', 'user'),
('guest', MD5('guest123'), 'guest@example.com', 'user');

-- 插入测试文章数据
INSERT IGNORE INTO articles (title, content, author_id) VALUES 
('欢迎来到PHP审计靶场', '这是一个专门用于学习SQL注入漏洞的靶场环境。通过实践不同类型的SQL注入攻击，帮助开发者更好地理解Web安全的重要性。', 1),
('SQL注入基础教程', 'SQL注入是最常见的Web安全漏洞之一。攻击者通过在用户输入中插入恶意SQL代码，可以绕过认证、窃取数据甚至控制数据库服务器。', 2),
('ThinkPHP框架安全指南', 'ThinkPHP作为流行的PHP框架，在使用过程中需要注意一些安全事项，特别是早期版本中存在的一些SQL注入漏洞。', 3),
('CMS系统安全防护', '传统CMS系统由于开发时间较早，往往存在各种安全漏洞。学习这些漏洞有助于提高代码安全意识。', 4),
('Web安全最佳实践', '了解常见的Web安全漏洞和防护措施，是每个Web开发者必备的技能。', 5);

-- 创建日志表（可选，用于记录攻击尝试）
CREATE TABLE IF NOT EXISTS attack_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip_address VARCHAR(45) NOT NULL COMMENT 'IP地址',
    user_agent TEXT COMMENT '用户代理',
    attack_type VARCHAR(50) NOT NULL COMMENT '攻击类型',
    payload TEXT COMMENT '攻击载荷',
    target_url VARCHAR(500) COMMENT '目标URL',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '记录时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='攻击日志表';

-- 显示表结构信息
SHOW TABLES;

-- 显示用户表结构
DESCRIBE users;

-- 显示文章表结构
DESCRIBE articles;

-- 查询测试数据
SELECT '用户数据:' AS '信息';
SELECT id, username, email, role, created_at FROM users;

SELECT '文章数据:' AS '信息';
SELECT a.id, a.title, u.username as author, a.created_at 
FROM articles a 
LEFT JOIN users u ON a.author_id = u.id;

-- 数据库使用统计
SELECT 
    (SELECT COUNT(*) FROM users) as total_users,
    (SELECT COUNT(*) FROM articles) as total_articles,
    (SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = 'php_audit_labs') as total_tables;