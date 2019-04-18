// Infinite Ajax Scroll, a jQuery plugin 1.0.2
(function(e){"use strict";Date.now=Date.now||function(){return+(new Date)},e.ias=function(t){function u(){var t;i.onChangePage(function(e,t,r){s&&s.setPage(e,r),n.onPageChange.call(this,e,r,t)});if(n.triggerPageThreshold>0)a();else if(e(n.next).attr("href")){var u=r.getCurrentScrollOffset(n.scrollContainer);E(function(){p(u)})}return s&&s.havePage()&&(l(),t=s.getPage(),r.forceScrollTop(function(){var n;t>1?(v(t),n=h(!0),e("html, body").scrollTop(n)):a()})),o}function a(){c(),n.scrollContainer.scroll(f)}function f(){var e,t;e=r.getCurrentScrollOffset(n.scrollContainer),t=h(),e>=t&&(m()>=n.triggerPageThreshold?(l(),E(function(){p(e)})):p(e))}function l(){n.scrollContainer.unbind("scroll",f)}function c(){e(n.pagination).hide()}function h(t){var r,i;return r=e(n.container).find(n.item).last(),r.size()===0?0:(i=r.offset().top+r.height(),t||(i+=n.thresholdMargin),i)}function p(t,r){var s;s=e(n.next).attr("href");if(!s)return n.noneleft&&e(n.container).find(n.item).last().after(n.noneleft),l();if(n.beforePageChange&&e.isFunction(n.beforePageChange)&&n.beforePageChange(t,s)===!1)return;i.pushPages(t,s),l(),y(),d(s,function(t,i){var o=n.onLoadItems.call(this,i),u;o!==!1&&(e(i).hide(),u=e(n.container).find(n.item).last(),u.after(i),e(i).fadeIn()),s=e(n.next,t).attr("href"),e(n.pagination).replaceWith(e(n.pagination,t)),b(),c(),s?a():l(),n.onRenderComplete.call(this,i),r&&r.call(this)})}function d(t,r,i){var s=[],o,u=Date.now(),a,f;i=i||n.loaderDelay,e.get(t,null,function(t){o=e(n.container,t).eq(0),0===o.length&&(o=e(t).filter(n.container).eq(0)),o&&o.find(n.item).each(function(){s.push(this)}),r&&(f=this,a=Date.now()-u,a<i?setTimeout(function(){r.call(f,t,s)},i-a):r.call(f,t,s))},"html")}function v(t){var n=h(!0);n>0&&p(n,function(){l(),i.getCurPageNum(n)+1<t?(v(t),e("html,body").animate({scrollTop:n},400,"swing")):(e("html,body").animate({scrollTop:n},1e3,"swing"),a())})}function m(){var e=r.getCurrentScrollOffset(n.scrollContainer);return i.getCurPageNum(e)}function g(){var t=e(".ias_loader");return t.size()===0&&(t=e('<div class="ias_loader">'+n.loader+"</div>"),t.hide()),t}function y(){var t=g(),r;n.customLoaderProc!==!1?n.customLoaderProc(t):(r=e(n.container).find(n.item).last(),r.after(t),t.fadeIn())}function b(){var e=g();e.remove()}function w(t){var r=e(".ias_trigger");return r.size()===0&&(r=e('<div class="ias_trigger"><a href="#">'+n.trigger+"</a></div>"),r.hide()),e("a",r).unbind("click").bind("click",function(){return S(),t.call(),!1}),r}function E(t){var r=w(t),i;n.customTriggerProc!==!1?n.customTriggerProc(r):(i=e(n.container).find(n.item).last(),i.after(r),r.fadeIn())}function S(){var e=w();e.remove()}var n=e.extend({},e.ias.defaults,t),r=new e.ias.util,i=new e.ias.paging(n.scrollContainer),s=n.history?new e.ias.history:!1,o=this;u()},e.ias.defaults={container:"#container",scrollContainer:e(window),item:".item",pagination:"#pagination",next:".next",noneleft:!1,loader:'<img src="images/loader.gif"/>',loaderDelay:600,triggerPageThreshold:3,trigger:"Load more items",thresholdMargin:0,history:!0,onPageChange:function(){},beforePageChange:function(){},onLoadItems:function(){},onRenderComplete:function(){},customLoaderProc:!1,customTriggerProc:!1},e.ias.util=function(){function i(){e(window).load(function(){t=!0})}var t=!1,n=!1,r=this;i(),this.forceScrollTop=function(i){e("html,body").scrollTop(0),n||(t?(i.call(),n=!0):setTimeout(function(){r.forceScrollTop(i)},1))},this.getCurrentScrollOffset=function(e){var t,n;return e.get(0)===window?t=e.scrollTop():t=e.offset().top,n=e.height(),t+n}},e.ias.paging=function(){function s(){e(window).scroll(o)}function o(){var t,s,o,f,l;t=i.getCurrentScrollOffset(e(window)),s=u(t),o=a(t),r!==s&&(f=o[0],l=o[1],n.call({},s,f,l)),r=s}function u(e){for(var n=t.length-1;n>0;n--)if(e>t[n][0])return n+1;return 1}function a(e){for(var n=t.length-1;n>=0;n--)if(e>t[n][0])return t[n];return null}var t=[[0,document.location.toString()]],n=function(){},r=1,i=new e.ias.util;s(),this.getCurPageNum=function(t){return t=t||i.getCurrentScrollOffset(e(window)),u(t)},this.onChangePage=function(e){n=e},this.pushPages=function(e,n){t.push([e,n])}},e.ias.history=function(){function n(){t=!!(window.history&&history.pushState&&history.replaceState),t=!1}var e=!1,t=!1;n(),this.setPage=function(e,t){this.updateState({page:e},"",t)},this.havePage=function(){return this.getState()!==!1},this.getPage=function(){var e;return this.havePage()?(e=this.getState(),e.page):1},this.getState=function(){var e,n,r;if(t){n=history.state;if(n&&n.ias)return n.ias}else{e=window.location.hash.substring(0,7)==="#/page/";if(e)return r=parseInt(window.location.hash.replace("#/page/",""),10),{page:r}}return!1},this.updateState=function(t,n,r){e?this.replaceState(t,n,r):this.pushState(t,n,r)},this.pushState=function(n,r,i){var s;t?history.pushState({ias:n},r,i):(s=n.page>0?"#/page/"+n.page:"",window.location.hash=s),e=!0},this.replaceState=function(e,n,r){t?history.replaceState({ias:e},n,r):this.pushState(e,n,r)}}})(jQuery);


// jquery cookie var COOKIE get | set | remove
jQuery.cookie=function(a,k,j){if(typeof k!="undefined"){j=j||{};if(k===null){k="";j.expires=-1}var e="";if(j.expires&&(typeof j.expires=="number"||j.expires.toUTCString)){var b;if(typeof j.expires=="number"){b=new Date();b.setTime(b.getTime()+(j.expires*24*60*60*1000))}else{b=j.expires}e="; expires="+b.toUTCString()}var m=j.path?"; path="+j.path:"";var c=j.domain?"; domain="+j.domain:"";var h=j.secure?"; secure":"";document.cookie=[a,"=",encodeURIComponent(k),e,m,c,h].join("")}else{var g=null;if(document.cookie&&document.cookie!=""){var l=document.cookie.split(";");for(var d=0;d<l.length;d++){var f=jQuery.trim(l[d]);if(f.substring(0,a.length+1)==(a+"=")){g=decodeURIComponent(f.substring(a.length+1));break}}}return g}};var COOKIE={get:function(a){if(window.localStorage){return localStorage.getItem(a)}else{return $.cookie(a)}},set:function(a,b){if(window.localStorage){localStorage[a]=b}else{$.cookie(a,b)}},remove:function(a){if(window.localStorage){localStorage.removeItem(a)}else{$.cookie(a,undefined)}}};


$.fn.serializeObject=function(){var a={},k=this.serializeArray();$.each(k,function(){void 0!==a[this.name]?(a[this.name].push||(a[this.name]=[a[this.name]]),a[this.name].push(this.value||"")):a[this.name]=this.value||""});return a};


/*! Lazy Load 1.9.5 - MIT license - Copyright 2010-2015 Mika Tuupola */
!function(a,b,c,d){var e=a(b);a.fn.lazyload=function(f){function g(){var b=0;i.each(function(){var c=a(this);if(!j.skip_invisible||c.is(":visible"))if(a.abovethetop(this,j)||a.leftofbegin(this,j));else if(a.belowthefold(this,j)||a.rightoffold(this,j)){if(++b>j.failure_limit)return!1}else c.trigger("appear"),b=0})}var h,i=this,j={threshold:0,failure_limit:0,event:"scroll",effect:"show",container:b,data_attribute:"original",skip_invisible:!1,appear:null,load:null,placeholder:"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC"};return f&&(d!==f.failurelimit&&(f.failure_limit=f.failurelimit,delete f.failurelimit),d!==f.effectspeed&&(f.effect_speed=f.effectspeed,delete f.effectspeed),a.extend(j,f)),h=j.container===d||j.container===b?e:a(j.container),0===j.event.indexOf("scroll")&&h.bind(j.event,function(){return g()}),this.each(function(){var b=this,c=a(b);b.loaded=!1,(c.attr("src")===d||c.attr("src")===!1)&&c.is("img")&&c.attr("src",j.placeholder),c.one("appear",function(){if(!this.loaded){if(j.appear){var d=i.length;j.appear.call(b,d,j)}a("<img />").bind("load",function(){var d=c.attr("data-"+j.data_attribute);c.hide(),c.is("img")?c.attr("src",d):c.css("background-image","url('"+d+"')"),c[j.effect](j.effect_speed),b.loaded=!0;var e=a.grep(i,function(a){return!a.loaded});if(i=a(e),j.load){var f=i.length;j.load.call(b,f,j)}}).attr("src",c.attr("data-"+j.data_attribute))}}),0!==j.event.indexOf("scroll")&&c.bind(j.event,function(){b.loaded||c.trigger("appear")})}),e.bind("resize",function(){g()}),/(?:iphone|ipod|ipad).*os 5/gi.test(navigator.appVersion)&&e.bind("pageshow",function(b){b.originalEvent&&b.originalEvent.persisted&&i.each(function(){a(this).trigger("appear")})}),a(c).ready(function(){g()}),this},a.belowthefold=function(c,f){var g;return g=f.container===d||f.container===b?(b.innerHeight?b.innerHeight:e.height())+e.scrollTop():a(f.container).offset().top+a(f.container).height(),g<=a(c).offset().top-f.threshold},a.rightoffold=function(c,f){var g;return g=f.container===d||f.container===b?e.width()+e.scrollLeft():a(f.container).offset().left+a(f.container).width(),g<=a(c).offset().left-f.threshold},a.abovethetop=function(c,f){var g;return g=f.container===d||f.container===b?e.scrollTop():a(f.container).offset().top,g>=a(c).offset().top+f.threshold+a(c).height()},a.leftofbegin=function(c,f){var g;return g=f.container===d||f.container===b?e.scrollLeft():a(f.container).offset().left,g>=a(c).offset().left+f.threshold+a(c).width()},a.inviewport=function(b,c){return!(a.rightoffold(b,c)||a.leftofbegin(b,c)||a.belowthefold(b,c)||a.abovethetop(b,c))},a.extend(a.expr[":"],{"below-the-fold":function(b){return a.belowthefold(b,{threshold:0})},"above-the-top":function(b){return!a.belowthefold(b,{threshold:0})},"right-of-screen":function(b){return a.rightoffold(b,{threshold:0})},"left-of-screen":function(b){return!a.rightoffold(b,{threshold:0})},"in-viewport":function(b){return a.inviewport(b,{threshold:0})},"above-the-fold":function(b){return!a.belowthefold(b,{threshold:0})},"right-of-fold":function(b){return a.rightoffold(b,{threshold:0})},"left-of-fold":function(b){return!a.rightoffold(b,{threshold:0})}})}(jQuery,window,document);


// A simple JS library that detects mobile devices. https://github.com/kaimallea/isMobile
!function(a){var b=/iPhone/i,c=/iPod/i,d=/iPad/i,e=/(?=.*\bAndroid\b)(?=.*\bMobile\b)/i,f=/Android/i,g=/(?=.*\bAndroid\b)(?=.*\bSD4930UR\b)/i,h=/(?=.*\bAndroid\b)(?=.*\b(?:KFOT|KFTT|KFJWI|KFJWA|KFSOWI|KFTHWI|KFTHWA|KFAPWI|KFAPWA|KFARWI|KFASWI|KFSAWI|KFSAWA)\b)/i,i=/IEMobile/i,j=/(?=.*\bWindows\b)(?=.*\bARM\b)/i,k=/BlackBerry/i,l=/BB10/i,m=/Opera Mini/i,n=/(CriOS|Chrome)(?=.*\bMobile\b)/i,o=/(?=.*\bFirefox\b)(?=.*\bMobile\b)/i,p=new RegExp("(?:Nexus 7|BNTV250|Kindle Fire|Silk|GT-P1000)","i"),q=function(a,b){return a.test(b)},r=function(a){var r=a||navigator.userAgent,s=r.split("[FBAN");return"undefined"!=typeof s[1]&&(r=s[0]),this.apple={phone:q(b,r),ipod:q(c,r),tablet:!q(b,r)&&q(d,r),device:q(b,r)||q(c,r)||q(d,r)},this.amazon={phone:q(g,r),tablet:!q(g,r)&&q(h,r),device:q(g,r)||q(h,r)},this.android={phone:q(g,r)||q(e,r),tablet:!q(g,r)&&!q(e,r)&&(q(h,r)||q(f,r)),device:q(g,r)||q(h,r)||q(e,r)||q(f,r)},this.windows={phone:q(i,r),tablet:q(j,r),device:q(i,r)||q(j,r)},this.other={blackberry:q(k,r),blackberry10:q(l,r),opera:q(m,r),firefox:q(o,r),chrome:q(n,r),device:q(k,r)||q(l,r)||q(m,r)||q(o,r)||q(n,r)},this.seven_inch=q(p,r),this.any=this.apple.device||this.android.device||this.windows.device||this.other.device||this.seven_inch,this.phone=this.apple.phone||this.android.phone||this.windows.phone,this.tablet=this.apple.tablet||this.android.tablet||this.windows.tablet,"undefined"==typeof window?this:void 0},s=function(){var a=new r;return a.Class=r,a};"undefined"!=typeof module&&module.exports&&"undefined"==typeof window?module.exports=r:"undefined"!=typeof module&&module.exports&&"undefined"!=typeof window?module.exports=s():"function"==typeof define&&define.amd?define("isMobile",[],a.isMobile=s()):a.isMobile=s()}(this);


// qrcode
(function(r){r.fn.qrcode=function(h){var s;function u(a){this.mode=s;this.data=a}function o(a,c){this.typeNumber=a;this.errorCorrectLevel=c;this.modules=null;this.moduleCount=0;this.dataCache=null;this.dataList=[]}function q(a,c){if(void 0==a.length){throw Error(a.length+"/"+c)}for(var d=0;d<a.length&&0==a[d];){d++}this.num=Array(a.length-d+c);for(var b=0;b<a.length-d;b++){this.num[b]=a[b+d]}}function p(a,c){this.totalCount=a;this.dataCount=c}function t(){this.buffer=[];this.length=0}u.prototype={getLength:function(){return this.data.length},write:function(a){for(var c=0;c<this.data.length;c++){a.put(this.data.charCodeAt(c),8)}}};o.prototype={addData:function(a){this.dataList.push(new u(a));this.dataCache=null},isDark:function(a,c){if(0>a||this.moduleCount<=a||0>c||this.moduleCount<=c){throw Error(a+","+c)}return this.modules[a][c]},getModuleCount:function(){return this.moduleCount},make:function(){if(1>this.typeNumber){for(var a=1,a=1;40>a;a++){for(var c=p.getRSBlocks(a,this.errorCorrectLevel),d=new t,b=0,e=0;e<c.length;e++){b+=c[e].dataCount}for(e=0;e<this.dataList.length;e++){c=this.dataList[e],d.put(c.mode,4),d.put(c.getLength(),j.getLengthInBits(c.mode,a)),c.write(d)}if(d.getLengthInBits()<=8*b){break}}this.typeNumber=a}this.makeImpl(!1,this.getBestMaskPattern())},makeImpl:function(a,c){this.moduleCount=4*this.typeNumber+17;this.modules=Array(this.moduleCount);for(var d=0;d<this.moduleCount;d++){this.modules[d]=Array(this.moduleCount);for(var b=0;b<this.moduleCount;b++){this.modules[d][b]=null}}this.setupPositionProbePattern(0,0);this.setupPositionProbePattern(this.moduleCount-7,0);this.setupPositionProbePattern(0,this.moduleCount-7);this.setupPositionAdjustPattern();this.setupTimingPattern();this.setupTypeInfo(a,c);7<=this.typeNumber&&this.setupTypeNumber(a);null==this.dataCache&&(this.dataCache=o.createData(this.typeNumber,this.errorCorrectLevel,this.dataList));this.mapData(this.dataCache,c)},setupPositionProbePattern:function(a,c){for(var d=-1;7>=d;d++){if(!(-1>=a+d||this.moduleCount<=a+d)){for(var b=-1;7>=b;b++){-1>=c+b||this.moduleCount<=c+b||(this.modules[a+d][c+b]=0<=d&&6>=d&&(0==b||6==b)||0<=b&&6>=b&&(0==d||6==d)||2<=d&&4>=d&&2<=b&&4>=b?!0:!1)}}}},getBestMaskPattern:function(){for(var a=0,c=0,d=0;8>d;d++){this.makeImpl(!0,d);var b=j.getLostPoint(this);if(0==d||a>b){a=b,c=d}}return c},createMovieClip:function(a,c,d){a=a.createEmptyMovieClip(c,d);this.make();for(c=0;c<this.modules.length;c++){for(var d=1*c,b=0;b<this.modules[c].length;b++){var e=1*b;this.modules[c][b]&&(a.beginFill(0,100),a.moveTo(e,d),a.lineTo(e+1,d),a.lineTo(e+1,d+1),a.lineTo(e,d+1),a.endFill())}}return a},setupTimingPattern:function(){for(var a=8;a<this.moduleCount-8;a++){null==this.modules[a][6]&&(this.modules[a][6]=0==a%2)}for(a=8;a<this.moduleCount-8;a++){null==this.modules[6][a]&&(this.modules[6][a]=0==a%2)}},setupPositionAdjustPattern:function(){for(var a=j.getPatternPosition(this.typeNumber),c=0;c<a.length;c++){for(var d=0;d<a.length;d++){var b=a[c],e=a[d];if(null==this.modules[b][e]){for(var f=-2;2>=f;f++){for(var i=-2;2>=i;i++){this.modules[b+f][e+i]=-2==f||2==f||-2==i||2==i||0==f&&0==i?!0:!1}}}}}},setupTypeNumber:function(a){for(var c=j.getBCHTypeNumber(this.typeNumber),d=0;18>d;d++){var b=!a&&1==(c>>d&1);this.modules[Math.floor(d/3)][d%3+this.moduleCount-8-3]=b}for(d=0;18>d;d++){b=!a&&1==(c>>d&1),this.modules[d%3+this.moduleCount-8-3][Math.floor(d/3)]=b}},setupTypeInfo:function(a,c){for(var d=j.getBCHTypeInfo(this.errorCorrectLevel<<3|c),b=0;15>b;b++){var e=!a&&1==(d>>b&1);6>b?this.modules[b][8]=e:8>b?this.modules[b+1][8]=e:this.modules[this.moduleCount-15+b][8]=e}for(b=0;15>b;b++){e=!a&&1==(d>>b&1),8>b?this.modules[8][this.moduleCount-b-1]=e:9>b?this.modules[8][15-b-1+1]=e:this.modules[8][15-b-1]=e}this.modules[this.moduleCount-8][8]=!a},mapData:function(a,c){for(var d=-1,b=this.moduleCount-1,e=7,f=0,i=this.moduleCount-1;0<i;i-=2){for(6==i&&i--;;){for(var g=0;2>g;g++){if(null==this.modules[b][i-g]){var n=!1;f<a.length&&(n=1==(a[f]>>>e&1));j.getMask(c,b,i-g)&&(n=!n);this.modules[b][i-g]=n;e--;-1==e&&(f++,e=7)}}b+=d;if(0>b||this.moduleCount<=b){b-=d;d=-d;break}}}}};o.PAD0=236;o.PAD1=17;o.createData=function(a,c,d){for(var c=p.getRSBlocks(a,c),b=new t,e=0;e<d.length;e++){var f=d[e];b.put(f.mode,4);b.put(f.getLength(),j.getLengthInBits(f.mode,a));f.write(b)}for(e=a=0;e<c.length;e++){a+=c[e].dataCount}if(b.getLengthInBits()>8*a){throw Error("code length overflow. ("+b.getLengthInBits()+">"+8*a+")")}for(b.getLengthInBits()+4<=8*a&&b.put(0,4);0!=b.getLengthInBits()%8;){b.putBit(!1)}for(;!(b.getLengthInBits()>=8*a);){b.put(o.PAD0,8);if(b.getLengthInBits()>=8*a){break}b.put(o.PAD1,8)}return o.createBytes(b,c)};o.createBytes=function(a,c){for(var d=0,b=0,e=0,f=Array(c.length),i=Array(c.length),g=0;g<c.length;g++){var n=c[g].dataCount,h=c[g].totalCount-n,b=Math.max(b,n),e=Math.max(e,h);f[g]=Array(n);for(var k=0;k<f[g].length;k++){f[g][k]=255&a.buffer[k+d]}d+=n;k=j.getErrorCorrectPolynomial(h);n=(new q(f[g],k.getLength()-1)).mod(k);
i[g]=Array(k.getLength()-1);for(k=0;k<i[g].length;k++){h=k+n.getLength()-i[g].length,i[g][k]=0<=h?n.get(h):0}}for(k=g=0;k<c.length;k++){g+=c[k].totalCount}d=Array(g);for(k=n=0;k<b;k++){for(g=0;g<c.length;g++){k<f[g].length&&(d[n++]=f[g][k])}}for(k=0;k<e;k++){for(g=0;g<c.length;g++){k<i[g].length&&(d[n++]=i[g][k])}}return d};s=4;for(var j={PATTERN_POSITION_TABLE:[[],[6,18],[6,22],[6,26],[6,30],[6,34],[6,22,38],[6,24,42],[6,26,46],[6,28,50],[6,30,54],[6,32,58],[6,34,62],[6,26,46,66],[6,26,48,70],[6,26,50,74],[6,30,54,78],[6,30,56,82],[6,30,58,86],[6,34,62,90],[6,28,50,72,94],[6,26,50,74,98],[6,30,54,78,102],[6,28,54,80,106],[6,32,58,84,110],[6,30,58,86,114],[6,34,62,90,118],[6,26,50,74,98,122],[6,30,54,78,102,126],[6,26,52,78,104,130],[6,30,56,82,108,134],[6,34,60,86,112,138],[6,30,58,86,114,142],[6,34,62,90,118,146],[6,30,54,78,102,126,150],[6,24,50,76,102,128,154],[6,28,54,80,106,132,158],[6,32,58,84,110,136,162],[6,26,54,82,110,138,166],[6,30,58,86,114,142,170]],G15:1335,G18:7973,G15_MASK:21522,getBCHTypeInfo:function(a){for(var c=a<<10;0<=j.getBCHDigit(c)-j.getBCHDigit(j.G15);){c^=j.G15<<j.getBCHDigit(c)-j.getBCHDigit(j.G15)}return(a<<10|c)^j.G15_MASK},getBCHTypeNumber:function(a){for(var c=a<<12;0<=j.getBCHDigit(c)-j.getBCHDigit(j.G18);){c^=j.G18<<j.getBCHDigit(c)-j.getBCHDigit(j.G18)}return a<<12|c},getBCHDigit:function(a){for(var c=0;0!=a;){c++,a>>>=1}return c},getPatternPosition:function(a){return j.PATTERN_POSITION_TABLE[a-1]},getMask:function(a,c,d){switch(a){case 0:return 0==(c+d)%2;case 1:return 0==c%2;case 2:return 0==d%3;case 3:return 0==(c+d)%3;case 4:return 0==(Math.floor(c/2)+Math.floor(d/3))%2;case 5:return 0==c*d%2+c*d%3;case 6:return 0==(c*d%2+c*d%3)%2;case 7:return 0==(c*d%3+(c+d)%2)%2;default:throw Error("bad maskPattern:"+a)}},getErrorCorrectPolynomial:function(a){for(var c=new q([1],0),d=0;d<a;d++){c=c.multiply(new q([1,l.gexp(d)],0))}return c},getLengthInBits:function(a,c){if(1<=c&&10>c){switch(a){case 1:return 10;case 2:return 9;case s:return 8;case 8:return 8;default:throw Error("mode:"+a)}}else{if(27>c){switch(a){case 1:return 12;case 2:return 11;case s:return 16;case 8:return 10;default:throw Error("mode:"+a)}}else{if(41>c){switch(a){case 1:return 14;case 2:return 13;case s:return 16;case 8:return 12;default:throw Error("mode:"+a)}}else{throw Error("type:"+c)}}}},getLostPoint:function(a){for(var c=a.getModuleCount(),d=0,b=0;b<c;b++){for(var e=0;e<c;e++){for(var f=0,i=a.isDark(b,e),g=-1;1>=g;g++){if(!(0>b+g||c<=b+g)){for(var h=-1;1>=h;h++){0>e+h||c<=e+h||0==g&&0==h||i==a.isDark(b+g,e+h)&&f++}}}5<f&&(d+=3+f-5)}}for(b=0;b<c-1;b++){for(e=0;e<c-1;e++){if(f=0,a.isDark(b,e)&&f++,a.isDark(b+1,e)&&f++,a.isDark(b,e+1)&&f++,a.isDark(b+1,e+1)&&f++,0==f||4==f){d+=3}}}for(b=0;b<c;b++){for(e=0;e<c-6;e++){a.isDark(b,e)&&!a.isDark(b,e+1)&&a.isDark(b,e+2)&&a.isDark(b,e+3)&&a.isDark(b,e+4)&&!a.isDark(b,e+5)&&a.isDark(b,e+6)&&(d+=40)}}for(e=0;e<c;e++){for(b=0;b<c-6;b++){a.isDark(b,e)&&!a.isDark(b+1,e)&&a.isDark(b+2,e)&&a.isDark(b+3,e)&&a.isDark(b+4,e)&&!a.isDark(b+5,e)&&a.isDark(b+6,e)&&(d+=40)}}for(e=f=0;e<c;e++){for(b=0;b<c;b++){a.isDark(b,e)&&f++}}a=Math.abs(100*f/c/c-50)/5;return d+10*a}},l={glog:function(a){if(1>a){throw Error("glog("+a+")")}return l.LOG_TABLE[a]},gexp:function(a){for(;0>a;){a+=255}for(;256<=a;){a-=255}return l.EXP_TABLE[a]},EXP_TABLE:Array(256),LOG_TABLE:Array(256)},m=0;8>m;m++){l.EXP_TABLE[m]=1<<m}for(m=8;256>m;m++){l.EXP_TABLE[m]=l.EXP_TABLE[m-4]^l.EXP_TABLE[m-5]^l.EXP_TABLE[m-6]^l.EXP_TABLE[m-8]}for(m=0;255>m;m++){l.LOG_TABLE[l.EXP_TABLE[m]]=m}q.prototype={get:function(a){return this.num[a]},getLength:function(){return this.num.length},multiply:function(a){for(var c=Array(this.getLength()+a.getLength()-1),d=0;d<this.getLength();d++){for(var b=0;b<a.getLength();b++){c[d+b]^=l.gexp(l.glog(this.get(d))+l.glog(a.get(b)))}}return new q(c,0)},mod:function(a){if(0>this.getLength()-a.getLength()){return this}for(var c=l.glog(this.get(0))-l.glog(a.get(0)),d=Array(this.getLength()),b=0;b<this.getLength();b++){d[b]=this.get(b)}for(b=0;b<a.getLength();b++){d[b]^=l.gexp(l.glog(a.get(b))+c)}return(new q(d,0)).mod(a)}};p.RS_BLOCK_TABLE=[[1,26,19],[1,26,16],[1,26,13],[1,26,9],[1,44,34],[1,44,28],[1,44,22],[1,44,16],[1,70,55],[1,70,44],[2,35,17],[2,35,13],[1,100,80],[2,50,32],[2,50,24],[4,25,9],[1,134,108],[2,67,43],[2,33,15,2,34,16],[2,33,11,2,34,12],[2,86,68],[4,43,27],[4,43,19],[4,43,15],[2,98,78],[4,49,31],[2,32,14,4,33,15],[4,39,13,1,40,14],[2,121,97],[2,60,38,2,61,39],[4,40,18,2,41,19],[4,40,14,2,41,15],[2,146,116],[3,58,36,2,59,37],[4,36,16,4,37,17],[4,36,12,4,37,13],[2,86,68,2,87,69],[4,69,43,1,70,44],[6,43,19,2,44,20],[6,43,15,2,44,16],[4,101,81],[1,80,50,4,81,51],[4,50,22,4,51,23],[3,36,12,8,37,13],[2,116,92,2,117,93],[6,58,36,2,59,37],[4,46,20,6,47,21],[7,42,14,4,43,15],[4,133,107],[8,59,37,1,60,38],[8,44,20,4,45,21],[12,33,11,4,34,12],[3,145,115,1,146,116],[4,64,40,5,65,41],[11,36,16,5,37,17],[11,36,12,5,37,13],[5,109,87,1,110,88],[5,65,41,5,66,42],[5,54,24,7,55,25],[11,36,12],[5,122,98,1,123,99],[7,73,45,3,74,46],[15,43,19,2,44,20],[3,45,15,13,46,16],[1,135,107,5,136,108],[10,74,46,1,75,47],[1,50,22,15,51,23],[2,42,14,17,43,15],[5,150,120,1,151,121],[9,69,43,4,70,44],[17,50,22,1,51,23],[2,42,14,19,43,15],[3,141,113,4,142,114],[3,70,44,11,71,45],[17,47,21,4,48,22],[9,39,13,16,40,14],[3,135,107,5,136,108],[3,67,41,13,68,42],[15,54,24,5,55,25],[15,43,15,10,44,16],[4,144,116,4,145,117],[17,68,42],[17,50,22,6,51,23],[19,46,16,6,47,17],[2,139,111,7,140,112],[17,74,46],[7,54,24,16,55,25],[34,37,13],[4,151,121,5,152,122],[4,75,47,14,76,48],[11,54,24,14,55,25],[16,45,15,14,46,16],[6,147,117,4,148,118],[6,73,45,14,74,46],[11,54,24,16,55,25],[30,46,16,2,47,17],[8,132,106,4,133,107],[8,75,47,13,76,48],[7,54,24,22,55,25],[22,45,15,13,46,16],[10,142,114,2,143,115],[19,74,46,4,75,47],[28,50,22,6,51,23],[33,46,16,4,47,17],[8,152,122,4,153,123],[22,73,45,3,74,46],[8,53,23,26,54,24],[12,45,15,28,46,16],[3,147,117,10,148,118],[3,73,45,23,74,46],[4,54,24,31,55,25],[11,45,15,31,46,16],[7,146,116,7,147,117],[21,73,45,7,74,46],[1,53,23,37,54,24],[19,45,15,26,46,16],[5,145,115,10,146,116],[19,75,47,10,76,48],[15,54,24,25,55,25],[23,45,15,25,46,16],[13,145,115,3,146,116],[2,74,46,29,75,47],[42,54,24,1,55,25],[23,45,15,28,46,16],[17,145,115],[10,74,46,23,75,47],[10,54,24,35,55,25],[19,45,15,35,46,16],[17,145,115,1,146,116],[14,74,46,21,75,47],[29,54,24,19,55,25],[11,45,15,46,46,16],[13,145,115,6,146,116],[14,74,46,23,75,47],[44,54,24,7,55,25],[59,46,16,1,47,17],[12,151,121,7,152,122],[12,75,47,26,76,48],[39,54,24,14,55,25],[22,45,15,41,46,16],[6,151,121,14,152,122],[6,75,47,34,76,48],[46,54,24,10,55,25],[2,45,15,64,46,16],[17,152,122,4,153,123],[29,74,46,14,75,47],[49,54,24,10,55,25],[24,45,15,46,46,16],[4,152,122,18,153,123],[13,74,46,32,75,47],[48,54,24,14,55,25],[42,45,15,32,46,16],[20,147,117,4,148,118],[40,75,47,7,76,48],[43,54,24,22,55,25],[10,45,15,67,46,16],[19,148,118,6,149,119],[18,75,47,31,76,48],[34,54,24,34,55,25],[20,45,15,61,46,16]];
p.getRSBlocks=function(a,c){var d=p.getRsBlockTable(a,c);if(void 0==d){throw Error("bad rs block @ typeNumber:"+a+"/errorCorrectLevel:"+c)}for(var b=d.length/3,e=[],f=0;f<b;f++){for(var h=d[3*f+0],g=d[3*f+1],j=d[3*f+2],l=0;l<h;l++){e.push(new p(g,j))}}return e};p.getRsBlockTable=function(a,c){switch(c){case 1:return p.RS_BLOCK_TABLE[4*(a-1)+0];case 0:return p.RS_BLOCK_TABLE[4*(a-1)+1];case 3:return p.RS_BLOCK_TABLE[4*(a-1)+2];case 2:return p.RS_BLOCK_TABLE[4*(a-1)+3]}};t.prototype={get:function(a){return 1==(this.buffer[Math.floor(a/8)]>>>7-a%8&1)},put:function(a,c){for(var d=0;d<c;d++){this.putBit(1==(a>>>c-d-1&1))}},getLengthInBits:function(){return this.length},putBit:function(a){var c=Math.floor(this.length/8);this.buffer.length<=c&&this.buffer.push(0);a&&(this.buffer[c]|=128>>>this.length%8);this.length++}};"string"===typeof h&&(h={text:h});h=r.extend({},{render:"canvas",width:256,height:256,typeNumber:-1,correctLevel:2,background:"#ffffff",foreground:"#000000"},h);return this.each(function(){var a;if("canvas"==h.render){a=new o(h.typeNumber,h.correctLevel);a.addData(h.text);a.make();var c=document.createElement("canvas");c.width=h.width;c.height=h.height;for(var d=c.getContext("2d"),b=h.width/a.getModuleCount(),e=h.height/a.getModuleCount(),f=0;f<a.getModuleCount();f++){for(var i=0;i<a.getModuleCount();i++){d.fillStyle=a.isDark(f,i)?h.foreground:h.background;var g=Math.ceil((i+1)*b)-Math.floor(i*b),j=Math.ceil((f+1)*b)-Math.floor(f*b);d.fillRect(Math.round(i*b),Math.round(f*e),g,j)}}}else{a=new o(h.typeNumber,h.correctLevel);a.addData(h.text);a.make();c=r("<table></table>").css("width",h.width+"px").css("height",h.height+"px").css("border","0px").css("border-collapse","collapse").css("background-color",h.background);d=h.width/a.getModuleCount();b=h.height/a.getModuleCount();for(e=0;e<a.getModuleCount();e++){f=r("<tr></tr>").css("height",b+"px").appendTo(c);for(i=0;i<a.getModuleCount();i++){r("<td></td>").css("width",d+"px").css("background-color",a.isDark(e,i)?h.foreground:h.background).appendTo(f)}}}a=c;jQuery(a).appendTo(this)})}})(jQuery);



// document main page
$(document).ready(function(){


	// TBUI
	window.TBUI = window.TBUI || {}

    TBUI.bd = $('body')
    TBUI.siteurl = TBUI.siteurl
    TBUI.uri = TBUI.uri
	TBUI.ajaxpager = TBUI.ajaxpager ? Number(TBUI.ajaxpager) : 10
    TBUI.pagenum = TBUI.pagenum ? Number(TBUI.pagenum) : 20
    TBUI.shareimage = TBUI.shareimage || ''
	TBUI.shareimagethumb = TBUI.shareimagethumb ? Number(TBUI.shareimagethumb) : 1
	TBUI.is_login_popup = TBUI.is_login_popup ? Number(TBUI.is_login_popup) : 1
	TBUI.click = 'click'

	

	$(".m-navbar-start").on("click", function() {
    	TBUI.bd.toggleClass("m-navbar-on")
	});
	$(".m-mask").on("click", function() {
	    TBUI.bd.removeClass("m-navbar-on"),
	    TBUI.bd.removeClass("m-wel-on")
	});
	$(".m-wel-start").on("click", function() {
	    TBUI.bd.toggleClass("m-wel-on")
	});
	$("#search").on("click", function(e) {
	    $("#header-search-dropdown").toggleClass("is-active")
	});

	if (TBUI.is_header_fixed == 1) {
		if ($(window).width() > 1024 ) {
	 		var fixedheader = $('header.header');
	 		$(window).scroll(function() {
			    var h = document.documentElement.scrollTop + document.body.scrollTop;
			    if (h > 88) {
			    	fixedheader.addClass('fixed');
			    	$('body').addClass('fixed');
			    }else{
			    	fixedheader.removeClass('fixed')
			    	$('body').removeClass('fixed')
			    }
			})
	 	}
	}

	 // CONET download_popup A
    $("#download-popup").on("click",function(){
        var url = $(this).attr('data-url');
        var info = $(this).attr('data-info');
        if (!info) {info='无需密码'}
        popup.showCustomModal({
            template: "Download",  // AlipayQrcode; WeixinpayQrcode ;Popup；PayMethod
            layerClose: 1,
            data: {
		        url: url,
		        info:info
		    }
        });
        return false;
    });


	// if (TBUI.is_header_fixed == 1) {
	// 	//自定义弹出公告
		
	// 	function timmerpopup(){
	// 	  popup.showCustomModal({
	// 	          template: "Popup",
	// 	          layerClose: 0,
	// 	          data: {
	// 	              html: TBUI.customnotehtml
	// 	          }
	// 	    });
	// 	}
	// 	setTimeout(timmerpopup,3000);　//8s后弹出
	// 	clearTimeout(timmerpopup); //取消
	// }
	// 移动端自动将下载信息放文章末尾
 	var this_max_width = $(window).width();
 	if (this_max_width < 1024 ) {
 		$(".sidebar .widget.widget-download").insertAfter($(".article-content .article-actions.clearfix"));
 	}else{
 		var t_sidebar = $('.container .sidebar') ? 1 : 0
 		if (t_sidebar) {
 			$('.container .sidebar').theiaStickySidebar({
		        additionalMarginTop: 20
		    });
 		}
 	}

 	// 弹窗登录 alert
	if (TBUI.is_login_popup == 1) {
		var etap_login = $('[etap="login_btn"]');
		var etap_register = $('[etap="register_btn"]');
		var oauth_html = '';
		var email_reg_html = '';
		if (TBUI.is_oauth_qq == 1) {
			oauth_html = '<div class="sign-qq"> <a href="'+TBUI.uri+'/oauth/qq?rurl='+window.location.href+'" title="使用QQ账户登录"></a> </div>';
		}
		if (TBUI.is_email_reg == 1) {
			email_reg_html = '<div class="item"> <input class="ipt" id="captcha" type="text" name="captcha" placeholder="输入验证码" required="" /> <span class="captcha-clk inline">发送邮箱验证码</span> </div>';
		}
		etap_login.on(TBUI.click,function(e){
			e.preventDefault();
			// $("footer .popup-login").remove();
		    popup.showCustomModal({
		        template: "Login",
		        layerClose: 1,
		        data: {
			        html: oauth_html
			    }
		    });

		});
		etap_register.on(TBUI.click,function(e){
			e.preventDefault();
			$("footer .popup-register").remove();
		    popup.showCustomModal({
		        template: "Register",
		        layerClose: 1,
		        data: {
			        html: oauth_html,
			        email_html: email_reg_html
			    }
		    });
		})

	}

	// LOGING REG
	//REMOVE THIS - it's just to show error messages 
	$('.sign-form').find('input[type="submit"]').on('click', function(event){
		event.preventDefault();
		$('.sign-form').find('input[type="email"]').toggleClass('has-error').next('span').toggleClass('is-visible');
	});



	//登陆
	$(document).ready(function() {
		var _loginTipstimer
		function logtips(str){
			if( !str ) return false
			_loginTipstimer && clearTimeout(_loginTipstimer)
			$('.sign-tips').html(str).animate({
				height: 60
			}, 220)
			_loginTipstimer = setTimeout(function(){
				$('.sign-tips').animate({
					height: 0
				}, 220)
			}, 5000)
		}
		
		function is_check_name(str) {    
			return /^[\w]{3,16}$/.test(str) 
		}
		function is_check_mail(str) {
			return /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/.test(str)
		}
			

		$('body').on('click','.captcha-clk',function(){
			if( !is_check_mail($("#user_email").val()) ){
				logtips('邮箱格式错误')
				return
			}
			
			var captcha = $(this);
			if(captcha.hasClass("disabled")){
				logtips('您操作太快了，等等吧')
			}else{
				captcha.addClass("disabled");
				captcha.html("发送中...");
				$.post(
					TBUI.uri+'/action/captcha.php?'+Math.random(),
					{
						action: "WPAY_captcha",
						email:$("#user_email").val()
					},
					function (data) {
						if($.trim(data) == "1"){
							logtips('已发送验证码至邮箱')
							var countdown=60; 
							settime()
							function settime() { 
								if (countdown == 0) { 
									captcha.removeClass("disabled");   
									captcha.html("发送验证码");
									countdown = 60; 
									return;
								} else { 
									captcha.addClass("disabled");
									captcha.html("重新发送(" + countdown + ")"); 
									countdown--; 
								} 
								setTimeout(function() { settime() },1000) 
							}

						}else if($.trim(data) == "2"){
							logtips('邮箱已存在')
							captcha.html("发送验证码");
							captcha.removeClass("disabled"); 
						}else if($.trim(data) == "3"){
							logtips('该站点未启用SMTP邮件功能')
							captcha.html("发送验证码");
							captcha.removeClass("disabled"); 
						}else{
							logtips('验证码发送失败，请稍后重试')
							captcha.html("发送验证码");
							captcha.removeClass("disabled"); 
						}
					}
				);
			}
		});

		$('body').on('click', '.login-loader',function(){
			logtips("登录中，请稍等...");									
			$.post(
				TBUI.uri+'/action/login.php',
				{
					usr: $("#username").val(),
					pwd: $("#password").val(),
					rememberme: $('#rememberme').val(),
					action: "WPAY_login"
					
				},
				function (data) {
					if (parseInt(data) != "1") {
						logtips("用户名或密码错误");
					}
					else {
						logtips("登录成功，跳转中...");
						location.reload();                     
					}
				}
			);
		})
		$('body').on('click', '.register-loader',function(){
			if( !is_check_name($("#user_name").val()) ){
				logtips('用户名只能由字母数字或下划线组成的3-16位字符')
				return
			}
			
			if( !is_check_mail($("#user_email").val()) ){
				logtips('邮箱格式错误')
				return
			}

			if( $("#user_pass").val().length < 6 ){
				logtips('密码太短，至少6位')
				return
			}
			
			if( $("#user_pass").val() != $("#user_pass2").val()){
				logtips('两次输入密码不一致')
				return
			}
			
			logtips("注册中，请稍等...");
			$.post(
				TBUI.uri+'/action/login.php',
				{
					user_register: $("#user_name").val(),
					user_email: $("#user_email").val(),
					password: $("#user_pass").val(),
					captcha: $("#captcha").val(),
					action: "WPAY_register"
				},
				function (data) {
					if (parseInt(data) == 1) {
						logtips("注册成功，登录中...");
						location.reload(); 
					}
					else {
						logtips(data);
					}
				}
			);										   
		})
	});

	//fancybox
	$(function() {
		jQuery(".gallery a").attr("data-fancybox","images");
	});

	// LAZYLOAD
	$('.excerpts .thumb').lazyload({
        data_attribute: 'src',
        threshold: 400
    });

    $('.postitems .thumb').lazyload({
        data_attribute: 'src',
        threshold: 400
    });

    $('.sidebar .thumb').lazyload({
        data_attribute: 'src',
        threshold: 400
    });


    $('.sidebar .avatar').lazyload({
        data_attribute: 'src',
        threshold: 400
    });

    $('.comments-respond .avatar').lazyload({
        data_attribute: 'src',
        threshold: 400
    });

    $('.postcomments .avatar').lazyload({
        data_attribute: 'src',
        threshold: 400
    });




	// AJAXPAGING
	if( TBUI.ajaxpager > 0 ){
	    $.ias({
	        // thresholdMargin: 400,
			triggerPageThreshold : TBUI.ajaxpager,
			history              : true,
			container            : '.excerpts',
			item                 : '.excerpt',
			pagination           : '.pagination',
			next                 : '.next-page a',
			loader               : '<div class="pagination-loading"><img src="'+TBUI.uri+'/img/loading.gif"></div>',
			trigger              : '加载更多',
			onRenderComplete     : function() {
				$('.excerpt:gt(-'+(TBUI.pagenum+1)+') .thumb').lazyload({
			        data_attribute: 'src',
			        threshold: 0
			    });
			}
	    });
    }






    // REWARDS
    $('[etap="rewards"]').on(TBUI.click, function(){
    	$('.rewards-popover-mask, .rewards-popover').fadeIn()
    })

    $('[etap="rewards-close"]').on(TBUI.click, function(){
    	$('.rewards-popover-mask, .rewards-popover').fadeOut()
    })


    


    // ROLLBAR
	TBUI.bd.append('<div class="rollbar">'+ (is_page('comment-open') ? '<div class="rollbar-item" etap="to_comments"><i class="fa">&#xe655;</i></div>' : '') +'<div class="rollbar-item" etap="to_top"><i class="fa">&#xe619;</i></div></div>')

	var scroller = $('.rollbar')
	$(window).scroll(function() {
	    var h = document.documentElement.scrollTop + document.body.scrollTop
	    h > 200 ? scroller.fadeIn() : scroller.fadeOut();
	})


	$('[etap="to_comments"]').on(TBUI.click, function(){
		$('html,body').animate({
            scrollTop: $('#comments').offset().top + 15
        }, 300, function(){
        	$('#comment').focus()
        })
	})

	$('[etap="to_top"]').on(TBUI.click, function(){
		$('html,body').animate({
            scrollTop: 0
        }, 300)
	})




    // SHARE IMAGE
    if( TBUI.shareimage ){
        if( !TBUI.shareimagethumb && $('.article-content img:first').length ){
            TBUI.shareimage = $('.article-content img:first').attr('src')
        }
        TBUI.bd.prepend('<div id="shareimage"><img src="'+ TBUI.shareimage +'"></div>')
    }



    // SHARE
	var share = {
        url: document.URL,
        pic: TBUI.shareimage,
        title: document.title || '',
        desc: $('meta[name="description"]').length ? $('meta[name="description"]').attr('content') : ''    
    }


    $('.share-weixin').each(function(){
	    if( !$(this).find('.share-popover').length ){
			$(this).append('<span class="share-popover"><span class="share-popover-inner" id="weixin-qrcode"></span></span>')
			$('#weixin-qrcode').qrcode({
				width: 80,
				height: 80,
				text: $(this).data('url')
			})
		}
	})


	$('[etap="share"]').on(TBUI.click, function(){

		var dom = $(this)
	    var to = dom.data('share')
	    var url = ''

	    switch(to){
	        case 'qq':
	            url = 'http://connect.qq.com/widget/shareqq/index.html?url='+share.url+'&desc='+share.desc+'&summary='+share.title+'&site=zeshlife&pics='+share.pic
	            break;

	        case 'weibo':
	            url = 'http://service.weibo.com/share/share.php?title='+share.title+'&url='+share.url+'&source=bookmark&pic='+share.pic
	            break;

	        case 'douban':
	            url = 'http://www.douban.com/share/service?image='+share.pic+'&href='+share.url+'&name='+share.title+'&text='+share.desc
	            break;

	        case 'qzone':
	            url = 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url='+share.url+'&title='+share.title+'&desc='+share.desc
	            break;

	        case 'tqq':
	            url = 'http://share.v.t.qq.com/index.php?c=share&a=index&url='+share.url+'&title='+share.title
	            break;

	        case 'renren':
	            url = 'http://widget.renren.com/dialog/share?srcUrl='+share.pic+'&resourceUrl='+share.url+'&title='+share.title+'&description='+share.desc
	            break;

	        case 'line':
	            url = 'http://line.naver.jp/R/msg/text/?'+share.title+'%0D%0A'+share.url
	            break;

	        case 'twitter':
	            url = 'https://twitter.com/intent/tweet?text='+share.title+'&url='+share.url
	            break;

	        case 'facebook':
	            url = 'https://www.facebook.com/sharer/sharer.php?u='+share.url+'&title='+share.title+'&description='+share.desc
	            break;

	    }

	    if( !dom.attr('href') && !dom.attr('target') ){
	    	dom.attr('href', url).attr('target', '_blank')
	    }
	})




	// LIKE
    $('.excerpts, .article-actions').on(TBUI.click, '[etap="like"]', function(){
    	var dom = $(this)
	    var pid = dom.attr('data-pid')

	    if( dom.hasClass('actived') ) return popup.showToast({type: "text",text:"您已赞"})

	    if ( !pid || !/^\d{1,}$/.test(pid) ) return;
	    
        var likes = $.cookie('likes') || ''
        if( $.inArray(pid, likes.split('.'))!==-1 ) return popup.showToast({type: "text",text:"您已赞"})
 
	    $.ajax({
	        url: TBUI.uri + '/action/like.php',
	        type: 'POST',
	        dataType: 'json',
	        data: {
	            id: pid
	        },
	        success: function(data, textStatus, xhr) {
	        	if (data.error==6) return popup.showToast({type: "text",text:"您已赞"})
	            if (data.error) return false;
	            dom.toggleClass('actived')
	            dom.find('span').html(data.response)
	        }
	    });
    })


	function is_page(name){
	    return TBUI.bd.hasClass(name) ? true : false;
	}

	// COMMENTS
	$('.commentlist .url').attr('target','_blank')

	$('.comment-user-change').on(TBUI.click, function(){
		$('#comment-author-info').slideDown(300)
    	$('#comment-author-info input:first').focus()
	})
	$('body').on('click', '.comment-reply-link', function(){
		addComment.moveForm( "comment-"+$(this).attr('data-commentid'), $(this).attr('data-commentid'), "respond", $(this).attr('data-postid') );
		return false; // 阻止默认行为
	});

    var edit_mode = '0',
        txt1 = '<div class="comt-tip comt-loading">评论提交中...</div>',
        txt2 = '<div class="comt-tip comt-error">#</div>',
        txt3 = '">',
        cancel_edit = '取消编辑',
        edit,
        num = 1,
        comm_array = [];
    comm_array.push('');

    $comments = $('#comments-title');
    $cancel = $('#cancel-comment-reply-link');
    cancel_text = $cancel.text();
    $submit = $('#commentform #submit');
    $submit.attr('disabled', false);
    $('.comt-tips').append(txt1 + txt2);
    $('.comt-loading').hide();
    $('.comt-error').hide();
    $body = (window.opera) ? (document.compatMode == "CSS1Compat" ? $('html') : $('body')) : $('html,body');
    $('#commentform').submit(function() {
        $('.comt-loading').slideDown(300);
        $submit.attr('disabled', true).fadeTo('slow', 0.5);
        if (edit) $('#comment').after('<input type="text" name="edit_id" id="edit_id" value="' + edit + '" style="display:none;" />');
        $.ajax({
            url: TBUI.uri + '/action/comment.php',
            data: $(this).serialize(),
            type: $(this).attr('method'),
            error: function(request) {
                $('.comt-loading').slideUp(300);
                $('.comt-error').slideDown(300).html(request.responseText);
                setTimeout(function() {
                        $submit.attr('disabled', false).fadeTo('slow', 1);
                        $('.comt-error').slideUp(300)
                    },
                    3000)
            },
            success: function(data) {
                $('.comt-loading').slideUp(300);
                comm_array.push($('#comment').val());
                $('textarea').each(function() {
                    this.value = ''
                });
                var t = addComment,
                    cancel = t.I('cancel-comment-reply-link'),
                    temp = t.I('wp-temp-form-div'),
                    respond = t.I(t.respondId),
                    post = t.I('comment_post_ID').value,
                    parent = t.I('comment_parent').value;
                if (!edit && $comments.length) {
                    n = parseInt($comments.text().match(/\d+/));
                    $comments.text($comments.text().replace(n, n + 1))
                }
                new_htm = '" id="new_comm_' + num + '"></';
                new_htm = (parent == '0') ? ('\n<ol style="clear:both;" class="commentlist commentnew' + new_htm + 'ol>') : ('\n<ul class="children' + new_htm + 'ul>');
                ok_htm = '\n<span id="success_' + num + txt3;
                ok_htm += '</span><span></span>\n';

                if (parent == '0') {
                    if ($('#postcomments .commentlist').length) {
                        $('#postcomments .commentlist').before(new_htm);
                    } else {
                        $('#respond').after(new_htm);
                    }
                } else {
                    $('#respond').after(new_htm);
                }

                $('#comment-author-info').slideUp()

                // console.log( $('#new_comm_' + num) )
                $('#new_comm_' + num).hide().append(data);
                $('#new_comm_' + num + ' li').append(ok_htm);
                $('#new_comm_' + num).fadeIn(1000);
                /*$body.animate({
                        scrollTop: $('#new_comm_' + num).offset().top - 200,002
                    },
                    500);*/
                $('#new_comm_' + num).find('.comt-avatar .avatar').attr('src', $('.commentnew .avatar:last').attr('src'));
                countdown();
                num++;
                edit = '';
                $('*').remove('#edit_id');
                cancel.style.display = 'none';
                cancel.onclick = null;
                t.I('comment_parent').value = '0';
                if (temp && respond) {
                    temp.parentNode.insertBefore(respond, temp);
                    temp.parentNode.removeChild(temp)
                }
            }
        });
        return false
    });
    addComment = {
        moveForm: function(commId, parentId, respondId, postId, num) {
            var t = this,
                div, comm = t.I(commId),
                respond = t.I(respondId),
                cancel = t.I('cancel-comment-reply-link'),
                parent = t.I('comment_parent'),
                post = t.I('comment_post_ID');
            if (edit) exit_prev_edit();
            num ? (t.I('comment').value = comm_array[num], edit = t.I('new_comm_' + num).innerHTML.match(/(comment-)(\d+)/)[2], $new_sucs = $('#success_' + num), $new_sucs.hide(), $new_comm = $('#new_comm_' + num), $new_comm.hide(), $cancel.text(cancel_edit)) : $cancel.text(cancel_text);
            t.respondId = respondId;
            postId = postId || false;
            if (!t.I('wp-temp-form-div')) {
                div = document.createElement('div');
                div.id = 'wp-temp-form-div';
                div.style.display = 'none';
                respond.parentNode.insertBefore(div, respond)
            }!comm ? (temp = t.I('wp-temp-form-div'), t.I('comment_parent').value = '0', temp.parentNode.insertBefore(respond, temp), temp.parentNode.removeChild(temp)) : comm.parentNode.insertBefore(respond, comm.nextSibling);
            $body.animate({
                    scrollTop: $('#respond').offset().top - 180
                },
                400);
                // pcsheight()
            if (post && postId) post.value = postId;
            parent.value = parentId;
            cancel.style.display = '';
            cancel.onclick = function() {
                if (edit) exit_prev_edit();
                var t = addComment,
                    temp = t.I('wp-temp-form-div'),
                    respond = t.I(t.respondId);
                t.I('comment_parent').value = '0';
                if (temp && respond) {
                    temp.parentNode.insertBefore(respond, temp);
                    temp.parentNode.removeChild(temp)
                }
                this.style.display = 'none';
                this.onclick = null;
                return false
            };
            try {
                t.I('comment').focus()
            } catch (e) {}
            return false
        },
        I: function(e) {
            return document.getElementById(e)
        }
    };

    function exit_prev_edit() {
        $new_comm.show();
        $new_sucs.show();
        $('textarea').each(function() {
            this.value = ''
        });
        edit = ''
    }
    var wait = 15,
        submit_val = $submit.val();

    function countdown() {
        if (wait > 0) {
            $submit.val(wait);
            wait--;
            setTimeout(countdown, 1000)
        } else {
            $submit.val(submit_val).attr('disabled', false).fadeTo('slow', 1);
            wait = 15
        }
    }


})