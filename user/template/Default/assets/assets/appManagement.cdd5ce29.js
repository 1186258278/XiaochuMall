import{_ as L,E as y,r as d,h as P,c as x,d as l,w as o,F as S,o as m,f as g,e as u,t as b,j as G,a as h,n as A,i as I,b as q,D as M}from"./index.7e8edd7c.js";const N={name:"appManagement",data(){return{data:{ImageUrl:""},limit:999,show:!1,Form:{},Types:"",GreatField:{url:"",name:""},show2:!1,type:1,Load:!1,Loads:{}}},mounted(){this.queryAppStatus()},methods:{AppCreate(){if(this.GreatField.name===""){this.$message({message:"\u8BF7\u5C06App\u540D\u79F0\u586B\u5199\u5B8C\u6574\uFF01",type:"error",grouping:!0});return}if(this.type==1)this.GreatField.url=-1;else if(this.GreatField.url==""){this.$message({message:"\u8BF7\u5C06\u81EA\u5B9A\u4E49\u5C01\u88C5\u57DF\u540D\u586B\u5199\u5B8C\u6574\uFF01",type:"error",grouping:!0});return}this.Load=!0;let s=this;y.confirm("\u786E\u5B9A\u8981\u521B\u5EFAAPP\u5417\uFF1F","\u6E29\u99A8\u63D0\u793A",{confirmButtonText:"\u786E\u8BA4\u521B\u5EFA",cancelButtonText:"\u53D6\u6D88",type:"warning"}).then(()=>{s.$ajax.post("ajax.php?act=AppAdd",s.GreatField).then(function(e){s.Load=!1,s.show2=!1,e.code>=0?(s.$message({message:e.msg,type:"success",grouping:!0}),s.queryAppStatus()):s.$message({message:e.msg,type:"error",grouping:!0})})}).catch(()=>{s.Load=!1})},AppDownload(s,e=1){let r=this;y.confirm("\u662F\u5426\u8981"+(e===1?"\u9884\u89C8\u5BA2\u6237\u7AEF\u4E0B\u8F7D\u5730\u5740\uFF1F":"\u5C06\u5BA2\u6237\u7AEF\u4E0B\u8F7D\u5730\u5740\u90E8\u7F72\u5230\u5E97\u94FA\uFF1F"),"\u6E29\u99A8\u63D0\u793A",{confirmButtonText:"\u786E\u5B9A",cancelButtonText:"\u53D6\u6D88",type:"warning"}).then(()=>{r.Loads[s]=!0,r.$ajax.post("ajax.php?act=AppDownload",{id:s,type:e}).then(f=>{r.Loads[s]=!1,f.code>=0?y.confirm(f.msg+`
`+f.url,"\u6E29\u99A8\u63D0\u793A",{confirmButtonText:"\u6253\u5F00\u5730\u5740",type:"success"}).then(()=>{open(f.url)}):r.$message({message:f.msg,type:"error",grouping:!0})})})},AppCalibration(s){let e=this;y.confirm("\u786E\u5B9A\u540C\u6B65App\u6253\u5305\u4EFB\u52A1\u8FDB\u5EA6\u5417\uFF1F","\u6E29\u99A8\u63D0\u793A",{confirmButtonText:"\u786E\u5B9A",cancelButtonText:"\u53D6\u6D88",type:"warning"}).then(()=>{e.Loads[s]=!0,e.$ajax.post("ajax.php?act=AppCalibration",{id:s}).then(r=>{e.Loads[s]=!1,r.code>=0?(e.$message({message:"\u540C\u6B65\u7ED3\u679C\uFF1A"+r.msg,type:"success",grouping:!0}),e.show=!1,e.queryAppStatus()):e.$message({message:r.msg,type:"error",grouping:!0})})})},AppSubmit(s){let e=this;y.confirm("\u786E\u5B9A\u63D0\u4EA4\u6B64\u4EFB\u52A1\u5230\u670D\u52A1\u5668\u751F\u6210\u5417\uFF1F\u63D0\u4EA4\u540E\u9664\u4E86\u4ECB\u7ECD\u5916\uFF0C\u5176\u4ED6\u53C2\u6570\u5747\u4E0D\u53EF\u4FEE\u6539\uFF01","\u6E29\u99A8\u63D0\u793A",{confirmButtonText:"\u786E\u5B9A\u63D0\u4EA4",cancelButtonText:"\u53D6\u6D88",type:"warning"}).then(()=>{e.Loads[s]=!0,e.$ajax.post("ajax.php?act=AppSubmit",{id:s}).then(r=>{e.Loads[s]=!1,r.code>=0?(e.$message({message:r.msg,type:"success",grouping:!0}),e.show=!1,e.queryAppStatus()):e.$message({message:r.msg,type:"error",grouping:!0})})})},adjustmentValue(s,e=-1){let r=this.Form;if(!r)return;if(r.TaskID!=-1&&s!=="content")return this.$message({message:"\u5DF2\u7ECF\u63D0\u4EA4\u751F\u6210\uFF0C\u65E0\u6CD5\u518D\u8FDB\u884C\u4FEE\u6539\uFF01",type:"error",grouping:!0}),!1;if(r[s]===""&&s!=="content")return this.$message({message:"\u8BF7\u586B\u5199\u5B8C\u6574\uFF01",type:"error",grouping:!0}),!1;let f="ajax.php?act=AppSet",t={id:r.id,field:s,value:r[s]};e!=-1&&(f="ajax.php?act=AppColorSet",t={id:r.id,color:r[s],type:e});let a=this;this.$ajax.post(f,t).then(F=>{F.code>=0?(a.$message({message:F.msg,type:"success",grouping:!0}),a.queryAppStatus()):a.$message({message:"\u8BF7\u586B\u5199\u5B8C\u6574\uFF01",type:"error",grouping:!0})})},AppDelete(s){let e=this;y.confirm("\u786E\u5B9A\u8981\u5220\u9664\u5417\uFF1F\u5220\u9664\u540E\u65E0\u6CD5\u6062\u590D\uFF0C\u5E76\u4E14\u751F\u6210App\u6240\u9700\u7684\u8D39\u7528\u4E5F\u4E0D\u4F1A\u9000\u8FD8\uFF01\uFF1F","\u8B66\u544A",{confirmButtonText:"\u786E\u8BA4\u5220\u9664",cancelButtonText:"\u53D6\u6D88",type:"warning"}).then(()=>{e.$ajax.post("ajax.php?act=AppDelete",{id:s}).then(function(r){r.code>=0?(e.$message({message:r.msg,type:"success",grouping:!0}),e.show=!1,e.queryAppStatus()):e.$message({message:r.msg,type:"error",grouping:!0})})})},ImageOpen(s){if(s==""){y.alert("\u8BF7\u5148\u586B\u5199\u56FE\u7247ID","\u8B66\u544A",{confirmButtonText:"\u597D\u7684",dangerouslyUseHTMLString:!0});return}else s=this.data.ImageUrl+s;y.alert('<img src="'+s+'" width="100%" />',"\u56FE\u7247\u9884\u89C8",{confirmButtonText:"OK",dangerouslyUseHTMLString:!0})},UploadedSuccessfully(s,e){s.code>=0?(this.Form[this.Types]=s.id,this.$message({message:s.msg,type:"success",grouping:!0}),this.queryAppStatus()):this.$message({message:s.msg,type:"error",grouping:!0})},beforeAvatarUpload(s){return s.size/1024/1024>2?(alert("\u6700\u591A\u53EA\u53EF\u4EE5\u4E0A\u4F202M\u7684\u56FE\u7247\uFF01"),!1):!0},appDetail(s){this.Form=JSON.parse(JSON.stringify(s)),this.show=!0},queryAppStatus(s=1){let e=this;this.$ajax.post("ajax.php?act=AppList",{page:s,limit:this.limit,name:""}).then(function(r){r.code>=0?e.data=r:e.$message({message:r.msg,type:"error",grouping:!0})})},Router(s,e={}){this.$router.push({path:s,query:e})}}},O=g("div",{style:{width:"100%",height:"140px","background-repeat":"no-repeat","background-size":"100% 100%","-moz-background-size":"100% 100%",filter:"blur(3px)","background-image":"url(public/image/404.png)"}},null,-1),E=g("div",{style:{"z-index":"10",position:"absolute","margin-top":"-100px","margin-left":"calc(39% - 25px)"}},[g("img",{src:M,style:{width:"50px",height:"50px","border-radius":"50px","box-shadow":"3px 3px 12px #eee"}})],-1),H=g("span",{style:{color:"#18b566"}},"\u70B9\u51FB\u521B\u5EFA\u65B0App\u751F\u6210\u4EFB\u52A1",-1),J={style:{"margin-top":"1em"}},K={style:{"z-index":"10",position:"absolute","margin-top":"-100px","margin-left":"calc(39% - 25px)"}},R=["src"],Q={style:{"margin-top":"1em"}},W=u("\u6253\u5305\u6210\u529F"),X=u("\u6B63\u5728\u6253\u5305"),Y=u("\u6253\u5305\u5931\u8D25"),Z=u("\u5F85\u6253\u5305"),$=u("\u53D6\u6D88"),ee=g("div",{style:{color:"red","font-size":"0.8em"}},"\u57DF\u540D\u683C\u5F0F\uFF1Abaidu.com \u6216 https://baidu.com\uFF0C\u63D0\u4EA4\u751F\u6210\u540E\u4E0D\u53EF\u518D\u4FEE\u6539\uFF01 ",-1),te=u("\u9884\u89C8"),le=u("\u4E0A\u4F20\u56FE\u7247"),oe=u("\u9884\u89C8"),se=u("\u4E0A\u4F20\u56FE\u7247"),ne={style:{flex:"auto"}},re=u("\u5173\u95ED\u7A97\u53E3"),ae={key:0},pe=u("\u67E5\u770B\u4E0B\u8F7D\u5730\u5740"),ie=u("\u90E8\u7F72App\u4E0B\u8F7D\u5730\u5740"),ue={key:1},de=u("\u540C\u6B65\u8FDB\u5EA6"),me={key:2},ge=u("\u91CD\u65B0\u63D0\u4EA4"),ce={key:3},fe=u("\u63D0\u4EA4\u751F\u6210"),_e=u("\u5220\u9664App");function ye(s,e,r,f,t,a){const F=d("n-ellipsis"),i=d("el-button"),k=d("n-card"),w=d("el-col"),B=d("el-row"),_=d("el-input"),c=d("el-form-item"),V=d("el-option"),z=d("el-select"),v=d("el-form"),C=d("el-dialog"),T=d("el-color-picker"),D=d("el-upload"),U=d("el-alert"),j=P("loading");return m(),x(S,null,[l(k,{title:"\u6211\u7684App\u5217\u8868"},{default:o(()=>[l(B,{gutter:5},{default:o(()=>[l(w,{span:6,style:{"margin-bottom":"0.15em","margin-top":"0.15em"}},{default:o(()=>[l(k,{title:"\u751F\u6210APP",style:{"text-align":"center","box-shadow":"1px 1px 4px #ccc",cursor:"pointer"},onClick:e[0]||(e[0]=n=>t.show2=!0)},{default:o(()=>[O,E,l(F,{style:{width:"160px"}},{default:o(()=>[H]),_:1}),g("div",J,[l(i,{type:"danger",size:"small"},{default:o(()=>[u("\u751F\u6210\u8D39\u7528\uFF1A"+b(t.data.Price)+"\u5143",1)]),_:1})])]),_:1})]),_:1}),(m(!0),x(S,null,G(t.data.data,n=>(m(),h(w,{span:6,style:{"margin-bottom":"0.15em","margin-top":"0.15em"}},{default:o(()=>[l(k,{title:n.name,style:{"text-align":"center","box-shadow":"1px 1px 4px #ccc",cursor:"pointer"},onClick:p=>a.appDetail(n)},{default:o(()=>[g("div",{style:A(["background-image:url("+t.data.ImageUrl+n.background+")",{width:"100%",height:"140px","background-repeat":"no-repeat","background-size":"100% 100%","-moz-background-size":"100% 100%",filter:"blur(3px)","background-color":"#cccccc"}])},null,4),g("div",K,[g("img",{src:t.data.ImageUrl+n.icon,style:A([{width:"50px",height:"50px","border-radius":"50px"},"box-shadow: 3px 3px 12px "+n.load_theme])},null,12,R)]),l(F,{style:{width:"160px"}},{default:o(()=>[g("span",{style:A("color:"+n.theme)},b(n.url),5)]),_:2},1024),g("div",Q,[l(i,{type:"danger",size:"small"},{default:o(()=>[u("\u5DF2\u82B1\u8D39:"+b(n.money-0)+"\u5143",1)]),_:2},1024),n.state==1?(m(),h(i,{key:0,type:"success",size:"small"},{default:o(()=>[W]),_:1})):n.state==2?(m(),h(i,{key:1,type:"warning",size:"small"},{default:o(()=>[X]),_:1})):n.state==3?(m(),h(i,{key:2,type:"danger",size:"small"},{default:o(()=>[Y]),_:1})):(m(),h(i,{key:3,type:"primary",size:"small"},{default:o(()=>[Z]),_:1}))])]),_:2},1032,["title","onClick"])]),_:2},1024))),256))]),_:1})]),_:1}),l(C,{modelValue:t.show2,"onUpdate:modelValue":e[5]||(e[5]=n=>t.show2=n),title:"\u521B\u5EFA\u65B0APP",width:"800px",draggable:""},{footer:o(()=>[l(i,{type:"info",onClick:e[4]||(e[4]=n=>t.show2=!1)},{default:o(()=>[$]),_:1}),l(i,{type:"success",onClick:a.AppCreate,style:{"margin-left":"1em"}},{default:o(()=>[u("\u70B9\u51FB\u82B1\u8D39"+b(t.data.Price)+"\u5143\u521B\u5EFA",1)]),_:1},8,["onClick"])]),default:o(()=>[I((m(),h(v,{model:t.GreatField,"label-width":"100px"},{default:o(()=>[l(c,{label:"APP\u751F\u6210\u8D39\u7528"},{default:o(()=>[l(_,{value:t.data.Price+"\u5143",disabled:""},null,8,["value"])]),_:1}),l(c,{label:"\u65B0APP\u7684\u540D\u79F0"},{default:o(()=>[l(_,{modelValue:t.GreatField.name,"onUpdate:modelValue":e[1]||(e[1]=n=>t.GreatField.name=n),placeholder:"\u8BF7\u8F93\u5165\u65B0APP\u7684\u540D\u79F0\uFF0C\u521B\u5EFA\u540E\u53EF\u4EE5\u4FEE\u6539\uFF01"},null,8,["modelValue"])]),_:1}),l(c,{label:"\u60A8\u7684\u7F51\u7AD9\u57DF\u540D"},{default:o(()=>[l(z,{modelValue:t.type,"onUpdate:modelValue":e[2]||(e[2]=n=>t.type=n),placeholder:"\u8BF7\u9009\u62E9App\u5C01\u88C5\u57DF\u540D\u83B7\u53D6\u65B9\u5F0F",style:{width:"100%"}},{default:o(()=>[l(V,{label:"\u4F7F\u7528\u4F60\u81EA\u5DF1\u7684\u5E97\u94FA\u57DF\u540D[\u63A8\u8350]",value:1}),l(V,{label:"\u4F7F\u7528\u81EA\u5B9A\u4E49\u57DF\u540D",value:2})]),_:1},8,["modelValue"])]),_:1}),t.type!==1?(m(),h(c,{key:0,label:"\u81EA\u5B9A\u4E49\u57DF\u540D"},{default:o(()=>[l(_,{modelValue:t.GreatField.url,"onUpdate:modelValue":e[3]||(e[3]=n=>t.GreatField.url=n),placeholder:"\u8BF7\u8F93\u5165\u60A8\u9700\u8981\u6253\u5305\u4E3AAPP\u7684\u7F51\u7AD9\u57DF\u540D\uFF0C\u521B\u5EFA\u540E\u53EF\u4EE5\u4FEE\u6539\uFF01"},null,8,["modelValue"])]),_:1})):q("",!0)]),_:1},8,["model"])),[[j,t.Load]])]),_:1},8,["modelValue"]),l(C,{modelValue:t.show,"onUpdate:modelValue":e[29]||(e[29]=n=>t.show=n),title:"App\u5C01\u88C5\u914D\u7F6E - "+t.Form.name,width:"800px",draggable:""},{footer:o(()=>[g("div",ne,[l(i,{type:"info",onClick:e[22]||(e[22]=n=>t.show=!1)},{default:o(()=>[re]),_:1}),t.Form.state==1?(m(),x("span",ae,[l(i,{type:"primary",style:{"margin-left":"1em"},onClick:e[23]||(e[23]=n=>a.AppDownload(t.Form.id,1))},{default:o(()=>[pe]),_:1}),l(i,{type:"success",style:{"margin-left":"1em"},onClick:e[24]||(e[24]=n=>a.AppDownload(t.Form.id,2))},{default:o(()=>[ie]),_:1})])):t.Form.state==2?(m(),x("span",ue,[l(i,{type:"warning",onClick:e[25]||(e[25]=n=>a.AppCalibration(t.Form.id)),style:{"margin-left":"1em"}},{default:o(()=>[de]),_:1})])):t.Form.state==3?(m(),x("span",me,[l(i,{onClick:e[26]||(e[26]=n=>a.AppSubmit(t.Form.id)),type:"primary",style:{"margin-left":"1em"}},{default:o(()=>[ge]),_:1})])):(m(),x("span",ce,[l(i,{onClick:e[27]||(e[27]=n=>a.AppSubmit(t.Form.id)),type:"success",style:{"margin-left":"1em"}},{default:o(()=>[fe]),_:1})])),l(i,{onClick:e[28]||(e[28]=n=>a.AppDelete(t.Form.id)),type:"danger",style:{"margin-left":"1em"}},{default:o(()=>[_e]),_:1})])]),default:o(()=>{var n;return[I((m(),h(v,{model:t.Form,"label-width":"100px"},{default:o(()=>[l(c,{label:"App\u540D\u79F0"},{default:o(()=>[l(_,{onChange:e[6]||(e[6]=p=>a.adjustmentValue("name")),disabled:t.Form.TaskID!=-1,modelValue:t.Form.name,"onUpdate:modelValue":e[7]||(e[7]=p=>t.Form.name=p),placeholder:"App\u540D\u79F0"},null,8,["disabled","modelValue"])]),_:1}),l(c,{label:"\u5C01\u88C5\u57DF\u540D"},{default:o(()=>[l(_,{onChange:e[8]||(e[8]=p=>a.adjustmentValue("url")),modelValue:t.Form.url,"onUpdate:modelValue":e[9]||(e[9]=p=>t.Form.url=p),disabled:t.Form.TaskID!=-1,placeholder:"\u8BF7\u4E00\u5B9A\u8981\u586B\u5199\u5B8C\u6574\u4F60\u7684\u57DF\u540D\uFF0C\u5426\u5219App\u6253\u5F00\u540E\u9875\u9762\u65E0\u6CD5\u663E\u793A,\u683C\u5F0F:http(s)://xxxxx"},null,8,["modelValue","disabled"]),ee]),_:1}),l(c,{label:"\u5BFC\u822A\u989C\u8272"},{default:o(()=>[l(T,{onChange:e[10]||(e[10]=p=>a.adjustmentValue("theme",1)),disabled:t.Form.TaskID!=-1,modelValue:t.Form.theme,"onUpdate:modelValue":e[11]||(e[11]=p=>t.Form.theme=p)},null,8,["disabled","modelValue"]),g("span",{style:A("color:"+t.Form.theme)},b(t.Form.theme),5)]),_:1}),l(c,{label:"\u52A0\u8F7D\u989C\u8272"},{default:o(()=>[l(T,{onChange:e[12]||(e[12]=p=>a.adjustmentValue("load_theme",2)),disabled:t.Form.TaskID!=-1,modelValue:t.Form.load_theme,"onUpdate:modelValue":e[13]||(e[13]=p=>t.Form.load_theme=p)},null,8,["disabled","modelValue"]),g("span",{style:A("color:"+t.Form.load_theme)},b(t.Form.load_theme),5)]),_:1}),l(c,{label:"\u56FE\u6807ID"},{default:o(()=>[l(_,{modelValue:t.Form.icon,"onUpdate:modelValue":e[16]||(e[16]=p=>t.Form.icon=p),disabled:"",placeholder:"\u7F51\u7AD9\u56FE\u6807ID"},{prepend:o(()=>[l(i,{onClick:e[14]||(e[14]=p=>a.ImageOpen(t.Form.icon))},{default:o(()=>[te]),_:1})]),append:o(()=>[l(D,{disabled:t.Form.TaskID!=-1,action:"ajax.php?act=AppUploading","show-file-list":!1,"on-success":a.UploadedSuccessfully,"before-upload":a.beforeAvatarUpload,data:{id:t.Form.id,type:1}},{default:o(()=>[l(i,{onClick:e[15]||(e[15]=p=>t.Types="icon")},{default:o(()=>[le]),_:1})]),_:1},8,["disabled","on-success","before-upload","data"])]),_:1},8,["modelValue"])]),_:1}),l(c,{label:"\u542F\u52A8\u56FEID"},{default:o(()=>[l(_,{modelValue:t.Form.background,"onUpdate:modelValue":e[19]||(e[19]=p=>t.Form.background=p),disabled:"",placeholder:"\u7F51\u7AD9\u80CC\u666F\u56FEID"},{prepend:o(()=>[l(i,{onClick:e[17]||(e[17]=p=>a.ImageOpen(t.Form.background))},{default:o(()=>[oe]),_:1})]),append:o(()=>[l(D,{disabled:t.Form.TaskID!=-1,action:"ajax.php?act=AppUploading","show-file-list":!1,"on-success":a.UploadedSuccessfully,"before-upload":a.beforeAvatarUpload,data:{id:t.Form.id,type:2}},{default:o(()=>[l(i,{onClick:e[18]||(e[18]=p=>t.Types="background")},{default:o(()=>[se]),_:1})]),_:1},8,["disabled","on-success","before-upload","data"])]),_:1},8,["modelValue"])]),_:1}),l(c,{label:"App\u4ECB\u7ECD"},{default:o(()=>[l(_,{onChange:e[20]||(e[20]=p=>a.adjustmentValue("content")),modelValue:t.Form.content,"onUpdate:modelValue":e[21]||(e[21]=p=>t.Form.content=p),type:"textarea",placeholder:"App\u4ECB\u7ECD\u5185\u5BB9,\u7528\u6237\u4E0B\u8F7DApp\u7684\u65F6\u5019\u53EF\u4EE5\u770B\u5230"},null,8,["modelValue"])]),_:1})]),_:1},8,["model"])),[[j,t.Loads[t.Form.id]]]),l(U,{title:"\u72B6\u6001",description:(n=t.Form.TaskMsg)!=null?n:"\u65E0\u76F8\u5173\u63D0\u793A","show-icon":"",type:t.Form.state==1?"success":t.Form.state==2?"warning":t.Form.state==3?"error":"info",closable:!1,style:{"margin-top":"1em"}},null,8,["description","type"]),l(U,{description:"\u4EFB\u52A1\u63D0\u4EA4\u751F\u6210\u540E\uFF0C\u9664\u4E86App\u4ECB\u7ECD\u5916\uFF0C\u6240\u6709\u5185\u5BB9\u90FD\u65E0\u6CD5\u4FEE\u6539\uFF01\u60F3\u8981\u4FEE\u6539\u53EA\u80FD\u591F\u91CD\u65B0\u521B\u5EFAApp\u751F\u6210\u4EFB\u52A1\uFF01",title:"\u8B66\u544A",type:"error","show-icon":"",style:{"margin-top":"1em"}})]}),_:1},8,["modelValue","title"])],64)}var xe=L(N,[["render",ye]]);export{xe as default};