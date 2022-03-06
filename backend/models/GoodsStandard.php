<?php

namespace backend\models;

use jinxing\admin\helpers\Helper;
use yii;
use yii\db\Exception;

/**
 * This is the model class for table "{{%goods_standard}}".
 *
 * @property int $id 主键id
 * @property int $goods_id 商品id
 * @property string|null $name 规格组合id
 * @property float|null $price 销售价格
 * @property float|null $market_price 划线价
 * @property int|null $stock 商品规格库存
 * @property string|null $sn 商品规格编码
 * @property float|null $weight 商品规格重量
 * @property float|null $volume 商品规格体积
 * @property int|null $status 1正常0删除
 * @property int $created_at 添加时间
 * @property int $updated_at 修改时间
 * @property int $created_user_id 操作人
 */
class GoodsStandard extends \common\models\GoodsStandard
{
    /**
     * 保存规格
     * @param int $goodsId
     * @param array $data
     * @param array $attrs
     * @return array|bool
     * @throws Exception
     */
    public static function saveGoodsStandard($goodsId, array $data, array $attrs)
    {
        $f = $data['standard_price'];
        $g = $data['standard_del_price'];
        $h = $data['standard_stock'];
        $i = $data['standard_sn'];
        $j = $data['standard_name'];
        $w = $data['standard_weight'];
        $m = $data['standard_volume'];
        $n = $data['standard_id'];

        $a = self::getGoodsStandardIds($goodsId);
        $b = array_diff($a, $n);
        if (!empty($b)) self::updateStatus($goodsId,$b);

        $model = new self();
        foreach ($i as $k => $v)
        {
            /** @var \common\models\GoodsStandard $clone */
            $clone = in_array($n[$k], $a) ? self::findOne(['id' => $n[$k]]) : clone $model;

            $c = ['name' => self::setStandardName($j[$k],$attrs),'goods_id' => $goodsId, 'market_price' => $g[$k], 'price' => $f[$k], 'stock' => $h[$k], 'sn' => $i[$k], 'weight' => $w[$k], 'volume' => $m[$k], 'status' => 1];

            if ($clone->load($c, '') && !$clone->save()) throw new Exception(Helper::arrayToString($model->getErrors()));
        }

        return true;
    }

    public static function getGoodsStandardIds ($goodsId)
    {
        return self::find()->where(['goods_id' => $goodsId])->select(['id'])->column();
    }

    public static function updateStatus ($goodsId,$ids)
    {
        self::updateAll(['status' => 0], ['and', ['in', 'id', $ids], ['goods_id' => $goodsId]]);
    }

    private static function setStandardName ($name,$attrs)
    {
        $d = explode(",", $name);
        foreach ($d as $k1 => $v1) $d[$k1] = $attrs[$v1];
        return implode(',',$d);
    }
}
