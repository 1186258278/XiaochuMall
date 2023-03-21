<?php
// +----------------------------------------------------------------------
// | Project: xc
// +----------------------------------------------------------------------
// | Creation: 2023/1/21
// +----------------------------------------------------------------------
// | Filename: SourceShield.php
// +----------------------------------------------------------------------
// | Explain: 描述
// +----------------------------------------------------------------------
namespace lib\SourceShield;

use Curl\Curl;
use lib\supply\StringCargo;

class SourceShield
{
    /**
     * @var array|bool
     * 需屏蔽的站点数据
     */
    private static $arr = false;

    /**
     * @return false|int
     * 从服务端同步违规货源信息
     */
    public static function DataSync()
    {
        $Data = Curl::Get('/api/FileCalibre/index', [
            'act' => 'SourceShieldModel',
        ]);
        $Data = json_decode(xiaochu_de($Data), true);
        /**
         * 将数据同步到本地
         */
        if ($Data['code'] >= 1) {
            return file_put_contents(ROOT . 'includes/lib/SourceShield/SourceShield.log', json_encode($Data['data'], JSON_UNESCAPED_UNICODE));
        }
        return false;
    }

    /**
     * @return bool
     * 初始化参数
     */
    public static function initialize()
    {
        if (self::$arr !== false) {
            return true;
        }
        $data = file_get_contents(ROOT . 'includes/lib/SourceShield/SourceShield.log');
        if (empty($data)) {
            if (!self::DataSync()) {
                self::$arr = [];
                return false;
            }
            $data = file_get_contents(ROOT . 'includes/lib/SourceShield/SourceShield.log');
            if (empty($data)) {
                self::$arr = [];
                return false;
            }
        }
        self::$arr = json_decode($data, true);
        return true;
    }

    /**
     * @param $url
     * 开始屏蔽内容
     */
    public static function ShieldSourceData($url)
    {
        $url = explode('/', StringCargo::UrlVerify($url, 2))[0];
        $ip = gethostbyname($url); //获取对接站点的IP
        self::initialize();
        if (!self::$arr || count(self::$arr) == 0) {
            return true;
        }
        foreach (self::$arr as $value) {
            $msg = '检测到对接的货源：' . $url . '(' . $ip . ') 存在诈骗风险，已自动拦截，拦截原因：' . $value['content'];
            if (!isset($_SESSION['ADMIN_TOKEN'])) {
                $msg = '数据获取失败，请联系站长处理！';
            }
            //检测IP是否包含
            if (in_array($ip, $value['iplist'])) {
                dies(-1, $msg);
            }
            foreach ($value['domainlist'] as $v) {
                if (strstr($url, $v)) {
                    dies(-1, $msg);
                }
            }
        }
        return true;
    }
}