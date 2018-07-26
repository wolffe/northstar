/*! ImagePress - 1.1 - Copyright 2014, 2015 */

// minified list.js // 1.1.1 // published
!function(){function a(b,c,d){var e=a.resolve(b);if(null==e){d=d||b,c=c||"root";var f=new Error('Failed to require "'+d+'" from "'+c+'"');throw f.path=d,f.parent=c,f.require=!0,f}var g=a.modules[e];if(!g._resolving&&!g.exports){var h={};h.exports={},h.client=h.component=!0,g._resolving=!0,g.call(this,h.exports,a.relative(e),h),delete g._resolving,g.exports=h.exports}return g.exports}a.modules={},a.aliases={},a.resolve=function(b){"/"===b.charAt(0)&&(b=b.slice(1));for(var c=[b,b+".js",b+".json",b+"/index.js",b+"/index.json"],d=0;d<c.length;d++){var b=c[d];if(a.modules.hasOwnProperty(b))return b;if(a.aliases.hasOwnProperty(b))return a.aliases[b]}},a.normalize=function(a,b){var c=[];if("."!=b.charAt(0))return b;a=a.split("/"),b=b.split("/");for(var d=0;d<b.length;++d)".."==b[d]?a.pop():"."!=b[d]&&""!=b[d]&&c.push(b[d]);return a.concat(c).join("/")},a.register=function(b,c){a.modules[b]=c},a.alias=function(b,c){if(!a.modules.hasOwnProperty(b))throw new Error('Failed to alias "'+b+'", it does not exist');a.aliases[c]=b},a.relative=function(b){function c(a,b){for(var c=a.length;c--;)if(a[c]===b)return c;return-1}function d(c){var e=d.resolve(c);return a(e,b,c)}var e=a.normalize(b,"..");return d.resolve=function(d){var f=d.charAt(0);if("/"==f)return d.slice(1);if("."==f)return a.normalize(e,d);var g=b.split("/"),h=c(g,"deps")+1;return h||(h=0),d=g.slice(0,h+1).join("/")+"/deps/"+d},d.exists=function(b){return a.modules.hasOwnProperty(d.resolve(b))},d},a.register("component-classes/index.js",function(a,b,c){function d(a){if(!a)throw new Error("A DOM element reference is required");this.el=a,this.list=a.classList}var e=b("indexof"),f=/\s+/,g=Object.prototype.toString;c.exports=function(a){return new d(a)},d.prototype.add=function(a){if(this.list)return this.list.add(a),this;var b=this.array(),c=e(b,a);return~c||b.push(a),this.el.className=b.join(" "),this},d.prototype.remove=function(a){if("[object RegExp]"==g.call(a))return this.removeMatching(a);if(this.list)return this.list.remove(a),this;var b=this.array(),c=e(b,a);return~c&&b.splice(c,1),this.el.className=b.join(" "),this},d.prototype.removeMatching=function(a){for(var b=this.array(),c=0;c<b.length;c++)a.test(b[c])&&this.remove(b[c]);return this},d.prototype.toggle=function(a,b){return this.list?("undefined"!=typeof b?b!==this.list.toggle(a,b)&&this.list.toggle(a):this.list.toggle(a),this):("undefined"!=typeof b?b?this.add(a):this.remove(a):this.has(a)?this.remove(a):this.add(a),this)},d.prototype.array=function(){var a=this.el.className.replace(/^\s+|\s+$/g,""),b=a.split(f);return""===b[0]&&b.shift(),b},d.prototype.has=d.prototype.contains=function(a){return this.list?this.list.contains(a):!!~e(this.array(),a)}}),a.register("segmentio-extend/index.js",function(a,b,c){c.exports=function(a){for(var b,c=Array.prototype.slice.call(arguments,1),d=0;b=c[d];d++)if(b)for(var e in b)a[e]=b[e];return a}}),a.register("component-indexof/index.js",function(a,b,c){c.exports=function(a,b){if(a.indexOf)return a.indexOf(b);for(var c=0;c<a.length;++c)if(a[c]===b)return c;return-1}}),a.register("component-event/index.js",function(a){var b=window.addEventListener?"addEventListener":"attachEvent",c=window.removeEventListener?"removeEventListener":"detachEvent",d="addEventListener"!==b?"on":"";a.bind=function(a,c,e,f){return a[b](d+c,e,f||!1),e},a.unbind=function(a,b,e,f){return a[c](d+b,e,f||!1),e}}),a.register("timoxley-to-array/index.js",function(a,b,c){function d(a){return"[object Array]"===Object.prototype.toString.call(a)}c.exports=function(a){if("undefined"==typeof a)return[];if(null===a)return[null];if(a===window)return[window];if("string"==typeof a)return[a];if(d(a))return a;if("number"!=typeof a.length)return[a];if("function"==typeof a&&a instanceof Function)return[a];for(var b=[],c=0;c<a.length;c++)(Object.prototype.hasOwnProperty.call(a,c)||c in a)&&b.push(a[c]);return b.length?b:[]}}),a.register("javve-events/index.js",function(a,b){var c=b("event"),d=b("to-array");a.bind=function(a,b,e,f){a=d(a);for(var g=0;g<a.length;g++)c.bind(a[g],b,e,f)},a.unbind=function(a,b,e,f){a=d(a);for(var g=0;g<a.length;g++)c.unbind(a[g],b,e,f)}}),a.register("javve-get-by-class/index.js",function(a,b,c){c.exports=function(){return document.getElementsByClassName?function(a,b,c){return c?a.getElementsByClassName(b)[0]:a.getElementsByClassName(b)}:document.querySelector?function(a,b,c){return b="."+b,c?a.querySelector(b):a.querySelectorAll(b)}:function(a,b,c){var d=[],e="*";null==a&&(a=document);for(var f=a.getElementsByTagName(e),g=f.length,h=new RegExp("(^|\\s)"+b+"(\\s|$)"),i=0,j=0;g>i;i++)if(h.test(f[i].className)){if(c)return f[i];d[j]=f[i],j++}return d}}()}),a.register("javve-get-attribute/index.js",function(a,b,c){c.exports=function(a,b){var c=a.getAttribute&&a.getAttribute(b)||null;if(!c)for(var d=a.attributes,e=d.length,f=0;e>f;f++)void 0!==b[f]&&b[f].nodeName===b&&(c=b[f].nodeValue);return c}}),a.register("javve-natural-sort/index.js",function(a,b,c){c.exports=function(a,b,c){var d,e,f=/(^-?[0-9]+(\.?[0-9]*)[df]?e?[0-9]?$|^0x[0-9a-f]+$|[0-9]+)/gi,g=/(^[ ]*|[ ]*$)/g,h=/(^([\w ]+,?[\w ]+)?[\w ]+,?[\w ]+\d+:\d+(:\d+)?[\w ]?|^\d{1,4}[\/\-]\d{1,4}[\/\-]\d{1,4}|^\w+, \w+ \d+, \d{4})/,i=/^0x[0-9a-f]+$/i,j=/^0/,c=c||{},k=function(a){return c.insensitive&&(""+a).toLowerCase()||""+a},l=k(a).replace(g,"")||"",m=k(b).replace(g,"")||"",n=l.replace(f,"\x00$1\x00").replace(/\0$/,"").replace(/^\0/,"").split("\x00"),o=m.replace(f,"\x00$1\x00").replace(/\0$/,"").replace(/^\0/,"").split("\x00"),p=parseInt(l.match(i))||1!=n.length&&l.match(h)&&Date.parse(l),q=parseInt(m.match(i))||p&&m.match(h)&&Date.parse(m)||null,r=c.desc?-1:1;if(q){if(q>p)return-1*r;if(p>q)return 1*r}for(var s=0,t=Math.max(n.length,o.length);t>s;s++){if(d=!(n[s]||"").match(j)&&parseFloat(n[s])||n[s]||0,e=!(o[s]||"").match(j)&&parseFloat(o[s])||o[s]||0,isNaN(d)!==isNaN(e))return isNaN(d)?1:-1;if(typeof d!=typeof e&&(d+="",e+=""),e>d)return-1*r;if(d>e)return 1*r}return 0}}),a.register("javve-to-string/index.js",function(a,b,c){c.exports=function(a){return a=void 0===a?"":a,a=null===a?"":a,a=a.toString()}}),a.register("component-type/index.js",function(a,b,c){var d=Object.prototype.toString;c.exports=function(a){switch(d.call(a)){case"[object Date]":return"date";case"[object RegExp]":return"regexp";case"[object Arguments]":return"arguments";case"[object Array]":return"array";case"[object Error]":return"error"}return null===a?"null":void 0===a?"undefined":a!==a?"nan":a&&1===a.nodeType?"element":typeof a.valueOf()}}),a.register("list.js/index.js",function(a,b,c){!function(a,d){"use strict";var e=a.document,f=b("get-by-class"),g=b("extend"),h=b("indexof"),i=function(a,c,j){var k,l=this,m=b("./src/item")(l),n=b("./src/add-async")(l),o=b("./src/parse")(l);k={start:function(){l.listClass="list",l.searchClass="search",l.sortClass="sort",l.page=200,l.i=1,l.items=[],l.visibleItems=[],l.matchingItems=[],l.searched=!1,l.filtered=!1,l.handlers={updated:[]},l.plugins={},l.helpers={getByClass:f,extend:g,indexOf:h},g(l,c),l.listContainer="string"==typeof a?e.getElementById(a):a,l.listContainer&&(l.list=f(l.listContainer,l.listClass,!0),l.templater=b("./src/templater")(l),l.search=b("./src/search")(l),l.filter=b("./src/filter")(l),l.sort=b("./src/sort")(l),this.items(),l.update(),this.plugins())},items:function(){o(l.list),j!==d&&l.add(j)},plugins:function(){for(var a=0;a<l.plugins.length;a++){var b=l.plugins[a];l[b.name]=b,b.init(l,i)}}},this.add=function(a,b){if(b)return n(a,b),void 0;var c=[],e=!1;a[0]===d&&(a=[a]);for(var f=0,g=a.length;g>f;f++){var h=null;a[f]instanceof m?(h=a[f],h.reload()):(e=l.items.length>l.page?!0:!1,h=new m(a[f],d,e)),l.items.push(h),c.push(h)}return l.update(),c},this.show=function(a,b){return this.i=a,this.page=b,l.update(),l},this.remove=function(a,b,c){for(var d=0,e=0,f=l.items.length;f>e;e++)l.items[e].values()[a]==b&&(l.templater.remove(l.items[e],c),l.items.splice(e,1),f--,e--,d++);return l.update(),d},this.get=function(a,b){for(var c=[],d=0,e=l.items.length;e>d;d++){var f=l.items[d];f.values()[a]==b&&c.push(f)}return c},this.size=function(){return l.items.length},this.clear=function(){return l.templater.clear(),l.items=[],l},this.on=function(a,b){return l.handlers[a].push(b),l},this.off=function(a,b){var c=l.handlers[a],d=h(c,b);return d>-1&&c.splice(d,1),l},this.trigger=function(a){for(var b=l.handlers[a].length;b--;)l.handlers[a][b](l);return l},this.reset={filter:function(){for(var a=l.items,b=a.length;b--;)a[b].filtered=!1;return l},search:function(){for(var a=l.items,b=a.length;b--;)a[b].found=!1;return l}},this.update=function(){var a=l.items,b=a.length;l.visibleItems=[],l.matchingItems=[],l.templater.clear();for(var c=0;b>c;c++)a[c].matching()&&l.matchingItems.length+1>=l.i&&l.visibleItems.length<l.page?(a[c].show(),l.visibleItems.push(a[c]),l.matchingItems.push(a[c])):a[c].matching()?(l.matchingItems.push(a[c]),a[c].hide()):a[c].hide();return l.trigger("updated"),l},k.start()};c.exports=i}(window)}),a.register("list.js/src/search.js",function(a,b,c){var d=b("events"),e=b("get-by-class"),f=b("to-string");c.exports=function(a){var b,c,g,h,i={resetList:function(){a.i=1,a.templater.clear(),h=void 0},setOptions:function(a){2==a.length&&a[1]instanceof Array?c=a[1]:2==a.length&&"function"==typeof a[1]?h=a[1]:3==a.length&&(c=a[1],h=a[2])},setColumns:function(){c=void 0===c?i.toArray(a.items[0].values()):c},setSearchString:function(a){a=f(a).toLowerCase(),a=a.replace(/[-[\]{}()*+?.,\\^$|#]/g,"\\$&"),g=a},toArray:function(a){var b=[];for(var c in a)b.push(c);return b}},j={list:function(){for(var b=0,c=a.items.length;c>b;b++)j.item(a.items[b])},item:function(a){a.found=!1;for(var b=0,d=c.length;d>b;b++)if(j.values(a.values(),c[b]))return a.found=!0,void 0},values:function(a,c){return a.hasOwnProperty(c)&&(b=f(a[c]).toLowerCase(),""!==g&&b.search(g)>-1)?!0:!1},reset:function(){a.reset.search(),a.searched=!1}},k=function(b){return a.trigger("searchStart"),i.resetList(),i.setSearchString(b),i.setOptions(arguments),i.setColumns(),""===g?j.reset():(a.searched=!0,h?h(g,c):j.list()),a.update(),a.trigger("searchComplete"),a.visibleItems};return a.handlers.searchStart=a.handlers.searchStart||[],a.handlers.searchComplete=a.handlers.searchComplete||[],d.bind(e(a.listContainer,a.searchClass),"keyup",function(b){var c=b.target||b.srcElement,d=""===c.value&&!a.searched;d||k(c.value)}),d.bind(e(a.listContainer,a.searchClass),"input",function(a){var b=a.target||a.srcElement;""===b.value&&k("")}),a.helpers.toString=f,k}}),a.register("list.js/src/sort.js",function(a,b,c){var d=b("natural-sort"),e=b("classes"),f=b("events"),g=b("get-by-class"),h=b("get-attribute");c.exports=function(a){a.sortFunction=a.sortFunction||function(a,b,c){return c.desc="desc"==c.order?!0:!1,d(a.values()[c.valueName],b.values()[c.valueName],c)};var b={els:void 0,clear:function(){for(var a=0,c=b.els.length;c>a;a++)e(b.els[a]).remove("asc"),e(b.els[a]).remove("desc")},getOrder:function(a){var b=h(a,"data-order");return"asc"==b||"desc"==b?b:e(a).has("desc")?"asc":e(a).has("asc")?"desc":"asc"},getInSensitive:function(a,b){var c=h(a,"data-insensitive");b.insensitive="true"===c?!0:!1},setOrder:function(a){for(var c=0,d=b.els.length;d>c;c++){var f=b.els[c];if(h(f,"data-sort")===a.valueName){var g=h(f,"data-order");"asc"==g||"desc"==g?g==a.order&&e(f).add(a.order):e(f).add(a.order)}}}},c=function(){a.trigger("sortStart"),options={};var c=arguments[0].currentTarget||arguments[0].srcElement||void 0;c?(options.valueName=h(c,"data-sort"),b.getInSensitive(c,options),options.order=b.getOrder(c)):(options=arguments[1]||options,options.valueName=arguments[0],options.order=options.order||"asc",options.insensitive="undefined"==typeof options.insensitive?!0:options.insensitive),b.clear(),b.setOrder(options),options.sortFunction=options.sortFunction||a.sortFunction,a.items.sort(function(a,b){return options.sortFunction(a,b,options)}),a.update(),a.trigger("sortComplete")};return a.handlers.sortStart=a.handlers.sortStart||[],a.handlers.sortComplete=a.handlers.sortComplete||[],b.els=g(a.listContainer,a.sortClass),f.bind(b.els,"click",c),a.on("searchStart",b.clear),a.on("filterStart",b.clear),a.helpers.classes=e,a.helpers.naturalSort=d,a.helpers.events=f,a.helpers.getAttribute=h,c}}),a.register("list.js/src/item.js",function(a,b,c){c.exports=function(a){return function(b,c,d){var e=this;this._values={},this.found=!1,this.filtered=!1;var f=function(b,c,d){if(void 0===c)d?e.values(b,d):e.values(b);else{e.elm=c;var f=a.templater.get(e,b);e.values(f)}};this.values=function(b,c){if(void 0===b)return e._values;for(var d in b)e._values[d]=b[d];c!==!0&&a.templater.set(e,e.values())},this.show=function(){a.templater.show(e)},this.hide=function(){a.templater.hide(e)},this.matching=function(){return a.filtered&&a.searched&&e.found&&e.filtered||a.filtered&&!a.searched&&e.filtered||!a.filtered&&a.searched&&e.found||!a.filtered&&!a.searched},this.visible=function(){return e.elm.parentNode==a.list?!0:!1},f(b,c,d)}}}),a.register("list.js/src/templater.js",function(a,b,c){var d=b("get-by-class"),e=function(a){function b(b){if(void 0===b){for(var c=a.list.childNodes,d=0,e=c.length;e>d;d++)if(void 0===c[d].data)return c[d];return null}if(-1!==b.indexOf("<")){var f=document.createElement("div");return f.innerHTML=b,f.firstChild}return document.getElementById(a.item)}var c=b(a.item),e=this;this.get=function(a,b){e.create(a);for(var c={},f=0,g=b.length;g>f;f++){var h=d(a.elm,b[f],!0);c[b[f]]=h?h.innerHTML:""}return c},this.set=function(a,b){if(!e.create(a))for(var c in b)if(b.hasOwnProperty(c)){var f=d(a.elm,c,!0);f&&("IMG"===f.tagName&&""!==b[c]?f.src=b[c]:f.innerHTML=b[c])}},this.create=function(a){if(void 0!==a.elm)return!1;var b=c.cloneNode(!0);return b.removeAttribute("id"),a.elm=b,e.set(a,a.values()),!0},this.remove=function(b){a.list.removeChild(b.elm)},this.show=function(b){e.create(b),a.list.appendChild(b.elm)},this.hide=function(b){void 0!==b.elm&&b.elm.parentNode===a.list&&a.list.removeChild(b.elm)},this.clear=function(){if(a.list.hasChildNodes())for(;a.list.childNodes.length>=1;)a.list.removeChild(a.list.firstChild)}};c.exports=function(a){return new e(a)}}),a.register("list.js/src/filter.js",function(a,b,c){c.exports=function(a){return a.handlers.filterStart=a.handlers.filterStart||[],a.handlers.filterComplete=a.handlers.filterComplete||[],function(b){if(a.trigger("filterStart"),a.i=1,a.reset.filter(),void 0===b)a.filtered=!1;else{a.filtered=!0;for(var c=a.items,d=0,e=c.length;e>d;d++){var f=c[d];f.filtered=b(f)?!0:!1}}return a.update(),a.trigger("filterComplete"),a.visibleItems}}}),a.register("list.js/src/add-async.js",function(a,b,c){c.exports=function(a){return function(b,c,d){var e=b.splice(0,10000);d=d||[],d=d.concat(a.add(e)),b.length>0?setTimeout(function(){addAsync(b,c,d)},10):(a.update(),c(d))}}}),a.register("list.js/src/parse.js",function(a,b,c){c.exports=function(a){var c=b("./item")(a),d=function(a){for(var b=a.childNodes,c=[],d=0,e=b.length;e>d;d++)void 0===b[d].data&&c.push(b[d]);return c},e=function(b,d){for(var e=0,f=b.length;f>e;e++)a.items.push(new c(d,b[e]))},f=function(b,c){var d=b.splice(0,10000);e(d,c),b.length>0?setTimeout(function(){init.items.indexAsync(b,c)},10):a.update()};return function(){var b=d(a.list),c=a.valueNames;a.indexAsync?f(b,c):e(b,c)}}}),a.alias("component-classes/index.js","list.js/deps/classes/index.js"),a.alias("component-classes/index.js","classes/index.js"),a.alias("component-indexof/index.js","component-classes/deps/indexof/index.js"),a.alias("segmentio-extend/index.js","list.js/deps/extend/index.js"),a.alias("segmentio-extend/index.js","extend/index.js"),a.alias("component-indexof/index.js","list.js/deps/indexof/index.js"),a.alias("component-indexof/index.js","indexof/index.js"),a.alias("javve-events/index.js","list.js/deps/events/index.js"),a.alias("javve-events/index.js","events/index.js"),a.alias("component-event/index.js","javve-events/deps/event/index.js"),a.alias("timoxley-to-array/index.js","javve-events/deps/to-array/index.js"),a.alias("javve-get-by-class/index.js","list.js/deps/get-by-class/index.js"),a.alias("javve-get-by-class/index.js","get-by-class/index.js"),a.alias("javve-get-attribute/index.js","list.js/deps/get-attribute/index.js"),a.alias("javve-get-attribute/index.js","get-attribute/index.js"),a.alias("javve-natural-sort/index.js","list.js/deps/natural-sort/index.js"),a.alias("javve-natural-sort/index.js","natural-sort/index.js"),a.alias("javve-to-string/index.js","list.js/deps/to-string/index.js"),a.alias("javve-to-string/index.js","list.js/deps/to-string/index.js"),a.alias("javve-to-string/index.js","to-string/index.js"),a.alias("javve-to-string/index.js","javve-to-string/index.js"),a.alias("component-type/index.js","list.js/deps/type/index.js"),a.alias("component-type/index.js","type/index.js"),"object"==typeof exports?module.exports=a("list.js"):"function"==typeof define&&define.amd?define(function(){return a("list.js")}):this.List=a("list.js")}();

// minified list.pagination.js // 0.1.1 // published
!function(){function a(b,c,d){var e=a.resolve(b);if(null==e){d=d||b,c=c||"root";var f=new Error('Failed to require "'+d+'" from "'+c+'"');throw f.path=d,f.parent=c,f.require=!0,f}var g=a.modules[e];if(!g._resolving&&!g.exports){var h={};h.exports={},h.client=h.component=!0,g._resolving=!0,g.call(this,h.exports,a.relative(e),h),delete g._resolving,g.exports=h.exports}return g.exports}a.modules={},a.aliases={},a.resolve=function(b){"/"===b.charAt(0)&&(b=b.slice(1));for(var c=[b,b+".js",b+".json",b+"/index.js",b+"/index.json"],d=0;d<c.length;d++){var b=c[d];if(a.modules.hasOwnProperty(b))return b;if(a.aliases.hasOwnProperty(b))return a.aliases[b]}},a.normalize=function(a,b){var c=[];if("."!=b.charAt(0))return b;a=a.split("/"),b=b.split("/");for(var d=0;d<b.length;++d)".."==b[d]?a.pop():"."!=b[d]&&""!=b[d]&&c.push(b[d]);return a.concat(c).join("/")},a.register=function(b,c){a.modules[b]=c},a.alias=function(b,c){if(!a.modules.hasOwnProperty(b))throw new Error('Failed to alias "'+b+'", it does not exist');a.aliases[c]=b},a.relative=function(b){function c(a,b){for(var c=a.length;c--;)if(a[c]===b)return c;return-1}function d(c){var e=d.resolve(c);return a(e,b,c)}var e=a.normalize(b,"..");return d.resolve=function(d){var f=d.charAt(0);if("/"==f)return d.slice(1);if("."==f)return a.normalize(e,d);var g=b.split("/"),h=c(g,"deps")+1;return h||(h=0),d=g.slice(0,h+1).join("/")+"/deps/"+d},d.exists=function(b){return a.modules.hasOwnProperty(d.resolve(b))},d},a.register("component-classes/index.js",function(a,b,c){function d(a){if(!a)throw new Error("A DOM element reference is required");this.el=a,this.list=a.classList}var e=b("indexof"),f=/\s+/,g=Object.prototype.toString;c.exports=function(a){return new d(a)},d.prototype.add=function(a){if(this.list)return this.list.add(a),this;var b=this.array(),c=e(b,a);return~c||b.push(a),this.el.className=b.join(" "),this},d.prototype.remove=function(a){if("[object RegExp]"==g.call(a))return this.removeMatching(a);if(this.list)return this.list.remove(a),this;var b=this.array(),c=e(b,a);return~c&&b.splice(c,1),this.el.className=b.join(" "),this},d.prototype.removeMatching=function(a){for(var b=this.array(),c=0;c<b.length;c++)a.test(b[c])&&this.remove(b[c]);return this},d.prototype.toggle=function(a,b){return this.list?("undefined"!=typeof b?b!==this.list.toggle(a,b)&&this.list.toggle(a):this.list.toggle(a),this):("undefined"!=typeof b?b?this.add(a):this.remove(a):this.has(a)?this.remove(a):this.add(a),this)},d.prototype.array=function(){var a=this.el.className.replace(/^\s+|\s+$/g,""),b=a.split(f);return""===b[0]&&b.shift(),b},d.prototype.has=d.prototype.contains=function(a){return this.list?this.list.contains(a):!!~e(this.array(),a)}}),a.register("component-event/index.js",function(a){var b=window.addEventListener?"addEventListener":"attachEvent",c=window.removeEventListener?"removeEventListener":"detachEvent",d="addEventListener"!==b?"on":"";a.bind=function(a,c,e,f){return a[b](d+c,e,f||!1),e},a.unbind=function(a,b,e,f){return a[c](d+b,e,f||!1),e}}),a.register("component-indexof/index.js",function(a,b,c){c.exports=function(a,b){if(a.indexOf)return a.indexOf(b);for(var c=0;c<a.length;++c)if(a[c]===b)return c;return-1}}),a.register("list.pagination.js/index.js",function(a,b,c){var d=b("classes"),e=b("event");c.exports=function(a){a=a||{};var b,c,f=function(){var e,f=c.matchingItems.length,i=c.i,j=c.page,k=Math.ceil(f/j),l=Math.ceil(i/j),m=a.innerWindow||2,n=a.left||a.outerWindow||0,o=a.right||a.outerWindow||0;o=k-o,b.clear();for(var p=1;k>=p;p++){var q=l===p?"active":"";g.number(p,n,o,l,m)?(e=b.add({page:p,dotted:!1})[0],q&&d(e.elm).add(q),h(e.elm,p,j)):g.dotted(p,n,o,l,m,b.size())&&(e=b.add({page:"...",dotted:!0})[0],d(e.elm).add("disabled"))}},g={number:function(a,b,c,d,e){return this.left(a,b)||this.right(a,c)||this.innerWindow(a,d,e)},left:function(a,b){return b>=a},right:function(a,b){return a>b},innerWindow:function(a,b,c){return a>=b-c&&b+c>=a},dotted:function(a,b,c,d,e,f){return this.dottedLeft(a,b,c,d,e)||this.dottedRight(a,b,c,d,e,f)},dottedLeft:function(a,b,c,d,e){return a==b+1&&!this.innerWindow(a,d,e)&&!this.right(a,c)},dottedRight:function(a,c,d,e,f,g){return b.items[g-1].values().dotted?!1:a==d&&!this.innerWindow(a,e,f)&&!this.right(a,d)}},h=function(a,b,d){e.bind(a,"click",function(){c.show((b-1)*d+1,d)})};return{init:function(d){c=d,b=new List(c.listContainer.id,{listClass:a.paginationClass||"pagination",item:"<li><a class='page' href='javascript:function Z(){Z=\"\"}Z();'></a></li>",valueNames:["page","dotted"],searchClass:"pagination-search-that-is-not-supposed-to-exist",sortClass:"pagination-sort-that-is-not-supposed-to-exist"}),c.on("updated",f),f()},name:a.name||"pagination"}}}),a.alias("component-classes/index.js","list.pagination.js/deps/classes/index.js"),a.alias("component-classes/index.js","classes/index.js"),a.alias("component-indexof/index.js","component-classes/deps/indexof/index.js"),a.alias("component-event/index.js","list.pagination.js/deps/event/index.js"),a.alias("component-event/index.js","event/index.js"),a.alias("component-indexof/index.js","list.pagination.js/deps/indexof/index.js"),a.alias("component-indexof/index.js","indexof/index.js"),a.alias("list.pagination.js/index.js","list.pagination.js/index.js"),"object"==typeof exports?module.exports=a("list.pagination.js"):"function"==typeof define&&define.amd?define(function(){return a("list.pagination.js")}):this.ListPagination=a("list.pagination.js")}();

// minified spectrum.js // 1.7.0 // published
!function(t){"use strict";"function"==typeof define&&define.amd?define(["jquery"],t):"object"==typeof exports&&"object"==typeof module?module.exports=t:t(jQuery)}(function(t,e){"use strict";function r(e,r,n,a){for(var i=[],s=0;s<e.length;s++){var o=e[s];if(o){var l=tinycolor(o),c=l.toHsl().l<.5?"sp-thumb-el sp-thumb-dark":"sp-thumb-el sp-thumb-light";c+=tinycolor.equals(r,o)?" sp-thumb-active":"";var f=l.toString(a.preferredFormat||"rgb"),u=b?"background-color:"+l.toRgbString():"filter:"+l.toFilter();i.push('<span title="'+f+'" data-color="'+l.toRgbString()+'" class="'+c+'"><span class="sp-thumb-inner" style="'+u+';" /></span>')}else{var h="sp-clear-display";i.push(t("<div />").append(t('<span data-color="" style="background-color:transparent;" class="'+h+'"></span>').attr("title",a.noColorSelectedText)).html())}}return"<div class='sp-cf "+n+"'>"+i.join("")+"</div>"}function n(){for(var t=0;t<p.length;t++)p[t]&&p[t].hide()}function a(e,r){var n=t.extend({},d,e);return n.callbacks={move:c(n.move,r),change:c(n.change,r),show:c(n.show,r),hide:c(n.hide,r),beforeShow:c(n.beforeShow,r)},n}function i(i,o){function c(){if(W.showPaletteOnly&&(W.showPalette=!0),Dt.text(W.showPaletteOnly?W.togglePaletteMoreText:W.togglePaletteLessText),W.palette){dt=W.palette.slice(0),pt=t.isArray(dt[0])?dt:[dt],gt={};for(var e=0;e<pt.length;e++)for(var r=0;r<pt[e].length;r++){var n=tinycolor(pt[e][r]).toRgbString();gt[n]=!0}}kt.toggleClass("sp-flat",X),kt.toggleClass("sp-input-disabled",!W.showInput),kt.toggleClass("sp-alpha-enabled",W.showAlpha),kt.toggleClass("sp-clear-enabled",Jt),kt.toggleClass("sp-buttons-disabled",!W.showButtons),kt.toggleClass("sp-palette-buttons-disabled",!W.togglePaletteOnly),kt.toggleClass("sp-palette-disabled",!W.showPalette),kt.toggleClass("sp-palette-only",W.showPaletteOnly),kt.toggleClass("sp-initial-disabled",!W.showInitial),kt.addClass(W.className).addClass(W.containerClassName),z()}function d(){function e(e){return e.data&&e.data.ignore?(O(t(e.target).closest(".sp-thumb-el").data("color")),j()):(O(t(e.target).closest(".sp-thumb-el").data("color")),j(),I(!0),W.hideAfterPaletteSelect&&T()),!1}if(g&&kt.find("*:not(input)").attr("unselectable","on"),c(),Bt&&_t.after(Lt).hide(),Jt||jt.hide(),X)_t.after(kt).hide();else{var r="parent"===W.appendTo?_t.parent():t(W.appendTo);1!==r.length&&(r=t("body")),r.append(kt)}y(),Kt.bind("click.spectrum touchstart.spectrum",function(e){xt||A(),e.stopPropagation(),t(e.target).is("input")||e.preventDefault()}),(_t.is(":disabled")||W.disabled===!0)&&V(),kt.click(l),Ft.change(P),Ft.bind("paste",function(){setTimeout(P,1)}),Ft.keydown(function(t){13==t.keyCode&&P()}),Et.text(W.cancelText),Et.bind("click.spectrum",function(t){t.stopPropagation(),t.preventDefault(),F(),T()}),jt.attr("title",W.clearText),jt.bind("click.spectrum",function(t){t.stopPropagation(),t.preventDefault(),Qt=!0,j(),X&&I(!0)}),qt.text(W.chooseText),qt.bind("click.spectrum",function(t){t.stopPropagation(),t.preventDefault(),g&&Ft.is(":focus")&&Ft.trigger("change"),E()&&(I(!0),T())}),Dt.text(W.showPaletteOnly?W.togglePaletteMoreText:W.togglePaletteLessText),Dt.bind("click.spectrum",function(t){t.stopPropagation(),t.preventDefault(),W.showPaletteOnly=!W.showPaletteOnly,W.showPaletteOnly||X||kt.css("left","-="+(St.outerWidth(!0)+5)),c()}),f(Ht,function(t,e,r){ht=t/st,Qt=!1,r.shiftKey&&(ht=Math.round(10*ht)/10),j()},S,C),f(At,function(t,e){ct=parseFloat(e/at),Qt=!1,W.showAlpha||(ht=1),j()},S,C),f(Ct,function(t,e,r){if(r.shiftKey){if(!yt){var n=ft*et,a=rt-ut*rt,i=Math.abs(t-n)>Math.abs(e-a);yt=i?"x":"y"}}else yt=null;var s=!yt||"x"===yt,o=!yt||"y"===yt;s&&(ft=parseFloat(t/et)),o&&(ut=parseFloat((rt-e)/rt)),Qt=!1,W.showAlpha||(ht=1),j()},S,C),$t?(O($t),q(),Yt=Xt||tinycolor($t).format,w($t)):q(),X&&M();var n=g?"mousedown.spectrum":"click.spectrum touchstart.spectrum";Ot.delegate(".sp-thumb-el",n,e),Nt.delegate(".sp-thumb-el:nth-child(1)",n,{ignore:!0},e)}function y(){if(G&&window.localStorage){try{var e=window.localStorage[G].split(",#");e.length>1&&(delete window.localStorage[G],t.each(e,function(t,e){w(e)}))}catch(r){}try{bt=window.localStorage[G].split(";")}catch(r){}}}function w(e){if(Y){var r=tinycolor(e).toRgbString();if(!gt[r]&&-1===t.inArray(r,bt))for(bt.push(r);bt.length>vt;)bt.shift();if(G&&window.localStorage)try{window.localStorage[G]=bt.join(";")}catch(n){}}}function _(){var t=[];if(W.showPalette)for(var e=0;e<bt.length;e++){var r=tinycolor(bt[e]).toRgbString();gt[r]||t.push(bt[e])}return t.reverse().slice(0,W.maxSelectionSize)}function x(){var e=N(),n=t.map(pt,function(t,n){return r(t,e,"sp-palette-row sp-palette-row-"+n,W)});y(),bt&&n.push(r(_(),e,"sp-palette-row sp-palette-row-selection",W)),Ot.html(n.join(""))}function k(){if(W.showInitial){var t=Wt,e=N();Nt.html(r([t,e],e,"sp-palette-row-initial",W))}}function S(){(0>=rt||0>=et||0>=at)&&z(),tt=!0,kt.addClass(mt),yt=null,_t.trigger("dragstart.spectrum",[N()])}function C(){tt=!1,kt.removeClass(mt),_t.trigger("dragstop.spectrum",[N()])}function P(){var t=Ft.val();if(null!==t&&""!==t||!Jt){var e=tinycolor(t);e.isValid()?(O(e),I(!0)):Ft.addClass("sp-validation-error")}else O(null),I(!0)}function A(){Z?T():M()}function M(){var e=t.Event("beforeShow.spectrum");return Z?void z():(_t.trigger(e,[N()]),void(J.beforeShow(N())===!1||e.isDefaultPrevented()||(n(),Z=!0,t(wt).bind("keydown.spectrum",R),t(wt).bind("click.spectrum",H),t(window).bind("resize.spectrum",U),Lt.addClass("sp-active"),kt.removeClass("sp-hidden"),z(),q(),Wt=N(),k(),J.show(Wt),_t.trigger("show.spectrum",[Wt]))))}function R(t){27===t.keyCode&&T()}function H(t){2!=t.button&&(tt||(Gt?I(!0):F(),T()))}function T(){Z&&!X&&(Z=!1,t(wt).unbind("keydown.spectrum",R),t(wt).unbind("click.spectrum",H),t(window).unbind("resize.spectrum",U),Lt.removeClass("sp-active"),kt.addClass("sp-hidden"),J.hide(N()),_t.trigger("hide.spectrum",[N()]))}function F(){O(Wt,!0)}function O(t,e){if(tinycolor.equals(t,N()))return void q();var r,n;!t&&Jt?Qt=!0:(Qt=!1,r=tinycolor(t),n=r.toHsv(),ct=n.h%360/360,ft=n.s,ut=n.v,ht=n.a),q(),r&&r.isValid()&&!e&&(Yt=Xt||r.getFormat())}function N(t){return t=t||{},Jt&&Qt?null:tinycolor.fromRatio({h:ct,s:ft,v:ut,a:Math.round(100*ht)/100},{format:t.format||Yt})}function E(){return!Ft.hasClass("sp-validation-error")}function j(){q(),J.move(N()),_t.trigger("move.spectrum",[N()])}function q(){Ft.removeClass("sp-validation-error"),D();var t=tinycolor.fromRatio({h:ct,s:1,v:1});Ct.css("background-color",t.toHexString());var e=Yt;1>ht&&(0!==ht||"name"!==e)&&("hex"===e||"hex3"===e||"hex6"===e||"name"===e)&&(e="rgb");var r=N({format:e}),n="";if(Vt.removeClass("sp-clear-display"),Vt.css("background-color","transparent"),!r&&Jt)Vt.addClass("sp-clear-display");else{var a=r.toHexString(),i=r.toRgbString();if(b||1===r.alpha?Vt.css("background-color",i):(Vt.css("background-color","transparent"),Vt.css("filter",r.toFilter())),W.showAlpha){var s=r.toRgb();s.a=0;var o=tinycolor(s).toRgbString(),l="linear-gradient(left, "+o+", "+a+")";g?Rt.css("filter",tinycolor(o).toFilter({gradientType:1},a)):(Rt.css("background","-webkit-"+l),Rt.css("background","-moz-"+l),Rt.css("background","-ms-"+l),Rt.css("background","linear-gradient(to right, "+o+", "+a+")"))}n=r.toString(e)}W.showInput&&Ft.val(n),W.showPalette&&x(),k()}function D(){var t=ft,e=ut;if(Jt&&Qt)Tt.hide(),Mt.hide(),Pt.hide();else{Tt.show(),Mt.show(),Pt.show();var r=t*et,n=rt-e*rt;r=Math.max(-nt,Math.min(et-nt,r-nt)),n=Math.max(-nt,Math.min(rt-nt,n-nt)),Pt.css({top:n+"px",left:r+"px"});var a=ht*st;Tt.css({left:a-ot/2+"px"});var i=ct*at;Mt.css({top:i-lt+"px"})}}function I(t){var e=N(),r="",n=!tinycolor.equals(e,Wt);e&&(r=e.toString(Yt),w(e)),It&&_t.val(r),t&&n&&(J.change(e),_t.trigger("change",[e]))}function z(){et=Ct.width(),rt=Ct.height(),nt=Pt.height(),it=At.width(),at=At.height(),lt=Mt.height(),st=Ht.width(),ot=Tt.width(),X||(kt.css("position","absolute"),kt.offset(W.offset?W.offset:s(kt,Kt))),D(),W.showPalette&&x(),_t.trigger("reflow.spectrum")}function B(){_t.show(),Kt.unbind("click.spectrum touchstart.spectrum"),kt.remove(),Lt.remove(),p[Ut.id]=null}function L(r,n){return r===e?t.extend({},W):n===e?W[r]:(W[r]=n,void c())}function K(){xt=!1,_t.attr("disabled",!1),Kt.removeClass("sp-disabled")}function V(){T(),xt=!0,_t.attr("disabled",!0),Kt.addClass("sp-disabled")}function $(t){W.offset=t,z()}var W=a(o,i),X=W.flat,Y=W.showSelectionPalette,G=W.localStorageKey,Q=W.theme,J=W.callbacks,U=u(z,10),Z=!1,tt=!1,et=0,rt=0,nt=0,at=0,it=0,st=0,ot=0,lt=0,ct=0,ft=0,ut=0,ht=1,dt=[],pt=[],gt={},bt=W.selectionPalette.slice(0),vt=W.maxSelectionSize,mt="sp-dragging",yt=null,wt=i.ownerDocument,_t=(wt.body,t(i)),xt=!1,kt=t(m,wt).addClass(Q),St=kt.find(".sp-picker-container"),Ct=kt.find(".sp-color"),Pt=kt.find(".sp-dragger"),At=kt.find(".sp-hue"),Mt=kt.find(".sp-slider"),Rt=kt.find(".sp-alpha-inner"),Ht=kt.find(".sp-alpha"),Tt=kt.find(".sp-alpha-handle"),Ft=kt.find(".sp-input"),Ot=kt.find(".sp-palette"),Nt=kt.find(".sp-initial"),Et=kt.find(".sp-cancel"),jt=kt.find(".sp-clear"),qt=kt.find(".sp-choose"),Dt=kt.find(".sp-palette-toggle"),It=_t.is("input"),zt=It&&"color"===_t.attr("type")&&h(),Bt=It&&!X,Lt=Bt?t(v).addClass(Q).addClass(W.className).addClass(W.replacerClassName):t([]),Kt=Bt?Lt:_t,Vt=Lt.find(".sp-preview-inner"),$t=W.color||It&&_t.val(),Wt=!1,Xt=W.preferredFormat,Yt=Xt,Gt=!W.showButtons||W.clickoutFiresChange,Qt=!$t,Jt=W.allowEmpty&&!zt;d();var Ut={show:M,hide:T,toggle:A,reflow:z,option:L,enable:K,disable:V,offset:$,set:function(t){O(t),I()},get:N,destroy:B,container:kt};return Ut.id=p.push(Ut)-1,Ut}function s(e,r){var n=0,a=e.outerWidth(),i=e.outerHeight(),s=r.outerHeight(),o=e[0].ownerDocument,l=o.documentElement,c=l.clientWidth+t(o).scrollLeft(),f=l.clientHeight+t(o).scrollTop(),u=r.offset();return u.top+=s,u.left-=Math.min(u.left,u.left+a>c&&c>a?Math.abs(u.left+a-c):0),u.top-=Math.min(u.top,u.top+i>f&&f>i?Math.abs(i+s-n):n),u}function o(){}function l(t){t.stopPropagation()}function c(t,e){var r=Array.prototype.slice,n=r.call(arguments,2);return function(){return t.apply(e,n.concat(r.call(arguments)))}}function f(e,r,n,a){function i(t){t.stopPropagation&&t.stopPropagation(),t.preventDefault&&t.preventDefault(),t.returnValue=!1}function s(t){if(f){if(g&&c.documentMode<9&&!t.button)return l();var n=t.originalEvent&&t.originalEvent.touches&&t.originalEvent.touches[0],a=n&&n.pageX||t.pageX,s=n&&n.pageY||t.pageY,o=Math.max(0,Math.min(a-u.left,d)),b=Math.max(0,Math.min(s-u.top,h));p&&i(t),r.apply(e,[o,b,t])}}function o(r){var a=r.which?3==r.which:2==r.button;a||f||n.apply(e,arguments)!==!1&&(f=!0,h=t(e).height(),d=t(e).width(),u=t(e).offset(),t(c).bind(b),t(c.body).addClass("sp-dragging"),s(r),i(r))}function l(){f&&(t(c).unbind(b),t(c.body).removeClass("sp-dragging"),setTimeout(function(){a.apply(e,arguments)},0)),f=!1}r=r||function(){},n=n||function(){},a=a||function(){};var c=document,f=!1,u={},h=0,d=0,p="ontouchstart"in window,b={};b.selectstart=i,b.dragstart=i,b["touchmove mousemove"]=s,b["touchend mouseup"]=l,t(e).bind("touchstart mousedown",o)}function u(t,e,r){var n;return function(){var a=this,i=arguments,s=function(){n=null,t.apply(a,i)};r&&clearTimeout(n),(r||!n)&&(n=setTimeout(s,e))}}function h(){return t.fn.spectrum.inputTypeColorSupport()}var d={beforeShow:o,move:o,change:o,show:o,hide:o,color:!1,flat:!1,showInput:!1,allowEmpty:!1,showButtons:!0,clickoutFiresChange:!0,showInitial:!1,showPalette:!1,showPaletteOnly:!1,hideAfterPaletteSelect:!1,togglePaletteOnly:!1,showSelectionPalette:!0,localStorageKey:!1,appendTo:"body",maxSelectionSize:7,cancelText:"cancel",chooseText:"choose",togglePaletteMoreText:"more",togglePaletteLessText:"less",clearText:"Clear Color Selection",noColorSelectedText:"No Color Selected",preferredFormat:!1,className:"",containerClassName:"",replacerClassName:"",showAlpha:!1,theme:"sp-light",palette:[["#ffffff","#000000","#ff0000","#ff8000","#ffff00","#008000","#0000ff","#4b0082","#9400d3"]],selectionPalette:[],disabled:!1,offset:null},p=[],g=!!/msie/i.exec(window.navigator.userAgent),b=function(){function t(t,e){return!!~(""+t).indexOf(e)}var e=document.createElement("div"),r=e.style;return r.cssText="background-color:rgba(0,0,0,.5)",t(r.backgroundColor,"rgba")||t(r.backgroundColor,"hsla")}(),v=["<div class='sp-replacer'>","<div class='sp-preview'><div class='sp-preview-inner'></div></div>","<div class='sp-dd'>&#9660;</div>","</div>"].join(""),m=function(){var t="";if(g)for(var e=1;6>=e;e++)t+="<div class='sp-"+e+"'></div>";return["<div class='sp-container sp-hidden'>","<div class='sp-palette-container'>","<div class='sp-palette sp-thumb sp-cf'></div>","<div class='sp-palette-button-container sp-cf'>","<button type='button' class='sp-palette-toggle'></button>","</div>","</div>","<div class='sp-picker-container'>","<div class='sp-top sp-cf'>","<div class='sp-fill'></div>","<div class='sp-top-inner'>","<div class='sp-color'>","<div class='sp-sat'>","<div class='sp-val'>","<div class='sp-dragger'></div>","</div>","</div>","</div>","<div class='sp-clear sp-clear-display'>","</div>","<div class='sp-hue'>","<div class='sp-slider'></div>",t,"</div>","</div>","<div class='sp-alpha'><div class='sp-alpha-inner'><div class='sp-alpha-handle'></div></div></div>","</div>","<div class='sp-input-container sp-cf'>","<input class='sp-input' type='text' spellcheck='false'  />","</div>","<div class='sp-initial sp-thumb sp-cf'></div>","<div class='sp-button-container sp-cf'>","<a class='sp-cancel' href='#'></a>","<button type='button' class='sp-choose'></button>","</div>","</div>","</div>"].join("")}(),y="spectrum.id";t.fn.spectrum=function(e,r){if("string"==typeof e){var n=this,a=Array.prototype.slice.call(arguments,1);return this.each(function(){var r=p[t(this).data(y)];if(r){var i=r[e];if(!i)throw new Error("Spectrum: no such method: '"+e+"'");"get"==e?n=r.get():"container"==e?n=r.container:"option"==e?n=r.option.apply(r,a):"destroy"==e?(r.destroy(),t(this).removeData(y)):i.apply(r,a)}}),n}return this.spectrum("destroy").each(function(){var r=t.extend({},e,t(this).data()),n=i(this,r);t(this).data(y,n.id)})},t.fn.spectrum.load=!0,t.fn.spectrum.loadOpts={},t.fn.spectrum.draggable=f,t.fn.spectrum.defaults=d,t.fn.spectrum.inputTypeColorSupport=function w(){if("undefined"==typeof w._cachedResult){var e=t("<input type='color' value='!' />")[0];w._cachedResult="color"===e.type&&"!"!==e.value}return w._cachedResult},t.spectrum={},t.spectrum.localization={},t.spectrum.palettes={},t.fn.spectrum.processNativeColorInputs=function(){var e=t("input[type=color]");e.length&&!h()&&e.spectrum({preferredFormat:"hex6"})},function(){function t(t){var r={r:0,g:0,b:0},a=1,s=!1,o=!1;return"string"==typeof t&&(t=F(t)),"object"==typeof t&&(t.hasOwnProperty("r")&&t.hasOwnProperty("g")&&t.hasOwnProperty("b")?(r=e(t.r,t.g,t.b),s=!0,o="%"===String(t.r).substr(-1)?"prgb":"rgb"):t.hasOwnProperty("h")&&t.hasOwnProperty("s")&&t.hasOwnProperty("v")?(t.s=R(t.s),t.v=R(t.v),r=i(t.h,t.s,t.v),s=!0,o="hsv"):t.hasOwnProperty("h")&&t.hasOwnProperty("s")&&t.hasOwnProperty("l")&&(t.s=R(t.s),t.l=R(t.l),r=n(t.h,t.s,t.l),s=!0,o="hsl"),t.hasOwnProperty("a")&&(a=t.a)),a=x(a),{ok:s,format:t.format||o,r:D(255,I(r.r,0)),g:D(255,I(r.g,0)),b:D(255,I(r.b,0)),a:a}}function e(t,e,r){return{r:255*k(t,255),g:255*k(e,255),b:255*k(r,255)}}function r(t,e,r){t=k(t,255),e=k(e,255),r=k(r,255);var n,a,i=I(t,e,r),s=D(t,e,r),o=(i+s)/2;if(i==s)n=a=0;else{var l=i-s;switch(a=o>.5?l/(2-i-s):l/(i+s),i){case t:n=(e-r)/l+(r>e?6:0);break;case e:n=(r-t)/l+2;break;case r:n=(t-e)/l+4}n/=6}return{h:n,s:a,l:o}}function n(t,e,r){function n(t,e,r){return 0>r&&(r+=1),r>1&&(r-=1),1/6>r?t+6*(e-t)*r:.5>r?e:2/3>r?t+(e-t)*(2/3-r)*6:t}var a,i,s;if(t=k(t,360),e=k(e,100),r=k(r,100),0===e)a=i=s=r;else{var o=.5>r?r*(1+e):r+e-r*e,l=2*r-o;a=n(l,o,t+1/3),i=n(l,o,t),s=n(l,o,t-1/3)}return{r:255*a,g:255*i,b:255*s}}function a(t,e,r){t=k(t,255),e=k(e,255),r=k(r,255);var n,a,i=I(t,e,r),s=D(t,e,r),o=i,l=i-s;if(a=0===i?0:l/i,i==s)n=0;else{switch(i){case t:n=(e-r)/l+(r>e?6:0);break;case e:n=(r-t)/l+2;break;case r:n=(t-e)/l+4}n/=6}return{h:n,s:a,v:o}}function i(t,e,r){t=6*k(t,360),e=k(e,100),r=k(r,100);var n=j.floor(t),a=t-n,i=r*(1-e),s=r*(1-a*e),o=r*(1-(1-a)*e),l=n%6,c=[r,s,i,i,o,r][l],f=[o,r,r,s,i,i][l],u=[i,i,o,r,r,s][l];return{r:255*c,g:255*f,b:255*u}}function s(t,e,r,n){var a=[M(q(t).toString(16)),M(q(e).toString(16)),M(q(r).toString(16))];return n&&a[0].charAt(0)==a[0].charAt(1)&&a[1].charAt(0)==a[1].charAt(1)&&a[2].charAt(0)==a[2].charAt(1)?a[0].charAt(0)+a[1].charAt(0)+a[2].charAt(0):a.join("")}function o(t,e,r,n){var a=[M(H(n)),M(q(t).toString(16)),M(q(e).toString(16)),M(q(r).toString(16))];return a.join("")}function l(t,e){e=0===e?0:e||10;var r=B(t).toHsl();return r.s-=e/100,r.s=S(r.s),B(r)}function c(t,e){e=0===e?0:e||10;var r=B(t).toHsl();return r.s+=e/100,r.s=S(r.s),B(r)}function f(t){return B(t).desaturate(100)}function u(t,e){e=0===e?0:e||10;var r=B(t).toHsl();return r.l+=e/100,r.l=S(r.l),B(r)}function h(t,e){e=0===e?0:e||10;var r=B(t).toRgb();return r.r=I(0,D(255,r.r-q(255*-(e/100)))),r.g=I(0,D(255,r.g-q(255*-(e/100)))),r.b=I(0,D(255,r.b-q(255*-(e/100)))),B(r)}function d(t,e){e=0===e?0:e||10;var r=B(t).toHsl();return r.l-=e/100,r.l=S(r.l),B(r)}function p(t,e){var r=B(t).toHsl(),n=(q(r.h)+e)%360;return r.h=0>n?360+n:n,B(r)}function g(t){var e=B(t).toHsl();return e.h=(e.h+180)%360,B(e)}function b(t){var e=B(t).toHsl(),r=e.h;return[B(t),B({h:(r+120)%360,s:e.s,l:e.l}),B({h:(r+240)%360,s:e.s,l:e.l})]}function v(t){var e=B(t).toHsl(),r=e.h;return[B(t),B({h:(r+90)%360,s:e.s,l:e.l}),B({h:(r+180)%360,s:e.s,l:e.l}),B({h:(r+270)%360,s:e.s,l:e.l})]}function m(t){var e=B(t).toHsl(),r=e.h;return[B(t),B({h:(r+72)%360,s:e.s,l:e.l}),B({h:(r+216)%360,s:e.s,l:e.l})]}function y(t,e,r){e=e||6,r=r||30;var n=B(t).toHsl(),a=360/r,i=[B(t)];for(n.h=(n.h-(a*e>>1)+720)%360;--e;)n.h=(n.h+a)%360,i.push(B(n));return i}function w(t,e){e=e||6;for(var r=B(t).toHsv(),n=r.h,a=r.s,i=r.v,s=[],o=1/e;e--;)s.push(B({h:n,s:a,v:i})),i=(i+o)%1;return s}function _(t){var e={};for(var r in t)t.hasOwnProperty(r)&&(e[t[r]]=r);return e}function x(t){return t=parseFloat(t),(isNaN(t)||0>t||t>1)&&(t=1),t}function k(t,e){P(t)&&(t="100%");var r=A(t);return t=D(e,I(0,parseFloat(t))),r&&(t=parseInt(t*e,10)/100),j.abs(t-e)<1e-6?1:t%e/parseFloat(e)}function S(t){return D(1,I(0,t))}function C(t){return parseInt(t,16)}function P(t){return"string"==typeof t&&-1!=t.indexOf(".")&&1===parseFloat(t)}function A(t){return"string"==typeof t&&-1!=t.indexOf("%")}function M(t){return 1==t.length?"0"+t:""+t}function R(t){return 1>=t&&(t=100*t+"%"),t}function H(t){return Math.round(255*parseFloat(t)).toString(16)}function T(t){return C(t)/255}function F(t){t=t.replace(O,"").replace(N,"").toLowerCase();var e=!1;if(L[t])t=L[t],e=!0;else if("transparent"==t)return{r:0,g:0,b:0,a:0,format:"name"};var r;return(r=V.rgb.exec(t))?{r:r[1],g:r[2],b:r[3]}:(r=V.rgba.exec(t))?{r:r[1],g:r[2],b:r[3],a:r[4]}:(r=V.hsl.exec(t))?{h:r[1],s:r[2],l:r[3]}:(r=V.hsla.exec(t))?{h:r[1],s:r[2],l:r[3],a:r[4]}:(r=V.hsv.exec(t))?{h:r[1],s:r[2],v:r[3]}:(r=V.hsva.exec(t))?{h:r[1],s:r[2],v:r[3],a:r[4]}:(r=V.hex8.exec(t))?{a:T(r[1]),r:C(r[2]),g:C(r[3]),b:C(r[4]),format:e?"name":"hex8"}:(r=V.hex6.exec(t))?{r:C(r[1]),g:C(r[2]),b:C(r[3]),format:e?"name":"hex"}:(r=V.hex3.exec(t))?{r:C(r[1]+""+r[1]),g:C(r[2]+""+r[2]),b:C(r[3]+""+r[3]),format:e?"name":"hex"}:!1}var O=/^[\s,#]+/,N=/\s+$/,E=0,j=Math,q=j.round,D=j.min,I=j.max,z=j.random,B=function(e,r){if(e=e?e:"",r=r||{},e instanceof B)return e;if(!(this instanceof B))return new B(e,r);var n=t(e);this._originalInput=e,this._r=n.r,this._g=n.g,this._b=n.b,this._a=n.a,this._roundA=q(100*this._a)/100,this._format=r.format||n.format,this._gradientType=r.gradientType,this._r<1&&(this._r=q(this._r)),this._g<1&&(this._g=q(this._g)),this._b<1&&(this._b=q(this._b)),this._ok=n.ok,this._tc_id=E++};B.prototype={isDark:function(){return this.getBrightness()<128},isLight:function(){return!this.isDark()},isValid:function(){return this._ok},getOriginalInput:function(){return this._originalInput},getFormat:function(){return this._format},getAlpha:function(){return this._a},getBrightness:function(){var t=this.toRgb();return(299*t.r+587*t.g+114*t.b)/1e3},setAlpha:function(t){return this._a=x(t),this._roundA=q(100*this._a)/100,this},toHsv:function(){var t=a(this._r,this._g,this._b);return{h:360*t.h,s:t.s,v:t.v,a:this._a}},toHsvString:function(){var t=a(this._r,this._g,this._b),e=q(360*t.h),r=q(100*t.s),n=q(100*t.v);return 1==this._a?"hsv("+e+", "+r+"%, "+n+"%)":"hsva("+e+", "+r+"%, "+n+"%, "+this._roundA+")"},toHsl:function(){var t=r(this._r,this._g,this._b);return{h:360*t.h,s:t.s,l:t.l,a:this._a}},toHslString:function(){var t=r(this._r,this._g,this._b),e=q(360*t.h),n=q(100*t.s),a=q(100*t.l);return 1==this._a?"hsl("+e+", "+n+"%, "+a+"%)":"hsla("+e+", "+n+"%, "+a+"%, "+this._roundA+")"},toHex:function(t){return s(this._r,this._g,this._b,t)},toHexString:function(t){return"#"+this.toHex(t)},toHex8:function(){return o(this._r,this._g,this._b,this._a)},toHex8String:function(){return"#"+this.toHex8()},toRgb:function(){return{r:q(this._r),g:q(this._g),b:q(this._b),a:this._a}},toRgbString:function(){return 1==this._a?"rgb("+q(this._r)+", "+q(this._g)+", "+q(this._b)+")":"rgba("+q(this._r)+", "+q(this._g)+", "+q(this._b)+", "+this._roundA+")"},toPercentageRgb:function(){return{r:q(100*k(this._r,255))+"%",g:q(100*k(this._g,255))+"%",b:q(100*k(this._b,255))+"%",a:this._a}},toPercentageRgbString:function(){return 1==this._a?"rgb("+q(100*k(this._r,255))+"%, "+q(100*k(this._g,255))+"%, "+q(100*k(this._b,255))+"%)":"rgba("+q(100*k(this._r,255))+"%, "+q(100*k(this._g,255))+"%, "+q(100*k(this._b,255))+"%, "+this._roundA+")"},toName:function(){return 0===this._a?"transparent":this._a<1?!1:K[s(this._r,this._g,this._b,!0)]||!1},toFilter:function(t){var e="#"+o(this._r,this._g,this._b,this._a),r=e,n=this._gradientType?"GradientType = 1, ":"";if(t){var a=B(t);r=a.toHex8String()}return"progid:DXImageTransform.Microsoft.gradient("+n+"startColorstr="+e+",endColorstr="+r+")"},toString:function(t){var e=!!t;t=t||this._format;var r=!1,n=this._a<1&&this._a>=0,a=!e&&n&&("hex"===t||"hex6"===t||"hex3"===t||"name"===t);return a?"name"===t&&0===this._a?this.toName():this.toRgbString():("rgb"===t&&(r=this.toRgbString()),"prgb"===t&&(r=this.toPercentageRgbString()),("hex"===t||"hex6"===t)&&(r=this.toHexString()),"hex3"===t&&(r=this.toHexString(!0)),"hex8"===t&&(r=this.toHex8String()),"name"===t&&(r=this.toName()),"hsl"===t&&(r=this.toHslString()),"hsv"===t&&(r=this.toHsvString()),r||this.toHexString())},_applyModification:function(t,e){var r=t.apply(null,[this].concat([].slice.call(e)));return this._r=r._r,this._g=r._g,this._b=r._b,this.setAlpha(r._a),this},lighten:function(){return this._applyModification(u,arguments)},brighten:function(){return this._applyModification(h,arguments)},darken:function(){return this._applyModification(d,arguments)},desaturate:function(){return this._applyModification(l,arguments)},saturate:function(){return this._applyModification(c,arguments)},greyscale:function(){return this._applyModification(f,arguments)},spin:function(){return this._applyModification(p,arguments)},_applyCombination:function(t,e){return t.apply(null,[this].concat([].slice.call(e)))},analogous:function(){return this._applyCombination(y,arguments)},complement:function(){return this._applyCombination(g,arguments)},monochromatic:function(){return this._applyCombination(w,arguments)},splitcomplement:function(){return this._applyCombination(m,arguments)},triad:function(){return this._applyCombination(b,arguments)},tetrad:function(){return this._applyCombination(v,arguments)}},B.fromRatio=function(t,e){if("object"==typeof t){var r={};for(var n in t)t.hasOwnProperty(n)&&("a"===n?r[n]=t[n]:r[n]=R(t[n]));t=r}return B(t,e)},B.equals=function(t,e){return t&&e?B(t).toRgbString()==B(e).toRgbString():!1},B.random=function(){return B.fromRatio({r:z(),g:z(),b:z()})},B.mix=function(t,e,r){r=0===r?0:r||50;var n,a=B(t).toRgb(),i=B(e).toRgb(),s=r/100,o=2*s-1,l=i.a-a.a;n=o*l==-1?o:(o+l)/(1+o*l),n=(n+1)/2;var c=1-n,f={r:i.r*n+a.r*c,g:i.g*n+a.g*c,b:i.b*n+a.b*c,a:i.a*s+a.a*(1-s)};return B(f)},B.readability=function(t,e){var r=B(t),n=B(e),a=r.toRgb(),i=n.toRgb(),s=r.getBrightness(),o=n.getBrightness(),l=Math.max(a.r,i.r)-Math.min(a.r,i.r)+Math.max(a.g,i.g)-Math.min(a.g,i.g)+Math.max(a.b,i.b)-Math.min(a.b,i.b);return{brightness:Math.abs(s-o),color:l}},B.isReadable=function(t,e){var r=B.readability(t,e);return r.brightness>125&&r.color>500},B.mostReadable=function(t,e){for(var r=null,n=0,a=!1,i=0;i<e.length;i++){var s=B.readability(t,e[i]),o=s.brightness>125&&s.color>500,l=3*(s.brightness/125)+s.color/500;(o&&!a||o&&a&&l>n||!o&&!a&&l>n)&&(a=o,n=l,r=B(e[i]))}return r};var L=B.names={aliceblue:"f0f8ff",antiquewhite:"faebd7",aqua:"0ff",aquamarine:"7fffd4",azure:"f0ffff",beige:"f5f5dc",bisque:"ffe4c4",black:"000",blanchedalmond:"ffebcd",blue:"00f",blueviolet:"8a2be2",brown:"a52a2a",burlywood:"deb887",burntsienna:"ea7e5d",cadetblue:"5f9ea0",chartreuse:"7fff00",chocolate:"d2691e",coral:"ff7f50",cornflowerblue:"6495ed",cornsilk:"fff8dc",crimson:"dc143c",cyan:"0ff",darkblue:"00008b",darkcyan:"008b8b",darkgoldenrod:"b8860b",darkgray:"a9a9a9",darkgreen:"006400",darkgrey:"a9a9a9",darkkhaki:"bdb76b",darkmagenta:"8b008b",darkolivegreen:"556b2f",darkorange:"ff8c00",darkorchid:"9932cc",darkred:"8b0000",darksalmon:"e9967a",darkseagreen:"8fbc8f",darkslateblue:"483d8b",darkslategray:"2f4f4f",darkslategrey:"2f4f4f",darkturquoise:"00ced1",darkviolet:"9400d3",deeppink:"ff1493",deepskyblue:"00bfff",dimgray:"696969",dimgrey:"696969",dodgerblue:"1e90ff",firebrick:"b22222",floralwhite:"fffaf0",forestgreen:"228b22",fuchsia:"f0f",gainsboro:"dcdcdc",ghostwhite:"f8f8ff",gold:"ffd700",goldenrod:"daa520",gray:"808080",green:"008000",greenyellow:"adff2f",grey:"808080",honeydew:"f0fff0",hotpink:"ff69b4",indianred:"cd5c5c",indigo:"4b0082",ivory:"fffff0",khaki:"f0e68c",lavender:"e6e6fa",lavenderblush:"fff0f5",lawngreen:"7cfc00",lemonchiffon:"fffacd",lightblue:"add8e6",lightcoral:"f08080",lightcyan:"e0ffff",lightgoldenrodyellow:"fafad2",lightgray:"d3d3d3",lightgreen:"90ee90",lightgrey:"d3d3d3",lightpink:"ffb6c1",lightsalmon:"ffa07a",lightseagreen:"20b2aa",lightskyblue:"87cefa",lightslategray:"789",lightslategrey:"789",lightsteelblue:"b0c4de",lightyellow:"ffffe0",lime:"0f0",limegreen:"32cd32",linen:"faf0e6",magenta:"f0f",maroon:"800000",mediumaquamarine:"66cdaa",mediumblue:"0000cd",mediumorchid:"ba55d3",mediumpurple:"9370db",mediumseagreen:"3cb371",mediumslateblue:"7b68ee",mediumspringgreen:"00fa9a",mediumturquoise:"48d1cc",mediumvioletred:"c71585",midnightblue:"191970",mintcream:"f5fffa",mistyrose:"ffe4e1",moccasin:"ffe4b5",navajowhite:"ffdead",navy:"000080",oldlace:"fdf5e6",olive:"808000",olivedrab:"6b8e23",orange:"ffa500",orangered:"ff4500",orchid:"da70d6",palegoldenrod:"eee8aa",palegreen:"98fb98",paleturquoise:"afeeee",palevioletred:"db7093",papayawhip:"ffefd5",peachpuff:"ffdab9",peru:"cd853f",pink:"ffc0cb",plum:"dda0dd",powderblue:"b0e0e6",purple:"800080",rebeccapurple:"663399",red:"f00",rosybrown:"bc8f8f",royalblue:"4169e1",saddlebrown:"8b4513",salmon:"fa8072",sandybrown:"f4a460",seagreen:"2e8b57",seashell:"fff5ee",sienna:"a0522d",silver:"c0c0c0",skyblue:"87ceeb",slateblue:"6a5acd",slategray:"708090",slategrey:"708090",snow:"fffafa",springgreen:"00ff7f",steelblue:"4682b4",tan:"d2b48c",teal:"008080",thistle:"d8bfd8",tomato:"ff6347",turquoise:"40e0d0",violet:"ee82ee",wheat:"f5deb3",white:"fff",whitesmoke:"f5f5f5",yellow:"ff0",yellowgreen:"9acd32"},K=B.hexNames=_(L),V=function(){var t="[-\\+]?\\d+%?",e="[-\\+]?\\d*\\.\\d+%?",r="(?:"+e+")|(?:"+t+")",n="[\\s|\\(]+("+r+")[,|\\s]+("+r+")[,|\\s]+("+r+")\\s*\\)?",a="[\\s|\\(]+("+r+")[,|\\s]+("+r+")[,|\\s]+("+r+")[,|\\s]+("+r+")\\s*\\)?";return{rgb:new RegExp("rgb"+n),rgba:new RegExp("rgba"+a),hsl:new RegExp("hsl"+n),hsla:new RegExp("hsla"+a),hsv:new RegExp("hsv"+n),hsva:new RegExp("hsva"+a),hex3:/^([0-9a-fA-F]{1})([0-9a-fA-F]{1})([0-9a-fA-F]{1})$/,hex6:/^([0-9a-fA-F]{2})([0-9a-fA-F]{2})([0-9a-fA-F]{2})$/,hex8:/^([0-9a-fA-F]{2})([0-9a-fA-F]{2})([0-9a-fA-F]{2})([0-9a-fA-F]{2})$/}}();window.tinycolor=B}(),t(function(){t.fn.spectrum.load&&t.fn.spectrum.processNativeColorInputs()})});



// ImagePress
(function($){
    jQuery.fn.jConfirmAction = function(options){
        var theOptions = jQuery.extend({
            question: 'Are you sure you want to delete this image? This action is irreversible!',
            yesAnswer: 'Yes',
            cancelAnswer: 'No'
        }, options);

        return this.each(function(){
            $(this).bind('click', function(e){
                e.preventDefault();
                var thisHref = $(this).attr('href');
                if($(this).next('.question').length <= 0)
                    $(this).after('<div class="question"><i class="fa fa-exclamation-triangle"></i> ' + theOptions.question + '<br><span class="yes button noir-secondary">' + theOptions.yesAnswer + '</span><span class="cancel button noir-default">' + theOptions.cancelAnswer + '</span></div>');

                $(this).next('.question').animate({opacity: 1}, 300);
                $('.yes').bind('click', function(){
                    window.location = thisHref;
                });

                $('.cancel').bind('click', function(){
                    $(this).parents('.question').fadeOut(300, function() {
                        $(this).remove();
                    });
                });
            });
        });
    }
})(jQuery);



jQuery.fn.extend({
    greedyScroll: function(sensitivity) {
        return this.each(function() {
            jQuery(this).bind('mousewheel DOMMouseScroll', function(evt) {
               var delta;
               if (evt.originalEvent) {
                  delta = -evt.originalEvent.wheelDelta || evt.originalEvent.detail;
               }
               if (delta !== null) {
                  evt.preventDefault();
                  if (evt.type === 'DOMMouseScroll') {
                     delta = delta * (sensitivity ? sensitivity : 20);
                  }
                  return jQuery(this).scrollTop(delta + jQuery(this).scrollTop());
               }
            });
        });
    }
});

function bytesToSize(bytes) {
	var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
	if(bytes === 0) return 'n/a';
	var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
	if(i === 0) return bytes + ' ' + sizes[i]; 
	return (bytes / Math.pow(1024, i)).toFixed(1) + ' ' + sizes[i];
};

function ajaxReloadLike(lid) {
    jQuery.ajax({
        type: 'post',
        url: ip_ajax_var.ajaxreloadurl,
        data: 'id=' + lid,
        success: function(result) {
            jQuery('#ip-who-value').html(result);
        }
    });
}

// This one is IE10+
// https://developer.mozilla.org/en-US/docs/Web/API/Window/matchMedia
function isMobileSpy() {
    return jQuery('#mobile-spy').is(':visible');
}

jQuery(document).ready(function($) {
    if (isMobileSpy() && jQuery('.profile-hub-container').length) {
        jQuery('.ip-tabs').insertAfter(jQuery('.cinnamon-profile-sidebar'));
    }

    // Set up the "collect" action
    /**/
    jQuery('body').on('click', '.imagepress-collect', function(e){
        e.preventDefault();
        e.stopImmediatePropagation();

        var collect = jQuery(this),
            pid = collect.data('post-id'), // poster ID
            cid = jQuery('#ip_collections').val(), // collection ID (if existing)
            cnew = jQuery('#ip_collections_new').val(), // collection name (if new)
            cstatus = jQuery('#collection_status').val(); // collection status (if new)

        jQuery.ajax({
            type: 'post',
            url: ip_ajax_var.ajaxcollecturl,
            data: 'pid=' + pid + '&cid=' + cid + '&cnew=' + cnew + '&cstatus=' + cstatus,
            success: function(result) {
                jQuery('.showme').show().delay(2000).fadeOut(100, function() {
                    jQuery('.frontEndModal').removeClass('active').fadeOut();
                });
            }
        });
    });
    /**/

    // Set up the "like" action
    jQuery('body').on('click', '.imagepress-like', function(e){
        e.preventDefault();
        e.stopImmediatePropagation();

        var like = jQuery(this),
            pid = like.data('post_id'),
            howManyLikes = parseInt(jQuery('.ip-count-value').text()); // current number of likes

        like.html('<i class="fa fa-heart"></i> <i class="fa fa-spinner fa-spin"></i>');
        jQuery.ajax({
            type: 'post',
            url: ip_ajax_var.ajaxurl,
            data: 'action=imagepress-like&nonce=' + ip_ajax_var.nonce + '&imagepress_like=&post_id=' + pid,
            success: function(count) {
                if(count.indexOf('already') !== -1) {
                    var lecount = count.replace('already', '');
                    if(lecount === '0') {
                        lecount = ip_ajax_var.likelabel;
                    }
                    like.removeClass('liked');
                    like.html('<i class="fa fa-heart"></i> <span class="ip-count-value">' + lecount + '</span>');
                    jQuery('.ip-count-value').text(howManyLikes - 1); // decrease likes
                    ajaxReloadLike(pid);
                }
                else {
                    count = ip_ajax_var.unlikelabel;
                    like.addClass('liked');
                    like.html('<i class="fa fa-heart-o"></i> <span class="ip-count-value">' + count + '</span>');
                    jQuery('.ip-count-value').text(howManyLikes + 1); // increase likes
                    ajaxReloadLike(pid);
                }
            }
        });
        return false;
    });

    // Set up the "like" action
    jQuery('body').on('click', '.feed-like', function(e){
        e.preventDefault();
        e.stopImmediatePropagation();

        var like = jQuery(this),
            pid = like.data('post_id'),
            howManyLikes = jQuery(this).find('.ip-count-value').text(); // current number of likes

        like.html('<i class="fa fa-fw fa-heart"></i> <span class="ip-count-value">' + howManyLikes + '</span>');

        jQuery('[data-post_id="' + pid + '"] .fa-heart').addClass('like--on');
        jQuery('[data-post_id="' + pid + '"] .fa-heart-o').addClass('like--on');
        setTimeout(function() {
            jQuery('[data-post_id="' + pid + '"] .fa-heart').removeClass('like--on');
            jQuery('[data-post_id="' + pid + '"] .fa-heart-o').removeClass('like--on');
            console.log('one second');
        }, 1000);

        jQuery.ajax({
            type: 'post',
            url: ip_ajax_var.ajaxurl,
            data: 'action=imagepress-like&nonce=' + ip_ajax_var.nonce + '&imagepress_like=&post_id=' + pid,
            success: function(count) {
                if (count.indexOf('already') !== -1) {
                    var lecount = count.replace('already', '');
                    //console.log(lecount);
                    if (lecount === '0') {
                        lecount = ip_ajax_var.likelabel;
                    }
                    like.removeClass('liked');
                    like.html('<i class="fa fa-fw fa-heart"></i> <span class="ip-count-value">' + lecount + '</span>');
                    like.html('<i class="fa fa-fw fa-heart-o"></i> <span class="ip-count-value">' + lecount + '</span>');
                    like.find('.ip-count-value').text(howManyLikes - 1); // decrease likes
                    //ajaxReloadLike(pid);
                } else {
                    count = ip_ajax_var.unlikelabel;
                    like.addClass('liked');
                    like.html('<i class="fa fa-fw fa-heart-o"></i> <span class="ip-count-value">' + count + '</span>');
                    like.html('<i class="fa fa-fw fa-heart"></i> <span class="ip-count-value">' + count + '</span>');
                    like.find('.ip-count-value').text(parseInt(howManyLikes) + 1); // increase likes
                    //ajaxReloadLike(pid);
                }
            }
        });
        return false;
    });

	// begin upload
	/**
    jQuery('#imagepress_upload_image_form').submit(function(){
        jQuery('#imagepress_submit').prop('disabled', true);
        jQuery('#imagepress_submit').css('opacity', '0.5');
        jQuery('#ipload').html('<i class="fa fa-cog fa-spin"></i> Uploading...');
    });
    /**/

    //
	var fileInput = jQuery('#imagepress_image_file');
	var maxSize = fileInput.data('max-size');
	var maxWidth = fileInput.data('max-width');
	jQuery('#imagepress_image_file').change(function(e){
		if(fileInput.get(0).files.length){
			var fileSize = fileInput.get(0).files[0].size; // in bytes
			if(fileSize > maxSize) {
				jQuery('#imagepress-errors').append('<p>Warning: File size is too big (' + bytesToSize(fileSize) + ')!</p>');
				jQuery('#imagepress_submit').attr('disabled', true);
				return false;
			}
			else {
				jQuery('#imagepress-errors').html('');
				jQuery('#imagepress_submit').removeAttr('disabled');
			}
		}
		else {
			//alert('choose file, please');
			return false;
		}
	});

    //
    jQuery('#imagepress_upload_image_form').submit(function(e){
		jQuery('#imagepress-errors').html('');
        jQuery('#imagepress_submit').prop('disabled', true);
        jQuery('#imagepress_submit').css('opacity', '0.5');
        jQuery('#ipload').html('<i class="fa fa-cog fa-spin"></i> Uploading...');
    });
	// end upload

    jQuery(document).on('click', '#ip-editor-open', function(e){
        jQuery('.ip-editor').slideToggle('fast');
        e.preventDefault();
    });

    jQuery('.ask').jConfirmAction();

    // ip_editor() related actions
    jQuery('.delete-post').click(function(e){
        if(confirm('Delete this image?')) {
            jQuery(this).parent().parent().fadeOut();

            var id = jQuery(this).data('id');
            var nonce = jQuery(this).data('nonce');
            var post = jQuery(this).parents('.post:first');
            jQuery.ajax({
                type: 'post',
                url: ip_ajax_var.ajaxurl,
                data: {
                    action: 'ip_delete_post',
                    nonce: nonce,
                    id: id
                },
                success: function(result) {
                    if(result == 'success') {
                        post.fadeOut(function(){
                            post.remove();
                        });
                    }
                }
            });
        }
        e.preventDefault();
        return false;
    });
    jQuery('.featured-post').click(function(e){
        if(confirm('Set this image as main image?')) {
            jQuery(this).parent().parent().css('border', '3px solid #ffffff');

            var pid = jQuery(this).data('pid');
            var id = jQuery(this).data('id');
            var nonce = jQuery(this).data('nonce');
            var post = jQuery(this).parents('.post:first');
            jQuery.ajax({
                type: 'post',
                url: ip_ajax_var.ajaxurl,
                data: {
                    action: 'ip_featured_post',
                    nonce: nonce,
                    pid: pid,
                    id: id
                },
                success: function(result) {
                    if(result == 'success') {
                        /*
                        post.fadeOut(function(){
                            post.remove();
                        });
                        */
                    }
                }
            });
        }
        e.preventDefault();
        return false;
    });

    // notifications
	jQuery('.notifications-container .notification-item.unread').click(function(){
		var id = jQuery(this).data('id');
		jQuery.ajax({
			type: 'post',
			url: ip_ajax_var.ajaxurl,
			data: {
				action: 'notification_read',
				id: id
			}
		});
	});

	// mark all as read
	jQuery('.ip_notification_mark').click(function(e){
		e.preventDefault();
		var userid = jQuery(this).data('userid');
		jQuery.ajax({
			type: 'post',
			url: ip_ajax_var.ajaxurl,
			data: {
				action: 'notification_read_all',
				userid: userid
			}
		});

		//jQuery('.notifications-bell sup').hide();
		jQuery('.notifications-bell').html('<i class="fa fa-bell-o"></i><sup>0</sup>');
	});

	jQuery('.notifications-container .notifications-inner').greedyScroll(25);
    jQuery('.notifications-container').hide();
    jQuery('.notifications-bell').click(function(e){
        jQuery('.notifications-bell').toggleClass('on');
        jQuery('.notifications-container').toggle();
        e.preventDefault();
    });
    jQuery('.notifications-container').mouseleave(function(e){
        jQuery('.notifications-bell').removeClass('on');
        jQuery('.notifications-container').fadeOut('fast');
        e.preventDefault();
    });
    //


    // profile specific functions
	(function($) {
		$('.ip-tab .ip-tabs').addClass('active').find('> li:eq(0)').addClass('current');
        $('.ip-tab .ip-tabs li a:not(.imagepress-button)').click(function(g) { 
            var tab = $(this).closest('.ip-tab'), 
                index = $(this).closest('li').index();

            tab.find('.ip-tabs > li').removeClass('current');
            $(this).closest('li').addClass('current');

            tab.find('.tab_content').find('.ip-tabs-item').not('.ip-tabs-item:eq(' + index + ')').slideUp();
            tab.find('.tab_content').find('.ip-tabs-item:eq(' + index + ')').slideDown();

            g.preventDefault();
        });
    })(jQuery);

    // portfolio specific functions
    jQuery("#cinnamon-feature").hide();
    jQuery("#cinnamon-index").hide();
    jQuery(".cinnamon-grid-blank a").click(function(e) {
        e.preventDefault();
        var image = jQuery(this).attr("rel");
        jQuery("#cinnamon-feature").html('<img src="' + image + '" alt="">');
        jQuery("#cinnamon-feature").show();
        jQuery("#cinnamon-index").fadeIn();
    });
    jQuery("#cinnamon-index a").click(function(e) {
        e.preventDefault();
        jQuery("#cinnamon-feature").hide();
        jQuery("#cinnamon-index").hide();
    });
    jQuery(".c-index").click(function() {
        jQuery("#cinnamon-feature").hide();
        jQuery("#cinnamon-index").hide();
    });

    jQuery('#ip-tab li:first').addClass('active');
    jQuery('.tab_icerik').hide();
    jQuery('.tab_icerik:first').show();
    jQuery('#ip-tab li').click(function(e) {
        var index = jQuery(this).index();
        jQuery('#ip-tab li').removeClass('active');
        jQuery(this).addClass('active');
        jQuery('.tab_icerik').hide();
        jQuery('.tab_icerik:eq(' + index + ')').show();
        return false
    });

    jQuery("#cinnamon_sort").change(function(){ this.form.submit(); });

	jQuery('.follow-links a').on('click', function(e) {
        e.preventDefault();
        var $this = jQuery(this);
        if(ip_ajax_var.logged_in != 'undefined' && ip_ajax_var.logged_in != 'true') {
            alert(ip_ajax_var.login_required);
            return;
        }

        var data = {
            action: $this.hasClass('follow') ? 'follow' : 'unfollow',
			user_id: $this.data('user-id'),
			follow_id: $this.data('follow-id'),
			nonce: ip_ajax_var.nonce
		};

        //$this.html('<i class="fa fa-cog fa-spin fa-fw"></i>').fadeOut();
        //jQuery('.follow[data-follow-id="' + $this.data('follow-id') + '"]').hide();

        //jQuery('.follow[data-follow-id="' + $this.data('follow-id') + '"]').html('<i class="fa fa-check fa-fw"></i> Following');
        //jQuery('.follow[data-follow-id="' + $this.data('follow-id') + '"]').attr('style', 'background-color: #2ECC71 !important');

        //jQuery('.unfollow[data-follow-id="' + $this.data('follow-id') + '"]').show();
        //$this('img.pwuf-ajax').show();

        jQuery.post(ip_ajax_var.ajaxurl, data, function(response) {
            /**/
			if(response == 'success') {
                console.log(data['action']);
                if (data['action'] === 'follow') {
                    jQuery('.follow[data-follow-id="' + $this.data('follow-id') + '"]').html('<i class="fa fa-check fa-fw"></i> Following');
                    jQuery('.follow[data-follow-id="' + $this.data('follow-id') + '"]').attr('style', 'background-color: #2ECC71 !important');
                    jQuery('.follow[data-follow-id="' + $this.data('follow-id') + '"]').removeClass('follow').addClass('unfollow').addClass('followed');
                } else if (data['action'] === 'unfollow') {
                    jQuery('.unfollow[data-follow-id="' + $this.data('follow-id') + '"]').html('<i class="fa fa-plus fa-fw"></i> Follow');
                    jQuery('.unfollow[data-follow-id="' + $this.data('follow-id') + '"]').attr('style', 'background-color: #02b2fc !important');
                    jQuery('.unfollow[data-follow-id="' + $this.data('follow-id') + '"]').removeClass('unfollow').removeClass('followed').addClass('follow');
                }
				//$this.toggle();
            }
			//else
				//alert(ip_ajax_var.processing_error);
            /**/
			jQuery('img.pwuf-ajax').hide();
            //jQuery('.follow[data-follow-id="' + $this.data('follow-id') + '"]').hide();
        });
	});

    jQuery(document).on('mouseover', '.unfollow.followed.imagepress-button', function() {
        jQuery(this).attr('style', 'background-color: #E74C3C !important');
        jQuery(this).html('<i class="fa fa-fw fa-times"></i> Unfollow');
    });
    jQuery(document).on('mouseout', '.unfollow.followed.imagepress-button', function() {
        jQuery(this).attr('style', 'background-color: #2ECC71 !important');
        jQuery(this).html('<i class="fa fa-fw fa-check"></i> Following');
    });

    jQuery(document).on('click', '.slide', function() {
		jQuery('.view').slideToggle(100);

        return false;
	});

	jQuery('.social-hub').hide();
    jQuery(document).on('click', '#lightbox-share', function() {
		jQuery('.social-hub').slideToggle(100);

        return false;
	});



	jQuery('.initial i').addClass('teal');
    jQuery(document).on('click', '.sort', function(e){
		jQuery('.sort i').removeClass('teal');
		jQuery('i', this).addClass('teal');
	});


	// begin pagination
	if(jQuery('.pagination').length) {
		var paginationOptions = {
			outerWindow: 1
		};
		var monkeyList = new List('cinnamon-cards', {
			valueNames: ['imagetitle', 'name', 'location', 'followers', 'uploads', 'imageviews', 'imagecomments', 'imagelikes', 'imagecategory'],
			page: ip_ajax_var.imagesperpage,
			indexAsync: true,
			plugins: [ ListPagination(paginationOptions) ]
		});
		var monkeyList = new List('cinnamon-love', {
			valueNames: ['imagetitle', 'name', 'location', 'followers', 'uploads', 'imageviews', 'imagecomments', 'imagelikes', 'imagecategory'],
			page: ip_ajax_var.imagesperpage,
			indexAsync: true,
			plugins: [ ListPagination(paginationOptions) ]
		});
		var monkeyList = new List('author-cards', {
			valueNames: ['imagetitle', 'name', 'location', 'followers', 'uploads', 'imageviews', 'imagecomments', 'imagelikes', 'imagecategory'],
			page: ip_ajax_var.authorsperpage,
			indexAsync: true,
			plugins: [ ListPagination(paginationOptions) ]
		});
	}
	// end pagination

    jQuery(document).on('click', '.imagecategory', function(e){
		var tag = jQuery(this).data('tag')
        console.log('clicked on category ' + tag);
		jQuery('body').find('#ipsearch').val(tag);
		jQuery('body').find('#ipsearch').focus();
        //jQuery('body').find('#ipsearch').trigger({ type : 'keypress', which : 13 });
        //jQuery('body').find('#ipsearch').trigger(jQuery.Event('keypress', {keyCode: 13}));

        jQuery('body').find('#ipsearch').trigger('keyup');
	});

	// portfolio editor // color picker
	jQuery(".color_portfolio_bg").spectrum({
		color: "#ffffff",
		showInput: true,
		className: "full-spectrum",
		showInitial: true,
		showPalette: true,
		showSelectionPalette: true,
		maxPaletteSize: 10,
		preferredFormat: "hex",
		change: function(color) {
			jQuery("#hub_portfolio_bg").val(color.toHexString());        
			jQuery(".color_portfolio_bg").css("background-color", color.toHexString());        
		},
		palette: [
			["rgb(0, 0, 0)", "rgb(67, 67, 67)", "rgb(102, 102, 102)", "rgb(204, 204, 204)", "rgb(217, 217, 217)","rgb(255, 255, 255)"],
			["rgb(152, 0, 0)", "rgb(255, 0, 0)", "rgb(255, 153, 0)", "rgb(255, 255, 0)", "rgb(0, 255, 0)", "rgb(0, 255, 255)", "rgb(74, 134, 232)", "rgb(0, 0, 255)", "rgb(153, 0, 255)", "rgb(255, 0, 255)"],
			["rgb(230, 184, 175)", "rgb(244, 204, 204)", "rgb(252, 229, 205)", "rgb(255, 242, 204)", "rgb(217, 234, 211)", "rgb(208, 224, 227)", "rgb(201, 218, 248)", "rgb(207, 226, 243)", "rgb(217, 210, 233)", "rgb(234, 209, 220)", "rgb(221, 126, 107)", "rgb(234, 153, 153)", "rgb(249, 203, 156)", "rgb(255, 229, 153)", "rgb(182, 215, 168)", "rgb(162, 196, 201)", "rgb(164, 194, 244)", "rgb(159, 197, 232)", "rgb(180, 167, 214)", "rgb(213, 166, 189)", "rgb(204, 65, 37)", "rgb(224, 102, 102)", "rgb(246, 178, 107)", "rgb(255, 217, 102)", "rgb(147, 196, 125)", "rgb(118, 165, 175)", "rgb(109, 158, 235)", "rgb(111, 168, 220)", "rgb(142, 124, 195)", "rgb(194, 123, 160)", "rgb(166, 28, 0)", "rgb(204, 0, 0)", "rgb(230, 145, 56)", "rgb(241, 194, 50)", "rgb(106, 168, 79)", "rgb(69, 129, 142)", "rgb(60, 120, 216)", "rgb(61, 133, 198)", "rgb(103, 78, 167)", "rgb(166, 77, 121)", "rgb(91, 15, 0)", "rgb(102, 0, 0)", "rgb(120, 63, 4)", "rgb(127, 96, 0)", "rgb(39, 78, 19)", "rgb(12, 52, 61)", "rgb(28, 69, 135)", "rgb(7, 55, 99)", "rgb(32, 18, 77)", "rgb(76, 17, 48)"]
		]
	});
	jQuery(".color_portfolio_text").spectrum({
		color: "#000000",
		showInput: true,
		className: "full-spectrum",
		showInitial: true,
		showPalette: true,
		showSelectionPalette: true,
		maxPaletteSize: 10,
		preferredFormat: "hex",
		change: function(color) {
			jQuery("#hub_portfolio_text").val(color.toHexString());        
			jQuery(".color_portfolio_text").css("background-color", color.toHexString());        
		},
		palette: [
			["rgb(0, 0, 0)", "rgb(67, 67, 67)", "rgb(102, 102, 102)", "rgb(204, 204, 204)", "rgb(217, 217, 217)","rgb(255, 255, 255)"],
			["rgb(152, 0, 0)", "rgb(255, 0, 0)", "rgb(255, 153, 0)", "rgb(255, 255, 0)", "rgb(0, 255, 0)", "rgb(0, 255, 255)", "rgb(74, 134, 232)", "rgb(0, 0, 255)", "rgb(153, 0, 255)", "rgb(255, 0, 255)"],
			["rgb(230, 184, 175)", "rgb(244, 204, 204)", "rgb(252, 229, 205)", "rgb(255, 242, 204)", "rgb(217, 234, 211)", "rgb(208, 224, 227)", "rgb(201, 218, 248)", "rgb(207, 226, 243)", "rgb(217, 210, 233)", "rgb(234, 209, 220)", "rgb(221, 126, 107)", "rgb(234, 153, 153)", "rgb(249, 203, 156)", "rgb(255, 229, 153)", "rgb(182, 215, 168)", "rgb(162, 196, 201)", "rgb(164, 194, 244)", "rgb(159, 197, 232)", "rgb(180, 167, 214)", "rgb(213, 166, 189)", "rgb(204, 65, 37)", "rgb(224, 102, 102)", "rgb(246, 178, 107)", "rgb(255, 217, 102)", "rgb(147, 196, 125)", "rgb(118, 165, 175)", "rgb(109, 158, 235)", "rgb(111, 168, 220)", "rgb(142, 124, 195)", "rgb(194, 123, 160)", "rgb(166, 28, 0)", "rgb(204, 0, 0)", "rgb(230, 145, 56)", "rgb(241, 194, 50)", "rgb(106, 168, 79)", "rgb(69, 129, 142)", "rgb(60, 120, 216)", "rgb(61, 133, 198)", "rgb(103, 78, 167)", "rgb(166, 77, 121)", "rgb(91, 15, 0)", "rgb(102, 0, 0)", "rgb(120, 63, 4)", "rgb(127, 96, 0)", "rgb(39, 78, 19)", "rgb(12, 52, 61)", "rgb(28, 69, 135)", "rgb(7, 55, 99)", "rgb(32, 18, 77)", "rgb(76, 17, 48)"]
		]
	});
	jQuery(".color_portfolio_link").spectrum({
		color: "#0000ff",
		showInput: true,
		className: "full-spectrum",
		showInitial: true,
		showPalette: true,
		showSelectionPalette: true,
		maxPaletteSize: 10,
		preferredFormat: "hex",
		change: function(color) {
			jQuery("#hub_portfolio_link").val(color.toHexString());        
			jQuery(".color_portfolio_link").css("background-color", color.toHexString());        
		},
		palette: [
			["rgb(0, 0, 0)", "rgb(67, 67, 67)", "rgb(102, 102, 102)", "rgb(204, 204, 204)", "rgb(217, 217, 217)","rgb(255, 255, 255)"],
			["rgb(152, 0, 0)", "rgb(255, 0, 0)", "rgb(255, 153, 0)", "rgb(255, 255, 0)", "rgb(0, 255, 0)", "rgb(0, 255, 255)", "rgb(74, 134, 232)", "rgb(0, 0, 255)", "rgb(153, 0, 255)", "rgb(255, 0, 255)"],
			["rgb(230, 184, 175)", "rgb(244, 204, 204)", "rgb(252, 229, 205)", "rgb(255, 242, 204)", "rgb(217, 234, 211)", "rgb(208, 224, 227)", "rgb(201, 218, 248)", "rgb(207, 226, 243)", "rgb(217, 210, 233)", "rgb(234, 209, 220)", "rgb(221, 126, 107)", "rgb(234, 153, 153)", "rgb(249, 203, 156)", "rgb(255, 229, 153)", "rgb(182, 215, 168)", "rgb(162, 196, 201)", "rgb(164, 194, 244)", "rgb(159, 197, 232)", "rgb(180, 167, 214)", "rgb(213, 166, 189)", "rgb(204, 65, 37)", "rgb(224, 102, 102)", "rgb(246, 178, 107)", "rgb(255, 217, 102)", "rgb(147, 196, 125)", "rgb(118, 165, 175)", "rgb(109, 158, 235)", "rgb(111, 168, 220)", "rgb(142, 124, 195)", "rgb(194, 123, 160)", "rgb(166, 28, 0)", "rgb(204, 0, 0)", "rgb(230, 145, 56)", "rgb(241, 194, 50)", "rgb(106, 168, 79)", "rgb(69, 129, 142)", "rgb(60, 120, 216)", "rgb(61, 133, 198)", "rgb(103, 78, 167)", "rgb(166, 77, 121)", "rgb(91, 15, 0)", "rgb(102, 0, 0)", "rgb(120, 63, 4)", "rgb(127, 96, 0)", "rgb(39, 78, 19)", "rgb(12, 52, 61)", "rgb(28, 69, 135)", "rgb(7, 55, 99)", "rgb(32, 18, 77)", "rgb(76, 17, 48)"]
		]
	});
	//



	// collections
    jQuery(document).on('click', '.changeCollection', function(e){
		//jQuery('.collection_details_edit').toggleClass('active');
		jQuery(this).parent().parent().next('.collection_details_edit').toggleClass('active');
		e.preventDefault();
	});
    jQuery(document).on('click', '.closeCollectionEdit', function(e){
		//jQuery('.collection_details_edit').toggleClass('active');
		jQuery(this).parent().toggleClass('active');
		e.preventDefault();
	});
	jQuery(document).on('click', '.toggleModal', function(e){
		jQuery('.modal').toggleClass('active');
		e.preventDefault();
	});
	jQuery(document).on('click', '.toggleFrontEndModal', function(e){
		jQuery('.frontEndModal').toggleClass('active');
		e.preventDefault();
	});
	jQuery(document).on('click', '.toggleFrontEndModal .close', function(e){
		jQuery('.frontEndModal').toggleClass('active');
		e.preventDefault();
	});

    jQuery('.addCollection').click(function(e){
		jQuery('.addCollection').val('Creating...');
		jQuery('.collection-progress').fadeIn();
		jQuery.ajax({
			method: 'post',
			url: ip_ajax_var.ajaxurl,
			data: {
				action: 'addCollection',
				collection_author_id: jQuery('#collection_author_id').val(),
				collection_title: jQuery('#collection_title').val(),
				collection_status: jQuery('#collection_status').val()
			}
		}).done(function(msg) {
			jQuery('.addCollection').val('Create another collection');
			jQuery('.collection-progress').hide();
			jQuery('.showme').fadeIn();
		});

		e.preventDefault();
	});

    jQuery(document).on('click', '.deleteCollection', function(e){
        jQuery('body').find('deleteCollection').hide();
        var ipc = jQuery(this).data('collection-id');
		jQuery.ajax({
			method: 'post',
			url: ip_ajax_var.ajaxurl,
			data: {
				action: 'deleteCollection',
				collection_id: ipc,
			}
		}).done(function(msg) {
			jQuery('.ipc' + ipc).fadeOut();
			jQuery('.ip-loadingCollections').fadeOut();
		});

		e.preventDefault();
	});
    jQuery(document).on('click', '.deleteCollectionImage', function(e){
        var ipc = jQuery(this).data('image-id');
		jQuery.ajax({
			method: 'post',
			url: ip_ajax_var.ajaxurl,
			data: {
				action: 'deleteCollectionImage',
				image_id: ipc,
			}
		}).done(function(msg) {
			jQuery('.ip_box_' + ipc).fadeOut();
			jQuery('.ip-loadingCollections').fadeOut();
		});

		e.preventDefault();
	});

    jQuery(document).on('click', '.saveCollection', function(e){
        var ipc = jQuery(this).data('collection-id');
		jQuery.ajax({
			method: 'post',
			url: ip_ajax_var.ajaxurl,
			data: {
				action: 'editCollectionTitle',
				collection_id: ipc,
				collection_title: jQuery('.ct' + ipc).val(),
			}
		}).done(function(msg) {
			jQuery('.collection_details_edit').removeClass('active');
			jQuery('.imagepress-collections').trigger('click');
		});

		e.preventDefault();
	});
    jQuery(document).on('change', '.collection-status', function(e){
        var ipc = jQuery(this).data('collection-id');

		var option = this.options[this.selectedIndex];

		jQuery.ajax({
			method: 'post',
			url: ip_ajax_var.ajaxurl,
			data: {
				action: 'editCollectionStatus',
				collection_id: ipc,
				collection_status: jQuery(option).val()
			}
		}).done(function(msg) {
			jQuery('.cde' + ipc).fadeOut('fast');
		});

		e.preventDefault();
	});

	jQuery('.modal .close').click(function(e){
		jQuery.ajax({
			method: 'post',
			url: ip_ajax_var.ajaxurl,
			data: {
				action: 'ip_collections_display',
			}
		}).done(function(msg) {
			jQuery('.collections-display').html(msg);
		});

		e.preventDefault();
	});
	jQuery('.imagepress-collections').click(function(e){
		jQuery('.ip-loadingCollections').show();
		jQuery.ajax({
			method: 'post',
			url: ip_ajax_var.ajaxurl,
			data: {
				action: 'ip_collections_display',
			}
		}).done(function(msg) {
			jQuery('.collections-display').html(msg);
			jQuery('.ip-loadingCollections').fadeOut();
		});

		e.preventDefault();
	});

	jQuery(document).on('click', '.editCollection', function(e){
		var ipc = jQuery(this).data('collection-id');
		jQuery('.ip-loadingCollectionImages').show();

		jQuery.ajax({
			method: 'post',
			url: ip_ajax_var.ajaxurl,
			data: {
				collection_id: ipc,
				action: 'ip_collection_display',
			}
		}).done(function(msg) {
			jQuery('.collections-display').html(msg);
			jQuery('.ip-loadingCollectionImages').fadeOut();
		});

		e.preventDefault();
	});
	// end collections

    // Submit button is disable by default
    jQuery('#imagepress_submit').css('opacity', '0.5');
    jQuery('#imagepress_submit').attr('disabled', true);

    // Check if agreement has been checked
    jQuery(document).on('click', '#ip-agree', function(e) {
        if(jQuery('#ip-agree').is(':checked')) {
            jQuery('#imagepress_submit').css('opacity', '1');
            jQuery('#imagepress_submit').removeAttr('disabled');
        } else {
            jQuery('#imagepress_submit').css('opacity', '0.5');
            jQuery('#imagepress_submit').attr('disabled', true);
            //jQuery('#imagepress_submit').prop('disabled', true);
        }
    })
});





function postPrivateMessage() {
    var receiver = jQuery('.pm-new #pm_to').val(),
        message = jQuery('.pm-new #pm_message').val();

    if (message.length) {
        jQuery.ajax({
            method: 'post',
            url: ip_ajax_var.ajaxurl,
            data: {
                receiver: receiver,
                message: message,
                action: 'ip_post_pm_thread',
            },
            success: function (data) {
                jQuery('#pm_message').val('');

                jQuery.ajax({
                    method: 'post',
                    url: ip_ajax_var.ajaxurl,
                    data: {
                        user_id: receiver,
                        action: 'ip_get_pm_thread',
                    },
                    success: function (data) {
                        jQuery('.pm-right-inner').html(data);
                        //jQuery('.pm-new').show();
                        jQuery('.pm-right-inner').scrollTop(jQuery('.pm-right-inner')[0].scrollHeight);
                    }
                });
            }
        });
    }

    return false;
}



// AJAX call for autocomplete 
jQuery(document).ready(function () {
    if (parseInt(jQuery('#pm-enable').val()) === 0) {
        jQuery('#pm-enable').prop('checked', false);
        jQuery('#pm-message').html('<p>Message requests are currently disabled.</p>');
    } else {
        jQuery('#pm-enable').prop('checked', true);
    }

    jQuery(document).on('change', '#pm-enable', function() {
        jQuery('#pm-message').html('<p><i class="fa fa-circle-o-notch fa-spin"></i> Saving...</p>');

        var pm_user_id = jQuery(this).data('user-id'),
            pm_value = 0;

        if (document.getElementById('pm-enable').checked) {
            pm_value = 1;
        }

        jQuery.ajax({
			method: 'post',
			url: ip_ajax_var.ajaxurl,
			data: {
                pm_user_id: pm_user_id,
                pm_value: pm_value,
                action: 'ip_user_pm_enable',
            },
            success: function (data) {
                jQuery('#pm-message').html('<p>Settings saved.</p>');
            }
        });
    });

    jQuery("#search-box").keyup(function(){
        jQuery.ajax({
			method: 'post',
			url: ip_ajax_var.ajaxurl,
			data: {
				username: jQuery(this).val(),
				action: 'ip_user_select',
            },
            beforeSend: function(){
                jQuery("#search-box").css("background","rgba(0, 0, 0, 0.25) url(https://posterspy.com/wp-content/plugins/imagepress/img/1-1.gif) no-repeat center right");
            },
            success: function(data){
                jQuery("#suggesstion-box").show();
                jQuery("#suggesstion-box").html(data);
                jQuery("#search-box").css("background","rgba(0, 0, 0, 0.25)");
            }
        });
    });

    jQuery(".pm-message-single").click(function () {
        var sender = jQuery(this).data('sender');

        jQuery('.pm-right-inner').html('<div class="pm-right-inner--centered"><img src="https://posterspy.com/wp-content/plugins/imagepress/img/1-1.gif" alt="Loading"></div>');

        jQuery.ajax({
			method: 'post',
			url: ip_ajax_var.ajaxurl,
			data: {
				user_id: sender,
				action: 'ip_get_pm_thread',
            },
            success: function (data) {
                jQuery('.pm-right-inner').html(data);
                jQuery('.pm-new').show();
                jQuery('.pm-right-inner').scrollTop(jQuery('.pm-right-inner')[0].scrollHeight);
                jQuery('.pm-right').append('<span id="pm-beacon" data-sender-id="' + sender + '">[beacon]</span>');
            }
        });
    });



    jQuery(document).on('submit', 'form#pm_send_form', function (e) {
        postPrivateMessage();
    });

    jQuery(document).on('click', 'a#pm_send', function (e) {
        postPrivateMessage();

        e.preventDefault();
    });

    // Set limit on load
    //jQuery('#pm-load-limit').attr('data-limit', 5);

    jQuery(document).on('click', '#pm-load-limit', function () {
        var loadLimit = jQuery(this).attr('data-limit'),
            loadReceiver= jQuery(this).data('receiver');

        loadLimit = parseInt(loadLimit) + 5;
        console.log('should set new limit for ' + loadLimit);
        jQuery('#pm-load-limit').attr('data-limit', loadLimit);

        jQuery.ajax({
            method: 'post',
            url: ip_ajax_var.ajaxurl,
            data: {
                user_id: loadReceiver,
                pm_message_limit: loadLimit,
                action: 'ip_get_pm_thread',
            },
            success: function (data) {
                jQuery('.pm-right-inner').html(data);
                jQuery('.pm-new').show();
                //jQuery('.pm-right-inner').scrollTop(jQuery('.pm-right-inner')[0].scrollHeight);
            }
        });
    });



    // Javascript to enable link to tab
    var hash = document.location.hash;
    if (hash) {
        document.querySelectorAll('.whiskey-tabs li a[href="' + hash + '"]')[0].click();
    }

    var tabLinks = document.querySelectorAll('.whiskey-tabs li a');

    for (var i = 0; i < tabLinks.length; i++) { 
        tabLinks[i].onclick = function() {
            var target = this.getAttribute('href').replace('#', '');
            var sections = document.querySelectorAll('.whiskey-tab-content');

            for (var j=0; j < sections.length; j++) {
                sections[j].style.display = 'none';
            }

            document.getElementById(target).style.display = 'block';

            for (var k=0; k < tabLinks.length; k++) {
                tabLinks[k].removeAttribute('class');
            }

            this.setAttribute('class', 'is-active');

            return false;
        }
    };
});
//To select country name
function selectUser(id, val) {
    jQuery("#search-box").val(val);
    jQuery("#pm_to").val(id);
    jQuery("#suggesstion-box").hide();
}


/**
jQuery(window).load(function(){
    jQuery('#hub-loading').fadeOut(100);
});
/**/




console.log('init');
var intervalId = setInterval(function () {
    // Check for a specific element ID
    if (document.getElementById('pm-beacon')) {
        var senderId = jQuery('#pm-beacon').data('sender-id'),
            loadLimit = jQuery('#pm-load-limit').attr('data-limit'),
            loadData = jQuery('.pm-right-inner').html().replace(/ scale="0"/g, '');
        //console.log('abcd' + senderId + ' ' + loadLimit);
        //console.log('aaa:' + loadData);

        jQuery.ajax({
            method: 'post',
            url: ip_ajax_var.ajaxurl,
            data: {
                user_id: senderId,
                pm_message_limit: loadLimit,
                action: 'ip_get_pm_thread',
            },
            success: function (data) {
                if (data.replace(/ \/>/g, '>') === loadData) {
                    //console.log('the same');
                } else {
                    //console.log('not the same, refreshing');
                    jQuery('.pm-right-inner').html(data);
                    jQuery('.pm-right-inner').scrollTop(jQuery('.pm-right-inner')[0].scrollHeight);
                }
                //console.log('bbb:' + data.replace(/ \/>/g, '>'));
                //jQuery('.pm-right-inner').scrollTop(jQuery('.pm-right-inner')[0].scrollHeight);
            }
        });
    }
}, 500);





jQuery(function(){
	jQuery("#new_collection").hide();
	jQuery('#imagepress_collection').change(function() {
		if(jQuery(this).find('option:selected').val() == "other") {
			jQuery("#new_collection").show();
		} else {
			jQuery("#new_collection").hide();
		}
	});
});




jQuery(window).load(function(){
    if(jQuery(location).attr('href') == 'http://posterspy.com/all-posters/' || jQuery(location).attr('href') == 'https://posterspy.com/all-posters/') {
    jQuery('.imagepress-async-container').html('<div style="text-align: center; padding: 64px;"><p><i class="fa fa-circle-o-notch fa-5x fa-spin teal"></i></p><div style="font-family: Montserrat; font-size: 24px;">LOADING POSTERS</div><div><small>(BEAR WITH US, WE PROMISE THE<br>POSTERS ARE WORTH THE WAIT)</small></div></div>');
    var data = {
        action: 'process_shortcode_on_imagepress_action'
    }
    jQuery.post(ip_ajax_var.ajaxurl, data).done(function(response) {
        jQuery('.imagepress-async-container').html(response);
        jQuery('.initial i').addClass('teal');

        jQuery(".page-id-34 ul.list .ip_box").slice(21).hide();
        var mincount = 21;
        var maxcount = 42;

        jQuery(window).scroll(function () {
            if (jQuery(window).scrollTop() + jQuery(window).height() >= jQuery(document).height() - 50) {
                jQuery(".page-id-34 ul.list .ip_box").slice(mincount, maxcount).fadeIn();

                mincount = mincount + 7;
                maxcount = maxcount + 7;
            }
        });

        // begin pagination
        /**/
    	if(jQuery('.pagination').length) {
    		var paginationOptions = {
    			outerWindow: 1
    		};
    		var monkeyList1 = new List('cinnamon-cards', {
    			valueNames: ['imagetitle', 'name', 'location', 'followers', 'uploads', 'imageviews', 'imagecomments', 'imagelikes', 'imagecategory'],
    			page: ip_ajax_var.imagesperpage,
    			plugins: [ ListPagination(paginationOptions) ]
    		});
    		var monkeyList2 = new List('cinnamon-love', {
    			valueNames: ['imagetitle', 'name', 'location', 'followers', 'uploads', 'imageviews', 'imagecomments', 'imagelikes', 'imagecategory'],
    			page: ip_ajax_var.imagesperpage,
    			plugins: [ ListPagination(paginationOptions) ]
    		});
    		var monkeyList3 = new List('author-cards', {
    			valueNames: ['imagetitle', 'name', 'location', 'followers', 'uploads', 'imageviews', 'imagecomments', 'imagelikes', 'imagecategory'],
    			page: ip_ajax_var.authorsperpage,
    			plugins: [ ListPagination(paginationOptions) ]
    		});

            jQuery(document).on('click', '.sortByTaxonomyList', function() {
                var clickedOn = this.text;
                monkeyList1.filter(function(item) {
                    if (item.values().imagecategory == clickedOn) {
                        return true;
                    } else {
                        return false;
                    }
                });

                return false;
            });
            jQuery(document).on('click', '#ip-taxonomy-filter-none', function() {
                monkeyList1.filter();

                return false;
            });

    	}
        /**/
    	// end pagination
    });
    //});
}
});




/**
function existingTag(text) {
	var existing = false,
		text = text.toLowerCase();

	$(".tags").each(function(){
		if ($(this).text().toLowerCase() == text) 
		{
			existing = true;
			return "";
		}
	});

	return existing;
}

$(function(){
  $(".hub-skills-new input").focus();
  
  $(".hub-skills-new input").keyup(function(){

		var tag = $(this).val().trim(),
		length = tag.length;

		if((tag.charAt(length - 1) == ',') && (tag != ","))
		{
			tag = tag.substring(0, length - 1);

			if(!existingTag(tag))
			{
				$('<li class="hub-skills"><span>' + tag + '</span><i class="fa fa-times"></i></i></li>').insertBefore($(".hub-skills-new"));
				$(this).val("");	
			}
			else
			{
				$(this).val(tag);
			}
		}
	});
  
  $(document).on("click", ".hub-skills i", function(){
    $(this).parent("li").remove();
  });

});
/**/
