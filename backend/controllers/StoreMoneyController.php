<?php
namespace backend\controllers;



/**
 * Class StoreMoneyController 店铺金额 执行操作控制器
 * @package backend\controllers
 */
class StoreMoneyController extends Controller
{
    /** @var string pk 定义表使用的主键名称 */
protected $pk = 'id'; 
    
    /** @var string sort 定义默认排序字段名称 */
protected $sort = 'id'; 
   
    /**
     * @var string 定义使用的model
     */
    public $modelClass = 'common\models\StoreMoney';
    
    /**
     * 需要定义where 方法，确定前端查询字段对应的查询方式
     * 
     * @return array 
     */
    public function where()
    {
        return [
            [['id'], '='],
        ];
    }
}
