# PHP代码审计靶场 - SQL注入漏洞

这是一个专门用于学习和实践SQL注入漏洞的PHP代码审计靶场项目。项目包含两种不同风格的SQL注入漏洞实现：传统CMS风格和ThinkPHP框架风格。

## 项目特点

- **双靶场设计**：CMS风格和ThinkPHP框架风格两种不同的SQL注入场景
- **真实漏洞模拟**：基于真实世界中的常见SQL注入漏洞模式
- **学习导向**：每个漏洞都配有代码示例和Payload示例
- **安全环境**：仅供学习使用，不包含真实敏感数据

## 靶场内容

### 1. CMS风格SQL注入靶场
模拟传统CMS系统中的SQL注入漏洞：

- **搜索功能注入**：直接拼接用户输入到LIKE查询
- **登录功能注入**：用户名密码直接拼接的登录验证
- **用户信息查询注入**：数字型ID查询注入

### 2. ThinkPHP框架风格SQL注入靶场
模拟ThinkPHP框架中的SQL注入漏洞：

- **where方法注入**：ThinkPHP 3.x风格的where条件注入
- **field方法注入**：SELECT字段拼接注入
- **order方法注入**：ORDER BY子句注入
- **table方法注入**：表名拼接注入
- **find方法注入**：ThinkPHP 5.x风格的find方法注入

## 快速开始

### 环境要求

- PHP 7.0+
- MySQL 5.6+
- Web服务器（Apache/Nginx）

### 安装步骤

1. 克隆或下载项目到Web服务器目录
2. 确保MySQL服务正在运行
3. 访问 `install.php` 运行数据库安装脚本
4. 或手动执行 `setup_database.php` 中的SQL语句
5. 访问 `index.php` 开始使用靶场

### 数据库配置

默认数据库配置（可在 `config/database.php` 中修改）：

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'php_audit_labs');
```

## 数据库结构

### 用户表 (users)
```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### 文章表 (articles)
```sql
CREATE TABLE articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL,
    author_id INT NOT NULL,
    status ENUM('published', 'draft') DEFAULT 'published',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES users(id)
);
```

## 测试数据

安装脚本会自动插入以下测试数据：

### 用户数据
- admin / admin123 (管理员)
- user1 / user123 (普通用户)
- test / test123 (普通用户)

### 文章数据
- 3篇示例文章用于测试搜索功能

## 使用指南

### 对于学习者

1. **从简单开始**：先尝试CMS风格的注入漏洞
2. **理解原理**：查看每个漏洞的代码示例
3. **实践Payload**：使用提供的Payload示例进行测试
4. **循序渐进**：从简单的注入到复杂的框架注入

### 对于教育者

1. **分模块教学**：可以按漏洞类型分模块讲解
2. **对比学习**：对比CMS和框架注入的异同
3. **安全编码**：讲解如何修复这些漏洞

## 安全注意事项

⚠️ **重要提醒**：

- 此靶场仅供学习使用
- 不要在真实环境中部署
- 学习完成后请及时删除项目
- 了解SQL注入的危害和防范措施

## 技术栈

- **前端**：Bootstrap 5.1.3
- **后端**：PHP 7.0+
- **数据库**：MySQL 5.6+
- **安全**：模拟真实漏洞环境

## 贡献

欢迎提交Issue和Pull Request来改进这个靶场项目。

## 许可证

本项目仅用于教育目的，请遵守相关法律法规。

---

**免责声明**：本项目仅供学习SQL注入漏洞的原理和防御方法，请勿用于非法用途。使用者需自行承担相关责任。