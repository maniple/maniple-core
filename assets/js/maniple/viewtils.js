define([],function(){var a={version:"0.1.0"};return a.esc=function(a){return String(a).replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/"/g,"&quot;").replace(/'/g,"&#39;").replace(/&(?!(\w+|\#\d+);)/g,"&amp;")},a.escrx=function(a){return String(a).replace(/([.*+?^${}()|[\]\/\\])/g,"\\$1")},a.Interp=function(b){var c=b||{},d=c.esc||a.esc,e=c.ldelim||"\\{\\s*",f=c.rdelim||"\\s*\\}",g=new RegExp(e+"(\\w+)"+f,"g");this.interp=function(a,b){return b=b||{},String(a).replace(g,function(a,c){return d?d(b[c]):b[c]})}},a.interp=function(){var b=new a.Interp;return function(a,c){return b.interp(a,c)}}(),a.camelize=function(){function a(a){return a.substr(1).toUpperCase()}return function(b){return String(b).replace(/(-[a-z])/g,a)}}(),a.dataset=function(b){var c,d,e,f,g,h={};if("number"==typeof b.length&&(b=b[0]),b&&b.attributes)for(c=b.attributes,f=0,g=c.length;g>f;++f)d=c[f],e=d.nodeName,0===e.indexOf("data-")&&(h[a.camelize(e.substr(5))]=d.nodeValue);return h},a.hooks=function(){var b=a.camelize,c=function(a,b){if(!a[b])throw new Error("Hook element '"+b+"' is not defined")},d=function(a,b,d){c(a,d),c(a,b);for(var e=a[d].parentNode;e;){if(e===a[b])return;e=e.parentNode}throw new Error("Hook '"+b+"' is not an ancestor of hook '"+d+"'")},e=function(a){for(var b=[],c=[a];c.length;){var d=c.shift();if(1==d.nodeType&&(null!==d.getAttributeNode("data-hook")&&b.push(d),null===d.getAttributeNode("data-hooks-nodescend")&&d.hasChildNodes()))for(var e=d.childNodes,f=0,g=e.length;g>f;++f){var h=e[f];1==h.nodeType&&c.push(h)}}return b};return function(a,f){var g,h,i,j,k,l,n={};if("string"==typeof f||f instanceof Array?(h=f,f=null):f&&(h=f.required),"string"==typeof h&&(h=-1===h.indexOf(" ")?h.split(/\s+/):[h]),f=f||{},a){var o=f.remove;g="object"==typeof a&&"length"in a?a:[a];for(i=0,k=g.length;k>i;++i){var p=e(g[i],"data-hook");for(j=0,m=p.length;m>j;++j){var q=p[j];if(l=String(q.getAttribute("data-hook")),l.length||(l=q.getAttribute("id")),o&&q.removeAttribute("data-hook"),l=b(l),n.hasOwnProperty(l))throw new Error("Hook '"+l+"' is already defined");n[l]=q}}}if(h)for(i=0,k=h.length;k>i;++i){var r=String(h[i]).split(".");if(1==r.length)c(n,r[0]);else for(j=r.length-1;j>0;--j)d(n,r[j-1],r[j])}if("function"==typeof f.wrapper){var s=f.wrapper;for(l in n)n.hasOwnProperty(l)&&(n[l]=s(n[l]))}return n}}(),a.fsize=function(a,b,c){var d=0,e=1024,f=["","K","M","G","T","P","E","Z","Y"],g=f.length-1;for(b=isFinite(b)?Math.max(0,Math.floor(b)):2,c="string"==typeof c?c:" ";a>=e&&(a/=e,d!=g);)++d;var h=Math.pow(10,b),i=Math.round(h*a)/h;return i+c+f[d]+"B"},a.attr=function(b,c){return" "+a.esc(b)+'="'+a.esc(c)+'"'},a.attrs=function(b){var c="",d=a.attr;for(var e in b)c+=d(e,b[e]);return c},a});