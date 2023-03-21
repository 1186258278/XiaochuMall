<?php
// +----------------------------------------------------------------------
// | Project: shop
// +----------------------------------------------------------------------
// | Creation: 2022/2/9
// +----------------------------------------------------------------------
// | Filename: Simulation.php
// +----------------------------------------------------------------------
// | Explain: 对接货源请求模拟,方便其他系统通过热门货源对接本系统!
// +----------------------------------------------------------------------

namespace lib\Simulation;

use extend\UserConf;
use lib\supply\Order;
use lib\supply\Price;
use Medoo\DB\SQL;
use voku\helper\AntiXSS;

class Simulation
{
    // 直客用户ID
    private static $User = [];

    /**
     * 直客接口模拟
     */
    public static function zhike($Data)
    {
        global $accredit;
        $Type = $Data['TypeS'];
        unset($Data['TypeS']);
        $User = self::zhike_user($Data);
        unset($Data['TypeUrl']);
        if ($User['code'] == -1) {
            dies(403, $User['msg']);
        }
        self::$User = $User['data'];
        $userJu = UserConf::judge();
        if ($userJu) {
            $_SESSION['UserGoods'] = xiaochu_en(json_encode(['uid' => $userJu['id'], 'grade' => $userJu['grade'], 'GoodsRis' => ($userJu['pricehike'] == '' ? [] : json_decode($userJu['pricehike'], TRUE))], JSON_UNESCAPED_UNICODE), $accredit['token']);
        }
        switch ($Type) {
            case '/api/client/goods/v2/category': //商品分类
                self::zhike_category();
                break;
            case '/api/client/goods/v2/goods/list': //商品列表
                self::zhike_goods_list();
                break;
            case '/api/client/goods/v2/goods': //商品详情
                self::zhike_goods($Data);
                break;
            case '/api/client/goods/v2/order_buy': //商品下单
                self::zhike_order_buy();
                break;
            case '/api/client/goods/v2/order': //订单详情
                self::zhike_order($Data);
                break;
            case '/api/client/account/v2/profile': //获取账户信息
                self::zhike_profile();
                break;
            default:
                dies(403, '请求的路径不存在！');
        }
    }

    /**
     * @param $Data
     * 获取对接用户的信息，方便后续操作
     */
    private static function zhike_user($Data)
    {
        $Header = self::GetHeaders();
        if (empty($Header['appid']) || empty($Header['apptoken']) || empty($Header['apptimestamp'])) {
            return [
                'code' => -1,
                'msg' => '请将Header内的验证参数提交完整(AppId=用户ID, AppToken=SHA1算法加密后的密钥，AppTimestamp=服务器时间戳)',
            ];
        }
        $query = [];
        foreach ($_GET as $key => $value) {
            if ($value == "") {
                continue;
            }
            $query[] = $key . '=' . $value;
        }
        //sha1
        if (count($query) === 0) {
            $Url = $Data['TypeUrl'];
        } else {
            $Url = $Data['TypeUrl'] . '?' . implode("&", $query);
        }
        //去除时间戳限制
        $DB = SQL::DB();
        $User = $DB->get('user', '*', [
            'id' => $Header['appid'],
        ]);
        if (!$User) {
            return [
                'code' => -1,
                'msg' => '用户验证失败,ID为' . $User['id'] . '的用户不存在！',
            ];
        }
        if ($User['state'] != 1) {
            return [
                'code' => -1,
                'msg' => '您的账户已被禁封！',
            ];
        }
        $ArrayIp = explode('|', $User['ip_white_list']);
        if (!in_array(userip(), $ArrayIp)) {
            return [
                'code' => -1,
                'msg' => 'IP：' . userip() . ' 未设置对接白名单！',
            ];
        }
        $ApiToken = sha1($Header['appid'] . $User['token'] . $Url . $Header['apptimestamp']);
        //开始验证
        if ($ApiToken != $Header['apptoken']) {
            return [
                'code' => -1,
                'msg' => 'AppToken参数验证失败，请参考文档来进行开发：docs.79tian.com' . $ApiToken, // TODO 开发完成后需要去除
            ];
        }
        return [
            'code' => 1,
            'data' => $User,
        ];
    }

    /**
     * 获取自定义的header数据
     */
    private static function GetHeaders()
    {
        // 忽略获取的header数据
        $ignore = array('host', 'accept', 'content-length', 'content-type');
        $headers = array();
        foreach ($_SERVER as $key => $value) {
            if (substr($key, 0, 5) === 'HTTP_') {
                $key = substr($key, 5);
                $key = str_replace('_', ' ', $key);
                $key = str_replace(' ', '-', $key);
                $key = strtolower($key);
                if (!in_array($key, $ignore)) {
                    $headers[$key] = $value;
                }
            }
        }
        return $headers;
    }

    /**
     * 获取分类列表
     */
    private static function zhike_category()
    {
        $DB = SQL::DB();
        $SQL = [
            'state' => 1,
        ];
        $ClassList = $DB->select('class', [
            'cid', 'name', 'superior', 'image'
        ], $SQL);
        $Data = [];
        foreach ($ClassList as $value) {
            $Data[] = [
                'categoryId' => $value['cid'],
                'categoryName' => $value['name'],
                'categoryPath' => $value['name'] . ($value['superior'] == -1 ? '' : ' > 分类' . $value['superior']), //分类路径
                'categoryThumb' => ImageUrl($value['image']),
                'parentCategoryId' => ($value['superior'] == -1 ? 0 : $value['superior']), //上级分类ID
                'parentCategoryName' => ($value['superior'] == -1 ? '顶级分类' : '分类' . $value['superior']), //上级分类名称
            ];
        }

        dier([
            'code' => 100,
            'msg' => 'ok',
            'result' => [
                'total' => count($ClassList),
                'data' => $Data,
            ]
        ]);
    }

    /**
     * 获取商品列表
     */
    private static function zhike_goods_list()
    {
        $DB = SQL::DB();
        $GoodsHide = UserConf::GoodsHide();
        $SQL = [
            'method[~]' => '4',
            'specification[!]' => 2 //不取出规格商品
        ];
        if (count($GoodsHide) >= 1) {
            $SQL = array_merge($SQL, [
                'gid[!]' => $GoodsHide
            ]);
        }
        $GoodsList = $DB->select('goods', ['gid', 'name', 'image', 'cid'], $SQL);
        $Data = [];
        foreach ($GoodsList as $value) {
            $Image = json_decode($value['image'], true);
            $Data[] = [
                'goodsSN' => $value['gid'],
                'goodsName' => $value['name'],
                'goodsThumb' => ImageUrl($Image[0]),
                'categoryId' => $value['cid'],
                'categoryName' => '分类' . $value['cid'],
            ];
        }

        dier([
            'code' => 100,
            'msg' => 'ok',
            'result' => [
                'total' => count($GoodsList),
                'data' => $Data,
            ]
        ]);
    }

    /**
     * @param $Data
     * 商品详情
     */
    private static function zhike_goods($Data)
    {
        $DB = SQL::DB();
        $Goods = $DB->get('goods', '*', [
            'gid' => $Data['goodsSN'],
        ]);
        if (!$Goods) {
            dies(900, "商品不存在！");
        }
        $Image = json_decode($Goods['image'], true);

        foreach ($Image as $k => $v) {
            $Image[$k] = ImageUrl($v);
        }

        $Pricer = Price::Get($Goods['money'], $Goods['profits'], self::$User['grade'], $Goods['gid'], $Goods['selling'], $Goods['profit_rule']);
        $Goods['price'] = $Pricer['price'];

        /**
         * 同步卡密库存
         */
        if ((int)$Goods['deliver'] === 3) {
            $Goods['quota'] = $DB->count('token', [
                "AND" => [
                    "uid" => 1,
                    "gid" => $Goods['gid']
                ],
            ]);
        }

        $params = [];
        if (!empty($Goods['input'])) {
            $Ex = explode('|', $Goods['input']);
            $index = 1;
            foreach ($Ex as $value) {
                if (empty($value)) {
                    continue;
                }
                if (strstr($value, '{') && strstr($value, '}')) {
                    $Name = explode('{', $value);
                    $data = explode(',', substr_replace($Name[1], "", -1));
                    $params[] = [
                        'name' => $Name[0],
                        'alias' => 'value' . $index,
                        'required' => true,
                        'assist' => '请从:' . implode('|', $data) . '，中选择一个参数来提交！',
                        'desc' => '请将' . $value . '提交完整，不能为空！'
                    ];
                } else {
                    $params[] = [
                        'name' => $value,
                        'alias' => 'value' . $index,
                        'required' => true,
                        'assist' => '',
                        'desc' => '请将' . $value . '填写完整，不能为空！'
                    ];
                }
                $index++;
            }
        }

        dier([
            'code' => 100,
            'result' => [
                'goodsId' => $Goods['gid'],
                'goodsSN' => $Goods['gid'],
                'goodsName' => $Goods['name'],
                'goodsThumb' => $Image[0],
                'goodsUnit' => $Goods['units'] ?? '个',
                'goodsDesc' => $Goods['alert'] ?? '',
                'minOrderNum' => $Goods['min'] * $Goods['quantity'],
                'maxOrderNum' => $Goods['max'] * $Goods['quantity'],
                'categoryName' => '分类' . $Goods['cid'],
                'goodsPrice' => $Goods['price'] / $Goods['quantity'],
                'goodsImages' => $Image,
                'goodsDetail' => $Goods['docs'] ?? '',
                'goodsStock' => $Goods['quota'] > 9999 ? -1 : $Goods['quota'], //超过9999则提示无限
                'preUnitNum' => $Goods['quantity'] - 0,
                'goodsType' => $Goods['deliver'] == 3 ? 2 : 1,
                'canTui' => false,
                'canRepeat' => (bool)strstr($Goods['method'], '7'), //是否可以重复下单
                'isClose' => $Goods['state'] != 1, //是否关闭下单
                'paramsTemplate' => $params
            ]
        ]);
    }

    /**
     * 商品下单
     */
    private static function zhike_order_buy()
    {
        $Data = json_decode(file_get_contents('php://input'), true);
        if (!$Data || empty($Data['goodsSN']) || empty($Data['customOrderSN']) || empty($Data['number'])) {
            dies(900, '请将参数提交完整,在body内提交JSON格式的数据，数据内容请参考开发文档：docs.79tian.com');
        }
        //检测重复订单
        if (is_file(__DIR__ . '/customOrderSN/' . md5($Data['customOrderSN']) . '.txt')) {
            $Content = file_get_contents(__DIR__ . '/customOrderSN/' . md5($Data['customOrderSN']) . '.txt');
            dier([
                'code' => 900,
                'msg' => '请勿提交重复订单,customOrderSN(' . $Data['customOrderSN'] . ')参数已经提交过了',
                'order' => $Content,
            ]);
        }

        $DB = SQL::DB();
        $Goods = $DB->get('goods', '*', [
            'gid' => $Data['goodsSN']
        ]);
        if ($Goods['state'] != 1) {
            dies(900, '商品已下架！');
        }
        /**
         * 同步卡密库存
         */
        if ((int)$Goods['deliver'] === 3) {
            $Goods['quota'] = $DB->count('token', [
                "AND" => [
                    "uid" => 1,
                    "gid" => $Goods['gid']
                ],
            ]);
        }
        if ($Goods['quota'] <= 0) {
            dies(900, '卡密库存不足，无法完成下单！');
        }
        //验证下单信息
        $DataInput = explode('|', $Goods['input']);
        $InputArr = [];
        $i = 1;
        foreach ($DataInput as $value) {
            if (empty($value)) {
                continue;
            }
            if (strstr($value, '{') && strstr($value, '}')) {
                $value = explode('{', $value)[0];
            }
            $input = '';
            foreach ($Data['params'] as $v) {
                if (!empty($v['value']) || $v['alias'] == 'value' . $i) {
                    $input = $v['value'];
                }
            }
            if ($input == '') {
                dies(900, '下单信息缺失,请将[' . $value . ']填写完整');
            }
            $InputArr[] = $input;
            unset($input);
            $i++;
        }

        $DataBuy = [
            'gid' => $Data['goodsSN'],
            'type' => 2,
            'num' => ($Data['number'] / $Goods['quantity']),
            'data' => $InputArr,
            'mode' => 'wxpay',
            'Api' => 1,
            'CouponId' => -1
        ];
        $order = Order::Creation($DataBuy, self::$User);
        if ($order) {
            $OrderData = $DB->get('order', ['order'], [
                'id' => $order,
            ]);
            $User = $DB->get('user', [
                'money'
            ], ['id' => self::$User['id']]);
            //写入重复订单限制信息
            mkdirs(__DIR__ . '/customOrderSN/');
            file_put_contents(__DIR__ . '/customOrderSN/' . md5($Data['customOrderSN']) . '.txt', $OrderData['order']);
            //写入下单备注
            if (!empty($Data['orderNote'])) {
                $DB->update('order', [
                    'remark' => htmlentities($Data['orderNote']),
                ], [
                    'order' => $OrderData['order']
                ]);
            }
            dier([
                'code' => 100,
                'msg' => '下单成功',
                'result' => [
                    'orderSN' => $OrderData['order'],
                    'money' => $User['money']
                ]
            ]);
        } else {
            dies(900, '订单创建失败，无法完成下单!');
        }
    }

    /**
     * @param $Data
     * 订单详情
     */
    private static function zhike_order($Data)
    {
        $DB = SQL::DB();
        $OrderData = $DB->get('order', ['id', 'order'], [
            'order' => $Data['orderSN']
        ]);
        if (!$OrderData) {
            dies(900, '订单不存在！');
        }
        $Order = Order::Query($OrderData['id'], self::$User['id'], 1, $OrderData['order']);
        $Order = $Order['data'];
        $params = [];
        foreach ($Order['input'] as $key => $value) {
            $Ex = explode('：', $value);
            $params[] = [
                'name' => $Ex[0],
                'alias' => 'value' . ($key + 1),
                'value' => $Ex[1],
            ];
        }

        dier([
            'code' => 100,
            'msg' => '订单查询成功！',
            'result' => [
                'orderSN' => $OrderData['order'],
                'goodsSN' => $Order['gid'],
                'GoodsName' => $Order['name'],
                'orderState' => self::zhike_state_get($Order['stateid']),
                'price' => $Order['price'] / ($Order['quantity'] * $Order['num']),
                'orderNum' => $Order['quantity'] * $Order['num'],
                'orderRemark' => $Order['remark'],
                'orderAmount' => $Order['price'],
                'finishTotal' => $Order['ApiSn'] == 1 ? $Order['ApiPresent'] : 0,
                'createdAt' => $Order['addtiem'],
                'refundAmount' => self::zhike_refundAmount($Order), // 获取退款金额
                'refundNumber' => self::zhike_refundAmount($Order, 2), //退款数量
                'buyNotify' => -1,
                'notifyPhone' => '',
                'startNum' => $Order['ApiSn'] == 1 ? $Order['ApiInitial'] : 0,
                'currentNum' => $Order['ApiSn'] == 1 ? $Order['ApiPresent'] : 0,
                'params' => $params,
                'logs' => [
                    [
                        'content' => '创建订单',
                        'createdAt' => $Order['addtiem']
                    ],
                    [
                        'content' => '处理订单',
                        'createdAt' => $Order['endtime']
                    ]
                ],
                'orderId' => $Order['id'],
                'cardNumber' => $Order['token_arr'] != -1 ? implode(',', $Order['token_arr']) : '',
            ]
        ]);
    }

    /**
     * @param $Order
     * @param $type //退款金额或退款数量
     * 获取退款金额或退款数量
     */
    private static function zhike_refundAmount($Order, $type = 1)
    {
        if ($Order['stateid'] != 5 || !strstr($Order['remark'], '退款金额为：')) {
            return 0;
        }
        $money = explode('退款金额为：', $Order['remark']);
        $money = explode('元！', $money[1]);
        $money = $money[0] - 0;

        if ($type == 1) {
            return $money;
        } else {
            $count = ($Order['num'] * $Order['quantity']);
            $Percentage = round(($money / $Order['price']), 2);
            return ceil($count * $Percentage);
        }
    }

    /**
     * @param $state
     * 获取匹配的状态
     */
    private static function zhike_state_get($state)
    {
        switch ((int)$state) {
            case 1: //成功
                return 4;
            case 2: //待处理
                return 2;
            case 3: //异常
                return 5;
            case 4: //正在处理
                return 2;
            case 5: //已退款
                return 7;
            case 6: //售后中
                return 5;
            case 7: //已评价
                return 4;
            default:
                return 8;

        }
    }

    /**
     * 获取用户信息，名称+余额，实时获取
     */
    private static function zhike_profile()
    {
        dier([
            'code' => 100,
            'msg' => 'ok',
            'result' => [
                'uid' => self::$User['id'],
                'username' => self::$User['name'],
                'balance' => self::$User['money']
            ]
        ]);
    }


    /**
     * @param $Data
     * 玖伍模拟
     */
    public static function jiuwu($Data)
    {
        if (isset($Data['c'])) {
            $Data['c'] = strtolower($Data['c']);
        }

        if (isset($Data['a'])) {
            $Data['a'] = strtolower($Data['a']);
        }

        switch ($Data['c']) {
            case 'api':
                $user = self::jiuwu_user($Data);
                if ($Data['a'] === 'user_get_goods_lists_details') {
                    self::jiuwu_GoodsList($user);
                } else if ($Data['a'] === 'get_goods_lists') {
                    self::jiuwu_GoodsListSimplify();
                }
                break;
            case 'goods':
                if ($Data['a'] === 'detail') {
                    $user = self::jiuwu_user_cookies();
                    self::jiuwu_GoodsDetail($user, $Data['id']);
                }
                break;
            case 'user':
                if ($Data['a'] === 'login') {
                    self::jiuwu_UserLogin($Data);
                }
                break;
            case 'order':
                $user = self::jiuwu_user($Data);
                if ($Data['a'] === 'add') {
                    self::jiuwu_buy($user, $Data);
                } else if ($Data['a'] === 'query_orders_detail') {
                    self::jiuwu_Order($user, $Data);
                }
                break;
        }
        self::jiuwu_error('此接口尚未模拟，无法获取对应数据[bbs.79tian.com]！');
    }

    /**
     * 通过cookie获取用户信息
     */
    public static function jiuwu_user_cookies()
    {
        $antiXss = new AntiXSS();
        $auto_uid = $antiXss->xss_clean(trim($_COOKIE['auto_uid']));
        $auto_token = $antiXss->xss_clean(trim($_COOKIE['auto_token']));

        $DB = SQL::DB();
        $User = $DB->get('user', '*', [
            'id' => $auto_uid,
            'state' => 1
        ]);
        if (!$User) {
            self::jiuwu_error('用户不存在，或已被禁止登录!');
        }

        if (md5($User['user_idu']) !== $auto_token) {
            self::jiuwu_error('用户token验证失败,请重新获取Cookie数据！');
        }

        return $User;
    }

    /**
     * @param $Data
     * 获取用户数据,通过，Api_UserName和Api_UserMd5Pass参数获取
     */
    public static function jiuwu_user($Data)
    {
        global $accredit;
        $DB = SQL::DB();
        $User = $DB->get('user', '*', [
            'id' => (int)$Data['Api_UserName'],
            'state' => 1
        ]);

        if (!$User) {
            self::jiuwu_error('用户不存在，或已被禁止登录，请使用本站用户后台的对接ID和对接密钥来进行对接!');
        }

        if (empty($User['token']) || md5($User['token']) !== $Data['Api_UserMd5Pass']) {
            self::jiuwu_error('对接密钥不正确，无法获取数据！');
        }

        if ($User['ip_white_list'] == '') {
            self::jiuwu_error('IP：' . userip() . ' 未设置对接白名单！');
        }

        $ArrayIp = explode('|', $User['ip_white_list']);
        if (!in_array(userip(), $ArrayIp)) {
            self::jiuwu_error('IP：' . userip() . ' 未设置对接白名单！');
        }
        $userJu = UserConf::judge();
        if ($userJu) {
            $_SESSION['UserGoods'] = xiaochu_en(json_encode(['uid' => $userJu['id'], 'grade' => $userJu['grade'], 'GoodsRis' => ($userJu['pricehike'] == '' ? [] : json_decode($userJu['pricehike'], TRUE))], JSON_UNESCAPED_UNICODE), $accredit['token']);
        }
        return $User;
    }

    /**
     * @param $User
     * @param $Data
     * 查询商品订单
     */
    public static function jiuwu_Order($User, $Data)
    {
        $Res = Order::Query($Data['orders_id'], $User['id'], 3);
        $Res = $Res['data'];
        $input = [
            'aa' => "",
            'bb' => "",
            'cc' => "",
            'dd' => "",
            'ee' => "",
        ];

        $input_arr = [];
        foreach ($Res['input'] as $value) {
            $Ex = explode('：', $value);
            $input_arr[] = $Ex[1];
        }
        $i = 0;
        foreach ($input as $key => $val) {
            $input[$key] = ($input_arr[$i] ?? '');
            ++$i;
        }

        $array = [
            'id' => $Res['order'],
            'user_note' => '',
            'need_num_0' => ((int)$Res['ApiSn'] == 1 ? (int)$Res['ApiNum'] : (int)$Res['num']),
            'need_num_1' => 0,
            'need_num_2' => 0,
            'need_num_3' => 0,
            'start_num' => ((int)$Res['ApiSn'] == 1 ? $Res['ApiInitial'] : 0),
            'end_num' => 0,
            'now_num' => ((int)$Res['ApiSn'] == 1 ? $Res['ApiPresent'] : 0),
            'login_state' => 0,
            'start_time' => $Res['addtime'],
            'end_time' => $Res['endtime'],
            'add_time' => $Res['addtime'],
            'order_amount' => (float)$Res['price'],
            'order_cardnum' => 0,
            'tui_amount' => 0,
            'tui_cardnum' => 0,
            'user_id' => $User['id'],
            'card_id' => '',
            'order_pay_type' => ($Res['type'] == '余额付款' ? 1 : 0),
            'goods_id' => $Res['gid'],
            'goods_type' => $Res['gid'],
            'goods_type_title' => $Res['gid'],
        ];

        switch ($Res['stateid']) {
            case 1: //已完成
                $array['order_state'] = 3;
                break;
            case 2: //未开始
                $array['order_state'] = 1;
                break;
            case 3: //退单中
                $array['order_state'] = 5;
                break;
            case 4: //进行中
                $array['order_state'] = 2;
                break;
            case 5: //已退单
                $array['order_state'] = 4;
                break;
            default:
                $array['order_state'] = 6;
                break;
        }
        dier([
            'status' => true,
            'rows' => [array_merge($array, $input)],
            'total' => 1
        ]);
    }

    /**
     * @param $User
     * @param $Data
     * 商品下单
     */
    public static function jiuwu_buy($User, $Data)
    {
        $DB = SQL::DB();
        $GoodsHide = UserConf::GoodsHide();

        if (count($GoodsHide) >= 1) {
            if (in_array($Data['goods_id'], $GoodsHide)) {
                self::jiuwu_error('商品已下架！');
            }
        }

        $Goods = $DB->get('goods', '*',
            ['gid' => (int)$Data['goods_id'], 'state' => 1, 'method[~]' => '4']);
        if (!$Goods) {
            self::jiuwu_error('抱歉,此商品已下架或不存在,无法被对接！');
        }

        $DataInput = explode('|', $Goods['input']);
        $InputArr = [];
        $i = 1;
        foreach ($DataInput as $value) {
            if (strstr($value, '{') && strstr($value, '}')) {
                $value = explode('{', $value)[0];
            }
            if (empty($Data['value' . $i])) {
                self::jiuwu_error('下单信息缺失,请将[' . $value . ']填写完整');
            }
            $InputArr[] = $Data['value' . $i];
            $i++;
        }

        $DataBuy = [
            'gid' => $Data['goods_id'],
            'type' => ($Data['pay_type'] == 1 ? 2 : 3),
            'num' => ($Data['need_num_0'] / $Goods['quantity']),
            'data' => $InputArr,
            'mode' => 'alipay',
            'Api' => 1,
            'CouponId' => -1
        ];

        $order = Order::Creation($DataBuy, $User);
        if ($order) {
            $OrderData = $DB->get('order', ['order'], [
                'id' => (int)$order,
            ]);

            $User = $DB->get('user', [
                'money', 'currency'
            ], ['id' => (int)$User['id']]);

            $Return = [
                'status' => 1,
                'order_id' => $OrderData['order'],
                'after_use_rmb' => ($Data['pay_type'] == 1 ? $User['money'] : $User['currency']),
                'after_use_cardnum' => ($Data['pay_type'] == 1 ? $User['money'] : $User['currency']),
                'msg' => '订单创建成功,购买后剩余' . ($Data['pay_type'] == 1 ? $User['money'] . '余额!' : $User['currency'] . '积分!'),
            ];

            $OrderToken = $DB->select('token', ['token'], ['order' => $OrderData['order']]);
            if ($OrderToken && count($OrderToken) >= 1) {
                $ArrayToken = [];
                foreach ($OrderToken as $value) {
                    $ArrayToken[] = $value['token'];
                }
                $Return['token'] = json_encode($ArrayToken);
                $Return['msg'] .= '，另,本次共发卡' . count($ArrayToken) . '张！';
            }
            dier($Return);
        } else {
            self::jiuwu_error('订单创建失败！');
        }
    }

    /**
     * @param $Data
     * 通对接数据获取登录token【UserToken】
     * username
     * username_password
     */
    public static function jiuwu_UserLogin($Data)
    {
        $DB = SQL::DB();
        $User = $DB->get('user', ['id', 'user_idu', 'ip_white_list', 'state'], [
            'id' => (int)$Data['username'],
            'token' => (string)$Data['username_password']
        ]);
        if (!$User) {
            self::jiuwu_error('用户名或密码不正确！');
        }

        if ($User['state'] != 1) {
            self::jiuwu_error('当前账户已被禁止登录！');
        }

        if ($User['ip_white_list'] == '') {
            self::jiuwu_error('IP：' . userip() . ' 未设置对接白名单！');
        }

        $ArrayIp = explode('|', $User['ip_white_list']);
        if (!in_array(userip(), $ArrayIp)) {
            self::jiuwu_error('IP：' . userip() . ' 未设置对接白名单！');
        }

        $time = (time() + 86400) * 7;
        setcookie("auto_uid", $User['id'], $time, "/");
        setcookie("auto_token", md5($User['user_idu']), $time, "/");
        setcookie("auto_time", $time, $time, "/");

        die('<div class="system-message">
		<!-- <h1>:)</h1> -->
	<p class="success">登录成功！</p>
		<p class="detail"></p>
	<p class="jump"><b id="wait">1</b> 秒后页面将自动跳转</p>
	<div>
		<a id="href" class="href" href="/index.php">立即跳转</a> 
		<button id="btn-stop" type="button" onclick="stop()">停止跳转</button> 
		<a class="href" href="/">到网站首页</a>
	</div>
</div>');
    }


    /**
     * @param $User
     * @param $Gid
     * 获取商品详情
     */
    public static function jiuwu_GoodsDetail($User, $Gid)
    {
        global $conf;
        $DB = SQL::DB();
        $GoodsHide = UserConf::GoodsHide();

        if (count($GoodsHide) >= 1 && in_array($Gid, $GoodsHide)) {
            self::jiuwu_error('此商品已下架！');
        }

        $Goods = $DB->get('goods', '*', [
            'gid' => (int)$Gid,
            'state' => 1,
            'method[~]' => '4',
            'specification[!]' => 2
        ]);

        if (!$Goods) {
            self::jiuwu_error('商品不存在或已下架!');
        }

        $input = '';
        $i = 1;
        if (!empty($Goods['input'])) {
            $Ex = explode('|', $Goods['input']);
            foreach ($Ex as $value) {
                if (strstr($value, '{') && strstr($value, '}')) {
                    $Name = explode('{', $value);
                    $input .= '<li><span class="fixed-width-right-80">' . $Name[0] . '：</span><input name="value' . $i . '" type="text" placeholder="请输入' . $Name[0] . '"/></li>' . "\n";
                } else {
                    $input .= '<li><span class="fixed-width-right-80">' . $value . '：</span><input name="value' . $i . '" type="text" placeholder="请输入' . $value . '"/></li>' . "\n";
                }
                ++$i;
            }
        } else {
            $input .= '<li><span class="fixed-width-right-80">下单账号：</span><input name="value' . $i . '" type="text" placeholder="请输入下单账号"/></li>' . "\n";
        }

        $Pricer = Price::Get($Goods['money'], $Goods['profits'], $User['grade'], $Goods['gid'], $Goods['selling'], $Goods['profit_rule']);
        $Goods['price'] = $Pricer['price'];
        $Goods['image'] = json_decode($Goods['image'], true)[0];
        $HtmlTP = file_get_contents(SYSTEM_ROOT . 'lib/Simulation/jiuwu.TP');

        $HtmlTP = str_replace("[content]", $Goods['docs'], $HtmlTP);
        $HtmlTP = str_replace("[name]", $Goods['name'], $HtmlTP);
        $HtmlTP = str_replace("[title]", $conf['sitename'], $HtmlTP);
        $HtmlTP = str_replace("[username]", $User['id'], $HtmlTP);
        $HtmlTP = str_replace("[money]", round($User['money']), $HtmlTP);
        $HtmlTP = str_replace("[integration]", $User['currency'], $HtmlTP);
        $HtmlTP = str_replace("[UnitPrice]", ($Goods['price'] / $Goods['quantity']), $HtmlTP);
        $HtmlTP = str_replace("[gid]", $Goods['gid'], $HtmlTP);
        $HtmlTP = str_replace("[cid]", $Goods['cid'], $HtmlTP);
        $HtmlTP = str_replace("[min]", ($Goods['min'] * $Goods['quantity']), $HtmlTP);
        $HtmlTP = str_replace("[max]", ($Goods['max'] * $Goods['quantity']), $HtmlTP);
        $HtmlTP = str_replace("[MinPrice]", ($Goods['min'] * ($Goods['price'] / $Goods['quantity'])), $HtmlTP);
        $HtmlTP = str_replace("[input]", $input, $HtmlTP);
        $HtmlTP = str_replace("[units]", $Goods['units'], $HtmlTP);

        die($HtmlTP);
    }

    /**
     * 获取商品列表[精简]
     */
    public static function jiuwu_GoodsListSimplify()
    {
        $List = [];
        $DB = SQL::DB();
        $GoodsHide = UserConf::GoodsHide();
        $SQL = [
            'ORDER' => ['sort' => 'DESC'],
            'method[~]' => '4',
            'specification[!]' => 2 //不取出规格商品
        ];
        if (count($GoodsHide) >= 1) {
            $SQL = array_merge($SQL, [
                'gid[!]' => $GoodsHide
            ]);
        }

        $Count = $DB->count('goods', $SQL);
        if ($Count === 0) {
            dies(-1, '一个可对接商品都没有！');
        }

        $GoodsList = $DB->select('goods', [
            'gid', 'cid', 'name', 'image', 'units', 'quantity', 'min', 'max'
        ], $SQL);

        foreach ($GoodsList as $Goods) {
            if (!empty($Goods['image'])) {
                $Goods['image'] = json_decode($Goods['image'], TRUE)[0];
            }
            $List[] = [
                'id' => (int)$Goods['gid'],
                'title' => $Goods['name'] ?? '',
                'streamline_title' => $Goods['name'],
                'thumb' => $Goods['image'] ?? '',
                'unit' => $Goods['units'] ?? '个',
                'minbuynum_0' => ($Goods['min'] * $Goods['quantity']),
                'maxbuynum_0' => ($Goods['max'] * $Goods['quantity']),
                'goods_type' => (int)$Goods['cid'],
                'goods_type_title' => '分类：' . $Goods['cid'],
            ];
            unset($Goods);
        }

        dier([
            'status' => 1,
            'msg' => '获取成功',
            'goods_rows' => $List,
        ]);
    }

    /**
     * @param $User
     * 获取商品列表
     */
    public static function jiuwu_GoodsList($User)
    {
        $List = [];
        $DB = SQL::DB();
        $GoodsHide = UserConf::GoodsHide();
        $SQL = [
            'ORDER' => ['sort' => 'DESC'],
            'method[~]' => '4',
            'specification[!]' => 2 //不取出规格商品
        ];
        if (count($GoodsHide) >= 1) {
            $SQL = array_merge($SQL, [
                'gid[!]' => $GoodsHide
            ]);
        }

        $Count = $DB->count('goods', $SQL);
        if ($Count === 0) {
            dies(-1, '一个可对接商品都没有！');
        }

        $GoodsList = $DB->select('goods', [
            'gid', 'cid', 'name', 'state', 'image',
            'units', 'selling', 'profits', 'money',
            'quantity', 'min', 'max', 'alert', 'profit_rule'
        ], $SQL);

        foreach ($GoodsList as $Goods) {

            if (!empty($Goods['image'])) {
                $Goods['image'] = json_decode($Goods['image'], TRUE)[0];
            }

            $Pricer = Price::Get($Goods['money'], $Goods['profits'], $User['grade'], $Goods['gid'], $Goods['selling'], $Goods['profit_rule']);
            $Goods['price'] = $Pricer['price'];

            $List[] = [
                'id' => (int)$Goods['gid'],
                'title' => $Goods['name'],
                'thumb' => $Goods['image'],
                'unit' => $Goods['units'],
                'goods_unitprice' => ($Goods['price'] / $Goods['quantity']),
                'user_unitprice' => ($Goods['price'] / $Goods['quantity']),
                'no_display' => 0,
                'goods_status' => ($Goods['state'] == 1 ? 0 : 1),
                'goods_close_msg' => $Goods['alert'],
                'minbuynum_0' => ($Goods['min'] * $Goods['quantity']),
                'maxbuynum_0' => ($Goods['max'] * $Goods['quantity']),
                'goods_type' => (int)$Goods['cid'],
                'goods_type_title' => '分类：' . $Goods['cid'],
            ];
        }

        dier([
            'status' => true,
            'msg' => '获取成功',
            'user_goods_lists_details' => $List,
        ]);
    }


    /**
     * @param $msg
     * @return never
     * 玖伍错误提示信息
     */
    public static function jiuwu_error($msg = '错误提示!')
    {
        dier([
            'status' => false,
            'msg' => '<p class="error">' . $msg . '</p>',
        ]);
//        die('<div class="system-message">
//		<!-- <h1>:(</h1> -->
//	<p class="error">' . $msg . '</p>
//		<p class="detail"></p>
//	<p class="jump"><b id="wait">3</b> 秒后页面将自动跳转</p>
//	<div>
//		<a id="href" class="href" href="javascript:history.back(-1);">立即跳转</a>
//		<button id="btn-stop" type="button" onclick="stop()">停止跳转</button>
//		<a class="href" href="/">到网站首页</a>
//	</div>
//</div>');
    }


}