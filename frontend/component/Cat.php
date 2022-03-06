<?php
namespace frontend\component;

use frontend\events\MyEvent;
use yii\base\Component;

/**
 * 定义触发事件
 *
 * Class Cat
 * @package frontend\component
 */
class Cat extends Component
{
    const EVENT_SHOUT = 'miao';

    public function shout()
    {
        echo "mima miao miao <br/>";

        $event = new MyEvent();
        $event->message = "hello my event <br/>";

        $this->trigger(self::EVENT_SHOUT, $event);
    }
}