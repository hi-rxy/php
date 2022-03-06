<?php

namespace api\modules\web\controllers;

use api\controllers\AuthController;
use api\models\web\Menu;
use yii\helpers\ArrayHelper;

class MenuController extends AuthController
{
    public $modelClass = 'api\models\web\Menu';

    /**
     * @Notes: 条件
     * @Function actionCreate
     * @return array
     * @author: Admin
     * @Time: 2022/2/22 15:13
     */
    protected function getWhere()
    {
        $where = ['and'];
        $name = $this->request->get('menu_name', '');
        $id = $this->request->get('id', '');
        if ($name != '') {
            $where[] = ['like', 'menu_name', $name];
        }
        if ($id != '') {
            $menu = Menu::getMenuOne('pid',['id' => $id]);
            if (!$menu['pid']) {
                $where[] = ['pid' => $id];
            } else {
                $where[] = ['id' => $id];
            }
        }
        return $where;
    }

    /**
     * @Notes: 根据角色获取菜单
     * @Function actionLogout
     * @return array
     * @author: Admin
     * @Time: 2022/2/21 14:01
     */
    public function actionMenuByRole()
    {
        //$uid = ArrayHelper::getValue($this->module,'userId');
        //return $this->success(Menu::getUserMenus($uid));
        $vueRouters = [
            'path' => '/system',
            'component' => 'Layout',
            'redirect' => 'noRedirect',
            'name' => 'System',
            'meta' => [
                'title' => '后台管理',
                'icon' => 'dashboard',
            ],
            'children' => [
                [
                    'path' => 'menu',
                    'component' => 'menu',
                    'name' => 'TreeMenu',
                    'meta' => [
                        'title' => '菜单管理',
                        'roles' => [
                            'admin','editor'
                        ],
                    ]
                ],
                [
                    'path' => 'admin',
                    'component' => 'admin',
                    'name' => 'Admin',
                    'meta' => [
                        'title' => '用户管理',
                        'roles' => [
                            'admin','editor'
                        ],
                    ]
                ], [
                    'path' => 'role',
                    'component' => 'role',
                    'name' => 'Role',
                    'meta' => [
                        'title' => '角色管理',
                        'roles' => [
                            'admin','editor'
                        ],
                    ]
                ],[
                    'path' => 'permission',
                    'component' => 'permission',
                    'name' => 'Permission',
                    'meta' => [
                        'title' => '权限管理',
                        'roles' => [
                            'admin','editor'
                        ],
                    ]
                ],[
                    'path' => 'table',
                    'component' => 'table',
                    'name' => 'Table',
                    'meta' => [
                        'title' => '数据管理',
                        'roles' => [
                            'admin','editor'
                        ],
                    ]
                ],[
                    'path' => 'config-group',
                    'component' => 'config-group',
                    'name' => 'ConfigGroup',
                    'meta' => [
                        'title' => '配置管理',
                        'roles' => [
                            'admin','editor'
                        ],
                    ]
                ],[
                    'path' => 'configs',
                    'component' => 'configs',
                    'name' => 'Configs',
                    'meta' => [
                        'title' => '同步配置',
                        'roles' => [
                            'admin','editor'
                        ],
                    ]
                ],
            ]
        ];
        return $this->success([$vueRouters]);
    }

    /**
     * @Notes: 树形菜单
     * @Function actionTreeMenu
     * @return array
     * @author: Admin
     * @Time: 2022/2/23 14:54
     */
    public function actionTreeMenu()
    {
        $where = $this->getWhere();
        return $this->success(Menu::getJsTreeMenus($where));
    }

    /**
     * @Notes: 展示
     * @Function actionTreeMenu
     * @return array
     * @author: Admin
     * @Time: 2022/2/23 14:54
     */
    public function actionView($id)
    {
        return Menu::getMenuOne([
            'id', 'pid', 'menu_name', 'icons', 'url', 'status', 'sort'
        ], [
            'id' => $id
        ]);
    }

    /**
     * @Notes: 添加
     * @Function actionCreate
     * @return array
     * @author: Admin
     * @Time: 2022/2/22 15:13
     */
    public function actionCreate()
    {
        $data = $this->request->post();
        return $this->success(Menu::store($this->getObject(), $this->handleParams($data)));
    }

    /**
     * @Notes: 修改
     * @Function actionUpdate
     * @return array
     * @author: Admin
     * @Time: 2022/2/22 15:31
     */
    public function actionUpdate()
    {
        $data = $this->request->post();
        return $this->success(Menu::store($this->getObject(),$this->handleParams($data)));
    }

    /**
     * @Notes: 删除
     * @Function actionDelete
     * @return array
     * @author: Admin
     * @Time: 2022/2/22 15:31
     */
    public function actionDelete()
    {
        return $this->success(Menu::del($this->getObject(), $this->request->post()));
    }

    /**
     * @Notes: 状态
     * @Function actionState
     * @return array
     * @throws \api\exceptions\HttpBadRequestException
     * @author: Admin
     * @Time: 2022/2/23 15:18
     */
    public function actionState()
    {
        $data = $this->request->post();
        return $this->success(Menu::store($this->getObject(), $this->handleParams($data)));
    }

    /**
     * @Notes: 查询修改
     * @Function afterSearch
     * @param array $array
     * @author: Admin
     * @Time: 2022/2/23 14:20
     */
    protected function afterSearch(&$array = [])
    {
        foreach ($array as $vo => &$item) {
            $item['status'] = Menu::getStatusColor($item['status']);
            $item['created_at'] = date('Y-m-d H:i:s',$item['created_at']);
            $item['updated_at'] = date('Y-m-d H:i:s',$item['updated_at']);
        }
    }

    /**
     * @Notes: 其他参数处理
     * @Function handleParams
     * @param $data
     * @return mixed
     * @author: Admin
     * @Time: 2022/2/26 11:55
     */
    private function handleParams ($data)
    {
        $status = array_flip(Menu::getStatusColor());
        if (!in_array($data['status'],array_keys($status))) {
            return $data;
        }
        $data['status'] = $status[$data['status']];
        return $data;
    }
}
