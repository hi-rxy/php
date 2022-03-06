<div class="J_Brand">

    <div class="attr-list-hd tm-clear">
        <h4>产品参数：</h4>
    </div>
    <div class="clear"></div>
    <ul id="J_AttrUL">
        <?php if ($details['attr_name']) :?>
        <?php foreach ($details['attr_name'] as $key => $item) :?>
        <li title="<?=$item?>"><?=$key?>:&nbsp;<?=$item?></li>
        <?php endforeach;?>
        <?php endif;?>
    </ul>
    <div class="clear"></div>
</div>

<div class="details">
    <div class="attr-list-hd after-market-hd">
        <h4>商品细节</h4>
    </div>
    <div class="twlistNews">
        <p><?=\yii\helpers\Html::decode($details['content_one']['content'])?></p>
    </div>
</div>
<div class="clear"></div>