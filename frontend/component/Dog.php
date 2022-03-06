<?php
namespace frontend\component;

use frontend\behaviors\AnimalBehavior;
use yii\base\Component;

/**
 * 行为
 * 无须改变类继承关系即可增强一个已有的类功能
 * 当行为附加到组件后，它将“注入”它的方法和属性到组件， 然后可以像访问组件内定义的方法和属性一样访问它们。 此外，行为通过组件能响应被触发的事件，从而自定义或调整组件正常执行的代码。
 * Class Dog
 * @package frontend\extension
 */
class Dog extends Component
{
    public function behaviors()
    {
        return [
            AnimalBehavior::className()
        ];
    }

    public function look(){
        echo "i am looking<br/>";
    }
}