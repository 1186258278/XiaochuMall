(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-user-landing-register-register"],{"1d8b":function(t,i,e){"use strict";var o=e("8a2a"),a=e.n(o);a.a},"1ef5":function(t,i,e){"use strict";e("7a82"),Object.defineProperty(i,"__esModule",{value:!0}),i.default=void 0,e("a9e3");var o={name:"u-image",props:{src:{type:String,default:""},mode:{type:String,default:"aspectFill"},width:{type:[String,Number],default:"100%"},height:{type:[String,Number],default:"auto"},shape:{type:String,default:"square"},borderRadius:{type:[String,Number],default:0},lazyLoad:{type:Boolean,default:!0},showMenuByLongpress:{type:Boolean,default:!0},loadingIcon:{type:String,default:"photo"},errorIcon:{type:String,default:"error-circle"},showLoading:{type:Boolean,default:!0},showError:{type:Boolean,default:!0},fade:{type:Boolean,default:!0},webp:{type:Boolean,default:!1},duration:{type:[String,Number],default:500},bgColor:{type:String,default:"#f3f4f6"}},data:function(){return{isError:!1,loading:!0,opacity:1,durationTime:this.duration,backgroundStyle:{}}},watch:{src:{immediate:!0,handler:function(t){t?this.isError=!1:(this.isError=!0,this.loading=!1)}}},computed:{wrapStyle:function(){var t={};return t.width=this.$u.addUnit(this.width),t.height=this.$u.addUnit(this.height),t.borderRadius="circle"==this.shape?"50%":this.$u.addUnit(this.borderRadius),t.overflow=this.borderRadius>0?"hidden":"visible",this.fade&&(t.opacity=this.opacity,t.transition="opacity ".concat(Number(this.durationTime)/1e3,"s ease-in-out")),t}},methods:{onClick:function(){this.$emit("click")},onErrorHandler:function(t){this.loading=!1,this.isError=!0,this.$emit("error",t)},onLoadHandler:function(){var t=this;if(this.loading=!1,this.isError=!1,this.$emit("load"),!this.fade)return this.removeBgColor();this.opacity=0,this.durationTime=0,setTimeout((function(){t.durationTime=t.duration,t.opacity=1,setTimeout((function(){t.removeBgColor()}),t.durationTime)}),50)},removeBgColor:function(){this.backgroundStyle={backgroundColor:"transparent"}}}};i.default=o},4617:function(t,i,e){var o=e("24fb");i=o(!1),i.push([t.i,".login-btn[data-v-da32c950]{padding:%?10?% %?70?%;margin-top:%?520?%;text-align:center}.login-function[data-v-da32c950]{overflow:auto;padding:%?20?% %?20?% %?30?% %?20?%}.login-forget[data-v-da32c950]{float:left;font-size:%?26?%;color:#999}.login-register[data-v-da32c950]{color:#666;float:right;font-size:%?26?%}.login-input uni-input[data-v-da32c950]{background:#f2f5f6;font-size:%?28?%;padding:%?10?% %?25?%;height:%?62?%;line-height:%?62?%;border-radius:%?8?%}.login-margin-b[data-v-da32c950]{margin-bottom:%?25?%}.login-input[data-v-da32c950]{padding:%?10?% %?20?%}.login-head[data-v-da32c950]{font-size:%?34?%;text-align:center;padding:%?0?% %?10?% %?55?% %?10?%}.login-card[data-v-da32c950]{background:#fff;border-radius:%?12?%;padding:%?10?% %?25?%;position:relative;margin-top:%?30?%}.login-bg[data-v-da32c950]{height:%?260?%;padding:%?25?%}.content[data-v-da32c950]{margin-bottom:%?100?%}",""]),t.exports=i},"467b":function(t,i,e){"use strict";e.r(i);var o=e("bada"),a=e("503c");for(var n in a)["default"].indexOf(n)<0&&function(t){e.d(i,t,(function(){return a[t]}))}(n);e("a3ac");var r=e("f0c5"),d=Object(r["a"])(a["default"],o["b"],o["c"],!1,null,"966fd6d8",null,!1,o["a"],void 0);i["default"]=d.exports},"503c":function(t,i,e){"use strict";e.r(i);var o=e("ea46"),a=e.n(o);for(var n in o)["default"].indexOf(n)<0&&function(t){e.d(i,t,(function(){return o[t]}))}(n);i["default"]=a.a},"53caa":function(t,i,e){var o=e("24fb");i=o(!1),i.push([t.i,"/* uni.scss */.TemCut[data-v-28c068b7]{bottom:%?300?%;right:%?40?%;border-radius:5493px;background-color:#e1e1e1;z-index:999999;opacity:.8;width:%?80?%;height:%?80?%;position:fixed;z-index:12;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:horizontal;-webkit-box-direction:normal;-webkit-flex-direction:row;flex-direction:row;flex-direction:column;-webkit-box-pack:center;-webkit-justify-content:center;justify-content:center;background-color:#e1e1e1;-webkit-box-align:center;-webkit-align-items:center;align-items:center;-webkit-transition:opacity .4s}.grid-text[data-v-28c068b7]{font-size:%?20?%;margin-top:%?8?%;color:#909399}.demo-warter[data-v-28c068b7]{border-radius:8px;margin:5px;background-color:#fff;padding:8px;position:relative;max-width:47vw}.u-close[data-v-28c068b7]{position:absolute;top:%?32?%;right:%?32?%}.demo-image[data-v-28c068b7]{width:100%;border-radius:4px}.demo-title[data-v-28c068b7]{font-size:%?30?%;margin-top:5px;color:#303133}.demo-tag[data-v-28c068b7]{display:flex;margin-top:5px}.demo-tag-owner[data-v-28c068b7]{background-color:#fa3534;color:#fff;display:flex;align-items:center;padding:%?4?% %?14?%;border-radius:%?50?%;font-size:%?20?%;line-height:1}.demo-tag-text[data-v-28c068b7]{border:1px solid #2979ff;color:#2979ff;margin-left:10px;border-radius:%?50?%;line-height:1;padding:%?4?% %?14?%;display:flex;align-items:center;border-radius:%?50?%;font-size:%?20?%}.HistoryBtn[data-v-28c068b7]{background-color:#f1f1f1;border:none!important;font-size:.5rem;margin:.2rem}.demo-price[data-v-28c068b7]{font-size:%?30?%;color:#fa3534;margin-top:5px}.demo-shop[data-v-28c068b7]{font-size:%?22?%;color:#909399;margin-top:5px}.uni-scroll-view-content[data-v-28c068b7]{height:auto!important}.jingdong[data-v-28c068b7]{width:96%;margin-left:2%;height:auto;background-color:#fff;display:flex;box-shadow:3px 3px 16px #eee;margin-bottom:1rem}.jingdong .left[data-v-28c068b7]{padding:0 %?30?%;background-color:#5f94e0;text-align:center;font-size:%?28?%;color:#fff}.jingdong .left .sum[data-v-28c068b7]{margin-top:%?50?%;font-weight:700;font-size:%?32?%}.jingdong .left .sum .num[data-v-28c068b7]{font-size:%?80?%}.jingdong .left .type[data-v-28c068b7]{margin-bottom:%?50?%;font-size:%?24?%}.jingdong .right[data-v-28c068b7]{padding:%?20?% %?20?% 0;font-size:%?28?%}.jingdong .right .top[data-v-28c068b7]{border-bottom:%?2?% dashed #e4e7ed}.jingdong .right .top .title[data-v-28c068b7]{margin-right:%?60?%;line-height:%?40?%}.jingdong .right .top .title .tag[data-v-28c068b7]{padding:%?4?% %?20?%;background-color:#499ac9;border-radius:%?20?%;color:#fff;font-weight:700;font-size:%?24?%;margin-right:%?10?%}.jingdong .right .top .bottom[data-v-28c068b7]{display:flex;margin-top:%?20?%;align-items:center;justify-content:space-between;margin-bottom:%?10?%}.jingdong .right .top .bottom .date[data-v-28c068b7]{font-size:%?20?%;flex:1}.jingdong .right .tips[data-v-28c068b7]{width:100%;line-height:%?50?%;display:flex;align-items:center;justify-content:space-between;font-size:%?24?%}.jingdong .right .tips .transpond[data-v-28c068b7]{margin-right:%?10?%}.jingdong .right .tips .explain[data-v-28c068b7]{display:flex;align-items:center}.jingdong .right .tips .particulars[data-v-28c068b7]{width:%?30?%;height:%?30?%;box-sizing:border-box;padding-top:%?8?%;border-radius:50%;background-color:#c8c9cc;text-align:center}.Countitle[data-v-28c068b7]{width:100vw;height:3rem;text-align:left;line-height:3rem;box-shadow:3px 3px 16px #ccc;font-weight:700;font-size:16px;text-indent:.5rem;overflow:hidden;white-space:nowrap;text-overflow:ellipsis}[data-v-28c068b7] .TagSize{font-size:%?20?%!important;padding:%?8?% %?12?%!important;margin-right:.5rem}[data-v-28c068b7] .TagSizeColor{background-color:#333!important;color:#ffd450!important;border:none!important}[data-v-28c068b7] .item-price{height:auto;font-size:%?30?%;text-align:center;width:100%;margin-top:%?6?%;font-weight:400}.u-image[data-v-28c068b7]{position:relative;transition:opacity .5s ease-in-out}.u-image__image[data-v-28c068b7]{width:100%;height:100%}.u-image__loading[data-v-28c068b7], .u-image__error[data-v-28c068b7]{position:absolute;top:0;left:0;width:100%;height:100%;display:flex;flex-direction:row;align-items:center;justify-content:center;background-color:#f3f4f6;color:#909399;font-size:%?46?%}",""]),t.exports=i},6006:function(t,i,e){"use strict";e.d(i,"b",(function(){return a})),e.d(i,"c",(function(){return n})),e.d(i,"a",(function(){return o}));var o={uIcon:e("0431").default},a=function(){var t=this,i=t.$createElement,e=t._self._c||i;return e("v-uni-view",{staticClass:"u-image",style:[t.wrapStyle,t.backgroundStyle],on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.onClick.apply(void 0,arguments)}}},[t.isError?t._e():e("v-uni-image",{staticClass:"u-image__image",style:{borderRadius:"circle"==t.shape?"50%":t.$u.addUnit(t.borderRadius)},attrs:{src:t.src,mode:t.mode,"lazy-load":t.lazyLoad,"show-menu-by-longpress":t.showMenuByLongpress},on:{error:function(i){arguments[0]=i=t.$handleEvent(i),t.onErrorHandler.apply(void 0,arguments)},load:function(i){arguments[0]=i=t.$handleEvent(i),t.onLoadHandler.apply(void 0,arguments)}}}),t.showLoading&&t.loading?e("v-uni-view",{staticClass:"u-image__loading",style:{borderRadius:"circle"==t.shape?"50%":t.$u.addUnit(t.borderRadius),backgroundColor:this.bgColor}},[t.$slots.loading?t._t("loading"):e("u-icon",{attrs:{name:t.loadingIcon,width:t.width,height:t.height}})],2):t._e(),t.showError&&t.isError&&!t.loading?e("v-uni-view",{staticClass:"u-image__error",style:{borderRadius:"circle"==t.shape?"50%":t.$u.addUnit(t.borderRadius)}},[t.$slots.error?t._t("error"):e("u-icon",{attrs:{name:t.errorIcon,width:t.width,height:t.height}})],2):t._e()],1)},n=[]},"69d3":function(t,i,e){"use strict";e.r(i);var o=e("6b8a"),a=e.n(o);for(var n in o)["default"].indexOf(n)<0&&function(t){e.d(i,t,(function(){return o[t]}))}(n);i["default"]=a.a},"6b8a":function(t,i,e){"use strict";e("7a82"),Object.defineProperty(i,"__esModule",{value:!0}),i.default=void 0;var o={data:function(){return{Form:{},num:1,state:!1,content:"注册成功！",code_image:""}},onLoad:function(){this.code_image=getApp().globalData.domain+"/user/ajax.php?act=VerificationCode&n=Login_res"},methods:{Open:function(){this.shows=!0},OpenPage:function(){this.$u.route({type:"reLaunch",url:"pages/user/user"})},Submit:function(){var t=this;this.state=!0,this.$u.post("?act=UserAjax&uac=login_register",this.Form).then((function(i){t.state=!1,i.code>=0?(t.$refs.uToast.show({title:i.msg,type:"success"}),t.OpenPage()):-2==i.code?(t.$refs.uToast.show({title:i.msg,type:"error"}),t.adds()):t.adds()}))},adds:function(){this.num+=1},go_register:function(){this.$u.route({type:"navigateTo",url:"pages/user/landing/login/login"})}}};i.default=o},"83a2":function(t,i,e){var o=e("8cf7");o.__esModule&&(o=o.default),"string"===typeof o&&(o=[[t.i,o,""]]),o.locals&&(t.exports=o.locals);var a=e("4f065").default;a("ef3c8cd4",o,!0,{sourceMap:!1,shadowMode:!1})},"8a2a":function(t,i,e){var o=e("4617");o.__esModule&&(o=o.default),"string"===typeof o&&(o=[[t.i,o,""]]),o.locals&&(t.exports=o.locals);var a=e("4f065").default;a("03d41212",o,!0,{sourceMap:!1,shadowMode:!1})},"8b12":function(t,i,e){"use strict";e.r(i);var o=e("1ef5"),a=e.n(o);for(var n in o)["default"].indexOf(n)<0&&function(t){e.d(i,t,(function(){return o[t]}))}(n);i["default"]=a.a},"8cf7":function(t,i,e){var o=e("24fb");i=o(!1),i.push([t.i,"/* uni.scss */.TemCut[data-v-966fd6d8]{bottom:%?300?%;right:%?40?%;border-radius:5493px;background-color:#e1e1e1;z-index:999999;opacity:.8;width:%?80?%;height:%?80?%;position:fixed;z-index:12;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:horizontal;-webkit-box-direction:normal;-webkit-flex-direction:row;flex-direction:row;flex-direction:column;-webkit-box-pack:center;-webkit-justify-content:center;justify-content:center;background-color:#e1e1e1;-webkit-box-align:center;-webkit-align-items:center;align-items:center;-webkit-transition:opacity .4s}.grid-text[data-v-966fd6d8]{font-size:%?20?%;margin-top:%?8?%;color:#909399}.demo-warter[data-v-966fd6d8]{border-radius:8px;margin:5px;background-color:#fff;padding:8px;position:relative;max-width:47vw}.u-close[data-v-966fd6d8]{position:absolute;top:%?32?%;right:%?32?%}.demo-image[data-v-966fd6d8]{width:100%;border-radius:4px}.demo-title[data-v-966fd6d8]{font-size:%?30?%;margin-top:5px;color:#303133}.demo-tag[data-v-966fd6d8]{display:flex;margin-top:5px}.demo-tag-owner[data-v-966fd6d8]{background-color:#fa3534;color:#fff;display:flex;align-items:center;padding:%?4?% %?14?%;border-radius:%?50?%;font-size:%?20?%;line-height:1}.demo-tag-text[data-v-966fd6d8]{border:1px solid #2979ff;color:#2979ff;margin-left:10px;border-radius:%?50?%;line-height:1;padding:%?4?% %?14?%;display:flex;align-items:center;border-radius:%?50?%;font-size:%?20?%}.HistoryBtn[data-v-966fd6d8]{background-color:#f1f1f1;border:none!important;font-size:.5rem;margin:.2rem}.demo-price[data-v-966fd6d8]{font-size:%?30?%;color:#fa3534;margin-top:5px}.demo-shop[data-v-966fd6d8]{font-size:%?22?%;color:#909399;margin-top:5px}.uni-scroll-view-content[data-v-966fd6d8]{height:auto!important}.jingdong[data-v-966fd6d8]{width:96%;margin-left:2%;height:auto;background-color:#fff;display:flex;box-shadow:3px 3px 16px #eee;margin-bottom:1rem}.jingdong .left[data-v-966fd6d8]{padding:0 %?30?%;background-color:#5f94e0;text-align:center;font-size:%?28?%;color:#fff}.jingdong .left .sum[data-v-966fd6d8]{margin-top:%?50?%;font-weight:700;font-size:%?32?%}.jingdong .left .sum .num[data-v-966fd6d8]{font-size:%?80?%}.jingdong .left .type[data-v-966fd6d8]{margin-bottom:%?50?%;font-size:%?24?%}.jingdong .right[data-v-966fd6d8]{padding:%?20?% %?20?% 0;font-size:%?28?%}.jingdong .right .top[data-v-966fd6d8]{border-bottom:%?2?% dashed #e4e7ed}.jingdong .right .top .title[data-v-966fd6d8]{margin-right:%?60?%;line-height:%?40?%}.jingdong .right .top .title .tag[data-v-966fd6d8]{padding:%?4?% %?20?%;background-color:#499ac9;border-radius:%?20?%;color:#fff;font-weight:700;font-size:%?24?%;margin-right:%?10?%}.jingdong .right .top .bottom[data-v-966fd6d8]{display:flex;margin-top:%?20?%;align-items:center;justify-content:space-between;margin-bottom:%?10?%}.jingdong .right .top .bottom .date[data-v-966fd6d8]{font-size:%?20?%;flex:1}.jingdong .right .tips[data-v-966fd6d8]{width:100%;line-height:%?50?%;display:flex;align-items:center;justify-content:space-between;font-size:%?24?%}.jingdong .right .tips .transpond[data-v-966fd6d8]{margin-right:%?10?%}.jingdong .right .tips .explain[data-v-966fd6d8]{display:flex;align-items:center}.jingdong .right .tips .particulars[data-v-966fd6d8]{width:%?30?%;height:%?30?%;box-sizing:border-box;padding-top:%?8?%;border-radius:50%;background-color:#c8c9cc;text-align:center}.Countitle[data-v-966fd6d8]{width:100vw;height:3rem;text-align:left;line-height:3rem;box-shadow:3px 3px 16px #ccc;font-weight:700;font-size:16px;text-indent:.5rem;overflow:hidden;white-space:nowrap;text-overflow:ellipsis}[data-v-966fd6d8] .TagSize{font-size:%?20?%!important;padding:%?8?% %?12?%!important;margin-right:.5rem}[data-v-966fd6d8] .TagSizeColor{background-color:#333!important;color:#ffd450!important;border:none!important}[data-v-966fd6d8] .item-price{height:auto;font-size:%?30?%;text-align:center;width:100%;margin-top:%?6?%;font-weight:400}.u-loading-circle[data-v-966fd6d8]{display:inline-flex;vertical-align:middle;width:%?28?%;height:%?28?%;background:0 0;border-radius:50%;border:2px solid;border-color:#e5e5e5 #e5e5e5 #e5e5e5 #8f8d8e;-webkit-animation:u-circle-data-v-966fd6d8 1s linear infinite;animation:u-circle-data-v-966fd6d8 1s linear infinite}.u-loading-flower[data-v-966fd6d8]{width:20px;height:20px;display:inline-block;vertical-align:middle;-webkit-animation:a 1s steps(12) infinite;animation:u-flower-data-v-966fd6d8 1s steps(12) infinite;background:transparent url(data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMjAiIGhlaWdodD0iMTIwIiB2aWV3Qm94PSIwIDAgMTAwIDEwMCI+PHBhdGggZmlsbD0ibm9uZSIgZD0iTTAgMGgxMDB2MTAwSDB6Ii8+PHJlY3Qgd2lkdGg9IjciIGhlaWdodD0iMjAiIHg9IjQ2LjUiIHk9IjQwIiBmaWxsPSIjRTlFOUU5IiByeD0iNSIgcnk9IjUiIHRyYW5zZm9ybT0idHJhbnNsYXRlKDAgLTMwKSIvPjxyZWN0IHdpZHRoPSI3IiBoZWlnaHQ9IjIwIiB4PSI0Ni41IiB5PSI0MCIgZmlsbD0iIzk4OTY5NyIgcng9IjUiIHJ5PSI1IiB0cmFuc2Zvcm09InJvdGF0ZSgzMCAxMDUuOTggNjUpIi8+PHJlY3Qgd2lkdGg9IjciIGhlaWdodD0iMjAiIHg9IjQ2LjUiIHk9IjQwIiBmaWxsPSIjOUI5OTlBIiByeD0iNSIgcnk9IjUiIHRyYW5zZm9ybT0icm90YXRlKDYwIDc1Ljk4IDY1KSIvPjxyZWN0IHdpZHRoPSI3IiBoZWlnaHQ9IjIwIiB4PSI0Ni41IiB5PSI0MCIgZmlsbD0iI0EzQTFBMiIgcng9IjUiIHJ5PSI1IiB0cmFuc2Zvcm09InJvdGF0ZSg5MCA2NSA2NSkiLz48cmVjdCB3aWR0aD0iNyIgaGVpZ2h0PSIyMCIgeD0iNDYuNSIgeT0iNDAiIGZpbGw9IiNBQkE5QUEiIHJ4PSI1IiByeT0iNSIgdHJhbnNmb3JtPSJyb3RhdGUoMTIwIDU4LjY2IDY1KSIvPjxyZWN0IHdpZHRoPSI3IiBoZWlnaHQ9IjIwIiB4PSI0Ni41IiB5PSI0MCIgZmlsbD0iI0IyQjJCMiIgcng9IjUiIHJ5PSI1IiB0cmFuc2Zvcm09InJvdGF0ZSgxNTAgNTQuMDIgNjUpIi8+PHJlY3Qgd2lkdGg9IjciIGhlaWdodD0iMjAiIHg9IjQ2LjUiIHk9IjQwIiBmaWxsPSIjQkFCOEI5IiByeD0iNSIgcnk9IjUiIHRyYW5zZm9ybT0icm90YXRlKDE4MCA1MCA2NSkiLz48cmVjdCB3aWR0aD0iNyIgaGVpZ2h0PSIyMCIgeD0iNDYuNSIgeT0iNDAiIGZpbGw9IiNDMkMwQzEiIHJ4PSI1IiByeT0iNSIgdHJhbnNmb3JtPSJyb3RhdGUoLTE1MCA0NS45OCA2NSkiLz48cmVjdCB3aWR0aD0iNyIgaGVpZ2h0PSIyMCIgeD0iNDYuNSIgeT0iNDAiIGZpbGw9IiNDQkNCQ0IiIHJ4PSI1IiByeT0iNSIgdHJhbnNmb3JtPSJyb3RhdGUoLTEyMCA0MS4zNCA2NSkiLz48cmVjdCB3aWR0aD0iNyIgaGVpZ2h0PSIyMCIgeD0iNDYuNSIgeT0iNDAiIGZpbGw9IiNEMkQyRDIiIHJ4PSI1IiByeT0iNSIgdHJhbnNmb3JtPSJyb3RhdGUoLTkwIDM1IDY1KSIvPjxyZWN0IHdpZHRoPSI3IiBoZWlnaHQ9IjIwIiB4PSI0Ni41IiB5PSI0MCIgZmlsbD0iI0RBREFEQSIgcng9IjUiIHJ5PSI1IiB0cmFuc2Zvcm09InJvdGF0ZSgtNjAgMjQuMDIgNjUpIi8+PHJlY3Qgd2lkdGg9IjciIGhlaWdodD0iMjAiIHg9IjQ2LjUiIHk9IjQwIiBmaWxsPSIjRTJFMkUyIiByeD0iNSIgcnk9IjUiIHRyYW5zZm9ybT0icm90YXRlKC0zMCAtNS45OCA2NSkiLz48L3N2Zz4=) no-repeat;background-size:100%}@-webkit-keyframes u-flower-data-v-966fd6d8{0%{-webkit-transform:rotate(0deg);transform:rotate(0deg)}to{-webkit-transform:rotate(1turn);transform:rotate(1turn)}}@keyframes u-flower-data-v-966fd6d8{0%{-webkit-transform:rotate(0deg);transform:rotate(0deg)}to{-webkit-transform:rotate(1turn);transform:rotate(1turn)}}@-webkit-keyframes u-circle-data-v-966fd6d8{0%{-webkit-transform:rotate(0);transform:rotate(0)}100%{-webkit-transform:rotate(1turn);transform:rotate(1turn)}}",""]),t.exports=i},"90eb":function(t,i,e){"use strict";var o=e("91ac"),a=e.n(o);a.a},"916d":function(t,i,e){"use strict";e.d(i,"b",(function(){return a})),e.d(i,"c",(function(){return n})),e.d(i,"a",(function(){return o}));var o={uInput:e("331b").default,uRow:e("1eda").default,uCol:e("6541").default,uImage:e("fbb0").default,uLoading:e("467b").default,uButton:e("5657").default,uToast:e("704b").default},a=function(){var t=this,i=t.$createElement,e=t._self._c||i;return e("v-uni-view",{staticClass:"content"},[e("v-uni-view",{staticClass:"login-bg"},[e("v-uni-view",{staticClass:"login-card"},[e("v-uni-view",{staticClass:"login-head"},[t._v("欢迎加入")]),e("v-uni-view",{staticClass:"login-input login-margin-b"},[e("u-input",{attrs:{height:90,"border-color":"#eee",focus:!0,placeholder:"请输入绑定QQ,用于头像显示",type:"text",border:!0},model:{value:t.Form.qq,callback:function(i){t.$set(t.Form,"qq",i)},expression:"Form.qq"}})],1),e("v-uni-view",{staticClass:"login-input login-margin-b"},[e("u-input",{attrs:{height:90,"border-color":"#eee",focus:!0,placeholder:"请输入登陆账号",type:"text",border:!0},model:{value:t.Form.username,callback:function(i){t.$set(t.Form,"username",i)},expression:"Form.username"}})],1),e("v-uni-view",{staticClass:"login-input login-margin-b"},[e("u-input",{attrs:{height:90,"border-color":"#eee",placeholder:"请输入登陆密码",type:"password",border:!0},model:{value:t.Form.password,callback:function(i){t.$set(t.Form,"password",i)},expression:"Form.password"}})],1),e("v-uni-view",{staticClass:"login-input"},[e("u-row",{attrs:{gutter:"1"}},[e("u-col",{attrs:{span:"6"}},[e("u-input",{attrs:{height:90,placeholder:"请输入验证码","border-color":"#eee",type:"text",border:!0},model:{value:t.Form.vercode,callback:function(i){t.$set(t.Form,"vercode",i)},expression:"Form.vercode"}})],1),e("u-col",{attrs:{span:"6"}},[e("u-image",{key:t.num,attrs:{"lazy-load":!1,width:"100%",height:90,src:t.code_image+"&t="+t.num,fade:!0,duration:"450"},on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.adds()}}},[e("v-uni-view",{staticStyle:{"font-size":"24rpx"},attrs:{slot:"error"},slot:"error"},[t._v("加载失败,点击重试")]),e("u-loading",{attrs:{slot:"loading"},slot:"loading"})],1)],1)],1)],1),e("v-uni-view",{staticClass:"login-function"},[e("v-uni-view",{staticClass:"login-register",on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.go_register.apply(void 0,arguments)}}},[t._v("快速登录")])],1)],1)],1),e("v-uni-view",{staticClass:"login-btn"},[e("u-button",{attrs:{type:"success",loading:t.state,"hair-line":!1,ripple:!0},on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.$u.throttle(t.Submit(),1e3)}}},[t._v("注册")])],1),e("u-toast",{ref:"uToast"})],1)},n=[]},"91ac":function(t,i,e){var o=e("53caa");o.__esModule&&(o=o.default),"string"===typeof o&&(o=[[t.i,o,""]]),o.locals&&(t.exports=o.locals);var a=e("4f065").default;a("0b8cfb28",o,!0,{sourceMap:!1,shadowMode:!1})},a3ac:function(t,i,e){"use strict";var o=e("83a2"),a=e.n(o);a.a},bada:function(t,i,e){"use strict";e.d(i,"b",(function(){return o})),e.d(i,"c",(function(){return a})),e.d(i,"a",(function(){}));var o=function(){var t=this.$createElement,i=this._self._c||t;return this.show?i("v-uni-view",{staticClass:"u-loading",class:"circle"==this.mode?"u-loading-circle":"u-loading-flower",style:[this.cricleStyle]}):this._e()},a=[]},ca20:function(t,i,e){"use strict";e.r(i);var o=e("916d"),a=e("69d3");for(var n in a)["default"].indexOf(n)<0&&function(t){e.d(i,t,(function(){return a[t]}))}(n);e("1d8b");var r=e("f0c5"),d=Object(r["a"])(a["default"],o["b"],o["c"],!1,null,"da32c950",null,!1,o["a"],void 0);i["default"]=d.exports},ea46:function(t,i,e){"use strict";e("7a82"),Object.defineProperty(i,"__esModule",{value:!0}),i.default=void 0,e("a9e3");var o={name:"u-loading",props:{mode:{type:String,default:"circle"},color:{type:String,default:"#c7c7c7"},size:{type:[String,Number],default:"34"},show:{type:Boolean,default:!0}},computed:{cricleStyle:function(){var t={};return t.width=this.size+"rpx",t.height=this.size+"rpx","circle"==this.mode&&(t.borderColor="#e4e4e4 #e4e4e4 #e4e4e4 ".concat(this.color?this.color:"#c7c7c7")),t}}};i.default=o},fbb0:function(t,i,e){"use strict";e.r(i);var o=e("6006"),a=e("8b12");for(var n in a)["default"].indexOf(n)<0&&function(t){e.d(i,t,(function(){return a[t]}))}(n);e("90eb");var r=e("f0c5"),d=Object(r["a"])(a["default"],o["b"],o["c"],!1,null,"28c068b7",null,!1,o["a"],void 0);i["default"]=d.exports}}]);