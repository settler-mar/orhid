var templates=(function(){
  function pug_attr(t,e,n,f){return e!==!1&&null!=e&&(e||"class"!==t&&"style"!==t)?e===!0?" "+(f?t:t+'="'+t+'"'):("function"==typeof e.toJSON&&(e=e.toJSON()),"string"==typeof e||(e=JSON.stringify(e),n||e.indexOf('"')===-1)?(n&&(e=pug_escape(e))," "+t+'="'+e+'"'):" "+t+"='"+e.replace(/'/g,"&#39;")+"'"):""}
  function pug_classes(s,r){return Array.isArray(s)?pug_classes_array(s,r):s&&"object"==typeof s?pug_classes_object(s):s||""}
  function pug_classes_array(r,a){for(var s,e="",u="",c=Array.isArray(a),g=0;g<r.length;g++)s=pug_classes(r[g]),s&&(c&&a[g]&&(s=pug_escape(s)),e=e+u+s,u=" ");return e}
  function pug_classes_object(r){var a="",n="";for(var o in r)o&&r[o]&&pug_has_own_property.call(r,o)&&(a=a+n+o,n=" ");return a}
  function pug_escape(e){var a=""+e,t=pug_match_html.exec(a);if(!t)return e;var r,c,n,s="";for(r=t.index,c=0;r<a.length;r++){switch(a.charCodeAt(r)){case 34:n="&quot;";break;case 38:n="&amp;";break;case 60:n="&lt;";break;case 62:n="&gt;";break;default:continue}c!==r&&(s+=a.substring(c,r)),c=r+1,s+=n}return c!==r?s+a.substring(c,r):s}
  var pug_has_own_property=Object.prototype.hasOwnProperty;
  var pug_match_html=/["&<>]/;
  ;return {
    chat_message:function (locals) {var pug_html = "", pug_mixins = {}, pug_interp;;var locals_for_with = (locals || {});(function (created_at, created_at_str, dop_class, message) {dop_class='еу'
      pug_html = pug_html + "\u003Cdiv" + (pug_attr("class", pug_classes(["mess",dop_class], [false,true]), false, false)+pug_attr("send_time", created_at, true, false)) + "\u003E\u003Cp\u003E\u003Cspan\u003E" + (pug_escape(null == (pug_interp = created_at_str) ? "" : pug_interp)) + "\u003C\u002Fspan\u003E\u003C\u002Fp\u003E\u003Cspan\u003E" + (pug_escape(null == (pug_interp = message) ? "" : pug_interp)) + "\u003C\u002Fspan\u003E\u003C\u002Fdiv\u003E";}.call(this,"created_at" in locals_for_with?locals_for_with.created_at:typeof created_at!=="undefined"?created_at:undefined,"created_at_str" in locals_for_with?locals_for_with.created_at_str:typeof created_at_str!=="undefined"?created_at_str:undefined,"dop_class" in locals_for_with?locals_for_with.dop_class:typeof dop_class!=="undefined"?dop_class:undefined,"message" in locals_for_with?locals_for_with.message:typeof message!=="undefined"?message:undefined));;return pug_html;},
    chat_user:function (locals) {var pug_html = "", pug_mixins = {}, pug_interp;;var locals_for_with = (locals || {});(function (id, in_new, is_online, is_this, out_new, photo, this_user, username) {if (is_online) {
      is_online='us_online'
    }
    else {
      is_online=''
    }
      if (this_user) {
        is_this=' this_user'
      }
      else {
        is_this=''
      }
      pug_html = pug_html + "\u003Ca" + (pug_attr("class", pug_classes(["us_on",is_online+is_this], [false,true]), false, false)+pug_attr("user", id, true, false)+pug_attr("href", '/chat/'+id, true, false)) + "\u003E\u003Cimg" + (pug_attr("src", photo, true, false)) + "\u002F\u003E\u003Cspan\u003E" + (pug_escape(null == (pug_interp = username) ? "" : pug_interp)) + "\u003C\u002Fspan\u003E";
      if (in_new) {
        pug_html = pug_html + "\u003Cb class=\"new_mes\"\u003E" + (pug_escape(null == (pug_interp = in_new) ? "" : pug_interp)) + "\u003C\u002Fb\u003E";
      }
      else
      if (out_new) {
        pug_html = pug_html + "\u003Cb\u003E" + (pug_escape(null == (pug_interp = out_new) ? "" : pug_interp)) + "\u003C\u002Fb\u003E";
      }
      pug_html = pug_html + "\u003C\u002Fa\u003E";}.call(this,"id" in locals_for_with?locals_for_with.id:typeof id!=="undefined"?id:undefined,"in_new" in locals_for_with?locals_for_with.in_new:typeof in_new!=="undefined"?in_new:undefined,"is_online" in locals_for_with?locals_for_with.is_online:typeof is_online!=="undefined"?is_online:undefined,"is_this" in locals_for_with?locals_for_with.is_this:typeof is_this!=="undefined"?is_this:undefined,"out_new" in locals_for_with?locals_for_with.out_new:typeof out_new!=="undefined"?out_new:undefined,"photo" in locals_for_with?locals_for_with.photo:typeof photo!=="undefined"?photo:undefined,"this_user" in locals_for_with?locals_for_with.this_user:typeof this_user!=="undefined"?this_user:undefined,"username" in locals_for_with?locals_for_with.username:typeof username!=="undefined"?username:undefined));;return pug_html;},
  }})();