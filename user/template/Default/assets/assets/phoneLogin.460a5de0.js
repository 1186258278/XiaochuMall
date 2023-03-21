import{_ as I,C as U,E as B,r as i,h as S,c as v,d as s,w as n,o as c,f as a,a as p,b as y,i as x,e as u,k as P,l as j}from"./index.7e8edd7c.js";import{a as z}from"./arrow-left-bold.3b628dc7.js";const R={components:{Conf:U,ArrowLeftBold:z},name:"phoneLogin",data(){return{UserPanelInformation:this.$store.state.UserPanelInformation,ConfData:this.$store.state.ConfData,Form:{phone:"",code:"",code_vs:""},loginChannel:{logo:"",title:"",phone:"",login:"",qqlogin:"",qqtype:!1},code_img:"ajax.php?act=VerificationCode&n=Login_sms_vis",time:1,code_type:!1,Load:!1}},mounted(){this.UserPanelInformation?this.Router("/user"):this.LoginChannel()},methods:{verifySimsMessage(){let o={code:this.Form.code_vs},e=this;this.Load=!0,this.$ajax.post("ajax.php?act=Send_verification_login",o).then(function(l){e.Load=!0,l.code==1?B.confirm(l.msg,"\u606D\u559C",{confirmButtonText:"\u597D\u7684",type:"success"}).then(()=>{e.LoginChannel()}):e.$message({message:l.msg,type:"error",grouping:!0})})},sendTextMessage(){let o={mobile:this.Form.phone,code:this.Form.code},e=this;this.Load=!0,this.$ajax.post("ajax.php?act=Send_verification_code_login",o).then(function(l){e.Load=!1,l.code==1?(e.$message({message:l.msg,type:"success",grouping:!0}),e.code_type=!0):(e.code_type=!1,e.$message({message:l.msg,type:"error",grouping:!0}),l.code==-2&&e.time++)})},Router(o,e={}){this.$router.push({path:o,query:e})},LoginChannel(){let o=this;this.$ajax.post("ajax.php?act=UserData").then(function(e){e.code==-3?(o.loginChannel=e.data,o.$store.commit("SetUserPanelInformation",!1)):e.code==1?(o.$store.commit("SetUserPanelInformation",e),o.UserPanelInformation=e,o.Router("/user")):(o.$store.commit("SetUserPanelInformation",!1),o.$message({message:e.msg,type:"error",grouping:!0}))})}}},q=o=>(P("data-v-7d922dd1"),o=o(),j(),o),D={class:"user_body"},M={style:{"text-align":"left",position:"absolute"}},V={style:{"margin-top":"36px"}},N=["src"],T=u("\u624B\u673A\u53F7\u767B\u5F55/\u6CE8\u518C"),A=q(()=>a("div",{class:"Partition"},null,-1)),E={style:{width:"350px",margin:"auto","text-align":"left"}},G={key:1,style:{"margin-bottom":"2em"}},H=["src"],J=u(" \u53D1\u9001\u9A8C\u8BC1\u7801 "),K=u(" \u70B9\u51FB\u9A8C\u8BC1\u77ED\u4FE1\u9A8C\u8BC1\u7801 "),O={style:{"text-align":"center","margin-top":"1.3em","font-size":"1.1em","margin-bottom":"1.2em"}};function Q(o,e,l,W,t,_){const C=i("ArrowLeftBold"),L=i("el-icon"),b=i("n-h2"),d=i("n-input"),m=i("n-form-item"),f=i("el-col"),k=i("el-row"),w=i("n-form"),g=i("n-button"),F=i("n-card"),h=S("loading");return c(),v("div",D,[s(F,{bordered:!1,class:"user_card"},{default:n(()=>[a("div",M,[s(L,{onClick:e[0]||(e[0]=r=>_.Router("/")),color:"#000",style:{cursor:"pointer"},size:"38"},{default:n(()=>[s(C)]),_:1})]),a("div",V,[a("img",{src:t.loginChannel.logo,style:{height:"56px"}},null,8,N)]),s(b,{style:{"font-weight":"350","margin-top":"24px","font-size":"22px","margin-bottom":"12px"}},{default:n(()=>[T]),_:1}),A,a("div",E,[s(w,{ref:"formRef",size:"large",model:t.Form,"show-label":!1},{default:n(()=>[s(m,null,{default:n(()=>[s(d,{class:"user_input",clearable:"",value:t.Form.phone,"onUpdate:value":e[1]||(e[1]=r=>t.Form.phone=r),placeholder:"\u8F93\u5165\u624B\u673A\u53F7"},null,8,["value"])]),_:1}),t.code_type?(c(),p(m,{key:0},{default:n(()=>[s(d,{class:"user_input",clearable:"",value:t.Form.code_vs,"onUpdate:value":e[2]||(e[2]=r=>t.Form.code_vs=r),placeholder:"\u8BF7\u8F93\u5165\u9A8C\u8BC1\u7801"},null,8,["value"])]),_:1})):y("",!0),t.code_type?y("",!0):(c(),v("div",G,[s(m,null,{default:n(()=>[s(k,{gutter:1,style:{width:"100%"}},{default:n(()=>[s(f,{span:16},{default:n(()=>[s(d,{placeholder:"\u8F93\u5165\u9A8C\u8BC1\u7801",value:t.Form.code,"onUpdate:value":e[3]||(e[3]=r=>t.Form.code=r)},null,8,["value"])]),_:1}),s(f,{span:8},{default:n(()=>[a("img",{onClick:e[4]||(e[4]=r=>++t.time),class:"code_img",src:t.code_img+"&t="+t.time},null,8,H)]),_:1})]),_:1})]),_:1})]))]),_:1},8,["model"]),t.code_type?x((c(),p(g,{key:1,onClick:_.verifySimsMessage,color:"rgba(255, 184, 30, 1)",class:"user_btn",size:"large"},{default:n(()=>[K]),_:1},8,["onClick"])),[[h,t.Load]]):x((c(),p(g,{key:0,onClick:_.sendTextMessage,color:"rgba(255, 184, 30, 1)",class:"user_btn",size:"large"},{default:n(()=>[J]),_:1},8,["onClick"])),[[h,t.Load]]),a("div",O,[a("span",{onClick:e[5]||(e[5]=r=>_.Router("/login")),style:{cursor:"pointer"}},"\u8FD4\u56DE\u5230\u767B\u5F55\u9875")])])]),_:1})])}var Z=I(R,[["render",Q],["__scopeId","data-v-7d922dd1"]]);export{Z as default};