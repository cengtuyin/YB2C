<?php

// 为网页
$isWeb = true;
// 标记为无需登录
$NotLogin = true;
if(!@include_once("./config.php")){
    echo '<meta http-equiv="refresh" content="0;url=/">';
    exit;
}

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $AllConfig["name"]; ?> - 登录</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            text-decoration: none;
            tap-highlight-color: transparent;
            -webkit-tap-highlight-color: transparent;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #1B1B1F 0%, #1B1B1F 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-container {
            background: #202127;
            border-radius: 16px;
            padding: 40px;
            width: 100%;
            max-width: 400px;
            position: relative;
            overflow: hidden;
        }
        
        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #4A90E2, #5BA7F7, #6BB6FF);
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .login-title {
            color: #4A90E2;
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .login-subtitle {
            color: #666;
            font-size: 14px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            color: #333;
            font-weight: 500;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #DFDFD6;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #1B1B1F;
            color: #DFDFD6;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #4A90E2;
            background: #1B1B1F;
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
        }
        
        .login-button {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #4A90E2, #5BA7F7);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .login-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(74, 144, 226, 0.3);
        }
        
        .login-button:active {
            transform: translateY(0);
        }
        
        .login-button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
        
        .error-message {
            background: #fee;
            color: #c53030;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            border-left: 4px solid #f56565;
            display: none;
        }
        
        .success-message {
            background: #f0fff4;
            color: #2f855a;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            border-left: 4px solid #48bb78;
            display: none;
        }
        
        .login-info {
            background: #f7fafc;
            color: #4a5568;
            padding: 16px;
            border-radius: 8px;
            margin-top: 20px;
            font-size: 13px;
            border-left: 4px solid #4A90E2;
        }
        
        .login-attempts {
            text-align: center;
            margin-top: 15px;
            color: #666;
            font-size: 12px;
        }
        
        @media (max-width: 480px) {
            .login-container {
                padding: 30px 20px;
                margin: 10px;
            }
            
            .login-title {
                font-size: 24px;
            }
        }
        
        /* 加载动画 */
        .loading {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid transparent;
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-right: 8px;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* 倒计时样式（保持原有风格） */
        .lockout-message {
            background: #fff8e1;
            color: #856404;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            border-left: 4px solid #ffc107;
        }
        
        .countdown {
            font-weight: bold;
            font-size: 18px;
            color: #e74c3c;
            margin: 5px 0;
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            background: rgba(231, 76, 60, 0.1);
        }
        
        .refresh-hint {
            font-size: 13px;
            color: #666;
            margin-top: 8px;
        }
        
        /* 倒计时数字动画 */
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .countdown-number {
            display: inline-block;
            min-width: 30px;
            text-align: center;
            animation: pulse 1s infinite;
        }
        
        .remember-me {
            margin: 15px 0;
            font-size: 14px;
            color: #555;
        }

        .custom-checkbox {
            display: inline-flex;
            align-items: center;
            position: relative;
            cursor: pointer;
            user-select: none;
            padding-left: 28px;
        }

        .custom-checkbox input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }

        .checkmark {
            position: absolute;
            left: 0;
            height: 18px;
            width: 18px;
            background-color: #1B1B1F;
            border: 1px solid #ccc;
            border-radius: 4px;
            transition: all 0.2s;
            border-radius: 50%;
        }

        .custom-checkbox:hover input ~ .checkmark {
            border-color: #1B1B1F;
        }

        .custom-checkbox input:checked ~ .checkmark {
            background-color: #1B1B1F;
            border-color: #4285f4;
        }

        .checkmark:after {
            content: "";
            position: absolute;
            display: none;
        }

        .custom-checkbox input:checked ~ .checkmark:after {
            display: block;
        }

        .custom-checkbox .checkmark:after {
            left: 6px;
            top: 2px;
            width: 5px;
            height: 10px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }

        .label-text {
            margin-left: 8px;
            color: #666;
        }
        
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1 class="login-title"><?php echo $AllConfig["name"]; ?></h1>
        </div>
        
        <div class="error-message" id="errorMessage"></div>
        <div class="success-message" id="successMessage"></div>
        
        <form id="loginForm">
            <div class="form-group">
                <input class="form-input" type="text" id="name" name="name" placeholder="请输入用户名" required>
            </div>
            
            <div class="form-group">
                <input class="form-input" type="password" id="password" name="password" placeholder="请输入密码" required>
            </div>
            
            <div class="remember-me">
                <label class="custom-checkbox">
                    <input type="checkbox" name="remember_me" id="remember_me">
                    <span class="checkmark"></span>
                    <span class="label-text">7天内保存账号</span>
                </label>
            </div>
            
            <button type="submit" class="login-button" id="loginButton">
                <span class="loading" id="loadingSpinner"></span>
                <span id="buttonText">登录</span>
            </button>
        </form>
        
        <div class="footer">
            <span class="label-text">© 2025 YB2C</span>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.getElementById('loginForm');
            const errorMessage = document.getElementById('errorMessage');
            const successMessage = document.getElementById('successMessage');
            const loginButton = document.getElementById('loginButton');
            const loadingSpinner = document.getElementById('loadingSpinner');
            const buttonText = document.getElementById('buttonText');
            
            // 检查本地存储中是否有记住的登录信息
            const savedName = localStorage.getItem('savedName');
            const savedPassword = localStorage.getItem('savedPassword');
            const rememberMe = localStorage.getItem('rememberMe') === 'true';
            
            if (rememberMe && savedName) {
                document.getElementById('name').value = savedName;
                document.getElementById('password').value = savedPassword;
                document.getElementById('remember_me').checked = true;
            }
            
            loginForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                // 获取表单数据
                const formData = new FormData(loginForm);
                const name = formData.get('name');
                const password = formData.get('password');
                const rememberMe = document.getElementById('remember_me').checked;
                
                // 显示加载状态
                loginButton.disabled = true;
                loadingSpinner.style.display = 'inline-block';
                buttonText.textContent = '登录中...';
                
                // 隐藏之前的消息
                errorMessage.style.display = 'none';
                successMessage.style.display = 'none';
                
                try {
                    // 发送登录请求到API
                    const response = await fetch('./api/login.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `user=${encodeURIComponent(name)}&password=${encodeURIComponent(password)}`
                    });
                    
                    const result = await response.json();
                    
                    if (result.code === 200) {
                        // 登录成功
                        successMessage.textContent = result.msg || '登录成功！正在跳转...';
                        successMessage.style.display = 'block';
                        
                        // 如果用户选择了记住我，保存信息到本地存储
                        if (rememberMe) {
                            localStorage.setItem('savedName', name);
                            localStorage.setItem('savedPassword', password);
                            localStorage.setItem('rememberMe', 'true');
                        } else {
                            localStorage.removeItem('savedName');
                            localStorage.removeItem('savedPassword');
                            localStorage.removeItem('rememberMe');
                        }
                        
                        // 跳转到管理页面
                        setTimeout(() => {
                            window.location.href = '/';
                        }, 1500);
                    } else {
                        // 登录失败
                        errorMessage.textContent = result.msg || '登录失败，请检查信息是否正确';
                        errorMessage.style.display = 'block';
                        
                        // 如果是锁定状态，显示倒计时
                        if (result.locked && result.unlock_time) {
                            const remainingTime = result.unlock_time - Math.floor(Date.now() / 1000);
                            if (remainingTime > 0) {
                                startCountdown(remainingTime);
                            }
                        }
                        
                        // 恢复按钮状态
                        loginButton.disabled = false;
                        loadingSpinner.style.display = 'none';
                        buttonText.textContent = '登录';
                    }
                } catch (error) {
                    console.error('登录请求失败:', error);
                    errorMessage.textContent = '网络错误，请稍后重试';
                    errorMessage.style.display = 'block';
                    
                    // 恢复按钮状态
                    loginButton.disabled = false;
                    loadingSpinner.style.display = 'none';
                    buttonText.textContent = '登录';
                }
            });
            
            // 倒计时功能
            function startCountdown(remainingTime) {
                const countdownElement = document.createElement('div');
                countdownElement.className = 'lockout-message';
                countdownElement.innerHTML = `
                    <strong>账户已被锁定</strong><br>
                    请在 <span class="countdown" id="countdown">${formatTime(remainingTime)}</span> 后重试
                    <div class="refresh-hint">页面将自动刷新</div>
                `;
                
                errorMessage.parentNode.insertBefore(countdownElement, errorMessage.nextSibling);
                
                function updateCountdown() {
                    remainingTime--;
                    
                    if (remainingTime <= 0) {
                        window.location.reload();
                        return;
                    }
                    
                    document.getElementById('countdown').textContent = formatTime(remainingTime);
                }
                
                updateCountdown();
                setInterval(updateCountdown, 1000);
            }
            
            function formatTime(seconds) {
                const minutes = Math.floor(seconds / 60);
                const secs = seconds % 60;
                return `${minutes}:${secs.toString().padStart(2, '0')}`;
            }
        });
    </script>
</body>
</html>