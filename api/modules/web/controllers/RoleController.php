<?php

namespace api\modules\web\controllers;

use api\controllers\AuthController;
use api\exceptions\HttpBadRequestException;
use api\models\web\Admin;
use api\models\web\Auth;
use api\models\web\Menu;
use Yii;
use yii\helpers\ArrayHelper;

class RoleController extends AuthController
{
    public $modelClass = 'api\models\web\Auth';

    /**
     * @Notes: 条件
     * @Function: getWhere
     * @return array|void
     * @Author: 17908
     * @Time: 2022/2/25 0025 19:50
     */
    protected function getWhere ()
    {
        $where = [ 'and' ];
        $where[] = [ 'type' => Auth::TYPE_ROLE ];
        $uid = $this->module->getUserId();
        $name = $this->request->get('name', '');
        if ($uid != Admin::SUPER_ADMIN_ID) {
            // 获取用户的所有角色
            if ($roles = Yii::$app->authManager->getRolesByUser($uid)) {
                $where[] = [ 'in', '_yii2.name', array_keys($roles) ];
                $where[] = ['like', '_yii2.name', $name];
            }
        }
        return $where;
    }

    /**
     * @Notes: 添加
     * @Function actionCreate
     * @return array
     * @author: Auth
     * @Time: 2022/2/22 15:13
     */
    public function actionCreate ()
    {
        $data = $this->request->post();
        $data['type'] = Auth::TYPE_ROLE;
        return $this->success(Auth::store($this->getObject(), $data));
    }

    /**
     * @Notes: 修改
     * @Function actionUpdate
     * @return array
     * @author: Auth
     * @Time: 2022/2/22 15:31
     */
    public function actionUpdate ()
    {
        $data = $this->request->post();
        $data['type'] = Auth::TYPE_ROLE;
        return $this->success(Auth::store($this->getObject(), $data));
    }

    /**
     * @Notes: 删除
     * @Function actionDelete
     * @return array
     * @author: Auth
     * @Time: 2022/2/22 15:31
     */
    public function actionDelete ()
    {
        return $this->success(Auth::del($this->getObject(), $this->request->post()));
    }

    /**
     * @Notes: 分配权限
     * @Function actionAssignPermissions
     * @return array
     * @throws HttpBadRequestException
     * @author: Admin
     * @Time: 2022/3/1 15:52
     */
    public function actionAssignPermissions ()
    {
        $name = $this->request->get('name','');
        $uid = ArrayHelper::getValue($this->module,'userId');
        $objAuth  = Yii::$app->getAuthManager();
        $mixRoles = $objAuth->getAssignment($name, $uid);
        if (!$mixRoles && $uid != Admin::SUPER_ADMIN_ID) {
            throw new HttpBadRequestException('Sorry, you do not have permission to modify this role!');
        }
        # 1、该角色正在使用的权限集合
        $oldPermissions = $objAuth->getPermissionsByRole($name);
        $oldPermissions = ArrayHelper::toArray($oldPermissions);
        $routers = array_keys($oldPermissions);

        #2、当前管理员可分配的权限集合
        $permissions = Auth::getPermissions($uid);
        $permissions = $this->handlePermissionGroups($permissions);

        #3、将权限路径加入到所属的父节点
        $menus = Menu::getJsMenus($permissions);

        return $this->success(compact('menus','routers','permissions'));
    }

    /**
     * @Notes: 给角色分配权限
     * @Function actionSetPermissionByRole
     * @return array
     * @throws HttpBadRequestException
     * @throws \yii\base\Exception
     * @author: Admin
     * @Time: 2022/3/2 15:27
     */
    public function actionSetPermissionByRole ()
    {
        $data = $this->request->post();
        if (!$data['nodes'] || !$data['name'])
            throw new HttpBadRequestException('对不起，您没有选择权限，此操作已拒绝!');
        $nodes = $data['nodes'];
        $name = $data['name'];
        $model = $this->findModel($name);
        $permissions = $this->getChildrenUrl($nodes);
        $result = $model->savePermissionsByRole($name, $permissions);
        return $this->success($result);
    }

    private function getChildrenUrl ($nodes)
    {
        $data = [];
        foreach ($nodes as $item) {
            if (isset($item['pid'])) {
                $this->getChildrenUrl($item['children']);
            } else {
                $data[] = $item['url'];
            }
        }
        return $data;
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
            $item['created_at'] = date('Y-m-d H:i:s',$item['created_at']);
            $item['updated_at'] = date('Y-m-d H:i:s',$item['updated_at']);
        }
    }

    /**
     * @Notes: 获取角色实列
     * @Function findModel
     * @param $name
     * @return Auth|void
     * @author: Admin
     * @Time: 2022/3/1 9:37
     */
    protected function findModel ( $name )
    {
        if ($name) {
            $auth = Yii::$app->getAuthManager();
            $model = new Auth();
            $role = $auth->getRole($name);
            if ($role) {
                $model->name = $role->name;
                $model->type = $role->type;
                $model->description = $role->description;
                $model->created_at = $role->createdAt;
                $model->updated_at = $role->updatedAt;
                $model->setIsNewRecord(false);
                return $model;
            }
        }
    }

    /**
     * @Notes: 重新组合权限集合
     * @Function handlePermissionGroups
     * @param $permissions
     * @return array
     * @author: Admin
     * @Time: 2022/3/1 9:36
     */
    protected function handlePermissionGroups($permissions)
    {
        $items = [];
        foreach ($permissions as $name => $value) {
            $names = explode('/', $name);
            array_pop($names);
            $index = implode('/', $names);
            if (!isset($items[$index])) {
                $items[$index] = [];
            }

            $items[$index][] = [
                'url' => $name,
                'text' => $value,
            ];
        }
        return $items;
    }
}
