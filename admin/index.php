<?php

/**
 * 后台首页
 */
$title = '数据中心';
$tise = 'index';
include 'header.php';
global $cdnserver, $accredit;
$opcacheType = function_exists("opcache_get_configuration");
if ($opcacheType) {
    $opcacheState = opcache_get_status();
    if (isset($opcacheState['scripts'])) {
        $opcacheContent = '<div style="overflow-y: auto;width: 100%;"><table class="layui-table" lay-size="sm" lay-even lay-skin="nob" style="white-space:nowrap;"><thead><tr><th>文件名</th><th>调用量</th><th>占用内存</th><th>缓存时间</th></tr></thead><tbody>';
        $i = 0;
        foreach ($opcacheState['scripts'] as $value) {
            if ($i > 20) {
                continue;
            }
            $fileName = explode(DIRECTORY_SEPARATOR, $value['full_path']);
            $fileName = $fileName[count($fileName) - 1] ?? $value['full_path'];
            $opcacheContent .= "<tr><td title='{$value['full_path']}' style='cursor:pointer;'>{$fileName}</td><td>" . ($value['hits'] ?? '无') . "</td><td>" . MemoryComputing($value['memory_consumption'] ?? 0) . "</td><td>" . ($value['last_used'] ?? '获取失败') . "</td></tr>";
            unset($fileName);
            ++$i;
        }
        $opcacheContent .= '</tbody></table></div>';
    } else {
        $opcacheContent = '状态获取失败,可能当前没有缓存内容,或开启失败!';
    }
} else {
    $opcacheContent = '请先开启opcache扩展!';
}
?>
<div id="App">
    <div class="row" v-show="Data!==-1">
        <div class="col-xl-4">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xl-6" style="width: 50%;">
                    <div class="card widget-flat" onclick="layer.msg('整站订单总数')">
                        <div class="card-body">
                            <div class="float-right" style="width: 30px;height: 30px">
                                <i class="layui-icon layui-icon-cart-simple widget-icon"
                                   style="background-color: #7C4DFF;color: white;"></i>
                            </div>
                            <h5 class="text-muted font-weight-normal mt-0">
                                <a href="admin.order.list.php" target="_blank">订单总数</a></h5>
                            <h5 class="mt-3 mb-0" style="font-weight: 300">{{ Data.OrderSum }}条</h5>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xl-6" style="width: 50%">
                    <div class="card widget-flat" onclick="layer.msg('整站今日新增订单数量,包括已经退单的订单')">
                        <div class="card-body">
                            <div class="float-right" style="width: 30px;height: 30px">
                                <i class="layui-icon layui-icon-cart-simple widget-icon"
                                   style="background-color: #43A047;color: white"></i>
                            </div>
                            <h5 class="text-muted font-weight-normal mt-0">
                                <a href="admin.order.list.php" target="_blank">今日订单</a></h5>
                            <h5 class="mt-3 mb-0" style="font-weight: 300">{{ Data.NewOrders }}条</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xl-6" style="width: 50%">
                    <div class="card widget-flat" onclick="layer.msg('整站未完成订单总数，除了已完成/已收货/已退款，其他均是未完成！')">
                        <div class="card-body">
                            <div class="float-right" style="width: 30px;height: 30px">
                                <i class="layui-icon layui-icon-cart-simple widget-icon"
                                   style="background-color: #FF9800;color: white"></i>
                            </div>
                            <h5 class="text-muted font-weight-normal mt-0">
                                <a href="admin.order.list.php" target="_blank">未完成数</a></h5>
                            <h5 class="mt-3 mb-0" style="font-weight: 300">{{ Data.FailOrders }}条</h5>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xl-6" style="width: 50%">
                    <div class="card widget-flat"
                         onclick="layer.msg('整站总订单交易流水，不包括用户充值，仅计算订单内除免费领取，积分兑换外的全部订单交易金额总和，此处未计算订单队列内的订单！')">
                        <div class="card-body">
                            <div class="float-right" style="width: 30px;height: 30px">
                                <i class="layui-icon layui-icon-rmb widget-icon"
                                   style="background-color: #FF7043;color: white"></i>
                            </div>
                            <h5 class="text-muted font-weight-normal mt-0">
                                总交易额</h5>
                            <h5 class="mt-3 mb-0" style="font-weight: 300">{{ Data.TurnoverSum }}元</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xl-6" style="width: 50%">
                    <div class="card widget-flat" onclick="layer.msg('今日订单交易流水，不包括用户充值，仅计算订单内除免费领取，积分兑换外的全部订单交易金额总和！')">
                        <div class="card-body">
                            <div class="float-right" style="width: 30px;height: 30px">
                                <i class="layui-icon layui-icon-rmb widget-icon"
                                   style="background-color: #1DE9B6;color: white"></i>
                            </div>
                            <h5 class="text-muted font-weight-normal mt-0">
                                今日交易额
                            </h5>
                            <h5 class="mt-3 mb-0" style="font-weight: 300">{{ Data.DayTurnover }}元</h5>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-xl-6" style="width: 50%">
                    <div class="card widget-flat"
                         onclick="layer.msg('今日订单成本，不包括用户充值，仅计算订单内除免费领取，积分兑换外的全部订单成本总和，且不包含已退款订单！')">
                        <div class="card-body">
                            <div class="float-right" style="width: 30px;height: 30px">
                                <i class="layui-icon layui-icon-rmb widget-icon"
                                   style="background-color: #40C4FF;color: white"></i>
                            </div>
                            <h5 class="text-muted font-weight-normal mt-0">
                                今日成本
                            </h5>
                            <h5 class="mt-3 mb-0" style="font-weight: 300">{{ Data.DayCost }}元</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xl-6" style="width: 50%">
                    <div class="card widget-flat" onclick="layer.msg('平台用户总数，包含任意等级用户！')">
                        <div class="card-body">
                            <div class="float-right" style="width: 30px;height: 30px">
                                <i class="layui-icon layui-icon-user widget-icon"
                                   style="background-color: #C6FF00;color: white"></i>
                            </div>
                            <h5 class="text-muted font-weight-normal mt-0">
                                <a href="admin.user.list.php" target="_blank">用户总数</a></h5>
                            <h5 class="mt-3 mb-0" style="font-weight: 300">{{ Data.UserSum }}人</h5>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xl-6" style="width: 50%">
                    <div class="card widget-flat" onclick="layer.msg('今日新注册的用户总数，包含任意等级用户！')">
                        <div class="card-body">
                            <div class="float-right" style="width: 30px;height: 30px">
                                <i class="layui-icon layui-icon-add-circle-fine widget-icon"
                                   style="background-color: #00C853;color: white"></i>
                            </div>
                            <h5 class="text-muted font-weight-normal mt-0">
                                <a href="admin.user.list.php" target="_blank">今日新增</a></h5>
                            <h5 class="mt-3 mb-0" style="font-weight: 300">{{ Data.NewUser }}人</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xl-6" style="width: 50%">
                    <div class="card widget-flat" onclick="layer.msg('今日签到用户总数')">
                        <div class="card-body">
                            <div class="float-right" style="width: 30px;height: 30px">
                                <i class="layui-icon layui-icon-date widget-icon"
                                   style="background-color: #69F0AE;color: white"></i>
                            </div>
                            <h5 class="text-muted font-weight-normal mt-0">
                                今日签到</h5>
                            <h5 class="mt-3 mb-0" style="font-weight: 300">{{ Data.DaySign }}人</h5>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xl-6" style="width: 50%">
                    <div class="card widget-flat">
                        <div class="card-body">
                            <div class="float-right" style="width: 30px;height: 30px">
                                <i class="layui-icon layui-icon-add-circle-fine widget-icon"
                                   style="background-color: #FFC107;color: white"></i>
                            </div>
                            <h5 class="text-muted font-weight-normal mt-0">
                                <a href="admin.discuss.list.php" target="_blank">待审评论</a></h5>
                            <h5 class="mt-3 mb-0" style="font-weight: 300">{{ Data.CheckPending }}条</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xl-6" style="width: 50%">
                    <div class="card widget-flat">
                        <div class="card-body">
                            <div class="float-right" style="width: 30px;height: 30px">
                                <i class="layui-icon layui-icon-rmb widget-icon"
                                   style="background-color: #9575CD;color: white"></i>
                            </div>
                            <h5 class="text-muted font-weight-normal mt-0">
                                <a href="admin.user.pay.php" target="_blank">待审提现</a></h5>
                            <h5 class="mt-3 mb-0" style="font-weight: 300">{{ Data.CheckWithdraw }}笔</h5>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-xl-6" style="width: 50%">
                    <div class="card widget-flat">
                        <div class="card-body">
                            <div class="float-right" style="width: 30px;height: 30px">
                                <i class="layui-icon layui-icon-chart widget-icon"
                                   style="background-color: #304FFE;color: white"></i>
                            </div>
                            <h5 class="text-muted font-weight-normal mt-0"><a href="tickets.php"
                                                                              target="_blank">待办工单</a></h5>
                            <h5 class="mt-3 mb-0" style="font-weight: 300">{{ Data.CheckWorkOrder }}条</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xl-6" style="width: 50%">
                    <div class="card widget-flat">
                        <div class="card-body" onclick="layer.msg('平台商品总数，包含任意状态商品！')">
                            <div class="float-right" style="width: 30px;height: 30px">
                                <i class="layui-icon layui-icon-gift widget-icon"
                                   style="background-color: #deac46;color: white"></i>
                            </div>
                            <h5 class="text-muted font-weight-normal mt-0">
                                <a href="admin.goods.list.php" target="_blank">商品总数</a></h5>
                            <h5 class="mt-3 mb-0" style="font-weight: 300">{{ Data.GoodsSum }}个</h5>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-xl-6" style="width: 50%">
                    <div class="card widget-flat">
                        <div class="card-body" onclick="layer.msg('今日用户充值金额总数！')">
                            <div class="float-right" style="width: 30px;height: 30px">
                                <i class="layui-icon layui-icon-rmb widget-icon"
                                   style="background-color: #fe4f30;color: white"></i>
                            </div>
                            <h5 class="text-muted font-weight-normal mt-0"><a href="admin.order.pay.php"
                                                                              target="_blank">今日充值</a></h5>
                            <h5 class="mt-3 mb-0" style="font-weight: 300">{{ Data.DayPay }}元</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xl-6" style="width: 50%">
                    <div class="card widget-flat">
                        <div class="card-body" onclick="layer.msg('今日用户消耗积分总数！')">
                            <div class="float-right" style="width: 30px;height: 30px">
                                <i class="layui-icon layui-icon-rmb widget-icon"
                                   style="background-color: #75cdaa;color: white"></i>
                            </div>
                            <h5 class="text-muted font-weight-normal mt-0">
                                <a href="admin.user.log.php" target="_blank">今日积分</a></h5>
                            <h5 class="mt-3 mb-0" style="font-weight: 300">消耗{{ Data.DayConsumption }}个</h5>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-xl-6" style="width: 50%">
                    <div class="card widget-flat">
                        <div class="card-body" onclick="layer.msg('平台成功提现的金额总数！')">
                            <div class="float-right" style="width: 30px;height: 30px">
                                <i class="layui-icon layui-icon-chart widget-icon"
                                   style="background-color: #e9fe30;color: white"></i>
                            </div>
                            <h5 class="text-muted font-weight-normal mt-0"><a href="admin.user.pay.php" target="_blank">成功提现</a>
                            </h5>
                            <h5 class="mt-3 mb-0" style="font-weight: 300">{{ Data.SuccessfulWithdrawal }}元</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xl-6" style="width: 50%">
                    <div class="card widget-flat">
                        <div class="card-body" onclick="layer.msg('超过默认注册等级的用户总数')">
                            <div class="float-right" style="width: 30px;height: 30px">
                                <i class="layui-icon layui-icon-group widget-icon"
                                   style="background-color: #75bbcd;color: white"></i>
                            </div>
                            <h5 class="text-muted font-weight-normal mt-0">
                                <a href="admin.user.list.php" target="_blank">代理总数</a></h5>
                            <h5 class="mt-3 mb-0" style="font-weight: 300">{{ Data.AgentsSum }}人</h5>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-xl-6" style="width: 50%">
                    <div class="card widget-flat">
                        <div class="card-body" onclick="layer.msg('今日被邀用户总数，被重复邀请的用户也被计算入内')">
                            <div class="float-right" style="width: 30px;height: 30px">
                                <i class="layui-icon layui-icon-chart widget-icon"
                                   style="background-color: #fe30e3;color: white"></i>
                            </div>
                            <h5 class="text-muted font-weight-normal mt-0"><a href="admin.user.log.php" target="_blank">今日邀请</a>
                            </h5>
                            <h5 class="mt-3 mb-0" style="font-weight: 300">{{ Data.DayInvite }}人</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body mdui-text-center">
                    <a href="admin.goods.supply.php" target="_blank" class="mdui-btn">批量上货</a>
                    <a href="admin.source.add.php" target="_blank" class="mdui-btn">添加货源</a>
                    <a href="admin.class.add.php" target="_blank" class="mdui-btn">添加分类</a>
                    <a href="admin.goods.add.php" target="_blank" class="mdui-btn">添加商品</a>
                    <a href="admin.goods.list.php" target="_blank" class="mdui-btn">商品列表</a>
                    <a href="admin.order.list.php" target="_blank" class="mdui-btn">订单列表</a>
                    <a href="admin.user.list.php" target="_blank" class="mdui-btn">用户列表</a>
                    <a href="admin.Payments.php" target="_blank" class="mdui-btn">支付配置</a>
                    <a href="admin.template.set.php" target="_blank" class="mdui-btn">模板配置</a>
                    <a href="admin.app.set.php" target="_blank" class="mdui-btn">编辑网站</a>
                    <a href="admin.monitoring.list.php" target="_blank" class="mdui-btn">监控中心</a>
                    <a href="admin.level.list.php" target="_blank" class="mdui-btn">等级配置</a>
                    <a href="admin.user.set.php" target="_blank" class="mdui-btn">用户配置</a>
                </div>
            </div>
        </div>
        <div class="col-xl-8">
            <div class="card">
                <div class="card-body" style="height:60vh">
                    <div id="container" style="height: 100%;width:100%;">数据载入中...</div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card">
                <div class="card-body" style="height:60vh">
                    <div v-if="SalesList!==-1">
                        <div class="dropdown float-right">
                            <a href="#" class="dropdown-toggle arrow-none card-drop" data-toggle="dropdown"
                               aria-expanded="false">
                                <i class="mdi mdi-dots-vertical"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a @click="SalesListGet(1)" class="dropdown-item">热销总榜</a>
                                <a @click="SalesListGet(2)" class="dropdown-item">今日热卖</a>
                                <a @click="SalesListGet(3)" class="dropdown-item">昨日热卖</a>
                                <a href="./admin.goods.rank.php" class="dropdown-item">查看全部</a>
                            </div>
                        </div>
                        <h4 class="header-title mb-3">热销商品排行 - {{ type===1?'总榜':(type===2?'今日':'昨日') }}</h4>
                        <div class="inbox-widget" v-if="SalesList.length>=1"
                             style="height:50vh;overflow:hidden;overflow-y: auto;">
                            <div class="inbox-item" v-for="(item,index) in SalesList ">
                                <div class="inbox-item-img">
                                    <img :src="item.image" style="width:3rem;height:3rem;" class="rounded-circle"/>
                                </div>
                                <div class="inbox-item-author layui-elip" style="width:50%;">{{ item.name }}</div>
                                <p class="inbox-item-text">
                                    销售额：{{ item.money }}元
                                    <br>
                                    成本：{{ item.cost }}元
                                </p>
                                <p class="inbox-item-date">
                                    <a :href="'./admin.order.list.php?gid='+item.gid" target="_blank"
                                       class="btn btn-sm btn-link text-success font-13">{{ item.count }}个订单</a>
                                </p>
                            </div>
                        </div>
                        <div v-else>
                            空空如也~
                        </div>
                    </div>
                    <div v-else>
                        数据载入中...
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    Opcache扩展启用状态：<?= ($opcacheType ? '<span class="badge badge-success-lighten">已启用,网站已加速</span>' : '<span class="badge badge-danger-lighten">未启用,网站未加速</span>') ?></div>
                <div class="card-body">
                    PHP扩展简介[搜索引擎抄录]：<a
                            href="javascript:layer.alert('1、Opcache是什么？<br>Opcache是一种通过将解析的PHP脚本预编译的字节码（Operate Code）存放在共享内存中来避免每次加载和解析PHP脚本的开销，解析器可以直接从共享内存读取已经缓存的字节码（Operate Code），从而大大提高PHP的执行效率。<br><br>2、什么是Operate Code？<br>当解释器完成对脚本代码的分析后，便将它们生成可以直接运行的中间代码，也称为操作码（Operate Code，opcode）。Opcode cache的目地是避免重复编译，减少CPU和内存开销。如果动态内容的性能瓶颈不在于CPU和内存，而在于I/O操作，比如数据库查询带来的磁盘I/O开销，那么opcode cache的性能提升是非常有限的。但是既然opcode cache能带来CPU和内存开销的降低，这总归是好事。现代操作码缓存器（Optimizer+，APC2.0+，其他）使用共享内存进行存储，并且可以直接从中执行文件，而不用在执行前“反序列化”代码。这将带来显着的性能加速，通常特别是高流量和高并发量时降低了整体服务器的内存消耗，而且很少有缺点。',{btn:['了解'],title:'扩展介绍'})">点击查看</a>
                    <br>
                    <?php if ($opcacheType) { ?>
                        <br>
                        Opcache扩展缓存状态：<a href="javascript:opcacheContent()">点击查看</a>
                    <?php } else { ?>
                        <br>
                        为什么要开启Opcache,有什么好处：<a
                                href="javascript:layer.alert('本程序针对此扩展进行了相关优化,开启后可以极大的提升网站速度!，提高用户体验,减缓网站压力！,可以点击下方按钮来获取对应的安装开启教程！<hr>Ps：这个扩展不是必须安装的，只是安装了速度更快而已，没有安装条件的话，可以不用安装！',{btn:['关闭','宝塔安装','PHP安装'],title:'为什么要启用此扩展？',btn2:function (){open('https://cn.bing.com/search?q=%E5%AE%9D%E5%A1%94%E5%AE%89%E8%A3%85Opcache%E6%95%99%E7%A8%8B')},btn3:function (){open('https://cn.bing.com/search?q=PHP%E5%AE%89%E8%A3%85Opcache%E6%95%99%E7%A8%8B')}})">点击查看</a>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <div class="dropdown float-right">
                        <a href="#" class="dropdown-toggle arrow-none card-drop" data-toggle="dropdown"
                           aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="javascript:Hover.Update(2)" class="dropdown-item">获取最新数据</a>
                        </div>
                    </div>
                    <h4 class="header-title mb-4">程序版本管理</h4>
                    <div class="text-center">
                        <p class="mt-4 mb-2">
                            本地版本号：<?= $accredit['versions'] ?>
                        </p>
                        <h3 class="font-weight-normal  text-primary">↑↓</h3>
                        <p class="mt-2 mb-3">
                            云端最新版：<span id="VersionNumber">载入中...</span>
                        </p>
                        <a href="./admin.update.php" class="btn btn-outline-success btn-sm btn-block mb-1">程序版本管理
                            <i class="mdi mdi-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-8">
            <div class="card">
                <div class="card-body">
                    <div class="dropdown float-right">
                        <a href="#" class="dropdown-toggle arrow-none card-drop" data-toggle="dropdown"
                           aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="javascript:Announcement.GetList(2)" class="dropdown-item">获取最新公告</a>
                            <a href="./main.php?act=ForumUrl" target="_blank" class="dropdown-item">查看全部公告</a>
                        </div>
                    </div>
                    <h4 class="header-title mb-2" id="Announcement">官方公告通知</h4>
                    <div class="slimScrollDiv"
                         style="position: relative; overflow: hidden; width: auto; height: 456.8px;">
                        <div style="max-height: 171px; overflow: hidden; width: auto; height: 456.8px;overflow-y: auto;">
                            <div class="timeline-alt pb-0" v-if="Data2!==-1">
                                <div class="timeline-item" v-for="(item,index) in Data2"
                                     @click="Details(index)">
                                    <i class="mdi mdi-airplane bg-primary-lighten text-primary timeline-icon"></i>
                                    <div class="timeline-item-info">
                                        <div class="text-primary font-weight-bold mb-1 d-block">
                                            {{ item.title }}
                                        </div>
                                        <p class="mb-0 pb-2">
                                            <small class="text-muted">{{ item.addtime }}</small>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="timeline-alt pb-0" v-else>
                                公告数据载入中,请稍后...
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="AppBanner" class="row">
    <div class="col-xl-12" v-for="(item,index) in List">
        <div class="card mdui-p-a-0 mdui-m-b-1 mdui-m-t-1">
            <div class="card-body p-0">
                <div class="mdui-panel mdui-panel-popout" :id="item.id + '_card'">
                    <div class="mdui-panel-item mdui-shadow-0" :id="item.id + '_cards'">
                        <div class="mdui-panel-item-header" @click="Open(item.id)">
                            <div class="mdui-panel-item-title" style="width:80%">{{item.name}}
                            </div>
                            <i class="mdui-panel-item-arrow mdui-icon material-icons">keyboard_arrow_down</i>
                        </div>
                        <div class="mdui-panel-item-body">
                            <fieldset class="layui-elem-field">
                                <legend><a :href="item.url" target="_blank">{{item.url}}</a></legend>
                                <div class="layui-field-box">
                                    {{item.content}}
                                    <hr>
                                    <div class="mdui-text-center">
                                        <img :src="item.image"
                                             class="mdui-shadow-2"
                                             style="max-width:100%;"/>
                                    </div>
                                    <hr>
                                    广告热度：{{item.hot}}<br>
                                    点赞人数：{{item.top.length}}人<br>
                                    踩的人数：{{item.step_on.length}}人<br>
                                    抵押金额：{{item.deposit-0}}元
                                    <i class="mdui-icon material-icons" @click="Tips()"
                                       style="font-size:1.2em;color:#ff6b1b;cursor:pointer;">error_outline</i><br>
                                    到期时间：{{item.endtime}}<br>
                                    投放时间：{{item.addtime}}
                                    <hr>
                                    <div class="mc-vote mdui-text-center">
                                        <button @click="GiveThumbsUp(1,item,1)"
                                                class="mc-icon-button mdui-btn mdui-btn-icon mdui-btn-outlined mdui-shadow-2"
                                                :mdui-tooltip="'{content: \'顶一下,当前：'+item.top.length+'个人顶过\', delay: 300}'">
                                            <i
                                                    class="mdui-icon material-icons mdui-text-color-theme-icon"
                                                    style="color:rgba(4,180,170,0.72) !important">thumb_up</i>
                                        </button>
                                        <button @click="GiveThumbsUp(2,item,1)"
                                                class="mc-icon-button mdui-btn mdui-btn-icon mdui-btn-outlined mdui-shadow-2 mdui-m-l-5"
                                                :mdui-tooltip="'{content: \'踩一下,当前：'+item.step_on.length+'个人踩过\', delay: 300}'"
                                        ><i
                                                    class="mdui-icon material-icons mdui-text-color-theme-icon"
                                                    style="color:rgba(248,10,10,0.62) !important">thumb_down</i>
                                        </button>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'bottom.php'; ?>
<script src="../assets/js/echarts.min.js"></script>
<script src="../assets/js/vue3.js"></script>
<script src="../assets/admin/js/index.js?vs=<?= $accredit['versions'] ?>"></script>
<script src="../assets/admin/js/banner.js?vs=<?= $accredit['versions'] ?>"></script>
<script>
    function opcacheContent() {
        mdui.dialog({
            title: 'Opcache扩展缓存状态',
            content: `<?=$opcacheContent?>`,
            modal: true,
            history: false,
            buttons: [{
                text: '关闭',
            }]
        });
    }

    AppBanner.ListGet(1);
</script>
</body>
</html>
