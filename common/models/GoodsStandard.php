<?php

namespace common\models;

use common\models\traits\BaseModelTrait;
use common\services\Service;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

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
class GoodsStandard extends Service
{
    const STATUS_OPEN = 1; // 正常
    const STATUS_CLOSE = 0; // 删除

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%goods_standard}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['goods_id', 'stock', 'status', 'created_at', 'updated_at', 'created_user_id'], 'integer'],
            [['name'], 'string'],
            [['price', 'market_price', 'weight', 'volume'], 'number'],
            [['sn'], 'string', 'max' => 200],
            // unique
            [['sn'], 'unique'],
            // trim
            [['name','sn','price','market_price','weight','volume'],'trim']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键id',
            'goods_id' => '商品id',
            'name' => '规格组合id',
            'price' => '销售价格',
            'market_price' => '划线价',
            'stock' => '商品规格库存',
            'sn' => '商品规格编码',
            'weight' => '商品规格重量',
            'volume' => '商品规格体积',
            'status' => '1正常0删除',
            'created_at' => '添加时间',
            'updated_at' => '修改时间',
            'created_user_id' => '操作人',
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

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)){
            $this->created_user_id = Yii::$app->user->id;
            return true;
        }
        return false;
    }

    /**
     * 关联商品表
     * @return ActiveQuery
     */
    public function getGoods()
    {
        return $this->hasOne(Goods::className(), ['id' => 'goods_id'])->alias('goods_');
    }

    /**
     * 根据商品ID获取集合
     * @param $goodsId
     * @return array|ActiveRecord|null
     */
    public static function getStandardByGid ($goodsId)
    {
        return self::queryCondition()->select([
            'min_price' => 'min(price)',
            'max_price' => 'max(price)',
            'market_min_price' => 'min(market_price)',
            'market_max_price' => 'max(market_price)',
            'stock' => 'sum(stock)',
        ])->andFilterWhere(['goods_id' => $goodsId])->asArray()->one();
    }

    /**
     * 默认条件
     * @return ActiveQuery
     */
    public static function queryCondition ()
    {
        $query = self::find();
        $query->andFilterWhere(['status' => self::STATUS_OPEN]);
        return $query;
    }

    /**
     * 默认条件
     * @param $condition
     * @param string $operator
     * @return string[]
     */
    private static function getWhere ($condition,$operator = 'and')
    {
        $where   = [$operator];
        $where[] = ['status' => 1];
        array_push($where,$condition);
        return $where;
    }

    /**
     * 取出一条数据
     * @param $where
     * @return array
     */
    public static function tableGetOne ($where)
    {
        return self::getDataOne(self::tableName(), self::getWhere($where), ['id', 'goods_id', 'name','price','market_price','stock','sn','weight','volume','status','created_user_id']);
    }
}
