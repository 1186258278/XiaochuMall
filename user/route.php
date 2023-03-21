<?php
/**
 * Author：晴玖天
 * Creation：2020/7/3 12:46
 * Filename：route.php
 * 路由导航，解决每个模板之间的路径差异问题！
 * 这只是一个简单的例子，开发新模板时可以对照开发，系统内置的访问方式已经注释好
 */

switch ($_GET['p']) {
    case 'Cart': //购物车 /user/route.php?p=Cart
        header("Location: ./#/user/cart");
        break;
    case 'Order': //订单列表 /user/route.php?p=Order
        header("Location: ./#/user/order");
        break;
    case 'Invite': //邀请有奖 /user/route.php?p=Invite
        header("Location: ./#/user/invite");
        break;
    case 'Ticket': //售后工单 /user/route.php?p=Ticket
        header("Location: ./#/user/ticket");
        break;
    case 'Mainframe': //我的主机 /user/route.php?p=Mainframe
        header("Location: ./#/user/HostManagement");
        break;
    case 'Comment': //我的点评 /user/route.php?p=Comment
        header("Location: ./#/user/evaluate");
        break;
    case 'StoreOrder': //店铺订单管理 /user/route.php?p=StoreOrder
        header("Location: ./#/user/storeOrder");
        break;
    case 'DomainName': //域名管理 /user/route.php?p=DomainName
        header("Location: ./#/user/domainNameManage");
        break;
    case 'Merchandise': //商品配置 /user/route.php?p=Merchandise
        header("Location: ./#/user/CommodityManagement");
        break;
    case 'Decor': //店铺装修 /user/route.php?p=Decor
        header("Location: ./#/user/ShopDecoration");
        break;
    case 'Detail': //资金明细 /user/route.php?p=Detail
        header("Location: ./#/user/journal");
        break;
    case 'InformSet': //个人设置 /user/route.php?p=InformSet
        header("Location: ./#/user/personalSettings");
        break;
    case 'Notice': //通知列表 /user/route.php?p=Notice
        header("Location: ./#/user/notificationList");
        break;
    case 'Sign': //每日签到 /user/route.php?p=Sign
        header("Location: ./#/user/sign");
        break;
    case 'Charge': //在线充值 /user/route.php?p=Charge
        header("Location: ./#/user/pay");
        break;
    case 'Shop': //我的小店 /user/route.php?p=Shop
        header("Location: ./#/user/shop");
        break;
    case 'Member': //会员中心 /user/route.php?p=Member
        header("Location: ./#/user/grade");
        break;
    case 'StoreSetup': //站点配置 /user/route.php?p=StoreSetup
        header("Location: ./#/user/siteConfiguration");
        break;
    case 'Cash': //余额提现 /user/route.php?p=Cash
        header("Location: ./#/user/withdrawalBalance");
        break;
    case 'Agent': //我的下级管理 /user/route.php?p=Agent
        header("Location: ./#/user/userList");
        break;
    case 'AppManage': //App管理 /user/route.php?p=AppManage
        header("Location: ./#/user/App");
        break;
    case 'Coupon': //优惠券管理 /user/route.php?p=Coupon
        header("Location: ./#/user/coupon");
        break;
    case 'Resist': //账号注册 /user/route.php?p=Resist
        header("Location: ./#/register");
        break;
    case 'PhoneLogin': //手机号登陆 /user/route.php?p=PhoneLogin
        header("Location: ./#/phoneLogin");
        break;
    case 'Login': //后台登录 /user/route.php?p=Login
        header("Location: ./#/login");
        break;
    default:
        header("Location: ./");
        break;
}