function strRepeat(t,e){for(var i=0;i<e-1;++i)t+=t;return t}function toDigits(t,e){"undefined"==typeof e&&(e=2);var i=Math.pow(10,e-1);return t<i?strRepeat("0",e-1)+t:t}function urlParam(t){var e=new RegExp("[?&]"+t+"=([^&#]*)").exec(window.location.href);return null==e?null:decodeURIComponent(e[1])||0}function isUnset(t){return void 0==t||"undefined"==typeof t||null==t}function isSet(t){return!isUnset(t)}function isString(t){return"string"==typeof t}function isObject(t,e){return e=isUnset(e)?"[object]":"[object "+e+"]",isSet(t)&&Object.prototype.toString.call(t)===e}function isArray(t){return isObject(t,"Array")}function beginsWith(t,e){return!!isSet(t)&&0==t.toString().indexOf(e)}function nl2br(t){return t.replace(/\r*\n/g,"<br>")}function htmlspecialchars(t){var e={"&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#039;"};return t.replace(/[&<>"']/g,function(t){return e[t]})}function NumberFormatHelper(){this.DEFAULT_NUMBER_OF_DECIMAL_POINTS=2,this.type=KATNISS_SETTINGS.number_format,this.numberOfDecimalPoints=this.DEFAULT_NUMBER_OF_DECIMAL_POINTS}function KatnissApiMessages(t){this.messages=isArray(t)?t:isString(t)?[t]:[]}function KatnissApi(t){this.REQUEST_TYPE_POST="post",this.REQUEST_TYPE_GET="get",this.REQUEST_TYPE_PUT="put",this.REQUEST_TYPE_DELETE="delete",this.isWebApi=isSet(t)&&t===!0}function updateCsrfToken(t){KATNISS_REQUEST_TOKEN=t,$('input[type="hidden"][name="_token"]').val(t),startSessionTimeout()}function startSessionTimeout(){isSet(_sessionTimeout)&&clearTimeout(_sessionTimeout),_sessionTimeout=setTimeout(function(){if(KATNISS_USER===!1){var t=new KatnissApi((!0));t.get("user/csrf-token",null,function(t,e,i){t||updateCsrfToken(e.csrf_token)})}else x_modal_lock()},KATNISS_SESSION_LIFETIME)}function openWindow(t,e,i,n){var s=[];for(var o in i)s.push(o+"="+i[o]);return window.open(t,e,s.join(","),n)}function quickForm(t,e,i){"undefined"==typeof i&&(i="post"),$form=$('<form action="'+t+'" method="'+i+'"></form>');for(var n in e)$form.append('<input type="hidden" name="'+n+'" value="'+e[n]+'">');return $form}NumberFormatHelper.prototype.modeInt=function(t){this.mode(0)},NumberFormatHelper.prototype.modeNormal=function(t){this.mode(this.DEFAULT_NUMBER_OF_DECIMAL_POINTS)},NumberFormatHelper.prototype.mode=function(t){this.numberOfDecimalPoints=t},NumberFormatHelper.prototype.format=function(t){switch(t=parseFloat(t),this.type){case"point_comma":return this.formatPointComma(t);case"point_space":return this.formatPointSpace(t);case"comma_point":return this.formatCommaPoint(t);case"comma_space":return this.formatCommaSpace(t);default:return t}},NumberFormatHelper.prototype.formatPointComma=function(t){return t.toFixed(this.numberOfDecimalPoints).replace(/(\d)(?=(\d{3})+\.)/g,"$1,")},NumberFormatHelper.prototype.formatPointSpace=function(t){return t.toFixed(this.numberOfDecimalPoints).replace(/(\d)(?=(\d{3})+\.)/g,"$1 ")},NumberFormatHelper.prototype.formatCommaPoint=function(t){return t=this.formatPointSpace(t),t.replace(".",",").replace(" ",".")},NumberFormatHelper.prototype.formatCommaSpace=function(t){return t=this.formatPointSpace(t),t.replace(".",",")},KatnissApiMessages.prototype.hasAny=function(){return this.messages.length>0},KatnissApiMessages.prototype.all=function(){return this.messages},KatnissApiMessages.prototype.first=function(){return this.hasAny()?this.messages[0]:""},KatnissApi.prototype.switchToAppApi=function(){this.isWebApi=!1},KatnissApi.prototype.switchToWebApi=function(){this.isWebApi=!0},KatnissApi.prototype.buildUrl=function(t){var e=this.isWebApi?KATNISS_WEB_API_URL:KATNISS_API_URL;return beginsWith(t,e)?t:e+"/"+t},KatnissApi.prototype.buildParams=function(t){return isUnset(t)&&(t={}),this.isWebApi?(isObject(t,"FormData")?t.append("_token",KATNISS_REQUEST_TOKEN):t._token=KATNISS_REQUEST_TOKEN,t):(isObject(t,"FormData")?(t.append("_app",JSON.stringify(KATNISS_APP)),t.append("_settings",JSON.stringify(KATNISS_SETTINGS))):(t._app=KATNISS_APP,t._settings=KATNISS_SETTINGS),t)},KatnissApi.prototype.buildOptions=function(t,e,i){return isSet(i)||(i={}),i.type=t,i.data=this.buildParams(e),isSet(i.dataType)||(i.dataType="json"),isObject(e,"FormData")&&(i.processData=!1,i.contentType=!1),i},KatnissApi.prototype.beforeRequest=function(){this.isWebApi&&startSessionTimeout()},KatnissApi.prototype.post=function(t,e,i,n,s){this.beforeRequest(),this.promise($.post(this.buildUrl(t),this.buildParams(e)),i,n,s)},KatnissApi.prototype.get=function(t,e,i,n,s){this.beforeRequest(),this.promise($.get(this.buildUrl(t),this.buildParams(e)),i,n,s)},KatnissApi.prototype.request=function(t,e,i,n,s,o,r){this.beforeRequest(),this.promise($.ajax(this.buildUrl(t),this.buildOptions(e,i,n)),s,o,r)},KatnissApi.prototype.promise=function(t,e,i,n){var s=this;t.done(function(t){isSet(e)&&e.call(s,s.isFailed(t),s.data(t),s.messages(t))}).fail(function(t,e,n){isSet(i)&&i.call(s,e,n)}).always(function(){isSet(n)&&n.call(s)})},KatnissApi.prototype.isFailed=function(t){return isSet(t._success)&&1!=t._success},KatnissApi.prototype.data=function(t){return isSet(t._data)?t._data:null},KatnissApi.prototype.messages=function(t){return new KatnissApiMessages(t._messages)};var _sessionTimeout=null;startSessionTimeout(),$(function(){var t=1050;$(document).on("shown.bs.modal",".modal",function(e){$(this).css("z-index",++t)}),$(document).on("click",".go-url",function(t){t.preventDefault();var e=$(this).attr("data-url");e&&(window.location.href=e)}).on("click",".open-window",function(t){t.preventDefault();var e=$(this),i=$.extend(e.data()),n="",s="",o=!0,r=null;i.url?(n=i.url,delete i.url):e.is("a")?n=e.attr("href"):e.is("img")&&(n=e.attr("src")),i.name&&(s=i.name,delete i.name),i.replace&&(o=i.replace,delete i.replace),i.callback&&(r=i.callback,delete i.callback);var a=openWindow(n,s,i,o);return r&&"string"==typeof r&&window[r](this,a),!1})});