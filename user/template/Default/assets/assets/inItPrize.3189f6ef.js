import{_ as V,E as G,r as l,a as c,w as e,o as s,d as t,c as u,f as h,e as i,t as r,b as j}from"./index.7e8edd7c.js";const N={name:"inItPrize",data(){return{Data:{List:[],count:-1},limit:4,page:1}},mounted(){this.InviteDataGet()},methods:{receiveReward(m){let o=this;G.confirm("\u786E\u8BA4\u9886\u53D6\u5956\u52B1\u5417?","\u6E29\u99A8\u63D0\u793A",{confirmButtonText:"\u786E\u8BA4",cancelButtonText:"\u53D6\u6D88",type:"warning"}).then(()=>{o.$ajax.post("ajax.php?act=ReceiveAward",{id:m}).then(function(_){_.code>=0?(o.$message({message:_.msg,type:"success",grouping:!0}),o.InviteDataGet()):o.$message({message:_.msg,type:"error",grouping:!0})})})},Download(){open(this.Data.image)},InviteDataGet(m=-1){m==-1?m=this.page:this.page=m;let o=this;this.$ajax.post("ajax.php?act=InviteData",{page:m,limit:o.limit}).then(function(_){_.code>=0?o.Data=_.data:o.$message({message:_.msg,type:"error",grouping:!0})})}}},E={key:1},L={style:{"margin-top":"1em"}},R={style:{"text-align":"center","margin-top":"1em"}},T={key:1},U=["src"],P={style:{"margin-top":"1em","text-align":"center"}},A=i("\u6362\u4E00\u5F20\u9080\u8BF7\u56FE "),M=i("\u4FDD\u5B58\u9080\u8BF7\u56FE "),S={key:1},q=i("\u5DF2\u9886"),F=i("\u672A\u9886"),H=["src"],J={key:0,style:{"font-size":"0.8em",color:"#18b566"}},K={key:1,style:{"font-size":"0.8em",color:"#f4a300"}},O={key:0,style:{"font-size":"0.8em"}};function Q(m,o,_,W,n,g){const f=l("el-skeleton"),k=l("el-input"),D=l("el-form-item"),v=l("el-form"),d=l("el-button"),y=l("n-card"),w=l("el-col"),x=l("el-skeleton-item"),p=l("el-table-column"),b=l("n-ellipsis"),z=l("el-table"),C=l("el-empty"),I=l("el-pagination"),B=l("el-row");return s(),c(B,{gutter:5},{default:e(()=>[t(w,{span:24},{default:e(()=>[t(y,{title:"\u6211\u7684\u63A8\u5E7F\u4FE1\u606F",style:{"margin-bottom":"0.3em"}},{default:e(()=>[n.Data.count===-1?(s(),c(f,{key:0,animated:"",rows:2})):(s(),u("div",E,[t(v,{"label-width":"96px"},{default:e(()=>[t(D,{label:"\u6211\u7684\u9080\u8BF7\u94FE\u63A5"},{default:e(()=>[t(k,{modelValue:n.Data.Url,"onUpdate:modelValue":o[0]||(o[0]=a=>n.Data.Url=a)},null,8,["modelValue"])]),_:1})]),_:1}),h("div",L,[t(d,{type:"warning"},{default:e(()=>[i("\u9080\u8BF7\u603B\u6570\uFF1A"+r(n.Data.a1),1)]),_:1}),t(d,{type:"primary"},{default:e(()=>[i("\u5DF2\u9886\u53D6\u5956\u52B1\u6570\uFF1A"+r(n.Data.a2),1)]),_:1}),t(d,{type:"success"},{default:e(()=>[i("\u6BCF\u9080\u8BF71\u4EBA\u5956\u52B1"+r(n.Data.award)+r(n.Data.currency),1)]),_:1})])]))]),_:1})]),_:1}),t(w,{span:12},{default:e(()=>[t(y,{title:"\u6211\u7684\u63A8\u5E7F\u56FE\u7247",style:{"margin-bottom":"0.3em"}},{default:e(()=>[n.Data.count===-1?(s(),c(f,{key:0,style:{width:"100%"},animated:""},{template:e(()=>[t(x,{animated:"",variant:"image",style:{width:"100%",height:"230px",cursor:"pointer","box-shadow":"3px 3px 12px #ccc","border-radius":"6px"}}),h("div",R,[t(x,{variant:"button",style:{"margin-right":"16px",width:"30%",height:"30px"}}),t(x,{variant:"button",style:{width:"30%",height:"30px"}})])]),_:1})):(s(),u("div",T,[h("img",{src:n.Data.image,onClick:o[1]||(o[1]=a=>g.InviteDataGet(-1)),style:{width:"100%",height:"230px",cursor:"pointer","box-shadow":"3px 3px 12px #ccc","border-radius":"6px"}},null,8,U),h("div",P,[t(d,{text:"",onClick:o[2]||(o[2]=a=>g.InviteDataGet(-1)),type:"warning",bg:""},{default:e(()=>[A]),_:1}),t(d,{text:"",type:"success",onClick:g.Download,bg:""},{default:e(()=>[M]),_:1},8,["onClick"])])]))]),_:1})]),_:1}),t(w,{span:12},{default:e(()=>[t(y,{title:"\u6211\u7684\u9080\u8BF7\u5217\u8868",style:{"margin-bottom":"0.3em",height:"370px"}},{default:e(()=>[n.Data.count===-1?(s(),c(f,{key:0,animated:"",rows:7})):(s(),u("div",S,[n.Data.List.length>=1?(s(),c(z,{key:0,data:n.Data.List,stripe:"",style:{width:"100%"}},{default:e(()=>[t(p,{label:"\u9886\u53D6\u72B6\u6001"},{default:e(a=>[a.row.award==0?(s(),c(d,{key:0,size:"small",type:"danger"},{default:e(()=>[q]),_:1})):(s(),c(d,{key:1,onClick:X=>g.receiveReward(a.row.id),type:"success",size:"small"},{default:e(()=>[F]),_:2},1032,["onClick"]))]),_:1}),t(p,{label:"\u7528\u6237\u5934\u50CF"},{default:e(a=>[h("img",{src:a.row.image,style:{width:"30px",height:"30px"}},null,8,H)]),_:1}),t(p,{label:"\u7528\u6237\u540D\u79F0"},{default:e(a=>[t(b,{style:{"max-width":"70px","font-size":"0.8em"}},{default:e(()=>[i(r(a.row.name),1)]),_:2},1024)]),_:1}),t(p,{label:"\u5956\u52B1\u5185\u5BB9"},{default:e(a=>[a.row.award==0?(s(),u("span",J,"\u5DF2\u9886\u53D6")):(s(),u("span",K,r(a.row.award)+r(n.Data.currency),1))]),_:1}),t(p,{label:"\u9080\u8BF7\u65F6\u95F4"},{default:e(a=>[t(b,{style:{"max-width":"70px","font-size":"0.8em"}},{default:e(()=>[i(r(a.row.creation_time),1)]),_:2},1024)]),_:1}),t(p,{label:"\u9886\u53D6\u65F6\u95F4"},{default:e(a=>[a.row.draw_time?j("",!0):(s(),u("span",O,"\u672A\u9886\u53D6")),t(b,{style:{"max-width":"70px","font-size":"0.8em"}},{default:e(()=>[i(r(a.row.draw_time),1)]),_:2},1024)]),_:1})]),_:1},8,["data"])):(s(),c(C,{key:1,description:"\u5F53\u524D\u4E00\u4E2A\u6709\u6548\u9080\u8BF7\u90FD\u6CA1\u6709\uFF0C\u5FEB\u53BB\u901A\u8FC7\u4E0A\u65B9\u7684\u94FE\u63A5\u6765\u9080\u8BF7\u597D\u53CB\u5427"})),t(I,{onCurrentChange:g.InviteDataGet,"default-page-size":n.limit,"page-size":n.limit,style:{"margin-top":"1em"},background:"","hide-on-single-page":"",small:"",layout:"total, prev, pager, next, jumper",total:n.Data.count},null,8,["onCurrentChange","default-page-size","page-size","total"])]))]),_:1})]),_:1})]),_:1})}var Z=V(N,[["render",Q]]);export{Z as default};
