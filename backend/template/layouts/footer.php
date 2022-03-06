<div class="footer-box">
    <div class="footer">
        <div class="footer-hd ">
            <p>
                <a href="javascript:;">阿西里西</a>
                <b>|</b>
                <a href="javascript:;">商城首页</a>
                <b>|</b>
                <a href="javascript:;">支付宝</a>
                <b>|</b>
                <a href="javascript:;">物流</a>
            </p>
        </div>
        <div class="footer-bd ">
            <p>
                <a href="javascript:;">关于阿西里西</a>
                <a href="javascript:;">合作伙伴</a>
                <a href="javascript:;">联系我们</a>
                <a href="javascript:;">网站地图</a>
                <em>阿西里西商城 黔ICP备10550059号 Copyright © 2010 -2018 Axlix.com All Rights Reserved</em>
            </p>
        </div>
    </div>
</div>

<div id="loginBox" style="display:none;">
    <form class="layui-form" action="">
        <div class="layui-form-item">
            <label class="layui-form-label am-icon-user"></label>
            <div class="layui-input-inline">
                <input type="text" name="username" placeholder="请输入邮箱或手机" id="login_username" class="layui-input" datatype="*" nullmsg="用户名不能为空！">
            </div>
            <div class="logininfo">
                <label class="layui-form-label"></label>
                <span class="Validform_checktip"></span>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label am-icon-lock"></label>
            <div class="layui-input-inline">
                <input type="password" name="password" id="login_password" placeholder="请输入密码" class="layui-input" datatype="*" nullmsg="密码不能为空！">
            </div>
            <div class="logininfo">
                <label class="layui-form-label"></label>
                <span class="Validform_checktip"></span>
            </div>
        </div>
        <if condition="$show_verify eq 1">
            <div class="layui-form-item verify-box">
                <label class="layui-form-label am-icon-plug"></label>
                <div class="layui-input-inline verify">
                    <input type="text" width="50" name="code" id="login_code" placeholder="验证码" class="layui-input" datatype="*" nullmsg="验证码不能为空！">
                    <!--<img src="{:url('Login/verify')}" alt="">-->
                </div>
                <div class="logininfo">
                    <label class="layui-form-label"></label>
                    <span class="Validform_checktip"></span>
                </div>
            </div>
        </if>
        <div class="layui-form-item login-and-a">
            <input type="hidden" name="login_uid" value="{$uid}" />
            <button class="layui-btn login-btn" type="button" onclick="login_common();">登陆</button>
            <a class="layui-btn layui-btn-normal" href="{:url('Login/reg')}">注册</a>
        </div>
    </form>
</div>
<!--消息提醒-->
<div class="msg-box" style="display: none;">
    <div class="msg-tit"><i class="am-icon news-icon"></i>消息提示
        <a href="javascript:;" onclick="msg_close();" class="am-icon am-icon-close msg-close"></a>
    </div>
    <div class="msg-con">
        <ul>
        </ul>
    </div>
</div>
<audio id="audio_source_1">
    <source  type="audio/mp3">
</audio>