<?php
if (PHP_VERSION_ID < 70000 || PHP_VERSION_ID >= 80000) {
    die('为了更好的使用程序,当前PHP版本最低设置为7.0，最高为7.4，请调整PHP版本，当前PHP版本：' . PHP_VERSION);
}
include './includes/fun.global.php';
global $_QET;
View::Home($_QET['mod'] ?? 0);