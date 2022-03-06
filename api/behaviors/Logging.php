<?php

namespace jinxing\admin\behaviors;

use yii;
use yii\base\Event;
use yii\db\BaseActiveRecord;
use yii\web\User;
use yii\di\Instance;
use yii\base\Behavior;
use yii\base\Controller;
use yii\base\ActionEvent;
use jinxing\admin\models\AdminLog;

/**
 * Class LoggingBehavior 日志记录
 *
 * @package jinxing\admin\behaviors
 */
class Logging extends Behavior
{
    /**
     * @var string 使用的用户组件
     */
    public $user = 'user';

    /**
     * @var array 需要记录日志的action
     */
    public $needLogActions = ['create', 'update', 'delete', 'delete-all', 'editable', 'upload'];

    /**
     * 定义事件对应的方法
     *
     * @return array
     */
    public function events()
    {
        return [
            //控制器方法执行后触发事件
            Controller::EVENT_AFTER_ACTION => 'afterAction'
        ];
    }

    /**
     * 初始化赋值 从服务定位器检索组件
     *
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();
        if ($this->user !== false) {
            $this->user = Instance::ensure($this->user, User::className());
        }
    }

    /**
     * @param ActionEvent $event
     */
    public function afterAction($event)
    {
//        if (in_array($event->action->id, $this->needLogActions) && !Yii::$app->request->isGet) {
//            AdminLog::create($event->action, $this->user, $event->result);
//        }
    }
}