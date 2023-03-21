<?php
// +----------------------------------------------------------------------
// | Project: xc
// +----------------------------------------------------------------------
// | Creation: 2023/2/27
// +----------------------------------------------------------------------
// | Filename: index.php
// +----------------------------------------------------------------------
// | Explain: 中转：http://域名/api/index.php?zhike=/api/client/account/v2/profile[路径]
// +----------------------------------------------------------------------
require_once "loader.php";
global $_QET;

if (!empty($_QET['zhike'])) {
    $Ex = explode('/', $_QET['zhike']);
    if (empty($Ex[0])) {
        unset($Ex[0]);
        $_QET['zhike'] = implode('/', $Ex);
    }
    $Ex2 = explode('?', $_QET['zhike']);
    if (!empty($Ex2[0]) && !empty($Ex2[1])) {
        $Path = $Ex2[0];
        $Ex3 = explode('=', $Ex2[1]);
        $_QET[$Ex3[0]] = $Ex3[1];
        $_GET[$Ex3[0]] = $Ex3[1];
        $_REQUEST[$Ex3[0]] = $Ex3[1];
    } else {
        $Path = $_QET['zhike'];
    }
}
if (empty($_QET['zhike']) || !is_file(ROOT . $Path . '/index.php')) {
    dies(403, '请求错误，路径不存在！');
}
unset($_QET['zhike'], $_GET['zhike'], $_POST['zhike'], $_REQUEST['zhike']);
require ROOT . $Path . '/index.php';
