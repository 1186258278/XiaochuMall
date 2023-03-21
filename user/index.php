<?php
include '../includes/fun.global.php';
global $conf;
if (View::isMobile()) {
    $path = "template/{$conf['UserBackgroundTemplateM']}/index.php";
} else {
    $path = "template/{$conf['UserBackgroundTemplatePC']}/index.php";
}
if (!is_file($path)) {
    if (View::isMobile()) {
        $path = "template/DefaultM/index.php";
    } else {
        $path = "template/Default/index.php";
    }
}
include $path;