"use strict";(self.webpackChunkfree_version=self.webpackChunkfree_version||[]).push([[602],{2406:(P,v,c)=>{c.d(v,{A:()=>I});var l=c(3953),h=c(1626);let y=(()=>{class i{constructor(t){this.http=t,this.url="https://thelocalcraftfood.duckdns.org/api/admin"}get(t,s,e){return this.http.get(this.url+"/"+t,e)}post(t,s,e){return this.http.post(this.url+"/"+t,s,e)}put(t,s,e){return this.http.put(this.url+"/"+t,s,e)}delete(t,s){return this.http.delete(this.url+"/"+t,s)}patch(t,s,e){return this.http.patch(this.url+"/"+t,s,e)}static{this.\u0275fac=function(s){return new(s||i)(l.KVO(h.Qq))}}static{this.\u0275prov=l.jDH({token:i,factory:i.\u0275fac,providedIn:"root"})}}return i})();var R=c(245),f=c(9253);let I=(()=>{class i{getUserById(t){throw new Error("Method not implemented.")}constructor(t,s,e){this.api=t,this.router=s,this.utility=e,this.serialize=r=>{const n=[];for(const a in r)if(r.hasOwnProperty(a)){let o=encodeURIComponent(a)+"="+encodeURIComponent(r[a]);n.push(o)}return n.join("&")}}loginViaEmail(t){return this.httpPostResponse("auth/login-via-email",t,null,!1,!0)}getRestaurants(t){const s=this.serialize(t);return this.httpGetResponse("restaurant"+(s?`?${s}`:""),null,!1,!0)}getRestaurantById(t){return this.httpGetResponse(`restaurant/${t}`,null,!1,!0)}addRestaurant(t){return this.httpPostResponse("restaurant",t,null,!1,!0)}removeRestaurant(t){return this.httpDeleteResponse("restaurant",t,!1,!0)}getUserByToken(){return this.httpGetResponse("auth-user",null,!1)}getUsers(t){const s=this.serialize(t);return this.httpGetResponse("user"+(s?`?${s}`:""),null,!1,!0)}getUsersById(t){return this.httpGetResponse(`user/${t}`,null,!1,!0)}addUser(t){return this.httpPostResponse("user",t,null,!1,!0)}removeUser(t){return this.httpDeleteResponse("user",t,!1,!0)}getRoles(t){const s=this.serialize(t);return this.httpGetResponse("role"+(s?`?${s}`:""),null,!1,!0)}getCategories(t){const s=this.serialize(t);return this.httpGetResponse("category"+(s?`?${s}`:""),null,!1,!0)}getCategoriesById(t){return this.httpGetResponse(`category/${t}`,null,!1,!0)}addCategory(t){return this.httpPostResponse("category",t,null,!1,!0)}removeCategory(t){return this.httpDeleteResponse("category",t,!1,!0)}getProducts(t){const s=this.serialize(t);return this.httpGetResponse("product"+(s?`?${s}`:""),null,!1,!0)}getProductsById(t){return this.httpGetResponse(`product/${t}`,null,!1,!0)}addProduct(t){return this.httpPostResponse("product",t,null,!1,!0)}removeProduct(t){return this.httpDeleteResponse("product",t,!1,!0)}addInvoice(t){return this.httpDeleteResponse("invoice",t,!1,!0)}getInvoices(t){return this.httpDeleteResponse("invoice",t,!1,!0)}removeInvoice(t){return this.httpDeleteResponse("invoice",t,!1,!0)}getTables(t){const s=this.serialize(t);return this.httpGetResponse("rtable"+(s?`?${s}`:""),null,!1,!0)}getTablesById(t){return this.httpGetResponse(`rtable/${t}`,null,!1,!0)}addTable(t){return this.httpPostResponse("rtable",t,null,!1,!0)}removeTable(t){return this.httpDeleteResponse("rtable",t,!1,!0)}getOrders(t){const s=this.serialize(t);return this.httpGetResponse("order"+(s?`?${s}`:""),null,!1,!0)}getOrdersById(t){return this.httpGetResponse(`order/${t}`,null,!1,!0)}addOrder(t){return this.httpPostResponse("order",t,null,!1,!0)}httpPostResponse(t,s,e=null,r=!0,n=!0,a="application/json"){return this.httpResponse("post",t,s,e,r,n,a)}httpGetResponse(t,s=null,e=!0,r=!0,n="application/json"){return this.httpResponse("get",t,{},s,e,r,n)}httpPutResponse(t,s,e=null){return new Promise((r,n)=>{this.api.put(t,s).subscribe(a=>{r(a)})})}httpPatchResponse(t,s,e=null){return new Promise((r,n)=>{this.api.patch(t,s).subscribe(a=>{r(a)})})}httpDeleteResponse(t,s=null,e=!0,r=!0,n="application/json"){return this.httpResponse("delete",t,{},s,e,r,n)}httpResponse(t="get",s,e,r=null,n=!0,a=!0,o="application/json"){return new Promise((p,u)=>{!0===n&&this.utility.showLoader();const m=s+(r?"/"+r:"");("get"===t?this.api.get(m,{}):"delete"===t?this.api.delete(m,{}):this.api.post(m,e)).subscribe({next:g=>{!0===n&&this.utility.hideLoader(),console.log("EW",g),p(g.result)},error:g=>{this.utility.hideLoader(),1==a&&this.utility.presentFailureToast(g.error.message),401==g.status&&(localStorage.removeItem("token"),localStorage.removeItem("user_role"),this.router.navigate([""])),u(g.error)}})}).catch(p=>{"Error"==p.status&&(this.utility.presentFailureToast(p.message),"User Not Logged In!"==p.message&&this.router.navigate([""]))})}static{this.\u0275fac=function(s){return new(s||i)(l.KVO(y),l.KVO(R.Ix),l.KVO(f.Q))}}static{this.\u0275prov=l.jDH({token:i,factory:i.\u0275fac,providedIn:"root"})}}return i})()},5602:(P,v,c)=>{c.d(v,{g:()=>R});var l=c(467),h=c(3953),y=c(2406);let R=(()=>{class f{constructor(i){this.network=i}getUser(){if(!this._user){const i=localStorage.getItem("user");i&&(this._user=JSON.parse(i))}return this._user}setUser(i){var d=this;return(0,l.A)(function*(){return localStorage.setItem("user",JSON.stringify(i)),d._user=i,i})()}getLoginUserFromApi(){var i=this;return(0,l.A)(function*(){return new Promise(function(){var d=(0,l.A)(function*(t){if(localStorage.getItem("token"))try{let e=yield i.network.getUserByToken();i.setUser(e.user),t(e.user)}catch{t(!1)}else t(!1)});return function(t){return d.apply(this,arguments)}}())})()}static{this.\u0275fac=function(d){return new(d||f)(h.KVO(y.A))}}static{this.\u0275prov=h.jDH({token:f,factory:f.\u0275fac,providedIn:"root"})}}return f})()},9253:(P,v,c)=>{c.d(v,{Q:()=>d});var l=c(3953),h=c(467);let y=(()=>{class t{constructor(){}showLoader(e=""){return(0,h.A)(function*(){})()}hideLoader(){return(0,h.A)(function*(){})()}static{this.\u0275fac=function(r){return new(r||t)}}static{this.\u0275prov=l.jDH({token:t,factory:t.\u0275fac,providedIn:"root"})}}return t})();var R=c(8032),f=c.n(R);let I=(()=>{class t{constructor(){}validateEmail(e){return e.match(/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/)}getOnlyDigits(e){const n=e.toString().replace(/[^\d]/g,"");return parseInt(n,10).toString()}isPhoneNumberValid(e){const r=this.getOnlyDigits(e);return r.toString(),!(r.toString().length<10)}capitalizeEachFirst(e){if(!e)return"";const r=e.toLowerCase().split(" ");for(let n=0;n<r.length;n++)r[n]=r[n].charAt(0).toUpperCase()+r[n].substring(1);return r.join(" ")}capitalizeAllLetters(e){if(!e)return"";const r=e.toLowerCase().split(" ");for(let n=0;n<r.length;n++)r[n]=r[n].charAt(0).toUpperCase()+r[n].substring(1);return r.join(" ")}checkIfMatchingPasswords(e,r){return n=>{const o=n.controls[r];return o.setErrors(n.controls[e].value!==o.value?{notEquivalent:!0}:null)}}parseAddressFromProfile(e){return`${e.apartment||""} ${e.street_address||""} ${e.city||""} ${e.state||""} ${e.zip_code||""}`}parseName(e,r=!1){const n=u=>"string"!=typeof u?"":(u=u.toLowerCase()).charAt(0).toUpperCase()+u.slice(1),a=e||"",o={};if(a.length>0){let u=a.match(/\w*/g)||[];u=u.filter(m=>m),u.length>3?(o.firstName=u.slice(0,2).join(" "),o.firstName=n(o.firstName)):(o.firstName=u.slice(0,1).join(" "),o.firstName=n(o.firstName)),u.length>2?(o.middleName=u.slice(-2,-1).join(" "),o.lastName=u.slice(-1).join(" "),o.middleName=n(o.middleName),o.lastName=n(o.lastName)):1===u.length?(o.lastName="",o.middleName=""):(o.lastName=u.slice(-1).join(" "),o.lastName=n(o.lastName),o.middleName="")}return r?o:o.lastName+(o.lastName?" ":"")+o.firstName}isLastNameExist(e){return""!==this.parseName(e,!0).lastName}numDigits(e){return Math.log(e)*Math.LOG10E+1||0}static{this.\u0275fac=function(r){return new(r||t)}}static{this.\u0275prov=l.jDH({token:t,factory:t.\u0275fac,providedIn:"root"})}}return t})(),i=(()=>{class t{constructor(e){this.strings=e}showAlert(e,r="Alert"){return new Promise(function(){var n=(0,h.A)(function*(a){});return function(a){return n.apply(this,arguments)}}())}presentSuccessToast(e){return(0,h.A)(function*(){})()}presentFailureToast(e){return(0,h.A)(function*(){console.log(e),f().fire({toast:!0,position:"top-end",icon:"error",title:e,showConfirmButton:!1,timer:3e3,timerProgressBar:!0,didOpen:r=>{r.addEventListener("mouseenter",f().stopTimer),r.addEventListener("mouseleave",f().resumeTimer)}})})()}presentToast(e){return(0,h.A)(function*(){})()}presentConfirm(e="OK",r="Cancel",n="Are You Sure?",a="",o="",p=""){return new Promise(function(){var u=(0,h.A)(function*(m){});return function(m){return u.apply(this,arguments)}}())}presentRadioSelections(e,r,n,a="OK",o="Cancel"){return new Promise(function(){var p=(0,h.A)(function*(u){});return function(u){return p.apply(this,arguments)}}())}static{this.\u0275fac=function(r){return new(r||t)(l.KVO(I))}}static{this.\u0275prov=l.jDH({token:t,factory:t.\u0275fac,providedIn:"root"})}}return t})(),d=(()=>{class t{constructor(e,r){this.loading=e,this.alerts=r}showLoader(e=""){return this.loading.showLoader(e)}hideLoader(){return this.loading.hideLoader()}showAlert(e,r="Alert"){return this.alerts.showAlert(e,r)}presentToast(e){return this.alerts.presentToast(e)}presentSuccessToast(e){return this.alerts.presentSuccessToast(e)}presentFailureToast(e){return this.alerts.presentFailureToast(e)}presentConfirm(e="OK",r="Cancel",n="Are You Sure?",a="",o="",p=""){return this.alerts.presentConfirm(e,r,n,a,o,p)}static{this.\u0275fac=function(r){return new(r||t)(l.KVO(y),l.KVO(i))}}static{this.\u0275prov=l.jDH({token:t,factory:t.\u0275fac,providedIn:"root"})}}return t})()}}]);