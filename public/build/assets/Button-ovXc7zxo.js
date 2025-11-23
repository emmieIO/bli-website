import{j as r}from"./app-NaCvn22C.js";function l({children:t,variant:s="primary",icon:a,loading:e=!1,className:i="",disabled:n,...o}){const d={primary:"bg-primary hover:bg-primary-600 text-white",secondary:"bg-gray-200 hover:bg-gray-300 text-gray-700",danger:"bg-red-600 hover:bg-red-700 text-white",ghost:"border border-gray-300 hover:bg-gray-50 text-gray-700"};return r.jsxs("button",{className:`
                inline-flex items-center gap-2 font-medium px-6 py-3 rounded-lg
                transition-all duration-200 font-montserrat
                disabled:opacity-50 disabled:cursor-not-allowed
                ${d[s]}
                ${i}
            `,disabled:n||e,...o,children:[e?r.jsx("i",{className:"fas fa-spinner fa-spin w-4 h-4"}):a?r.jsx("i",{className:`fas fa-${a} w-4 h-4`}):null,t]})}export{l as B};
