import{_ as r,o as d,c as l,k as o,m as _,b as e,q as c,t as n,n as s,s as m}from"./app.js";const f={props:{data:{Type:[String,Array],default:()=>{}},customTotal:{Type:[String],default:"0"},customPaginate:{Type:[String,Array],default:()=>{}},loading:{Type:[Boolean],default:!0}},methods:{changePage(u){this.$emit("click",u)}}},b={class:"paginator pagination example"},k={class:"pagination pg-blue"},v=["disabled"],y={key:1,class:"active page-item"},P=["disabled"],C={key:0,class:"paginatorInfo"},S={key:1,class:"paginatorInfo"};function T(u,t,a,h,x,g){return d(),l("div",null,[o(e("div",b,[e("div",k,[e("button",{class:c(["page-item",{disabled:a.data.current_page==1||a.loading}]),disabled:a.data.current_page==1||a.loading,onClick:t[0]||(t[0]=i=>g.changePage(a.data.current_page-1))},t[6]||(t[6]=[e("a",{class:"page-link","aria-label":"Previous"},[e("i",{class:"fa fa-caret-left"})],-1)]),10,v),e("button",{class:c(["page-item",{active:a.data.current_page==1}]),onClick:t[1]||(t[1]=i=>g.changePage(1))}," 1 ",2),a.data.current_page-1!=1&&a.data.current_page!=1?(d(),l("button",{key:0,class:"page-item",onClick:t[2]||(t[2]=i=>g.changePage(a.data.current_page-1))},n(a.data.current_page-1),1)):s("",!0),a.data.current_page!=1?(d(),l("button",y,n(a.data.current_page),1)):s("",!0),a.data.current_page+1!=a.data.last_page&&a.data.current_page!=a.data.last_page?(d(),l("button",{key:2,class:"page-item",onClick:t[3]||(t[3]=i=>g.changePage(a.data.current_page+1))},n(a.data.current_page+1),1)):s("",!0),a.data.current_page!=a.data.last_page&&a.data.last_page?(d(),l("button",{key:3,class:"page-item",onClick:t[4]||(t[4]=i=>g.changePage(a.data.last_page))},n(a.data.last_page),1)):s("",!0),e("button",{class:c(["page-item",{disabled:a.data.current_page==a.data.last_page||a.loading}]),disabled:a.data.current_page==a.data.last_page||a.loading,onClick:t[5]||(t[5]=i=>g.changePage(a.data.current_page+1))},t[7]||(t[7]=[e("a",{class:"page-link","aria-label":"Next"},[e("i",{class:"fa fa-caret-right"})],-1)]),10,P)]),m(u.$slots,"paginationnumberslot")],512),[[_,a.data.to>1]]),a.data.total>1?(d(),l("div",C," Showing "+n(a.data.from)+" to "+n(a.data.to)+" of "+n(a.data.total),1)):a.customTotal&&a.customPaginate?(d(),l("div",S," Found "+n(a.customPaginate.total)+" results ",1)):s("",!0)])}const w=r(f,[["render",T]]);export{w as default};