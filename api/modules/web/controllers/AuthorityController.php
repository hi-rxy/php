<?php

namespace api\modules\web\controllers;

use api\controllers\AuthController;
use api\models\web\Auth;
use api\models\web\Menu;

class AuthorityController extends AuthController
{
    public $modelClass = 'api\models\web\Auth';

    /**
     * @Notes: 展示字段
     * @Function getField
     * @return string[]
     * @author: Admin
     * @Time: 2022/2/26 11:38
     */
    protected function getField()
    {
        return [
            '_yii2.name', '_yii2.type', '_yii2.description', '_yii2.created_at','_yii2.updated_at'
        ];
    }

    /**
     * @Notes: 条件
     * @Function: getWhere
     * @return array|void
     * @Author: 17908
     * @Time: 2022/2/25 0025 19:50
     */
    protected function getWhere ()
    {
        $name = $this->request->get('name', '');
        $id = $this->request->get('id', '');
        $where = [ 'and' ];
        $where[] = [ '_yii2.type' => Auth::TYPE_PERMISSION ];
        if ($name != '') {
            $where[] = ['like', '_yii2.name', $name];
        }
        if ($id != '') {
            $menu = Menu::getMenuOne('url',['id' => $id]);
            if ($menu['url']) {
                $where[] = ['like', '_yii2.name', Menu::formatUrl($menu['url'])];
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
        $data['type'] = Auth::TYPE_PERMISSION;
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
        $data['type'] = Auth::TYPE_PERMISSION;
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
}
