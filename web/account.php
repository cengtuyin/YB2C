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
    <ul style="display:flex;margin:0;padding:0;list-style:none;white-space: nowrap;">
        <li><button class="tab-button active" data-tab="0" style="padding:12px 28px;background:transparent;border:none;color:#667eea;cursor:pointer;border-bottom:2px solid #667eea;">账号</button></li>
        <li><button class="tab-button" data-tab="1" style="padding:12px 28px;background:transparent;border:none;color:#98989F;cursor:pointer;border-bottom:2px solid transparent;">权限组</button></li>
        <li><button class="tab-button" data-tab="2" style="padding:12px 28px;background:transparent;border:none;color:#98989F;cursor:pointer;border-bottom:2px solid transparent;">IP黑名单</button></li>
    </ul>
</div>

<div class="tab-pane active">
    <div class="Main_Cord Header_NameView" style="width:100%;height:auto;margin-bottom: 18px;display:block;place-items: flex-start;">
        <div style="width:100%;display: flex;place-items: center;border-bottom: 2px dashed #32363F;">
            <img src="./favicon.ico" />
            <span>账户列表 ( <?php echo count(getUserIDList()) ?> )</span>
        </div>
        <div style="display:flex;width:100%;">
            <table>
                <tr class="TableThree">
                    <th>名称</th>
                    <th>邮箱</th>
                    <th class="TableDisplayNone">权限组</th>
                    <th class="TableDisplayNone">上次活跃</th>
                    <th>操作</th>
                </tr>
                <?php if(count($AllPermission) === 0): ?>
                    <tr><td colspan="4" style="text-align:center;">无数据</td></tr>
                <?php else: ?>
                    <?php foreach (getUserIDList() as $UserID): $UserData = getUserData($UserID); $UserTokenData = getUserToken($UserID)[0]; $UserConfig = getUserConfig($UserID); ?>
                        <tr class="TableThree">
                            <td><?php echo $UserData["name"] ?></td>
                            <td><?php echo $UserData["mail"] ?></td>
                            <td class="TableDisplayNone"><?php echo $UserConfig["permission"]["account"]." | ".implode(" | ",$UserConfig["permission"]["group"]) ?></td>
                            <td class="TableDisplayNone"><?php echo date("y-m-d h:i:s",$UserTokenData["time"]) ?></td>
                            <td><button class="Main_Button_NH">编辑</button></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </table>
        </div>
    </div>
</div>

<div class="tab-pane">
    <div class="Main_Cord Header_NameView" style="width:100%;height:auto;margin-bottom: 18px;display:block;place-items: flex-start;">
        <div style="width:100%;display: flex;place-items: center;border-bottom: 2px dashed #32363F;">
            <img src="./favicon.ico" />
            <span>权限组 ( <?php echo count($AllPermission) ?> )</span>
        </div>
        <div style="display:flex;width:100%;">
            <table>
                <tr>
                    <th>名称</th>
                    <th>权限</th>
                </tr>
                <?php if(count($AllPermission) === 0): ?>
                    <tr><td colspan="4" style="text-align:center;">无数据</td></tr>
                <?php else: ?>
                    <?php foreach ($AllPermission as $PermissionGroup=>$PermissionGroup_S): ?>
                        <tr>
                            <td><?php echo $PermissionGroup ?></td>
                            <td><?php echo "( $PermissionGroup_S ) ".decbin($PermissionGroup_S) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </table>
        </div>
    </div>
    <div class="Main_Cord Header_NameView" style="width:100%;height:auto;margin-bottom: 18px;display:block;place-items: flex-start;">
        <div style="width:100%;display: flex;place-items: center;border-bottom: 2px dashed #32363F;">
            <img src="./favicon.ico" />
            <span>提示</span>
        </div>
        <div style="display:flex;width:100%;">
            <p style="padding:18px;color:#DFDFD6;">需要添加权限组请在 /config.php 内手动添加</p>
        </div>
    </div>
</div>

<div class="tab-pane">
    <div class="Main_Cord Header_NameView" style="width:100%;height:auto;margin-bottom: 18px;display:block;place-items: flex-start;">
        <div style="width:100%;display: flex;place-items: center;border-bottom: 2px dashed #32363F;">
            <img src="./favicon.ico" />
            <span>IP黑名单 ( <?php echo count(getBanIPList()) ?> )</span>
        </div>
        <div style="display:flex;width:100%;">
            <table>
                <tr>
                    <th colspan="4">IP</th>
                </tr>
                <?php if(count(getBanIPList()) === 0): ?>
                    <tr><td colspan="4" style="text-align:center;">无数据</td></tr>
                <?php else: ?>
                    <?php foreach (getBanIPList() as $IP): ?>
                        <tr>
                            <td><?php echo $IP ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </table>
        </div>
    </div>
    <div class="Main_Cord Header_NameView" style="width:100%;height:auto;margin-bottom: 18px;display:block;place-items: flex-start;">
        <div style="width:100%;display: flex;place-items: center;border-bottom: 2px dashed #32363F;">
            <img src="./favicon.ico" />
            <span>提示</span>
        </div>
        <div style="display:flex;width:100%;">
            <p style="padding:18px;color:#DFDFD6;">需要删除或添加请手动修改 /data/banip.txt</p>
        </div>
    </div>
</div>