System.register(["./index-legacy.6abde332.js"],(function(e){"use strict";var t,a,l,o,s,r,p,d,i,n,m,u,c,g,h,y,f,b;return{setters:[function(e){t=e._,a=e.E,l=e.r,o=e.h,s=e.c,r=e.d,p=e.w,d=e.F,i=e.o,n=e.f,m=e.e,u=e.t,c=e.j,g=e.a,h=e.n,y=e.i,f=e.b,b=e.D}],execute:function(){const x={name:"appManagement",data:()=>({data:{ImageUrl:""},limit:999,show:!1,Form:{},Types:"",GreatField:{url:"",name:""},show2:!1,type:1,Load:!1,Loads:{}}),mounted(){this.queryAppStatus()},methods:{AppCreate(){if(""===this.GreatField.name)return void this.$message({message:"请将App名称填写完整！",type:"error",grouping:!0});if(1==this.type)this.GreatField.url=-1;else if(""==this.GreatField.url)return void this.$message({message:"请将自定义封装域名填写完整！",type:"error",grouping:!0});this.Load=!0;let e=this;a.confirm("确定要创建APP吗？","温馨提示",{confirmButtonText:"确认创建",cancelButtonText:"取消",type:"warning"}).then((()=>{e.$ajax.post("ajax.php?act=AppAdd",e.GreatField).then((function(t){e.Load=!1,e.show2=!1,t.code>=0?(e.$message({message:t.msg,type:"success",grouping:!0}),e.queryAppStatus()):e.$message({message:t.msg,type:"error",grouping:!0})}))})).catch((()=>{e.Load=!1}))},AppDownload(e,t=1){let l=this;a.confirm("是否要"+(1===t?"预览客户端下载地址？":"将客户端下载地址部署到店铺？"),"温馨提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((()=>{l.Loads[e]=!0,l.$ajax.post("ajax.php?act=AppDownload",{id:e,type:t}).then((t=>{l.Loads[e]=!1,t.code>=0?a.confirm(t.msg+"\n"+t.url,"温馨提示",{confirmButtonText:"打开地址",type:"success"}).then((()=>{open(t.url)})):l.$message({message:t.msg,type:"error",grouping:!0})}))}))},AppCalibration(e){let t=this;a.confirm("确定同步App打包任务进度吗？","温馨提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((()=>{t.Loads[e]=!0,t.$ajax.post("ajax.php?act=AppCalibration",{id:e}).then((a=>{t.Loads[e]=!1,a.code>=0?(t.$message({message:"同步结果："+a.msg,type:"success",grouping:!0}),t.show=!1,t.queryAppStatus()):t.$message({message:a.msg,type:"error",grouping:!0})}))}))},AppSubmit(e){let t=this;a.confirm("确定提交此任务到服务器生成吗？提交后除了介绍外，其他参数均不可修改！","温馨提示",{confirmButtonText:"确定提交",cancelButtonText:"取消",type:"warning"}).then((()=>{t.Loads[e]=!0,t.$ajax.post("ajax.php?act=AppSubmit",{id:e}).then((a=>{t.Loads[e]=!1,a.code>=0?(t.$message({message:a.msg,type:"success",grouping:!0}),t.show=!1,t.queryAppStatus()):t.$message({message:a.msg,type:"error",grouping:!0})}))}))},adjustmentValue(e,t=-1){let a=this.Form;if(!a)return;if(-1!=a.TaskID&&"content"!==e)return this.$message({message:"已经提交生成，无法再进行修改！",type:"error",grouping:!0}),!1;if(""===a[e]&&"content"!==e)return this.$message({message:"请填写完整！",type:"error",grouping:!0}),!1;let l="ajax.php?act=AppSet",o={id:a.id,field:e,value:a[e]};-1!=t&&(l="ajax.php?act=AppColorSet",o={id:a.id,color:a[e],type:t});let s=this;this.$ajax.post(l,o).then((e=>{e.code>=0?(s.$message({message:e.msg,type:"success",grouping:!0}),s.queryAppStatus()):s.$message({message:"请填写完整！",type:"error",grouping:!0})}))},AppDelete(e){let t=this;a.confirm("确定要删除吗？删除后无法恢复，并且生成App所需的费用也不会退还！？","警告",{confirmButtonText:"确认删除",cancelButtonText:"取消",type:"warning"}).then((()=>{t.$ajax.post("ajax.php?act=AppDelete",{id:e}).then((function(e){e.code>=0?(t.$message({message:e.msg,type:"success",grouping:!0}),t.show=!1,t.queryAppStatus()):t.$message({message:e.msg,type:"error",grouping:!0})}))}))},ImageOpen(e){""!=e?(e=this.data.ImageUrl+e,a.alert('<img src="'+e+'" width="100%" />',"图片预览",{confirmButtonText:"OK",dangerouslyUseHTMLString:!0})):a.alert("请先填写图片ID","警告",{confirmButtonText:"好的",dangerouslyUseHTMLString:!0})},UploadedSuccessfully(e,t){e.code>=0?(this.Form[this.Types]=e.id,this.$message({message:e.msg,type:"success",grouping:!0}),this.queryAppStatus()):this.$message({message:e.msg,type:"error",grouping:!0})},beforeAvatarUpload:e=>!(e.size/1024/1024>2&&(alert("最多只可以上传2M的图片！"),1)),appDetail(e){this.Form=JSON.parse(JSON.stringify(e)),this.show=!0},queryAppStatus(e=1){let t=this;this.$ajax.post("ajax.php?act=AppList",{page:e,limit:this.limit,name:""}).then((function(e){e.code>=0?t.data=e:t.$message({message:e.msg,type:"error",grouping:!0})}))},Router(e,t={}){this.$router.push({path:e,query:t})}}},F=n("div",{style:{width:"100%",height:"140px","background-repeat":"no-repeat","background-size":"100% 100%","-moz-background-size":"100% 100%",filter:"blur(3px)","background-image":"url(public/image/404.png)"}},null,-1),A=n("div",{style:{"z-index":"10",position:"absolute","margin-top":"-100px","margin-left":"calc(39% - 25px)"}},[n("img",{src:b,style:{width:"50px",height:"50px","border-radius":"50px","box-shadow":"3px 3px 12px #eee"}})],-1),_=n("span",{style:{color:"#18b566"}},"点击创建新App生成任务",-1),k={style:{"margin-top":"1em"}},w={style:{"z-index":"10",position:"absolute","margin-top":"-100px","margin-left":"calc(39% - 25px)"}},V=["src"],C={style:{"margin-top":"1em"}},T=m("打包成功"),U=m("正在打包"),$=m("打包失败"),j=m("待打包"),D=m("取消"),S=n("div",{style:{color:"red","font-size":"0.8em"}},"域名格式：baidu.com 或 https://baidu.com，提交生成后不可再修改！ ",-1),v=m("预览"),I=m("上传图片"),P=m("预览"),z=m("上传图片"),L={style:{flex:"auto"}},B=m("关闭窗口"),G={key:0},q=m("查看下载地址"),O=m("部署App下载地址"),M={key:1},H=m("同步进度"),J={key:2},N=m("重新提交"),E={key:3},K=m("提交生成"),R=m("删除App");e("default",t(x,[["render",function(e,t,a,b,x,Q){const W=l("n-ellipsis"),X=l("el-button"),Y=l("n-card"),Z=l("el-col"),ee=l("el-row"),te=l("el-input"),ae=l("el-form-item"),le=l("el-option"),oe=l("el-select"),se=l("el-form"),re=l("el-dialog"),pe=l("el-color-picker"),de=l("el-upload"),ie=l("el-alert"),ne=o("loading");return i(),s(d,null,[r(Y,{title:"我的App列表"},{default:p((()=>[r(ee,{gutter:5},{default:p((()=>[r(Z,{span:6,style:{"margin-bottom":"0.15em","margin-top":"0.15em"}},{default:p((()=>[r(Y,{title:"生成APP",style:{"text-align":"center","box-shadow":"1px 1px 4px #ccc",cursor:"pointer"},onClick:t[0]||(t[0]=e=>x.show2=!0)},{default:p((()=>[F,A,r(W,{style:{width:"160px"}},{default:p((()=>[_])),_:1}),n("div",k,[r(X,{type:"danger",size:"small"},{default:p((()=>[m("生成费用："+u(x.data.Price)+"元",1)])),_:1})])])),_:1})])),_:1}),(i(!0),s(d,null,c(x.data.data,(e=>(i(),g(Z,{span:6,style:{"margin-bottom":"0.15em","margin-top":"0.15em"}},{default:p((()=>[r(Y,{title:e.name,style:{"text-align":"center","box-shadow":"1px 1px 4px #ccc",cursor:"pointer"},onClick:t=>Q.appDetail(e)},{default:p((()=>[n("div",{style:h(["background-image:url("+x.data.ImageUrl+e.background+")",{width:"100%",height:"140px","background-repeat":"no-repeat","background-size":"100% 100%","-moz-background-size":"100% 100%",filter:"blur(3px)","background-color":"#cccccc"}])},null,4),n("div",w,[n("img",{src:x.data.ImageUrl+e.icon,style:h([{width:"50px",height:"50px","border-radius":"50px"},"box-shadow: 3px 3px 12px "+e.load_theme])},null,12,V)]),r(W,{style:{width:"160px"}},{default:p((()=>[n("span",{style:h("color:"+e.theme)},u(e.url),5)])),_:2},1024),n("div",C,[r(X,{type:"danger",size:"small"},{default:p((()=>[m("已花费:"+u(e.money-0)+"元",1)])),_:2},1024),1==e.state?(i(),g(X,{key:0,type:"success",size:"small"},{default:p((()=>[T])),_:1})):2==e.state?(i(),g(X,{key:1,type:"warning",size:"small"},{default:p((()=>[U])),_:1})):3==e.state?(i(),g(X,{key:2,type:"danger",size:"small"},{default:p((()=>[$])),_:1})):(i(),g(X,{key:3,type:"primary",size:"small"},{default:p((()=>[j])),_:1}))])])),_:2},1032,["title","onClick"])])),_:2},1024)))),256))])),_:1})])),_:1}),r(re,{modelValue:x.show2,"onUpdate:modelValue":t[5]||(t[5]=e=>x.show2=e),title:"创建新APP",width:"800px",draggable:""},{footer:p((()=>[r(X,{type:"info",onClick:t[4]||(t[4]=e=>x.show2=!1)},{default:p((()=>[D])),_:1}),r(X,{type:"success",onClick:Q.AppCreate,style:{"margin-left":"1em"}},{default:p((()=>[m("点击花费"+u(x.data.Price)+"元创建",1)])),_:1},8,["onClick"])])),default:p((()=>[y((i(),g(se,{model:x.GreatField,"label-width":"100px"},{default:p((()=>[r(ae,{label:"APP生成费用"},{default:p((()=>[r(te,{value:x.data.Price+"元",disabled:""},null,8,["value"])])),_:1}),r(ae,{label:"新APP的名称"},{default:p((()=>[r(te,{modelValue:x.GreatField.name,"onUpdate:modelValue":t[1]||(t[1]=e=>x.GreatField.name=e),placeholder:"请输入新APP的名称，创建后可以修改！"},null,8,["modelValue"])])),_:1}),r(ae,{label:"您的网站域名"},{default:p((()=>[r(oe,{modelValue:x.type,"onUpdate:modelValue":t[2]||(t[2]=e=>x.type=e),placeholder:"请选择App封装域名获取方式",style:{width:"100%"}},{default:p((()=>[r(le,{label:"使用你自己的店铺域名[推荐]",value:1}),r(le,{label:"使用自定义域名",value:2})])),_:1},8,["modelValue"])])),_:1}),1!==x.type?(i(),g(ae,{key:0,label:"自定义域名"},{default:p((()=>[r(te,{modelValue:x.GreatField.url,"onUpdate:modelValue":t[3]||(t[3]=e=>x.GreatField.url=e),placeholder:"请输入您需要打包为APP的网站域名，创建后可以修改！"},null,8,["modelValue"])])),_:1})):f("",!0)])),_:1},8,["model"])),[[ne,x.Load]])])),_:1},8,["modelValue"]),r(re,{modelValue:x.show,"onUpdate:modelValue":t[29]||(t[29]=e=>x.show=e),title:"App封装配置 - "+x.Form.name,width:"800px",draggable:""},{footer:p((()=>[n("div",L,[r(X,{type:"info",onClick:t[22]||(t[22]=e=>x.show=!1)},{default:p((()=>[B])),_:1}),1==x.Form.state?(i(),s("span",G,[r(X,{type:"primary",style:{"margin-left":"1em"},onClick:t[23]||(t[23]=e=>Q.AppDownload(x.Form.id,1))},{default:p((()=>[q])),_:1}),r(X,{type:"success",style:{"margin-left":"1em"},onClick:t[24]||(t[24]=e=>Q.AppDownload(x.Form.id,2))},{default:p((()=>[O])),_:1})])):2==x.Form.state?(i(),s("span",M,[r(X,{type:"warning",onClick:t[25]||(t[25]=e=>Q.AppCalibration(x.Form.id)),style:{"margin-left":"1em"}},{default:p((()=>[H])),_:1})])):3==x.Form.state?(i(),s("span",J,[r(X,{onClick:t[26]||(t[26]=e=>Q.AppSubmit(x.Form.id)),type:"primary",style:{"margin-left":"1em"}},{default:p((()=>[N])),_:1})])):(i(),s("span",E,[r(X,{onClick:t[27]||(t[27]=e=>Q.AppSubmit(x.Form.id)),type:"success",style:{"margin-left":"1em"}},{default:p((()=>[K])),_:1})])),r(X,{onClick:t[28]||(t[28]=e=>Q.AppDelete(x.Form.id)),type:"danger",style:{"margin-left":"1em"}},{default:p((()=>[R])),_:1})])])),default:p((()=>{var e;return[y((i(),g(se,{model:x.Form,"label-width":"100px"},{default:p((()=>[r(ae,{label:"App名称"},{default:p((()=>[r(te,{onChange:t[6]||(t[6]=e=>Q.adjustmentValue("name")),disabled:-1!=x.Form.TaskID,modelValue:x.Form.name,"onUpdate:modelValue":t[7]||(t[7]=e=>x.Form.name=e),placeholder:"App名称"},null,8,["disabled","modelValue"])])),_:1}),r(ae,{label:"封装域名"},{default:p((()=>[r(te,{onChange:t[8]||(t[8]=e=>Q.adjustmentValue("url")),modelValue:x.Form.url,"onUpdate:modelValue":t[9]||(t[9]=e=>x.Form.url=e),disabled:-1!=x.Form.TaskID,placeholder:"请一定要填写完整你的域名，否则App打开后页面无法显示,格式:http(s)://xxxxx"},null,8,["modelValue","disabled"]),S])),_:1}),r(ae,{label:"导航颜色"},{default:p((()=>[r(pe,{onChange:t[10]||(t[10]=e=>Q.adjustmentValue("theme",1)),disabled:-1!=x.Form.TaskID,modelValue:x.Form.theme,"onUpdate:modelValue":t[11]||(t[11]=e=>x.Form.theme=e)},null,8,["disabled","modelValue"]),n("span",{style:h("color:"+x.Form.theme)},u(x.Form.theme),5)])),_:1}),r(ae,{label:"加载颜色"},{default:p((()=>[r(pe,{onChange:t[12]||(t[12]=e=>Q.adjustmentValue("load_theme",2)),disabled:-1!=x.Form.TaskID,modelValue:x.Form.load_theme,"onUpdate:modelValue":t[13]||(t[13]=e=>x.Form.load_theme=e)},null,8,["disabled","modelValue"]),n("span",{style:h("color:"+x.Form.load_theme)},u(x.Form.load_theme),5)])),_:1}),r(ae,{label:"图标ID"},{default:p((()=>[r(te,{modelValue:x.Form.icon,"onUpdate:modelValue":t[16]||(t[16]=e=>x.Form.icon=e),disabled:"",placeholder:"网站图标ID"},{prepend:p((()=>[r(X,{onClick:t[14]||(t[14]=e=>Q.ImageOpen(x.Form.icon))},{default:p((()=>[v])),_:1})])),append:p((()=>[r(de,{disabled:-1!=x.Form.TaskID,action:"ajax.php?act=AppUploading","show-file-list":!1,"on-success":Q.UploadedSuccessfully,"before-upload":Q.beforeAvatarUpload,data:{id:x.Form.id,type:1}},{default:p((()=>[r(X,{onClick:t[15]||(t[15]=e=>x.Types="icon")},{default:p((()=>[I])),_:1})])),_:1},8,["disabled","on-success","before-upload","data"])])),_:1},8,["modelValue"])])),_:1}),r(ae,{label:"启动图ID"},{default:p((()=>[r(te,{modelValue:x.Form.background,"onUpdate:modelValue":t[19]||(t[19]=e=>x.Form.background=e),disabled:"",placeholder:"网站背景图ID"},{prepend:p((()=>[r(X,{onClick:t[17]||(t[17]=e=>Q.ImageOpen(x.Form.background))},{default:p((()=>[P])),_:1})])),append:p((()=>[r(de,{disabled:-1!=x.Form.TaskID,action:"ajax.php?act=AppUploading","show-file-list":!1,"on-success":Q.UploadedSuccessfully,"before-upload":Q.beforeAvatarUpload,data:{id:x.Form.id,type:2}},{default:p((()=>[r(X,{onClick:t[18]||(t[18]=e=>x.Types="background")},{default:p((()=>[z])),_:1})])),_:1},8,["disabled","on-success","before-upload","data"])])),_:1},8,["modelValue"])])),_:1}),r(ae,{label:"App介绍"},{default:p((()=>[r(te,{onChange:t[20]||(t[20]=e=>Q.adjustmentValue("content")),modelValue:x.Form.content,"onUpdate:modelValue":t[21]||(t[21]=e=>x.Form.content=e),type:"textarea",placeholder:"App介绍内容,用户下载App的时候可以看到"},null,8,["modelValue"])])),_:1})])),_:1},8,["model"])),[[ne,x.Loads[x.Form.id]]]),r(ie,{title:"状态",description:null!==(e=x.Form.TaskMsg)&&void 0!==e?e:"无相关提示","show-icon":"",type:1==x.Form.state?"success":2==x.Form.state?"warning":3==x.Form.state?"error":"info",closable:!1,style:{"margin-top":"1em"}},null,8,["description","type"]),r(ie,{description:"任务提交生成后，除了App介绍外，所有内容都无法修改！想要修改只能够重新创建App生成任务！",title:"警告",type:"error","show-icon":"",style:{"margin-top":"1em"}})]})),_:1},8,["modelValue","title"])],64)}]]))}}}));
