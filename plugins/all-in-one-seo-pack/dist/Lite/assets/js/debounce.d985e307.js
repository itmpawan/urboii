import{r as b,i as C}from"./isArrayLikeObject.71906cce.js";import{t as k}from"./toNumber.8f3d4cd6.js";var M=function(){return b.Date.now()};const _=M;var R="Expected a function",S=Math.max,F=Math.min;function U(x,t,u){var a,c,m,o,e,r,s=0,T=!1,l=!1,g=!0;if(typeof x!="function")throw new TypeError(R);t=k(t)||0,C(u)&&(T=!!u.leading,l="maxWait"in u,m=l?S(k(u.maxWait)||0,t):m,g="trailing"in u?!!u.trailing:g);function p(n){var i=a,f=c;return a=c=void 0,s=n,o=x.apply(f,i),o}function I(n){return s=n,e=setTimeout(d,t),T?p(n):o}function L(n){var i=n-r,f=n-s,E=t-i;return l?F(E,m-f):E}function y(n){var i=n-r,f=n-s;return r===void 0||i>=t||i<0||l&&f>=m}function d(){var n=_();if(y(n))return h(n);e=setTimeout(d,L(n))}function h(n){return e=void 0,g&&a?p(n):(a=c=void 0,o)}function A(){e!==void 0&&clearTimeout(e),s=0,a=r=c=e=void 0}function W(){return e===void 0?o:h(_())}function v(){var n=_(),i=y(n);if(a=arguments,c=this,r=n,i){if(e===void 0)return I(r);if(l)return clearTimeout(e),e=setTimeout(d,t),p(r)}return e===void 0&&(e=setTimeout(d,t)),o}return v.cancel=A,v.flush=W,v}export{U as d};
