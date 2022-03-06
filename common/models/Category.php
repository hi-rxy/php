<?php
namespace common\models;

use common\services\Service;
use jinxing\admin\helpers\Tree;
use yii\base\ModelEvent;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%category}}".
 *
 * @property int $id 分类自增ID
 * @property int $parent_id 父级分类
 * @property string $name 分类名称
 * @property string $alias 分类别名
 * @property int $sort 分类排序
 * @property string $template 分类模板
 * @property string $article_template 文章模板
 * @property string $remark 分类备注
 * @property int $created_at
 * @property int $updated_at
 */
class Category extends Service
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%category}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parent_id', 'sort', 'created_at', 'updated_at'], 'integer'],
            // trim : 去除字符串首尾空格
            [['name', 'alias'], 'trim'],
            // unique : 唯一性
            [['name'], 'unique', 'message' => '名称已经存在'],
            // 场景
            [['sort'], 'required', 'on' => 'sort'],
            // required : 必须值验证属性
            [['name', 'alias'], 'required'],
            [['name', 'alias', 'template', 'article_template', 'remark'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => 'Parent ID',
            'name' => 'Name',
            'alias' => 'Alias',
            'sort' => 'Sort',
            'template' => 'Template',
            'article_template' => 'Article Template',
            'remark' => 'Remark',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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
     * 关联文章表
     * @return ActiveQuery
     */
    public function getArticle()
    {
        return $this->hasMany(Article::className(), ['category_id' => 'id'])->alias('article_');
    }

    /**
     * 关联分类表
     * @return ActiveQuery
     */
    public function getChildren()
    {
        return $this->hasMany(Category::className(), ['parent_id' => 'id']);
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
        $class_one = self::findOne($params[0]);
        if (!is_null($class_one)) {
            $arr[] = self::getClassName($class_one['parent_id'], $class_one['name']);
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
        if (!$this->parent_id) {
            if (self::find()->where(['parent_id' => $this->id])->count()) {
                $event = new ModelEvent();
                $event->isValid = false;
                return $this->addError('parent_id', '当前父级存在子级，不允许删除');
            }
        }
        return parent::beforeDelete();
    }

    /**
     * 获取树形结构数据
     * @param int $selectedId
     * @return array
     */
    public static function getCategoryData($selectedId = 0)
    {
        // 查询父级分类信息
        $category = self::find()->select(['id', 'name', 'parent_id'])->indexBy('id')->asArray()->all();

        // 处理显示select
        $strOptions = (new Tree(['array' => $category, 'parentIdName' => 'parent_id']))
            ->getTree(0, '<option {extend_selected} value="{id}" data-pid="{parent_id}"> {extend_space}{name} </option>', $selectedId);

        return [
            'category' => $category,
            'options' => $strOptions
        ];
    }

    /**
     * 树形菜单
     * @param array $category
     * @return array
     */
    public static function getJsMenus()
    {
        $id     = 0;
        $text   = '全部分类';
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
        $data   = self::find()->select(['id', 'name', 'parent_id'])->asArray()->all();
        $items  = array();
        foreach ($data as $val) {
            $items[$val['id']] = [
                'id'        => $val['id'],
                'text'      => $val['name'],
                'state'     => ['opened' => false],
                'icon'      => 'menu-icon fa fa-globe',
                'parent_id' => $val['parent_id'],
            ];
        }

        $tree = array();
        foreach ($items as $k => $val) {
            if (isset($items[$val['parent_id']])) {
                $items[$val['parent_id']]['children'][] = &$items[$k];
            } else {
                $tree[] = &$items[$k];
            }
        }

        unset($items);
        return $tree;
    }
}
