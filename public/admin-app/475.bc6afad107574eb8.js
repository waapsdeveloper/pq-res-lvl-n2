"use strict";(self.webpackChunkfree_version=self.webpackChunkfree_version||[]).push([[475],{4475:(G,p,o)=>{o.r(p),o.d(p,{ListInvoicesModule:()=>E});var r=o(177),u=o(245),f=o(467),t=o(3953),m=o(5754),v=o(2406),h=o(5602),g=o(6879),k=o(7744);function I(s,c){if(1&s&&(t.j41(0,"div",4)(1,"div",13)(2,"a",14),t.EFF(3),t.k0s()()()),2&s){const i=c.$implicit;t.R7$(3),t.SpI("",null==i?null:i.product_name," ")}}function F(s,c){if(1&s&&(t.j41(0,"div",4)(1,"div",13)(2,"a",14),t.EFF(3),t.k0s()()()),2&s){const i=c.$implicit;t.R7$(3),t.SpI("",null==i?null:i.quantity," ")}}function _(s,c){if(1&s&&(t.j41(0,"div",4)(1,"div",13)(2,"a",14),t.EFF(3),t.k0s()()()),2&s){const i=c.$implicit;t.R7$(3),t.SpI("$",null==i?null:i.price," ")}}function $(s,c){1&s&&t.nrm(0,"i",15)}function j(s,c){if(1&s){const i=t.RV6();t.j41(0,"i",16),t.bIt("click",function(){t.eBV(i);const n=t.XpG().index,l=t.XpG();return t.Njj(l.deleteRow(n))}),t.k0s()}}function y(s,c){if(1&s){const i=t.RV6();t.j41(0,"tr")(1,"td"),t.DNE(2,I,4,1,"div",3),t.k0s(),t.j41(3,"td"),t.DNE(4,F,4,1,"div",3),t.k0s(),t.j41(5,"td"),t.DNE(6,_,4,1,"div",3),t.k0s(),t.j41(7,"td")(8,"div",4)(9,"div",5),t.EFF(10),t.k0s()()(),t.j41(11,"td")(12,"div",4)(13,"div",6),t.EFF(14),t.k0s()()(),t.j41(15,"td",7)(16,"i",8),t.bIt("click",function(){const n=t.eBV(i).index,l=t.XpG();return t.Njj(l.openDetails(n))}),t.k0s(),t.DNE(17,$,1,0,"i",9)(18,j,1,0,"i",10),t.k0s(),t.j41(19,"td")(20,"div",11),t.nrm(21,"input",12),t.k0s()()()}if(2&s){const i=c.$implicit,e=t.XpG();t.R7$(2),t.Y8G("ngForOf",i.products),t.R7$(2),t.Y8G("ngForOf",i.products),t.R7$(2),t.Y8G("ngForOf",i.products),t.R7$(4),t.SpI("$",null==i?null:i.total_price,""),t.R7$(4),t.JRh(i.status),t.R7$(3),t.Y8G("ngIf",e.showEdit),t.R7$(),t.Y8G("ngIf",e.showEdit)}}const R=[{path:"",component:(()=>{class s{constructor(i,e,n){this.nav=i,this.network=e,this.users=n,this.title="Invoices",this.addurl="/pages/invoices/add",this.search="",this.page=1,this.lastPage=-1,this.total=0,this.perpage=10,this.list=[],this.showEdit=!1,this.columns=["Products","Quantity","Price","Total Price","Status"],this.initialize()}initialize(){this.getList("",1);const i=this.users.getUser();(1==i.role_id||2==i.role_id)&&(this.showEdit=!0)}getList(i="",e=1){var n=this;return(0,f.A)(function*(){let l={search:i,page:e,perpage:n.perpage};const d=yield n.network.getOrders(l);if(d.data){let a=d.data;n.page=a.current_page,n.lastPage=a.last_page,n.total=a.total,n.list=a.data,console.log(n.list)}return d})()}editRow(i){}deleteRow(i){var e=this;return(0,f.A)(function*(){let n=e.list[i];n&&(yield e.network.removeTable(n.id)),e.list.splice(i,1)})()}loadMore(){this.page<this.lastPage&&this.getList(this.search,this.page+1)}openDetails(i){this.nav.push("/pages/invoices/view/"+this.list[i].id)}onChangePerPage(i){this.getList("",1)}static{this.\u0275fac=function(e){return new(e||s)(t.rXU(m.F),t.rXU(v.A),t.rXU(h.g))}}static{this.\u0275cmp=t.VBU({type:s,selectors:[["app-list-invoices"]],decls:3,vars:6,consts:[[3,"title","addurl"],[3,"columns","currentPage","totalPages"],[4,"ngFor","ngForOf"],["class","d-flex",4,"ngFor","ngForOf"],[1,"d-flex"],[1,"text-muted","fs-7","fw-bold"],[1,"badge","badge-light-primary","fw-bold","me-auto","px-4","py-3"],[1,"text-end","d-flex","flex-row","justify-content-end","align-items-center","gap-3"],[1,"ti","ti-eye",2,"font-size","24px",3,"click"],["class","ti ti-edit","style","font-size: 24px;",4,"ngIf"],["class","ti ti-trash","style","font-size: 24px;",3,"click",4,"ngIf"],[1,"form-check","form-check-sm","form-check-custom","form-check-solid"],["type","checkbox","value","1",1,"form-check-input"],[1,"ms-5"],[1,"text-muted","fs-7","fw-bold","text-hover-primary","fs-5","fw-bold","mb-1"],[1,"ti","ti-edit",2,"font-size","24px"],[1,"ti","ti-trash",2,"font-size","24px",3,"click"]],template:function(e,n){1&e&&(t.j41(0,"app-kt-list-page",0)(1,"app-kt-app-list-page-table",1),t.DNE(2,y,22,7,"tr",2),t.k0s()()),2&e&&(t.Y8G("title","Invoices")("addurl","/pages/invoices/add"),t.R7$(),t.Y8G("columns",n.columns)("currentPage",n.page)("totalPages",n.total),t.R7$(),t.Y8G("ngForOf",n.list))},dependencies:[r.Sq,r.bT,g.E,k.h]})}}return s})(),data:{breadcrumb:"list"}}];let x=(()=>{class s{static{this.\u0275fac=function(e){return new(e||s)}}static{this.\u0275mod=t.$C({type:s})}static{this.\u0275inj=t.G2t({imports:[u.iI.forChild(R),u.iI]})}}return s})();var L=o(1407),C=o(3806);let E=(()=>{class s{static{this.\u0275fac=function(e){return new(e||s)}}static{this.\u0275mod=t.$C({type:s})}static{this.\u0275inj=t.G2t({imports:[r.MD,x,C.H,L.q]})}}return s})()}}]);