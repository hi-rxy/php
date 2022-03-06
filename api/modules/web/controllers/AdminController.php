<?php

namespace api\modules\web\controllers;

use Yii;
use api\controllers\AuthController;
use api\models\web\Admin;
use yii\helpers\ArrayHelper;

class AdminController extends AuthController
{
    public $modelClass = 'api\models\web\Admin';

    protected function getField ()
    {
        return [
                '_yii2.id','_yii2.username','_yii2.email','_yii2.face','_yii2.role','_yii2.status','_yii2.last_time','_yii2.last_ip','_yii2.created_at','_yii2.created_id'
        ];
    }

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
        $name = $this->request->get('username', '');
        $intUid = (int)ArrayHelper::getValue($this->module, 'userId');
        if ($name != '') {
            $where[] = ['like', '_yii2.username', $name];
        }
        if ($intUid != Admin::SUPER_ADMIN_ID) {
            $where[] = ['or',['_yii2.created_id' => $intUid]];
            $where[] = ['and',['!=', '_yii2.id' , $intUid],['!=', '_yii2.id' , Admin::SUPER_ADMIN_ID]];
        }
        return $where;
    }

    /**
     * @Notes: 管理员信息
     * @Function actionAdmin
     * @return array
     * @author: Admin
     * @Time: 2022/2/21 14:00
     */
    public function actionAdmin()
    {
        $data = $this->module->getAdmin();
        $data['name'] = $data['username'];
        $data['avatar'] = $data['face'];
        $data['roles'] = explode(',',$data['role']);
        # 不显示以下字段
        unset(
            $data['role'],
            $data['face'],
            $data['address'],
            $data['age'],
            $data['auth_key'],
            $data['password_hash'],
            $data['password_reset_token'],
            $data['created_at'],
            $data['created_id'],
            $data['updated_at'],
            $data['updated_id'],
            $data['last_time'],
            $data['last_ip']
        );
        return $this->success($data);
    }

    /**
     * @Notes: 管理员退出
     * @Function actionLogout
     * @return array
     * @author: Admin
     * @Time: 2022/2/21 14:01
     */
    public function actionLogout()
    {
        Yii::$app->cache->delete($this->module->token);
        return $this->success();
    }

    /**
     * @Notes: 用户角色
     * @Function actionGetRoles
     * @return array
     * @author: Admin
     * @Time: 2022/2/25 14:00
     */
    public function actionGetRoles ()
    {
        $uid = ArrayHelper::getValue($this->module,'userId');
        $roles = Admin::getArrayRole($uid);
        $data = [];
        foreach ($roles as $key => $item) {
            $data[] = [
                'value' => $key,
                'label' => $key
            ];
        }
        return $this->success($data);
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
        return $this->success(Admin::store($this->getObject(), $this->handleParams($data)));
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
        return $this->success(Admin::store($this->getObject(), $this->handleParams($data)));
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
        return $this->success(Admin::del($this->getObject(), $this->request->post()));
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
        return $this->success(Admin::store($this->getObject(), $this->handleParams($data)));
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
            $item['isSuper'] = Admin::SUPER_ADMIN_ID == $item['id'];
            $item['status'] = Admin::getStatusColor($item['status']);
            $item['last_time'] = !$item['last_time']?'':date('Y-m-d H:i:s',$item['last_time']);
            $item['created_at'] = date('Y-m-d H:i:s',$item['created_at']);
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
        $status = array_flip(Admin::getStatusColor());
        if (!in_array($data['status'],array_keys($status))) {
            return $data;
        }
        $data['status'] = $status[$data['status']];
        return $data;
    }
}
