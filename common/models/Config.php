<?php
namespace common\models;

use common\models\traits\BaseModelTrait;
use common\services\Service;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * This is the model class for table "{{%config}}".
 *
 * @property int $id
 * @property int $group_id 组id
 * @property string $name 配置参数英文名称
 * @property string $title 配置参数中文名称
 * @property string $message 配置参数提示信息
 * @property string $value 配置参数值
 * @property string $type 配置参数类型
 * @property string $info 配置参数
 * @property int $sort 配置参数排序
 * @property int $status 开启状态0表示关闭1表示开启
 * @property int $created_at 添加时间
 * @property int $updated_at 修改时间
 */
class Config extends Service
{
    const STATUS_CLOSE = 0; // 隐藏
    const STATUS_OPEN = 1; // 开启

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%config}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // required
            [['group_id'], 'required'],
            // integer
            [['group_id', 'sort', 'status', 'created_at', 'updated_at'], 'integer'],
            [['value', 'type'], 'string'],
            [['name'], 'string', 'max' => 100],
            [['title', 'message', 'info'], 'string', 'max' => 200],
            // trim
            [['name', 'title', 'message', 'value', 'info', 'type'], 'trim'],
            // unique
            [['name', 'title'], 'unique'],
            // scenarios 设置场景
            [['sort'], 'integer', 'on' => ['sort']],
            [['status'], 'integer', 'on' => ['status']],
            // default
            ['status', 'default', 'value' => self::STATUS_OPEN],
            ['status', 'in', 'range' => [self::STATUS_CLOSE, self::STATUS_OPEN]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'group_id' => 'Group ID',
            'name' => 'Name',
            'title' => 'Title',
            'message' => 'Message',
            'value' => 'Value',
            'type' => 'Type',
            'info' => 'Info',
            'sort' => 'Sort',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At'
        ];
    }

    /**
     * 自动把时间戳填充指定的属性
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at']
                ]
            ]
        ];
    }

    /**
     * 显示类型
     * @param null $intStatus
     * @return array|mixed
     */
    public static function getShowTypes($intStatus = null)
    {
        $array = [
            'text' => 'text',
            'radio' => 'radio',
            'textarea' => 'textarea',
            'select' => 'select',
        ];

        if ($intStatus !== null && isset($array[$intStatus])) {
            $array = $array[$intStatus];
        }

        return $array;
    }

    /**
     * 状态值
     * @param null $intStatus
     * @return array|mixed
     */
    public static function getArrayStatus($intStatus = null)
    {
        $array = [
            self::STATUS_OPEN => '开启',
            self::STATUS_CLOSE => '禁用'
        ];

        if ($intStatus !== null && isset($array[$intStatus])) {
            $array = $array[$intStatus];
        }

        return $array;
    }

    /**
     * 获取状态值对应的颜色信息
     * @param int $intStatus 状态值
     * @return array|string
     */
    public static function getStatusColor($intStatus = null)
    {
        $array = [
            self::STATUS_OPEN => 'btn-success',
            self::STATUS_CLOSE => 'btn-danger',
        ];

        if ($intStatus !== null && isset($array[$intStatus])) {
            $array = $array[$intStatus];
        }

        return $array;
    }

    /**
     * 关联配置组表
     * @return ActiveQuery
     */
    public function getConfigGroup()
    {
        return $this->hasOne(ConfigGroup::className(), ['group_id' => 'id'])->alias('config_group_');
    }

    /**
     * 获取配置信息
     * @param array $where
     * @return array
     */
    public static function getConfigs($where = [])
    {
        $data = ConfigGroup::getList();
        if (empty($data)) return [];
        foreach ($data as $key => $item) {
            if ($config = self::queryCondition()
                ->andFilterWhere(['group_id' => $item['id'], 'status' => $item['status']])
                ->select(['id','group_id','name','title','message','value','type','info','sort','status'])
                ->orderBy(['sort' => SORT_ASC, 'id' => SORT_ASC])
                ->asArray()
                ->all()) {
                foreach ($config as $k => $v) {
                    $func = "_" . $v['type'];
                    $config[$k]['html'] = self::$func($v);
                }
            }
            $data[$key]['config'] = $config;
        }

        return $data;
    }

    /**
     * 更新配置
     * @param array $data
     * @return int
     * @throws Exception
     */
    public static function _update($data = [])
    {
        $updated_at = time();
        $sql = '';
        foreach ($data as $id => $field) {
            $sql .= "UPDATE " . self::tableName() . " SET
                    `value` = '$field[value]',
                    `updated_at` = '$updated_at'
                     WHERE `id` = '$id';";
        }
        Yii::$app->db->createCommand($sql)->execute();
        return self::_write();
    }

    /**
     * 配置内容写入文件
     * @return false|int
     */
    private static function _write()
    {
        $config = [];
        $data = self::queryCondition()->andFilterWhere(['status' => self::STATUS_OPEN])->orderBy(['sort' => SORT_ASC, 'id' => SORT_ASC])->asArray()->all();
        if (!empty($data)) {
            foreach ($data as $k => $v) {
                $name = strtoupper($v['name']);
                if (strtoupper($v['value']) == "FALSE") $v['value'] = false;
                if (strtoupper($v['value']) == "TRUE") $v['value'] = true;
                $config['site'][$name] = htmlspecialchars_decode($v['value']);
            }
        }
        //写入配置文件
        $content = "<?php 
return " . varExport($config,true) . ";";
        return file_put_contents("../../common/config/site.php", $content);
    }

    /**
     * 输入框
     * @param $config
     * @return string
     */
    private static function _text($config)
    {
        $name = 'config[' . $config['id'] . '][value]';
        return Html::textInput($name,$config['value'],['class' => 'col-xs-10']);
    }

    /**
     * 单选框
     * @param $config
     * @return string
     */
    private static function _radio($config)
    {
        $info = explode(",", $config['info']);
        $html = "";
        foreach ($info as $k => $radio) {
            $data = explode("|", $radio);//[0]值如1 [1]描述如开启
            $checked = $data[0] == $config['value'] ? true : false;//'checked="checked"' : '';
            $name = 'config[' . $config['id'] . '][value]';
            $k = $k + 1;
            $radio = Html::radio($name,$checked,['value' => $data[0], 'class' => 'ace']);
            if ($k % 3 == 0) {
                $html .= '<label>'.$radio.'<span class="lbl">' . $data[1] . '</span></label>';
            } else {
                $html .= '<label style="margin-left: 10px;">'.$radio.'<span class="lbl">' . $data[1] . '</span></label>';
            }
        }
        return $html;
    }

    /**
     * 文本域
     * @param $config
     * @return string
     */
    private static function _textarea($config)
    {
        $name = 'config[' . $config['id'] . '][value]';
        return Html::textarea($name,$config['value'],['class' => 'col-xs-10', 'style' => 'height:100px;border:1px solid #ccc;']);
    }

    /**
     * 单选框
     * @param $config
     * @return string
     */
    private static function _select($config)
    {
        $info = explode(",", $config['info']);
        $html = '<select name="config[' . $config['id'] . '][value]"  class="col-xs-10">';
        foreach ($info as $k => $radio) {
            $data = explode("|", $radio);//[0]值如1 [1]描述如开启
            $selected = $data[0] == $config['value'] ? 'selected="selected"' : '';
            $html .= '<option value="' . $data[0] . '" ' . $selected . '>' . $data[1] . '</option>';
        }
        return $html;
    }

    /**
     * 默认条件
     * @return ActiveQuery
     */
    public static function queryCondition ()
    {
        $query = self::find();
        $query->andFilterWhere(['status' => self::STATUS_OPEN]);
        return $query;
    }
}
