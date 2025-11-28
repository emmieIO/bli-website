import{b as d,u as q,j as K}from"./app-RI3mvuVx.js";let Q={data:""},V=e=>{if(typeof window=="object"){let t=(e?e.querySelector("#_goober"):window._goober)||Object.assign(document.createElement("style"),{innerHTML:" ",id:"_goober"});return t.nonce=window.__nonce__,t.parentNode||(e||document.head).appendChild(t),t.firstChild}return e||Q},W=/(?:([\u0080-\uFFFF\w-%@]+) *:? *([^{;]+?);|([^;}{]*?) *{)|(}\s*)/g,G=/\/\*[^]*?\*\/|  +/g,_=/\n+/g,x=(e,t)=>{let a="",s="",i="";for(let o in e){let r=e[o];o[0]=="@"?o[1]=="i"?a=o+" "+r+";":s+=o[1]=="f"?x(r,o):o+"{"+x(r,o[1]=="k"?"":t)+"}":typeof r=="object"?s+=x(r,t?t.replace(/([^,])+/g,n=>o.replace(/([^,]*:\S+\([^)]*\))|([^,])+/g,l=>/&/.test(l)?l.replace(/&/g,n):n?n+" "+l:l)):o):r!=null&&(o=/^--/.test(o)?o:o.replace(/[A-Z]/g,"-$&").toLowerCase(),i+=x.p?x.p(o,r):o+":"+r+";")}return a+(t&&i?t+"{"+i+"}":i)+s},h={},F=e=>{if(typeof e=="object"){let t="";for(let a in e)t+=a+F(e[a]);return t}return e},J=(e,t,a,s,i)=>{let o=F(e),r=h[o]||(h[o]=(l=>{let u=0,p=11;for(;u<l.length;)p=101*p+l.charCodeAt(u++)>>>0;return"go"+p})(o));if(!h[r]){let l=o!==e?e:(u=>{let p,c,f=[{}];for(;p=W.exec(u.replace(G,""));)p[4]?f.shift():p[3]?(c=p[3].replace(_," ").trim(),f.unshift(f[0][c]=f[0][c]||{})):f[0][p[1]]=p[2].replace(_," ").trim();return f[0]})(e);h[r]=x(i?{["@keyframes "+r]:l}:l,a?"":"."+r)}let n=a&&h.g?h.g:null;return a&&(h.g=h[r]),((l,u,p,c)=>{c?u.data=u.data.replace(c,l):u.data.indexOf(l)===-1&&(u.data=p?l+u.data:u.data+l)})(h[r],t,s,n),r},X=(e,t,a)=>e.reduce((s,i,o)=>{let r=t[o];if(r&&r.call){let n=r(a),l=n&&n.props&&n.props.className||/^go/.test(n)&&n;r=l?"."+l:n&&typeof n=="object"?n.props?"":x(n,""):n===!1?"":n}return s+i+(r??"")},"");function D(e){let t=this||{},a=e.call?e(t.p):e;return J(a.unshift?a.raw?X(a,[].slice.call(arguments,1),t.p):a.reduce((s,i)=>Object.assign(s,i&&i.call?i(t.p):i),{}):a,V(t.target),t.g,t.o,t.k)}let L,A,I;D.bind({g:1});let v=D.bind({k:1});function ee(e,t,a,s){x.p=t,L=e,A=a,I=s}function w(e,t){let a=this||{};return function(){let s=arguments;function i(o,r){let n=Object.assign({},o),l=n.className||i.className;a.p=Object.assign({theme:A&&A()},n),a.o=/ *go\d+/.test(l),n.className=D.apply(a,s)+(l?" "+l:"");let u=e;return e[0]&&(u=n.as||e,delete n.as),I&&u[0]&&I(n),L(u,n)}return t?t(i):i}}var te=e=>typeof e=="function",O=(e,t)=>te(e)?e(t):e,ae=(()=>{let e=0;return()=>(++e).toString()})(),M=(()=>{let e;return()=>{if(e===void 0&&typeof window<"u"){let t=matchMedia("(prefers-reduced-motion: reduce)");e=!t||t.matches}return e}})(),re=20,P="default",H=(e,t)=>{let{toastLimit:a}=e.settings;switch(t.type){case 0:return{...e,toasts:[t.toast,...e.toasts].slice(0,a)};case 1:return{...e,toasts:e.toasts.map(r=>r.id===t.toast.id?{...r,...t.toast}:r)};case 2:let{toast:s}=t;return H(e,{type:e.toasts.find(r=>r.id===s.id)?1:0,toast:s});case 3:let{toastId:i}=t;return{...e,toasts:e.toasts.map(r=>r.id===i||i===void 0?{...r,dismissed:!0,visible:!1}:r)};case 4:return t.toastId===void 0?{...e,toasts:[]}:{...e,toasts:e.toasts.filter(r=>r.id!==t.toastId)};case 5:return{...e,pausedAt:t.time};case 6:let o=t.time-(e.pausedAt||0);return{...e,pausedAt:void 0,toasts:e.toasts.map(r=>({...r,pauseDuration:r.pauseDuration+o}))}}},C=[],B={toasts:[],pausedAt:void 0,settings:{toastLimit:re}},b={},U=(e,t=P)=>{b[t]=H(b[t]||B,e),C.forEach(([a,s])=>{a===t&&s(b[t])})},Y=e=>Object.keys(b).forEach(t=>U(e,t)),se=e=>Object.keys(b).find(t=>b[t].toasts.some(a=>a.id===e)),T=(e=P)=>t=>{U(t,e)},oe={blank:4e3,error:4e3,success:2e3,loading:1/0,custom:4e3},ie=(e={},t=P)=>{let[a,s]=d.useState(b[t]||B),i=d.useRef(b[t]);d.useEffect(()=>(i.current!==b[t]&&s(b[t]),C.push([t,s]),()=>{let r=C.findIndex(([n])=>n===t);r>-1&&C.splice(r,1)}),[t]);let o=a.toasts.map(r=>{var n,l,u;return{...e,...e[r.type],...r,removeDelay:r.removeDelay||((n=e[r.type])==null?void 0:n.removeDelay)||(e==null?void 0:e.removeDelay),duration:r.duration||((l=e[r.type])==null?void 0:l.duration)||(e==null?void 0:e.duration)||oe[r.type],style:{...e.style,...(u=e[r.type])==null?void 0:u.style,...r.style}}});return{...a,toasts:o}},ne=(e,t="blank",a)=>({createdAt:Date.now(),visible:!0,dismissed:!1,type:t,ariaProps:{role:"status","aria-live":"polite"},message:e,pauseDuration:0,...a,id:(a==null?void 0:a.id)||ae()}),k=e=>(t,a)=>{let s=ne(t,e,a);return T(s.toasterId||se(s.id))({type:2,toast:s}),s.id},m=(e,t)=>k("blank")(e,t);m.error=k("error");m.success=k("success");m.loading=k("loading");m.custom=k("custom");m.dismiss=(e,t)=>{let a={type:3,toastId:e};t?T(t)(a):Y(a)};m.dismissAll=e=>m.dismiss(void 0,e);m.remove=(e,t)=>{let a={type:4,toastId:e};t?T(t)(a):Y(a)};m.removeAll=e=>m.remove(void 0,e);m.promise=(e,t,a)=>{let s=m.loading(t.loading,{...a,...a==null?void 0:a.loading});return typeof e=="function"&&(e=e()),e.then(i=>{let o=t.success?O(t.success,i):void 0;return o?m.success(o,{id:s,...a,...a==null?void 0:a.success}):m.dismiss(s),i}).catch(i=>{let o=t.error?O(t.error,i):void 0;o?m.error(o,{id:s,...a,...a==null?void 0:a.error}):m.dismiss(s)}),e};var le=1e3,de=(e,t="default")=>{let{toasts:a,pausedAt:s}=ie(e,t),i=d.useRef(new Map).current,o=d.useCallback((c,f=le)=>{if(i.has(c))return;let g=setTimeout(()=>{i.delete(c),r({type:4,toastId:c})},f);i.set(c,g)},[]);d.useEffect(()=>{if(s)return;let c=Date.now(),f=a.map(g=>{if(g.duration===1/0)return;let $=(g.duration||0)+g.pauseDuration-(c-g.createdAt);if($<0){g.visible&&m.dismiss(g.id);return}return setTimeout(()=>m.dismiss(g.id,t),$)});return()=>{f.forEach(g=>g&&clearTimeout(g))}},[a,s,t]);let r=d.useCallback(T(t),[t]),n=d.useCallback(()=>{r({type:5,time:Date.now()})},[r]),l=d.useCallback((c,f)=>{r({type:1,toast:{id:c,height:f}})},[r]),u=d.useCallback(()=>{s&&r({type:6,time:Date.now()})},[s,r]),p=d.useCallback((c,f)=>{let{reverseOrder:g=!1,gutter:$=8,defaultPosition:R}=f||{},N=a.filter(y=>(y.position||R)===(c.position||R)&&y.height),Z=N.findIndex(y=>y.id===c.id),S=N.filter((y,z)=>z<Z&&y.visible).length;return N.filter(y=>y.visible).slice(...g?[S+1]:[0,S]).reduce((y,z)=>y+(z.height||0)+$,0)},[a]);return d.useEffect(()=>{a.forEach(c=>{if(c.dismissed)o(c.id,c.removeDelay);else{let f=i.get(c.id);f&&(clearTimeout(f),i.delete(c.id))}})},[a,o]),{toasts:a,handlers:{updateHeight:l,startPause:n,endPause:u,calculateOffset:p}}},ce=v`
from {
  transform: scale(0) rotate(45deg);
	opacity: 0;
}
to {
 transform: scale(1) rotate(45deg);
  opacity: 1;
}`,ue=v`
from {
  transform: scale(0);
  opacity: 0;
}
to {
  transform: scale(1);
  opacity: 1;
}`,pe=v`
from {
  transform: scale(0) rotate(90deg);
	opacity: 0;
}
to {
  transform: scale(1) rotate(90deg);
	opacity: 1;
}`,fe=w("div")`
  width: 20px;
  opacity: 0;
  height: 20px;
  border-radius: 10px;
  background: ${e=>e.primary||"#ff4b4b"};
  position: relative;
  transform: rotate(45deg);

  animation: ${ce} 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275)
    forwards;
  animation-delay: 100ms;

  &:after,
  &:before {
    content: '';
    animation: ${ue} 0.15s ease-out forwards;
    animation-delay: 150ms;
    position: absolute;
    border-radius: 3px;
    opacity: 0;
    background: ${e=>e.secondary||"#fff"};
    bottom: 9px;
    left: 4px;
    height: 2px;
    width: 12px;
  }

  &:before {
    animation: ${pe} 0.15s ease-out forwards;
    animation-delay: 180ms;
    transform: rotate(90deg);
  }
`,me=v`
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
`,ge=w("div")`
  width: 12px;
  height: 12px;
  box-sizing: border-box;
  border: 2px solid;
  border-radius: 100%;
  border-color: ${e=>e.secondary||"#e0e0e0"};
  border-right-color: ${e=>e.primary||"#616161"};
  animation: ${me} 1s linear infinite;
`,ye=v`
from {
  transform: scale(0) rotate(45deg);
	opacity: 0;
}
to {
  transform: scale(1) rotate(45deg);
	opacity: 1;
}`,be=v`
0% {
	height: 0;
	width: 0;
	opacity: 0;
}
40% {
  height: 0;
	width: 6px;
	opacity: 1;
}
100% {
  opacity: 1;
  height: 10px;
}`,he=w("div")`
  width: 20px;
  opacity: 0;
  height: 20px;
  border-radius: 10px;
  background: ${e=>e.primary||"#61d345"};
  position: relative;
  transform: rotate(45deg);

  animation: ${ye} 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275)
    forwards;
  animation-delay: 100ms;
  &:after {
    content: '';
    box-sizing: border-box;
    animation: ${be} 0.2s ease-out forwards;
    opacity: 0;
    animation-delay: 200ms;
    position: absolute;
    border-right: 2px solid;
    border-bottom: 2px solid;
    border-color: ${e=>e.secondary||"#fff"};
    bottom: 6px;
    left: 6px;
    height: 10px;
    width: 6px;
  }
`,ve=w("div")`
  position: absolute;
`,xe=w("div")`
  position: relative;
  display: flex;
  justify-content: center;
  align-items: center;
  min-width: 20px;
  min-height: 20px;
`,we=v`
from {
  transform: scale(0.6);
  opacity: 0.4;
}
to {
  transform: scale(1);
  opacity: 1;
}`,Ee=w("div")`
  position: relative;
  transform: scale(0.6);
  opacity: 0.4;
  min-width: 20px;
  animation: ${we} 0.3s 0.12s cubic-bezier(0.175, 0.885, 0.32, 1.275)
    forwards;
`,ke=({toast:e})=>{let{icon:t,type:a,iconTheme:s}=e;return t!==void 0?typeof t=="string"?d.createElement(Ee,null,t):t:a==="blank"?null:d.createElement(xe,null,d.createElement(ge,{...s}),a!=="loading"&&d.createElement(ve,null,a==="error"?d.createElement(fe,{...s}):d.createElement(he,{...s})))},$e=e=>`
0% {transform: translate3d(0,${e*-200}%,0) scale(.6); opacity:.5;}
100% {transform: translate3d(0,0,0) scale(1); opacity:1;}
`,je=e=>`
0% {transform: translate3d(0,0,-1px) scale(1); opacity:1;}
100% {transform: translate3d(0,${e*-150}%,-1px) scale(.6); opacity:0;}
`,Ce="0%{opacity:0;} 100%{opacity:1;}",Oe="0%{opacity:1;} 100%{opacity:0;}",De=w("div")`
  display: flex;
  align-items: center;
  background: #fff;
  color: #363636;
  line-height: 1.3;
  will-change: transform;
  box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1), 0 3px 3px rgba(0, 0, 0, 0.05);
  max-width: 350px;
  pointer-events: auto;
  padding: 8px 10px;
  border-radius: 8px;
`,Te=w("div")`
  display: flex;
  justify-content: center;
  margin: 4px 10px;
  color: inherit;
  flex: 1 1 auto;
  white-space: pre-line;
`,Ne=(e,t)=>{let a=e.includes("top")?1:-1,[s,i]=M()?[Ce,Oe]:[$e(a),je(a)];return{animation:t?`${v(s)} 0.35s cubic-bezier(.21,1.02,.73,1) forwards`:`${v(i)} 0.4s forwards cubic-bezier(.06,.71,.55,1)`}},ze=d.memo(({toast:e,position:t,style:a,children:s})=>{let i=e.height?Ne(e.position||t||"top-center",e.visible):{opacity:0},o=d.createElement(ke,{toast:e}),r=d.createElement(Te,{...e.ariaProps},O(e.message,e));return d.createElement(De,{className:e.className,style:{...i,...a,...e.style}},typeof s=="function"?s({icon:o,message:r}):d.createElement(d.Fragment,null,o,r))});ee(d.createElement);var Ae=({id:e,className:t,style:a,onHeightUpdate:s,children:i})=>{let o=d.useCallback(r=>{if(r){let n=()=>{let l=r.getBoundingClientRect().height;s(e,l)};n(),new MutationObserver(n).observe(r,{subtree:!0,childList:!0,characterData:!0})}},[e,s]);return d.createElement("div",{ref:o,className:t,style:a},i)},Ie=(e,t)=>{let a=e.includes("top"),s=a?{top:0}:{bottom:0},i=e.includes("center")?{justifyContent:"center"}:e.includes("right")?{justifyContent:"flex-end"}:{};return{left:0,right:0,display:"flex",position:"absolute",transition:M()?void 0:"all 230ms cubic-bezier(.21,1.02,.73,1)",transform:`translateY(${t*(a?1:-1)}px)`,...s,...i}},Pe=D`
  z-index: 9999;
  > * {
    pointer-events: auto;
  }
`,j=16,Re=({reverseOrder:e,position:t="top-center",toastOptions:a,gutter:s,children:i,toasterId:o,containerStyle:r,containerClassName:n})=>{let{toasts:l,handlers:u}=de(a,o);return d.createElement("div",{"data-rht-toaster":o||"",style:{position:"fixed",zIndex:9999,top:j,left:j,right:j,bottom:j,pointerEvents:"none",...r},className:n,onMouseEnter:u.startPause,onMouseLeave:u.endPause},l.map(p=>{let c=p.position||t,f=u.calculateOffset(p,{reverseOrder:e,gutter:s,defaultPosition:t}),g=Ie(c,f);return d.createElement(Ae,{id:p.id,key:p.id,onHeightUpdate:u.updateHeight,className:p.visible?Pe:"",style:g},p.type==="custom"?O(p.message,p):i?i(p):d.createElement(ze,{toast:p,position:c}))}))},E=m;function _e(){return K.jsx(Re,{position:"top-right",reverseOrder:!1,gutter:8,toastOptions:{duration:4e3,style:{background:"#fff",color:"#363636",padding:"16px",borderRadius:"8px",boxShadow:"0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)"},success:{iconTheme:{primary:"#10b981",secondary:"#fff"},style:{border:"1px solid #10b981"}},error:{iconTheme:{primary:"#ef4444",secondary:"#fff"},style:{border:"1px solid #ef4444"}}}})}function Fe(){const{flash:e}=q().props;d.useEffect(()=>{if(e!=null&&e.message)switch(e.type||"info"){case"success":E.success(e.message);break;case"error":E.error(e.message);break;case"warning":E(e.message,{icon:"⚠️",style:{border:"1px solid #f59e0b"}});break;case"info":E(e.message,{icon:"ℹ️",style:{border:"1px solid #3b82f6"}});break;default:E(e.message)}},[e])}export{_e as T,Fe as u};
