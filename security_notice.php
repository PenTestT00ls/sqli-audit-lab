<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>安全提示 - PHP代码审计靶场</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .security-alert {
            border-left: 6px solid #dc3545;
            background: #f8d7da;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .best-practice {
            border-left: 6px solid #28a745;
            background: #d4edda;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .code-example {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1>安全提示与最佳实践</h1>
        
        <div class="security-alert">
            <h4>⚠️ 重要安全提醒</h4>
            <p>此靶场包含故意设计的SQL注入漏洞，仅供学习使用。请遵守以下安全准则：</p>
            <ul>
                <li>不要在真实生产环境中部署此项目</li>
                <li>学习完成后请及时删除项目文件</li>
                <li>仅用于合法的安全研究和教育目的</li>
                <li>遵守相关法律法规</li>
            </ul>
        </div>
        
        <div class="best-practice">
            <h4>🔒 SQL注入防护最佳实践</h4>
            
            <h5>1. 使用预处理语句（Prepared Statements）</h5>
            <div class="code-example">
// ❌ 不安全的写法
$username = $_GET['username'];
$sql = "SELECT * FROM users WHERE username = '$username'";

// ✅ 安全的写法（使用PDO预处理）
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
            </div>
            
            <h5>2. 使用参数化查询</h5>
            <div class="code-example">
// ✅ 安全的写法（使用命名参数）
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
$stmt->execute(['username' => $username]);
            </div>
            
            <h5>3. 输入验证和过滤</h5>
            <div class="code-example">
// 验证数字类型
if (!is_numeric($user_id)) {
    die("无效的用户ID");
}

// 过滤特殊字符
$username = filter_var($username, FILTER_SANITIZE_STRING);
            </div>
            
            <h5>4. 使用ORM框架的安全方法</h5>
            <div class="code-example">
// ThinkPHP安全写法
Db::name('users')->where('username', $username)->find();

// Laravel安全写法
User::where('username', $username)->first();
            </div>
        </div>
        
        <div class="mt-4">
            <h4>📚 学习资源</h4>
            <ul>
                <li><a href="https://owasp.org/www-community/attacks/SQL_Injection" target="_blank">OWASP SQL注入攻击指南</a></li>
                <li><a href="https://cheatsheetseries.owasp.org/cheatsheets/SQL_Injection_Prevention_Cheat_Sheet.html" target="_blank">SQL注入防护备忘单</a></li>
                <li><a href="https://portswigger.net/web-security/sql-injection" target="_blank">PortSwigger SQL注入教程</a></li>
            </ul>
        </div>
        
        <div class="mt-4">
            <a href="index.php" class="btn btn-primary">返回首页</a>
        </div>
    </div>
</body>
</html>