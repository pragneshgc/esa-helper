import{_ as o,o as i,c as l,O as r,P as _,b as e,F as c,J as n,C as s,s as m}from"./app.js";const f={props:{data:{Type:[String,Array],default:()=>{}},customTotal:{Type:[String],default:"0"},customPaginate:{Type:[String,Array],default:()=>{}},loading:{Type:[Boolean],default:!0}},methods:{changePage(u){console.log("emitting click"),this.$emit("change",u)}}},v={class:"paginator pagination"},b={class:"pagination pg-blue"},k=["disabled"],P={key:1,class:"active page-item"},y=["disabled"],C={key:0,class:"paginatorInfo"},h={key:1,class:"paginatorInfo"};function S(u,t,a,w,T,g){return i(),l("div",null,[r(e("div",v,[e("div",b,[e("button",{class:c(["page-item",{disabled:a.data.current_page==1||a.loading}]),disabled:a.data.current_page==1||a.loading,onClick:t[0]||(t[0]=d=>g.changePage(a.data.current_page-1))},t[6]||(t[6]=[e("a",{class:"page-link","aria-label":"Previous"},[e("i",{class:"fa fa-caret-left"})],-1)]),10,k),e("button",{class:c(["page-item",{active:a.data.current_page==1}]),onClick:t[1]||(t[1]=d=>g.changePage(1))}," 1 ",2),a.data.current_page-1!=1&&a.data.current_page!=1?(i(),l("button",{key:0,class:"page-item",onClick:t[2]||(t[2]=d=>g.changePage(a.data.current_page-1))},n(a.data.current_page-1),1)):s("v-if",!0),a.data.current_page!=1?(i(),l("button",P,n(a.data.current_page),1)):s("v-if",!0),a.data.current_page+1!=a.data.last_page&&a.data.current_page!=a.data.last_page?(i(),l("button",{key:2,class:"page-item",onClick:t[3]||(t[3]=d=>g.changePage(a.data.current_page+1))},n(a.data.current_page+1),1)):s("v-if",!0),a.data.current_page!=a.data.last_page&&a.data.last_page?(i(),l("button",{key:3,class:"page-item",onClick:t[4]||(t[4]=d=>g.changePage(a.data.last_page))},n(a.data.last_page),1)):s("v-if",!0),e("button",{class:c(["page-item",{disabled:a.data.current_page==a.data.last_page||a.loading}]),disabled:a.data.current_page==a.data.last_page||a.loading,onClick:t[5]||(t[5]=d=>g.changePage(a.data.current_page+1))},t[7]||(t[7]=[e("a",{class:"page-link","aria-label":"Next"},[e("i",{class:"fa fa-caret-right"})],-1)]),10,y)]),m(u.$slots,"paginationnumberslot")],512),[[_,a.data.to>1]]),a.data.total>1?(i(),l("div",C," Showing "+n(a.data.from)+" to "+n(a.data.to)+" of "+n(a.data.total),1)):a.customTotal&&a.customPaginate?(i(),l("div",h," Found "+n(a.customPaginate.total)+" results ",1)):s("v-if",!0)])}const x=o(f,[["render",S],["__file","D:/laragon/www/esa/esa-helper/resources/js/components/PaginationComponent.vue"]]);export{x as default};
