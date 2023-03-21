<?php
$title = '单商品加价规则配置';
include 'header.php';
?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <a title="创建规则" href="javascript:App.CreateRule()" class="badge badge-primary"><i
                            class="layui-icon layui-icon-addition"></i></a>
                <a title="查看商品" href="./admin.goods.list.php" class="badge badge-success"><i
                            class="layui-icon layui-icon-cart-simple"></i></a>
                <a title="初始化" href="javascript:App.ListRules();" class="badge badge-warning"><i
                            class="layui-icon layui-icon-refresh-3"></i></a>
            </div>
            <div class="card-body" id="App">
                <div class="table-responsive" id="table">
                    <table class="table table-hover table-centered mb-0" style="font-size:0.9em;">
                        <thead>
                        <tr style="white-space: nowrap">
                            <th>操作</th>
                            <th>ID</th>
                            <th>规则名称</th>
                            <th>加价模式</th>
                            <th>规则状态</th>
                            <th>创建时间</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(item,index) in Data">
                            <td>
                                <span style="cursor:pointer;color: red"
                                      @click="DeleteRule(item.id)" class="action-icon"> <i
                                            class="layui-icon layui-icon-delete"></i></span>
                            </td>
                            <td>
                                {{item.id}}
                            </td>
                            <td>
                                <span @click="Preview(item.json,item.name,item.type,item.JsonPrimitive,item.id)"
                                      style="cursor: pointer;color: #0b9ff5">{{item.name}}</span>
                            </td>
                            <td>
                                <a v-if="item.type==1"
                                   href="javascript:void" class="badge badge-primary-lighten">固定加价</a>
                                <a v-else href="javascript:void"
                                   class="badge badge-warning-lighten">百分比加价</a>
                            </td>
                            <td>
                                <a v-if="item.state==1" @click="StateSet(item.id,2)"
                                   href="javascript:void" class="badge badge-success-lighten">启用中</a>
                                <a v-else href="javascript:void"
                                   @click="StateSet(item.id,1)"
                                   class="badge badge-danger-lighten">已停用</a>
                            </td>
                            <td>
                                {{ item.addtime }}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div v-if="Data.length===0" class="text-center w-100 mt-3 font-w300">
                    {{ type==-1?'正在载入中,请稍后...':'一个加价规则也没有' }}
                </div>
            </div>
            <div class="card-body">
                <blockquote class="layui-elem-quote" style="color: rgba(67,35,218,0.6);">商品售价配置优先级：<span
                            mdui-tooltip="{content: '商品详情内配置', position: 'top'}" style="cursor: pointer;color: salmon">商品固定金额</span>
                    > <a href="admin.PriceRule.php" target="_blank"
                         mdui-tooltip="{content: '商品相关内的加价规则处配置', position: 'top'}">单商品规则加价</a> > <span
                            mdui-tooltip="{content: '商品详情内配置', position: 'top'}" style="cursor: pointer;color: salmon">自定义利润比例加价</span>
                    >
                    <a href="admin.increasePrice.list.php" target="_blank"
                       mdui-tooltip="{content: '批量加价规则内配置', position: 'top'}">利润比例规则加价</a>
                    > <a href="admin.level.list.php" target="_blank"
                         mdui-tooltip="{content: '等级列表内配置', position: 'top'}">用户等级利润规则</a>
                </blockquote>
            </div>
        </div>
    </div>
</div>
<?php include 'bottom.php'; ?>
<script src="../assets/js/jquery.nestable.js"></script>
<script src="../assets/js/vue3.js"></script>
<script src="../assets/admin/js/PriceRule.js?vse=<?= $accredit['versions'] ?>"></script>
</body>
</html>
