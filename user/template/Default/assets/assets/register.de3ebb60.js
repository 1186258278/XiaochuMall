import{_ as C,C as F,E as U,r as l,h as q,c as L,d as o,w as s,o as _,f as i,i as I,a as B,e as p,k,l as P}from"./index.7e8edd7c.js";import{a as R}from"./arrow-left-bold.3b628dc7.js";const S={components:{Conf:F,ArrowLeftBold:R},name:"register",data(){return{UserPanelInformation:this.$store.state.UserPanelInformation,Form:{username:"",password:"",code:""},loginChannel:{logo:"",title:"",phone:"",login:"",qqlogin:"",qqtype:!1},code_img:"ajax.php?act=VerificationCode&n=Login_res",time:1,Load:!1}},mounted(){this.UserPanelInformation?this.Router("/user"):this.LoginChannel()},methods:{registeredUser(){let t={username:this.Form.username,password:this.Form.password,qq:this.Form.qq,vercode:this.Form.code},e=this;this.Load=!0,this.$ajax.post("ajax.php?act=login_register",t).then(function(a){e.Load=!1,a.code==1?U.confirm(a.msg,"\u606D\u559C",{confirmButtonText:"\u597D\u7684",type:"success"}).then(()=>{e.LoginChannel()}):a.code==2?e.$message({message:a.msg,type:"error",grouping:!0}):(a.code==-2&&e.time++,e.$message({message:a.msg,type:"error",grouping:!0}))})},Router(t,e={}){this.$router.push({path:t,query:e})},LoginChannel(){let t=this;this.$ajax.post("ajax.php?act=UserData").then(function(e){e.code==-3?(t.loginChannel=e.data,t.$store.commit("SetUserPanelInformation",!1)):e.code==1?(t.$store.commit("SetUserPanelInformation",e),t.UserPanelInformation=e,t.Router("/user")):t.$store.commit("SetUserPanelInformation",!1)})}}},j=t=>(k("data-v-085b1a88"),t=t(),P(),t),z={class:"user_body"},V={style:{"text-align":"left",position:"absolute"}},A=p("\u514D\u8D39\u6CE8\u518C"),D=j(()=>i("div",{class:"Partition"},null,-1)),E={style:{width:"350px",margin:"auto","text-align":"left"}},N=["src"],Q=p(" \u6CE8\u518C\u5E76\u767B\u5F55 "),T={style:{"text-align":"center","margin-top":"1.3em","font-size":"1.1em","margin-bottom":"1.2em"}};function M(t,e,a,G,n,u){const f=l("ArrowLeftBold"),g=l("el-icon"),h=l("n-h2"),m=l("n-input"),c=l("n-form-item"),d=l("el-col"),v=l("el-row"),x=l("n-form"),w=l("n-button"),b=l("n-card"),y=q("loading");return _(),L("div",z,[o(b,{bordered:!1,class:"user_card"},{default:s(()=>[i("div",V,[o(g,{onClick:e[0]||(e[0]=r=>u.Router("/")),color:"#000",style:{cursor:"pointer"},size:"38"},{default:s(()=>[o(f)]),_:1})]),o(h,{style:{"font-weight":"350","margin-top":"24px","font-size":"22px","margin-bottom":"12px"}},{default:s(()=>[A]),_:1}),D,i("div",E,[o(x,{ref:"formRef",size:"large",model:n.Form,"show-label":!1},{default:s(()=>[o(c,null,{default:s(()=>[o(m,{class:"user_input",clearable:"",value:n.Form.username,"onUpdate:value":e[1]||(e[1]=r=>n.Form.username=r),placeholder:"\u8F93\u5165\u767B\u5F55\u8D26\u53F7"},null,8,["value"])]),_:1}),o(c,null,{default:s(()=>[o(m,{class:"user_input",value:n.Form.password,"onUpdate:value":e[2]||(e[2]=r=>n.Form.password=r),clearable:"",placeholder:"\u8F93\u51656\u4F4D\u4EE5\u4E0A\u7684\u5BC6\u7801"},null,8,["value"])]),_:1}),o(c,null,{default:s(()=>[o(m,{class:"user_input",clearable:"",value:n.Form.qq,"onUpdate:value":e[3]||(e[3]=r=>n.Form.qq=r),placeholder:"\u8F93\u5165QQ\u53F7\uFF0C\u7528\u4E8E\u627E\u56DE\u5BC6\u7801"},null,8,["value"])]),_:1}),o(c,null,{default:s(()=>[o(v,{gutter:1,style:{width:"100%"}},{default:s(()=>[o(d,{span:16},{default:s(()=>[o(m,{placeholder:"\u8F93\u5165\u9A8C\u8BC1\u7801",value:n.Form.code,"onUpdate:value":e[4]||(e[4]=r=>n.Form.code=r)},null,8,["value"])]),_:1}),o(d,{span:8},{default:s(()=>[i("img",{onClick:e[5]||(e[5]=r=>++n.time),class:"code_img",src:n.code_img+"&t="+n.time},null,8,N)]),_:1})]),_:1})]),_:1})]),_:1},8,["model"]),I((_(),B(w,{onClick:u.registeredUser,color:"rgba(255, 184, 30, 1)",class:"user_btn",size:"large"},{default:s(()=>[Q]),_:1},8,["onClick"])),[[y,n.Load]]),i("div",T,[i("span",{onClick:e[6]||(e[6]=r=>u.Router("/login")),style:{cursor:"pointer"}},"\u8FD4\u56DE\u5230\u767B\u5F55\u9875")])])]),_:1})])}var K=C(S,[["render",M],["__scopeId","data-v-085b1a88"]]);export{K as default};
