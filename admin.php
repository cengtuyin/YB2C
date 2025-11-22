<?php
// 未登录或登录无效会自动处理
include_once("./config.php");

$UserData = getUserData();

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QWall管理系统 - 控制面板</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f2f5;
        }
        .header {
            background-color: #2c3e50;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .welcome {
            font-size: 18px;
        }
        .logout-btn {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }
        .logout-btn:hover {
            background-color: #c0392b;
        }
        .container {
            padding: 20px;
        }
        .dashboard-card {
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        .dashboard-title {
            color: #2c3e50;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="datetime-local"],
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .form-group textarea {
            height: 100px;
        }
        .form-group input[type="checkbox"] {
            margin-right: 5px;
        }
        .btn {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 10px;
        }
        .btn:hover {
            background-color: #2980b9;
        }
        .btn-danger {
            background-color: #e74c3c;
        }
        .btn-danger:hover {
            background-color: #c0392b;
        }
        .wall-list {
            margin-top: 20px;
        }
        .wall-item {
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 10px;
            background-color: #f9f9f9;
        }
        .wall-item h3 {
            margin-top: 0;
            color: #2c3e50;
        }
        .message {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
            margin-right: 8px;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="welcome">
            QWall管理系统 - 控制面板
        </div>
        <a href="logout.php" class="logout-btn">退出登录</a>
    </div>
    
    <div class="container">
        <div class="dashboard-card">
            <h2 class="dashboard-title">系统概览</h2>
            <p>欢迎使用QWall管理系统。您已成功登录。</p>
            <div>
                <p><strong>用户名:</strong> <?php echo htmlspecialchars($UserData['name']); ?></p>
                <p><strong>QQ:</strong> <?php echo htmlspecialchars($UserData['qq']); ?></p>
            </div>
        </div>
        
        <div class="dashboard-card">
            <h2 class="dashboard-title">墙信息管理</h2>
            
            <div id="messageContainer"></div>
            
            <h3>添加/编辑墙信息</h3>
            <form id="wallForm">
                <input type="hidden" name="action" value="add_edit_wall">
                <input type="hidden" name="wall_id" id="wall_id" value="">
                
                <div class="form-group">
                    <label for="platform">名称</label>
                    <input type="text" id="platform" name="platform" required>
                </div>
                
                <div class="form-group">
                    <label for="status">状态</label>
                    <select id="status" name="status" required>
                        <option value="正常">正常</option>
                        <option value="停运">停运</option>
                        <option value="封禁">封禁</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="qqnum">墙QQ号</label>
                    <input type="text" id="qqnum" name="qqnum" required>
                </div>
                
                <div class="form-group">
                    <label for="contact">负责人联系方式</label>
                    <input type="text" id="contact" name="contact" required>
                </div>
                
                <div class="form-group">
                    <label for="operator_type">运营方类型</label>
                    <select id="operator_type" name="operator_type" required>
                        <option value="个人">个人</option>
                        <option value="团队">团队</option>
                        <option value="公司">公司</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="register_time">登记时间</label>
                    <input type="datetime-local" id="register_time" name="register_time" required>
                </div>
                
                <div class="form-group">
                    <label for="details">详情</label>
                    <textarea id="details" name="details" required></textarea>
                </div>
                
                <button type="submit" class="btn" id="submitBtn">
                    <span class="loading" id="loadingSpinner" style="display: none;"></span>
                    <span id="buttonText">保存墙信息</span>
                </button>
                <button type="button" class="btn" onclick="resetForm()">重置表单</button>
            </form>
            
            <div class="wall-list">
                <h3>墙信息列表</h3>
                <div id="wallsList">
                    <p>加载中...</p>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // 全局变量
        let wallsData = [];
        
        // 页面加载完成后执行
        document.addEventListener('DOMContentLoaded', function() {
            // 加载墙信息列表
            loadWalls();
            
            // 表单提交事件
            document.getElementById('wallForm').addEventListener('submit', function(e) {
                e.preventDefault();
                saveWall();
            });
        });
        
        // 加载墙信息列表
        async function loadWalls() {
            try {
                const response = await fetch('api/walls.php');
                const result = await response.json();
                
                if (result.code == 200) {
                    wallsData = result.data;
                    renderWallsList();
                } else {
                    showMessage(result.msg || '加载墙信息失败', 'error');
                }
            } catch (error) {
                console.error('加载墙信息失败:', error);
                showMessage('网络错误，请稍后重试', 'error');
            }
        }
        
        // 渲染墙信息列表
        function renderWallsList() {
            const wallsList = document.getElementById('wallsList');
            
            if (wallsData.length === 0) {
                wallsList.innerHTML = '<p>暂无墙信息</p>';
                return;
            }
            
            let html = '';
            wallsData.forEach(wall => {
                html += `
                    <div class="wall-item" id="wall-${wall.id}">
                        <p><strong>名称:</strong> ${escapeHtml(wall.platform)}</p>
                        <p><strong>状态:</strong> ${escapeHtml(wall.status)}</p>
                        <p><strong>QQ号：</strong> ${escapeHtml(wall.qqnum)}</p>
                        <p><strong>联系方式:</strong> ${escapeHtml(wall.contact)}</p>
                        <p><strong>运营方:</strong> ${escapeHtml(wall.operator_type)}</p>
                        <p><strong>登记时间:</strong> ${escapeHtml(wall.register_time)}</p>
                        <p><strong>详情:</strong> ${escapeHtml(wall.details)}</p>
                        
                        <div style="margin-top: 10px;">
                            <button type="button" class="btn" onclick="editWall(${wall.id})">编辑</button>
                            <button type="button" class="btn btn-danger" onclick="deleteWall(${wall.id})">删除</button>
                        </div>
                    </div>
                `;
            });
            
            wallsList.innerHTML = html;
        }
        
        // 保存墙信息
        async function saveWall() {
            const submitBtn = document.getElementById('submitBtn');
            const buttonText = document.getElementById('buttonText');
            const loadingSpinner = document.getElementById('loadingSpinner');
            
            // 显示加载状态
            submitBtn.disabled = true;
            loadingSpinner.style.display = 'inline-block';
            buttonText.textContent = '保存中...';
            
            try {
                // 获取表单数据
                const formData = new FormData(document.getElementById('wallForm'));
                const wallData = Object.fromEntries(formData.entries());
                
                // 发送请求
                const response = await fetch('api/walls.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(wallData)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showMessage(result.message || '保存成功', 'success');
                    resetForm();
                    loadWalls(); // 重新加载列表
                } else {
                    showMessage(result.message || '保存失败', 'error');
                }
            } catch (error) {
                console.error('保存墙信息失败:', error);
                showMessage('网络错误，请稍后重试', 'error');
            } finally {
                // 恢复按钮状态
                submitBtn.disabled = false;
                loadingSpinner.style.display = 'none';
                buttonText.textContent = '保存墙信息';
            }
        }
        
        // 编辑墙信息
        function editWall(wallId) {
            const wall = wallsData.find(w => w.id == wallId);
            if (!wall) {
                showMessage('未找到指定的墙信息', 'error');
                return;
            }
            
            // 填充表单
            document.getElementById('wall_id').value = wall.id;
            document.getElementById('platform').value = wall.platform;
            document.getElementById('status').value = wall.status;
            document.getElementById('qqnum').value = wall.qqnum;
            document.getElementById('contact').value = wall.contact;
            document.getElementById('operator_type').value = wall.operator_type;
            document.getElementById('register_time').value = wall.register_time.replace(' ', 'T');
            document.getElementById('details').value = wall.details;
            
            // 滚动到表单
            document.getElementById('wallForm').scrollIntoView({ behavior: 'smooth' });
            
            showMessage('已加载墙信息，请修改后保存', 'success');
        }
        
        // 删除墙信息
        async function deleteWall(wallId) {
            if (!confirm('确定要删除这条墙信息吗？此操作不可撤销。')) {
                return;
            }
            
            try {
                const response = await fetch('api/walls.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        action: 'delete_wall',
                        wall_id: wallId
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showMessage(result.message || '删除成功', 'success');
                    loadWalls(); // 重新加载列表
                } else {
                    showMessage(result.message || '删除失败', 'error');
                }
            } catch (error) {
                console.error('删除墙信息失败:', error);
                showMessage('网络错误，请稍后重试', 'error');
            }
        }
        
        // 重置表单
        function resetForm() {
            document.getElementById('wallForm').reset();
            document.getElementById('wall_id').value = '';
        }
        
        // 显示消息
        function showMessage(message, type) {
            const messageContainer = document.getElementById('messageContainer');
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${type}`;
            messageDiv.textContent = message;
            
            messageContainer.innerHTML = '';
            messageContainer.appendChild(messageDiv);
            
            // 5秒后自动隐藏成功消息
            if (type === 'success') {
                setTimeout(() => {
                    messageDiv.style.display = 'none';
                }, 5000);
            }
        }
        
        // HTML转义函数
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    </script>
</body>
</html>