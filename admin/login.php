<?php

use lib\AppStore\AppList;

include '../includes/fun.global.php';
DirectoryProtection();
if (!empty($_SESSION['ADMIN_TOKEN'])) header("Location:./index.php");
$image = background::image();
AppList::PreloadedApp()
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>登录主站后台</title>
    <link rel="icon" href="../assets/favicon.ico"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="../assets/css/vendor/bootstrap.css" id="theme-stylesheet">
    <link rel="stylesheet" href="../assets/css/admin.css" id="theme-stylesheet">
</head>
<body>
<style>
    .login-page::before {
    <?= ($image == false ? 'background-image: linear-gradient(to right, #e0eafc, #cfdef3);' : $image) ?>
    }
</style>
<div class="page login-page">
    <div class="container d-flex align-items-center">
        <div class="form-holder has-shadow">
            <div class="row">
                <div class="col-lg-6">
                    <div class="info d-flex align-items-center">
                        <div class="content">
                            <div class="logo">
                                <h1>欢迎登录</h1>
                            </div>
                            <p><?= $conf['sitename'] ?>管理系统</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 bg-white">
                    <div class="form d-flex align-items-center">
                        <div class="content">
                            <div id="form" class="form-validate layui-form">
                                <?php if (!isset($_QET['phone'])) { ?>
                                    <div class="form-group">
                                        <input type="text" name="user" required
                                               placeholder="账号" class="input-material"/>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" name="pass" required
                                               placeholder="密码" class="input-material"/>
                                    </div>
                                    <div class="form-group">
                                        <div style="width: 67%;display:inline-block">
                                            <input type="text" name="vercode" required
                                                   placeholder="验证码" class="input-material"/>
                                        </div>
                                        <div style="width: 31%;display:inline-block">
                                            <img id="vc" src="ajax.php?act=VerificationCode&n=Login_uvc"
                                                 style="width: 100%;height: 42px;cursor: pointer"
                                                 title="点击刷新二维码"
                                                 onclick="this.src='ajax.php?act=VerificationCode&n=Login_uvc';"
                                                 class="layadmin-user-login-codeimg">
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary"
                                            onclick="AdminLogin.do_user_login()">账号密码登录
                                    </button>
                                <?php } else { ?>
                                    <div class="form-group">
                                        <input type="text" name="phone" required
                                               placeholder="手机号" class="input-material"/>
                                    </div>
                                    <div class="form-group">
                                        <div style="width: 67%;display:inline-block">
                                            <input type="text" name="vercode" required
                                                   placeholder="短信验证码" class="input-material"/>
                                        </div>
                                        <div style="width: 31%;display:inline-block">
                                            <button style="width: 100%;"
                                                    class="btn btn-warning"
                                                    onclick="AdminLogin.do_admin_get_mobile_code()">获取
                                            </button>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary"
                                            onclick="AdminLogin.do_admin_login_phone_verify()">手机号登录
                                    </button>
                                <?php } ?>
                            </div>
                            <div id="Weix" style="display:none ">
                                <div class="form-validate layui-form">
                                    <div class="form-group">
                                        <input type="text" name="token" required
                                               placeholder="请输入登录Token" class="input-material"/>
                                    </div>
                                    <button type="submit" class="btn btn-primary"
                                            onclick="AdminLogin.do_admin_login_token()">开始验证登录Token
                                    </button>
                                </div>
                                <div style="width: 100%;margin-top: 2em;color: #00A7AA">
                                    请尽快输入验证码哦
                                </div>
                            </div>
                            <br/>
                            <div class="row">
                                <div class="col-6">
                                    <small>
                                        没有授权?</small><a
                                            href="https://cdn.79tian.com/api/wxapi/view/login.php?condition=4"
                                            target="_blank"
                                            class="signup">&nbsp;注册</a>
                                </div>
                                <div class="col-6" id="SelectS" style="text-align: right;display: none">
                                    <select class="SelectS"></select>
                                </div>
                            </div>

                            <div class="content" style="text-align: center;margin: 3em auto 0;">
                                <?php if (isset($_QET['phone'])) { ?>
                                    <div class="LoginStype">
                                        <a href="?"> <img style="width: 56px;margin-bottom: -1px;"
                                                          src="../assets/img/login.png"/></a>
                                    </div>
                                <?php } else { ?>
                                    <div class="LoginStype">
                                        <a href="?phone"><img title="手机号登录" style="margin-bottom: 2px;"
                                                              src="../assets/img/phone.png"/></a>
                                    </div>
                                <?php } ?>
                                <div class="LoginStype">
                                    <img title="APP验证登陆"
                                         onclick="AdminLogin.do_login_app()"
                                         style="background-color: #009CF8;width: 48px;margin-bottom: 4px;margin-left: 10px;"
                                         src="../assets/img/app.png"/>
                                </div>
                                <div class="LoginStype">
                                    <img style="width: 55px;margin-bottom: 0;" onclick="AdminLogin.do_login_scan()"
                                         title="APP扫码登陆"
                                         src="../assets/img/Sweep.png"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="../assets/layui/layui.all.js"></script>
<script src="../assets/js/jquery-3.4.1.min.js"></script>
<script src="../assets/admin/js/login.js?vs=<?= $accredit['versions'] ?>"></script>
</body>
</html>
