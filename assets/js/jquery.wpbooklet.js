/*
 * jQuery Booklet Plugin
 * Copyright (c) 2010 - 2014 William Grauvogel (http://builtbywill.com/)
 *
 * Licensed under the MIT license (http://www.opensource.org/licenses/mit-license.php)
 *
 * Version : 1.4.4
 *
 * Originally based on the work of:
 *	1) Charles Mangin (http://clickheredammit.com/pageflip/)
 *
 * Last modified by BinaryStash 2015-06-02 
 */
;(function(a){a.fn.wpbooklet=function(k,f,d){var i,c,h,g,l,e,j;if(typeof k==="string"){l=[];a(this).each(function(){i=a(this).data("booklet");if(i){c=k;h=[];if(typeof f!=="undefined"){h.push(f)}if(typeof d!=="undefined"){h.push(d)}if(i[c]){g=i[c].apply(i,h);if(typeof g!=="undefined"||g){l.push(i[c].apply(i,h))}}else{a.error('Method "'+c+'" does not exist on jQuery.booklet.')}}else{a.error('jQuery.booklet has not been initialized. Method "'+k+'" cannot be called.')}});if(l.length==1){return l[0]}else{if(l.length>0){return l}else{return a(this)}}}else{if(typeof k==="number"){return a(this).each(function(){i=a(this).data("booklet");if(i){j=k;i.gotopage(j)}else{a.error("jQuery.booklet has not been initialized.")}})}else{if(typeof c==="object"||!c){return a(this).each(function(){e=a.extend({},a.fn.wpbooklet.defaults,k);i=a(this).data("booklet");if(i){i.destroy()}i=new b(a(this),e);i.init();return this})}}}};function b(bg,a7){var aH=bg,aI=a7,au=false,aj=false,aS=false,f=false,aR=false,Q=false,o={empty:'<div class="b-page-empty" title=""></div>',blank:'<div class="b-page-blank" title=""></div>',sF:'<div class="b-shadow-f"></div>',sB:'<div class="b-shadow-b"></div>'},aO={leftToRight:"LTR",rightToLeft:"RTL"},aN={},am={},e,a1,C,u=[],an="",az="/page/",al,ag,ae,ai,at,z,ax,t,V,aG,aF,aD,aB,ay,bd,aP,af,D,k,bb,a2,bc,be,aL,s,aU,B,a0,aZ,aq,aX,aV,x,U,T,P,O,m,l,w,aE,p,a4,Y,S,n,d,aT,aC,aK,c,a8,X,ad,ap,aw,I,K,ar,av,y,aY,E={create:"bookletcreate",start:"bookletstart",change:"bookletchange",add:"bookletadd",remove:"bookletremove"},L,a5=function(h,bi,bh){var j="",bj="",i;if(h.attr("rel")){j=h.attr("rel")}if(h.attr("title")){bj=h.attr("title")}if(h.hasClass("b-page-empty")){h.wrap('<div class="b-page"><div class="b-wrap"></div></div>')}else{if(bh.closed&&bh.covers&&(bi==1||bi==bh.pageTotal-2)){h.wrap('<div class="b-page"><div class="b-wrap b-page-cover"></div></div>')}else{if(bi%2!=0){h.wrap('<div class="b-page"><div class="b-wrap b-wrap-right"></div></div>')}else{h.wrap('<div class="b-page"><div class="b-wrap b-wrap-left"></div></div>')}}}i=h.parents(".b-page").addClass("b-page-"+bi);if(bh.pageNumbers&&!h.hasClass("b-page-empty")&&(!bh.closed||(bh.closed&&!bh.covers)||(bh.closed&&bh.covers&&bi!=1&&bi!=bh.pageTotal-2))){if(bh.direction==aO.leftToRight){t++}h.parent().append('<div class="b-counter">'+t+"</div>");if(bh.direction==aO.rightToLeft){t--}}return{index:bi,contentNode:h[0],pageNode:i[0],chapter:j,title:bj}},aQ=function(){aH.find(".b-load").children().unwrap();aH.addClass("booklet");aH.data("booklet",this);ax=aH.children().length;aI.currentIndex=0;r();a6();ba();R();v();au=true;Q=false;L={options:a.extend({},aI),index:aI.currentIndex,pages:[u[aI.currentIndex].contentNode,u[aI.currentIndex+1].contentNode]};if(aI.create){aH.off(E.create+".booklet").on(E.create+".booklet",aI.create)}aH.trigger(E.create,L)},W=function(){Q=false},H=function(){Q=true},g=function(){bf();ak();aH.removeClass("booklet");aH.removeData("booklet");au=false},r=function(){u=[];if((aH.children().length%2)!=0){if(aI.closed&&aI.covers){aH.children().last().before(o.blank)}else{aH.children().last().after(o.blank)}}if(aI.closed){a(o.empty).attr({title:aI.closedFrontTitle||"",rel:aI.closedFrontChapter||""}).prependTo(aH);aH.children().last().attr({title:aI.closedBackTitle||"",rel:aI.closedBackChapter||""});aH.append(o.empty)}aI.pageTotal=aH.children().length;t=0;if(aI.direction==aO.rightToLeft){t=aI.pageTotal;if(aI.closed){t-=2}if(aI.covers){t-=2}a(aH.children().get().reverse()).each(function(){a(this).appendTo(aH)})}if(!au){if(aI.direction==aO.leftToRight){aI.currentIndex=0}else{if(aI.direction==aO.rightToLeft){aI.currentIndex=aI.pageTotal-2}}if(!isNaN(aI.startingPage)&&aI.startingPage<=aI.pageTotal&&aI.startingPage>0){if((aI.startingPage%2)!=0){aI.startingPage--}aI.currentIndex=aI.startingPage}}aH.children().each(function(h){var j=new a5(a(this),h,aI);u.push(j)})},ba=function(){Z();aJ();N()},Z=function(){aH.find(".b-page").removeClass("b-pN b-p0 b-p1 b-p2 b-p3 b-p4").hide();if(aI.currentIndex-2>=0){aH.find(".b-page-"+(aI.currentIndex-2)).addClass("b-pN").show();aH.find(".b-page-"+(aI.currentIndex-1)).addClass("b-p0").show()}aH.find(".b-page-"+(aI.currentIndex)).addClass("b-p1").show();aH.find(".b-page-"+(aI.currentIndex+1)).addClass("b-p2").show();if(aI.currentIndex+3<=aI.pageTotal){aH.find(".b-page-"+(aI.currentIndex+2)).addClass("b-p3").show();aH.find(".b-page-"+(aI.currentIndex+3)).addClass("b-p4").show()}V=aH.find(".b-pN");aG=aH.find(".b-p0");aF=aH.find(".b-p1");aD=aH.find(".b-p2");aB=aH.find(".b-p3");ay=aH.find(".b-p4");bd=aH.find(".b-pN .b-wrap");aP=aH.find(".b-p0 .b-wrap");af=aH.find(".b-p1 .b-wrap");D=aH.find(".b-p2 .b-wrap");k=aH.find(".b-p3 .b-wrap");bb=aH.find(".b-p4 .b-wrap");a2=aH.find(".b-wrap");if(aI.shadows){aH.find(".b-shadow-f, .b-shadow-b").remove();bc=a(o.sF).css(aN.sF).appendTo(aB);be=a(o.sB).appendTo(aG).css(aN.sB)}},aJ=function(){aH.find(".b-shadow-f, .b-shadow-b, .b-p0, .b-p3").css({filter:"",zoom:""});aH.find(".b-page").removeAttr("style");a2.removeAttr("style");a2.css(aN.wrap);aP.css(aN.p0wrap);aF.css(aN.p1);aD.css(aN.p2);if(aI.closed&&aI.autoCenter&&aI.currentIndex>=aI.pageTotal-2){aD.hide()}V.css(aN.pN);aG.css(aN.p0);aB.stop().css(aN.p3);ay.css(aN.p4);if(aI.closed&&aI.autoCenter&&aI.currentIndex==0){V.css({left:0});aF.css({left:ar});aD.css({left:0});aB.css({left:K});ay.css({left:0})}if(aI.closed&&aI.autoCenter&&(aI.currentIndex==0||aI.currentIndex>=aI.pageTotal-2)){if(aI.overlays){B.width("100%")}aH.width(K)}else{if(aI.overlays){B.width("50%")}aH.width(aI.width)}aH.find(".b-page").css({filter:"",zoom:""})},ak=function(){aH.find(".b-wrap").unwrap();aH.find(".b-wrap").children().unwrap();aH.find(".b-counter, .b-page-blank, .b-page-empty, .b-shadow-f, .b-shadow-b").remove();if(aI.direction==aO.rightToLeft){a(aH.children().get().reverse()).each(function(){a(this).appendTo(aH)})}},a6=function(i){var h=false;if(i!=null&&typeof i!="undefined"){ak();bf();aI=a.extend({},aI,i);h=true;r()}if(!aI.width){aI.width=aH.width()}else{if(typeof aI.width=="string"&&aI.width.indexOf("px")!=-1){aI.width=aI.width.replace("px","")}else{if(typeof aI.width=="string"&&aI.width.indexOf("%")!=-1){ad=true;ap=aI.width;aI.width=(aI.width.replace("%","")/100)*parseFloat(aH.parent().css("width"))}}}if(!aI.height){aI.height=aH.height()}else{if(typeof aI.height=="string"&&aI.height.indexOf("px")!=-1){aI.height=aI.height.replace("px","")}else{if(typeof aI.height=="string"&&aI.height.indexOf("%")!=-1){aw=true;I=aI.height;aI.height=(aI.height.replace("%","")/100)*parseFloat(aH.parent().css("height"))}}}aH.width(aI.width);aH.height(aI.height);K=aI.width/2;ar="-"+(K)+"px";av=K/2;y=aI.height;aY=aI.speed/2;if(aI.closed&&aI.autoCenter){if(aI.currentIndex==0){aH.width(K)}else{if(aI.currentIndex>=aI.pageTotal-2){aH.width(K)}}}if(aI.shadows){aI.shadowTopFwdWidth="-"+aI.shadowTopFwdWidth+"px";aI.shadowTopBackWidth="-"+aI.shadowTopBackWidth+"px"}aI.pageTotal=aH.children(".b-page").length;if(aI.name){document.title=aI.name}else{aI.name=document.title}ac();if(au){ba()}if(aI.menu&&a(aI.menu).length>0){w=a(aI.menu);if(!w.hasClass("b-menu")){w.addClass("b-menu")}if(aI.pageSelector&&w.find(".b-selector-page").length==0){a4=a('<div class="b-selector b-selector-page"><span class="b-current">'+(aI.currentIndex+1)+" - "+(aI.currentIndex+2)+"</span></div>").appendTo(w);Y=a("<ul></ul>").appendTo(a4).empty().css("height","auto");for(ag=0;ag<aI.pageTotal;ag+=2){ae=ag;S=(ae+1)+"-"+(ae+2);if(aI.closed){ae--;if(ag==0){S="1"}else{if(ag==aI.pageTotal-2){S=aI.pageTotal-2}else{S=(ae+1)+"-"+(ae+2)}}if(aI.covers){ae--;if(ag==0){S=""}else{if(ag==aI.pageTotal-2){S=""}else{S=(ae+1)+"-"+(ae+2)}}}}if(ag==0){a4.find(".b-current").text(S)}n=u[ag].title;if(n==""){n=u[ag+1].title}if(aI.direction==aO.rightToLeft){n=u[Math.abs(ag-aI.pageTotal)-1].title;if(n==""){n=u[Math.abs(ag-aI.pageTotal)-2].title}}d=a('<li><a href="#'+az+(ag+1)+'" id="selector-page-'+ag+'"><span class="b-text">'+n+'</span><span class="b-num">'+S+"</span></a></li>").appendTo(Y);if(!aI.hash){d.find("a").on("click.booklet",function(j){j.preventDefault();if(aj||Q){return}if(aI.direction==aO.rightToLeft){a4.find(".b-current").text(a(this).find(".b-num").text());F(Math.abs(parseInt(a(this).attr("id").replace("selector-page-",""))-aI.pageTotal)-2)}else{F(parseInt(a(this).attr("id").replace("selector-page-","")))}})}}aT=Y.height();Y.css({height:0,"padding-bottom":0});a4.on("mouseenter.booklet",function(){Y.stop().animate({height:aT,paddingBottom:10},500)}).on("mouseleave.booklet",function(){Y.stop().animate({height:0,paddingBottom:0},500)})}else{if(!aI.pageSelector){w.find(".b-selector-page").remove();a4=Y=S=n=d=aT=null}}if(aI.chapterSelector&&w.find(".b-selector-chapter").length==0){aC=u[aI.currentIndex].chapter;if(aC==""){aC=u[aI.currentIndex+1].chapter}aK=a('<div class="b-selector b-selector-chapter"><span class="b-current">'+aC+"</span></div>").appendTo(w);c=a("<ul></ul>").appendTo(aK).empty().css("height","auto");for(ag=0;ag<aI.pageTotal;ag+=1){if(u[ag].chapter!=""&&typeof u[ag].chapter!="undefined"){if(aI.direction==aO.rightToLeft){ae=ag;if(ae%2!=0){ae--}aK.find(".b-current").text(u[ag].chapter);a8=a('<li><a href="#'+az+(ae+1)+'" id="selector-page-'+(ae)+'"><span class="b-text">'+u[ag].chapter+"</span></a></li>").prependTo(c)}else{a8=a('<li><a href="#'+az+(ag+1)+'" id="selector-page-'+ag+'"><span class="b-text">'+u[ag].chapter+"</span></a></li>").appendTo(c)}if(!aI.hash){a8.find("a").on("click.booklet",function(bh){bh.preventDefault();var j;if(aj||Q){return}if(aI.direction==aO.rightToLeft){aK.find(".b-current").text(a(this).find(".b-text").text());j=Math.abs(parseInt(a(this).attr("id").replace("selector-page-",""))-aI.pageTotal)-2}else{j=parseInt(a(this).attr("id").replace("selector-page-",""))}if(j%2!=0){j-=1}F(j)})}}}X=c.height();c.css({height:0,"padding-bottom":0});aK.on("mouseenter.booklet",function(){c.stop().animate({height:X,paddingBottom:10},500)}).on("mouseleave.booklet",function(){c.stop().animate({height:0,paddingBottom:0},500)})}else{if(!aI.chapterSelector){w.find(".b-selector-chapter").remove();aC=aK=c=a8=X=null}}}else{w=null;if(aI.menu){a(aI.menu).removeClass("b-menu")}aH.find(".b-selector").remove()}aU=aH.find(".b-controls");if(aU.length==0){aU=a('<div class="b-controls"></div>').appendTo(aH)}if(aI.manual&&a.ui){aI.overlays=false}if(P){P.off("click.booklet");P=null}if(O){O.off("click.booklet");O=null}B=aH.find(".b-overlay");if(aI.overlays&&B.length==0){aZ=a('<div class="b-overlay b-overlay-prev b-prev" title="'+aI.previousControlTitle+'"></div>').appendTo(aU);a0=a('<div class="b-overlay b-overlay-next b-next" title="'+aI.nextControlTitle+'"></div>').appendTo(aU);B=aH.find(".b-overlay")}else{if(!aI.overlays){B.remove();B=null}}aq=aH.find(".b-tab");if(aI.tabs&&aq.length==0){aV=a('<div class="b-tab b-tab-prev b-prev" title="'+aI.previousControlTitle+'">'+aI.previousControlText+"</div>").appendTo(aU);aX=a('<div class="b-tab b-tab-next b-next" title="'+aI.nextControlTitle+'">'+aI.nextControlText+"</div>").appendTo(aU);aq=aH.find(".b-tab")}else{if(!aI.tabs){aH.css({marginTop:0});aq.remove();aq=null}}if(aI.tabs&&aq.length>0){if(aI.tabWidth){aq.width(aI.tabWidth)}if(aI.tabHeight){aq.height(aI.tabHeight)}aq.css({top:"-"+aX.outerHeight()+"px"});aH.css({marginTop:aX.outerHeight()});if(aI.direction==aO.rightToLeft){aX.html(aI.previousControlText).attr("title",aI.previousControlTitle);aV.html(aI.nextControlText).attr("title",aI.nextControlTitle)}}x=aH.find(".b-arrow");if(aI.arrows&&x.length==0){T=a('<div class="b-arrow b-arrow-prev b-prev" title="'+aI.previousControlTitle+'"><div>'+aI.previousControlText+"</div></div>").appendTo(aU);U=a('<div class="b-arrow b-arrow-next b-next" title="'+aI.nextControlTitle+'"><div>'+aI.nextControlText+"</div></div>").appendTo(aU);x=aH.find(".b-arrow");if(aI.direction==aO.rightToLeft){U.html("<div>"+aI.previousControlText+"</div>").attr("title",aI.previousControlTitle);T.html("<div>"+aI.nextControlText+"</div>").attr("title",aI.nextControlTitle)}}else{if(!aI.arrows){x.remove();x=null}}m=aU.find(".b-next");l=aU.find(".b-prev");m.off(".booklet");l.off(".booklet");m.on("click.booklet",function(j){j.preventDefault();aM()});l.on("click.booklet",function(j){j.preventDefault();M()});if(aI.next&&a(aI.next).length>0){P=a(aI.next);P.on("click.booklet",function(j){j.preventDefault();aM()})}if(aI.prev&&a(aI.prev).length>0){O=a(aI.prev);O.on("click.booklet",function(j){j.preventDefault();M()})}if(aI.overlays&&aI.hovers){m.on("mouseover.booklet",function(){aW(true)}).on("mouseout.booklet",function(){ab(true)});l.on("mouseover.booklet",function(){aW(false)}).on("mouseout.booklet",function(){ab(false)})}if(aI.arrows){if(aI.arrowsHide){if(a.support.opacity){m.on("mouseover.booklet",function(){U.find("div").stop().fadeTo("fast",1)}).on("mouseout.booklet",function(){U.find("div").stop().fadeTo("fast",0)});l.on("mouseover.booklet",function(){T.find("div").stop().fadeTo("fast",1)}).on("mouseout.booklet",function(){T.find("div").stop().fadeTo("fast",0)})}else{m.on("mouseover.booklet",function(){U.find("div").show()}).on("mouseout.booklet",function(){U.find("div").hide()});l.on("mouseover.booklet",function(){T.find("div").show()}).on("mouseout.booklet",function(){T.find("div").hide()})}}else{U.find("div").show();T.find("div").show()}}a(document).on("keyup.booklet",function(j){if(j.keyCode==37&&aI.keyboard){M()}else{if(j.keyCode==39&&aI.keyboard){aM()}}});clearInterval(ai);ai=null;if(aI.hash){ao();clearInterval(ai);ai=setInterval(function(){aA()},250)}a(window).on("resize.booklet",function(){if((ad||aw)){a3()}});if(aI.auto&&aI.delay){clearInterval(at);at=setInterval(function(){if(aI.direction==aO.leftToRight){aM()}else{M()}},aI.delay);aS=true;if(aI.pause&&a(aI.pause).length>0){aE=a(aI.pause);aE.off("click.booklet").on("click.booklet",function(j){j.preventDefault();if(aS){clearInterval(at);aS=false}})}if(aI.play&&a(aI.play).length>0){p=a(aI.play);p.off("click.booklet").on("click.booklet",function(j){j.preventDefault();if(!aS){clearInterval(at);at=setInterval(function(){if(aI.direction==aO.leftToRight){aM()}else{M()}},aI.delay);aS=true}})}}else{clearInterval(at);at=null;if(aI.pause&&a(aI.pause).length>0){a(aI.pause).off("click.booklet")}aE=null;if(aI.play&&a(aI.play).length>0){a(aI.play).off("click.booklet")}p=null;aS=false}if(h){ba();R();v()}},ac=function(){aN={wrap:{left:0,width:K-(aI.pagePadding*2)-(aI.pageBorder*2),height:y-(aI.pagePadding*2)-(aI.pageBorder*2),padding:aI.pagePadding},p0wrap:{right:0,left:"auto"},p1:{left:0,width:K,height:y},p2:{left:K,width:K,opacity:1,height:y},pN:{left:0,width:K,height:y},p0:{left:0,width:0,height:y},p3:{left:K*2,width:0,height:y,paddingLeft:0},p4:{left:K,width:K,height:y},sF:{right:0,width:K,height:y},sB:{left:0,width:K,height:y}};e=10;a1=aI.hoverWidth+e;C=(aI.hoverWidth/2)+e;am={hover:{speed:aI.hoverSpeed,size:aI.hoverWidth,p2:{width:K-C},p3:{left:aI.width-a1,width:C},p3closed:{left:K-aI.hoverWidth,width:C},p3wrap:{left:e},p2end:{width:K},p2closedEnd:{width:K,left:0},p3end:{left:aI.width,width:0},p3closedEnd:{left:K,width:0},p3wrapEnd:{left:10},p1:{left:C,width:K-C},p1wrap:{left:"-"+C+"px"},p0:{left:C,width:C},p0wrap:{right:e},p1end:{left:0,width:K},p1wrapEnd:{left:0},p0end:{left:0,width:0},p0wrapEnd:{right:0}},p2:{width:0},p2closed:{width:0,left:K},p4closed:{left:K},p3in:{left:av,width:av,paddingLeft:aI.shadowBtmWidth},p3inDrag:{left:K/4,width:K*0.75,paddingLeft:aI.shadowBtmWidth},p3out:{left:0,width:K,paddingLeft:0},p3wrapIn:{left:aI.shadowBtmWidth},p3wrapOut:{left:0},p1:{left:K,width:0},p1wrap:{left:ar},p0:{left:K,width:K},p0in:{left:av,width:av},p0out:{left:K,width:K},p0outClosed:{left:0,width:K},p2back:{left:0},p0wrapDrag:{right:0},p0wrapIn:{right:aI.shadowBtmWidth},p0wrapOut:{right:0}}},a3=function(){if(!Q){if(ad){aI.width=(ap.replace("%","")/100)*parseFloat(aH.parent().css("width"));aH.width(aI.width);K=aI.width/2;ar="-"+(K)+"px";av=K/2}if(aw){aI.height=(I.replace("%","")/100)*parseFloat(aH.parent().css("height"));aH.height(aI.height);y=aI.height}ac();aJ()}},R=function(){if(aI.overlays||aI.tabs||aI.arrows){if(a.support.opacity){if(aI.currentIndex>=2&&aI.currentIndex!=0){l.fadeIn("fast").css("cursor",aI.cursor)}else{l.fadeOut("fast").css("cursor","default")}if(aI.currentIndex<aI.pageTotal-2){m.fadeIn("fast").css("cursor",aI.cursor)}else{m.fadeOut("fast").css("cursor","default")}}else{if(aI.currentIndex>=2&&aI.currentIndex!=0){l.show().css("cursor",aI.cursor)}else{l.hide().css("cursor","default")}if(aI.currentIndex<aI.pageTotal-2){m.show().css("cursor",aI.cursor)}else{m.hide().css("cursor","default")}}}},v=function(){if(aI.pageSelector){var h="";if(aI.direction==aO.rightToLeft){h=(Math.abs(aI.currentIndex-aI.pageTotal)-1)+" - "+((Math.abs(aI.currentIndex-aI.pageTotal)));if(aI.closed){if(aI.currentIndex==aI.pageTotal-2){h="1"}else{if(aI.currentIndex==0){h=aI.pageTotal-2}else{h=(Math.abs(aI.currentIndex-aI.pageTotal)-2)+" - "+((Math.abs(aI.currentIndex-aI.pageTotal)-1))}}if(aI.covers){if(aI.currentIndex==aI.pageTotal-2){h=""}else{if(aI.currentIndex==0){h=""}else{h=(Math.abs(aI.currentIndex-aI.pageTotal)-3)+" - "+((Math.abs(aI.currentIndex-aI.pageTotal)-2))}}}}}else{h=(aI.currentIndex+1)+" - "+(aI.currentIndex+2);if(aI.closed){if(aI.currentIndex==0){h="1"}else{if(aI.currentIndex==aI.pageTotal-2){h=aI.pageTotal-2}else{h=(aI.currentIndex)+"-"+(aI.currentIndex+1)}}if(aI.covers){if(aI.currentIndex==0){h=""}else{if(aI.currentIndex==aI.pageTotal-2){h=""}else{h=(aI.currentIndex-1)+"-"+(aI.currentIndex)}}}}}a(aI.menu+" .b-selector-page .b-current").text(h)}if(aI.chapterSelector){if(u[aI.currentIndex].chapter!=""){a(aI.menu+" .b-selector-chapter .b-current").text(u[aI.currentIndex].chapter)}else{if(u[aI.currentIndex+1].chapter!=""){a(aI.menu+" .b-selector-chapter .b-current").text(u[aI.currentIndex+1].chapter)}}if(aI.direction==aO.rightToLeft&&u[aI.currentIndex+1].chapter!=""){a(aI.menu+" .b-selector-chapter .b-current").text(u[aI.currentIndex+1].chapter)}else{if(u[aI.currentIndex]!=""){a(aI.menu+" .b-selector-chapter .b-current").text(u[aI.currentIndex].chapter)}}}},N=function(){var bm,h,bn,i,bi,bj,bh,bk,bl,bo,j;f=aR=aL=s=false;if(a.ui){if(aH.find(".b-page").draggable()){aH.find(".b-page").draggable("destroy").removeClass("b-grab b-grabbing")}if(aI.manual){aB.draggable({axis:"x",containment:[aH.offset().left,0,aD.offset().left+K-a1,y],drag:function(bp,bq){aL=true;aB.removeClass("b-grab").addClass("b-grabbing");bm=bq.originalPosition.left;h=bq.position.left;bn=bm-h;i=bn/bm;bi=i<0.5?i:(1-i);bj=(bi*aI.shadowBtmWidth*2)+e;bj=bn/bm>=0.5?bj-=e:bj;if(aI.shadows){bc.css({right:"-"+(aI.shadowTopFwdWidth*bi*2)+"px"});if(a.support.opacity){bc.css({opacity:bi*2})}else{bc.css({right:"auto",left:0.1*aB.width()})}}bh=C+bn/2;bh=bh>K?K:bh;bk=K-bh;if(aI.closed&&aI.autoCenter){if(aI.currentIndex==0){bl=0.5+0.5*i;bh=C+(C*i)+bn;bh=bh>K?K:bh;bk=K-bh;aD.css({left:K*i});ay.css({left:K*i});aH.width(aI.width*bl)}else{if(aI.currentIndex==aI.pageTotal-4){bl=(1-i)+0.5*i;bk=K-bh;ay.hide();aH.width(aI.width*bl)}else{aH.width(aI.width)}}}aB.width(bh);k.css({left:bj});aD.width(bk)},stop:function(){ab(false);if(i>aI.hoverThreshold){if(aI.shadows&&!a.support.opacity){bc.css({left:"auto",opacity:0})}aM();aB.removeClass("b-grab b-grabbing")}else{aL=false;aB.removeClass("b-grabbing").addClass("b-grab");bc.animate({left:"auto",opacity:0},am.hover.speed,aI.easing).css(aN.sF);if(aI.closed&&aI.autoCenter){if(aI.currentIndex==0){aD.animate({left:0},am.hover.speed,aI.easing);ay.animate({left:0},am.hover.speed,aI.easing);aH.animate({width:aI.width*0.5},am.hover.speed,aI.easing)}else{aH.animate({width:aI.width},am.hover.speed,aI.easing)}}}}});aG.draggable({axis:"x",containment:[aH.offset().left+C,0,aH.offset().left+aI.width,y],drag:function(bp,bq){s=true;aG.removeClass("b-grab").addClass("b-grabbing");bm=bq.originalPosition.left;h=bq.position.left;bn=h-bm;i=bn/(aI.width-bm);if(aI.closed&&aI.autoCenter&&aI.currentIndex==2){i=bn/(K-bm)}if(i>1){i=1}bi=i<0.5?i:(1-i);bj=(bi*aI.shadowBtmWidth*2)+e;bj=bn/bm>=0.5?bj-=e:bj;if(aI.shadows){if(a.support.opacity){be.css({opacity:bi*2})}else{be.css({left:aI.shadowTopBackWidth*bi*2})}}bh=i*(K-C)+C+bj;bo=bh-bj;j=-bo;if(aI.closed&&aI.autoCenter){if(aI.currentIndex==2){bl=(1-i)+0.5*i;bo=(1-i)*bo;j=-bo-(aI.width-(aI.width*bl));V.hide();aD.css({left:K*(1-i)});ay.css({left:K*(1-i)});aH.width(aI.width*bl)}else{if(aI.currentIndex==aI.pageTotal-2){bl=0.5+0.5*i;aH.width(aI.width*bl)}else{aH.width(aI.width)}}}bq.position.left=bo;aG.css({width:bh});aP.css({right:bj});aF.css({left:bo,width:K-bo});af.css({left:j})},stop:function(){ab(true);if(i>aI.hoverThreshold){M();aG.removeClass("b-grab b-grabbing")}else{be.animate({opacity:0},am.hover.speed,aI.easing).css(aN.sB);s=false;aG.removeClass("b-grabbing").addClass("b-grab");if(aI.closed&&aI.autoCenter){if(aI.currentIndex==2){aD.animate({left:K},am.hover.speed*2,aI.easing);ay.animate({left:K},am.hover.speed*2,aI.easing);aH.animate({width:aI.width},am.hover.speed*2,aI.easing)}else{if(aI.currentIndex==aI.pageTotal-2){aH.animate({width:aI.width*0.5},am.hover.speed,aI.easing)}}}}}});aH.find(".b-page").off("click.booklet");if(aI.hoverClick){aH.find(".b-pN, .b-p0").on("click.booklet",M).css({cursor:"pointer"});aH.find(".b-p3, .b-p4").on("click.booklet",aM).css({cursor:"pointer"})}aH.off("mousemove.booklet").on("mousemove.booklet",function(bp){bn=bp.pageX-aH.offset().left;if(bn<am.hover.size){aW(false)}else{if(bn>K-am.hover.size&&aI.currentIndex==0&&aI.autoCenter&&aI.closed){aW(true)}else{if(bn>am.hover.size&&bn<=aI.width-am.hover.size){ab(false);ab(true)}else{if(bn>aI.width-am.hover.size){aW(true)}}}}}).off("mouseleave.booklet").on("mouseleave.booklet",function(){ab(false);ab(true)})}}},ao=function(){al=J();if(!isNaN(al)&&al<=aI.pageTotal-1&&al>=0&&al!=""){if((al%2)!=0){al--}aI.currentIndex=al}else{ah(aI.currentIndex+1,aI)}an=al},aA=function(){al=J();if(!isNaN(al)&&al<=aI.pageTotal-1&&al>=0){if(al!=aI.currentIndex&&al.toString()!=an){if((al%2)!=0){al--}document.title=aI.name+aI.hashTitleText+(al+1);if(!aj){F(al);an=al}}}},J=function(){var i,h;i=window.location.hash.split("/");if(i.length>1){h=parseInt(i[2])-1;if(aI.direction==aO.rightToLeft){h=Math.abs(h+1-aI.pageTotal)}return h}else{return""}},ah=function(i,h){if(h.hash){if(h.direction==aO.rightToLeft){i=Math.abs(i-h.pageTotal)}window.location.hash=az+i}},bf=function(){if(aI.menu){a(aI.menu).removeClass("b-menu");if(aI.pageSelector){w.find(".b-selector-page").remove();a4=Y=S=n=d=aT=null}if(aI.chapterSelector){w.find(".b-selector-chapter").remove();aC=aK=c=a8=X=null}}w=null;if(P){P.off("click.booklet");P=null}if(O){O.off("click.booklet");O=null}if(m){m.off(".booklet");m=null}if(l){l.off(".booklet");l=null}aH.find(".b-selector, .b-controls").remove();clearInterval(ai);ai=null;clearInterval(at);at=null;if(aI.pause&&a(aI.pause).length>0){a(aI.pause).off("click.booklet")}aE=null;if(aI.play&&a(aI.play).length>0){a(aI.play).off("click.booklet")}p=null;q()},q=function(){if(a.ui){if(aH.find(".b-page").draggable()){aH.find(".b-page").draggable("destroy").removeClass("b-grab b-grabbing")}}aH.off(".booklet")},A=function(h,i){if(h=="start"){h=0}else{if(h=="end"){h=ax}else{if(typeof h=="number"){if(h<0||h>ax){return}}else{if(typeof h=="undefined"){return}}}}if(typeof i=="undefined"||i==""){return}ak();bf();if(aI.closed&&aI.covers&&h==ax){aH.children(":eq("+(h-1)+")").before(i)}else{if(aI.closed&&aI.covers&&h==0){aH.children(":eq("+h+")").after(i)}else{if(h==ax){aH.children(":eq("+(h-1)+")").after(i)}else{aH.children(":eq("+h+")").before(i)}}}ax=aH.children().length;L={options:a.extend({},aI),index:h,page:aH.children(":eq("+h+")")[0]};if(aI.add){aH.off(E.add+".booklet").on(E.add+".booklet",aI.add)}aH.trigger(E.add,L);r();a6();ba();R();v()},a9=function(h){if(h=="start"){h=0}else{if(h=="end"){h=ax}else{if(typeof h=="number"){if(h<0||h>ax){return}}else{if(typeof h=="undefined"){return}}}}if(aH.children(".b-page").length==2&&aH.find(".b-page-blank").length>0){return}ak();bf();if(h>=aI.currentIndex){if(h>0&&(h%2)!=0){aI.currentIndex-=2}if(aI.currentIndex<0){aI.currentIndex=0}}var i;if(aI.closed&&aI.covers&&h==ax){i=aH.children(":eq("+(h-1)+")").remove()}else{if(aI.closed&&aI.covers&&h==0){i=aH.children(":eq("+h+")").remove()}else{if(h==ax){i=aH.children(":eq("+(h-1)+")").remove()}else{i=aH.children(":eq("+h+")").remove()}}}ax=aH.children().length;L={options:a.extend({},aI),index:h,page:i[0]};if(aI.remove){aH.off(E.remove+".booklet").on(E.remove+".booklet",aI.remove)}aH.trigger(E.remove,L);i=null;r();ba();a6();R();v()},aM=function(){if(!aj&&!Q){if(aS&&aI.currentIndex+2>=aI.pageTotal){F(0)}else{F(aI.currentIndex+2)}}},M=function(){if(!aj&&!Q){if(aS&&aI.currentIndex-2<0){F(aI.pageTotal-2)}else{F(aI.currentIndex-2)}}},F=function(h){var i;if(h<aI.pageTotal&&h>=0&&!aj&&!Q){if(h>aI.currentIndex){aj=true;z=h-aI.currentIndex;aI.currentIndex=h;aI.movingForward=true;L={options:a.extend({},aI),index:h,pages:[u[h].contentNode,u[h+1].contentNode]};if(aI.start){aH.off(E.start+".booklet").on(E.start+".booklet",aI.start)}aH.trigger(E.start,L);v();if(h==aI.pageTotal-2){R()}ah(aI.currentIndex+1,aI);i=aL===true?aI.speed*(aB.width()/K):aY;G(z,true,bc,i);if(aI.closed&&aI.autoCenter&&h-z==0){aD.stop().animate(am.p2closed,aL===true?i:i*2,aI.easing);ay.stop().animate(am.p4closed,aL===true?i:i*2,aI.easing)}else{aD.stop().animate(am.p2,i,aL===true?aI.easeOut:aI.easeIn)}if(aL){aB.animate(am.p3out,i,aI.easeOut);k.animate(am.p3wrapOut,i,aI.easeOut,function(){aa()})}else{aB.stop().animate(am.p3in,i,aI.easeIn).animate(am.p3out,i,aI.easeOut);k.animate(am.p3wrapIn,i,aI.easeIn).animate(am.p3wrapOut,i,aI.easeOut,function(){aa()})}}else{if(h<aI.currentIndex){aj=true;z=aI.currentIndex-h;aI.currentIndex=h;aI.movingForward=false;L={options:a.extend({},aI),index:h,pages:[u[h].contentNode,u[h+1].contentNode]};if(aI.start){aH.off(E.start+".booklet").on(E.start+".booklet",aI.start)}aH.trigger(E.start,L);v();if(h==0){R()}ah(aI.currentIndex+1,aI);i=s===true?aI.speed*(aG.width()/K):aY;G(z,false,be,i);if(s){aF.animate(am.p1,i,aI.easeOut);af.animate(am.p1wrap,i,aI.easeOut);if(aI.closed&&aI.autoCenter&&aI.currentIndex==0){aG.animate(am.p0outClosed,i,aI.easeOut);aD.stop().animate(am.p2back,i,aI.easeOut)}else{aG.animate(am.p0,i,aI.easeOut)}aP.animate(am.p0wrapDrag,i,aI.easeOut,function(){aa()})}else{aF.animate(am.p1,i*2,aI.easing);af.animate(am.p1wrap,i*2,aI.easing);if(aI.closed&&aI.autoCenter&&aI.currentIndex==0){aG.animate(am.p0in,i,aI.easeIn).animate(am.p0outClosed,i,aI.easeOut);aD.stop().animate(am.p2back,i*2,aI.easing)}else{aG.animate(am.p0in,i,aI.easeIn).animate(am.p0out,i,aI.easeOut)}aP.animate(am.p0wrapIn,i,aI.easeIn).animate(am.p0wrapOut,i,aI.easeOut,function(){aa()})}}}}},aW=function(h){if(!Q&&((aI.hovers&&aI.overlays)||aI.manual)){if(h){if(!aj&&!f&&!aR&&!aL&&aI.currentIndex+2<=aI.pageTotal-2){aD.stop().animate(am.hover.p2,am.hover.speed,aI.easing);aB.addClass("b-grab");if(aI.closed&&aI.autoCenter&&aI.currentIndex==0){aB.stop().animate(am.hover.p3closed,am.hover.speed,aI.easing)}else{aB.stop().animate(am.hover.p3,am.hover.speed,aI.easing)}k.stop().animate(am.hover.p3wrap,am.hover.speed,aI.easing);if(aI.shadows&&!a.support.opacity){bc.css({right:"auto",left:"-40%"})}f=true}}else{if(!aj&&!aR&&!f&&!s&&aI.currentIndex-2>=0){aF.stop().animate(am.hover.p1,am.hover.speed,aI.easing);aG.addClass("b-grab");af.stop().animate(am.hover.p1wrap,am.hover.speed,aI.easing);aG.stop().animate(am.hover.p0,am.hover.speed,aI.easing);aP.stop().animate(am.hover.p0wrap,am.hover.speed,aI.easing);if(aI.shadows&&!a.support.opacity){be.css({left:-0.38*K})}aR=true}}}},ab=function(h){if(!Q&&((aI.hovers&&aI.overlays)||aI.manual)){if(h){if(!aj&&f&&!aL&&aI.currentIndex+2<=aI.pageTotal-2){if(aI.closed&&aI.autoCenter&&aI.currentIndex==0){aD.stop().animate(am.hover.p2closedEnd,am.hover.speed,aI.easing);aB.stop().animate(am.hover.p3closedEnd,am.hover.speed,aI.easing)}else{aD.stop().animate(am.hover.p2end,am.hover.speed,aI.easing);aB.stop().animate(am.hover.p3end,am.hover.speed,aI.easing)}k.stop().animate(am.hover.p3wrapEnd,am.hover.speed,aI.easing);if(aI.shadows&&!a.support.opacity){bc.css({left:"auto"})}f=false}}else{if(!aj&&aR&&!s&&aI.currentIndex-2>=0){aF.stop().animate(am.hover.p1end,am.hover.speed,aI.easing);af.stop().animate(am.hover.p1wrapEnd,am.hover.speed,aI.easing);aG.stop().animate(am.hover.p0end,am.hover.speed,aI.easing);aP.stop().animate(am.hover.p0wrapEnd,am.hover.speed,aI.easing);aR=false}}}},G=function(j,i,bh,h){if(i&&j>2){aH.find(".b-p3, .b-p4").removeClass("b-p3 b-p4").hide();aH.find(".b-page-"+aI.currentIndex).addClass("b-p3").show().stop().css(aN.p3);aH.find(".b-page-"+(aI.currentIndex+1)).addClass("b-p4").show().css(aN.p4);aH.find(".b-page-"+aI.currentIndex+" .b-wrap").show().css(aN.wrap);aH.find(".b-page-"+(aI.currentIndex+1)+" .b-wrap").show().css(aN.wrap);aB=aH.find(".b-p3");ay=aH.find(".b-p4");k=aH.find(".b-p3 .b-wrap");bb=aH.find(".b-p4 .b-wrap");if(aI.closed&&aI.autoCenter&&aI.currentIndex-j==0){aB.css({left:K});ay.css({left:0})}if(f){aB.css({left:aI.width-40,width:20,"padding-left":10})}if(aI.shadows){aH.find(".b-shadow-f").remove();bc=a(o.sF).css(aN.sF).appendTo(aB);bh=bc}}else{if(!i&&j>2){aH.find(".b-pN, .b-p0").removeClass("b-pN b-p0").hide();aH.find(".b-page-"+aI.currentIndex).addClass("b-pN").show().css(aN.pN);aH.find(".b-page-"+(aI.currentIndex+1)).addClass("b-p0").show().css(aN.p0);aH.find(".b-page-"+aI.currentIndex+" .b-wrap").show().css(aN.wrap);aH.find(".b-page-"+(aI.currentIndex+1)+" .b-wrap").show().css(aN.wrap);V=aH.find(".b-pN");aG=aH.find(".b-p0");bd=aH.find(".b-pN .b-wrap");aP=aH.find(".b-p0 .b-wrap");if(aI.closed&&aI.autoCenter){V.css({left:0})}aP.css(aN.p0wrap);if(aR){aG.css({left:10,width:40});aP.css({right:10})}if(aI.shadows){aH.find(".b-shadow-b, .b-shadow-f").remove();be=a(o.sB).appendTo(aG).css(aN.sB);bh=be}}}if(aI.closed){if(!i&&aI.currentIndex==0){V.hide()}else{if(!i){V.show()}}if(i&&aI.currentIndex>=aI.pageTotal-2){ay.hide()}else{if(i){ay.show()}}}if(aI.shadows){if(a.support.opacity){if(!aL&&!s){bh.animate({opacity:1},h,aI.easeIn)}bh.animate({opacity:0},h,aI.easeOut)}else{if(i){bh.animate({right:aI.shadowTopFwdWidth},h*2,aI.easeIn)}else{bh.animate({left:aI.shadowTopBackWidth},h*2,aI.easeIn)}}}if(aI.closed&&aI.autoCenter){if(aI.currentIndex==0){aB.hide();ay.hide();aH.animate({width:K},!aL&&!s?h*2:h,aI.easing)}else{if(aI.currentIndex>=aI.pageTotal-2){aG.hide();V.hide();aH.animate({width:K},h*2,aI.easing)}else{aH.animate({width:aI.width},h*2,aI.easing)}}}},aa=function(){ba();v();R();aj=false;L={options:a.extend({},aI),index:aI.currentIndex,pages:[u[aI.currentIndex].contentNode,u[aI.currentIndex+1].contentNode]};if(aI.change){aH.off(E.change+".booklet").on(E.change+".booklet",aI.change)}aH.trigger(E.change,L)};return{init:aQ,enable:W,disable:H,destroy:g,next:aM,prev:M,gotopage:function(h){if(typeof h==="string"){if(h=="start"){h=0}else{if(h=="end"){h=aI.pageTotal-2}else{this.gotopage(parseInt(h))}}}else{if(typeof h==="number"){if(h<0||h>=aI.pageTotal){return}}else{if(typeof h==="undefined"){return}}}if(h%2!=0){h-=1}if(aI.direction==aO.rightToLeft){h=Math.abs(h-aI.pageTotal)-2}F(h)},add:A,remove:a9,option:function(h,i){if(typeof h==="string"){if(typeof aI[h]!=="undefined"){if(typeof i!=="undefined"){aI[h]=i;a6()}else{return aI[h]}}else{a.error('Option "'+h+'" does not exist on jQuery.booklet.')}}else{if(typeof h==="object"){a6(h)}else{if(typeof h==="undefined"||!h){return a.extend({},aI)}}}}}}a.fn.wpbooklet.defaults={name:null,width:600,height:400,speed:1000,direction:"LTR",startingPage:0,easing:"easeInOutQuad",easeIn:"easeInQuad",easeOut:"easeOutQuad",closed:false,closedFrontTitle:"Beginning",closedFrontChapter:"Beginning of Book",closedBackTitle:"End",closedBackChapter:"End of Book",covers:false,autoCenter:false,pagePadding:10,pageNumbers:true,pageBorder:0,manual:true,hovers:true,hoverWidth:50,hoverSpeed:500,hoverThreshold:0.25,hoverClick:true,overlays:false,tabs:false,tabWidth:60,tabHeight:20,nextControlText:"Next",previousControlText:"Previous",nextControlTitle:"Next Page",previousControlTitle:"Previous Page",arrows:false,arrowsHide:false,cursor:"pointer",hash:false,hashTitleText:" - Page ",keyboard:true,next:null,prev:null,auto:false,delay:5000,pause:null,play:null,menu:null,pageSelector:false,chapterSelector:false,shadows:true,shadowTopFwdWidth:166,shadowTopBackWidth:166,shadowBtmWidth:50,create:null,start:null,change:null,add:null,remove:null}})(jQuery);