<?php
// +----------------------------------------------------------------------
// | Project: xc
// +----------------------------------------------------------------------
// | Creation: 2023/2/25
// +----------------------------------------------------------------------
// | Filename: index.php
// +----------------------------------------------------------------------
// | Explain: 商品分类：http://接口域名/api/client/goods/v2/category
// +----------------------------------------------------------------------

require_once __DIR__ . '/../../../../loader.php';

global $_QET;

use lib\Simulation as ZhiKe;

$_QET['TypeS'] = "/api/client/goods/v2/category"; //判断
$_QET['TypeUrl'] = "/api/client/goods/v2/category"; //请求路径,用于拼接URL
ZhiKe\Simulation::zhike($_QET);