<div class="theme-popover-mask"></div>
<div class="theme-popover">
    <!--标题 -->
    <div class="am-cf am-padding">
        <div class="am-fl am-cf"><strong class="am-text-danger am-text-lg">添加地址</strong> / <small>添加地址</small></div>
    </div>
    <hr/>

    <div class="am-u-md-12">
        <form class="am-form am-form-horizontal">
            <div class="am-form-group">
                <label for="address_username" class="am-form-label">收货人</label>
                <div class="am-form-content">
                    <input type="text" id="address_username" name="address[address_username]" placeholder="收货人">
                </div>
            </div>
            <div class="am-form-group">
                <label for="address_mobile" class="am-form-label">手机号码</label>
                <div class="am-form-content">
                    <input id="address_mobile" placeholder="手机号必填" type="text" name="address[address_mobile]" >
                </div>
            </div>
            <div class="am-form-group">
                <label class="am-form-label">所在地</label>
                <div class="am-form-content address">
                    <select  lay-ignore name="address[address_province]" id="address_province" onChange="load_address(this.value,'address_city')">
                        <option value="0">请选择省份</option>
                    </select>
                    <select  lay-ignore name="address[address_city]" id="address_city" onChange="load_address(this.value,'address_district')">
                        <option value="0">请选择城市</option>
                    </select>
                    <select lay-ignore name="address[address_district]" id="address_district" onChange="load_address(this.value,'null')">
                        <option value="0">请选择地区</option>
                    </select>
                </div>
            </div>
            <div class="am-form-group">
                <label for="user_address" class="am-form-label">详细地址</label>
                <div class="am-form-content">
                    <textarea class="" name="address[user_address]" rows="3" id="user_address" placeholder="输入详细地址"></textarea>
                    <small>100字以内写出你的详细地址...</small>
                </div>
            </div>
            <div class="am-form-group">
                <label class="am-form-label">设为默认</label>
                <div class="am-form-content default-addr">
                    <label class="addck">
                        <input type="radio" name="address[is_default]" value="1" checked>是</label>
                    <label class="addck">
                        <input type="radio" name="address[is_default]" value="0">否</label>
                </div>
            </div>
            <div class="am-form-group address_submit">
                <div class="am-u-sm-9 am-u-sm-push-3">
                    <div class="am-btn am-btn-danger" method="add">保存</div>
                    <div class="am-btn am-btn-danger close">取消</div>
                </div>
            </div>
        </form>
    </div>
</div>