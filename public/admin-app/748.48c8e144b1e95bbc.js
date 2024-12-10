"use strict";(self.webpackChunkfree_version=self.webpackChunkfree_version||[]).push([[748],{4748:(H,_,a)=>{a.r(_),a.d(_,{AddOrdersModule:()=>q});var p=a(177),u=a(245),l=a(467),t=a(3953),k=a(5754),j=a(2406);let m=(()=>{class s{constructor(e){this.network=e,this.categories=[],this.products=[],this.order_notes="",this.selectedCategory=null,this.selected_products=[],this.totalCost=0,this.initialize()}initialize(){var e=this;return(0,l.A)(function*(){const n=yield e.network.getCategories({perpage:500,page:1});if(n.data){let o=n.data.data;console.log(o),e.categories=o}})()}updateProductsBySelectedCategory(e){var r=this;return(0,l.A)(function*(){let n={category_id:e.id,perpage:500};const o=yield r.network.getProducts(n);if(o.data){let i=o.data.data;console.log(i),r.products=i}})()}updateProductInSelectedProducts(e){var r=this;return(0,l.A)(function*(){if(e.selected){if(!r.selected_products.some(o=>o.id===e.id)){let o=Object.assign({},e);o.quantity=1,o.cost=1*o.quantity,r.selected_products.push(o)}}else r.selected_products=r.selected_products.filter(n=>n.id!==e.id);r.totalOfProductCost()})()}removeProductInSelectedProducts(e){var r=this;return(0,l.A)(function*(){r.selected_products.splice(e,1),r.totalOfProductCost()})()}totalOfProductCost(){var e=this;return(0,l.A)(function*(){let r=e.selected_products.reduce((n,o)=>n+o.quantity*o.price,0);e.totalCost=r})()}submitOrder(){var e=this;return(0,l.A)(function*(){let r=e.selected_products.map(i=>({product_id:i.id,quantity:i.quantity,price:i.price,notes:i.notes}));if(0==r.length)return!1;console.log("order submitted");let n={customer_name:"Walk-In Customer",customer_phone:"XXXXXXXX",products:r,notes:e.order_notes};const o=yield e.network.addOrder(n);return console.log(o),e.selected_products=[],!0})()}static{this.\u0275fac=function(r){return new(r||s)(t.KVO(j.A))}}static{this.\u0275prov=t.jDH({token:s,factory:s.\u0275fac,providedIn:"root"})}}return s})();var c=a(4341),f=a(5011);function b(s,d){1&s&&t.nrm(0,"span",6)}function F(s,d){if(1&s){const e=t.RV6();t.j41(0,"div",2),t.bIt("click",function(){const n=t.eBV(e).$implicit,o=t.XpG();return t.Njj(o.setActiveCategory(n))}),t.j41(1,"a",3)(2,"span",4),t.EFF(3),t.k0s(),t.DNE(4,b,1,0,"span",5),t.k0s()()}if(2&s){const e=d.$implicit;t.R7$(3),t.JRh(e.name),t.R7$(),t.Y8G("ngIf",e.active)}}let x=(()=>{class s{constructor(e){this.orderService=e}setActiveCategory(e){for(var r=0;r<this.orderService.categories.length;r++)this.orderService.categories[r].active=this.orderService.categories[r].name==e.name,console.log(e),this.orderService.categories[r].name==e.name&&this.orderService.updateProductsBySelectedCategory(e)}static{this.\u0275fac=function(r){return new(r||s)(t.rXU(m))}}static{this.\u0275cmp=t.VBU({type:s,selectors:[["app-add-order-categories"]],decls:2,vars:1,consts:[[1,"category-items-outer"],["class","nav-item",3,"click",4,"ngFor","ngForOf"],[1,"nav-item",3,"click"],[1,"nav-link","btn","btn-flex","flex-column","overflow-hidden","p-5","single-category-item"],[1,"nav-text","text-gray-800","fw-bold","fs-6","lh-1"],["class","bullet-custom position-absolute bottom-0 w-100 h-4px bg-primary",4,"ngIf"],[1,"bullet-custom","position-absolute","bottom-0","w-100","h-4px","bg-primary"]],template:function(r,n){1&r&&(t.j41(0,"div",0),t.DNE(1,F,5,2,"div",1),t.k0s()),2&r&&(t.R7$(),t.Y8G("ngForOf",n.orderService.categories))},dependencies:[p.Sq,p.bT],styles:[".category-items-outer[_ngcontent-%COMP%]{display:flex;flex-direction:row;flex-wrap:wrap;gap:8px}.single-category-item[_ngcontent-%COMP%]{background-color:transparent;border:1px solid var(--kt-primary)!important;transition-duration:1ms;position:relative}.single-category-item.active[_ngcontent-%COMP%]{border:1px solid var(--kt-border-dashed-color)}"]})}}return s})();function C(s,d){1&s&&(t.j41(0,"div",7),t.nrm(1,"i",8),t.k0s())}function y(s,d){if(1&s){const e=t.RV6();t.j41(0,"div",2),t.bIt("click",function(){const n=t.eBV(e).$implicit,o=t.XpG();return t.Njj(o.setSelectedToggle(n))}),t.j41(1,"div",3)(2,"div",4),t.EFF(3),t.k0s(),t.j41(4,"div",5),t.EFF(5),t.k0s(),t.DNE(6,C,2,0,"div",6),t.k0s()()}if(2&s){const e=d.$implicit,r=t.XpG();t.R7$(),t.AVh("selected",r.isProductSelected(e.id)),t.R7$(2),t.SpI(" ",e.name," "),t.R7$(2),t.SpI(" $",e.price," "),t.R7$(),t.Y8G("ngIf",r.isProductSelected(e.id))}}let E=(()=>{class s{constructor(e){this.orderService=e}initialize(){return(0,l.A)(function*(){})()}setSelectedToggle(e){e.selected=!e.selected,this.orderService.updateProductInSelectedProducts(e)}isProductSelected(e){return this.orderService.selected_products.some(r=>r.id===e)}editNote(e,r){r.stopPropagation(),e.isEditingNote=!0}saveNote(e,r){r.stopPropagation(),e.isEditingNote=!1}cancelNoteEdit(e,r){r.stopPropagation(),e.isEditingNote=!1}stopPropagation(e){e.stopPropagation()}static{this.\u0275fac=function(r){return new(r||s)(t.rXU(m))}}static{this.\u0275cmp=t.VBU({type:s,selectors:[["app-add-order-products"]],decls:2,vars:1,consts:[[1,"d-flex","flex-row","justify-content-start","flex-wrap"],["class","card me-2",3,"click",4,"ngFor","ngForOf"],[1,"card","me-2",3,"click"],[1,"card-body","d-flex","flex-column","justify-content-between","align-items-start","p-4","border","rounded","border-primary","product-card-outer"],[1,"fs-4","text-gray-800","text-hover-primary","fw-bold"],[1,"fs-4","text-gray-400"],["class","check-icon-outer",4,"ngIf"],[1,"check-icon-outer"],[1,"ti","ti-check"]],template:function(r,n){1&r&&(t.j41(0,"div",0),t.DNE(1,y,7,5,"div",1),t.k0s()),2&r&&(t.R7$(),t.Y8G("ngForOf",n.orderService.products))},dependencies:[p.Sq,p.bT],styles:[".product-card-outer[_ngcontent-%COMP%]{position:relative;cursor:pointer}.check-icon-outer[_ngcontent-%COMP%]{position:absolute;bottom:10px;right:10px}.check-icon-outer[_ngcontent-%COMP%]   i[_ngcontent-%COMP%]{font-size:24px;color:purple}.product-card-outer.selected[_ngcontent-%COMP%]{background:#7a0c901a}"]})}}return s})();var h=a(8652);const w=()=>[2,3,4,5,6,8];function O(s,d){if(1&s){const e=t.RV6();t.j41(0,"div",25)(1,"div",26)(2,"textarea",27),t.mxI("ngModelChange",function(n){t.eBV(e);const o=t.XpG().$implicit;return t.DH7(o.notes,n)||(o.notes=n),t.Njj(n)}),t.k0s()()()}if(2&s){const e=t.XpG().$implicit;t.R7$(2),t.R50("ngModel",e.notes)}}function P(s,d){}function M(s,d){if(1&s){const e=t.RV6();t.j41(0,"div",34),t.bIt("click",function(n){const o=t.eBV(e).$implicit,i=t.XpG(2).$implicit,g=t.XpG();return i.quantity=o,t.Njj(g.changeQty(n))}),t.j41(1,"div",35)(2,"span",36),t.EFF(3),t.k0s()()()}if(2&s){const e=d.$implicit;t.R7$(3),t.JRh(e)}}function S(s,d){if(1&s){const e=t.RV6();t.j41(0,"div",25)(1,"div",28)(2,"label",29),t.EFF(3,"From 1 to 50"),t.k0s(),t.j41(4,"input",30),t.mxI("ngModelChange",function(n){t.eBV(e);const o=t.XpG().$implicit;return t.DH7(o.quantity,n)||(o.quantity=n),t.Njj(n)}),t.bIt("ngModelChange",function(n){t.eBV(e);const o=t.XpG(2);return t.Njj(o.changeQty(n))}),t.k0s()(),t.j41(5,"div",28)(6,"input",31),t.mxI("ngModelChange",function(n){t.eBV(e);const o=t.XpG().$implicit;return t.DH7(o.quantity,n)||(o.quantity=n),t.Njj(n)}),t.bIt("ngModelChange",function(n){t.eBV(e);const o=t.XpG(2);return t.Njj(o.changeQty(n))}),t.k0s()()(),t.j41(7,"div",32),t.DNE(8,M,4,1,"div",33),t.k0s()}if(2&s){const e=t.XpG().$implicit;t.R7$(4),t.R50("ngModel",e.quantity),t.R7$(2),t.R50("ngModel",e.quantity),t.R7$(2),t.Y8G("ngForOf",t.lJ4(3,w))}}function A(s,d){1&s&&(t.j41(0,"div",36),t.EFF(1,"Select Quantity"),t.k0s())}function R(s,d){if(1&s){const e=t.RV6();t.j41(0,"tr")(1,"td")(2,"div",13)(3,"div",14)(4,"a",15),t.EFF(5),t.k0s(),t.j41(6,"span",16),t.EFF(7),t.k0s(),t.j41(8,"input",17),t.mxI("ngModelChange",function(n){const o=t.eBV(e).$implicit;return t.DH7(o.price,n)||(o.price=n),t.Njj(n)}),t.k0s()()()(),t.j41(9,"td",18),t.DNE(10,O,3,1,"ng-template",null,0,t.C5r)(12,P,0,0,"ng-template",null,1,t.C5r),t.j41(14,"button",19,2),t.bIt("click",function(){t.eBV(e);const n=t.sdS(15);return t.Njj(n.open())}),t.nrm(16,"i",20),t.k0s()(),t.j41(17,"td",18),t.DNE(18,S,9,4,"ng-template",null,3,t.C5r)(20,A,2,0,"ng-template",null,4,t.C5r),t.j41(22,"button",21,2),t.bIt("click",function(){t.eBV(e);const n=t.sdS(15);return t.Njj(n.open())}),t.EFF(24),t.k0s()(),t.j41(25,"td",18)(26,"div",22),t.EFF(27),t.k0s()(),t.j41(28,"td",18)(29,"button",23),t.bIt("click",function(){const n=t.eBV(e).index,o=t.XpG();return t.Njj(o.removeItem(n))}),t.nrm(30,"i",24),t.k0s()()()}if(2&s){let e;const r=d.$implicit,n=t.sdS(11),o=t.sdS(13),i=t.sdS(19),g=t.sdS(21),v=t.XpG();t.R7$(5),t.JRh(r.name),t.R7$(2),t.SpI("$",r.price,""),t.R7$(),t.R50("ngModel",r.price),t.R7$(6),t.Y8G("ngbPopover",n)("popoverTitle",o)("autoClose","outside"),t.R7$(8),t.Y8G("ngbPopover",i)("popoverTitle",g)("autoClose","outside"),t.R7$(2),t.SpI(" Qty x ",v.parseTwoDigitNumber(r.quantity)," "),t.R7$(3),t.SpI("$ ",null!==(e=v.returnListItemCost(r))&&void 0!==e?e:0,"")}}let T=(()=>{class s{constructor(e){this.orderService=e}editNote(e){e.isEditingNote=!0}saveNote(e){e.isEditingNote=!1}removeItem(e){this.orderService.removeProductInSelectedProducts(e)}parseTwoDigitNumber(e){return e<10?`0${e}`:e}returnListItemCost(e){return e.quantity*e.price}changeQty(e){this.orderService.totalOfProductCost()}static{this.\u0275fac=function(r){return new(r||s)(t.rXU(m))}}static{this.\u0275cmp=t.VBU({type:s,selectors:[["app-add-order-price-list"]],decls:16,vars:1,consts:[["noteContent",""],["noteTitle",""],["popover","ngbPopover"],["popContent",""],["popTitle",""],[1,"table-responsive","line-items-table-outer"],[1,"table","table-row-dashed","align-middle","gs-0","gy-3","my-0"],[1,"fs-7","fw-bold","text-gray-400","border-bottom-0"],[1,"p-0","pb-3","min-w-150px","text-start"],[1,"p-0","pb-3","min-w-100px","text-end","pe-13"],[1,"p-0","pb-3","w-150px","text-end","pe-7"],[1,"p-0","pb-3","w-50px","text-end"],[4,"ngFor","ngForOf"],[1,"d-flex","align-items-center"],[1,"d-flex","justify-content-start","flex-column"],[1,"text-white","fw-bold","text-hover-primary","mb-1","fs-6"],[1,"text-gray-400","fw-semibold","d-block","fs-7"],["type","hidden",3,"ngModelChange","ngModel"],[1,"text-end"],["triggers","manual","placement","top",1,"btn","btn-sm",3,"click","ngbPopover","popoverTitle","autoClose"],[1,"ti","ti-notes",2,"color","#ffffff","font-size","22px"],["triggers","manual","placement","top",1,"badge","py-3","px-4","fs-7","badge-light-primary",3,"click","ngbPopover","popoverTitle","autoClose"],[1,"text-white","fw-bold","py-3","px-4","fs-2"],[1,"btn","btn-icon",3,"click"],[1,"ti","ti-trash",2,"font-size","24px","color","#ffffff"],[1,"row"],[1,"col-12"],["rows","3","placeholder","Add a note...",1,"form-control","form-control-solid","mb-2",3,"ngModelChange","ngModel"],[1,"col-6"],["for","customRange3",1,"form-label"],["type","range","min","1","max","50","step","1","id","customRange3",1,"form-range",3,"ngModelChange","ngModel"],["type","number",1,"form-control","form-control-solid",3,"ngModelChange","ngModel"],[1,"row","mt-2"],["class","col-2",3,"click",4,"ngFor","ngForOf"],[1,"col-2",3,"click"],[1,"number-box-select-9877"],[1,"fw-bold","fs-3"]],template:function(r,n){1&r&&(t.j41(0,"div",5)(1,"table",6)(2,"thead")(3,"tr",7)(4,"th",8),t.EFF(5,"Products"),t.k0s(),t.j41(6,"th",9),t.EFF(7,"Notes"),t.k0s(),t.j41(8,"th",9),t.EFF(9,"Qty"),t.k0s(),t.j41(10,"th",10),t.EFF(11,"Cost"),t.k0s(),t.j41(12,"th",11),t.EFF(13,"Action"),t.k0s()()(),t.j41(14,"tbody"),t.DNE(15,R,31,11,"tr",12),t.k0s()()()),2&r&&(t.R7$(15),t.Y8G("ngForOf",n.orderService.selected_products))},dependencies:[p.Sq,c.me,c.Q0,c.MR,c.BC,c.vS,h.ZM],styles:[".line-items-table-outer[_ngcontent-%COMP%]{background:purple;padding:3rem;border-radius:14px}.number-box-select-9877[_ngcontent-%COMP%]{border:2px solid purple;width:100%;border-radius:4px;display:flex;flex-direction:row;justify-content:center;align-items:center;cursor:pointer;-webkit-user-select:none;user-select:none}.enhanced-textarea[_ngcontent-%COMP%]{resize:vertical;border-radius:8px;box-shadow:0 2px 5px #0000001a;padding:10px;font-size:16px;background-color:#f8f9fa;border:1px solid #ced4da}.enhanced-textarea[_ngcontent-%COMP%]:focus{border-color:#80bdff;box-shadow:0 0 5px #007bff40;outline:none}"]})}}return s})();const V=[{path:"",component:(()=>{class s{constructor(e,r){this.nav=e,this.orderService=r}ngOnInit(){}onSubmit(e){var r=this;return(0,l.A)(function*(){(yield r.orderService.submitOrder())&&r.nav.pop()})()}static{this.\u0275fac=function(r){return new(r||s)(t.rXU(k.F),t.rXU(m))}}static{this.\u0275cmp=t.VBU({type:s,selectors:[["app-add-orders"]],decls:440,vars:3,consts:[[3,"title"],[1,"row","gx-5","gx-xl-10"],[1,"col-xl-6","mb-5","mb-xl-10"],[1,"card","card-flush","h-xl-100"],[1,"card-body","p-0"],[1,"mb-5"],[1,"tab-content"],["id","kt_stats_widget_16_tab_1",1,"tab-pane","fade","show","active","pt-5"],[1,"d-flex","align-items-center","justify-content-between","pb-3"],[1,"m-0","me-3"],[1,"search-box","position-relative"],["type","text","placeholder","Search products...",1,"form-control","form-control-solid","ps-5"],[1,"ti","ti-search","position-absolute","search-icon"],[1,"pb-2"],[1,"mb-4"],["rows","4","placeholder","Add a note...",1,"form-control","form-control-solid","enhanced-textarea",3,"ngModelChange","ngModel"],["id","kt_stats_widget_16_tab_2",1,"tab-pane","fade"],[1,"table-responsive"],[1,"table","table-row-dashed","align-middle","gs-0","gy-3","my-0"],[1,"fs-7","fw-bold","text-gray-400","border-bottom-0"],[1,"p-0","pb-3","min-w-150px","text-start"],[1,"p-0","pb-3","min-w-100px","text-end","pe-13"],[1,"p-0","pb-3","w-125px","text-end","pe-7"],[1,"p-0","pb-3","w-50px","text-end"],[1,"d-flex","align-items-center"],[1,"symbol","symbol-50px","me-3"],["src","assets/media/avatars/300-25.jpg","alt","",1,""],[1,"d-flex","justify-content-start","flex-column"],["href","../../demo1/dist/pages/user-profile/overview.html",1,"text-gray-800","fw-bold","text-hover-primary","mb-1","fs-6"],[1,"text-gray-400","fw-semibold","d-block","fs-7"],[1,"text-end","pe-13"],[1,"text-gray-600","fw-bold","fs-6"],[1,"text-end","pe-0"],["id","kt_table_widget_16_chart_2_1","data-kt-chart-color","success",1,"h-50px","mt-n8","pe-7"],[1,"text-end"],["href","#",1,"btn","btn-sm","btn-icon","btn-bg-light","btn-active-color-primary","w-30px","h-30px"],[1,"svg-icon","svg-icon-5","svg-icon-gray-700"],["width","24","height","24","viewBox","0 0 24 24","fill","none","xmlns","http://www.w3.org/2000/svg"],["d","M14.4 11H3C2.4 11 2 11.4 2 12C2 12.6 2.4 13 3 13H14.4V11Z","fill","currentColor"],["opacity","0.3","d","M14.4 20V4L21.7 11.3C22.1 11.7 22.1 12.3 21.7 12.7L14.4 20Z","fill","currentColor"],["src","assets/media/avatars/300-24.jpg","alt","",1,""],["id","kt_table_widget_16_chart_2_2","data-kt-chart-color","danger",1,"h-50px","mt-n8","pe-7"],["src","assets/media/avatars/300-20.jpg","alt","",1,""],["id","kt_table_widget_16_chart_2_3","data-kt-chart-color","success",1,"h-50px","mt-n8","pe-7"],["src","assets/media/avatars/300-17.jpg","alt","",1,""],["id","kt_table_widget_16_chart_2_4","data-kt-chart-color","success",1,"h-50px","mt-n8","pe-7"],["id","kt_stats_widget_16_tab_3",1,"tab-pane","fade"],["src","assets/media/avatars/300-11.jpg","alt","",1,""],["id","kt_table_widget_16_chart_3_1","data-kt-chart-color","success",1,"h-50px","mt-n8","pe-7"],["src","assets/media/avatars/300-23.jpg","alt","",1,""],["id","kt_table_widget_16_chart_3_2","data-kt-chart-color","danger",1,"h-50px","mt-n8","pe-7"],["src","assets/media/avatars/300-4.jpg","alt","",1,""],["id","kt_table_widget_16_chart_3_3","data-kt-chart-color","success",1,"h-50px","mt-n8","pe-7"],["src","assets/media/avatars/300-1.jpg","alt","",1,""],["id","kt_table_widget_16_chart_3_4","data-kt-chart-color","success",1,"h-50px","mt-n8","pe-7"],["id","kt_stats_widget_16_tab_4",1,"tab-pane","fade"],["src","assets/media/avatars/300-12.jpg","alt","",1,""],["id","kt_table_widget_16_chart_4_1","data-kt-chart-color","success",1,"h-50px","mt-n8","pe-7"],["src","assets/media/avatars/300-21.jpg","alt","",1,""],["id","kt_table_widget_16_chart_4_2","data-kt-chart-color","danger",1,"h-50px","mt-n8","pe-7"],["src","assets/media/avatars/300-30.jpg","alt","",1,""],["id","kt_table_widget_16_chart_4_3","data-kt-chart-color","success",1,"h-50px","mt-n8","pe-7"],["src","assets/media/avatars/300-14.jpg","alt","",1,""],["id","kt_table_widget_16_chart_4_4","data-kt-chart-color","success",1,"h-50px","mt-n8","pe-7"],["id","kt_stats_widget_16_tab_5",1,"tab-pane","fade"],["src","assets/media/avatars/300-6.jpg","alt","",1,""],["id","kt_table_widget_16_chart_5_1","data-kt-chart-color","success",1,"h-50px","mt-n8","pe-7"],["src","assets/media/avatars/300-10.jpg","alt","",1,""],["id","kt_table_widget_16_chart_5_2","data-kt-chart-color","danger",1,"h-50px","mt-n8","pe-7"],["src","assets/media/avatars/300-9.jpg","alt","",1,""],["id","kt_table_widget_16_chart_5_3","data-kt-chart-color","success",1,"h-50px","mt-n8","pe-7"],["src","assets/media/avatars/300-3.jpg","alt","",1,""],["id","kt_table_widget_16_chart_5_4","data-kt-chart-color","success",1,"h-50px","mt-n8","pe-7"],[1,"col-xxl-6","mb-5","mb-xl-10"],[1,"card-header"],[1,"card-title","align-items-start","flex-column"],[1,"card-label","fw-bold","text-dark"],[1,"text-gray-400","mt-1","fw-semibold","fs-6"],[1,"card-toolbar"],["type","submit",1,"btn","btn-primary","mt-4",3,"click"],[1,"card-body","pt-6"],["id","kt_chart_widget_8_month_tab","role","tabpanel",1,"tab-pane","fade","active","show"],[1,"d-flex","align-items-center","mb-2"],[1,"fs-1","fw-semibold","text-gray-400","me-1","mt-n1"],[1,"fs-3x","fw-bold","text-gray-800","me-2","lh-1","ls-n2"],[1,"badge","badge-light-success","fs-base"],[1,"svg-icon","svg-icon-5","svg-icon-success","ms-n1"],["opacity","0.5","x","13","y","6","width","13","height","2","rx","1","transform","rotate(90 13 6)","fill","currentColor"],["d","M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z","fill","currentColor"],[1,"fs-6","fw-semibold","text-gray-400"]],template:function(r,n){1&r&&(t.j41(0,"app-kt-app-form-page",0)(1,"div",1)(2,"div",2)(3,"div",3)(4,"div",4),t.nrm(5,"app-add-order-categories",5),t.j41(6,"div",6)(7,"div",7)(8,"div",8)(9,"h2",9),t.EFF(10,"Products"),t.k0s(),t.j41(11,"div",10),t.nrm(12,"input",11)(13,"i",12),t.k0s()(),t.nrm(14,"app-add-order-products"),t.k0s(),t.j41(15,"h2",13),t.EFF(16,"Notes"),t.k0s(),t.j41(17,"div",14)(18,"textarea",15),t.mxI("ngModelChange",function(i){return t.DH7(n.orderService.order_notes,i)||(n.orderService.order_notes=i),i}),t.k0s()(),t.j41(19,"div",16)(20,"div",17)(21,"table",18)(22,"thead")(23,"tr",19)(24,"th",20),t.EFF(25,"AUTHOR"),t.k0s(),t.j41(26,"th",21),t.EFF(27,"CONV."),t.k0s(),t.j41(28,"th",22),t.EFF(29,"CHART"),t.k0s(),t.j41(30,"th",23),t.EFF(31,"VIEW"),t.k0s()()(),t.j41(32,"tbody")(33,"tr")(34,"td")(35,"div",24)(36,"div",25),t.nrm(37,"img",26),t.k0s(),t.j41(38,"div",27)(39,"a",28),t.EFF(40,"Brooklyn Simmons"),t.k0s(),t.j41(41,"span",29),t.EFF(42,"Poland"),t.k0s()()()(),t.j41(43,"td",30)(44,"span",31),t.EFF(45,"85.23%"),t.k0s()(),t.j41(46,"td",32),t.nrm(47,"div",33),t.k0s(),t.j41(48,"td",34)(49,"a",35)(50,"span",36),t.qSk(),t.j41(51,"svg",37),t.nrm(52,"path",38)(53,"path",39),t.k0s()()()()(),t.joV(),t.j41(54,"tr")(55,"td")(56,"div",24)(57,"div",25),t.nrm(58,"img",40),t.k0s(),t.j41(59,"div",27)(60,"a",28),t.EFF(61,"Esther Howard"),t.k0s(),t.j41(62,"span",29),t.EFF(63,"Mexico"),t.k0s()()()(),t.j41(64,"td",30)(65,"span",31),t.EFF(66,"74.83%"),t.k0s()(),t.j41(67,"td",32),t.nrm(68,"div",41),t.k0s(),t.j41(69,"td",34)(70,"a",35)(71,"span",36),t.qSk(),t.j41(72,"svg",37),t.nrm(73,"path",38)(74,"path",39),t.k0s()()()()(),t.joV(),t.j41(75,"tr")(76,"td")(77,"div",24)(78,"div",25),t.nrm(79,"img",42),t.k0s(),t.j41(80,"div",27)(81,"a",28),t.EFF(82,"Annette Black"),t.k0s(),t.j41(83,"span",29),t.EFF(84,"Haiti"),t.k0s()()()(),t.j41(85,"td",30)(86,"span",31),t.EFF(87,"90.06%"),t.k0s()(),t.j41(88,"td",32),t.nrm(89,"div",43),t.k0s(),t.j41(90,"td",34)(91,"a",35)(92,"span",36),t.qSk(),t.j41(93,"svg",37),t.nrm(94,"path",38)(95,"path",39),t.k0s()()()()(),t.joV(),t.j41(96,"tr")(97,"td")(98,"div",24)(99,"div",25),t.nrm(100,"img",44),t.k0s(),t.j41(101,"div",27)(102,"a",28),t.EFF(103,"Marvin McKinney"),t.k0s(),t.j41(104,"span",29),t.EFF(105,"Monaco"),t.k0s()()()(),t.j41(106,"td",30)(107,"span",31),t.EFF(108,"54.08%"),t.k0s()(),t.j41(109,"td",32),t.nrm(110,"div",45),t.k0s(),t.j41(111,"td",34)(112,"a",35)(113,"span",36),t.qSk(),t.j41(114,"svg",37),t.nrm(115,"path",38)(116,"path",39),t.k0s()()()()()()()()(),t.joV(),t.j41(117,"div",46)(118,"div",17)(119,"table",18)(120,"thead")(121,"tr",19)(122,"th",20),t.EFF(123,"AUTHOR"),t.k0s(),t.j41(124,"th",21),t.EFF(125,"CONV."),t.k0s(),t.j41(126,"th",22),t.EFF(127,"CHART"),t.k0s(),t.j41(128,"th",23),t.EFF(129,"VIEW"),t.k0s()()(),t.j41(130,"tbody")(131,"tr")(132,"td")(133,"div",24)(134,"div",25),t.nrm(135,"img",47),t.k0s(),t.j41(136,"div",27)(137,"a",28),t.EFF(138,"Jacob Jones"),t.k0s(),t.j41(139,"span",29),t.EFF(140,"New York"),t.k0s()()()(),t.j41(141,"td",30)(142,"span",31),t.EFF(143,"52.34%"),t.k0s()(),t.j41(144,"td",32),t.nrm(145,"div",48),t.k0s(),t.j41(146,"td",34)(147,"a",35)(148,"span",36),t.qSk(),t.j41(149,"svg",37),t.nrm(150,"path",38)(151,"path",39),t.k0s()()()()(),t.joV(),t.j41(152,"tr")(153,"td")(154,"div",24)(155,"div",25),t.nrm(156,"img",49),t.k0s(),t.j41(157,"div",27)(158,"a",28),t.EFF(159,"Ronald Richards"),t.k0s(),t.j41(160,"span",29),t.EFF(161,"Madrid"),t.k0s()()()(),t.j41(162,"td",30)(163,"span",31),t.EFF(164,"77.65%"),t.k0s()(),t.j41(165,"td",32),t.nrm(166,"div",50),t.k0s(),t.j41(167,"td",34)(168,"a",35)(169,"span",36),t.qSk(),t.j41(170,"svg",37),t.nrm(171,"path",38)(172,"path",39),t.k0s()()()()(),t.joV(),t.j41(173,"tr")(174,"td")(175,"div",24)(176,"div",25),t.nrm(177,"img",51),t.k0s(),t.j41(178,"div",27)(179,"a",28),t.EFF(180,"Leslie Alexander"),t.k0s(),t.j41(181,"span",29),t.EFF(182,"Pune"),t.k0s()()()(),t.j41(183,"td",30)(184,"span",31),t.EFF(185,"82.47%"),t.k0s()(),t.j41(186,"td",32),t.nrm(187,"div",52),t.k0s(),t.j41(188,"td",34)(189,"a",35)(190,"span",36),t.qSk(),t.j41(191,"svg",37),t.nrm(192,"path",38)(193,"path",39),t.k0s()()()()(),t.joV(),t.j41(194,"tr")(195,"td")(196,"div",24)(197,"div",25),t.nrm(198,"img",53),t.k0s(),t.j41(199,"div",27)(200,"a",28),t.EFF(201,"Courtney Henry"),t.k0s(),t.j41(202,"span",29),t.EFF(203,"Mexico"),t.k0s()()()(),t.j41(204,"td",30)(205,"span",31),t.EFF(206,"67.84%"),t.k0s()(),t.j41(207,"td",32),t.nrm(208,"div",54),t.k0s(),t.j41(209,"td",34)(210,"a",35)(211,"span",36),t.qSk(),t.j41(212,"svg",37),t.nrm(213,"path",38)(214,"path",39),t.k0s()()()()()()()()(),t.joV(),t.j41(215,"div",55)(216,"div",17)(217,"table",18)(218,"thead")(219,"tr",19)(220,"th",20),t.EFF(221,"AUTHOR"),t.k0s(),t.j41(222,"th",21),t.EFF(223,"CONV."),t.k0s(),t.j41(224,"th",22),t.EFF(225,"CHART"),t.k0s(),t.j41(226,"th",23),t.EFF(227,"VIEW"),t.k0s()()(),t.j41(228,"tbody")(229,"tr")(230,"td")(231,"div",24)(232,"div",25),t.nrm(233,"img",56),t.k0s(),t.j41(234,"div",27)(235,"a",28),t.EFF(236,"Arlene McCoy"),t.k0s(),t.j41(237,"span",29),t.EFF(238,"London"),t.k0s()()()(),t.j41(239,"td",30)(240,"span",31),t.EFF(241,"53.44%"),t.k0s()(),t.j41(242,"td",32),t.nrm(243,"div",57),t.k0s(),t.j41(244,"td",34)(245,"a",35)(246,"span",36),t.qSk(),t.j41(247,"svg",37),t.nrm(248,"path",38)(249,"path",39),t.k0s()()()()(),t.joV(),t.j41(250,"tr")(251,"td")(252,"div",24)(253,"div",25),t.nrm(254,"img",58),t.k0s(),t.j41(255,"div",27)(256,"a",28),t.EFF(257,"Marvin McKinneyr"),t.k0s(),t.j41(258,"span",29),t.EFF(259,"Monaco"),t.k0s()()()(),t.j41(260,"td",30)(261,"span",31),t.EFF(262,"74.64%"),t.k0s()(),t.j41(263,"td",32),t.nrm(264,"div",59),t.k0s(),t.j41(265,"td",34)(266,"a",35)(267,"span",36),t.qSk(),t.j41(268,"svg",37),t.nrm(269,"path",38)(270,"path",39),t.k0s()()()()(),t.joV(),t.j41(271,"tr")(272,"td")(273,"div",24)(274,"div",25),t.nrm(275,"img",60),t.k0s(),t.j41(276,"div",27)(277,"a",28),t.EFF(278,"Jacob Jones"),t.k0s(),t.j41(279,"span",29),t.EFF(280,"PManila"),t.k0s()()()(),t.j41(281,"td",30)(282,"span",31),t.EFF(283,"88.56%"),t.k0s()(),t.j41(284,"td",32),t.nrm(285,"div",61),t.k0s(),t.j41(286,"td",34)(287,"a",35)(288,"span",36),t.qSk(),t.j41(289,"svg",37),t.nrm(290,"path",38)(291,"path",39),t.k0s()()()()(),t.joV(),t.j41(292,"tr")(293,"td")(294,"div",24)(295,"div",25),t.nrm(296,"img",62),t.k0s(),t.j41(297,"div",27)(298,"a",28),t.EFF(299,"Esther Howard"),t.k0s(),t.j41(300,"span",29),t.EFF(301,"Iceland"),t.k0s()()()(),t.j41(302,"td",30)(303,"span",31),t.EFF(304,"63.16%"),t.k0s()(),t.j41(305,"td",32),t.nrm(306,"div",63),t.k0s(),t.j41(307,"td",34)(308,"a",35)(309,"span",36),t.qSk(),t.j41(310,"svg",37),t.nrm(311,"path",38)(312,"path",39),t.k0s()()()()()()()()(),t.joV(),t.j41(313,"div",64)(314,"div",17)(315,"table",18)(316,"thead")(317,"tr",19)(318,"th",20),t.EFF(319,"AUTHOR"),t.k0s(),t.j41(320,"th",21),t.EFF(321,"CONV."),t.k0s(),t.j41(322,"th",22),t.EFF(323,"CHART"),t.k0s(),t.j41(324,"th",23),t.EFF(325,"VIEW"),t.k0s()()(),t.j41(326,"tbody")(327,"tr")(328,"td")(329,"div",24)(330,"div",25),t.nrm(331,"img",65),t.k0s(),t.j41(332,"div",27)(333,"a",28),t.EFF(334,"Jane Cooper"),t.k0s(),t.j41(335,"span",29),t.EFF(336,"Haiti"),t.k0s()()()(),t.j41(337,"td",30)(338,"span",31),t.EFF(339,"68.54%"),t.k0s()(),t.j41(340,"td",32),t.nrm(341,"div",66),t.k0s(),t.j41(342,"td",34)(343,"a",35)(344,"span",36),t.qSk(),t.j41(345,"svg",37),t.nrm(346,"path",38)(347,"path",39),t.k0s()()()()(),t.joV(),t.j41(348,"tr")(349,"td")(350,"div",24)(351,"div",25),t.nrm(352,"img",67),t.k0s(),t.j41(353,"div",27)(354,"a",28),t.EFF(355,"Esther Howard"),t.k0s(),t.j41(356,"span",29),t.EFF(357,"Kiribati"),t.k0s()()()(),t.j41(358,"td",30)(359,"span",31),t.EFF(360,"55.83%"),t.k0s()(),t.j41(361,"td",32),t.nrm(362,"div",68),t.k0s(),t.j41(363,"td",34)(364,"a",35)(365,"span",36),t.qSk(),t.j41(366,"svg",37),t.nrm(367,"path",38)(368,"path",39),t.k0s()()()()(),t.joV(),t.j41(369,"tr")(370,"td")(371,"div",24)(372,"div",25),t.nrm(373,"img",69),t.k0s(),t.j41(374,"div",27)(375,"a",28),t.EFF(376,"Jacob Jones"),t.k0s(),t.j41(377,"span",29),t.EFF(378,"Poland"),t.k0s()()()(),t.j41(379,"td",30)(380,"span",31),t.EFF(381,"93.46%"),t.k0s()(),t.j41(382,"td",32),t.nrm(383,"div",70),t.k0s(),t.j41(384,"td",34)(385,"a",35)(386,"span",36),t.qSk(),t.j41(387,"svg",37),t.nrm(388,"path",38)(389,"path",39),t.k0s()()()()(),t.joV(),t.j41(390,"tr")(391,"td")(392,"div",24)(393,"div",25),t.nrm(394,"img",71),t.k0s(),t.j41(395,"div",27)(396,"a",28),t.EFF(397,"Ralph Edwards"),t.k0s(),t.j41(398,"span",29),t.EFF(399,"Mexico"),t.k0s()()()(),t.j41(400,"td",30)(401,"span",31),t.EFF(402,"64.48%"),t.k0s()(),t.j41(403,"td",32),t.nrm(404,"div",72),t.k0s(),t.j41(405,"td",34)(406,"a",35)(407,"span",36),t.qSk(),t.j41(408,"svg",37),t.nrm(409,"path",38)(410,"path",39),t.k0s()()()()()()()()()()()()(),t.joV(),t.j41(411,"div",73)(412,"div",3)(413,"div",74)(414,"h3",75)(415,"span",76),t.EFF(416,"Order Overview"),t.k0s(),t.j41(417,"span",77),t.EFF(418,"take a look at bill details "),t.k0s()(),t.j41(419,"div",78)(420,"button",79),t.bIt("click",function(i){return n.onSubmit(i)}),t.EFF(421,"Submit"),t.k0s()()(),t.j41(422,"div",80)(423,"div",6)(424,"div",81)(425,"div",5)(426,"div",82)(427,"span",83),t.EFF(428,"$"),t.k0s(),t.j41(429,"span",84),t.EFF(430),t.k0s(),t.j41(431,"span",85)(432,"span",86),t.qSk(),t.j41(433,"svg",37),t.nrm(434,"rect",87)(435,"path",88),t.k0s()(),t.EFF(436,"Discount - 0%"),t.k0s()(),t.joV(),t.j41(437,"span",89),t.EFF(438,"Avarage cost per interaction"),t.k0s()(),t.nrm(439,"app-add-order-price-list"),t.k0s()()()()()()()),2&r&&(t.Y8G("title","Add Orders"),t.R7$(18),t.R50("ngModel",n.orderService.order_notes),t.R7$(412),t.JRh(n.orderService.totalCost))},dependencies:[c.me,c.BC,c.vS,f.R,x,E,T],styles:[".d-flex[_ngcontent-%COMP%]{display:flex;align-items:center;gap:20px}.search-box[_ngcontent-%COMP%]{position:relative;width:250px}.search-box[_ngcontent-%COMP%]   input[_ngcontent-%COMP%]{border-radius:6px;padding:.5rem 1rem .5rem 2.5rem;border:1px solid #e2e8f0;background-color:#f5f8fa}.search-box[_ngcontent-%COMP%]   .search-icon[_ngcontent-%COMP%]{position:absolute;right:10px;top:50%;transform:translateY(-50%);color:#6b7280}.filtered-products[_ngcontent-%COMP%]{max-height:200px;overflow-y:auto}"]})}}return s})(),data:{breadcrumb:"Add"}}];let N=(()=>{class s{static{this.\u0275fac=function(r){return new(r||s)}}static{this.\u0275mod=t.$C({type:s})}static{this.\u0275inj=t.G2t({imports:[u.iI.forChild(V),u.iI]})}}return s})();var I=a(7376),$=a(5291),G=a(1794);let L=(()=>{class s{static{this.\u0275fac=function(r){return new(r||s)}}static{this.\u0275mod=t.$C({type:s})}static{this.\u0275inj=t.G2t({imports:[p.MD]})}}return s})(),X=(()=>{class s{static{this.\u0275fac=function(r){return new(r||s)}}static{this.\u0275mod=t.$C({type:s})}static{this.\u0275inj=t.G2t({imports:[p.MD,c.YN]})}}return s})(),B=(()=>{class s{static{this.\u0275fac=function(r){return new(r||s)}}static{this.\u0275mod=t.$C({type:s})}static{this.\u0275inj=t.G2t({imports:[p.MD,c.YN,h.zr]})}}return s})(),q=(()=>{class s{static{this.\u0275fac=function(r){return new(r||s)}}static{this.\u0275mod=t.$C({type:s})}static{this.\u0275inj=t.G2t({imports:[p.MD,N,c.YN,I.qy,c.X1,$.u,G.g,L,X,B]})}}return s})()}}]);