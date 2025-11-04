<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP代码审计靶场 - SQL注入漏洞</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Microsoft YaHei', sans-serif;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 3rem 0;
            margin-bottom: 2rem;
        }
        .vulnerability-card {
            transition: transform 0.3s;
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .vulnerability-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.2);
        }
        .cms-card {
            border-left: 4px solid #28a745;
        }
        .thinkphp-card {
            border-left: 4px solid #dc3545;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <h1 class="display-4">PHP代码审计靶场</h1>
            <p class="lead">SQL注入漏洞实战演练平台</p>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card vulnerability-card cms-card">
                    <div class="card-body">
                        <h5 class="card-title">CMS风格SQL注入靶场</h5>
                        <p class="card-text">模拟传统CMS系统中的SQL注入漏洞，包含多种注入场景。</p>
                        <a href="cms_sql_injection.php" class="btn btn-success">进入靶场</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card vulnerability-card thinkphp-card">
                    <div class="card-body">
                        <h5 class="card-title">ThinkPHP框架SQL注入靶场</h5>
                        <p class="card-text">模拟ThinkPHP框架中的SQL注入漏洞，包含ORM相关注入场景。</p>
                        <a href="thinkphp_sql_injection.php" class="btn btn-danger">进入靶场</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>数据库配置信息</h5>
                    </div>
                    <div class="card-body">
                        <p>请确保已创建数据库并导入表结构：</p>
                        <pre><code>数据库名：php_audit_labs
用户名：root
密码：（根据您的MySQL配置）</code></pre>
                        <a href="setup_database.php" class="btn btn-primary">查看数据库设置</a>
                        <a href="install.php" class="btn btn-success ms-2">一键安装</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>安全提示</h5>
                    </div>
                    <div class="card-body">
                        <p>此靶场仅供学习使用，请遵守安全准则：</p>
                        <ul>
                            <li>不要在真实环境中部署</li>
                            <li>学习完成后及时删除</li>
                            <li>仅用于合法的安全研究</li>
                        </ul>
                        <a href="security_notice.php" class="btn btn-warning">查看安全提示</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>