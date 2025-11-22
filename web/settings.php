<?php

// 为网页
$isWeb = true;
// 仅权限组 admin 的账号可访问
$PagePermission = "admin";

// 未登录或登录无效会自动处理
include_once("../config.php");

?>
<style>
.tab-pane { display: none; }
.tab-pane.active { display: block; }
.tab-button.active { 
    color: #667eea !important; 
    border-bottom: 2px solid #667eea !important;
}
</style>

<div class="Main_Cord Header_NameView" style="width:100%;height:auto;margin-bottom: 18px;">
    <ul style="display:flex;margin:0;padding:0;list-style:none;">
        <li><button class="tab-button active" data-tab="0" style="padding:12px 28px;background:transparent;border:none;color:#667eea;cursor:pointer;border-bottom:2px solid #667eea;">全部</button></li>
        <li><button class="tab-button" data-tab="1" style="padding:12px 28px;background:transparent;border:none;color:#98989F;cursor:pointer;border-bottom:2px solid transparent;">面板</button></li>
        <li><button class="tab-button" data-tab="2" style="padding:12px 28px;background:transparent;border:none;color:#98989F;cursor:pointer;border-bottom:2px solid transparent;">安全</button></li>
        <li><button class="tab-button" data-tab="3" style="padding:12px 28px;background:transparent;border:none;color:#98989F;cursor:pointer;border-bottom:2px solid transparent;">额外</button></li>
    </ul>
</div>

<div class="tab-pane active">
    <div class="Main_Cord Header_NameView" style="width:100%;height:auto;margin-bottom: 18px;display:block;place-items: flex-start;">
        <div style="width:100%;display: flex;place-items: center;border-bottom: 2px dashed #32363F;">
            <img src="./favicon.ico" />
            <span>面板设置</span>
        </div>
        <div style="display:flex;width:100%;height:300px;justify-content: center;flex-direction: column;align-items: center;">
            <span>敬请期待</span>
        </div>
    </div>
</div>

<div class="tab-pane">
    <div class="Main_Cord Header_NameView" style="width:100%;height:auto;margin-bottom: 18px;display:block;place-items: flex-start;">
        <div style="width:100%;display: flex;place-items: center;border-bottom: 2px dashed #32363F;">
            <img src="./favicon.ico" />
            <span>More</span>
        </div>
        <div style="display:flex;width:100%;height:300px;justify-content: center;flex-direction: column;align-items: center;">
            <span>敬请期待</span>
        </div>
    </div>
</div>