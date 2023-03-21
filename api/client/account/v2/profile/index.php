<?php
// +----------------------------------------------------------------------
// | Project: xc
// +----------------------------------------------------------------------
// | Creation: 2023/2/25
// +----------------------------------------------------------------------
// | Filename: index.php
// +----------------------------------------------------------------------
// | Explain: 账户信息：http://接口域名/api/client/account/v2/profile
// +----------------------------------------------------------------------

require_once __DIR__ . '/../../../../loader.php';
global $_QET;

use lib\Simulation as ZhiKe;

$_QET['TypeS'] = "/api/client/account/v2/profile"; //判断
$_QET['TypeUrl'] = "/api/client/account/v2/profile"; //请求路径,用于拼接URL
ZhiKe\Simulation::zhike($_QET);