!function(){"use strict";function e(e,t){if(Array.isArray(t))t=t.join(" ");else if("function"==typeof t)t=t();else if("object"==typeof t&&null!==t)return function(e,t){let n=Object.entries(t),r=e=>e.split(" ").filter(Boolean),s=n.flatMap((([e,t])=>!!t&&r(e))).filter(Boolean),i=n.flatMap((([e,t])=>!t&&r(e))).filter(Boolean);const a=s.filter((t=>!e.classList.contains(t)&&(e.classList.add(t),!0))),o=i.filter((t=>e.classList.contains(t)&&(e.classList.remove(t),!0)));return()=>{o.forEach((t=>e.classList.add(t))),a.forEach((t=>e.classList.remove(t)))}}(e,t);return function(e,t){let n=t=>t.split(" ").filter((t=>!e.classList.contains(t))).filter(Boolean),r=t=>(e.classList.add(...t),()=>e.classList.remove(...t));return r(n(t=!0===t?"":t||""))}(e,t)}function t(e,n){return"object"==typeof n&&null!==n?function(e,n){let r={};return Object.entries(n).forEach((([t,n])=>{r[t]=e.style[t],t.startsWith("--")||(t=t.replace(/([a-z])([A-Z])/g,"$1-$2").toLowerCase()),e.style.setProperty(t,n)})),setTimeout((()=>0===e.style.length&&e.removeAttribute("style"))),()=>t(e,r)}(e,n):((e,t)=>{let n=e.getAttribute("style",t);return e.setAttribute("style",t),()=>{e.setAttribute("style",n||"")}})(e,n)}function n(e,t,n){let r;return function(){let s=this,i=arguments,a=n&&!r;clearTimeout(r),r=setTimeout((function(){r=null,n||e.apply(s,i)}),t),a&&e.apply(s,i)}}function r(e,t,n={},r=!1){return e=r?`with($data){${e}}`:/^[a-z][a-z\d]*(-[a-z\d]+)+$/.test(e)?`var result;with($data){result=$data['${e}']};return result`:`var result;with($data){result=${e}};return result`,new Function(["$data",...Object.keys(n)],e)(t,...Object.values(n))}function s(e){const t=/^(x-|x.|@|:)/;return[...e.attributes].filter((({name:e})=>t.test(e))).map((({name:e,value:n})=>{const r=e.match(t)[0],s=e.replace(r,""),i=s.split(".");return{name:e,directive:"x-"===r?e:":"===r?"x-bind":"",event:"@"===r?i[0]:"",expression:n,prop:"x."===r?i[0]:"",modifiers:"x."===r?i.slice(1):s.split(".").slice(1)}}))}function a(n,r,s){"value"===r?"radio"===n.type?n.checked=n.value===s:"checkbox"===n.type?n.checked=Array.isArray(s)?s.some((e=>e===n.value)):!!s:"SELECT"===n.tagName?function(e,t){const n=[].concat(t).map((e=>e+""));Array.from(e.options).forEach((e=>{e.selected=n.includes(e.value||e.text)}))}(n,s):n.value=s:"class"===r?function(t,n){t._x_undoAddedClasses&&t._x_undoAddedClasses();t._x_undoAddedClasses=e(t,n)}(n,s):"style"===r?function(e,n){e._x_undoAddedStyles&&e._x_undoAddedStyles();e._x_undoAddedStyles=t(e,n)}(n,s):["disabled","readonly","required","checked","autofocus","autoplay","hidden"].includes(r)?s?n.setAttribute(r,""):n.removeAttribute(r):n.setAttribute(r,s)}function o(e,t={}){return new CustomEvent(e,{detail:t,bubbles:!0,cancelable:!0})}function l(e,t,n=""){return e[e.indexOf(t)+1]||n}function c(e,t){t(e);let n=e.firstElementChild;for(;n;){if(n.hasAttribute("x-data"))return;c(n,t),n=n.nextElementSibling}}function d(e,t){const n=[];return c(e,(i=>{s(i).forEach((s=>{let{modifiers:a,prop:o,name:l}=s;if(o){if("checkbox"===i.type&&void 0===t[o]&&(t[o]=e.querySelectorAll(`[${CSS.escape(l)}]`).length>1?[]:""),function(e){return["input","select","textarea"].includes(e.tagName.toLowerCase())}(i)){let e=u(i,t,o,a),n=void 0!==t[o]?t[o]:null,s=r(e,t,{$el:i});t[o]=n&&(""===(c=s)||null===c||Array.isArray(c)&&0===c.length||"object"==typeof c&&0===Object.keys(c).length)?n:s}n.push({el:i,attribute:s})}var c}))})),document.dispatchEvent(o("x:fetched",{data:t,fetched:n})),t}function u(e,t,n,r){let s,i=e.tagName.toLowerCase();return s="checkbox"===e.type?Array.isArray(t[n])?`$el.checked ? ${n}.concat([$el.value]) : [...${n}.splice(0, ${n}.indexOf($el.value)), ...${n}.splice(${n}.indexOf($el.value)+1)]`:"$el.checked":"radio"===e.type?`$el.checked ? $el.value : (typeof ${n} !== 'undefined' ? ${n} : '')`:"select"===i&&e.multiple?`Array.from($el.selectedOptions).map(option => ${r.includes("number")?"parseFloat(option.value || option.text)":"option.value || option.text"})`:r.includes("number")?"parseFloat($el.value)":r.includes("trim")?"$el.value.trim()":"$el.value",e.hasAttribute("name")||e.setAttribute("name",n),`$data['${n}'] = ${s}`}class p{constructor(e){this.el=e,this.rawData=r(e.getAttribute("x-data")||"{}",{}),this.rawData=d(e,this.rawData),this.data=this.wrapDataInObservable(this.rawData),this.initialize(e,this.data)}evaluate(e,t){let n=[];return{output:r(e,new Proxy(this.data,{get:(e,t)=>(n.push(t),e[t])}),t),deps:n}}wrapDataInObservable(e){let t=this;return t.concernedData=[],new Proxy(e,{set(e,n,r){const s=Reflect.set(e,n,r);return-1===t.concernedData.indexOf(n)&&t.concernedData.push(n),t.refresh(),s}})}initialize(e,t,n){const r=this;c(e,(e=>{s(e).forEach((s=>{let{directive:i,event:o,expression:l,modifiers:c,prop:d}=s;if(o&&r.registerListener(e,o,c,l),d){let s=["select-multiple","select","checkbox","radio"].includes(e.type)||c.includes("lazy")?"change":"input";r.registerListener(e,s,c,u(e,t,d,c));let{output:i}=r.evaluate(d,n);a(e,"value",i)}if(i in x.directives){let t=l;if("x-for"!==i)try{({output:t}=r.evaluate(l,n))}catch(e){}x.directives[i](e,t,s,x,r)}}))}))}refresh(){const e=this;n((()=>{c(e.el,(t=>{s(t).forEach((n=>{let{directive:r,expression:s,prop:i}=n;if(i){let{output:r,deps:s}=e.evaluate(i);e.concernedData.filter((e=>s.includes(e))).length>0&&(a(t,"value",r),document.dispatchEvent(o("x:refreshed",{attribute:n,output:r})))}if(r in x.directives){let i=s,a=[];if("x-for"!==r)try{({output:i,deps:a}=e.evaluate(s))}catch(e){}else[,a]=s.split(" in ");e.concernedData.filter((e=>a.includes(e))).length>0&&x.directives[r](t,i,n,x,e)}}))})),e.concernedData=[]}),0)()}registerListener(e,t,r,s){const i=this,a=[];let c=e;function d(e){r.includes("prevent")&&e.preventDefault(),r.includes("stop")&&e.stopPropagation();let o=0;if(r.includes("delay")){const e=l(r,"delay").split("ms")[0];o=isNaN(e)?250:Number(e)}n((()=>{i.runListenerHandler(s,e),r.includes("once")&&(c.removeEventListener(t,d),e instanceof IntersectionObserverEntry&&function(e){const t=a.findIndex((t=>t.el===e));if(t>=0){const{observer:n}=a[t];n.unobserve(e),a.splice(t,1)}}(e.target))}),o)()}if(r.includes("window")&&(c=window),r.includes("document")&&(c=document),r.includes("outside")&&(c=document),r.includes("outside"))c.addEventListener(t,(t=>{e.contains(t.target)||e.offsetWidth<1&&e.offsetHeight<1||this.runListenerHandler(s,t)}));else if("load"===t)d(o("load",{}));else if("intersect"===t){const t=new IntersectionObserver((e=>e.forEach((e=>e.isIntersecting&&d(e)))));t.observe(e),a.push({el:e,observer:t})}else c.addEventListener(t,d)}runListenerHandler(e,t){const n={};Object.keys(x.methods).forEach((e=>n[e]=x.methods[e](t,t.target)));let s={},i=t.target;for(;i&&!(s=i.__x_for_data);)i=i.parentElement;r(e,this.data,{$el:t.target,$event:t,$refs:this.getRefsProxy(),...s,...n},!0)}getRefsProxy(){let e=this;return new Proxy({},{get(t,n){let r;return c(e.el,(e=>{e.hasAttribute("x-ref")&&e.getAttribute("x-ref")===n&&(r=e)})),r}})}}const h={directives:{},methods:{},start:async function(){await new Promise((e=>{"loading"===document.readyState?document.addEventListener("DOMContentLoaded",e):e()})),this.discoverComponents((e=>this.initializeElement(e))),this.listenUninitializedComponentsAtRunTime((e=>this.initializeElement(e)))},discoverComponents:e=>{Array.from(document.querySelectorAll("[x-data]")).forEach(e)},listenUninitializedComponentsAtRunTime:e=>{new MutationObserver((t=>t.forEach((t=>Array.from(t.addedNodes).filter((e=>1===e.nodeType&&e.matches("[x-data]"))).forEach(e))))).observe(document.querySelector("body"),{childList:!0,attributes:!0,subtree:!0})},initializeElement:e=>{e.__x=new p(e)}},f=(e,t)=>{if(e){if("cookie"===t){let t=document.cookie.match(new RegExp("(?:^|; )"+e.replace(/([.$?*|{}()\[\]\\\/+^])/g,"\\$1")+"=([^;]*)"));if(t){let e=decodeURIComponent(t[1]);try{return JSON.parse(e)}catch(t){return e}}}return"local"===t?localStorage.getItem(e):void 0}},m=(e,t,n,r={path:"/"})=>{if(e){if(t instanceof Object&&(t=JSON.stringify(t)),"cookie"===n){(r=r||{}).expires instanceof Date&&(r.expires=r.expires.toUTCString());let n=encodeURIComponent(e)+"="+encodeURIComponent(t);for(let e in r){n+="; "+e;let t=r[e];!0!==t&&(n+="="+t)}document.cookie=n}"local"===n&&(t?localStorage.setItem(e,t):localStorage.removeItem(e))}};function g(e){let t=e.charAt(e.length-1),n=parseInt(e,10);const r={y:"FullYear",m:"Month",d:"Date",h:"Hours",i:"Minutes",s:"Seconds"};if(t in r){const e=new Date,s=r[t];return e[`set${s}`](e[`get${s}`]()+n),e}return null}function b(e){return["cookie","local"].some((t=>e.includes(t)))}function y(e){return e.includes("cookie")?"cookie":"local"}document.addEventListener("x:refreshed",(({detail:e})=>{const{modifiers:t,prop:n}=e.attribute;if(b(t)){const r=y(t),s=l(t,r);e.output?m(n,e.output,r,{expires:g(s),secure:!0}):m(n,null,r,{expires:new Date,path:"/"})}})),document.addEventListener("x:fetched",(({detail:e})=>{const{data:t,fetched:n}=e;n.forEach((e=>{const{attribute:{modifiers:n,prop:r}}=e;if(b(n)){const e=y(n),s=f(r,e);t[r]=function(e,t){switch(typeof e){case"string":return String(t);case"number":return Number.isInteger(e)?parseInt(t,10):parseFloat(t);case"boolean":return Boolean(t);case"object":return e instanceof Date?new Date(t):Array.isArray(e)?Array.from(t):Object(t);case"undefined":return null===e?null:"true"===t||"false"!==t&&t;default:return t}}(t[r],s||t[r])}}))}));function v(e,t){e=`x-${e}`,h.directives[e]?console.warn(`X.js: directive '${e}' is already exists.`):h.directives[e]=t}v("for",((e,t,n,s,i)=>{if("string"!=typeof t)return;const[,a,o="key",l]=t.match(/^\(?(\w+)(?:,\s*(\w+))?\)?\s+in\s+(\w+)$/)||[],c=r(`${l}`,i.data);for(;e.nextSibling;)e.nextSibling.remove();c.forEach(((t,n)=>{const r=e.cloneNode(!0);r.removeAttribute("x-for"),(async()=>{r.__x_for_data={[a]:t,[o]:n},await i.initialize(r,i.data,r.__x_for_data),e.parentNode.appendChild(r)})()}))})),v("bind",((e,t,{name:n},r,s)=>{":attributes"===n&&"object"==typeof t?Object.entries(t).forEach((([t,n])=>a(e,t,n))):a(e,n.replace(":",""),t)})),v("html",((e,t,n,r,s)=>{e.innerHTML=t})),v("text",((e,t,n,r,s)=>{e.innerText=t})),v("show",((e,t,n,r,s)=>{e.style.display=t?"block":"none"}));function w(e,t){e=`$${e}`,h.methods[e]?console.warn(`X.js: method '${e}' is already exists.`):h.methods[e]=t}const E=1048576;function A(e,t){const{loaded:n=0,total:r=0,type:s}=e,{response:i="",responseText:a="",status:o="",responseURL:l=""}=t;return{blob:new Blob([i]),json:JSON.parse(a||"[]"),raw:i,status:o,url:l,loaded:$(n),total:$(r),percent:r>0?Math.round(n/r*100):0,start:"loadstart"===s,progress:"progress"===s,end:"loadend"===s}}function $(e){return Math.round(e/E*100)/100}w("fetch",((e,t)=>(e,n={},r)=>{let s=t.tagName.toLowerCase(),i="form"===s?"post":"get",a="form"===s?new FormData(t):new FormData,o=new XMLHttpRequest;switch(s){case"form":Array.from(t.querySelectorAll("input[type='file']")).forEach((e=>{e.files&&[...e.files].forEach((t=>a.append(e.name,t)))}));break;case"textarea":case"select":case"input":"file"===t.type&&t.files?Array.from(t.files).forEach((e=>a.append(t.name,e))):t.name&&a.append(t.name,t.value)}return new Promise((t=>{o.open(i,e);for(const e in n.headers)o.setRequestHeader(e,n.headers[e]);o.withCredentials="include"===n.credentials,o.onloadstart=o.upload.onprogress=e=>r?.(A(e,o)),o.onloadend=e=>t((()=>r?.(A(e,o)))),o.send(a)})).then((e=>e()))})),w("dispatch",((e,t)=>(e,n={})=>{t.dispatchEvent(new CustomEvent(e,{detail:n,bubbles:!0,composed:!0,cancelable:!0}))})),v("sticky",((e,t,n,r,s)=>{let i=e.parentElement.currentStyle||window.getComputedStyle(e.parentElement);if("relative"!==i.position)return!1;let a=e.getBoundingClientRect().height-document.scrollingElement.offsetHeight,o=parseInt(i.paddingTop)+42,l=parseInt(i.paddingBottom),c=0,d=0,u="top: "+o+"px";["load","scroll","resize"].forEach((t=>window.addEventListener(t,(()=>function(){if(a>0){let e=document.scrollingElement.scrollTop;window.scrollY>c?e>a?(d=-1*a-l,u="top: "+d+"px"):u="top: "+(-1*e-l)+"px":(d+=c-window.scrollY,d<o&&(u="top: "+d+"px"))}e.setAttribute("style","position: sticky;"+u),c=window.scrollY}()))))})),v("autocomplete",((e,t,n,r,s)=>{e.setAttribute("readonly",!0),e.onfocus=()=>setTimeout((()=>e.removeAttribute("readonly")),10),e.onblur=()=>e.setAttribute("readonly",!0)})),v("highlight",((e,t,{modifiers:n},r,s)=>{let i=n[0]||"html",a=document.createElement("code");a.classList.add("language-"+i),a.innerHTML=e.innerHTML,e.classList.add("line-numbers"),e.innerHTML="",e.setAttribute("data-lang",i.toUpperCase()),e.appendChild(a)})),v("collapse",((e,t,n,r,s)=>{})),v("anchor",((e,t,n,r,s)=>{let i=window.location.hash.replace("#",""),a=e.innerText.toLowerCase().replaceAll(" ","-");i&&i===a&&e.scrollIntoView({behavior:"smooth"}),e.addEventListener("click",(t=>{t.preventDefault(),window.location.hash=a,e.scrollIntoView({behavior:"smooth"})}),!1);new IntersectionObserver((e=>{e.forEach((e=>{e.isIntersecting&&1===e.intersectionRatio&&(window.location.hash=a)}))}),{threshold:1}).observe(e)})),v("listen",((e,t,n,r,s)=>{if(!t)return!1;let a,o,l="listen-node";function c(e,t){e.pause(),e.setAttribute("data-playing","false"),t.classList.remove("playing")}let d=document.createElement("style");d.type="text/css",d.innerHTML=".listen-node {display: inline-block; background:rgba(0, 0, 0, 0.05); padding: 1px 8px 2px; border-radius:3px; cursor: pointer;} .listen-node i {font-size: 0.65em; border: 0.5em solid transparent; border-left: 0.75em solid; display: inline-block; margin-right: 2px;margin-bottom: 1px;} .listen-node .playing { border: 0; border-left: 0.75em double; border-right: 0.5em solid transparent; height: 1em;}",document.getElementsByTagName("head")[0].appendChild(d),a=document.createElement("audio"),o=document.createElement("i"),a.src=e.getAttribute("data-src"),a.setAttribute("data-playing","false"),e.id=l+"-"+i,e.insertBefore(o,e.firstChild),e.appendChild(a),document.addEventListener("click",(e=>{let t,n,r;e.target.className===l?(t=e.target.children[1],n=e.target,r=e.target.children[0]):e.target.parentElement&&e.target.parentElement.className===l&&(t=e.target.parentElement.children[1],n=e.target.parentElement,r=e.target),t&&n&&r&&(t.srt=parseInt(n.getAttribute("data-start"))||0,t.end=parseInt(n.getAttribute("data-end"))||t.duration,t&&"false"===t.getAttribute("data-playing")?((t.srt>t.currentTime||t.end<t.currentTime)&&(t.currentTime=t.srt),function(e,t){t.classList.add("playing"),e.play(),e.setAttribute("data-playing","true"),e.addEventListener("ended",(function(){return c(e,t),e.parentNode.style.background=null,!1}))}(t,r)):c(t,r),function e(){let s=requestAnimationFrame(e),i=100*(t.currentTime-t.srt)/(t.end-t.srt);i=i<100?i:100,n.style.background="linear-gradient(to right, rgba(0, 0, 0, 0.1)"+i+"%, rgba(0, 0, 0, 0.05)"+i+"%)",t.end<t.currentTime&&(c(t,r),cancelAnimationFrame(s))}())}))})),v("textarea",((e,t,n,r,s)=>{if("TEXTAREA"!==e.tagName.toUpperCase())return!1;e.addEventListener("input",(()=>{let n=parseInt(t)||99;if(parseInt(e.value.split(/\r|\r\n|\n/).length)>n)return!1;let r=getComputedStyle(e,null),s=4*parseInt(r.getPropertyValue("border-width"));e.style.height="auto",e.style.height=e.scrollHeight+s+4+"px"}),!1)})),v("tooltip",((e,t,{modifiers:n},r,s)=>{let i,a;if(n&&n.forEach((e=>{i=["top","right","bottom","left"].includes(e)?e:"top",a=["hover","click"].includes(e)?e:"hover"})),i&&a)try{new Drooltip({element:e,trigger:a,position:i,background:"#fff",color:"var(--grafema-dark)",animation:"bounce",content:content||null,callback:null})}catch(e){console.warn("You forgot to connect the library Drooltip.js")}})),v("progress",((e,t,{modifiers:n},r,s)=>{new IntersectionObserver(((t,r)=>{t.forEach((t=>{if(t.isIntersecting){let[t=100,s=0,i=100,a="0ms"]=n,o=parseInt(s)/parseInt(t)*100,l=parseInt(i)/parseInt(t)*100;o>l&&([l,o]=[o,l]),e.style.setProperty("--grafema-progress",(o<0?0:o)+"%"),setTimeout((()=>{e.style.setProperty("--grafema-transition"," width "+a),e.style.setProperty("--grafema-progress",(l>100?100:l)+"%")}),500),r.unobserve(e)}}))})).observe(e)})),v("select",((e,t,n,r,s)=>{const i={showSearch:!1,hideSelected:!1,closeOnSelect:!0};e.hasAttribute("multiple")&&(i.hideSelected=!0,i.closeOnSelect=!1);const a=JSON.parse(t||"{}");"object"==typeof a&&Object.assign(i,a);try{new SlimSelect({settings:i,select:e,data:Array.from(e.options).reduce(((e,t)=>{let n=t.getAttribute("data-image"),r=t.getAttribute("data-icon"),s=t.getAttribute("data-description")||"",i=s?`<span class="ss-description">${s}</span>`:"",a=`${n?`<img src="${n}" alt />`:""}${r?`<i class="${r}"></i>`:""}<span class="ss-text">${t.text}${i}</span>`,o={text:t.text,value:t.value,html:a,selected:t.selected,display:!0,disabled:!1,mandatory:!1,placeholder:!1,class:"",style:"",data:{}};if("OPTGROUP"===t.parentElement.tagName){const n=t.parentElement.getAttribute("label"),r=e.find((e=>e.label===n));r?r.options.push(o):e.push({label:n,options:[o]})}else e.push(o);return e}),[])})}catch{console.error("The SlimSelect library is not connected")}})),v("starter",((e,t,n,r,s)=>{})),w("copy",((e,t)=>e=>{window.navigator.clipboard.writeText(e).then((()=>{let e="ph-copy ph-check".split(" ");e.forEach((e=>t.classList.toggle(e))),setTimeout((()=>e.forEach((e=>t.classList.toggle(e)))),1e3)}),(()=>{console.log("Your browser is not support clipboard!")}))}));let L=0,k=!1;w("countdown",(()=>({start:(e,t,n)=>{k||(L=e,k=!0,function e(){t&&t(!0),0===L?(n&&n(!0),k=!1):(L--,setTimeout(e,1e3))}())},second:L})));let S=null;w("stream",(()=>({check(e){let t=e.canvas,n=e.video,r=e.image;return t?n?r?void 0:(console.error("Image for output selfie is undefined"),!1):(console.error("Video for selfie preview is undefined"),!1):(console.error("Canvas element is undefined"),!1)},isVisible(e){const t=window.getComputedStyle(e);return!!t&&!("hidden"===t.visibility||"none"===t.display||0===parseFloat(t.opacity))},start(e){let t=e.video;new MutationObserver((e=>{for(let n of e)n.target!==document.body||S||setTimeout((async()=>{this.isVisible(t)&&(navigator.mediaDevices&&navigator.mediaDevices.getUserMedia?t.srcObject=S=await navigator.mediaDevices.getUserMedia({video:!0}):console.error("The browser does not support the getUserMedia API"))}),500)})).observe(document,{childList:!0,subtree:!0,attributes:!0})},snapshot(e){this.check(e),this.start(e);let t=e.canvas,n=e.video,r=e.image,s=n.offsetWidth,i=n.offsetHeight,a=window.getComputedStyle(r),o=parseInt(a.width,10),l=parseInt(a.height,10);t.width=o,t.height=l;let c=(i-l)/2,d=(s-o)/2,u=t.getContext("2d");u.imageSmoothingQuality="low";console.log((c+d)/2),u.drawImage(n,1.5*d,1.5*c,1.5*i,1.5*i,0,0,o,l);let p=t.toDataURL("image/png");return p&&(r.src=p,u.clearRect(0,0,t.width,t.height)),p},stop(){S&&S.getTracks().forEach((e=>e.stop())),S=null}}))),w("password",(()=>({min:{lowercase:2,uppercase:2,special:2,digit:2,length:12},valid:{lowercase:!1,uppercase:!1,special:!1,digit:!1,length:!1},charsets:{lowercase:"abcdefghijklmnopqrstuvwxyz",uppercase:"ABCDEFGHIJKLMNOPQRSTUVWXYZ",special:"!@#$%^&*(){|}~",digit:"0123456789"},switch:e=>!e,check(e){let t=0,n=0;for(const r in this.charsets){let s=this.min[r],i=new RegExp(`[${this.charsets[r]}]`,"g"),a=(e.match(i)||[]).length;t+=Math.min(a,s),n+=s,this.valid[r]=a>=s}return e.length>=this.min.length&&(t+=1,n+=1,this.valid.length=e.length>=this.min.length),Object.assign({progress:0===n?n:t/n*100},this.valid)},generate(){let e="",t=Object.keys(this.charsets);for(t.forEach((t=>{let n=Math.max(this.min[t],0),r=this.charsets[t];for(let t=0;t<n;t++){let t=Math.floor(Math.random()*r.length);e+=r[t]}}));e.length<this.min.length;){let n=t[Math.floor(Math.random()*t.length)],r=this.charsets[n],s=Math.floor(Math.random()*r.length);e+=r[s]}return this.check(e),this.shuffle(e)},shuffle(e){let t,n,r=e.split(""),s=r.length;for(;0!==s;)n=Math.floor(Math.random()*s),s-=1,t=r[s],r[s]=r[n],r[n]=t;return r.join("")}}))),w("modal",((e,t)=>({open:(e,t)=>{setTimeout((()=>{let n=document.getElementById(e);n&&n.classList.add("is-active",t||"fade"),document.body.style.overflow="hidden"}),25)},close:e=>{let n=t.closest(".modal");null!==n&&n.classList.contains("is-active")&&(n.classList.remove("is-active",e||"fade"),document.body.style.overflow="")}}))),w("default",((e,t)=>(e,t={},n)=>{})),window.x=h,window.x.start()}();