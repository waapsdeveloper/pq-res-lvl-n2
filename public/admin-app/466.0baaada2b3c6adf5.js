"use strict";(self.webpackChunkfree_version=self.webpackChunkfree_version||[]).push([[466],{7466:(G,g,o)=>{o.r(g),o.d(g,{ListCategoryModule:()=>F});var c=o(177),h=o(245),d=o(467),t=o(3953),m=o(5754),u=o(2406),f=o(5602),C=o(6879),y=o(7744);function v(a,p){1&a&&t.nrm(0,"i",14)}function k(a,p){if(1&a){const e=t.RV6();t.j41(0,"i",15),t.bIt("click",function(){t.eBV(e);const s=t.XpG().index,n=t.XpG();return t.Njj(n.deleteRow(s))}),t.k0s()}}function L(a,p){if(1&a){const e=t.RV6();t.j41(0,"tr")(1,"td")(2,"div",3)(3,"div",4)(4,"a",5),t.EFF(5),t.k0s()()()(),t.j41(6,"td")(7,"div",3)(8,"div",6),t.EFF(9),t.k0s()()(),t.j41(10,"td")(11,"div",3)(12,"div",7),t.EFF(13),t.k0s()()(),t.j41(14,"td",8)(15,"i",9),t.bIt("click",function(){const s=t.eBV(e).index,n=t.XpG();return t.Njj(n.openDetails(s))}),t.k0s(),t.DNE(16,v,1,0,"i",10)(17,k,1,0,"i",11),t.k0s(),t.j41(18,"td")(19,"div",12),t.nrm(20,"input",13),t.k0s()()()}if(2&a){const e=p.$implicit,i=t.XpG();t.R7$(5),t.JRh(e.name),t.R7$(4),t.JRh(e.category?e.category.name:""),t.R7$(4),t.JRh(e.status),t.R7$(3),t.Y8G("ngIf",i.showEdit),t.R7$(),t.Y8G("ngIf",i.showEdit)}}const _=[{path:"",component:(()=>{class a{constructor(e,i,s){this.nav=e,this.network=i,this.users=s,this.title="Categories",this.addurl="/pages/categories/add",this.search="",this.page=1,this.lastPage=-1,this.total=0,this.perpage=10,this.list=[],this.showEdit=!1,this.columns=["Name","parent category","Status"],this.initialize()}initialize(){this.getList("",1);const e=this.users.getUser();(1==e.role_id||2==e.role_id)&&(this.showEdit=!0)}getList(e="",i=1){var s=this;return(0,d.A)(function*(){let n={search:e,page:i,perpage:s.perpage};const r=yield s.network.getCategories(n);if(r.data){let l=r.data;s.page=l.current_page,s.lastPage=l.last_page,s.total=l.total,s.list=l.data}return r})()}editRow(e){}deleteRow(e){var i=this;return(0,d.A)(function*(){let s=i.list[e];s&&(yield i.network.removeCategory(s.id)),i.list.splice(e,1)})()}loadMore(){this.page<this.lastPage&&this.getList(this.search,this.page+1)}openDetails(e){this.nav.push("/pages/categories/view/"+this.list[e].id)}onChangePerPage(e){this.getList("",1)}pageChange(e){this.getList(this.search,e)}onSearch(e){console.log(e),this.search=e,this.getList(this.search,1)}static{this.\u0275fac=function(i){return new(i||a)(t.rXU(m.F),t.rXU(u.A),t.rXU(f.g))}}static{this.\u0275cmp=t.VBU({type:a,selectors:[["app-list-category"]],decls:3,vars:6,consts:[[3,"onSearch","title","addurl"],[3,"pageChange","columns","currentPage","totalPages"],[4,"ngFor","ngForOf"],[1,"d-flex"],[1,"ms-5"],[1,"text-gray-800","text-hover-primary","fs-5","fw-bold","mb-1"],[1,"text-muted","fs-7","fw-bold"],[1,"badge","badge-light-primary","fw-bold","me-auto","px-4","py-3"],[1,"text-end","d-flex","flex-row","justify-content-end","align-items-center","gap-3"],[1,"ti","ti-eye",2,"font-size","24px",3,"click"],["class","ti ti-edit","style","font-size: 24px;",4,"ngIf"],["class","ti ti-trash","style","font-size: 24px;",3,"click",4,"ngIf"],[1,"form-check","form-check-sm","form-check-custom","form-check-solid"],["type","checkbox","value","1",1,"form-check-input"],[1,"ti","ti-edit",2,"font-size","24px"],[1,"ti","ti-trash",2,"font-size","24px",3,"click"]],template:function(i,s){1&i&&(t.j41(0,"app-kt-list-page",0),t.bIt("onSearch",function(r){return s.onSearch(r)}),t.j41(1,"app-kt-app-list-page-table",1),t.bIt("pageChange",function(r){return s.pageChange(r)}),t.DNE(2,L,21,5,"tr",2),t.k0s()()),2&i&&(t.Y8G("title","Categories")("addurl","/pages/categories/add"),t.R7$(),t.Y8G("columns",s.columns)("currentPage",s.page)("totalPages",s.lastPage),t.R7$(),t.Y8G("ngForOf",s.list))},dependencies:[c.Sq,c.bT,C.E,y.h]})}}return a})(),data:{breadcrumb:"list"}}];let j=(()=>{class a{static{this.\u0275fac=function(i){return new(i||a)}}static{this.\u0275mod=t.$C({type:a})}static{this.\u0275inj=t.G2t({imports:[h.iI.forChild(_),h.iI]})}}return a})();var R=o(1407),x=o(3806);let F=(()=>{class a{static{this.\u0275fac=function(i){return new(i||a)}}static{this.\u0275mod=t.$C({type:a})}static{this.\u0275inj=t.G2t({imports:[c.MD,j,x.H,R.q]})}}return a})()}}]);