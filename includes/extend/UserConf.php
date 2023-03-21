<?php

/**
 * Author：晴玖天
 * Creation：2020/3/23 20:21
 * Filename：UserConf.php
 * 用户配置载入
 */

namespace extend;

use CookieCache;
use lib\supply\Price;
use login_data;
use Medoo\DB\SQL;

class UserConf
{
    /**
     * @param int $gid
     * @return int
     * 计算当前加盟站点对此商品的提价比例！
     */
    public static function GoodsPrice($gid = -1, $User = false)
    {
        $CacheName = md5($gid . (!$User ? -1 : json_encode($User)));
        if (!empty(CookieCache::$Cache['GoodsPrice'][md5($CacheName)])) {
            return CookieCache::$Cache['GoodsPrice'][md5($CacheName)];
        }
        global $conf, $accredit;
        if (empty($_SESSION['UserGoods'])) {
            CookieCache::$Cache['GoodsPrice'][md5($CacheName)] = 0;
            return 0;
        }
        $UserGoods = json_decode(xiaochu_de($_SESSION['UserGoods'], $accredit['token']), TRUE);
        if (empty($UserGoods['uid']) || empty($UserGoods['grade'])) {
            CookieCache::$Cache['GoodsPrice'][md5($CacheName)] = 0;
            return 0;
        }
        if (count($UserGoods['GoodsRis']) === 0) {
            CookieCache::$Cache['GoodsPrice'][md5($CacheName)] = 0;
            return 0;
        }

        if ($UserGoods['grade'] < $conf['usergradeprofit']) return 0;
        if (!empty($_COOKIE['THEKEY'])) {
            /**
             * 验证1、自己站点！
             * 验证2、等级>=当前站点站长！
             */
            if ($User == false || $User == -1) $User = login_data::user_data();
            if ($User != false && $User['id'] == $UserGoods['uid']) { //是站长
                CookieCache::$Cache['GoodsPrice'][md5($CacheName)] = 0;
                return 0;
            }
            if ($User['grade'] >= $UserGoods['grade']) { //同等级
                CookieCache::$Cache['GoodsPrice'][md5($CacheName)] = 0;
                return 0;
            }
        }

        if (array_key_exists($gid, $UserGoods['GoodsRis'])) {
            if (empty($UserGoods['GoodsRis'][$gid]['rise']) || $UserGoods['GoodsRis'][$gid]['rise'] <= 0) {
                CookieCache::$Cache['GoodsPrice'][md5($CacheName)] = 0;
                return 0;
            }
            CookieCache::$Cache['GoodsPrice'][md5($CacheName)] = $UserGoods['GoodsRis'][$gid]['rise'];
            return $UserGoods['GoodsRis'][$gid]['rise'];
        }
        CookieCache::$Cache['GoodsPrice'][md5($CacheName)] = 0;
        return 0;
    }

    /**
     * @return array
     * 取出当前加盟店内下架的商品ID
     */
    public static function GoodsHide()
    {
        $Name = md5(href());
        if (!empty(CookieCache::$Cache['GoodsHide'][$Name])) {
            return CookieCache::$Cache['GoodsHide'][$Name];
        }
        global $conf, $accredit;
        if (empty($_SESSION['UserGoods'])) {
            CookieCache::$Cache['GoodsHide'][$Name] = [];
            return [];
        }
        $UserGoods = json_decode(xiaochu_de($_SESSION['UserGoods'], $accredit['token']), TRUE);
        if (empty($UserGoods['uid']) || empty($UserGoods['grade'])) {
            CookieCache::$Cache['GoodsHide'][$Name] = [];
            return [];
        }
        if (count($UserGoods['GoodsRis']) === 0) {
            CookieCache::$Cache['GoodsHide'][$Name] = [];
            return [];
        }
        if ($UserGoods['grade'] < $conf['usergradegoodsstate']) {
            CookieCache::$Cache['GoodsHide'][$Name] = [];
            return [];
        }
        $Deploy = $UserGoods['GoodsRis']; //商品配置
        $Data = [];
        foreach ($Deploy as $key => $value) {
            if ($value['state'] == -1) {
                $Data[] = $key;
            }
        }
        CookieCache::$Cache['GoodsHide'][$Name] = $Data;
        return $Data;
    }

    public static function Conf($data)
    {
        global $accredit, $_QET;
        unset($_SESSION['UserGoods']);
        if ($data['userleague'] <> 1 || !empty($_SESSION['ADMIN_TOKEN'])) {
            return $data;
        }

        if (!empty($_COOKIE['league']) && $data['userdomaintype'] == 2 && empty($_QET['t'])) {
            $domain = xiaochu_de($_COOKIE['league']);
            if (empty($domain)) {
                setcookie("league", "", time() - 1, '/');
            }
        } else {
            $domain = ($data['userdomaintype'] == 1 ? href() : (isset($_QET['t']) ? $_QET['t'] : ''));
        }

        if ($data['userdomaintype'] == 2 && !empty($_QET['t'])) {
            if (empty($domain)) {
                $domain = $_QET['t'];
            }
            setcookie("league", xiaochu_en($domain), time() + 3600 * 12 * 30, '/');
        } else if ($data['userdomaintype'] == 1) {
            setcookie("league", "", time() - 1, '/');
        }
        if (empty($domain)) {
            return $data;
        }
        $DB = SQL::DB();
        $UserData = $DB->get('user', ['id', 'configuration', 'grade', 'pricehike'], [
            'domain' => (string)$domain,
            'state' => 1,
            'grade[>=]' => $data['userleaguegrade']
        ]);
        if (!$UserData) {
            return $data;
        }

        $_SESSION['UserGoods'] = xiaochu_en(json_encode(['uid' => $UserData['id'], 'grade' => $UserData['grade'], 'GoodsRis' => ($UserData['pricehike'] == '' ? [] : json_decode($UserData['pricehike'], TRUE))], JSON_UNESCAPED_UNICODE), $accredit['token']);

        if ($UserData['configuration'] == '') {
            return $data;
        }
        $UserC = json_decode($UserData['configuration'], true);
        if (!$UserC) {
            return $data;
        }
        $data = array_merge($data, $UserC);
        $data['notice_top'] = base64_decode($UserC['notice_top'], TRUE);
        $data['notice_check'] = base64_decode($UserC['notice_check'], TRUE);
        $data['notice_bottom'] = base64_decode($UserC['notice_bottom'], TRUE);
        $data['PopupNotice'] = base64_decode($UserC['PopupNotice'], TRUE);
        unset($UserC);
        return $data;
    }

    /**
     * @param $Muid //当前下单站点ID
     * @param $TradeNo //充值订单编号
     * @param $order //商品订单编号
     * @param $gid
     * @param $price //原始价格
     * @param $type //2=积分兑换，1=其他
     * @param $num //购买份数
     * @param $input //下单信息
     * @param int $State //1=正常提成提交，2=收获后提成提交
     * @return bool
     * 提成分配
     */
    public static function PushMoney($Muid, $TradeNo, $Uid, $order, $gid, $price, $type, $num, $input, $State = 1)
    {
        global $conf, $date;

        //写入缓存
        mkdirs(SYSTEM_ROOT . "extend/log/PushMoney/");
        if ($State == 1 && $conf['RoyaltyDistributionMode'] == 2) {
            //创建延迟发放缓存
            file_put_contents(SYSTEM_ROOT . "extend/log/PushMoney/{$order}.data", json_encode([
                'Muid' => $Muid,
                'TradeNo' => $TradeNo,
                "Uid" => $Uid,
                "order" => $order,
                "gid" => $gid,
                "price" => $price,
                "type" => $type,
                "num" => $num,
                "input" => $input,
            ]));
            return false;
        }

        $DB = SQL::DB();
        $domain_user = self::judge();
        if (file_exists(ROOT . 'includes/extend/log/Order/' . $order . '.log')) {
            $domain_user = self::judge(file_get_contents(ROOT . 'includes/extend/log/Order/' . $order . '.log'));
        }
        $Goods = $DB->get('goods', '*', [
            'gid' => (int)$gid
        ]);
        if (!$Goods) {
            return false;
        }

        //查询付款用户的分站信息【如果无法判断当前站点是否是分站的话就查询】
        if (!$domain_user && $PayOrder = self::OrderDestinyDataRead($TradeNo, 2)) {
            if ($PayOrder['muid'] >= 1) {
                $PayUser = $DB->get('user', '*', [
                    'id' => $PayOrder['muid'],
                ]);
                if ($PayUser) {
                    $domain_user = $PayUser;
                }
            }
        }

        if ($Uid >= 1) {
            //非游客，能够下单，账号肯定是正常状态
            $UserUs = $DB->get('user', ['superior', 'id'], [
                'id' => (int)$Uid,
                'superior[<=]' => 0,
            ]);
            //给没有绑定上级的用户绑定上级！
            if ($UserUs) {
                //主站直接下单
                if (!$domain_user) {
                    return false;
                }
                $DB->update('user', [
                    'superior' => $domain_user['id'],
                ], [
                    'id' => $Uid
                ]);
                $UserUs['superior'] = $domain_user['id'];
            } else {
                //已经存在上级
                $UserUs = $DB->get('user', ['superior', 'id'], [
                    'id' => (int)$Uid,
                ]);
            }
            //提成强制分配给上级
            if ($conf['CommitsDistribute'] == 1) {
                $superior_user = $DB->get('user', '*', ['id' => $UserUs['superior'], 'state' => 1]);
                if ($superior_user) {
                    $domain_user = $superior_user;
                }
                unset($superior_user);
            }
            unset($UserUs);

            //如果分成目标是自己，那就不需要分成~
            if ($Uid == $domain_user['id']) {
                return false;
            }
        }

        //无上级,非分站,无需分成
        if (!$domain_user) {
            return false;
        }

        //如果订单未绑定，则为此订单绑定店铺ID
        if ($Muid == -1) {
            $DB->update('order', [
                'muid' => $domain_user['id'],
            ], [
                'order' => $order
            ]);
        }

        //上级权限已经到期，无需分成
        if ($conf['PermissionTime'] == 1 && !empty($domain_user['endtime']) && $domain_user['endtime'] < $date) {
            return false;
        }

        //开始计算上级的商品购买价格
        $Sku = $input;
        if ($Goods['specification'] == 2) {
            $SpRule = RlueAnalysis($Goods, 1, $domain_user, true);
            $SkuData = [
                'data' => $SpRule,
                'SPU' => json_decode($Goods['specification_spu'], TRUE),
            ];
            $KeyName = [];
            $SpuIn = 0; //初始化
            $InputArr = [];
            foreach ($SkuData['SPU'] as $val) {
                $inputs = $InputArr[$SpuIn];
                foreach ($Sku as $v) {
                    if (in_array($v, $val)) {
                        $inputs = $v;
                    }
                }
                $InputArr[$SpuIn] = $inputs;
                ++$SpuIn;
            }
            foreach ($InputArr as $v) {
                $KeyName[] = $v;
            }

            $DataRule = $SkuData['data']['Parameter'][implode('`', $KeyName)];
            if (empty($DataRule)) {
                return false;
            }
            $Goods = array_merge($Goods, $DataRule);
            $pricer['level'] = $SkuData['data']['level'];
        } else {
            $pricer = Price::Get($Goods['money'], $Goods['profits'], login_data::user_grade($domain_user['id']), $Goods['gid'], $Goods['selling'], $Goods['profit_rule'], 2);
            $Goods['price'] = $pricer['price'];
            $Goods['points'] = $pricer['points'];
        }
        /**
         * 限购秒杀活动参数计算
         */
        $Seckill = $DB->get('seckill', ['start_time', 'end_time', 'astrict', 'depreciate'], [
            'end_time[>]' => $date,
            'start_time[<]' => $date,
            'gid' => (int)$gid
        ]);
        if ($Seckill) {
            $attend = $DB->count('order', [
                    'gid' => $gid,
                    'addtitm[>]' => $Seckill['start_time'],
                    'addtitm[<]' => $Seckill['end_time']
                ]) - 1;
            if ($attend < $Seckill['astrict']) {
                $Seckill = $Seckill['depreciate'] - 0;
                $Goods['price'] -= $Goods['price'] * ($Seckill / 100);
                $Goods['points'] -= $Goods['points'] * ($Seckill / 100);
            }
        }
        unset($Seckill);

        //计算积分价格
        $points_domain = $Goods['points'] * $num;
        if ($type == 1) {
            if ($Goods['freight'] != -1) {
                $freight = $DB->get('freight', '*', [
                    'id' => (int)$Goods['freight']
                ]);
                if ($freight) {
                    $price_domain = Price::Freight($freight, $input, $Goods['price'], $num);
                } else {
                    $price_domain = $Goods['price'] * $num;
                }
            } else {
                $price_domain = $Goods['price'] * $num;
            }
            //计算当前用户购买价和上级价格的差价
            $Money = $price - $price_domain;
            //如果当前用户的购买价大于上级购买价,则利润设置为0
            if ($price <= $price_domain) {
                $Money = 0;
            }
            self::PaidCommission($domain_user, $Money, 1, '余额提成', '用户[' . ($Uid == -1 ? '游客' : $Uid) . ']在您的站点购买了商品' . str_replace("！", "", $Goods['name']) . '，根据Ta购买的价格和您的进货成本来计算,特奖励您[Money]元提成,继续加油哦！' . ('(' . $order . ',[Money],余额提成)'), $order);
        } else {
            $Money = $price - $points_domain;
            if ($price <= $points_domain) {
                $Money = 0;
            }
            self::PaidCommission($domain_user, $Money, 2, '货币提成', '用户[' . ($Uid == -1 ? '游客' : $Uid) . ']在您的站点购买了商品' . str_replace("！", "", $Goods['name']) . '，根据Ta兑换商品的' . $conf['currency'] . '数和您的的兑换价来计算，特奖励您[Money]' . $conf['currency'] . ',继续加油哦！' . ('(' . $order . ',[Money],货币提成)'), $order);
        }
    }

    /**
     * @param $OrderNumber
     * 订单归宿数据写入
     * 域名绑定订单号,方便发放提成！
     */
    public static function OrderDestinyDataWritten($OrderNumber)
    {
        $Path = SYSTEM_ROOT . 'extend/log/Pay/';
        $User = self::judge();
        if (file_put_contents($Path . $OrderNumber, json_encode([
            'url' => href(), //付款时的域名
            'order' => $OrderNumber, //订单编号
            'muid' => ($User ? $User['id'] : -1), //当前付款的分站ID
        ]))) {
            return true;
        }
        return false;
    }

    /**
     * @param $OrderNumber
     * @param int $Type //1=直接通过订单号查询,2=通过trade_no查询
     * 根据充值订单号读取下单的站点信息
     */
    public static function OrderDestinyDataRead($OrderNumber, $Type = 1)
    {
        $Path = SYSTEM_ROOT . 'extend/log/Pay/';
        if ($Type == 2) {
            $DB = SQL::DB();
            $PayGet = $DB->get('pay', ['order'], [
                'trade_no' => $OrderNumber,
            ]);
            if ($PayGet) {
                $OrderNumber = $PayGet['order'];
            }
        }
        if ($Content = file_get_contents($Path . $OrderNumber)) {
            return json_decode($Content, true);
        }
        return false;
    }

    /**
     * @param null $dome
     * @return false|mixed
     * 判断当前是否是分店
     */
    public static function judge($dome = null, $TradeNo = null)
    {
        $Name = md5($dome . $TradeNo . href());
        if (!empty(CookieCache::$Cache['judge'][$Name])) {
            return CookieCache::$Cache['judge'][$Name];
        }
        global $conf;
        if (isset($_COOKIE['league']) && $conf['userdomaintype'] == 2) {
            $domain = xiaochu_de($_COOKIE['league']);
        } else $domain = href();
        if ($dome !== null) $domain = $dome;
        $DB = SQL::DB();
        $UserData = $DB->get('user', '*', [
            'domain' => (string)$domain,
            'state' => 1,
            'grade[>=]' => $conf['userleaguegrade'],
        ]);
        if (!$UserData) {
            if ($TradeNo !== null) {
                if ($PayOrder = self::OrderDestinyDataRead($TradeNo, 2)) {
                    if ($PayOrder['muid'] >= 1) {
                        $UserData = $DB->get('user', '*', [
                            'id' => $PayOrder['muid'],
                        ]);
                        if ($UserData) {
                            CookieCache::$Cache['judge'][$Name] = $UserData;
                            return $UserData;
                        }
                    }
                }
            }
            CookieCache::$Cache['judge'][$Name] = false;
            return false;
        }
        CookieCache::$Cache['judge'][$Name] = $UserData;
        return $UserData;
    }

    /**
     * @param $User //收到奖励的用户数据
     * @param $Price //收到奖励的金额
     * @param $type //收到奖励的方式
     * @param $name //商品名称
     * @param $msg //奖励的内容
     * @param $Money //原始金额
     * 奖励发放
     */
    public static function PaidCommission($User, $Price, $type, $name, $msg, $order, $Money = -1)
    {
        global $conf;
        $DB = SQL::DB();
        $Price = (float)$Price;
        if ($Money == -1) {
            $Money = $Price;
        }
        //存在上级,需要向上分成
        if ($User['superior'] >= 1) {
            $Mid = RatingParameters($User);
            if ($Mid === -1) {
                return false;
            }
            $ActualProfit = ($Mid['ActualProfit'] - 0);
            $ProfitThreshold = ($Mid['ProfitThreshold'] - 0);
            $PriceS = ($Price / 100) * $ActualProfit;
            $PriseT = $Price - $PriceS;
            $Threshold = ($Money / 100) * $ProfitThreshold;
            if ($PriceS >= $Price) {
                $PriceS = $Price;
            }
            $PrType = true;
            //触发分成阀值
            if ($PriceS <= $Threshold) {
                $PrType = false;
            }
            if ($PrType) {
                $UserTs = $DB->get('user', '*', ['id' => (int)$User['superior']]);
                if (!$UserTs || $UserTs['state'] != 1 || $UserTs['grade'] <= $User['grade']) {
                    if ((int)$conf['InfiniteDivision'] !== 1) {
                        //关闭无限级分成
                        $PrType = false;
                    }
                }
                $Price = $PriceS;
            }
        } else {
            $PrType = false;
        }

        $msg = str_replace('[Money]', $Price, $msg);
        userlog($name, $msg, $User['id'], $Price);
        $field = ($type == 1 ? 'money' : 'currency');
        $Res = $DB->update('user', [
            $field . '[+]' => $Price,
        ], [
            'id' => $User['id']
        ]);

        if (!$Res) {
            userlog('奖励失败', '提成奖励失败,无法写入奖励！,奖励' . ($type == 1 ? '金额' : '积分') . $Price . '应得用户ID:' . $User['id'], $User['id']);
        } else {
            if ($PrType) {
                $msg = '您的分销下级[' . $User['id'] . ']为您提供了[Money]' . ($type == 1 ? '元' : $conf['currency']) . '提成奖励，再接再厉哦！' . ('(' . $order . ',[Money],' . ($type == 1 ? '余额' : '货币') . '提成)');
                self::PaidCommission($UserTs, $PriseT, $type, $name, $msg, $order, $Money);
            }
            return true;
        }
    }

    /**
     * @param $OrderArr
     * @param $Pre
     * 订单批量退款
     */
    public static function OrderBulkRefund($OrderArr, $Pre = 100)
    {
        global $conf;
        $DB = SQL::DB();
        $Count = 0;
        foreach ($OrderArr as $re) {
            $price = (round($re['price'], 8) * ($Pre / 100));
            if ($price < 0) $price = 0;
            if ($re['uid'] >= 1) {
                if ($re['payment'] === '积分兑换') {
                    self::DeductCommission($re['order'], '货币提成');
                    $msg = '管理员帮您在后台进行了退款操作，退款订单号为：' . $re['order'] . ',退款数为：' . round($price, 2) . $conf['currency'] . '！';
                    $DB->update('user', [
                        'currency[+]' => $price,
                    ], [
                        'id' => $re['uid']
                    ]);
                } else {
                    self::DeductCommission($re['order'], '余额提成');
                    $msg = '管理员帮您在后台进行了退款操作，退款订单号为：' . $re['order'] . ',退款金额为：' . round($price, 2) . '元！';
                    $DB->update('user', [
                        'money[+]' => $price,
                    ], [
                        'id' => $re['uid']
                    ]);
                }
                userlog('订单退款', $msg, $re['uid'], $price);
            } else {
                if ($re['payment'] === '积分兑换') {
                    self::DeductCommission($re['order'], '货币提成');
                    $msg = '管理员已经将此订单设置为退款状态，退款订单号为：' . $re['order'] . ',退款数为：' . round($price, 2) . $conf['currency'] . '！';
                } else {
                    self::DeductCommission($re['order'], '余额提成');
                    $msg = '管理员已经将此订单设置为退款状态，退款订单号为：' . $re['order'] . ',退款金额为：' . round($price, 2) . '元！';
                }
            }
            $Res = $DB->update('order', [
                'remark' => $msg,
                'state' => 5,
            ], [
                'order' => $re['order']
            ]);
            if ($Res) {
                ++$Count;
            }
        }
        return $Count;
    }

    /**
     * @param $Order //退款订单号
     * @param $Type //方式，货币提成/余额提成
     */
    public static function DeductCommission($Order, $Type)
    {
        $DB = SQL::DB();
        $Res = $DB->select('journal', ['content', 'uid', 'id'], [
            'content[~]' => $Order,
            'name' => $Type
        ]);

        if (!$Res) return false;
        if (count($Res) == 0) return false;
        $I = 0;
        foreach ($Res as $Log) {
            $OrderLog = explode(',', getSubstr($Log['content'], '！(', ')'));
            if (empty($OrderLog[0]) || empty($OrderLog[1]) || empty($OrderLog[2])) return false;

            if ($Type === '货币提成') {
                $Res = $DB->update('user', [
                    'currency[-]' => $OrderLog[1]
                ], [
                    'id' => $Log['uid'],
                ]);
            } else {
                $Res = $DB->update('user', [
                    'money[-]' => $OrderLog[1]
                ], [
                    'id' => $Log['uid'],
                ]);
            }

            if ($Res) {
                @$DB->update('journal', [
                    'name' => $Type . '(无效)',
                    'count' => 0
                ], [
                    'id' => $Log['id']
                ]);
                ++$I;
            }
        }

        if ($I === count($Res)) return true;
        return false;
    }

    /**
     * @param $dowid
     * @param $money
     * @param $uid
     * @param $grade
     * @return bool
     * 升级提成发放
     */
    public static function Commission($dowid, $money, $uid, $grade)
    {
        global $conf;
        $DB = SQL::DB();
        $domain_user = self::judge();
        $money = (float)$money * ($conf['userupgradeprofit'] / 100);
        if ($money <= 0) $money = 0;
        if (($uid == 0 and $domain_user == false) || $conf['usergradecost'] == -1 || $dowid == $domain_user['id']) return false;

        if ($domain_user <> $uid && $domain_user <> false && $uid <> 0) {
            $User1 = $DB->get('user', [
                'id', 'configuration', 'grade'
            ], [
                'id' => (int)$uid,
                'state' => 1,
                'grade[>=]' => $conf['userleaguegrade']
            ]);
            if ($User1) {
                $money = $money / 2;
                if ($User1['grade'] >= $grade) {
                    userlog('升级提成', '您的下级[' . $dowid . ']将用户等级提升到了' . $grade . '级,特奖励您' . $money . '元下级升级奖励！', $uid, $money);
                    $DB->update('user', [
                        'money[+]' => $money,
                    ], [
                        'id' => $uid,
                    ]);
                }
                if ($domain_user['grade'] >= $grade) {
                    userlog('升级提成', '用户[' . $dowid . ']在您的站点将等级提升到了' . $grade . '级,特奖励您' . $money . '元下级升级奖励！', $domain_user['id'], $money);
                    $DB->update('user', [
                        'money[+]' => $money,
                    ], [
                        'id' => $domain_user['id'],
                    ]);
                }
            } else {
                if ($User1['grade'] >= $grade) {
                    userlog('升级提成', '您的下级[' . $dowid . ']将用户等级提升到了' . $grade . '级,特奖励您' . $money . '元下级升级奖励！', $uid, $money);
                    $DB->update('user', [
                        'money[+]' => $money,
                    ], [
                        'id' => $uid
                    ]);
                }
            }
        } else {
            if ($domain_user !== false) {
                if ($domain_user['grade'] >= $grade) {
                    userlog('升级提成', '您的下级[' . $dowid . ']将用户等级提升到了' . $grade . '级,特奖励您' . $money . '元下级升级奖励！', $domain_user['id'], $money);
                    $DB->update('user', [
                        'money[+]' => $money,
                    ], [
                        'id' => $domain_user['id']
                    ]);
                }
            } else {
                $User1 = $DB->get('user', [
                    'id', 'configuration', 'grade'
                ], [
                    'id' => (int)$uid,
                    'state' => 1,
                    'grade[>=]' => $conf['userleaguegrade']
                ]);
                if (!$User1) {
                    return false;
                }
                if ($User1['grade'] >= $grade) {
                    userlog('升级提成', '您的下级[' . $dowid . ']将用户等级提升到了' . $grade . '级,特奖励您' . $money . '元下级升级奖励！', $uid, $money);
                    $DB->update('user', [
                        'money[+]' => $money,
                    ], [
                        'id' => $uid
                    ]);
                    return true;
                }
            }
        }
    }
}
