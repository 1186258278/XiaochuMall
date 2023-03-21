//站长后台登陆js
var AdminLogin = {
    GetNode() {
        $.ajax({
            type: 'POST',
            url: 'main.php?act=ApiList',
            data: {
                type: 2,
            },
            dataType: "json",
            success: function (data) {
                let content = '';
                for (const dataKey in data.data) {
                    if (dataKey == (data.at - 1)) {
                        content += '<option selected value="' + dataKey + '" id="SelectS_' + dataKey + '">' + data.data[dataKey].name + ' - ' + (data.data[dataKey].ping) + '</option>';
                    } else {
                        content += '<option value="' + dataKey + '">' + data.data[dataKey].name + ' - ' + (data.data[dataKey].ping) + '</option>';
                    }
                }
                $(".SelectS").html(content);
                $("#SelectS").show();
            }, error: function () {
                $("#SelectS").hide();
            }
        })
    },
    GetNodeSet(id) {
        $.ajax({
            type: 'POST',
            url: 'main.php?act=ApiSet',
            data: {
                id: id,
            },
            dataType: "json",
            success: function (data) {
                if (data.code >= 1) {
                    layer.msg(data.msg, {
                        icon: 1,
                    });
                } else {
                    layer.alert(data.msg, {
                        icon: 2,
                    });
                }
                AdminLogin.GetNode();
            }, error: function () {
                $("#SelectS").hide();
            }
        })
    },
    do_user_login: function () {
        var user = $("input[name='user']").val();
        var pass = $("input[name='pass']").val();
        var vercode = $("input[name='vercode']").val();
        if (user == '' || pass == '' || vercode == '') {
            layer.alert('请将账号密码填写完整！', {
                icon: 2,
            });
            $("#vc").attr('src', 'ajax.php?act=VerificationCode&n=Login_uvc');
            $("input[name='vercode']").val('');
            return;
        }
        layer.msg('正在登陆中,请稍后...', {icon: 16, time: 9999999});
        $.ajax({
            type: "post", url: "ajax.php?act=login_account", data: {
                user: user, pass: pass, vercode: vercode,
            }, dataType: "json", success: function (data) {
                layer.closeAll();
                if (data.code == 1) {
                    layer.msg(data.msg, {icon: 1});
                    layer.alert(data.msg, {
                        icon: 1, yes: function () {
                            location.href = './index.php';
                        }
                    })
                } else {
                    layer.alert(data.msg, {icon: 2});
                    $("#vc").attr('src', 'ajax.php?act=VerificationCode&n=Login_uvc');
                    $("input[name='vercode']").val('');
                }
            }, error: function () {
                layer.alert('登陆请求发送失败！');
            }
        });
    }, do_login_scan: function () {
        layer.msg('正在获取二维码数据', {icon: 16, time: 9999999});
        $.ajax({
            type: "POST", url: "ajax.php?act=login_scan", dataType: "json", success: function (data) {
                layer.closeAll();
                if (data.code > 0) {
                    layer.open({
                        title: '请打开小程序"晴天窝"完成扫码',
                        content: '<img class="AdminImageLogin" src="' + data.image + '" />',
                        btn: false,
                        area: ['300px', '340px'],
                        offset: '150px',
                        end: function () {
                            location.reload();
                        }
                    });
                    AdminLogin.do_login_wx_monitoring();
                } else {
                    layer.alert(data.msg, {icon: 2});
                }
            }, error: function () {
                layer.closeAll();
                layer.alert('服务器异常！');
            },
        });
    }, do_login_app: function () {
        layer.open({
            title: '是否要使用服务端APP登陆？',
            content: '点击确认后请前往服务端APP验证界面完成登陆验证！',
            btn: ['确认', '取消'],
            icon: 3,
            anim: 6,
            btn1: function () {
                layer.msg('正在发送服务端APP登陆验证码', {icon: 16, time: 9999999});
                $.ajax({
                    type: "POST", url: "ajax.php?act=login_app", dataType: "json", success: function (data) {
                        if (data.code > 0) {
                            $(".Token").attr('data-token', data.token);
                            AdminLogin.do_login_wx_monitoring(data.token);
                            layer.alert(data.msg, {icon: 1});
                        } else {
                            layer.alert(data.msg, {icon: 2});
                        }

                    }, error: function () {
                        layer.alert('服务器异常！');
                    },
                });
            },
        });
    }, do_login_wx: function () {
        layer.open({
            title: '是否要使用微信登陆？',
            content: '点击确定后会在微信端发送登陆验证码,点击确认后即可完成登陆！',
            btn: ['确认发送', '取消'],
            icon: 3,
            anim: 6,
            btn1: function () {
                layer.msg('正在发送微信登陆验证码', {icon: 16, time: 9999999});
                $.ajax({
                    type: "POST", url: "ajax.php?act=login", dataType: "json", success: function (data) {
                        if (data.code > 0) {
                            $(".Token").attr('data-token', data.token);
                            AdminLogin.do_login_wx_monitoring(data.token);
                            layer.alert(data.msg, {icon: 1});
                        } else {
                            layer.alert(data.msg, {icon: 2});
                        }

                    }, error: function () {
                        layer.alert('服务器异常！');
                    },
                });
            },
        });
    }, do_login_wx_monitoring: function (token = null) {
        $.ajax({
            type: "POST", url: "ajax.php?act=login_log", dataType: "json", success: function (data) {
                AdminLogin.monitoring(data);
                if (data.code == '-2') {
                    layer.alert(data.msg, {
                        icon: 2, end: function () {
                            location.reload();
                        },
                    });
                } else if (data.code == '-1') {
                    setTimeout(function () {
                        AdminLogin.do_login_wx_monitoring(token);
                    }, 1500);
                }

            }, error: function () {
                layer.alert('服务器异常！');
            },
        });
    }, monitoring: function (data) {
        $("#form").hide(100);
        $("#Weix").show(100);
        $(".WeixText").html(data.msg);
        if (data.code == 1) {
            setTimeout(function () {
                location.reload();
            }, 1500);
        }

    }, do_admin_login_token: function () {
        var token = $("input[name='token']").val();
        if (token == '') {
            layer.msg('请填写完整！', {
                icon: 2, title: '温馨提示',
            });
            return false;
        }

        layer.open({
            title: '确认手动输入验证码登陆？',
            content: '点击确认后将验证你填写的token，验证成功后将登陆后台！',
            btn: ['确认验证', '取消'],
            icon: 3,
            anim: 6,
            btn1: function () {
                layer.msg('正在验证,请稍后', {icon: 16, time: 9999999});
                $.ajax({
                    type: "POST",
                    url: "ajax.php?act=login_token",
                    data: {token: token},
                    dataType: "json",
                    success: function (data) {
                        if (data.code > 0) {
                            AdminLogin.monitoring(data);
                            layer.alert(data.msg, {icon: 1});
                        } else {
                            layer.alert(data.msg, {icon: 2});
                        }
                    },
                    error: function () {
                        layer.alert('服务器异常！');
                    },
                });
            },
        });
    }, do_sms_verification() {
        /**
         * 登陆短信验证，验证通过后可发送验证码！
         */
        layer.open({
            type: 1,
            title: '请先完成验证',
            btn: ['验证', '取消'],
            id: 'smsvis',
            content: '<div class="layui-row" style="padding: 1em"><div><input type="text" name="vercode" style="width: 100%;" placeholder="输入验证码"></div><div><div style="width: 100%;margin-top: 6px;"><img id="vc" src="ajax.php?act=VerificationCode&n=AdminLogin_sms_vis" onclick="this.src=\'ajax.php?act=VerificationCode&n=AdminLogin_sms_vis\';"></div></div></div>',
            btn1: function () {
                let code = $("#smsvis input[name='vercode']").val();
                layer.msg('正在验证中,请稍后！', {icon: 16, time: 9999999});
                AdminLogin.do_admin_get_mobile_code(code, 2);
            },
            tipsMore: true,
            zIndex: layer.zIndex
        })
    }, do_admin_get_mobile_code(type = 2, state = 1) {
        var phone = $("input[name='phone']").val();
        if (phone == '') {
            layer.alert('请先填写手机号', {icon: 2});
            return false;
        }

        if (type == 2 && state === 1) {
            AdminLogin.do_sms_verification();
            return false;
        }

        layer.msg('正在发送,请稍后', {icon: 16, time: 9999999});
        $.ajax({
            type: "POST",
            url: 'ajax.php?act=Send_verification_code_login',
            data: {mobile: phone, code: type},
            dataType: "json",
            success: function (data) {
                layer.closeAll();
                if (data.code > 0) {
                    AdminLogin.do_admin_codes(60);
                    layer.alert(data.msg, {icon: 1});
                } else if (data.code == -2) {
                    layer.msg(data.msg, {icon: 2});
                    $("#smsvis #vc").attr('src', 'ajax.php?act=VerificationCode&n=AdminLogin_sms_vis');
                    $("#smsvis input[name='vercode']").val('');
                } else {
                    layer.msg(data.msg, {icon: 2});
                }
            },
            error: function () {
                layer.alert('服务器异常！');
            },
        });
    }, do_admin_codes(m) {
        $("#PhoneCode").attr('class', 'layui-btn layui-btn-fluid LgoinBtnDe');
        $("#PhoneCode").html('<span id="codes">' + m + '</span>秒后重发');
        $("#PhoneCode").attr('href', 'javascript:layer.msg(\'请填写短信！' + m + '秒后可重新发送！\')');
        var ms = $("#codes").text() - 0;
        if (ms <= 0) {
            $("#PhoneCode").attr('class', 'layui-btn layui-btn-fluid LgoinBtn');
            $("#PhoneCode").html('获取验证码');
            $("#PhoneCode").attr('href', 'javascript:AdminLogin.do_admin_get_mobile_code()');
        } else {
            setTimeout(function () {
                AdminLogin.do_admin_codes(ms - 1);
            }, 1000);
        }
    }, do_admin_login_phone_verify() {
        var code = $("input[name='vercode']").val();
        layer.open({
            title: '是否确认登陆？',
            content: '若确认验证码填写正确，可点击确定登陆后台！',
            btn: ['确认', '取消'],
            icon: 3,
            anim: 6,
            btn1: function () {
                layer.msg('正在验证,请稍后', {icon: 16, time: 9999999});
                $.ajax({
                    type: "POST",
                    url: 'ajax.php?act=Send_verification_login',
                    data: {code: code},
                    dataType: "json",
                    success: function (data) {
                        if (data.code > 0) {
                            layer.alert(data.msg, {
                                icon: 1, title: '恭喜', end: function (lyaero, index) {
                                    location.reload();
                                }
                            });
                        } else {
                            layer.alert(data.msg, {icon: 2});
                        }
                    },
                    error: function () {
                        layer.alert('服务器异常！');
                    },
                });
            },
        });
    },
};

AdminLogin.GetNode();

$(".SelectS").on("change", function () {
    AdminLogin.GetNodeSet($(".SelectS").val());
});