import{_ as N,E as g,r,c as x,f as c,d as n,w as l,F as B,o as u,a as p,t as I,b as j,j as S,e as m,k as E,l as O}from"./index.7e8edd7c.js";const P={name:"ShopDecoration",data(){return{TemplateConfigure:{domain:!1,data:{M:{},PC:{}},conf:{template:-999,template_m:-999,background:-999,banner:-999}},index:1,Banner:[],BannerSetState:!1,BannerSetState2:!1,BannerIndex:-1,content:"",Form:{}}},mounted(){this.getTemplateConfigure()},watch:{"TemplateConfigure.conf.background":{handler(t,e){if(e===-999)return!1;this.TemAjax()}},"TemplateConfigure.conf.template":{handler(t,e){if(e===-999)return!1;this.TemAjax()}},"TemplateConfigure.conf.template_m":{handler(t,e){if(e===-999)return!1;this.TemAjax()}},"TemplateConfigure.conf.banner":{handler(t,e){if(e===-999)return!1;this.TemAjax()}}},methods:{BannerIndexDel(){let t={};for(const e in this.Banner)e!=this.BannerIndex&&(t[e]=this.Banner[e]);this.Banner=t,this.BannerSet(2),this.BannerSetState=!1,this.Form={}},BannerIndexDelAll(){let t=this;g.confirm("\u786E\u8BA4\u5220\u9664\u5168\u90E8\u5417\uFF0C\u5220\u9664\u540E\u65E0\u6CD5\u6062\u590D\uFF0C\u9700\u8981\u91CD\u65B0\u7F16\u8F91?","\u8B66\u544A",{confirmButtonText:"\u6211\u5DF2\u4E86\u89E3\u540E\u679C,\u786E\u8BA4\u5220\u9664",cancelButtonText:"\u53D6\u6D88",type:"warning"}).then(()=>{t.TemplateConfigure.conf.banner=""})},BannerIndexSet(t,e){this.BannerIndex=t-0,this.Form=e,this.BannerSetState=!0},ImageOpen(t){if(t==""){g.alert("\u8BF7\u5148\u586B\u5199\u56FE\u7247\u5730\u5740","\u8B66\u544A",{confirmButtonText:"\u597D\u7684",dangerouslyUseHTMLString:!0});return}g.alert('<img src="'+t+'" width="100%" />',"\u56FE\u7247\u9884\u89C8",{confirmButtonText:"OK",dangerouslyUseHTMLString:!0})},UploadedSuccessfully(t,e){console.log(t,e),t.code==0?(this.Form.image=t.src,this.$message({message:t.msg,type:"success",grouping:!0})):this.$message({message:t.msg,type:"error",grouping:!0})},beforeAvatarUpload(t){return t.size/1024/1024>2?(alert("\u6700\u591A\u53EA\u53EF\u4EE5\u4E0A\u4F202M\u7684\u56FE\u7247\uFF01"),!1):!0},editBroadcastMap(){this.BannerIndex==-1?this.Banner[this.Banner.length]=this.Form:this.Banner[this.BannerIndex]=this.Form,this.BannerSet(2),this.BannerSetState=!1},BannerSet(t=1){if(t===1){if(this.TemplateConfigure.conf.banner=="")return this.Banner=[],[];let e=this.TemplateConfigure.conf.banner.split("|"),f={},d=0;for(const o in e){if(e[o]===""||e[o]===void 0)continue;let s=e[o].split("*");f[d]={image:s[0],url:s[1]},++d}return this.Banner=f,f}else{if(this.Banner.length===0)return this.TemplateConfigure.conf.banner="","";let e=[],f=0;for(const d in this.Banner)this.Banner[d].image===""||this.Banner[d].image===void 0||this.Banner[d].url===""||this.Banner[d].url===void 0||(e[f]=this.Banner[d].image+"*"+this.Banner[d].url,++f);return this.TemplateConfigure.conf.banner=e.join("|"),this.TemplateConfigure.conf.banner}},setTemplat(t,e){this.TemplateConfigure.conf[e]=t},TemAjax(){let t=this;this.$ajax.post("ajax.php?act=configuration_save&type=template",this.TemplateConfigure.conf).then(function(e){e.code>=0?t.getTemplateConfigure():g.alert(e.msg,"\u8B66\u544A",{confirmButtonText:"\u597D\u7684",dangerouslyUseHTMLString:!0})})},Router(t,e={}){this.$router.push({path:t,query:e})},getTemplateConfigure(){let t=this;this.$ajax.post("ajax.php?act=TemplateSettings").then(function(e){e.code>=0?(t.TemplateConfigure=e,t.content=t.TemplateConfigure.conf.banner,t.BannerSet()):g.alert(e.msg,"\u8B66\u544A",{confirmButtonText:"\u597D\u7684",dangerouslyUseHTMLString:!0})})}}},K=t=>(E("data-v-5e949a93"),t=t(),O(),t),R={style:{"background-color":"#FFFFFF",padding:"1em","margin-bottom":"0.5em"}},q=["href"],G={style:{"margin-top":"1em"}},J=m("\u5DF2\u9009\u62E9 "),Q=m("\u9009\u62E9"),W={style:{"margin-top":"1em"}},X=m("\u5DF2\u9009\u62E9 "),Y=m("\u9009\u62E9"),Z={style:{"text-align":"center","margin-top":"1em"}},$=m("\u6DFB\u52A0\u4E00\u4E2A"),ee=m("\u5FEB\u901F\u7F16\u8F91 "),te=m("\u5220\u9664\u5168\u90E8"),ne=m("\u56FE\u7247\u9884\u89C8"),le=m("\u4E0A\u4F20\u8F6E\u64AD\u56FE"),oe={style:{"text-align":"center"}},ae=m(" \u5220\u9664 "),re=K(()=>c("span",{style:{color:"#18b566"}},"\u8F93\u5165\u5FEB\u901F\u7F16\u8F91\u683C\u5F0F : \u56FE\u7247\u5730\u5740*\u8DF3\u8F6C\u94FE\u63A5|\u56FE\u7247\u5730\u5740*\u8DF3\u8F6C\u94FE\u63A5\uFF0C\u5982\uFF1A/assets/img/pay.jpg*/user",-1)),se={style:{"text-align":"center"}},ie=m(" \u4FDD\u5B58\u5DF2\u7F16\u8F91\u5185\u5BB9 "),ue=m(" \u70B9\u51FB\u521D\u59CB\u5316,\u8FD8\u539F\u9ED8\u8BA4\u914D\u7F6E ");function me(t,e,f,d,o,s){const U=r("el-page-header"),M=r("n-alert"),v=r("n-image"),i=r("el-button"),_=r("n-card"),k=r("el-col"),w=r("el-row"),y=r("n-tab-pane"),h=r("el-option"),A=r("el-select"),D=r("el-image"),z=r("n-carousel"),L=r("n-tabs"),H=r("el-upload"),b=r("el-input"),C=r("el-form-item"),V=r("el-form"),F=r("el-dialog");return u(),x(B,null,[c("div",R,[n(U,{onBack:e[0]||(e[0]=a=>s.Router("/user/shop")),content:"\u5E97\u94FA\u88C5\u4FEE"})]),n(_,{title:"\u914D\u7F6E\u9762\u677F"},{default:l(()=>[o.TemplateConfigure.domain?(u(),p(M,{key:0,title:"\u5E97\u94FA\u57DF\u540D - \u70B9\u51FB\u67E5\u770B\u6548\u679C",type:"success",style:{"margin-bottom":"0.5em"},bordered:!1},{default:l(()=>[c("a",{href:o.TemplateConfigure.domain,target:"_blank",style:{color:"#07C160","text-decoration":"none"}},I(o.TemplateConfigure.domain),9,q)]),_:1})):j("",!0),n(L,{"default-value":o.index,type:"line"},{default:l(()=>[n(y,{name:1,tab:"\u79FB\u52A8\u7AEF\u6A21\u677F\u914D\u7F6E"},{default:l(()=>[n(w,{gutter:5},{default:l(()=>[(u(!0),x(B,null,S(o.TemplateConfigure.data.M,a=>(u(),p(k,{span:6,style:{"margin-bottom":"0.15em","margin-top":"0.15em"}},{default:l(()=>[n(_,{title:a.name,style:{"text-align":"center","box-shadow":"3px 3px 12px #eee"}},{default:l(()=>[n(v,{width:"120",height:"80",src:a.image},null,8,["src"]),c("div",G,[a.index==o.TemplateConfigure.conf.template_m?(u(),p(i,{key:0,type:"success",size:"small"},{default:l(()=>[J]),_:1})):(u(),p(i,{key:1,type:"danger",onClick:T=>s.setTemplat(a.index,"template_m"),size:"small"},{default:l(()=>[Q]),_:2},1032,["onClick"]))])]),_:2},1032,["title"])]),_:2},1024))),256))]),_:1})]),_:1}),n(y,{name:2,tab:"PC\u7AEF\u6A21\u677F\u914D\u7F6E"},{default:l(()=>[n(w,{gutter:5},{default:l(()=>[(u(!0),x(B,null,S(o.TemplateConfigure.data.PC,a=>(u(),p(k,{span:6,style:{"margin-bottom":"0.15em","margin-top":"0.15em"}},{default:l(()=>[n(_,{title:a.name,style:{"text-align":"center","box-shadow":"3px 3px 12px #eee"}},{default:l(()=>[n(v,{width:"120",height:"80",src:a.image},null,8,["src"]),c("div",W,[a.index==o.TemplateConfigure.conf.template?(u(),p(i,{key:0,type:"success",size:"small"},{default:l(()=>[X]),_:1})):(u(),p(i,{key:1,type:"danger",size:"small",onClick:T=>s.setTemplat(a.index,"template")},{default:l(()=>[Y]),_:2},1032,["onClick"]))])]),_:2},1032,["title"])]),_:2},1024))),256))]),_:1})]),_:1}),n(y,{name:3,tab:"\u5176\u4ED6\u6742\u9879\u914D\u7F6E"},{default:l(()=>[n(_,{title:"\u80CC\u666F\u56FE\u7247",style:{"box-shadow":"3px 3px 12px #eee"}},{default:l(()=>[n(A,{modelValue:o.TemplateConfigure.conf.background,"onUpdate:modelValue":e[1]||(e[1]=a=>o.TemplateConfigure.conf.background=a),placeholder:"\u8BF7\u9009\u62E9\u6A21\u677F\u80CC\u666F\u56FE\u7247",style:{width:"100%"}},{default:l(()=>[n(h,{label:"\u968F\u673A\u4E8C\u6B21\u5143\u4E00",value:"1"}),n(h,{label:"\u968F\u673A\u9AD8\u6E05\u58C1\u7EB8",value:"2"}),n(h,{label:"\u968F\u673A\u4E8C\u6B21\u5143\u4E8C",value:"3"}),n(h,{label:"\u6A21\u677F\u9ED8\u8BA4\u80CC\u666F",value:"4"})]),_:1},8,["modelValue"])]),_:1}),n(_,{title:"\u6A2A\u5E45\u5E7F\u544A",style:{"box-shadow":"3px 3px 12px #eee","margin-top":"0.3em"}},{default:l(()=>[n(z,{"show-arrow":""},{default:l(()=>[(u(!0),x(B,null,S(o.Banner,(a,T)=>(u(),p(D,{onClick:de=>s.BannerIndexSet(T,a),src:a.image,class:"carousel-img"},null,8,["onClick","src"]))),256))]),_:1}),c("div",Z,[n(i,{type:"primary",onClick:e[2]||(e[2]=a=>s.BannerIndexSet(-1,{image:"",url:""}))},{default:l(()=>[$]),_:1}),n(i,{type:"success",onClick:e[3]||(e[3]=a=>{o.BannerSetState2=!0})},{default:l(()=>[ee]),_:1}),n(i,{type:"warning",onClick:s.BannerIndexDelAll},{default:l(()=>[te]),_:1},8,["onClick"])])]),_:1})]),_:1})]),_:1},8,["default-value"])]),_:1}),n(F,{modelValue:o.BannerSetState,"onUpdate:modelValue":e[9]||(e[9]=a=>o.BannerSetState=a),title:"\u8F6E\u64AD\u56FE\u8BBE\u7F6E"},{default:l(()=>[n(V,{model:o.Form,"label-position":"top"},{default:l(()=>[n(C,{label:"\u8F6E\u64AD\u56FE\u56FE\u7247\u5730\u5740"},{default:l(()=>[n(b,{modelValue:o.Form.image,"onUpdate:modelValue":e[5]||(e[5]=a=>o.Form.image=a),placeholder:"\u8F6E\u64AD\u56FE\u56FE\u7247\u5730\u5740"},{prepend:l(()=>[n(i,{onClick:e[4]||(e[4]=a=>s.ImageOpen(o.Form.image))},{default:l(()=>[ne]),_:1})]),append:l(()=>[n(H,{action:"ajax.php?act=image_up","show-file-list":!1,"on-success":s.UploadedSuccessfully,"before-upload":s.beforeAvatarUpload},{default:l(()=>[n(i,null,{default:l(()=>[le]),_:1})]),_:1},8,["on-success","before-upload"])]),_:1},8,["modelValue"])]),_:1}),n(C,{label:"\u8DF3\u8F6C\u5730\u5740",placeholder:"\u8F6E\u64AD\u56FE\u8DF3\u8F6C\u5730\u5740"},{default:l(()=>[n(b,{modelValue:o.Form.url,"onUpdate:modelValue":e[6]||(e[6]=a=>o.Form.url=a)},null,8,["modelValue"])]),_:1})]),_:1},8,["model"]),c("div",oe,[n(i,{style:{width:"45%"},type:"success",onClick:e[7]||(e[7]=a=>s.editBroadcastMap())},{default:l(()=>[m(I(o.BannerIndex==-1?"\u65B0\u589E":"\u4FDD\u5B58"),1)]),_:1}),o.BannerIndex!=-1?(u(),p(i,{key:0,style:{width:"45%"},type:"danger",onClick:e[8]||(e[8]=a=>s.BannerIndexDel())},{default:l(()=>[ae]),_:1})):j("",!0)])]),_:1},8,["modelValue"]),n(F,{modelValue:o.BannerSetState2,"onUpdate:modelValue":e[13]||(e[13]=a=>o.BannerSetState2=a),title:"\u5FEB\u901F\u7F16\u8F91\u8F6E\u64AD\u56FE"},{default:l(()=>[n(V,{"label-position":"top"},{default:l(()=>[n(C,{label:"\u8BF7\u586B\u5199\u8F6E\u64AD\u56FE\u89C4\u5219"},{default:l(()=>[n(b,{"show-word-limit":"",modelValue:o.content,"onUpdate:modelValue":e[10]||(e[10]=a=>o.content=a),type:"textarea",placeholder:"\u8BF7\u6309\u7167\u4E0B\u65B9\u89C4\u5219\u586B\u5199\uFF01"},null,8,["modelValue"]),re]),_:1})]),_:1}),c("div",se,[n(i,{style:{width:"45%"},type:"success",onClick:e[11]||(e[11]=a=>{o.TemplateConfigure.conf.banner=o.content,o.BannerSetState2=!1})},{default:l(()=>[ie]),_:1}),n(i,{style:{width:"45%"},type:"danger",onClick:e[12]||(e[12]=a=>{o.TemplateConfigure.conf.banner="/assets/img/pay.jpg*/user",o.BannerSetState2=!1})},{default:l(()=>[ue]),_:1})])]),_:1},8,["modelValue"])],64)}var ce=N(P,[["render",me],["__scopeId","data-v-5e949a93"]]);export{ce as default};