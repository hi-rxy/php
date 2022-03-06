<?php
namespace frontend\controllers;


use frontend\behaviors\AnimalBehavior;
use frontend\component\Cat;
use frontend\component\Dog;
use frontend\extension\Mouse;
use yii\base\Event;
use yii\web\Controller;


/**
 * 测试事件
 *
 * 事件机制
 *
 * 1、扫描式
 * 2、绑定式
 *
 * 处理事件 -> 触发事件 -> 绑定事件
 *
 * Class EventController
 * @package frontend\controllers
 */
class AnimalController extends Controller
{
    const EVENT_SHOUT = 'miao';

    public function actionIndex ()
    {
        // 绑定事件
        Event::on(Cat::className(),self::EVENT_SHOUT, [(new Mouse()), 'run']);
        (new Cat())->shout();
    }

    public function actionDemo ()
    {
        $dog = new Dog();

        /** @var $dog Dog*/
        $dog->look();

        /** @var $dog AnimalBehavior*/
        $dog->eat();
        $dog->height = '15cm <br/>';

        echo $dog->height;

        $dog->trigger('wang');
    }
}