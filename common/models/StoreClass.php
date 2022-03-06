<?php
namespace common\models;

use common\services\Service;
use jinxing\admin\models\Admin;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%store_class}}".
 *
 * @property int $id
 * @property int $store_id 店铺id
 * @property string $name 分类名称
 * @property string $title 分类seo标题
 * @property string $keywords 分类关键词
 * @property string $desc 分类描述
 * @property int $pid 父级id
 * @property int $sort 分类排序
 * @property int $status 0表示关闭1表示开启
 * @property int $is_nav 0表示不显示1表示显示
 * @property int $created_at 添加时间
 * @property int $create_user_id 添加者
 */
class StoreClass extends Service
{
    const STATUS_CLOSE = 0; // 禁用/隐藏
    const STATUS_OPEN = 1; // 开启/显示

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%store_class}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['store_id', 'pid', 'sort', 'status', 'is_nav', 'created_at', 'create_user_id'], 'integer'],
            [['name'], 'string', 'max' => 100],
            // unique
            [['name'], 'unique', 'message' => '名称已经存在'],
            [['title', 'keywords', 'desc'], 'string', 'max' => 200],
            // trim
            [['name', 'keywords', 'desc'], 'trim'],
            // default
            ['status', 'default', 'value' => self::STATUS_OPEN],
            ['status', 'in', 'range' => [self::STATUS_CLOSE, self::STATUS_OPEN]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store_id' => '店铺id',
            'name' => '分类名称',
            'title' => '分类seo标题',
            'keywords' => '分类关键词',
            'desc' => '分类描述',
            'pid' => '父级id',
            'sort' => '分类排序',
            'status' => '0表示关闭1表示开启',
            'is_nav' => '0表示不显示1表示显示',
            'created_at' => '添加时间',
            'create_user_id' => '添加者',
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
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at']
                ]
            ]
        ];
    }

    /**
     * 状态值
     * @param null $intStatus
     * @param int $intType
     * @return array|mixed
     */
    public static function getArrayStatus($intStatus = null)
    {
        $array = [
            self::STATUS_OPEN => '开启',
            self::STATUS_CLOSE => '禁用',
        ];

        if ($intStatus !== null && isset($array[$intStatus])) {
            $array = $array[$intStatus];
        }

        return $array;
    }

    /**
     * 获取状态值对应的颜色信息
     * @param int $intStatus 状态值
     * @return array|string
     */
    public static function getStatusColor($intStatus = null)
    {
        $array = [
            self::STATUS_OPEN => 'btn-success',
            self::STATUS_CLOSE => 'btn-danger',
        ];

        if ($intStatus !== null && isset($array[$intStatus])) {
            $array = $array[$intStatus];
        }

        return $array;
    }

    /**
     * 是否显示值
     * @param null $intStatus
     * @param int $intType
     * @return array|mixed
     */
    public static function getArrayNavStatus($intStatus = null)
    {
        $array = [
            self::STATUS_OPEN => '显示',
            self::STATUS_CLOSE => '隐藏',
        ];

        if ($intStatus !== null && isset($array[$intStatus])) {
            $array = $array[$intStatus];
        }

        return $array;
    }

    /**
     * 获取是否显示值对应的颜色信息
     * @param int $intStatus 状态值
     * @return array|string
     */
    public static function getNavStatusColor($intStatus = null)
    {
        $array = [
            self::STATUS_OPEN => 'btn-success',
            self::STATUS_CLOSE => 'btn-danger',
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
     * 关联管理员表
     * @return ActiveQuery
     */
    public function getAdmin()
    {
        return $this->hasOne(Admin::className(), ['id' => 'create_user_id'])->alias('admin_');
    }

    /**
     * 关联店铺父级表
     * @return ActiveQuery
     */
    public function getParents()
    {
        return $this->hasOne(StoreClass::className(), ['id' => 'pid'])->alias('parents_');
    }

    /**
     * 关联商品表
     * @return ActiveQuery
     */
    public function getGoods()
    {
        return $this->hasMany(Goods::className(), ['store_class_id' => 'id'])->alias('goods_');
    }

    /**
     * 获取分类名称
     * @return array
     */
    public static function getClassName()
    {
        $params = func_get_args();
        if (isset($params[1]) && $params[1]) {
            $arr[]  = $params[1];
        }
        //查找父级数据
        $class_one = self::find()->select(['id','name','pid'])->andFilterWhere(['id' => $params[0]])->asArray()->one();
        if (!empty($class_one)) {
            $arr[] = self::getClassName($class_one['pid'], $class_one['name']);
        }
        return reduce_arr($arr);
    }

    /**
     * 获取分类ID
     * @return array
     */
    public static function getClassId($class_id)
    {
        $params = func_get_args();
        $arr    = array();
        $arr[]  = $params[0];
        //查找父级数据
        $class_one = self::find()->select(['id','name','pid'])->andFilterWhere(['id' => $params[0]])->asArray()->one();
        if (!empty($class_one)) {
            $arr[] = self::getClassId($class_one['pid']);
        }
        return reduce_arr($arr);
    }

    /**
     * 保存后的处理事件
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->create_user_id = Yii::$app->user->id;
            return true;
        }
        return false;
    }

    /**
     * 树形菜单
     * @return array
     */
    public static function getJsMenus()
    {
        $id = 0;
        $text = '全部分类';
        return [
            'id'        => $id,
            'text'      => $text,
            'children'  => self::_getChildren(),
            'state'     => ['opened' => true],
            'icon'      => 'menu-icon fa fa-home'
        ];
    }

    /**
     * 无限极分类
     * @return array
     */
    private static function _getChildren()
    {
        $data = self::queryCondition()->select(['id', 'name', 'pid'])->asArray()->all();
        $items = array();
        foreach ($data as $val) {
            $items[$val['id']] = [
                'id'    => $val['id'],
                'text'  => $val['name'],
                'state' => ['opened' => false],
                'icon'  => 'menu-icon fa fa-globe',
                'pid'   => $val['pid'],
            ];
        }

        $tree = array();
        foreach ($items as $k => $val) {
            if (isset($items[$val['pid']])) {
                $items[$val['pid']]['children'][] = &$items[$k];
            } else {
                $tree[] = &$items[$k];
            }
        }

        unset($items);
        return $tree;
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
}
