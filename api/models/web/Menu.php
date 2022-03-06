<?php

namespace api\models\web;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use jinxing\admin\helpers\Tree;
use api\models\web\traits\AdminModelTrait;

/**
 * This is the model class for table "{{%menu}}".
 *
 * @property integer $id
 * @property string  $pid
 * @property string  $menu_name
 * @property string  $icons
 * @property string  $url
 * @property integer $status
 * @property integer $sort
 * @property integer $created_at
 * @property integer $created_id
 * @property integer $updated_at
 * @property integer $updated_id
 */
class Menu extends \api\models\BaseModel
{
    use AdminModelTrait;

    /**
     * 状态
     */
    const STATUS_ACTIVE = 1; // 启用
    const STATUS_DELETE = 0; // 关闭

    /**
     * @var array 新增时候填写的权限
     */
    public $auth = [];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%menu}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid', 'status', 'sort'], 'integer'],
            [['menu_name', 'status'], 'required'],
            ['url', 'filter', 'filter' => function ($value) {
                return trim($value, '/');
            }, 'when'                  => function ($model) {
                return $model->url;
            }],
            [['menu_name', 'icons', 'url'], 'string', 'max' => 50],
            ['auth', 'safe'],
        ];
    }

    public function scenarios()
    {
        return array_merge(parent::scenarios(),[
            'create' => ['pid','menu_name','icons','url','status','sort'],
            'update' => ['pid','menu_name','icons','url','status','sort'],
            'delete' => ['id'],
            'state'  => ['id','status'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'pid'        => '上级分类',
            'menu_name'  => '栏目名称',
            'icons'      => '图标',
            'url'        => '访问地址',
            'status'     => '状态',
            'sort'       => '排序字段',
            'created_at' => '创建时间',
            'created_id' => '创建用户',
            'updated_at' => '修改时间',
            'updated_id' => '修改用户',
        ];
    }

    /**
     * @Notes: 获取状态值对应的颜色信息
     * @Function getStatusColor
     * @param null $intStatus
     * @return string|string[]
     * @author: Admin
     * @Time: 2022/2/22 13:28
     */
    public static function getStatusColor($intStatus = null)
    {
        $array = [
            self::STATUS_DELETE => 'disable',
            self::STATUS_ACTIVE => 'enable'
        ];

        if ($intStatus !== null && isset($array[$intStatus])) {
            $array = $array[$intStatus];
        }

        return $array;
    }

    /**
     * @Notes: 状态值
     * @Function getArrayStatus
     * @param null $intStatus
     * @return string|string[]
     * @author: Admin
     * @Time: 2022/2/22 13:28
     */
    public static function getArrayStatus($intStatus = null)
    {
        $array = [
            self::STATUS_DELETE => '停用',
            self::STATUS_ACTIVE => '启用'
        ];

        if ($intStatus !== null && isset($array[$intStatus])) {
            $array = $array[$intStatus];
        }

        return $array;
    }

    /**
     * @Notes: 修改之后的处理
     * @Function afterSave
     * @param bool $insert
     * @param array $changedAttributes
     * @return bool
     * @throws \yii\base\Exception
     * @author: Admin
     * @Time: 2022/2/22 13:29
     */
    public function afterSave($insert, $changedAttributes)
    {
        if ($insert && $this->url && $this->auth) {
            $url = explode('/', $this->url);
            array_pop($url);
            if ($url) {
                (new Auth())->batchInsert($this->auth, implode('/', $url) . '/', $this->menu_name);
            }
        }

        return true;
    }

    /**
     * @Notes: 获取用户导航栏信息
     * @Function getUserMenus
     * @param $intUserId
     * @return array
     * @author: Admin
     * @Time: 2022/2/22 13:29
     */
    public static function getUserMenus($intUserId)
    {
        // 管理员登录
        if ($intUserId == Admin::SUPER_ADMIN_ID) {
            $menus = self::find()
                ->where(['status' => self::STATUS_ACTIVE])
                ->orderBy(['sort' => SORT_ASC])
                ->asArray()
                ->all();
        } else {
            // 其他用户登录成功获取权限
            if ($permissions = Yii::$app->getAuthManager()->getPermissionsByUser($intUserId)) {
                $menus = self::getMenusByPermissions($permissions);
            }
        }

        // 没有菜单返回空
        if (empty($menus)) return [];

        // 生成导航信息
        return (new Tree([
            'array'        => $menus,
            'childrenName' => 'child',
            'parentIdName' => 'pid',
        ]))->getTreeArray(0);
    }

    /**
     * @Notes: 通过权限获取导航栏目
     * @Function getMenusByPermissions
     * @param $permissions
     * @return array
     * @author: Admin
     * @Time: 2022/2/22 13:29
     */
    public static function getMenusByPermissions($permissions)
    {
        // 查询导航栏目
        if ($menus = static::findMenus(['url' => array_keys($permissions), 'status' => static::STATUS_ACTIVE])) {
            $sort = ArrayHelper::getColumn($menus, 'sort');
            array_multisort($sort, SORT_ASC, $menus);
        }

        return $menus;
    }

    /**
     * @Notes:
     * @Function findMenus
     * @param $where
     * @return array|ActiveRecord[]
     * @author: Admin
     * @Time: 2022/2/22 13:29
     */
    public static function findMenus($where)
    {
        if ($parents = static::find()->where($where)->asArray()->indexBy('id')->all()) {
            $arrParentIds = [];
            foreach ($parents as $value) {
                if ($value['pid'] != 0 && !isset($parents[$value['pid']])) {
                    $arrParentIds[] = $value['pid'];
                }
            }

            if ($arrParentIds) {
                if ($arrParents = static::findMenus(['id' => $arrParentIds])) {
                    $parents += $arrParents;
                }
            }
        }

        return $parents;
    }

    /**
     * @Notes: 包含权限的树形菜单
     * @Function getJsMenus
     * @param $array
     * @param $arrHaves
     * @return array
     * @author: Admin
     * @Time: 2022/2/22 13:30
     */
    public static function getJsMenus($permissions)
    {
        $key = array_keys($permissions);
        $data = self::getAllMenus(['or like','url',$key]);
        $ids = ArrayHelper::getColumn($data,'id');
        $pids = array_unique(ArrayHelper::getColumn($data,'pid'));
        $ids = array_merge($ids,$pids);
        $data = self::getAllMenus(['id'=>$ids]);
        $items = array();
        foreach ($data as $val) {
            $items[$val['id']] = [
                'id' => $val['id'],
                'text' => $val['menu_name'],
                'pid' => $val['pid'],
            ];
            if ($val['url']) {
                $url = self::formatUrl($val['url']);
                $items[$val['id']]['url'] = $url;
                if (isset($permissions[$url])) {
                    $items[$val['id']]['children'] = $permissions[$url];
                }
            }
        }

        $tree = array();
        foreach ($items as $k => $val) {
            if (isset($items[$val['pid']])) {
                $items[$val['pid']]['children'][] = &$items[$k];
            } else {
                $tree[] = &$items[$k];
            }
        }

        unset($items,$data);
        return $tree;
    }

    /**
     * @Notes: 获取所有
     * @Function getAllMenus
     * @param array $where
     * @return array|ActiveRecord[]
     * @author: Admin
     * @Time: 2022/3/2 14:23
     */
    public static function getAllMenus($where = [])
    {
        $query = self::find();
        $query->select(['id', 'menu_name', 'pid', 'icons','url','sort','status']);
        $query->filterWhere($where);
        $query->andFilterWhere(['status' => self::STATUS_ACTIVE]);
        $query->asArray();
        return $query->all();
    }

    /**
     * @Notes: 获取一条
     * @Function getMenuOne
     * @param $field
     * @param array $condition
     * @return array|ActiveRecord|null
     * @author: Admin
     * @Time: 2022/2/22 13:30
     */
    public static function getMenuOne($field, $condition = [])
    {
        $query = self::find();
        $query->select($field);
        $query->where($condition);
        $query->andFilterWhere(['status' => self::STATUS_ACTIVE]);
        $query->asArray();
        return $query->one();
    }

    /**
     * @Notes: 获取菜单分类数组，数组的键是主键ID
     * @Function getMenusIndexById
     * @param $field
     * @return array|ActiveRecord[]
     * @author: Admin
     * @Time: 2022/2/22 13:30
     */
    public static function getMenusIndexById($field)
    {
        return self::find()->select($field)->where([
            'status' => self::STATUS_ACTIVE,
        ])->orderBy(['sort' => SORT_ASC])->indexBy('id')->asArray()->all();
    }

    /**
     * @Notes: 获取树形结构数据
     * @Function getMenusData
     * @param int $selectedId
     * @return array
     * @author: Admin
     * @Time: 2022/2/22 13:30
     */
    public static function getMenusData($selectedId = 0)
    {
        $menus = self::getMenusIndexById(['id', 'menu_name', 'pid']);

        $strOptions = (new Tree(['array' => $menus, 'parentIdName' => 'pid']))
            ->getTree(0, '<option {extend_selected} value="{id}" data-pid="{pid}"> {extend_space}{menu_name} </option>');

        return [
            'menus' => $menus,
            'options' => $strOptions
        ];
    }

    /**
     * @Notes: 获取分类名称
     * @Function getClassName
     * @param $class_id
     * @param string $class_name
     * @return array
     * @author: Admin
     * @Time: 2022/2/22 13:31
     */
    public static function getClassName($class_id, $class_name = "")
    {
        $arr = array();
        if (!empty($class_name)) {
            $arr[] = $class_name;
        }
        //查找父级数据
        $class_one = self::getMenuOne(['id', 'menu_name', 'pid'], ['id' => $class_id]);
        if (!empty($class_one)) {
            $arr[] = self::getClassName($class_one['pid'], $class_one['menu_name']);
        }
        return reduce_arr($arr);
    }

    /**
     * @Notes: 树形菜单
     * @param $where
     * @Function getJsTreeMenus
     * @return array
     * @author: Admin
     * @Time: 2022/2/22 13:31
     */
    public static function getJsTreeMenus($where = [])
    {
        return self::_getChildren($where);
    }

    /**
     * @Notes: 无限极分类
     * @param array $permissions
     * @Function _getChildren
     * @return array
     * @author: Admin
     * @Time: 2022/2/22 13:31
     */
    private static function _getChildren($where)
    {
        $data = self::getAllMenus($where);
        $items = $tree = array();
        foreach ($data as $val) {
            $val['status'] = Menu::getStatusColor($val['status']);
            $items[$val['id']] = $val;
        }
        foreach ($items as $k => $val) {
            if (isset($items[$val['pid']])) {
                $items[$val['pid']]['children'][] = &$items[$k];
            } else {
                $tree[] = &$items[$k];
            }
        }
        unset($items,$data);
        return $tree;
    }

    /**
     * @Notes: 关联菜单表
     * @Function getChildrens
     * @return \yii\db\ActiveQuery
     * @author: Admin
     * @Time: 2022/2/22 13:31
     */
    public function getChildrens()
    {
        return $this->hasMany(Menu::className(), ['pid' => 'id']);
    }

    /**
     * @Notes: 格式化地址
     * @Function formatUrl
     * @param $url
     * @return string
     * @author: Admin
     * @Time: 2022/3/1 9:17
     */
    public static function formatUrl ($url)
    {
        $arr = explode('/',$url);
        array_pop($arr);
        return implode('/',$arr);
    }
}
