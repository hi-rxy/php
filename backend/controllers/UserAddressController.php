<?php
namespace backend\controllers;

use common\models\Region;
use common\models\UserAddress;
use jinxing\admin\helpers\Helper;
use Yii;
use yii\web\Request;

/**
 * Class UserAddressController 收货地址 执行操作控制器
 * @package backend\controllers
 */
class UserAddressController extends Controller
{
    /** @var string pk 定义表使用的主键名称 */
    protected $pk = 'id';
    
    /** @var string sort 定义默认排序字段名称 */
    protected $sort = 'id';
   
    /**
     * @var string 定义使用的model
     */
    public $modelClass = 'common\models\UserAddress';

    public $uid = 1;
    
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

    # 异步保存用户地址
    public function actionAsyncCreate ()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            $data = $this->setPostData($request);
            $list = UserAddress::tableGetData(['user_id' => $this->uid]);

            if (!empty($list)) {
                $count = count($list);
                # 收货地址数量限制
                if ($count >= 10) {
                    return $this->error(201,'您的收货地址已经达到上限,最多为10条,请删除一条后,再来重新添加');
                }
            } else {
                # 如果当前用户没有添加过任何收货地址并且用户在添加收货地址的时候没有选择默认，则程序强制设定为默认地址
                if ((int)$data['is_default'] == 0) {
                    $data['is_default'] = 1;
                }
            }

            # 保存收货地址
            $model = new UserAddress();
            if ($model->load($data,'') && $model->save()) {
                $address_id = $model->attributes['id'];

                # 设置当前地址为默认收货地址
                if (!empty($list) && $data['is_default'] == 1) {
                    UserAddress::updateAll([
                        'is_default' => 0
                    ],[
                        'and',
                        ['user_id' => $this->uid],
                        ['<>', 'id', $address_id]
                    ]);
                }

                return $this->success(UserAddress::getAddressDetails($address_id));
            }

            return $this->error(201,Helper::arrayToString($model->getErrors()));
        }
        return $this->returnJson();
    }

    # 异步保存用户地址
    public function actionAsyncUpdate ()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            $data = $this->setPostData($request);
            # 保存收货地址
            $model = UserAddress::findOne(['user_id' => $this->uid, 'id' => $data['address_id']]);
            if ($model->load($data,'') && $model->save()) {
                $address_id = $model->id;

                # 设置当前地址为默认收货地址
                if ($data['is_default'] == 1) {
                    UserAddress::updateAll([
                        'is_default' => 0
                    ],[
                        'and',
                        ['user_id' => $this->uid],
                        ['<>', 'id', $address_id]
                    ]);
                }

                return $this->success(UserAddress::getAddressDetails($address_id));
            }

            return $this->error(201,Helper::arrayToString($model->getErrors()));
        }
        return $this->returnJson();
    }

    # 异步删除用户地址
    public function actionAsyncDelete ()
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            $address_id = $request->post('address_id',0);
            $result = UserAddress::deleteAll(['id' => $address_id,'user_id' => $this->uid]);
            if ($result) {
                $list = UserAddress::find()->where(['user_id' => $this->uid])->orderBy(['is_default' => SORT_DESC, 'id' => SORT_DESC])->asArray()->one();
                return $this->success(UserAddress::getAddressDetails($list['id']));
            }
            return $this->error(201,'删除失败');
        }
        return $this->returnJson();
    }

    # 异步获取用户地址
    public function actionAsyncViews ()
    {
        if (Yii::$app->request->isAjax) {
            $request = Yii::$app->request;
            $address_id = $request->post('address_id',0);
            $address_one = UserAddress::getAddressDetails($address_id);
            return $this->success([
                'address_one' => $address_one,
                'province_data' => Region::tableGetData(['pid' => 1]),
                'city_data' => Region::tableGetData(['pid' => $address_one['consignee_province']]),
                'district_data' => Region::tableGetData(['pid' => $address_one['consignee_city']]),
            ]);
        }
        return $this->returnJson();
    }

    # 异步设置默认用户地址
    public function actionAsyncSetDefault ()
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            $address_id = (int)$request->post('address_id',0);
            $is_default = (int)$request->post('is_default',1);
            $result = UserAddress::updateAll(['is_default' => $is_default], ['user_id' => $this->uid, 'id' => $address_id]);
            if ($result) {
                UserAddress::updateAll([
                    'is_default' => 0
                ],[
                    'and',
                    ['user_id' => $this->uid],
                    ['<>', 'id', $address_id]
                ]);
                return $this->success(UserAddress::getAddressDetails($address_id));
            }
            return $this->error(201,'修改失败');
        }
        return $this->returnJson();
    }

    private function setPostData (Request $request)
    {
        return [
            'consignee_username' => $request->post('address_username',''),
            'consignee_province' => $request->post('address_province',''),
            'consignee_city'     => $request->post('address_city',''),
            'consignee_district' => $request->post('address_district',''),
            'consignee_address'  => $request->post('user_address',''),
            'consignee_mobile'   => $request->post('address_mobile',''),
            'is_default'         => $request->post('is_default',0),
            'address_id'         => $request->post('address_id',0),
            'user_id'            => $this->uid,
        ];
    }
}
