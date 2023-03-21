<?php
/**
 * 网站模板配置
 */
$title = '网站模板配置';
include 'header.php';
global $cdnserver;
?>
<div class="card" id="App">
    <div class="card-body">
        <div class="mdui-tab mdui-tab-full-width" mdui-tab>
            <a href="#tab1" class="mdui-ripple">首页模板</a>
            <a href="#tab2" class="mdui-ripple">用户后台</a>
            <a href="#tab3" class="mdui-ripple">全局配置</a>
        </div>
        <div id="tab1" class="mdui-p-a-0 mdui-p-t-1">
            <div v-if="TemData!==false">
                <h3>
                    PC端模板：
                    <span class="badge badge-danger-lighten" v-if="TemData.conf.template==-1">已关闭</span>
                    <span class="badge badge-warning-lighten" v-else-if="TemData.conf.template==-2">套娃模式</span>
                    <span class="badge badge-primary-lighten" v-else>{{TemData.conf.template}}</span>
                </h3>

                <div class="mdui-row-xs-2 mdui-row-sm-3 mdui-row-md-4 mdui-row-lg-5 mdui-row-xl-6 mdui-grid-list">
                    <div class="mdui-col" @click="TemplateSelection(index,item,1)" style="cursor: pointer;"
                         title="当前选择的模板"
                         v-for="(item,index) in TemData.data.PC">
                        <div class="mdui-grid-tile">
                            <div v-if="TemData.conf.template==index" class="mdui-card-menu">
                                <button class="mdui-btn mdui-btn-icon mdui-text-color-white mdui-p-l-1 mdui-shadow-1 mdui-ripple"
                                        style="background-color: rgba(255,0,0,0.85);"><i
                                            class="mdui-icon material-icons mdui-m-r-1">beenhere</i>
                                </button>
                            </div>
                            <img onerror="this.src='../assets/img/tebj.jpg'" style="height:8em;"
                                 :src="'../template/'+index+'/index.png'"/>
                            <div class="mdui-grid-tile-actions mdui-grid-tile-actions-gradien">
                                <div class="mdui-grid-tile-text">
                                    <div class="mdui-grid-tile-title">
                                        {{(item==false?(index==-1?'关闭模板':(index==-2?'套娃模式':index)):item.name)}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <h3>
                    移动端模板：
                    <span class="badge badge-danger-lighten" v-if="TemData.conf.template_m==-1">已关闭</span>
                    <span class="badge badge-primary-lighten" v-else>{{TemData.conf.template_m}}</span>
                </h3>
                <div class="mdui-row-xs-2 mdui-row-sm-3 mdui-row-md-4 mdui-row-lg-5 mdui-row-xl-6 mdui-grid-list">
                    <div class="mdui-col" @click="TemplateSelection(index,item,2)" style="cursor: pointer;"
                         title="当前选择的模板"
                         v-for="(item,index) in TemData.data.M">
                        <div class="mdui-grid-tile">
                            <div v-if="TemData.conf.template_m==index" class="mdui-card-menu">
                                <button class="mdui-btn mdui-btn-icon mdui-text-color-white mdui-p-l-1 mdui-shadow-1 mdui-ripple"
                                        style="background-color: rgba(255,0,0,0.85);"><i
                                            class="mdui-icon material-icons mdui-m-r-1">beenhere</i>
                                </button>
                            </div>
                            <img onerror="this.src='../assets/img/tebj.jpg'" style="height:8em;"
                                 :src="'../template/'+index+'/index.png'"/>
                            <div class="mdui-grid-tile-actions mdui-grid-tile-actions-gradien">
                                <div class="mdui-grid-tile-text">
                                    <div class="mdui-grid-tile-title">
                                        {{(item==false?(index==-1?'关闭模板':index):item.name)}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-else>
                数据载入中...
            </div>
        </div>
        <div id="tab2" class="mdui-p-a-0 mdui-p-t-1">
            <div v-if="TemData!==false">
                <h3>
                    用户后台PC端模板：
                    <span class="badge badge-danger-lighten" v-if="TemData.conf.UserBackgroundTemplatePC==-1">已关闭</span>
                    <span class="badge badge-primary-lighten" v-else>{{TemData.conf.UserBackgroundTemplatePC}}</span>
                </h3>

                <div class="mdui-row-xs-2 mdui-row-sm-3 mdui-row-md-4 mdui-row-lg-5 mdui-row-xl-6 mdui-grid-list">
                    <div class="mdui-col" @click="TemplateSelection(index,item,1,2)" style="cursor: pointer;"
                         title="当前选择的模板"
                         v-for="(item,index) in TemData.dataUser.PC">
                        <div class="mdui-grid-tile">
                            <div v-if="TemData.conf.UserBackgroundTemplatePC==index" class="mdui-card-menu">
                                <button class="mdui-btn mdui-btn-icon mdui-text-color-white mdui-p-l-1 mdui-shadow-1 mdui-ripple"
                                        style="background-color: rgba(255,0,0,0.85);"><i
                                            class="mdui-icon material-icons mdui-m-r-1">beenhere</i>
                                </button>
                            </div>
                            <img onerror="this.src='../assets/img/tebj.jpg'" style="height:8em;"
                                 :src="'../user/template/'+index+'/index.png'"/>
                            <div class="mdui-grid-tile-actions mdui-grid-tile-actions-gradien">
                                <div class="mdui-grid-tile-text">
                                    <div class="mdui-grid-tile-title">
                                        {{(item==false?(index==-1?'关闭模板':index):item.name)}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <h3>
                    用户后台移动端模板：
                    <span class="badge badge-danger-lighten" v-if="TemData.conf.UserBackgroundTemplateM==-1">已关闭</span>
                    <span class="badge badge-primary-lighten" v-else>{{TemData.conf.UserBackgroundTemplateM}}</span>
                </h3>
                <div class="mdui-row-xs-2 mdui-row-sm-3 mdui-row-md-4 mdui-row-lg-5 mdui-row-xl-6 mdui-grid-list">
                    <div class="mdui-col" @click="TemplateSelection(index,item,2,2)" style="cursor: pointer;"
                         title="当前选择的模板"
                         v-for="(item,index) in TemData.dataUser.M">
                        <div class="mdui-grid-tile">
                            <div v-if="TemData.conf.UserBackgroundTemplateM==index" class="mdui-card-menu">
                                <button class="mdui-btn mdui-btn-icon mdui-text-color-white mdui-p-l-1 mdui-shadow-1 mdui-ripple"
                                        style="background-color: rgba(255,0,0,0.85);"><i
                                            class="mdui-icon material-icons mdui-m-r-1">beenhere</i>
                                </button>
                            </div>
                            <img onerror="this.src='../assets/img/tebj.jpg'" style="height:8em;"
                                 :src="'../user/template/'+index+'/index.png'"/>
                            <div class="mdui-grid-tile-actions mdui-grid-tile-actions-gradien">
                                <div class="mdui-grid-tile-text">
                                    <div class="mdui-grid-tile-title">
                                        {{(item==false?(index==-1?'关闭模板':index):item.name)}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-else>
                数据载入中...
            </div>
        </div>
        <div id="tab3" class="mdui-p-a-0 mdui-p-t-1">
            <div v-if="TemData!==false">
                <div class="mdui-textfield">
                    <div style="height:2em;line-height:2em;">背景图片</div>
                    <select :value="TemData.conf.background" v-model="TemData.conf.background" class="mdui-select"
                            style="width:100%;font-size:14px;color: rgba(29,29,29,0.77)">
                        <option value="1">随机二次元一</option>
                        <option value="2">随机高清壁纸</option>
                        <option value="3">随机二次元二</option>
                        <option value="4">模板默认背景</option>
                        <option value="5">自定义背景图</option>
                    </select>
                    <div style="margin-top: 0.2em;font-size:12px;color:rgba(0,0,0,.54)">
                        自定义背景图存放到：assets/img/bj.png,若无则会显示空白！
                    </div>
                </div>

                <div class="mdui-textfield">
                    <div style="height:2em;line-height:2em;">静态资源加速节点</div>
                    <select :value="TemData.conf.cdnpublic" v-model="TemData.conf.cdnpublic" class="mdui-select"
                            style="width:100%;font-size:14px;color: rgba(29,29,29,0.77)">
                        <option value="1">七牛云CDN</option>
                        <option value="2">BootCDN</option>
                    </select>
                </div>

                <div class="mdui-textfield">
                    <div style="height:2em;line-height:2em;">晴玖静态资源加速</div>
                    <select :value="TemData.conf.cdnserver" v-model="TemData.conf.cdnserver" class="mdui-select"
                            style="width:100%;font-size:14px;color: rgba(29,29,29,0.77)">
                        <option value="1">开启静态资源加速</option>
                        <option value="2">关闭静态资源加速</option>
                    </select>
                    <div style="margin-top: 0.2em;font-size:12px;color:rgba(0,0,0,.54)">
                        若开启了SSL，可能会导致站点资源文件加载异常，导致无法正常使用！
                    </div>
                </div>

                <div class="mdui-textfield">
                    <div style="height:2em;line-height:2em;">首页横幅广告图</div>
                    <div>
                        <span class="badge badge-success-lighten" @click="BannerAdd()">添加一条</span>
                        <span class="badge badge-primary-lighten mdui-m-l-1" @click="BannerSetAll()">快速编辑</span>
                        <span class="badge badge-danger-lighten mdui-m-l-1" @click="BannerCloseAll()">清空全部</span>
                    </div>
                    <div class="mdui-m-t-1">
                        <div class="mdui-row-xs-2 mdui-row-sm-3 mdui-row-md-4 mdui-row-lg-5 mdui-row-xl-6 mdui-grid-list">
                            <div class="mdui-col" style="cursor: pointer;"
                                 title="点击编辑横幅广告"
                                 v-for="(item,index) in Banner"
                                 @click="OpenBanner(index,item)"
                            >
                                <div class="mdui-grid-tile">
                                    <img onerror="this.src='../assets/img/404.png'" style="height:8em;"
                                         :src="item.image"/>
                                    <div class="mdui-grid-tile-actions mdui-grid-tile-actions-gradien">
                                        <div class="mdui-grid-tile-text">
                                            <div class="mdui-grid-tile-title">
                                                {{item.url}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div v-else>
                数据载入中...
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header">帮助说明</div>
    <div class="card-body">
        首页模板存放路径：<span style="color: #0b9ff5"><?= ROOT ?>template/</span><br>
        用户后台模板存放路径：<span style="color: #0b9ff5"><?= ROOT ?>user/template/</span>
        <hr>
        <h5>模板文件夹内的文件结构说明</h5>
        <span style="color: #21dab0">conf.json</span>
        <pre class="layui-code">
{
    "name": "PC端默认模板",  //模板名称
    "version": "1.0.0",    //模板版本号
    "type": "1",    //1=PC端模板，2=移动端模板，3=通用模板
    "content": "一款PC端专用用户后台模板",     //模板文字介绍
    "extend": [  //输入框和选择框的配置区，不需要配置的话，直接返回[]
         {
            "type": 1, //1=输入框，2=选择框
            "name": "客服页面通知",  //输入框名称
            "value": "", //可编辑的参数,可以直接填写，来预设参数
            "Tips": "客服页面通知内容,不支持HTML"  //输入框提示内容
        },
        {
            "type": 2,  //1=输入框，2=选择框
            "name": "是否开启后台水印",  //选择框名称
            "value": "2", //默认选择的值
            "Tips": "开启后页面会显示水印信息", //提示信息
            "data": { //下拉框可选项
                "1": "关闭用户后台水印", //可选项1，其中："1"里面是值，"关闭用户后台水印"是值的描述
                "2": "开启用户后台水印" //可选项2
            }
        }
    ]
}
</pre>
        <hr>
        <span style="color: #21dab0">index.php</span>：模板入口文件<br>
        <span style="color: #21dab0">index.png</span>：模板图片
        <hr>
        首页模板配置获取接口：<span style="color: rgba(67,35,218,0.6)"><?= href(2) ?>/main.php?act=TemData&name=模板名称</span><br>
        用户后台模板配置获取接口：<span
                style="color: rgba(67,35,218,0.6)"><?= href(2) ?>/user/ajax.php?act=TemData&name=模板名称</span><br>
    </div>
</div>

<?php include 'bottom.php'; ?>
<script src="../assets/js/vue3.js"></script>
<script src="../assets/admin/js/temset.js?vs=<?= $accredit['versions'] ?>"></script>
