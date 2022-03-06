<?php
namespace backend\models;

use jinxing\admin\helpers\Helper;
use yii;

/**
 * This is the model class for table "{{%goods_album}}".
 *
 * @property int $id 相册id
 * @property int $goods_id 商品id
 * @property string|null $photo_1 图1
 * @property string|null $photo_2 图2
 * @property string|null $photo_3 图3
 * @property string|null $photo_4 图4
 * @property int|null $created_at 添加时间
 * @property int|null $updated_at 修改时间
 */
class GoodsAlbum extends \common\models\GoodsAlbum
{
    /**
     * 保存商品图片
     * @param int $goodsId
     * @param array $data
     * @return array|bool
     * @throws yii\db\Exception
     */
    public static function saveGoodsAlbum($goodsId, array $data)
    {
        if (!empty($data)) {
            $model = self::findOne(['goods_id' => $goodsId]);
            if (is_null($model)) $model = new self();
            $model->setAttribute('goods_id',$goodsId);
            if ($model->load($data,'') && !$model->save()) throw new yii\db\Exception(Helper::arrayToString($model->getErrors()));
        } else {
            self::deleteAll(['goods_id' => $goodsId]);
        }
        return true;
    }
}
