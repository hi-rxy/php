<?php
namespace frontend\behaviors;

use yii\base\Behavior;

class AnimalBehavior extends Behavior
{
    public $height;

    public function eat(){
        echo "dog eat<br/>";
    }

    public function events()
    {
        return [
            'wang' => 'shout'
        ];
    }

    public function shout ()
    {
        echo "wang wang wang<br/>";
    }
}