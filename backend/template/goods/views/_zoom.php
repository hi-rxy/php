<div class="clearfixLeft" id="clearcontent">
    <div class="box">
        <div class="tb-booth tb-pic tb-s310">
            <img src="<?='/uploads'.$details['gallery_one'][0]?>" alt="细节展示放大镜特效" rel="<?='/uploads'.$details['gallery_one'][0]?>" class="jqzoom" />
        </div>
        <ul class="tb-thumb" id="thumblist">
            <?php
            if ($details['gallery_one']) :
                foreach ($details['gallery_one'] as $key => $item) :
                    ?>
                    <li class="<?php if (!$key) :?>tb-selected<?php endif;?>">
                        <div class="tb-pic tb-s40">
                            <a href="javascript:;"><img src="<?='/uploads'.$item?>" mid="<?='/uploads'.$item?>" big="<?='/uploads'.$item?>"></a>
                        </div>
                        <img src="" title="pic" />
                    </li>
                <?php
                endforeach;
            endif;
            ?>
        </ul>
    </div>
    <div class="clear"></div>
    <div class="collection" onclick="goods_collect(this,<?=$model->id?>)"><i class="am-icon-heart am-icon-fw"></i><a class="collection-a" href="javascript:;">收藏商品</a></div>
</div>