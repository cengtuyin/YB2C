<?php

// 为网页
$isWeb = true;
// 仅权限组 admin 的账号可访问
$PagePermission = "admin";

// 未登录或登录无效会自动处理
include_once("./config.php");



$UserData = getUserData();

?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name='description' content='请输入文本'>
    <title><?php echo $AllConfig["name"]; ?></title>
    <link rel="stylesheet" type="text/css" href="./src/css/yinstyle.css">
    <script src="./src/js/yinscript.js"></script>
    <style>
        /* 添加焦点样式 */
        .Header_List a.active {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }
        
        .Header_List a.active span {
            color: #fff;
            font-weight: 600;
        }
        
        .Header_List a.active img {
            transform: scale(1.1);
            filter: brightness(1.2);
        }
        
        /* 悬停效果 */
        .Header_List a:hover {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 4px;
            transition: all 0.3s ease;
        }
    </style>
</head>
<body>
    <div class="Header">
        <div class="Header_ContentView">
            <a style="border-bottom: 2px dashed #32363F;margin-bottom:8px;" class="Header_NameView Header_Icon" href="./">
                <img src="./favicon.ico" />
                <span><?php echo $AllConfig["name"]; ?></span>
            </a>
            <div class="Header_List">
                <a href="#home" class="active" data-section="home">
                    <img src="./favicon.ico" />
                    <span>首页</span>
                </a>
                <a href="#data" data-section="data">
                    <img src="./favicon.ico" />
                    <span>数据</span>
                </a>
                <a href="#advice" data-section="advice">
                    <img src="./favicon.ico" />
                    <span>反馈</span>
                </a>
                <a href="#status" data-section="status">
                    <img src="./favicon.ico" />
                    <span>公告</span>
                </a>
                <a href="#account" data-section="account">
                    <img src="./favicon.ico" />
                    <span>用户</span>
                </a>
                <a href="#api" data-section="api">
                    <img src="./favicon.ico" />
                    <span>接口</span>
                </a>
                <a href="#settings" data-section="settings">
                    <img src="./favicon.ico" />
                    <span>配置</span>
                </a>
                
            </div>
            <div class="Header_List_Bottom">
                
            </div>
        </div>
    </div>
    <div class="Main">
        <div class="loading-container">
            <div class="loading-spinner"></div>
                <p>正在加载内容...</p>
        </div>
    </div>
    <script>
    function LoadJS(){
        document.querySelectorAll('.tab-button').forEach(button => {
            button.addEventListener('click', function() {
                // 更新按钮状态
                document.querySelectorAll('.tab-button').forEach(btn => {
                    btn.classList.remove('active');
                    btn.style.color = '#98989F';
                    btn.style.borderBottom = '2px solid transparent';
                });
                this.classList.add('active');
                this.style.color = '#667eea';
                this.style.borderBottom = '2px solid #667eea';
        
                // 更新内容显示
                const targetTab = this.getAttribute('data-tab');
                document.querySelectorAll('.tab-pane').forEach(pane => {
                    pane.classList.remove('active');
                });
                document.querySelectorAll('.tab-pane')[targetTab].classList.add('active');
            });
        });
    }
</script>
    <script>
    // Header 焦点管理
    document.addEventListener('DOMContentLoaded', function() {
        const headerLinks = document.querySelectorAll('.Header_List a');
        const mainContent = document.querySelector('.Main');
        
        // 初始化激活状态
        function setActiveLink(section) {
            headerLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('data-section') === section) {
                    link.classList.add('active');
                }
            });
        }
        
        // 点击导航项
        headerLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const section = this.getAttribute('data-section');
                setActiveLink(section);
                loadSection(section);
            });
        });
        
        function loadSection(section) {
            window.location.hash = section;
            showLoadingState();
            const url = `./web/${section}.php`;
            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.text();
                })
                .then(html => {
                    mainContent.innerHTML = html;
                    hideLoadingState();
                    dispatchContentLoaded(section);
                    LoadJS();
                })
                .catch(error => {
                    console.error('加载内容失败:', error);
                    mainContent.innerHTML = getErrorContent(section, error);
                    hideLoadingState();
                });
        }
        
        // 显示加载状态
        function showLoadingState() {
            mainContent.innerHTML = `
                <div class="loading-container">
                    <div class="loading-spinner"></div>
                    <p>正在加载内容...</p>
                </div>
            `;
        }
        
        // 隐藏加载状态
        function hideLoadingState() {
            // 加载状态会在内容更新时自动替换
        }
        function getErrorContent(section, error) {
            return `
                <div class="error-container">
                    <div class="error-icon">⚠️</div>
                    <h2>加载失败</h2>
                    <p>无法加载 ${section} 页面的内容</p>
                    <p class="error-detail">错误信息: ${error.message}</p>
                    <button onclick="location.reload()" class="retry-btn">重新加载</button>
                </div>
            `;
        }
        
        function dispatchContentLoaded(section) {
            const event = new CustomEvent('sectionContentLoaded', {
                detail: { section }
            });
            document.dispatchEvent(event);
        }
        
        window.addEventListener('hashchange', function() {
            const section = window.location.hash.substring(1);
            if (section) {
                setActiveLink(section);
                loadSection(section);
            }
        });
        showLoadingState();
        const initialSection = window.location.hash.substring(1) || 'home';
        setActiveLink(initialSection);
        loadSection(initialSection);
    });
    Heartbeat();
    function Heartbeat(){
        setInterval(() => {
            var request = new XMLHttpRequest();
            request.open("GET", "./api/ping.php", false);
            request.send(null);
            if (request.status === 200) {
                if(request.responseText !== "ok") window.href = "./";
                console.log(request.responseText);
            }
        }, 30*1000);
    }
    </script>
    <?php if ($Debug): ?>
        <!-- Debug Modeing -->
    <?php else: ?>
        <script src="./src/js/debugger.js"></script>
    <?php endif; ?>
</body>
</html>