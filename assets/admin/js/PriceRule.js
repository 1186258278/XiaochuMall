const App = Vue.createApp({
    data() {
        return {
            Data: [],
            Rules: [],
            RulesType: 2, //1固定，2百分比
            Money: 100,
        }
    }
    , watch: {
        RulesType() {
            this.RenderingRul();
        },
        Money() {
            this.RenderingRul();
        }
    }, methods: {
        Preview(JSON, name, type, JsonPrimitive, id) {
            this.Money = 100;
            this.Rules = JSON;
            this.RulesType = type;

            mdui.dialog({
                title: '规则预览[' + name + ']',
                content: `
<div class="layui-form layui-form-pane">
    <div class="layui-form-item">
        <label class="layui-form-label">成本</label>
        <div class="layui-input-block">
          <input type="number" name="money" onkeyup="changeInput()" value="100" required  placeholder="模拟的商品成本,可以查看最终价格" class="layui-input">
            <span style="color: salmon;font-size: 12px;">此价格作为下面加价后价格显示预览，无实际意义！</span>
        </div>
    </div>
</div>
<div style="min-height: 60vh;border: solid darkgrey;padding: 5px;"><table class="layui-table" lay-size="sm">
    <colgroup>
        <col width="50">
  </colgroup>
  <thead>
    <tr>
      <th>等级</th>
      <th>余额加价 / 模拟售价</th>
      <th>积分加价 / 模拟兑换价</th>
    </tr>
  </thead>
  <tbody id="ListRule"></tbody>
  </table></div>`,
                modal: true,
                destroyOnClosed: true,
                closeOnEsc: false,
                history: false,
                buttons: [{
                    text: '关闭',
                }, {
                    text: '编辑规则', onClick: function () {
                        let Data = {};
                        Data.name = name;
                        Data.type = type;
                        Data.json = JsonPrimitive;
                        Data.id = id;
                        let content = `
<div class="layui-form layui-form-pane">
  <div class="layui-form-item">
    <label class="layui-form-label">规则名称</label>
    <div class="layui-input-block">
      <input type="text" id="set_name" value="` + Data.name + `" placeholder="请输入规则名称" autocomplete="off" class="layui-input">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">加价模式</label>
    <div class="layui-input-block">
      <select id="set_type"  lay-ignore style="width: 100%;height: 38px;border-color: #D2D2D2;">
        <option value="1" ` + (Data.type == 1 ? 'selected' : '') + `>固定加价</option>
        <option value="2" ` + (Data.type == 2 ? 'selected' : '') + `>百分比加价</option>
      </select>
    </div>
  </div>
  <div class="layui-form-item layui-form-text">
    <label class="layui-form-label">规则原始内容[JSON][说明在最下面]</label>
    <div class="layui-input-block">
      <textarea id="set_json" placeholder="请输入内容" style="height: 300px;" class="layui-textarea">` + App.JSONFormat(Data.json) + `</textarea>
    </div>
  </div>
  <blockquote class="layui-elem-quote">可以通过修改原始JSON数据来进行规则内容调整！<br>"price：余额加价数值","profits：积分加价数值"<br>等级顺序：从低到高,1级,2级,3级...<br>加价顺序：从高到低：等级越高，加价金额或百分比越低！</blockquote>
  </div>
                        `;
                        layer.open({
                            title: '修改规则 : ' + id,
                            content: content,
                            btn: ['保存', '取消'],
                            btn1: function () {
                                Data.name = $("#set_name").val();
                                Data.type = $("#set_type").val();
                                try {
                                    let JSON = $("#set_json").val();
                                    Data.json = JSON.replace(/\n+\s+/g, "");
                                } catch (e) {
                                    layer.closeAll();
                                    layer.msg('注意：当前提交的JSON数据有误，无法完成修改！');
                                    return;
                                }
                                layer.msg('正在修改[' + name + ']规则', {icon: 16, time: 99999999});
                                $.ajax({
                                    type: 'POST',
                                    url: './main.php?act=UpdateRule',
                                    data: Data,
                                    async: true,
                                    dataType: 'json',
                                    success: function (res) {
                                        layer.closeAll();
                                        if (res.code >= 0) {
                                            App.ListRules();
                                            layer.msg(res.msg, {icon: 1});
                                        } else layer.msg(res.msg);
                                    }
                                });
                            }
                        })


                    }
                }],
                onOpened: function () {
                    App.RenderingRul();
                }
            })
        },
        JSONTrim(JSONstr) {
            try {
                JSONstr = JSONstr.replace(/'/g, '"');
                JSONstr = JSON.stringify(JSON.parse(JSONstr));
            } catch (error) {
                // 转换失败错误提示
                console.error('json数据格式有误...');
                console.error(error);
                JSONstr = null;
            }
            return JSONstr;
        },
        JSONFormat(JSONstr) {
            JSONstr = this.JSONTrim(JSONstr); // 初步格式化json
            let re = new RegExp('\\{|\\}|,|:', 'g'); // 匹配格式化后的json中的{},:
            let exec = null;
            let InvalidFs = 0;
            let InvalidBs = 0;
            while (exec = re.exec(JSONstr)) { // 找{},:
                let frontToCurrent = exec.input.substr(0, exec.index + 1); // 匹配开头到当前匹配字符之间的字符串
                if (frontToCurrent.replace(/\\"/g, "").replace(/[^"]/g, "").length % 2 != 0) { // 测试当前字符到开头"的数量，为双数则被判定为目标对象
                    if (exec[0] === '{') InvalidFs++;
                    else if (exec[0] === '}') InvalidBs++;
                    continue; // 不是目标对象，手动跳过
                }
                let keyTimesF = frontToCurrent.replace(/[^\{]/g, '').length - InvalidFs; // 找出当前匹配字符之前所有{的个数
                let keyTimesB = frontToCurrent.replace(/[^\}]/g, '').length - InvalidBs; // 找出当前匹配字符之前所有}的个数
                let indentationTimes = keyTimesF - keyTimesB; // 根据{个数计算缩进

                if (exec[0] === '{') {
                    JSONstr = JSONstr.slice(0, exec.index + 1) + '\n' + '\t'.repeat(indentationTimes) + JSONstr.slice(exec.index + 1); // 将缩进加入字符串
                } else if (exec[0] === '}') {
                    JSONstr = JSONstr.slice(0, exec.index) + '\n' + '\t'.repeat(indentationTimes) + JSONstr.slice(exec.index) // 将缩进加入字符串
                    re.exec(JSONstr); // 在查找目标前面插入字符串会回退本次查找，所以手动跳过本次查找
                } else if (exec[0] === ',') {
                    JSONstr = JSONstr.slice(0, exec.index + 1) + '\n' + '\t'.repeat(indentationTimes) + JSONstr.slice(exec.index + 1)
                } else if (exec[0] === ':') {
                    JSONstr = JSONstr.slice(0, exec.index + 1) + ' ' + JSONstr.slice(exec.index + 1)
                } else {
                    console.log(`匹配到了来路不明的${exec[0]}`)
                }
            }
            return JSONstr === null ? 'Invalid value' : JSONstr;
        },
        EmptyRul() {
            layer.open({
                title: '警告',
                content: '是否需要清空已配置的等级加价规则？',
                btn: ['清空', '取消'],
                icon: 3,
                btn1: function () {
                    App.Rules = [];
                    layer.closeAll();
                    layer.msg('清空成功', {icon: 1});
                    App.RenderingRul();
                }
            })
        },
        AddRul() {
            let count = Object.keys(App.Rules).length;
            let content = `
<div class="layui-form">
  <div class="layui-form-item">
    <span>余额加价(` + (App.RulesType == 1 ? '元' : '%') + `)</span>
    <div>
      <input type="number" name="price" required  placeholder="请输入余额加价金额或百分比" class="layui-input">
    </div>
  </div>
  <div class="layui-form-item">
    <span>积分加价(` + (App.RulesType == 1 ? '个' : '%') + `)</span>
    <div>
      <input type="number" name="profits" required  placeholder="请输入积分加价数量或百分比" class="layui-input">
    </div>
  </div>
</div>
                `;
            if (count >= 1) {
                content = `
<div class="layui-form">
  <div class="layui-form-item">
    <span>余额加价(` + (App.RulesType == 1 ? '元' : '%') + `)</span>
    <div>
      <input type="number" name="price" required  placeholder="请输入余额加价金额或百分比" class="layui-input">
       <span style="color: salmon;font-size: 12px;">不可低于：` + (App.Rules[count - 1].price) + `</span>
    </div>
  </div>
  <div class="layui-form-item">
    <span>积分加价(` + (App.RulesType == 1 ? '个' : '%') + `)</span>
    <div>
      <input type="number" name="profits" required  placeholder="请输入积分加价数量或百分比" class="layui-input">
      <span style="color: salmon;font-size: 12px;">不可低于：` + (App.Rules[count - 1].profits) + `</span>
    </div>
  </div>
</div>
                `;
            }

            layer.open({
                title: '创建V' + (count + 1) + '加价规则',
                content: content,
                btn: ['添加新规则', '取消'],
                btn1: function () {
                    let price = $("input[name='price']").val() - 0;
                    let profits = $("input[name='profits']").val() - 0;
                    if (!price || !profits || price <= 0 || profits <= 0) {
                        alert('请填写完整！');
                        return false;
                    }
                    if (count >= 1) {
                        if (App.Rules[count - 1].price <= price) {
                            alert('加价金额或百分比不可高于' + App.Rules[count - 1].price + '元');
                            return false;
                        }
                        if (App.Rules[count - 1].profits <= profits) {
                            alert('加价积分或百分比不可高于' + App.Rules[count - 1].profits + '积分');
                            return false;
                        }
                    }
                    App.Rules[count] = {
                        price: price,
                        profits: profits
                    };
                    App.RenderingRul();
                    App.AddRul();
                }
            })
        },
        RenderingRul() {
            let content = ``;
            for (const key in App.Rules) {
                try {
                    data = App.Rules[key];
                } catch (e) {
                    continue;
                }
                if (App.RulesType == 1) {
                    price = (App.Money + (data.price - 0)).toFixed(2);
                    profits = (App.Money + (data.profits - 0)).toFixed(0);
                } else {
                    price = (App.Money + (App.Money * (data.price / 100))).toFixed(2);
                    profits = (App.Money + (App.Money * (data.profits / 100))).toFixed(0);
                }
                content += `<tr>
      <td>V` + (key - 0 + 1) + `</td>
      <td>加价：` + data.price + (App.RulesType == 1 ? '元' : '%') + `<br>售价：` + price + `元</td>
      <td>加价：` + data.profits + (App.RulesType == 1 ? '个' : '%') + `<br>兑换价：` + profits + `点</td>
    </tr>`;
            }
            $("#ListRule").html(content);
        },
        CreateRule() {
            this.Rules = [];
            this.Money = 100;
            this.RulesType = 2;
            let content = `
<div class="layui-form">
  <div class="layui-form-item">
    <label class="layui-form-label">名称</label>
    <div class="layui-input-block">
      <input type="text" name="name" required  placeholder="请输入规则名称" class="layui-input">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">类型</label>
    <div class="layui-input-block">
      <input type="radio" lay-filter="RulesType" name="RulesType" value="1" title="固定金额加价">
      <input type="radio" lay-filter="RulesType" name="RulesType" value="2" title="百分比加价" checked>
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">成本</label>
    <div class="layui-input-block">
      <input type="number" name="money" onkeyup="changeInput()" value="100" required  placeholder="模拟的商品成本,可以查看最终价格" class="layui-input">
        <span style="color: salmon;font-size: 12px;">此价格作为下面加价后价格显示预览，无实际意义！</span>
    </div>
  </div>
</div>
 <button type="button" onclick="App.AddRul()" class="layui-btn layui-btn-sm  layui-btn-normal" style="margin-bottom: 1em;">添加规则内容</button>
 <button type="button" onclick="App.EmptyRul()" class="layui-btn layui-btn-sm  layui-btn-warm" style="margin-bottom: 1em;">清空已配置规则</button>
 <div style="min-height: 30vh;border: solid darkgrey;padding: 5px;">
 <blockquote class="layui-elem-quote">注意：价格从上至下，每级加价金额或百分比都必须比上一级低！</blockquote>
 <table class="layui-table" lay-size="sm">
    <colgroup>
        <col width="50">
  </colgroup>
  <thead>
    <tr>
      <th>等级</th>
      <th>余额加价 / 模拟售价</th>
      <th>积分加价 / 模拟兑换价</th>
    </tr>
  </thead>
  <tbody id="ListRule"></tbody>
  </table>
</div>
`;
            mdui.dialog({
                title: '新增规则',
                content: content,
                modal: true,
                destroyOnClosed: true,
                closeOnEsc: false,
                history: false,
                buttons: [{
                    text: '关闭',
                }, {
                    text: '添加规则', onClick: function () {
                        let Data = {};
                        let name = $("input[name='name']").val();
                        if (name == '') {
                            layer.msg('请将规则名称提交完整');
                            return false;
                        }

                        Data.name = name;
                        Data.type = App.RulesType;
                        Data.json = App.Rules;

                        layer.msg('正在创建[' + name + ']规则', {icon: 16, time: 99999999});
                        $.ajax({
                            type: 'POST',
                            url: './main.php?act=GreatRule',
                            data: Data,
                            async: true,
                            dataType: 'json',
                            success: function (res) {
                                layer.closeAll();
                                if (res.code >= 0) {
                                    App.ListRules();
                                    layer.msg(res.msg, {icon: 1});
                                } else layer.msg(res.msg);
                            }
                        });
                    }
                }],
                onOpened: function () {
                    layui.use(['form'], function () {
                        var form = layui.form;
                        form.render();
                        form.on('radio(RulesType)', function (data) {
                            App.RulesType = data.value;
                            App.RenderingRul();
                        });
                        App.RenderingRul();
                    });
                }
            });
        },
        DeleteRule(id) {
            layer.open({
                title: '温馨提示',
                content: '是否删除此规则，删除后，对应的商品加价规则全部失效！',
                icon: 3,
                btn: ['确定', '取消'],
                btn1: function () {
                    let is = layer.msg('删除中，请稍后...', {icon: 16, time: 9999999});
                    $.ajax({
                        type: "POST", url: './main.php?act=DeleteRule', data: {
                            id: id
                        }, dataType: "json", success: function (res) {
                            layer.close(is);
                            if (res.code == 1) {
                                layer.alert(res.msg, {
                                    icon: 1, btn1: function () {
                                        App.ListRules();
                                    }
                                });
                            } else {
                                layer.alert(res.msg, {
                                    icon: 2
                                });
                            }
                        }, error: function () {
                            layer.msg('服务器异常！');
                        }
                    });
                }
            });
        },
        StateSet(id, state) {
            let is = layer.msg('加载中，请稍后...', {icon: 16, time: 9999999});
            $.ajax({
                type: "POST", url: './main.php?act=SetRule', data: {
                    id: id, state: state
                }, dataType: "json", success: function (res) {
                    layer.close(is);
                    if (res.code == 1) {
                        App.ListRules();
                    } else {
                        layer.alert(res.msg, {
                            icon: 2
                        });
                    }
                }, error: function () {
                    layer.msg('服务器异常！');
                }
            });
        },
        ListRules() {
            let is = layer.msg('规则载入中，请稍后...', {icon: 16, time: 9999999});
            $.ajax({
                type: "POST", url: './main.php?act=ListRule'
                , dataType: "json", success: function (res) {
                    layer.close(is);
                    if (res.code == 1) {
                        App.Data = res.data;
                    } else {
                        App.Data = [];
                    }
                }, error: function () {
                    layer.msg('服务器异常！');
                }
            });
        }
    }
}).mount('#App');
App.ListRules();

function changeInput() {
    App.Money = $("input[name='money']").val() - 0;
}