<?php
// +----------------------------------------------------------------------
// | Project: xc
// +----------------------------------------------------------------------
// | Creation: 2023/2/25
// +----------------------------------------------------------------------
// | Filename: index.php
// +----------------------------------------------------------------------
// | Explain: 订单详情或下单：http://接口域名/api/client/goods/v2/order
// +----------------------------------------------------------------------

require_once __DIR__ . '/../../../../loader.php';

global $_QET;

use lib\Simulation as ZhiKe;

if (!empty($_QET['orderSN'])) {
    $_QET['TypeS'] = "/api/client/goods/v2/order"; //查单
} else {
    $_QET['TypeS'] = "/api/client/goods/v2/order_buy"; //下单
}
$_QET['TypeUrl'] = "/api/client/goods/v2/order"; //请求路径,用于拼接URL
ZhiKe\Simulation::zhike($_QET);