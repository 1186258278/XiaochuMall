<?php
// +----------------------------------------------------------------------
// | Project: 商城
// +----------------------------------------------------------------------
// | Creation: 2022/2/25
// +----------------------------------------------------------------------
// | Filename: cli.php
// +----------------------------------------------------------------------
// | Explain: cli功能入口模块：php cli.php [命令行操作!]
// +----------------------------------------------------------------------


use extend\Autoload;
use lib\CLI\CLI;

error_reporting(0);
ini_set("memory_limit", "-1");
if (!session_id()) session_start();
const IN_CRONLITE = true;
const SYSTEM_ROOT = __DIR__ . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR;
const ROOT = __DIR__ . DIRECTORY_SEPARATOR;
$ROOT_DIR = dirname(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR, 2) . DIRECTORY_SEPARATOR;
$ROOT_DIR = str_replace($_SERVER["DOCUMENT_ROOT"] ?? substr_replace(ROOT, "", -1), '', $ROOT_DIR);
if (empty($ROOT_DIR) || count(explode(DIRECTORY_SEPARATOR, $ROOT_DIR)) >= 3) {
    $ROOT_DIR = DIRECTORY_SEPARATOR;
}
$ROOT_DIR = str_replace(DIRECTORY_SEPARATOR, "/", $ROOT_DIR);
define("ROOT_DIR", $ROOT_DIR);
define('ROOT_DIR_S', substr_replace(ROOT_DIR, "", -1));
date_default_timezone_set('Asia/Shanghai');
$date = date("Y-m-d H:i:s");

if (PHP_VERSION_ID < 70000 || PHP_VERSION_ID >= 80000) {
    die('为了更好的使用程序,当前PHP版本最低设置为7.0，最高为7.4，请调整PHP版本，当前PHP版本：' . PHP_VERSION);
}
if (!class_exists('PDO')) {
    die('由于系统引入了Medoo框架,您的空间或服务器必须支持PDO扩展！,请先安装！');
}
if (!file_exists("./install/install.lock")) {
    die('请先安装程序！');
}

include_once SYSTEM_ROOT . "fun.core.php";
include_once SYSTEM_ROOT . "fun.ajax.php";
include_once SYSTEM_ROOT . "lib/rule/rule.php";
include_once SYSTEM_ROOT . "fun.test.php";
include_once SYSTEM_ROOT . "db.class.php";
include "./includes/extend/Autoload.php";
Autoload::AutoloadRegister();
include './vendor/autoload.php';
include_once SYSTEM_ROOT . "deploy.php";
global $accredit, $dbconfig;

CLI::index();