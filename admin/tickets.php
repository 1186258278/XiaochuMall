<?php
// +----------------------------------------------------------------------
// | Project: xc
// +----------------------------------------------------------------------
// | Creation: 2023/3/9
// +----------------------------------------------------------------------
// | Filename: tickets2.php
// +----------------------------------------------------------------------
// | Explain: 售后工单管理
// +----------------------------------------------------------------------

$title = '售后工单管理';
include 'header.php';
global $_QET;
if (empty($_QET['state'])) {
    $State = 'all';
} else {
    $State = $_QET['state'];
}
?>
<div class="card">
    <div class="card-header">工单管理</div>
    <div class="card-body">
        <div class="layui-btn-container">
            <a href="?state=all" class="layui-btn layui-btn-sm" style="background-color: #0b9ff5">查看全部</a>
            <a href="?state=2" class="layui-btn layui-btn-sm" style="background-color: #ff9c0c">查看未结单</a>
            <a href="?state=1" class="layui-btn layui-btn-sm" style="background-color: #31c81e">查看已解决</a>
            <a href="?state=3" class="layui-btn layui-btn-sm" style="background-color: #7f9090">查看已关闭</a>
        </div>
        <div class="demoTable">
            搜索用户ID：
            <div class="layui-inline">
                <input class="layui-input" style="height:2em" name="id" id="demoReload" autocomplete="off">
            </div>
            <button class="layui-btn layui-btn-sm" data-type="reload">搜索</button>
        </div>
        <script type="text/html" id="bar">
            <a class="layui-btn layui-btn-xs" lay-event="edit" style="color: white">详情</a>
        </script>
        <table id="data" lay-filter="data"></table>
    </div>
</div>
<?php
include 'bottom.php';
?>
<script>
    layui.use(['table'], function () {
        var table = layui.table;
        table.render({
            elem: '#data'
            , url: 'ajax.php?act=Tickets&type=list&state=<?=$State?>'
            , page: true
            , cols: [[
                {field: 'id', title: 'ID', width: 70, sort: true, fixed: 'left'}
                , {
                    field: 'name', title: '工单标题', minWidth: 100
                }
                , {
                    field: 'state', title: '工单状态', width: 100, templet: function (res) {
                        switch (res.state - 0) {
                            case 1:
                                return '<font color=#31c81e>已解决</font>'
                            case 2:
                                return '<font color=#5f9ea0>已处理</font>'
                            default:
                                return '<font color=#708090>已关闭</font>'
                        }
                    }, sort: true
                }
                , {field: 'class', title: '工单类型', width: 130, sort: true}
                , {
                    field: 'uid', title: '用户ID', width: 100, sort: true, templet: function (res) {
                        return '<a href="./admin.user.log.php?uid=' + res.uid + '" target="_blank">查看日志:' + res.uid + '</a>'
                    }
                }
                , {
                    field: 'type', title: '实时状态', templet: function (res) {
                        switch ((res.type - 0)) {
                            case 1:
                                return '<font color=red>需你回复</font>';
                            case 2:
                                return '<font color=#1e90ff>待用户回复</font>';
                            case 3:
                                return '<font color=#2e8b57>已解决</font>';
                            case 4:
                                return '<font color=#708090>已关闭</font>';
                            case 5:
                                return '<font color=#9acd32>已评价</font>';
                            default:
                                return '<font color=#ff69b4>未知状态</font>';
                        }
                    }, width: 140
                }
                , {
                    field: 'grade', title: '评分', width: 120, sort: true, templet: function (res) {
                        if (res.grade == undefined) {
                            return '尚未评分';
                        } else {
                            return res.grade + '分';
                        }
                    }
                }
                , {field: 'addtime', title: '创建时间', minWidth: 160, sort: true}
                , {fixed: 'right', title: '操作', minWidth: 70, toolbar: '#bar'}
            ]]
        });

        var $ = layui.$, active = {
            reload: function () {
                var demoReload = $('#demoReload');
                //执行重载
                table.reload('data', {
                    page: {
                        curr: 1
                    }
                    , where: {
                        uid: demoReload.val()
                    }
                });
            }
        };

        $('.demoTable .layui-btn').on('click', function () {
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });

        //监听工具条
        table.on('tool(data)', function (obj) {
            var data = obj.data;
            if (obj.event === 'edit') {
                layer.open({
                    type: 2,
                    area: ['98%', '98%'],
                    title: '工单详情[' + data.name + ']',
                    content: 'tickets_code.php?id=' + data.id,
                    skin: 'layui-layer-rim',
                    id: 'OrderMark',
                    btn: false,
                    end: function () {
                        table.reload('data');
                    }
                });
            }
        });
    });

</script>
