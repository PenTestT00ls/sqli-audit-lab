<?php
require_once 'config/database.php';

header('Content-Type: text/html; charset=utf-8');

$result = '';
$sql = '';

// 模拟CMS风格的SQL注入漏洞
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
    $action = $_GET['action'];
    $pdo = getDbConnection();
    
    switch ($action) {
        case 'search':
            // 漏洞1：直接拼接用户输入到SQL查询
            if (isset($_GET['keyword'])) {
                $keyword = $_GET['keyword'];
                $sql = "SELECT a.*, u.username as author_name FROM articles a LEFT JOIN users u ON a.author_id = u.id WHERE a.title LIKE '%$keyword%' OR a.content LIKE '%$keyword%'";
                
                try {
                    $stmt = $pdo->query($sql);
                    $articles = $stmt->fetchAll();
                    $result = "找到 " . count($articles) . " 篇文章";
                    
                    // 显示文章详情
                    if (count($articles) > 0) {
                        $result .= "<div class='mt-3'>";
                        $result .= "<h6>搜索结果：</h6>";
                        foreach ($articles as $article) {
                            $result .= "<div class='card mb-2'>";
                            $result .= "<div class='card-body p-2'>";
                            $result .= "<strong>" . htmlspecialchars($article['title']) . "</strong><br>";
                            $result .= "<small class='text-muted'>作者: " . htmlspecialchars($article['author_name']) . " | 时间: " . $article['created_at'] . "</small>";
                            $result .= "</div>";
                            $result .= "</div>";
                        }
                        $result .= "</div>";
                    }
                } catch (Exception $e) {
                    $result = "查询错误: " . $e->getMessage();
                }
            }
            break;
            
        case 'login':
            // 漏洞2：登录SQL注入
            if (isset($_GET['username']) && isset($_GET['password'])) {
                $username = $_GET['username'];
                $password = $_GET['password'];
                $sql = "SELECT * FROM users WHERE username = '$username' AND password = MD5('$password')";
                
                try {
                    $stmt = $pdo->query($sql);
                    $user = $stmt->fetch();
                    if ($user) {
                        $result = "<div class='alert alert-success'>";
                        $result .= "<h5><i class='fas fa-check-circle'></i> 登录成功！</h5>";
                        $result .= "<p>欢迎 <strong>" . htmlspecialchars($user['username']) . "</strong> 访问系统</p>";
                        $result .= "<hr>";
                        $result .= "<div class='row'>";
                        $result .= "<div class='col-md-6'>";
                        $result .= "<p><strong>用户信息：</strong></p>";
                        $result .= "<ul class='list-unstyled'>";
                        $result .= "<li><strong>用户ID：</strong> " . $user['id'] . "</li>";
                        $result .= "<li><strong>邮箱：</strong> " . htmlspecialchars($user['email']) . "</li>";
                        $result .= "<li><strong>角色：</strong> <span class='badge bg-primary'>" . $user['role'] . "</span></li>";
                        $result .= "<li><strong>创建时间：</strong> " . $user['created_at'] . "</li>";
                        $result .= "</ul>";
                        $result .= "</div>";
                        $result .= "<div class='col-md-6'>";
                        $result .= "<p><strong>权限信息：</strong></p>";
                        if ($user['role'] === 'admin') {
                            $result .= "<div class='alert alert-warning'>";
                            $result .= "<small><strong>管理员权限：</strong>可以访问所有功能，管理用户和系统设置</small>";
                            $result .= "</div>";
                        } else {
                            $result .= "<div class='alert alert-info'>";
                            $result .= "<small><strong>普通用户权限：</strong>可以查看公开内容和个人信息</small>";
                            $result .= "</div>";
                        }
                        $result .= "</div>";
                        $result .= "</div>";
                        $result .= "<div class='alert alert-warning mt-2'>";
                        $result .= "<small><strong>安全提示：</strong>此登录存在SQL注入漏洞，实际应用中应使用参数化查询</small>";
                        $result .= "</div>";
                        $result .= "</div>";
                    } else {
                        $result = "<div class='alert alert-danger'>";
                        $result .= "<h5><i class='fas fa-exclamation-triangle'></i> 登录失败</h5>";
                        $result .= "<p>用户名或密码错误，请检查输入</p>";
                        $result .= "</div>";
                    }
                } catch (Exception $e) {
                    $result = "登录错误: " . $e->getMessage();
                }
            }
            break;
            
        case 'user_info':
            // 漏洞3：用户信息查询注入
            if (isset($_GET['user_id'])) {
                $user_id = $_GET['user_id'];
                $sql = "SELECT * FROM users WHERE id = $user_id";
                
                try {
                    $stmt = $pdo->query($sql);
                    $user = $stmt->fetch();
                    if ($user) {
                        $result = "用户信息详情";
                        $result .= "<div class='mt-2'>";
                        $result .= "<div class='card'>";
                        $result .= "<div class='card-body'>";
                        $result .= "<h6>" . htmlspecialchars($user['username']) . "</h6>";
                        $result .= "<p><strong>用户ID:</strong> " . $user['id'] . "</p>";
                        $result .= "<p><strong>邮箱:</strong> " . htmlspecialchars($user['email']) . "</p>";
                        $result .= "<p><strong>角色:</strong> " . $user['role'] . "</p>";
                        $result .= "<p><strong>创建时间:</strong> " . $user['created_at'] . "</p>";
                        $result .= "</div>";
                        $result .= "</div>";
                        $result .= "</div>";
                    } else {
                        $result = "用户不存在";
                    }
                } catch (Exception $e) {
                    $result = "查询错误: " . $e->getMessage();
                }
            }
            break;
    }
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMS风格SQL注入靶场</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .vulnerability-section {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .code-block {
            background: #f8f9fa;
            border-left: 4px solid #007bff;
            padding: 15px;
            margin: 10px 0;
            font-family: 'Courier New', monospace;
            white-space: pre-wrap;
        }
        .payload-example {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 10px;
            margin: 10px 0;
            font-family: 'Courier New', monospace;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1>CMS风格SQL注入靶场</h1>
        <p class="lead">模拟传统CMS系统中的SQL注入漏洞</p>
        
        <div class="alert alert-warning">
            <strong>注意：</strong> 此页面包含故意设计的SQL注入漏洞，仅供学习使用。
        </div>
        
        <?php if ($result): ?>
        <div class="alert alert-info">
            <strong>执行结果：</strong> <?php echo $result; ?>
            <?php if ($sql): ?>
                <div class="mt-2">
                    <strong>执行的SQL：</strong>
                    <code><?php echo htmlspecialchars($sql); ?></code>
                </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <!-- 漏洞1：搜索功能注入 -->
        <div class="vulnerability-section">
            <h3>漏洞1：搜索功能SQL注入</h3>
            <p>典型的CMS搜索功能，直接拼接用户输入到SQL查询中。</p>
            
            <div class="code-block">
// 漏洞代码示例
$keyword = $_GET['keyword'];
$sql = "SELECT * FROM articles WHERE title LIKE '%$keyword%' OR content LIKE '%$keyword%'";
            </div>
            
            <form method="GET" class="mb-3">
                <input type="hidden" name="action" value="search">
                <div class="input-group">
                    <input type="text" name="keyword" class="form-control" placeholder="输入搜索关键词" value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>">
                    <button type="submit" class="btn btn-primary">搜索</button>
                </div>
            </form>
            
            <div class="payload-example">
                <strong>Payload示例：</strong><br>
                • 正常搜索: php<br>
                • 联合查询: php' order by 8 -- <br>
                • 报错注入: test' AND 1=updatexml(1,concat(0x7e,(SELECT @@version),0x7e),1) -- 
            </div>
        </div>
        
        <!-- 漏洞2：登录功能注入 -->
        <div class="vulnerability-section">
            <h3>漏洞2：登录功能SQL注入</h3>
            <p>典型的CMS登录功能，用户名和密码直接拼接。</p>
            
            <div class="code-block">
// 漏洞代码示例
$username = $_GET['username'];
$password = $_GET['password'];
$sql = "SELECT * FROM users WHERE username = '$username' AND password = MD5('$password')";
            </div>
            
            <form method="GET" class="mb-3">
                <input type="hidden" name="action" value="login">
                <div class="row">
                    <div class="col-md-6">
                        <input type="text" name="username" class="form-control mb-2" placeholder="用户名" value="<?php echo isset($_GET['username']) ? htmlspecialchars($_GET['username']) : ''; ?>">
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="password" class="form-control mb-2" placeholder="密码" value="<?php echo isset($_GET['password']) ? htmlspecialchars($_GET['password']) : ''; ?>">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">登录</button>
            </form>
            
            <div class="payload-example">
                <strong>Payload示例：</strong><br>
                • 万能密码: admin' OR '1'='1<br>
                • 注释绕过: admin' -- <br>
                • 联合查询: ' UNION SELECT 1,'admin',MD5('123'),'admin@test.com','admin',NOW() -- 
            </div>
        </div>
        
        <!-- 漏洞3：用户信息查询注入 -->
        <div class="vulnerability-section">
            <h3>漏洞3：用户信息查询SQL注入</h3>
            <p>通过用户ID查询用户信息，数字型注入。</p>
            
            <div class="code-block">
// 漏洞代码示例
$user_id = $_GET['user_id'];
$sql = "SELECT * FROM users WHERE id = $user_id";
            </div>
            
            <form method="GET" class="mb-3">
                <input type="hidden" name="action" value="user_info">
                <div class="input-group">
                    <input type="text" name="user_id" class="form-control" placeholder="输入用户ID" value="<?php echo isset($_GET['user_id']) ? htmlspecialchars($_GET['user_id']) : ''; ?>">
                    <button type="submit" class="btn btn-primary">查询</button>
                </div>
            </form>
            
            <div class="payload-example">
                <strong>Payload示例：</strong><br>
                • 正常查询: 1<br>
                • 联合查询: 1 UNION SELECT 1,2,3,4,5<br>
                • 布尔盲注: 1 AND (SELECT COUNT(*) FROM users) > 0<br>
                • 报错注入: 1 AND extractvalue(1, concat(0x7e, (SELECT user()), 0x7e))
            </div>
        </div>
        
        <div class="mt-4">
            <a href="index.php" class="btn btn-secondary">返回首页</a>
        </div>
    </div>
</body>
</html>