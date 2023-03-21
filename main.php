<?php

/**
 * Author：晴玖天
 * Creation：2020/7/20 18:59
 * Filename：main.php
 */


include './includes/fun.global.php';
header('Content-Type: application/json; charset=UTF-8');

global $conf, $_QET, $times, $date;

use extend\GoodsCart;
use extend\GoodsImage;
use extend\UserConf;
use lib\AppStore\AppList;
use lib\Hook\Hook;
use lib\supply\GoodsMonitoring;
use lib\supply\Order;
use lib\supply\ProductsExchange;
use Medoo\DB\SQL;

switch ($_QET['act']) {
    case 'banner':
        $Data = HomecachingVerify('banner', ['Home']);
        if (!$Data) {
            $banner = explode('|', $conf['banner']);
            $Data = [];
            foreach ($banner as $v) {
                $is = explode('*', $v);
                $Data[] = [
                    'image' => ImageUrl($is[0]),
                    'url' => htmlspecialchars_decode($is[1]),
                ];
            }
            HomecachingAdd('banner', ['Home'], $Data);
        }
        dier([
            'code' => 1,
            'msg' => '获取成功',
            'title' => ($conf['title'] == '' ? $conf['sitename'] : $conf['sitename'] . '-' . $conf['title']),
            'data' => $Data,
        ]);
        break;
    case 'Service': //客服数据
        if (empty($conf['kfqq'])) {
            $conf['kfqq'] = "暂未配置";
        }
        $kfqqImage = ImageUrl($conf['ServiceImageQQ']);
        if (empty($conf['ServiceImageQQ'])) {
            QR_Code(2, '客服QQ：' . $conf['kfqq'], 5, 0, SYSTEM_ROOT . 'extend/log/ShopImage/' . md5($conf['kfqq']) . '.png');
            $kfqqImage = href(2) . ROOT_DIR_S . '/includes/extend/log/ShopImage/' . md5($conf['kfqq']) . '.png';
        }
        $kfwxImage = ImageUrl($conf['ServiceImage']);
        if (empty($conf['ServiceImage'])) {
            $kfwxImage = false;
        }
        dier([
            'code' => 1,
            'msg' => '数据获取成功',
            'qq' => $conf['kfqq'], //客服QQ
            'wx' => $conf['kfwx'], //客服微信
            'qq_image' => $kfqqImage, //客服qq二维码
            'wx_image' => $kfwxImage, //客服微信二维码
            'tips' => htmlspecialchars_decode($conf['ServiceTips']) ?? false, //客服界面提示
            'GroupUrl' => htmlspecialchars_decode($conf['Communication']) ?? false, //加群链接
        ]);
        break;
    case 'ArticleList': //文章列表
        test(['page|e']);
        $User = login_data::user_data();
        $LIMIT = (empty($conf['HomeLimit']) ? 12 : $conf['HomeLimit']);
        $Page = ($_QET['page'] - 1) * $LIMIT;

        $SQL = [
            'LIMIT' => [$Page, $LIMIT],
            'state' => 1,
            'ORDER' => [
                'id' => 'DESC'
            ]
        ];

        if (!$User) {
            $SQL['type[!]'] = 2;
        }

        if (!empty($_QET['search'])) {
            $SQL = array_merge($SQL, [
                'title[~]' => $_QET['search']
            ]);
            Hook::execute('ArticleSeek', [
                'uid' => (!$User ? -1 : $User['id']),
                'search' => $_QET['search']
            ]);
        } else {
            Hook::execute('ArticleList', [
                'uid' => (!$User ? -1 : $User['id'])
            ]);
        }
        $DB = SQL::DB();
        $SQL2 = $SQL;
        unset($SQL2['LIMIT'], $SQL2['ORDER']);
        $Count = $DB->count('notice', $SQL2);

        $Data = HomecachingVerify('ArticleList', [$_QET['page'], $_QET['search']]);
        if ($Data !== false) {
            if (!empty($_QET['search'])) {
                Hook::execute('ArticleSeek', [
                    'uid' => ($User === false ? -1 : $User['id']),
                    'search' => $_QET['search']
                ]);
            } else {
                Hook::execute('ArticleList', [
                    'uid' => (!$User ? -1 : $User['id'])
                ]);
            }
            dier([
                'code' => 1,
                'msg' => '数据获取成功',
                'data' => $Data,
                'count' => $Count,
                'limit' => $LIMIT,
            ]);
        }
        $Res = $DB->select('notice', '*', $SQL);
        if ($Res) {
            $Data = [];
            foreach ($Res as $v) {
                $Data[] = [
                    'id' => $v['id'],
                    'title' => $v['title'],
                    'content' => $v['content'],
                    'image' => ImageUrl($v['image']),
                    'count' => $v['PV'],
                    'addtime' => $v['date']
                ];
            }
            HomecachingAdd('ArticleList', [$_QET['page'], $_QET['search']], $Data);
            dier([
                'code' => 1,
                'msg' => '数据获取成功',
                'data' => $Data,
                'count' => $Count,
                'limit' => $LIMIT,
            ]);
        } else {
            dies(-2, '文章列表获取失败');
        }
        break;
    case 'class': //取出分类列表
        test(['num|i']);
        $User = login_data::user_data();
        $Hide = UserConf::GoodsHide();
        $DataList = HomecachingVerify($_QET['act'] . $_QET['num'] . ($_QET['cid'] ?? -1), $User);
        if (!$User || empty($User['grade'])) {
            $grade = 1;
        } else {
            $grade = (int)$User['grade'];
        }
        if (!$DataList) {
            $DB = SQL::DB();
            $SQL = [
                'grade[<=]' => $grade,
                'ORDER' => [
                    'sort' => 'DESC'
                ],
                'state' => 1,
                'LIMIT' => $_QET['num'],
                'superior' => -1, //只取出一级分类
            ];
            if (!empty($_QET['cid'])) {
                $SQL['cid'] = $_QET['cid'];
            }
            $Res = $DB->select('class', '*', $SQL);
            if (!$Res) {
                $Res = [];
            }
            //根据一级分类取出下级分类
            $DataList = [];
            foreach ($Res as $k => $v) {
                $v['image'] = ImageUrl($v['image']);
                $DataList[$k] = $v;
                $List = $DB->select('class', '*', [
                    'superior' => $v['cid'],
                    'state' => 1,
                    'ORDER' => [
                        'sort' => 'DESC'
                    ],
                    'grade[<=]' => $grade,
                ]);
                //遍历子分类
                $DataList[$k]['SuborderNumber'] = count($List);
                if ($List && count($List) >= 1) {
                    foreach ($List as $vs) {
                        $vs['count'] = $DB->count('goods', [
                            'cid' => $vs['cid'],
                            'state' => 1,
                            'gid[!]' => (count($Hide) == 0 ? '' : $Hide)
                        ]);
                        $vs['image'] = ImageUrl($vs['image']);
                        $DataList[$k]['data'][] = $vs;
                    }
                } else {
                    $DataList[$k]['data'] = [];
                }
                $cidlist[] = $DataList[$k]['cid'];
                if ($List && count($List) >= 1) {
                    foreach ($List as $vs) {
                        $cidlist[] = $vs['cid'];
                    }
                }
                $DataList[$k]['count'] = $DB->count('goods', [
                    'state' => 1,
                    'cid' => $cidlist,
                    'gid[!]' => (count($Hide) == 0 ? '' : $Hide)
                ]);
                unset($cidlist);
                unset($List);
            }
            HomecachingAdd($_QET['act'] . $_QET['num'] . ($_QET['cid'] ?? -1), $User, $DataList);
        }
        dier([
            'code' => 1,
            'msg' => '商品分类数据获取成功',
            'data' => $DataList
        ]);
        break;
    case 'GoodsList': //取出商品列表
        $Data = HomePage::GoodsList($_QET);
        dier($Data);
        break;
    case 'inform': //取出系统公告+导航+app下载点
        $User = login_data::user_data();
        $Data = HomecachingVerify('inform', ['Home']);
        if ($Data) {
            $Data['data']['User'] = $User;
            dier($Data);
        }
        $navigationArr = [];
        foreach (explode('|', $conf['navigation']) as $value) {
            $arr = explode(',', $value);
            $navigationArr[] = [
                'name' => $arr[0],
                'url' => htmlspecialchars_decode($arr[1])
            ];
        }
        foreach (AppList::AppMenuList("MenuHome") as $value) {
            $navigationArr[] = [
                'name' => $value['name'],
                'url' => href(2) . ROOT_DIR_S . '/main.php?act=AppView&id=' . $value['id'] . '&path=index',
            ];
        }
        $data = [
            'code' => 1,
            'msg' => '获取成功',
            'data' => [
                'NoticeTop' => $conf['notice_top'], //首页顶部
                'NoticeUser' => $conf['notice_user'], //用户后台
                'PopupNotice' => $conf['PopupNotice'], //弹窗
                'NoticeBottom' => $conf['notice_bottom'], //底部
                'NoticeCheck' => $conf['notice_check'], //查单
                'Appurl' => htmlspecialchars_decode($conf['appurl']), //APP下载地址
                'Navigation' => $navigationArr, //自定义导航
                'UserImage' => UserImage($User),
                'HostAnnounced' => $conf['HostAnnounced'], //主机后台公告
                'FluctuationsPrices' => $conf['FluctuationsPrices'], //价格波动配置
                'User' => $User,
                'title' => $conf['sitename'], //网站名称
                'subTitle' => $conf['title'], //网站副标题
                'logo' => ImageUrl($conf['logo']),
                'RecordNumber' => $conf['RecordNumber'], //备案号
                'keywords' => $conf['keywords'], //网站关键词
                'description' => $conf['description'], //网站描述
                'currency' => $conf['currency'], //货币名称
                'CartState' => $conf['CartState'] == 1, //购物车开关
            ]
        ];
        HomecachingAdd('inform', ['Home'], $data);
        dier($data);
        break;
    case 'ActivitiesGoods': //限价秒杀商品
        HomePage::ActivitiesGoods();
        break;
    case 'Goods': //取出商品详情
        test(['gid|e']);
        $Data = HomePage::ProductDetails($_QET);
        dier($Data);
        break;
    case 'CouponGet': //领取优惠券
        test(['token|e'], '请提交优惠券识别码');
        $User = login_data::user_data();
        if (!$User) {
            dies(-2, '请先完成登录！');
        }
        $DB = SQL::DB();
        $Coupon = $DB->get('coupon', '*', [
            'limit_token' => (string)$_QET['token'],
            'oid' => -1,
            'uid' => -1,
            'get_way[!]' => 1
        ]);

        if (!$Coupon) {
            dies(-1, '优惠券不足，已被领取完');
        }

        $Count = $DB->count('coupon', [
            'limit_token' => (string)$_QET['token'],
            'uid' => $User['id'],
        ]);

        if ($Count >= $Coupon['limit']) {
            dies(-1, '此批优惠券每个用户最多领取' . $Coupon['limit'] . '张，无法继续领取！');
        }

        $CSQL = [
            'uid' => $User['id'],
            'gettime[>]' => $times,
        ];
        if ($conf['CouponUseIpType'] == 1) {
            $CSQL = [
                'OR' => [
                    'uid' => $User['id'],
                    'ip' => userip()
                ],
                'gettime[>]' => $times,
            ];
        }

        $CountUser = $DB->count('coupon', $CSQL);

        if ($CountUser >= $conf['CouponGetMax']) {
            dies(-1, '今日最多可领取' . $conf['CouponGetMax'] . '张优惠券,您已经领取了' . $CountUser . '张,请改日再来');
        }


        $Res = $DB->update('coupon', [
            'uid' => $User['id'],
            'gettime' => $date,
            'ip' => userip()
        ], [
            'id' => $Coupon['id'],
        ]);
        if ($Res) {
            userlog('领取优惠券', '用户[' . $User['id'] . ']于' . $date . '领取了优惠券[' . $Coupon['token'] . ']', $User['id'], '0');
            dies(1, '领取成功');
        } else {
            dies(-1, '领取失败!');
        }
        break;
    case 'CouponList': //取出优惠券列表
        test(['type|e'], '请选择需要获取的类别');

        $SQL = [
            'GROUP' => [
                'limit_token',
            ],
            'oid' => -1,
            'get_way[!]' => 1,
        ];

        if ($_QET['type'] == 1) {
            test(['gid|e'], '请提交需要获取的商品id');
            $SQL['gid'] = $_QET['gid'];
            $SQL['get_way'] = 2;
        } else if ($_QET['type'] == 2) {
            test(['cid|e'], '请提交需要获取的分类id');
            $SQL['cid'] = $_QET['cid'];
            $SQL['get_way'] = 3;
        } else {
            $SQL['get_way'] = 4;
        }
        $User = login_data::user_data();
        if (!$User) {
            $SQL['uid'] = -1;
        } else {
            $SQL['uid'] = [$User['id'], -1];
        }
        $DB = SQL::DB();
        $Coupon = $DB->select('coupon', ['name', 'content', 'limit_token', 'money', 'minimum', 'type', 'apply', 'term_type', 'expirydate', 'indate', 'gid', 'cid'], $SQL);
        $Data = [];
        if (!$Coupon) {
            $Coupon = [];
        }
        foreach ($Coupon as $v) {
            if ($User !== false) {
                $v['count'] = $DB->count('coupon', [
                    'limit_token' => $v['limit_token'],
                    'uid' => $User['id'],
                    'get_way[!]' => 1,
                    'oid' => -1,
                ]);
            } else {
                $v['count'] = 0;
            }
            if ($v['term_type'] == 2 && time() > strtotime($v['expirydate'])) {
                continue;
            }
            $Type = 1;
            switch ($v['apply']) {
                case 1:
                    $Name = $DB->get('goods', ['name'], ['gid' => (int)$v['gid']]);
                    if (!$Name) $Type = 2;
                    $scope = '此优惠券可以在购买商品[' . $Name['name'] . ']时使用！';
                    break;
                case 2:
                    $Name = $DB->get('class', ['name'], ['cid' => (int)$v['cid']]);
                    if (!$Name) $Type = 2;
                    $scope = '此优惠券可以在分类[' . $Name['name'] . ']下的任意商品时使用！';
                    break;
                case 3:
                    $scope = '此优惠券可以在购买任意商品时使用！';
                    break;
            }
            if ($Type == 2) continue;
            if ($v['term_type'] == 2) {
                $v['expirydate'] = explode(' ', $v['expirydate'])[0];
            }
            $v['scope'] = $scope;
            $Data[] = $v;
        }

        if (count($Data) >= 1) {
            dier([
                'code' => 1,
                'msg' => '共有' . count($Data) . '个可领取优惠券！',
                'data' => $Data,
                'type' => (!$User ? -1 : 1),
            ]);
        } else {
            dies(-3, '一张可用优惠券都没有哦');
        }
        break;
    case 'PriceCalculation': //商品售价计算！
        dies(-1, '此接口已废除,此模板已失效！');
        break;
    case 'CreateOrder': //验证商品订单
        test(['gid|e', 'num|i']);
        if ($_QET['num'] <= 0) {
            $_QET['num'] = 1;
        }
        $Data = HomePage::CreateOrder($_QET);
        dier($Data);
        break;
    case 'PaymentWay': //获取支付通道状态
        test(['gid|i', 'type|i']);
        $User = login_data::user_data();
        switch ($_QET['type']) {
            case 1: //普通付款订单
                $DB = SQL::DB();
                $Goods = $DB->get('goods', [
                    'method', 'cid', 'gid'
                ], [
                    'gid' => (int)$_QET['gid'],
                    'state' => 1
                ]);

                if (!$Goods) {
                    dies(-1, '商品不存在,或已下架,无法进行购买！');
                }

                $Class = $DB->get('class', ['support'], [
                    'cid' => (int)$Goods['cid']
                ]);

                $Meth = json_decode($Goods['method'], true);
                $Goods['method'] = PaymentMethodAnalysis($Meth);

                if ($Class) {
                    $support = explode(',', $Class['support']);
                    $Data = [[
                        'type' => ($conf['PayConQQ'] == -1 || $support[0] != 1 || !in_array(1, $Meth) ? 2 : 1), //QQ
                    ], [
                        'type' => ($conf['PayConWX'] == -1 || $support[1] != 1 || !in_array(1, $Meth) ? 2 : 1), //微信
                    ], [
                        'type' => ($conf['PayConZFB'] == -1 || $support[2] != 1 || !in_array(1, $Meth) ? 2 : 1), //支付宝
                    ], [
                        'type' => ($support[3] != 1 || $Goods['method'] == 3 ? 2 : 1),
                        'surplus' => (!$User ? '未登陆，点击登陆' : '当前余额：' . ($User['money'] - 0) . '元')
                    ], [
                        'type' => ($support[4] != 1 || $Goods['method'] == 2 ? 2 : 1),
                        'surplus' => (!$User ? '未登陆，点击登陆' : '当前' . $conf['currency'] . '：' . $User['currency'])
                    ]];
                } else {
                    $Data = [[
                        'type' => ($conf['PayConQQ'] == -1 || !in_array(1, $Meth) ? 2 : 1), //QQ
                    ], [
                        'type' => ($conf['PayConWX'] == -1 || !in_array(1, $Meth) ? 2 : 1), //微信
                    ], [
                        'type' => ($conf['PayConZFB'] == -1 || !in_array(1, $Meth) ? 2 : 1), //支付宝
                    ], [
                        'type' => ($Goods['method'] == 3 ? 2 : 1),
                        'surplus' => (!$User ? '未登陆，点击登陆' : '当前余额：' . $User['money'])
                    ], [
                        'type' => ($Goods['method'] == 2 ? 2 : 1),
                        'surplus' => (!$User ? '未登陆，点击登陆' : '当前' . $conf['currency'] . '：' . $User['currency'])
                    ]];
                }

                dier([
                    'code' => 1,
                    'msg' => '数据获取成功',
                    'data' => $Data,
                    'state' => ($User === false ? 2 : 1)
                ]);
                break;
            case 2: //购物车
                $Arr = explode(',', $conf['CartPaySet']);
                $Data = [[
                    'type' => ($conf['PayConQQ'] == -1 || !in_array(3, $Arr) ? 2 : 1), //QQ
                ], [
                    'type' => ($conf['PayConWX'] == -1 || !in_array(2, $Arr) ? 2 : 1), //微信
                ], [
                    'type' => ($conf['PayConZFB'] == -1 || !in_array(1, $Arr) ? 2 : 1), //支付宝
                ], [
                    'type' => (!in_array(4, $Arr) ? 2 : 1),
                    'surplus' => (!$User ? '未登陆，点击登陆' : '当前余额：' . $User['money'])
                ], [
                    'type' => (!in_array(5, $Arr) ? 2 : 1),
                    'surplus' => (!$User ? '未登陆，点击登陆' : '当前' . $conf['currency'] . '：' . $User['currency'])
                ]];

                dier([
                    'code' => 1,
                    'msg' => '数据获取成功',
                    'data' => $Data,
                    'state' => (!$User ? 2 : 1),
                    'notice' => html_entity_decode($conf['CartNotice']),
                    'currency' => $conf['currency']
                ]);
                break;
        }

        break;
    case 'Pay': //开始支付
        RVS(1000);
        test(['gid|e', 'num|e', 'type|e', 'mode|e']);
        $gid = (int)$_QET['gid'];
        $num = (int)$_QET['num'];
        $type = (int)$_QET['type'];
        $mode = $_QET['mode'];
        $CouponId = (empty($_QET['CouponSelect']) ? -1 : $_QET['CouponSelect']);
        unset($_QET['gid'], $_QET['num'], $_QET['type'], $_QET['act'], $_QET['mode'], $_QET['CouponSelect']);
        $User = login_data::user_data();
        foreach ($_QET as $value) {
            if (empty($value)) {
                dies(-1, '请填写完整！');
            }
        }
        $order = Order::Creation([
            'gid' => $gid,
            'num' => $num,
            'data' => $_QET,
            'type' => $type,
            'mode' => $mode,
            'CouponId' => (!$User ? -1 : $CouponId)
        ], $User);
        if ($order !== false) {
            OrderStatus($order);
        } else {
            dies(-1, '订单创建失败,请联系管理员处理！');
        }
        break;
    case 'Discuss': //评论列表
        test(['gid|e', 'num|e']);
        $Data = HomecachingVerify($_QET['act'], [$_QET['gid'], $_QET['num']]);
        if (!$Data) {
            $DB = SQL::DB();
            $Res = $DB->select('mark', [
                '[>]user' => ['uid' => 'id']
            ], [
                'mark.id',
                'user.image',
                'user.name(username)',
                'mark.content',
                'mark.image(imageArr)',
                'mark.score',
                'mark.name(goodsname)'
            ], ['mark.gid' => $_QET['gid'], 'mark.state' => 1, 'LIMIT' => (int)$_QET['num'], 'ORDER' => [
                'id' => 'DESC'
            ]]);

            $Data = [];
            if (!$Res) {
                $Res = [];
            }
            foreach ($Res as $v) {
                if (!empty($v['imageArr'])) {
                    $is = explode('|', $v['imageArr']);
                    $d = [];

                    foreach ($is as $vs) {
                        $d[] = ImageUrl($vs);
                    }
                    $v['imageArr'] = $d;
                } else {
                    $v['imageArr'] = [];
                }

                $Data[] = $v;
            }

            HomecachingAdd($_QET['act'], [$_QET['gid'], $_QET['num']], $Data);
        }

        $Avg = $DB->avg('mark', 'score', ['gid' => $_QET['gid'], 'state' => 1]);

        dier([
            'code' => 1,
            'msg' => '评论列表获取成功',
            'data' => $Data,
            'count' => (int)$DB->count('mark', [
                'gid' => $_QET['gid'],
                'state' => 1
            ]),
            'avg' => (int)$Avg
        ]);
        break;
    case 'GuessYouLike': //推荐商品
        test(['gid|e', 'page|e']);

        if ($conf['GoodsRecommendation'] != 1) {
            dies(-2, '商品推荐已关闭！');
        }
        $Data = HomePage::GuessYouLike($_QET);
        dier($Data);
        break;
    case 'OrdeTips': //新订单通知，动态通知

        if ($conf['DynamicMessage'] != 1) {
            dies(-2, '动态通知已经关闭');
        }

        $Data = HomecachingVerify('OrdeTips', ['Home']);
        if ($Data) {
            dier($Data);
        }

        $SQL = [
            'ORDER' => [
                'order.addtitm' => 'DESC'
            ],
            'LIMIT' => 30
        ];

        if (!empty($_QET['gid'])) {
            $SQL = array_merge($SQL, [
                'order.gid' => $_QET['gid']
            ]);
        }
        $DB = SQL::DB();
        $Res = $DB->select('order', [
            '[>]goods' => ['gid' => 'gid'],
            '[>]user' => ['uid' => 'id']
        ], [
            'order.addtitm',
            'goods.name(title)',
            'user.name',
            'user.image',
            'user.id'
        ], $SQL);

        $Data = [];
        if (!$Res) {
            $Res = [];
        }
        foreach ($Res as $v) {
            $Data[] = [
                'title' => ($v['name'] == '' ? '游客' : $v['name']) . '购买了' . (!empty($_QET['gid']) ? '此商品' : $v['title']),
                'img' => ($v['image'] ?? background::Bing_random() . '?t=' . $v['id']),
            ];
        }

        if (count($Data) < 30) {
            $SQL2 = [
                'mark.state' => 1,
                'LIMIT' => 30,
                'ORDER' => [
                    'mark.addtime' => 'DESC'
                ],
            ];

            if (!empty($_QET['gid'])) {
                $SQL2 = array_merge($SQL2, [
                    'mark.gid' => $_QET['gid']
                ]);
            }

            $Res = $DB->select('mark', [
                '[>]user' => ['uid' => 'id']
            ], [
                'mark.name(title)',
                'user.name',
                'user.image',
                'user.id',
                'mark.addtime',
                'mark.score'
            ], $SQL2);
            if (!$Res) {
                $Res = [];
            }
            foreach ($Res as $v) {
                if (empty($_QET['gid'])) {
                    $msg = ($v['name'] == '' ? '用户' . $v['uid'] : $v['name']) . '评论了' . (!empty($_QET['gid']) ? '此商品' : $v['title']);
                } else {
                    $msg = ($v['name'] == '' ? '用户' . $v['uid'] : $v['name']) . TimeLag($v['addtime']) . '前给出了' . $v['score'] . '星评价！';
                }
                $Data[] = [
                    'title' => $msg,
                    'img' => ($v['image'] ?? background::Bing_random() . '?t=' . $v['id']),
                ];
            }
        }

        if (count($Data) < 60) {
            $SQL3 = [
                'LIMIT' => 30,
                'ORDER' => [
                    'journal.date' => 'DESC'
                ],
                'journal.name' => '每日签到'
            ];

            $Res = $DB->select('journal', [
                '[>]user' => ['uid' => 'id']
            ], [
                'user.name',
                'user.image',
                'user.id',
                'journal.date'
            ], $SQL3);
            if (!$Res) {
                $Res = [];
            }
            foreach ($Res as $v) {
                $Data[] = [
                    'title' => ($v['name'] == '' ? '用户' : $v['name']) . '在后台签到成功,获得了奖励！',
                    'img' => ($v['image'] ?? background::Bing_random() . '?t=' . $v['id']),
                ];
            }
        }
        $data = [
            'code' => 1,
            'msg' => '数据获取成功',
            'data' => $Data
        ];
        HomecachingAdd('OrdeTips', ['Home'], $data);
        dier($data);
        break;
    case 'GoodsMonitoring': //商品价格监控
        if ((int)$conf['SupervisorySwitch'] !== 1) {
            dies(1, '商品价格监控功能未开启！请前往主站后台手动开启！');
        }
        test(['gid|e']);
        $gid = (int)$_QET['gid'];
        $DB = SQL::DB();
        $Goods = $DB->get('goods', '*', [
            'gid' => $gid,
            'method[~]' => '5',
            'deliver' => -1,
        ]);
        if (!$Goods) {
            dies(1, '商品不存在或此商品无需监控！');
        }

        $Type = GoodsMonitoring::DirectionalMonitoring($Goods, 2);

        if ($Type['code'] === 1) {
            dies(-1, $conf['SupervisoryMsg']);
        } else {
            dies(1, '商品价格无需调整！');
        }
        break;
    case 'SharePoster': //商品海报图分享
        test(['gid|e']);
        Hook::execute('SharePoster', [
            'gid' => $_QET['gid']
        ]);
        $image = ($conf['share_image'] == 1 ? GoodsImage::mergePic($_QET['gid']) : GoodsImage::mergPicClouds($_QET['gid']));
        if ($image) {
            $images = ImageUrl($image);
            Hook::execute('GoodsSharePoster', [
                'gid' => $_QET['gid'],
                'image' => $images
            ]);
            dier([
                'code' => 1,
                'msg' => '商品海报图生成成功！',
                'src' => $images . '?sr=' . time()
            ]);
        } else {
            dies(-1, '商品海报图生成失败！');
        }
        break;
    case 'OrderState': //查询支付状态，公共查询模块，和支付调用相互独立
        test(['pid|e', 'type|e']);
        $DB = SQL::DB();
        $Pay = $DB->get(($_QET['type'] == 1 ? 'pay' : 'order'), '*', [
            'id' => (int)$_QET['pid']
        ]);

        if (!$Pay) {
            dies(-3, '订单不存在,或未提交！');
        }

        if ($Pay['state'] == 1 || $_QET['type'] == 2) {
            switch ($Pay['gid']) {
                case -1: //在线充值
                    dies(1, '恭喜您成功充值' . round($Pay['money'], 2) . '元！');
                    break;
                case -2: //订单队列
                    dies(1, '恭喜您成功购买' . $Pay['name'] . '！');
                    break;
                default: //普通付款订单
                    OrderStatus($Pay['trade_no'], 2);
                    break;
            }
        } else {
            dies(-2, '支付订单未完成！');
        }
        break;
    case 'SubmitOrder': //提交队列订单到服务器
        Order::SubmitOrderQueue((empty($_QET['id']) ? false : $_QET['id']));
        break;
    case 'OrderPay': //队列订单付款！
        test(['id|e']);
        query::OrderPay($_QET['id']);
        break;
    case 'OrderList': //取出订单列表
        test(['type|e']);
        $User = login_data::user_data();
        switch ($_QET['type']) {
            case 'OrderAll':
                query::OrderAll($_QET['id'] ?? false, $_QET['seek'] ?? '', $_QET['state'] ?? 1, $_QET['page'] ?? 1, $User, $_QET['limit'] ?? 6);
                break;
            case 'QueryList': //查询未绑定订单
                query::QueryList($_QET['msg']);
                break;
            case 'QueryMark': //提交商品评价内容
                query::QueryMark($_QET, $User);
                break;
            case 'QueryTake': //确认收货
                query::QueryTake($_QET['id'], $User);
                break;
            case 'QueryDelete': //删除待付款队列订单
                query::QueryDelete($_QET['id'], $User);
                break;
            case 'QueryDetails': //查询订单进度
                dier(Order::Query($_QET['id'], (!$User ? false : $User['id']), 1, $_QET['order']));
                break;
            case 'Queue': //查询队列内的订单，未完成+待付款
                query::QueueList($User);
                break;
            case 'OrderModification': //修改下单信息
                test(['oid', 'order', 'data']);
                if (!empty($_QET['state']) && (int)$_QET['state'] === 2) {
                    $_QET['data'] = explode(',', $_QET['data']);
                }
                Order::OrderModification($_QET['oid'], $_QET['order'], (!$User ? false : $User['id']), $_QET['data']);
                break;
        }
        break;
    case 'CartAdd': //加入购物车
        $User = login_data::user_data();
        if (!$User && $conf['ForcedLanding'] != 1) {
            dies(-3, '您必须先登陆用户中心才可以将商品加入购物车，点击确定登陆！');
        }
        if ((int)$conf['CartState'] !== 1) {
            dies(-1, '购物车功能已经关闭!');
        }
        test(['gid|e', 'num|e']);
        $Gid = (int)$_QET['gid'];
        $Num = (int)$_QET['num'];
        unset($_QET['gid'], $_QET['num'], $_QET['act']);
        foreach ($_QET as $value) {
            if (empty($value)) {
                dies(-1, '请填写完整！');
            }
        }
        GoodsCart::CartAdd([
            'gid' => $Gid,
            'num' => $Num,
            'data' => $_QET
        ]);
        break;
    case 'CartNum': //购物车下单份数修改
        GoodsCart::CartNum($_QET);
        break;
    case 'CartCount': //购物车商品数量
        dier([
            'code' => 1,
            'msg' => '购物车商品数量获取成功！',
            'count' => GoodsCart::CartCount(),
        ]);
        break;
    case 'CartList': //查询购物车列表
        if (isset($_QET['data'])) {
            $_QET['data'] = explode(',', $_QET['data']);
        }
        $User = login_data::user_data();
        dier(GoodsCart::CartList($_QET['data'], $_QET['CartKey'], $User));
        break;
    case 'CartDet': //删除购物车指定内容
        test(['arr|i']);
        if (GoodsCart::CartDet(explode(',', $_QET['arr']), 2) === true) {
            dies(1, '删除成功！');
        } else {
            dies(-1, '删除失败！');
        }
        break;
    case 'CartDetAll': //删除全部
        GoodsCart::DeleteAll();
        break;
    case 'CartPay': //结算购物车商品
        test(['type|e', 'mode|e']);
        $type = (int)$_QET['type'];
        $mode = $_QET['mode'];
        unset($_QET['type'], $_QET['act'], $_QET['mode'], $_QET['currency']);
        if (count($_QET) === 0) {
            dies(-1, '请将需要结算的订单填写完整哦');
        }
        GoodsCart::CartPay($_QET, $type, $mode, $_QET['CartKey']);
        break;
    case 'UserData': //获取用户数据
        $User = login_data::user_data();
        if (!$User) {
            if ($conf['ShutDownUserSystem'] != 1) dies(-2, $conf['ShutDownUserSystemCause']);
            dier([
                'code' => -3,
                'msg' => '当前未登录,请先完成登录',
                'data' => [
                    'logo' => ImageUrl($conf['logo']),
                    'title' => $conf['sitename'],
                    'phone' => $conf['sms_switch_user'], //手机号登录
                    'login' => $conf['AccountPasswordLogin'], //账号密码登录
                    'qqlogin' => href(2) . ROOT_DIR_S . '/user/ajax.php?act=QQ_QuickLogin',
                    'qqtype' => !($conf['QQInternetChoice'] != 1 && $conf['QQInternetChoice'] != 2),
                ]
            ]);
        } else {
            HomePage::UserData($User);
        }
        break;
    case 'UserAjax': //用户后台请求中转
        $_QET['act'] = $_QET['uac'];
        include './user/ajax.php';
        break;
    case 'ChangesCommodityPrices': //商品价格变动日志
        if ($conf['FluctuationsPrices'] != 1) {
            dies(-1, '价格波动功能未开启');
        }
        if (isset($_QET['gid']) && !empty($_QET['gid']) && $_QET['gid'] >= 1) {
            HomePage::ChangesCommodityPricesGoodsList($_QET['gid']);
        } else {
            HomePage::ChangesCommodityPricesList($_QET['name'] ?? false);
        }
        break;
    case 'TemData': //获取模板单独配置参数
        if (isset($_QET['name'])) {
            //获取指定
            $TemState = TemConf($_QET['name'], 2);
        } else {
            //获取当前
            $Name = $conf['template'];
            if (View::isMobile() === true) {
                $Name = $conf['template_m'];
            }
            $TemState = TemConf($Name, 2);
        }
        if (!$TemState) {
            dies(-1, '数据获取失败，请确认模板名称是否正确？');
        }
        dier([
            'code' => 1,
            'msg' => '数据获取成功',
            'data' => $TemState
        ]);
        break;
    case 'PasswordAccessVerify': //验证访问密码
        test(['pass|e', 'token|e'], '请提交完整参数！');
        View::HomePasswordAccess($_QET['pass'], $_QET['token']);
        break;

    case 'Ordersubmit': #兑换
        $User = login_data::user_data();
        if (!$User && $conf['ForcedLanding'] != 1) {
            dies(-3, '您必须先登陆才可兑换商品！');
        }
        test(['Token|e'], '请将参数填写完整！');
        $DB = SQL::DB();
        $Token = $DB->get('cash_card', ['gid', 'token'], [
            'token' => $_QET['Token'],
            'state' => 1
        ]);
        if (!$Token) {
            dies(-1, '卡密不存在，或已经被使用了！');
        }
        unset($_QET['Token'], $_QET['act']);

        $Data = ProductsExchange::Ordersubmit([
            'gid' => $Token['gid'],
            'num' => 1,
            'data' => $_QET,
        ], $Token['token']);
        dier($Data);
        break;
    case 'GoodsData': #获取商品信息
        test(['Token|e'], '请将参数填写完整！');
        $DB = SQL::DB();
        $Token = $DB->get('cash_card', ['gid'], [
            'token' => trim((string)$_QET['Token']),
            'state' => 1
        ]);
        if (!$Token) {
            dies(-1, '卡密不存在，或已经被使用了！');
        }
        $Data = HomePage::ProductDetails(['gid' => $Token['gid']]);
        dier($Data);
        break;
    case 'AppView': //打开视图文件！
        if (empty($_QET['path']) || empty($_QET['id'])) show_msg('警告', '提交参数不完整！', false, false, false);
        AppList::view($_QET['id'], $_QET['path']);
        break;
    default:
        header('HTTP/1.1 404 Not Found');
        die(json_encode(['code' => -2, 'msg' => '请求路径不存在！']));
}
