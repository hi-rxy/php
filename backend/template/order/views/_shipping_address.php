<div class="address">
    <h3>确认收货地址 </h3>
    <div class="control">
        <div class="tc-btn createAddr theme-login am-btn am-btn-danger add_address">使用新地址</div>
    </div>
    <div class="clear"></div>
    <ul>
        <?php if (!empty($address['user_address_data'])) :
        foreach ($address['user_address_data'] as $key => $item) :?>
            <div class="per-border" data-address_id="<?=$item['id']?>" data-address_province="<?=$item['address_province'][0]?>" data-address_city="<?=$item['address_city'][0]?>"  data-address_district="<?=$item['address_district'][0]?>" data-user_address="<?=$item['consignee_address']?>" data-address_username="<?=$item['consignee_username']?>" data-address_mobile="<?=$item['consignee_mobile']?>" data-is_default="<?=$item['is_default']?>"></div>
            <li class="user-addresslist <?if ($item['is_default'] == 1) :?>defaultAddr<?php endif;?>">
                <div class="address-left">
                    <div class="user defaultAddr">
                        <span class="buy-address-detail">
                            <span class="buy-user"><?=$item['consignee_username']?></span>
                            <span class="buy-phone"><?=$item['consignee_mobile']?></span>
                        </span>
                    </div>
                    <div class="default-address defaultAddr">
                        <span class="buy-line-title buy-line-title-type">收货地址：</span>
                        <span class="buy--address-detail">
                            <span class="province"><?=$item['consignee_province']?></span>
                            <span class="city"><?=$item['consignee_city']?></span>
                            <span class="dist"><?=$item['consignee_district']?></span>
                            <span class="street"><?=$item['consignee_address']?></span>
                        </span>
                    </div>
                    <?if ($item['is_default'] == 1) :?><ins class="deftip">默认地址</ins><?php endif;?>
                </div>
                <div class="address-right">
                    <a href="{:url('Address/index')}"><span class="am-icon-angle-right am-icon-lg"></span></a>
                </div>
                <div class="clear"></div>
                <div class="new-addr-btn">
                    <?if ($item['is_default'] == 1) {?>
                        <a href="javascript:;" class="is_default  hidden" onclick="set_default_address(this,<?=$item['id']?>)">设为默认</a>
                        <?php } else {?>
                        <a href="javascript:;" class="is_default" onclick="set_default_address(this,<?=$item['id']?>)">设为默认</a>
                    <?php }?>
                    <span class="new-addr-bar hidden">|</span>
                    <a href="javascript:;" onclick="edit_address(this,<?=$item['id']?>);">编辑</a>
                    <span class="new-addr-bar">|</span>
                    <a href="javascript:void(0);" class="del_address" address_id="<?=$item['id']?>" onclick="del_address(this,<?=$item['id']?>,<?=$item['is_default']?>);">删除</a>
                </div>
            </li>
        <?php endforeach;endif;?>
    </ul>
    <div class="clear"></div>
</div>