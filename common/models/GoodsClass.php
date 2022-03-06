<?php
namespace common\models;

use common\models\traits\BaseModelTrait;
use common\services\Service;
use jinxing\admin\helpers\Helper;
use jinxing\admin\helpers\Tree;
use Yii;
use yii\base\Exception;
use yii\base\ModelEvent;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Transaction;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%goods_class}}".
 *
 * @property int $id
 * @property int $type_id 类型id
 * @property string $name 分类名称
 * @property string $keywords 分类关键词
 * @property string $desc 分类描述
 * @property int $pid 父级id
 * @property int $status 0表示关闭1表示开启
 * @property int $is_nav 0表示不显示1表示显示
 * @property string $letter 首字母
 * @property int $sort 分类排序
 * @property string $url 链接地址
 * @property string $level 级别
 */
class GoodsClass extends Service
{
    const STATUS_OPEN = 1; // 开启
    const STATUS_CLOSE = 0; // 关闭

    public static $statusArr = [ // 状态值
        ['关闭', '开启'],
        ['隐藏', '显示']
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%goods_class}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type_id', 'pid', 'status', 'is_nav', 'sort', 'level'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['keywords', 'desc', 'url'], 'string', 'max' => 200],
            [['letter'], 'string', 'max' => 10],
            // trim
            [['name', 'keywords', 'desc', 'url', 'letter'], 'trim'],
            // unique
            [['name'], 'unique'],
            // scenarios 设置场景
            [['sort'], 'integer', 'on' => ['sort']],
            [['status'], 'integer', 'on' => ['status']],
            [['is_nav'], 'integer', 'on' => ['is_nav']],
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
            'type_id' => 'Type ID',
            'name' => 'Name',
            'keywords' => 'Keywords',
            'desc' => 'Desc',
            'pid' => 'Pid',
            'status' => 'Status',
            'is_nav' => 'Is Nav',
            'letter' => 'Letter',
            'sort' => 'Sort',
            'url' => 'Url',
            'level' => 'Level',
        ];
    }

    /**
     * 状态值
     * @param null $intStatus
     * @param int $intType
     * @return array|mixed
     */
    public static function getArrayStatus($intType = 0, $intStatus = null)
    {
        $array = [
            self::STATUS_OPEN => self::$statusArr[$intType][1],
            self::STATUS_CLOSE => self::$statusArr[$intType][0],
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
            self::STATUS_OPEN => 'btn-success',
            self::STATUS_CLOSE => 'btn-danger',
        ];

        if ($intStatus !== null && isset($array[$intStatus])) {
            $array = $array[$intStatus];
        }

        return $array;
    }

    /**
     * 关联商品类型表
     * @return ActiveQuery
     */
    public function getGoodsType()
    {
        return $this->hasOne(GoodsType::className(), ['id' => 'type_id'])->alias('goods_type_');
    }

    /**
     * 关联商品表
     * @return ActiveQuery
     */
    public function getGoods()
    {
        return $this->hasMany(Goods::className(), ['class_id' => 'id'])->alias('goods_');
    }

    /**
     * 关联分类子集表
     * @return ActiveQuery
     */
    public function getChildren()
    {
        return $this->hasMany(GoodsClass::className(), ['pid' => 'id']);
    }

    /**
     * 获取分类名称
     * @return array
     */
    public static function getClassName()
    {
        $params = func_get_args();
        $arr    = array();
        if (isset($params[1]) && $params[1]) {
            $arr[]  = $params[1];
        }
        //查找父级数据
        $class_one = self::queryCondition()->select(['id', 'name', 'pid'])->andFilterWhere(['id' => $params[0]])->asArray()->one();
        if (!empty($class_one)) {
            $arr[] = self::getClassName($class_one['pid'], $class_one['name']);
        }
        return reduce_arr($arr);
    }

    /**
     * 获取父级分类ID并且包含当前ID
     * @return array
     */
    public static function getClassId()
    {
        $params = func_get_args();
        $arr    = array();
        $arr[]  = $params[0];
        //查找父级数据
        $class_one = self::queryCondition()->select(['id', 'name', 'pid'])->andFilterWhere(['id' => $params[0]])->asArray()->one();
        if (!empty($class_one)) {
            $arr[] = self::getClassId($class_one['pid']);
        }
        return reduce_arr($arr);
    }

    /**
     * 删除前检查 是否包含子级
     * 如果删除的是父级分类 则需要查询是否存在子级分类 存在则不允许删除
     * @return bool
     */
    public function beforeDelete()
    {
        if (!$this->pid) {
            if (self::find()->where(['pid' => $this->id, 'status' => self::STATUS_OPEN])->count()) {
                $event = new ModelEvent();
                $event->isValid = false;
                return $this->addError('pid', '当前父级存在子级，不允许删除');
            }
        }
        return parent::beforeDelete();
    }

    /**
     * 获取树形结构数据
     * @param int $selectedId
     * @return array
     */
    public static function getGoodsClassData($selectedId = 0)
    {
        // 查询父级分类信息
        $goodsClass = self::queryCondition()->select(['id', 'name', 'pid'])->indexBy('id')->asArray()->all();

        // 处理显示select
        $strOptions = (new Tree(['array' => $goodsClass, 'parentIdName' => 'pid']))
            ->getTree(0, '<option {extend_selected} value="{id}" data-pid="{pid}"> {extend_space}{name} </option>', $selectedId);

        return [
            'goodsClass' => $goodsClass,
            'options' => $strOptions
        ];
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
        foreach ($data as $item) {
            $items[$item['id']] = [
                'id'    => $item['id'],
                'text'  => $item['name'],
                'state' => ['opened' => false],
                'icon'  => 'menu-icon fa fa-globe',
                'pid'   => $item['pid'],
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
