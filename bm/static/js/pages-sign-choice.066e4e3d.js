(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-sign-choice"],{"1cc9":function(t,e,i){"use strict";i.r(e);var n=i("6331"),o=i.n(n);for(var a in n)"default"!==a&&function(t){i.d(e,t,(function(){return n[t]}))}(a);e["default"]=o.a},"3e9e":function(t,e,i){var n=i("f89c");n.__esModule&&(n=n.default),"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var o=i("4f06").default;o("770716a5",n,!0,{sourceMap:!1,shadowMode:!1})},"59c3":function(t,e,i){"use strict";var n;i.d(e,"b",(function(){return o})),i.d(e,"c",(function(){return a})),i.d(e,"a",(function(){return n}));var o=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-uni-view",[i("v-uni-view",{staticClass:"index-head"},[i("v-uni-view",{staticClass:"index-head-top"},[i("v-uni-view",{staticClass:"index-head-l",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.$navigateBack.apply(void 0,arguments)}}},[i("v-uni-view",{staticClass:"iconfont icon-jiantou"})],1)],1)],1),i("v-uni-view",{staticClass:"registration-method-title"},[t._v("请选择比赛组别")]),i("v-uni-view",{staticClass:"registration-method-line"}),i("v-uni-view",{staticClass:"registration-method-flex"},t._l(t.groupList,(function(e,n){return i("v-uni-view",{key:n,staticClass:"registration-method",class:t.group_id==e.id?"active":"",on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.changeGroup(e.id,e.price)}}},[t._v(t._s(e.group_name))])})),1),i("v-uni-view",{staticClass:"competitor-content-button"},[i("v-uni-view",{staticClass:"syb competitor-content-button-view",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.$navigateBack.apply(void 0,arguments)}}},[t._v("上一步")]),i("v-uni-view",{staticClass:"competitor-content-button-view",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.next.apply(void 0,arguments)}}},[t._v("下一步")])],1)],1)},a=[]},6331:function(t,e,i){"use strict";(function(t){var n=i("4ea4");Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0,i("96cf");var o=n(i("1da1")),a={data:function(){return{project_id:0,group_id:0,groupList:[],price:0}},components:{},onShow:function(){0==this.project_id?uni.showModal({title:"提示",content:"请选择比赛项目",confirmText:"确定",confirmColor:"#c00000",showCancel:!1,success:function(e){e.confirm?uni.switchTab({url:"/pages/sign/index"}):e.cancel&&t.log("用户点击取消")}}):this.getGroupList()},onLoad:function(t){t&&t.project_id&&(this.project_id=t.project_id)},methods:{next:function(){uni.navigateTo({url:"/pages/sign/information?project_id="+this.project_id+"&group_id="+this.group_id+"&type=1&price="+this.price})},getGroupList:function(){var t=this;return(0,o.default)(regeneratorRuntime.mark((function e(){var i;return regeneratorRuntime.wrap((function(e){while(1)switch(e.prev=e.next){case 0:return e.next=2,t.$lib.$http.post({url:t.$lib.$urlMap.gameGroupList,data:{project_id:t.project_id},needLogin:!0});case 2:i=e.sent,1==i.code&&(t.groupList=i.data.data,t.group_id=i.data.data[0].id,t.price=i.data.data[0].price);case 4:case"end":return e.stop()}}),e)})))()},changeGroup:function(t,e){this.group_id=t,this.price=e}}};e.default=a}).call(this,i("5a52")["default"])},"8ea6":function(t,e,i){"use strict";i.r(e);var n=i("59c3"),o=i("1cc9");for(var a in o)"default"!==a&&function(t){i.d(e,t,(function(){return o[t]}))}(a);i("c9d5");var r,c=i("f0c5"),d=Object(c["a"])(o["default"],n["b"],n["c"],!1,null,"6816deec",null,!1,n["a"],r);e["default"]=d.exports},c9d5:function(t,e,i){"use strict";var n=i("3e9e"),o=i.n(n);o.a},f89c:function(t,e,i){var n=i("24fb");e=n(!1),e.push([t.i,"uni-page-body[data-v-6816deec]{background:#fff}.registration-method-title[data-v-6816deec]{font-size:28px;text-align:center;margin-top:7%}.registration-method-line[data-v-6816deec]{width:21px;height:1px;background:#e6e6e6;-webkit-transform:rotate(120deg);transform:rotate(120deg);margin:33px auto}.registration-method-flex[data-v-6816deec]{display:flex;flex-direction:column;width:82%;margin:0 auto;text-align:center}.registration-method-flex .registration-method[data-v-6816deec]{background:#fff;border:solid 1px #ececec;border-radius:50px;padding:13px 0;color:#565656;font-size:16px;margin-bottom:19px}.registration-method-flex .active[data-v-6816deec]{background:#ffdfdf;border:solid 1px red}.competitor-content-button[data-v-6816deec]{width:94%;text-align:center;color:#fff;padding:10px 0;font-size:21px;position:absolute;left:0;bottom:0;padding-left:3%;display:flex;align-items:center;justify-content:space-between;background:#fff;padding-right:3%;border-top:solid 1px #f5f5f5}.competitor-content-button-view[data-v-6816deec]{background:#c00000;font-size:17px;flex:0 0 41%;padding:12px;border-radius:50px}.syb[data-v-6816deec]{background:#fff!important;border:solid 1px #c00000;color:#c00000}body.?%PAGE?%[data-v-6816deec]{background:#fff}",""]),t.exports=e}}]);