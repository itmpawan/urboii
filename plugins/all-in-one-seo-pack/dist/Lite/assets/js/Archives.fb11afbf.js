import{c as f,u as b,f as v}from"./links.50b3c915.js";import{A,T as y}from"./TitleDescription.ede4efc1.js";import{C as S}from"./Card.f9c412a3.js";import{C}from"./Tabs.de5972ab.js";import{r as i,c,F as D,h as T,o,d as p,w as r,a as l,n as x,t as $,e as m,C as k,j as w}from"./vue.runtime.esm-bundler.3acceac0.js";import{_ as B}from"./_plugin-vue_export-helper.109ab23d.js";import"./default-i18n.41786823.js";import"./isArrayLikeObject.71906cce.js";import"./index.333853dc.js";import"./Caret.918abbf1.js";/* empty css                                            *//* empty css                                              */import"./constants.008ef172.js";import"./JsonValues.870a4901.js";import"./MaxCounts.12b45bab.js";/* empty css                                              */import"./RadioToggle.22865edf.js";import"./ProBadge.2060c7e5.js";import"./RobotsMeta.cd0d0e5a.js";import"./Checkbox.56c563fd.js";import"./Checkmark.9bcd12eb.js";import"./Row.3c0caea3.js";import"./SettingsRow.cba2123c.js";import"./tags.85349473.js";import"./Tags.9c6c987d.js";import"./postContent.8401570a.js";import"./cleanForSlug.ef36194e.js";import"./toString.3425ebfb.js";import"./_baseTrim.8725856f.js";import"./_stringToArray.4de3b1f3.js";import"./html.fc130714.js";import"./get.03b63251.js";import"./GoogleSearchPreview.24057bd7.js";import"./HtmlTagsEditor.f0dc249e.js";import"./Editor.0db96cbe.js";import"./UnfilteredHtml.0e1af5ef.js";import"./Tooltip.38bcb67e.js";import"./Slide.0a204345.js";import"./TruSeoScore.177d3103.js";import"./SaveChanges.7ff5a9ed.js";import"./Information.19739ce3.js";const O={setup(){return{optionsStore:f(),rootStore:b(),settingsStore:v()}},components:{Advanced:A,CoreCard:S,CoreMainTabs:C,TitleDescription:y},data(){return{internalDebounce:null,tabs:[{slug:"title-description",name:this.$t.__("Title & Description",this.$td),access:"aioseo_search_appearance_settings",pro:!1},{slug:"advanced",name:this.$t.__("Advanced",this.$td),access:"aioseo_search_appearance_settings",pro:!1}],archives:[{label:"Author Archives",name:"author",singular:"Author",icon:"dashicons-admin-users"},{label:"Date Archives",name:"date",singular:"Date",icon:"dashicons-calendar-alt"},{label:"Search Page",name:"search",singular:"Search Page",icon:"dashicons-search"}]}},computed:{getArchives(){return this.archives.concat(this.rootStore.aioseo.postData.archives.map(e=>({label:`${e.label} Archives`,name:`${e.name}Archive`,icon:"dashicons-category",singular:e.singular,dynamic:!0})))}},methods:{processChangeTab(e,n){this.internalDebounce||(this.internalDebounce=!0,this.settingsStore.changeTab({slug:`${e}Archives`,value:n}),setTimeout(()=>{this.internalDebounce=!1},50))},getOptions(e){return e.dynamic?this.optionsStore.dynamicOptions.searchAppearance.archives[e.name.replace("Archive","")]:this.optionsStore.options.searchAppearance.archives[e.name]}}},j={class:"aioseo-search-appearance-archives"};function L(e,n,P,s,u,a){const h=i("core-main-tabs"),d=i("core-card");return o(),c("div",j,[(o(!0),c(D,null,T(a.getArchives,(t,_)=>(o(),p(d,{key:_,slug:`${t.name}Archives`},{header:r(()=>[l("div",{class:x(["icon dashicons",`${t.icon||"dashicons-admin-post"}`])},null,2),l("div",null,$(t.label),1)]),tabs:r(()=>[m(h,{tabs:u.tabs,showSaveButton:!1,active:s.settingsStore.settings.internalTabs[`${t.name}Archives`],internal:"",onChanged:g=>a.processChangeTab(t.name,g)},null,8,["tabs","active","onChanged"])]),default:r(()=>[m(w,{name:"route-fade",mode:"out-in"},{default:r(()=>[(o(),p(k(s.settingsStore.settings.internalTabs[`${t.name}Archives`]),{object:t,separator:s.optionsStore.options.searchAppearance.global.separator,options:a.getOptions(t),type:"archives","show-bulk":!1,"no-meta-box":"","include-keywords":""},null,8,["object","separator","options"]))]),_:2},1024)]),_:2},1032,["slug"]))),128))])}const Se=B(O,[["render",L]]);export{Se as default};
