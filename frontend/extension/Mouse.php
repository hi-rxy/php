<?php
namespace frontend\extension;

use frontend\events\MyEvent;

/**
 * 定义处理事件
 *
 * Class Mourse
 * @package frontend\extension
 */
class Mouse
{
    /**
     * @param $e MyEvent
     */
    public function run ($e)
    {
        echo $e->message;

        echo "i am running <br/>";
    }
}