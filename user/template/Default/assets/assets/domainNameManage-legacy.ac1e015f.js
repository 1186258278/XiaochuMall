!function(){var e=document.createElement("style");e.innerHTML=".input-with-select .el-input-group__prepend{background-color:var(--el-fill-color-blank)}\n",document.head.appendChild(e),System.register(["./index-legacy.6abde332.js"],(function(e){"use strict";var a,t,o,i,n,l,m,r,s,d,p,u,c,D,f;return{setters:[function(e){a=e._,t=e.m,o=e.E,i=e.r,n=e.c,l=e.f,m=e.d,r=e.w,s=e.F,d=e.o,p=e.a,u=e.t,c=e.j,D=e.b,f=e.e}],execute:function(){const y={name:"domainNameManage",data:()=>({DomainData:{Price:0,DomainList:[],Domain:!1,Blacklist:[],url:"",f_url:!1,Type:1,ssl:"http://"},prefix:"",domain:""}),mounted(){this.DomainDataGet()},methods:{Open(e){open(e)},configurePrefix(){if(""==this.prefix)return void t({type:"error",message:"请将信息填写完整！"});for(const a in this.DomainData.Blacklist)if(this.DomainData.Blacklist[a]==this.prefix)return void t({type:"error",message:"前缀"+this.prefix+"无法配置，此前缀已被管理员设置为黑名单！"});let e=this;o.confirm("确认要修改域名配置信息吗？","温馨提示",{confirmButtonText:"确认",cancelButtonText:"取消",type:"warning"}).then((()=>{this.$ajax.post("ajax.php?act=configuration_save&type=domain",{domain:e.domain,prefix:e.prefix}).then((function(a){a.code>=0?(o.alert(a.msg,"恭喜",{confirmButtonText:"OK",dangerouslyUseHTMLString:!0}),e.DomainDataGet()):e.$message({message:a.msg,type:"error",grouping:!0})}))}))},randomCoding(e=6){let a=["a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z",1,2,3,4,5,6,7,8,9,0],t="";for(let o=0;o<e;o++)t+=a[Math.floor(36*Math.random())];return this.prefix=t,!0},Router(e,a={}){this.$router.push({path:e,query:a})},copyText(e){var a=document.createElement("input");a.value=e,document.body.appendChild(a),a.select(),document.execCommand("Copy"),document.body.removeChild(a),this.$message({message:"复制成功",type:"success"})},DomainDataGet(){let e=this;this.$ajax.post("ajax.php?act=DomainData").then((function(a){if(a.code>=0){e.DomainData=a.data;try{1==a.data.Type?a.data.Domain?(e.prefix=a.data.Domain[0],""==a.data.Domain[1]?e.domain=a.data.DomainList[0]:e.domain=a.data.Domain[1]):e.domain=a.data.DomainList[0]:(e.domain=a.data.DomainList[0],a.data.Domain&&(e.prefix=a.data.Domain))}catch(t){e.domain=""}}else-2==a.code?(e.Router("/user/grade"),e.$message({message:a.msg,type:"error",grouping:!0})):e.$message({message:a.msg,type:"error",grouping:!0})}))}}},g={style:{"background-color":"#FFFFFF",padding:"1em","margin-bottom":"0.5em"}},h={key:2},x=f(" 我的店铺域名："),b=f("打开站点 "),k={key:3},_=f(" 我的店铺域名："),w=f("打开站点 "),C=f(" 我的推广域名："),v=f("打开站点 "),T=f("点击免费配置域名 "),V=f(" 必须让用户在你"),L=l("span",{style:{color:"red"}},"店铺域名",-1),j=f("内购买商品,你才可以得到商品提成奖励！ "),B=f(" 如果在你的店铺内购买商品的用户等级比你还要高，您就无法获得任何提成，所以尽量提高自己的站点等级吧 "),F=f(" 如果你店铺内的商品购买价格和用户购买商品的价格一样，中间无差价，也无法获得任何提成 所以只要你等级够高，提成就越高 "),P=f(" 用户在你店铺后台提升等级的时候，没有绑定其他上级，上级就是你，您就可以获得Ta的升级提成 "),$=f(" 提升等级的用户上级已经绑定了，并且不是你，但是他又跑到你的网站来提升等级了，这种情况下，你还是可以获得提成奖励！只不过是和他的上级一人一半对分！ "),M=f(" 这个提升等级的用户是从主站注册的，并且没有任何上级，直接跑到你加盟站提升等级，这种情况下，您也可以获得Ta的升级提成！ ");e("default",a(y,[["render",function(e,a,t,o,y,O){const U=i("el-page-header"),z=i("el-alert"),S=i("el-option"),E=i("el-select"),G=i("el-input"),R=i("el-button"),q=i("n-alert"),H=i("n-card");return d(),n(s,null,[l("div",g,[m(U,{onBack:a[0]||(a[0]=e=>O.Router("/user/shop")),content:"店铺域名配置"})]),m(H,{title:"配置我的域名",style:{"margin-bottom":"0.3em"}},{default:r((()=>[y.DomainData.Domain?(d(),p(z,{key:1,title:"检测到当前已经配置过域名,每次修改将收费"+y.DomainData.Price+"元！",type:"error",style:{"margin-bottom":"1em"}},null,8,["title"])):(d(),p(z,{key:0,title:"检测到当前未配置店铺域名,初次配置免费哦，后续如果要修改，每次收费"+y.DomainData.Price+"元！",type:"success",style:{"margin-bottom":"1em"}},null,8,["title"])),1==y.DomainData.Type?(d(),n("div",h,[m(G,{modelValue:y.prefix,"onUpdate:modelValue":a[3]||(a[3]=e=>y.prefix=e),placeholder:"域名前缀",class:"input-with-select",maxlength:6},{prepend:r((()=>[l("span",{style:{cursor:"pointer"},onClick:a[1]||(a[1]=e=>O.randomCoding(6))},"点击生成随机前缀："+u(y.DomainData.ssl),1)])),append:r((()=>[m(E,{modelValue:y.domain,"onUpdate:modelValue":a[2]||(a[2]=e=>y.domain=e),placeholder:"Select",style:{width:"200px"}},{default:r((()=>[(d(!0),n(s,null,c(y.DomainData.DomainList,(e=>(d(),p(S,{label:"."+e,value:e},null,8,["label","value"])))),256))])),_:1},8,["modelValue"])])),_:1},8,["modelValue"]),y.prefix?(d(),p(q,{key:0,"show-icon":!1,style:{"margin-top":"1em"},type:"success"},{default:r((()=>[x,l("span",{title:"复制",onClick:a[4]||(a[4]=e=>O.copyText(y.DomainData.ssl+y.prefix+"."+y.domain))},u(y.DomainData.ssl)+u(y.prefix)+"."+u(y.domain),1),m(R,{plain:"",style:{"margin-left":"1em"},text:"",link:"",bg:"",onClick:a[5]||(a[5]=e=>O.Open(y.DomainData.ssl+y.prefix+"."+y.domain)),type:"primary",size:"small"},{default:r((()=>[b])),_:1})])),_:1})):D("",!0)])):(d(),n("div",k,[m(G,{modelValue:y.prefix,"onUpdate:modelValue":a[8]||(a[8]=e=>y.prefix=e),placeholder:"域名小尾巴",class:"input-with-select",maxlength:6},{prepend:r((()=>[m(E,{modelValue:y.domain,"onUpdate:modelValue":a[6]||(a[6]=e=>y.domain=e),placeholder:"Select",style:{width:"200px"}},{default:r((()=>[(d(!0),n(s,null,c(y.DomainData.DomainList,(e=>(d(),p(S,{label:y.DomainData.ssl+e+"?t=",value:e},null,8,["label","value"])))),256))])),_:1},8,["modelValue"])])),append:r((()=>[l("span",{style:{cursor:"pointer"},onClick:a[7]||(a[7]=e=>O.randomCoding(6))},"点击生成随机小尾巴")])),_:1},8,["modelValue"]),y.DomainData.Domain?(d(),p(q,{key:0,"show-icon":!1,style:{"margin-top":"1em"},type:"success"},{default:r((()=>[_,l("span",{title:"复制",onClick:a[9]||(a[9]=e=>O.copyText(y.DomainData.url+"?t="+y.DomainData.Domain))},u(y.DomainData.url)+"?t="+u(y.DomainData.Domain),1),m(R,{plain:"",style:{"margin-left":"1em"},text:"",link:"",bg:"",onClick:a[10]||(a[10]=e=>O.Open(y.DomainData.url+"?t="+y.DomainData.Domain)),type:"primary",size:"small"},{default:r((()=>[w])),_:1})])),_:1})):D("",!0)])),y.DomainData.f_url?(d(),p(q,{key:4,"show-icon":!1,style:{"margin-top":"1em"},type:"success"},{default:r((()=>[C,l("span",{title:"复制",onClick:a[11]||(a[11]=e=>O.copyText(y.DomainData.f_url))},u(y.DomainData.f_url),1),m(R,{plain:"",style:{"margin-left":"1em"},text:"",link:"",bg:"",onClick:a[12]||(a[12]=e=>O.Open(y.DomainData.f_url)),type:"primary",size:"small"},{default:r((()=>[v])),_:1})])),_:1})):D("",!0),y.DomainData.Domain?(d(),p(R,{key:6,style:{"margin-top":"1em",width:"100%",color:"#e5610d"},text:"",color:"#626aef",bg:"",onClick:a[14]||(a[14]=e=>O.configurePrefix())},{default:r((()=>[f("点击花费"+u(y.DomainData.Price)+"元修改域名 ",1)])),_:1})):(d(),p(R,{key:5,style:{"margin-top":"1em",width:"100%",color:"#18b566"},text:"",color:"#626aef",bg:"",onClick:a[13]||(a[13]=e=>O.configurePrefix())},{default:r((()=>[T])),_:1}))])),_:1}),m(H,{title:"商品提成说明",style:{"margin-bottom":"0.3em"}},{default:r((()=>[m(q,{"show-icon":!1,style:{"margin-bottom":"0.5em"}},{default:r((()=>[V,L,j])),_:1}),m(q,{"show-icon":!1,style:{"margin-bottom":"0.5em"}},{default:r((()=>[B])),_:1}),m(q,{"show-icon":!1,style:{"margin-bottom":"0.5em"}},{default:r((()=>[F])),_:1})])),_:1}),m(H,{title:"升级提成说明"},{default:r((()=>[m(q,{"show-icon":!1,style:{"margin-bottom":"0.5em"}},{default:r((()=>[P])),_:1}),m(q,{"show-icon":!1,style:{"margin-bottom":"0.5em"}},{default:r((()=>[$])),_:1}),m(q,{"show-icon":!1,style:{"margin-bottom":"0.5em"}},{default:r((()=>[M])),_:1})])),_:1})],64)}]]))}}}))}();
