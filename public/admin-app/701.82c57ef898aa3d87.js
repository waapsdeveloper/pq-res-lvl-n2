"use strict";(self.webpackChunkfree_version=self.webpackChunkfree_version||[]).push([[701],{2701:(T,h,n)=>{n.r(h),n.d(h,{ListRtablesModule:()=>x});var c=n(177),d=n(245),g=n(467),t=n(3953),m=n(5754),f=n(2406),u=n(5602),v=n(6879),b=n(7744);function R(a,p){1&a&&t.nrm(0,"i",14)}function k(a,p){if(1&a){const e=t.RV6();t.j41(0,"i",15),t.bIt("click",function(){t.eBV(e);const i=t.XpG().index,o=t.XpG();return t.Njj(o.deleteRow(i))}),t.k0s()}}function C(a,p){if(1&a){const e=t.RV6();t.j41(0,"tr")(1,"td")(2,"div",3)(3,"div",4)(4,"a",5),t.EFF(5),t.k0s()()()(),t.j41(6,"td")(7,"div",3)(8,"div",6),t.EFF(9),t.k0s()()(),t.j41(10,"td")(11,"div",3)(12,"div",7),t.EFF(13),t.k0s()()(),t.j41(14,"td",8)(15,"i",9),t.bIt("click",function(){const i=t.eBV(e).index,o=t.XpG();return t.Njj(o.openDetails(i))}),t.k0s(),t.DNE(16,R,1,0,"i",10)(17,k,1,0,"i",11),t.k0s(),t.j41(18,"td")(19,"div",12),t.nrm(20,"input",13),t.k0s()()()}if(2&a){const e=p.$implicit,s=t.XpG();t.R7$(5),t.JRh(e.identifier),t.R7$(4),t.JRh(e.location),t.R7$(4),t.JRh(e.status),t.R7$(3),t.Y8G("ngIf",s.showEdit),t.R7$(),t.Y8G("ngIf",s.showEdit)}}const L=[{path:"",component:(()=>{class a{constructor(e,s,i){this.nav=e,this.network=s,this.users=i,this.title="Tables",this.addurl="/pages/tables/add",this.search="",this.page=1,this.lastPage=-1,this.total=0,this.perpage=10,this.list=[],this.showEdit=!1,this.columns=["Identifier","Location","Status"],this.initialize()}initialize(){this.getList("",1);const e=this.users.getUser();(1==e.role_id||2==e.role_id)&&(this.showEdit=!0)}getList(e="",s=1){var i=this;return(0,g.A)(function*(){let o={search:e,page:s,perpage:i.perpage};const l=yield i.network.getTables(o);if(l.data){let r=l.data;i.page=r.current_page,i.lastPage=r.last_page,i.total=r.total,i.list=r.data}return l})()}editRow(e){}deleteRow(e){var s=this;return(0,g.A)(function*(){let i=s.list[e];i&&(yield s.network.removeTable(i.id)),s.list.splice(e,1)})()}loadMore(){this.page<this.lastPage&&this.getList(this.search,this.page+1)}openDetails(e){this.nav.push("/pages/tables/view/"+this.list[e].id)}onChangePerPage(e){this.getList("",1)}pageChange(e){this.getList(this.search,e)}onSearch(e){console.log(e),this.search=e,this.getList(this.search,1)}static{this.\u0275fac=function(s){return new(s||a)(t.rXU(m.F),t.rXU(f.A),t.rXU(u.g))}}static{this.\u0275cmp=t.VBU({type:a,selectors:[["app-list-rtables"]],decls:3,vars:6,consts:[[3,"onSearch","title","addurl"],[3,"pageChange","columns","currentPage","totalPages"],[4,"ngFor","ngForOf"],[1,"d-flex"],[1,"ms-5"],[1,"text-gray-800","text-hover-primary","fs-5","fw-bold","mb-1"],[1,"text-muted","fs-7","fw-bold"],[1,"badge","badge-light-primary","fw-bold","me-auto","px-4","py-3"],[1,"text-end","d-flex","flex-row","justify-content-end","align-items-center","gap-3"],[1,"ti","ti-eye",2,"font-size","24px",3,"click"],["class","ti ti-edit","style","font-size: 24px;",4,"ngIf"],["class","ti ti-trash","style","font-size: 24px;",3,"click",4,"ngIf"],[1,"form-check","form-check-sm","form-check-custom","form-check-solid"],["type","checkbox","value","1",1,"form-check-input"],[1,"ti","ti-edit",2,"font-size","24px"],[1,"ti","ti-trash",2,"font-size","24px",3,"click"]],template:function(s,i){1&s&&(t.j41(0,"app-kt-list-page",0),t.bIt("onSearch",function(l){return i.onSearch(l)}),t.j41(1,"app-kt-app-list-page-table",1),t.bIt("pageChange",function(l){return i.pageChange(l)}),t.DNE(2,C,21,5,"tr",2),t.k0s()()),2&s&&(t.Y8G("title","Tables")("addurl","/pages/tables/add"),t.R7$(),t.Y8G("columns",i.columns)("currentPage",i.page)("totalPages",i.lastPage),t.R7$(),t.Y8G("ngForOf",i.list))},dependencies:[c.Sq,c.bT,v.E,b.h]})}}return a})(),data:{breadcrumb:"list"}}];let _=(()=>{class a{static{this.\u0275fac=function(s){return new(s||a)}}static{this.\u0275mod=t.$C({type:a})}static{this.\u0275inj=t.G2t({imports:[d.iI.forChild(L),d.iI]})}}return a})();var y=n(1407),j=n(3806);let x=(()=>{class a{static{this.\u0275fac=function(s){return new(s||a)}}static{this.\u0275mod=t.$C({type:a})}static{this.\u0275inj=t.G2t({imports:[c.MD,_,j.H,y.q]})}}return a})()}}]);