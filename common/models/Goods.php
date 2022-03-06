<?php
namespace common\models;

use common\models\relations\GoodsRelation;
use common\models\traits\BaseModelTrait;
use common\services\Service;
use yii\base\BaseObject;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%goods}}".
 *
 * @property int $id 商品id
 * @property int $class_id 商品分类id
 * @property int $store_id 店铺id
 * @property int $store_class_id 店铺分类id
 * @property string $name 商品名称
 * @property string $thumb 商品主图
 * @property string $keywords 商品关键词
 * @property string $price 价格
 * @property string $min_price 价格最小值
 * @property string $max_price 价格最大值
 * @property string $market_price 商品划线价
 * @property string $market_min_price 商品划线最低价
 * @property string $market_max_price 商品划线最高价
 * @property int $stock 商品库存
 * @property int $hits 商品点击数
 * @property int $collect 商品收藏数
 * @property int $sales_volume 商品销量
 * @property int $comment_volume 商品评论数
 * @property int $sort 商品排序
 * @property int $status 0未审核1表示已通过2表示下架
 * @property string $verify_idea 审核意见
 * @property int $created_at 添加时间
 * @property int $updated_at 更新时间
 */
class Goods extends Service
{
    const STATUS_PENDING_REVIEW = 0; // 待审核
    const STATUS_AUDITED = 1; // 已审核
    const STATUS_REVIEW_FAILED = 2; // 审核未通过

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%goods}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['class_id', 'store_id', 'store_class_id', 'stock', 'hits', 'collect', 'sales_volume', 'comment_volume', 'sort', 'status', 'created_at', 'updated_at'], 'integer'],
            [['price', 'min_price', 'max_price', 'market_price', 'market_min_price', 'market_max_price'], 'number'],
            [['name', 'thumb', 'keywords'], 'string', 'max' => 200],
            [['verify_idea'], 'string', 'max' => 255],
            // unique
            [['class_id', 'store_id', 'store_class_id', 'name', 'price'], 'unique'],
            // trim
            [['name', 'keywords', 'price', 'min_price', 'max_price', 'market_price', 'market_min_price', 'market_max_price'], 'trim'],
            // default
            ['status', 'default', 'value' => self::STATUS_PENDING_REVIEW],
            ['status', 'in', 'range' => [self::STATUS_PENDING_REVIEW, self::STATUS_AUDITED, self::STATUS_REVIEW_FAILED]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'class_id' => 'Class ID',
            'store_id' => 'Store ID',
            'store_class_id' => 'Store Class ID',
            'name' => 'Name',
            'thumb' => 'Thumb',
            'keywords' => 'Keywords',
            'price' => 'Price',
            'min_price' => 'Min Price',
            'max_price' => 'Max Price',
            'market_price' => 'Market Price',
            'market_min_price' => 'Market Min Price',
            'market_max_price' => 'Market Max Price',
            'stock' => 'Stock',
            'hits' => 'Hits',
            'collect' => 'Collect',
            'sales_volume' => 'Sales Volume',
            'comment_volume' => 'Comment Volume',
            'sort' => 'Sort',
            'status' => 'Status',
            'verify_idea' => 'Verify Idea',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'create_user_id' => 'Create User ID',
        ];
    }

    /**
     * 自动把时间戳填充指定的属性
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at']
                ]
            ]
        ];
    }

    /**
     * 状态值
     * @param null $intStatus
     * @return array|mixed
     */
    public static function getArrayStatus($intStatus = null)
    {
        $array = [
            self::STATUS_PENDING_REVIEW => '待审核',
            self::STATUS_AUDITED => '已审核',
            self::STATUS_REVIEW_FAILED => '审核未通过',
        ];

        if ($intStatus !== null && isset($array[$intStatus])) {
            $array = $array[$intStatus];
        }

        return $array;
    }

    /**
     * 获取状态值对应的颜色信息
     *
     * @param int $intStatus 状态值
     *
     * @return array|string
     */
    public static function getStatusColor($intStatus = null)
    {
        $array = [
            self::STATUS_AUDITED => 'btn-info',
            self::STATUS_PENDING_REVIEW => 'btn-success',
            self::STATUS_REVIEW_FAILED => 'btn-danger',
        ];

        if ($intStatus !== null && isset($array[$intStatus])) {
            $array = $array[$intStatus];
        }

        return $array;
    }

    /**
     * 关联店铺表
     * @return ActiveQuery
     */
    public function getStore()
    {
        return $this->hasOne(Store::className(), ['id' => 'store_id'])->alias('store_');
    }

    /**
     * 关联店铺分类表
     * @return ActiveQuery
     */
    public function getStoreClass()
    {
        return $this->hasOne(StoreClass::className(), ['id' => 'store_class_id'])->alias('store_class_');
    }

    /**
     * 关联商品分类表
     * @return ActiveQuery
     */
    public function getGoodsClass()
    {
        return $this->hasOne(GoodsClass::className(), ['id' => 'class_id'])->alias('goods_class_');
    }

    /**
     * 关联商品内容表
     * @return ActiveQuery
     */
    public function getGoodsContent()
    {
        return $this->hasOne(GoodsContent::className(), ['goods_id' => 'id'])->alias('goods_content_');
    }

    /**
     * 关联商品规格表
     * @return ActiveQuery
     */
    public function getGoodsStandard()
    {
        return $this->hasMany(GoodsStandard::className(), ['goods_id' => 'id'])->alias('goods_standard_');
    }

    /**
     * 关联商品自定义规格表
     * @return ActiveQuery
     */
    public function getGoodsSpec()
    {
        return $this->hasMany(GoodsSpec::className(), ['goods_id' => 'id'])->alias('goods_spec_');
    }

    /**
     * 关联商品属性表
     * @return ActiveQuery
     */
    public function getGoodsAttr()
    {
        return $this->hasMany(GoodsAttr::className(), ['goods_id' => 'id'])->alias('goods_attr_');
    }

    public static function doSaveField($goods_id,$post)
    {
        $goodId = !is_array($goods_id) ? [$goods_id] : $goods_id;

        $data = [
            'status' => isset($post['status']) ? $post['status'] : 0,
            'verify_idea' => $post['content'] ? trim($post['content']) : ''
        ];

        return Goods::updateAll($data, ['id' => $goodId]);
    }

    /**
     * 获取修改时的商品的信息
     * @return array
     */
    public static function getGoodsViews (Goods $model)
    {
        $goodsClassOne  = $model->goodsClass;
        #商品父级分类
        $classIds       = array_reverse(GoodsClass::getClassId((int)$model->class_id));
        #店铺父级分类
        $storeClassIds  = array_reverse(StoreClass::getClassId((int)$model->store_class_id));

        $searchGoodsClass = new \backend\models\search\GoodsClassSearch();
        $searchStoreClass = new \backend\models\search\StoreClassSearch();

        #商品分类
        if ((int)$model->class_id) {
            foreach ($classIds as $k => $v) {
                $clone = clone $searchGoodsClass;
                $goods_temp  = $clone->search(['status' => GoodsClass::STATUS_OPEN, 'pid' => $v])->asArray()->all();
                if (!empty($goods_temp)) {
                    $arrGoods[$k] = $goods_temp;
                }
            }
            $class_data     = $arrGoods;
        } else {
            $class_data[]   = $searchGoodsClass->search(['status' => GoodsClass::STATUS_OPEN, 'pid' => 0])->asArray()->all();
            $classIds       = [];
        }

        #店铺分类
        if ((int)$model->store_class_id) {
            foreach ($storeClassIds as $k1 => $v1) {
                $storeClone = clone $searchStoreClass;
                $store_temp  = $storeClone->search(['status' => StoreClass::STATUS_OPEN, 'pid' => $v1])->asArray()->all();
                if (!empty($store_temp)) {
                    $arrStore[$k1] = $store_temp;
                }
            }
            $store_class_data = $arrStore;
        } else {
            $store_class_data[] = $searchStoreClass->search(['status' => StoreClass::STATUS_OPEN, 'pid' => 0])->asArray()->all();
            $storeClassIds = array();
        }

        #获取总后台的规格
        $spec_data      = Attr::find()->select(['id','name','value'])->where(['type_id' => $goodsClassOne->type_id,'style' => 1])->indexBy('id')->asArray()->all();

        #总后台数据处理
        $spec_data_value = array();
        if (!empty($spec_data)) {
            foreach ($spec_data as $k => $v) {
                $spec_data_value[$v['name']] = explode(",", $v['value']);
            }
        }

        #当前商品拥有规格
        $goods_spec_data = GoodsSpec::find()->select(['id','name','value'])->where(['goods_id' => $model->id, 'status' => 1])->indexBy('id')->asArray()->all();
        if (!empty($goods_spec_data) && !empty($spec_data)) {
            $goods_spec_temp = array_merge($spec_data, $goods_spec_data);
        } elseif (!empty($goods_spec_data)) {
            $goods_spec_temp = $goods_spec_data;
        } elseif (!empty($spec_data)) {
            $goods_spec_temp = $spec_data;
        } else {
            $goods_spec_temp = array();
        }

        #总规格处理
        $zong_spec_data = array();
        if (!empty($goods_spec_temp)) {
            foreach ($goods_spec_temp as $k => $v) {
                $zong_spec_data[$v['name']][] = $v['value'];
            }
            foreach ($zong_spec_data as $k => $v) {
                $zong_spec_data[$k] = array_unique(explode(",", implode(",", $v)));
            }
        }

        #当前商品规格处理
        if (!empty($goods_spec_data)) {
            $arr = array();
            foreach ($goods_spec_data as $k => $v) {
                $arr[$v['name']] = explode(",", $v['value']);
            }
            $goods_spec_data = $arr;
        }

        #商品选中规格
        $standard_data = ArrayHelper::toArray($model->goodsStandard);
        $goods_attr_spec = GoodsAttr::find()->where(['goods_id' => $model->id, 'type' => 1, 'status' => 1])->indexBy('id')->asArray()->all();
        if (!empty($standard_data)) {
            unset($k,$v);
            foreach ($standard_data as $k => $v) {
                $standard_data[$k]['name'] = explode(",", $v['name']);
                if (!empty($standard_data[$k]['name'])) {
                    foreach ($standard_data[$k]['name'] as $k1 => $v1) {
                        $standard_data_str[$k1] = $goods_attr_spec[$v1]['value'];
                    }
                    $standard_data[$k]['name'] = implode(",", $standard_data_str);
                }
            }
        }

        #规格属性里面的图片数据
        $goods_pic_data = ArrayHelper::map($goods_attr_spec,'value','pic');

        #获取总后台的规格
        $attr_data      = Attr::find()->select(['id','name','value'])->where(['type_id' => $goodsClassOne->type_id,'style' => 0])->indexBy('id')->asArray()->all();

        #当前商品拥有属性
        $goods_attr_data = GoodsAttr::find()->where(['goods_id' => $model->id, 'type' => 0])->asArray()->all();
        if (!empty($goods_attr_data) && !empty($attr_data)) {
            foreach ($attr_data as $k => $v) {
                foreach ($goods_attr_data as $k1 => $v1) {
                    if ($v1['id'] == $v['id']) {
                        $attr_data[$k]['select_value'][] = $v1['value'];
                    }
                }
            }
        }
        if (!empty($attr_data)) {
            foreach ($attr_data as $k => $v) {
                $attr_data[$k]['value'] = explode(",", $attr_data[$k]['value']);
            }
        }

        #获取商品自定义属性
        $goods_attr_custom_data = GoodsAttrCustom::find()->where(['goods_id' => $model->id])->asArray()->all();

        #获取商品相册
        $gallery_one = GoodsAlbum::find()->where(['goods_id' => $model->id])->asArray()->one();
        $gallery_one = [
            $model->thumb,
            $gallery_one['photo_1'],
            $gallery_one['photo_2'],
            $gallery_one['photo_3'],
            $gallery_one['photo_4']
        ];
        $gallery_one = array_values(array_filter($gallery_one));

        #商品内容
        $content_one = GoodsContent::find()->where(['goods_id' => $model->id])->asArray()->one();

        return [
            'classIds'          => $classIds,
            'class_data'        => $class_data,
            'storeClassIds'     => $storeClassIds,
            'store_class_data'  => $store_class_data,
            'spec_data_value'   => $spec_data_value,
            'zong_spec_data'    => $zong_spec_data,
            'goods_spec_data'   => $goods_spec_data,
            'standard_data'     => $standard_data,
            'goods_pic_data'    => $goods_pic_data,
            'attr_data'         => $attr_data,
            'goods_attr_custom_data'    => $goods_attr_custom_data,
            'gallery_one'       => $gallery_one,
            'content_one'       => $content_one,
        ];
    }

    /**
     * 获取商品详情信息
     */
    public static function getOneDetail ($goods_id = 0, $standard_id = 0)
    {
        $goods_one = self::tableGetOne(['id' => $goods_id]);
        if (empty($goods_one)) return false;

        $goods_one['url'] =  Url::to('detail', ['goods_id' => $goods_id]);
        if ($standard_id > 0) {
            $standard_one = GoodsStandard::tableGetOne(['id' => $standard_id]);
            if (!empty($standard_one)) {
                $goods_one['goods_stock']       = $standard_one['stock'];
                $goods_one['goods_price']       = $standard_one['price'];
                $goods_one['goods_del_price']   = $standard_one['market_price'];
                $goods_one['goods_sn']          = $standard_one['sn'];

                # 商品规格
                $where = ['and'];
                $where[] = ['goods_attr_.goods_id' => $goods_id];
                $where[] = ['goods_attr_.type' => 1];
                $where[] = ['goods_attr_.id' => explode(",", $standard_one['name'])];
                $spec_data = GoodsAttr::getSearchData($where);
                if (!empty($spec_data)) {
                    foreach ($spec_data as $k1 => $v1) {
                        if (!empty($spec_data[$k1]['pic'])) {
                            $goods_one['goods_thumb'] = '/uploads'.$spec_data[$k1]['pic'];
                        }
                    }
                }
                $goods_one['spec_data']     = $spec_data;
                $goods_one['standard_id']   = $standard_one['id'];
                $goods_one['standard_name'] = $standard_one['name'];
                if ($standard_one['stock'] == 0) {
                    $goods_one['goods_status'] = 0;
                }
                if ($standard_one['status'] == 0) {
                    $goods_one['goods_status'] = 0;
                }
            } else {
                $goods_one['goods_status']  = 0;
                $goods_one['standard_id']   = 0;
            }
        } else {
            $goods_one['standard_id'] = 0;
        }
        return $goods_one;
    }
}
