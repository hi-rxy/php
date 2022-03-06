<?php

use yii\helpers\ArrayHelper;

/**
 * @Notes: 格式化var_export
 * @Function: varExport
 * @param $expression
 * @param $return
 * @return string|string[]|void|null
 * @Author: 17908
 * @Time: 2022/3/6 0006 7:27
 */
function varExport($expression, $return = FALSE)
{
    $export = var_export($expression, TRUE);
    $patterns = [
        "/array \(/" => '[',
        "/^([ ]*)\)(,?)$/m" => '$1]$2',
        "/=>[ ]?\n[ ]+\[/" => '=> [',
        "/([ ]*)(\'[^\']+\') => ([\[\'])/" => '$1$2 => $3',
    ];
    $export = preg_replace(array_keys($patterns), array_values($patterns), $export);
    if ($return) return $export; else echo $export;
}

/**
 * @Notes: 获取配置
 * @Function: config
 * @param $name
 * @return array|mixed
 * @Author: 17908
 * @Time: 2022/3/6 0006 7:48
 */
function config ($name = '')
{
    return $name ? Yii::$app->params[ $name ] : Yii::$app->params;
}

/**
 * @Notes: 数组转字符串
 * @Function: arrayToString
 * @param $array
 * @return string
 * @Author: 17908
 * @Time: 2022/3/6 0006 19:19
 */
function arrayToString($array)
{
    $str = '';
    if (!empty($array)) foreach ($array as $value) $str .= is_array($value) ? implode('', $value) : $value;
    return $str;
}

/**
 * @Notes: 每月第一天
 * @Function: getFirstDay
 * @return false|int
 * @Author: 17908
 * @Time: 2022/3/6 0006 7:27
 */
function getFirstDay()
{
    return strtotime(date('Y-m-01', strtotime(date("Y-m-d"))));
}

/**
 * @Notes: 每月最后一天
 * @Function: getLastDay
 * @return false|int
 * @Author: 17908
 * @Time: 2022/3/6 0006 7:27
 */
function getLastDay()
{
    $firstDay = date('Y-m-01', strtotime(date("Y-m-d")));
    return strtotime("$firstDay +1 month -1 day");
}

/**
 * @Notes: 工厂函数
 * @Function: factory
 * @param $namespace
 * @param $name
 * @param array $params
 * @return mixed|void
 * @Author: 17908
 * @Time: 2022/3/6 0006 7:35
 */
function factory ($namespace,$name,$params = [])
{
    $class = $namespace.'\\'.ucfirst($name);
    if (class_exists($class)) return new $class($params);
}

function reduce_arr($array)
{
    $return = array();
    array_walk_recursive($array, function ($x) use (&$return) {
        $return[] = $x;
    });
    return $return;
}

/**
 * @Notes: 是否微信
 * @Function: is_wechat
 * @return bool
 * @Author: 17908
 * @Time: 2022/3/6 0006 7:46
 */
function is_wechat()
{
    return false !== strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger');
}

/**
 * @Notes: 是否移动端
 * @Function: is_mobile
 * @return bool
 * @Author: 17908
 * @Time: 2022/3/6 0006 7:46
 */
function is_mobile()
{
    // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
    if (isset ($_SERVER['HTTP_X_WAP_PROFILE'])) return TRUE;
    // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
    if (isset ($_SERVER['HTTP_VIA'])) return (bool)stristr($_SERVER['HTTP_VIA'], "wap");
    // 判断手机发送的客户端标志,兼容性有待提高
    if (isset ($_SERVER['HTTP_USER_AGENT'])) {
        $keywords = array(
            'mobile',
            'nokia',
            'sony',
            'ericsson',
            'mot',
            'samsung',
            'htc',
            'sgh',
            'lg',
            'sharp',
            'sie-',
            'philips',
            'panasonic',
            'alcatel',
            'lenovo',
            'iphone',
            'ipad',
            'ipod',
            'blackberry',
            'meizu',
            'android',
            'netfront',
            'symbian',
            'ucweb',
            'windowsce',
            'palm',
            'operamini',
            'operamobi',
            'openwave',
            'nexusone',
            'cldc',
            'midp',
            'wap',
            'huawei',
            'Coolpad',
            'EVA',
            'ZTE',
            'OPPO',
            'Redmi',
            'vivo',
        );
        // 从HTTP_USER_AGENT中查找手机浏览器的关键字
        if (preg_match("/(" . implode('|', $keywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) return TRUE;
    }
    if (isset ($_SERVER['HTTP_ACCEPT'])) { // 协议法，因为有可能不准确，放到最后判断
        // 如果只支持wml并且不支持html那一定是移动设备
        // 如果支持wml和html但是wml在html之前则是移动设备
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== FALSE) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === FALSE || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
            return TRUE;
        }
    }
    return FALSE;
}

/**
 * @Notes:
 * @Function: excel
 * @param $title
 * @param $columns
 * @param $query
 * @param $handleParams
 * @param $function
 * @return void
 * @throws \PHPExcel_Exception
 * @throws \PHPExcel_Reader_Exception
 * @throws \PHPExcel_Writer_Exception
 * @throws \yii\base\ExitException
 * @Author: 17908
 * @Time: 2022/3/6 0006 19:23
 */
function excel($title, $columns, $query, $handleParams = [], $function = null)
{
    $intCount = $query->count();
    // 判断数据是否存在
    if ($intCount <= 0) return;

    set_time_limit(0);
    ob_end_clean();
    ob_start();
    $objPHPExcel = new \PHPExcel();
    if ($intCount > 3000) {
        $cacheMethod   = \PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
        $cacheSettings = array('memoryCacheSize' => '8MB');
        \PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
    }

    $objPHPExcel->getProperties()->setCreator("yii2.com")
        ->setLastModifiedBy("yii2.com")
        ->setTitle("Office 2007 XLSX Test Document")
        ->setSubject("Office 2007 XLSX Test Document")
        ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
        ->setKeywords("office 2007 openxml php")
        ->setCategory("Test result file");
    $objPHPExcel->setActiveSheetIndex(0);

    // 确定第一行信息
    $letter  = 'A';
    $letters = [];
    foreach ($columns as $attribute => $value) {
        $letters[$letter] = $attribute;
        $objPHPExcel->getActiveSheet()->setCellValue($letter . '1', $value);
        $letter++;
    }

    unset($letter);

    // 写入数据信息
    $intNum = 2;
    foreach ($query->batch(1000) as $array)
    {
        // 函数处理，允许修改数据
        if ($function instanceof Closure) $function($array);
        // 处理每一行的数据
        foreach ($array as $value)
        {
            // 写入信息数据
            foreach ($letters as $letter => $attribute)
            {
                // 使用 getValue 可以支持 user.name 的语法
                $tmpValue = ArrayHelper::getValue($value, $attribute, null);
                // 匿名函数处理
                if (isset($handleParams[$attribute]) && $handleParams[$attribute] instanceof Closure) $tmpValue = $handleParams[$attribute]($tmpValue, $value);
                $objPHPExcel->getActiveSheet()->setCellValue($letter . $intNum, $tmpValue);
            }
            $intNum++;
        }
    }

    // 设置sheet 标题信息
    $objPHPExcel->getActiveSheet()->setTitle($title);
    $objPHPExcel->setActiveSheetIndex(0);

    // 设置头信息
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $title . '.xlsx"');
    header('Cache-Control: max-age=0');
    header('Cache-Control: max-age=1');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');           // Date in the past
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');  // always modified
    header('Cache-Control: cache, must-revalidate');            // HTTP/1.1
    header('Pragma: public');                                   // HTTP/1.0

    // 直接输出文件
    $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
    \Yii::$app->end();
    return;
}
