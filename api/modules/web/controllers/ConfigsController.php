<?php
namespace api\modules\web\controllers;

use api\controllers\AuthController;
use api\models\web\Config;
use api\models\web\ConfigGroup;

class ConfigsController extends AuthController
{
    public $modelClass = "api\models\web\Config";

    /**
     * @Notes: 条件
     * @Function getWhere
     * @return string[]
     * @author: Admin
     * @Time: 2022/3/3 15:31
     */
    protected function getWhere()
    {
        $where = ['and'];
        $name = $this->request->get('name','');
        $keyword = $this->request->get('keyword','');
        $group_id = $this->request->get('gid','');
        if ($keyword != '') {
            $where[] = ['like' , 'name' , $keyword];
        }
        if ($name != '') {
            $group = ConfigGroup::queryCondition()->andWhere(['name' => $name])->one();
            $where[] = ['group_id' => $group['id']];
        }
        if ($group_id != '') {
            $where[] = ['group_id' => $group_id];
        }
        return $where;
    }

    /**
     * @Notes: 获取配置
     * @Function actionSetConfigs
     * @return array
     * @author: Admin
     * @Time: 2022/3/4 16:17
     */
    public function actionSetConfigs ()
    {
        return $this->success(Config::getConfigs($this->request->get('name','')));
    }

    /**
     * @Notes: 添加
     * @Function actionCreate
     * @return array
     * @throws \api\exceptions\ErrorMsgException
     * @throws \api\exceptions\HttpBadRequestException
     * @author: Admin
     * @Time: 2022/3/3 8:50
     */
    public function actionCreate ()
    {
        $data = $this->request->post();
        return $this->success(Config::store($this->getObject(), $this->handleParams($data)));
    }

    /**
     * @Notes: 修改
     * @Function actionUpdate
     * @return array
     * @throws \api\exceptions\ErrorMsgException
     * @throws \api\exceptions\HttpBadRequestException
     * @author: Admin
     * @Time: 2022/3/3 8:50
     */
    public function actionUpdate ()
    {
        $data = $this->request->post();
        return $this->success(Config::store($this->getObject(), $this->handleParams($data)));
    }

    /**
     * @Notes: 删除
     * @Function actionDelete
     * @return array
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     * @author: Admin
     * @Time: 2022/3/3 8:50
     */
    public function actionDelete ()
    {
        return $this->success(Config::del($this->getObject(), $this->request->post()));
    }

    /**
     * @Notes: 保存各配置值
     * @Function: actionUpdateConfigs
     * @return array
     * @throws \api\exceptions\HttpBadRequestException
     * @Author: 17908
     * @Time: 2022/3/5 0005 21:20
     */
    public function actionUpdateConfigs ()
    {
        return $this->success($this->getObject()->saveFields($this->request->post()));
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
            $item['status'] = Config::getStatusColor($item['status']);
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
        $status = array_flip(Config::getStatusColor());
        if (!in_array($data['status'],array_keys($status))) {
            return $data;
        }
        $data['status'] = $status[$data['status']];
        return $data;
    }
}
