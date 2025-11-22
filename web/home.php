<?php

// 为网页
$isWeb = true;
// 仅权限组 admin 的账号可访问
$PagePermission = "admin";

// 未登录或登录无效会自动处理
include_once("../config.php");
$UserData = getUserData();

?>
<div class="Main_Cord Header_NameView" style="width:100%;margin-bottom: 18px;">
    <div style="display: flex;place-items: center;">
        <img src="./favicon.ico" />
        <span style="font-size:16px;">欢迎回来</span>
    </div>
    <div style="display: flex;place-items: center;margin: 0 18px 0 auto;">
        <span style="margin:0 8px 0 0;font-size:16px;"><?php echo "".$YB2CInfo["version_name"]." " ?></span>
        <a href="#update" style="color:#DFDFD6;font-size:16px;">更新</a>
    </div>
</div>
<div class="Main_Cord Header_NameView" style="width:100%;height:auto;margin-bottom: 18px;display:block;place-items: flex-start;">
    <div style="width:100%;display: flex;place-items: center;border-bottom: 2px dashed #32363F;">
        <img src="./favicon.ico" />
        <span>数据速览</span>
    </div>
    <div style="display:flex;width:100%;">
        <div class="Main_Content">
            <div>
                <h2>用户量</h2>
                <p style="font-size:1.3em;"><?php echo count(getUserIDList()) ?></p>
            </div>
            <div>
                <h2>本日活跃</h2>
                <p style="font-size:1.3em;">Loading</p>
            </div>
            <div>
                <h2>本月活跃</h2>
                <p style="font-size:1.3em;">Loading</p>
            </div>
            <div>
                <h2>总访问量</h2>
                <p style="font-size:1.3em;">Loading</p>
            </div>
            <div>
                <h2>未处理反馈</h2>
                <p style="font-size:1.3em;"><?php echo count(getAdvicesData("on")) ?></p>
            </div>
        </div>
    </div>
</div>
<div class="Main_Cord Header_NameView" style="width:100%;height:auto;margin-bottom: 18px;display:block;place-items: flex-start;">
    <div style="width:100%;display: flex;place-items: center;border-bottom: 2px dashed #32363F;">
        <img src="./favicon.ico" />
        <span>快速操作</span>
    </div>
    <div style="display:flex;width:100%;height:300px;justify-content: center;flex-direction: column;align-items: center;">
        <span>敬请期待</span>
    </div>
</div>