/*!
* Fancyform - jQuery Plugin
* Simple and fancy form styling alternative
*
* Examples and documentation at: http://www.lutrasoft.nl/jQuery/fancyform/
*
* Copyright (c) 2010-2013 - Lutrasoft
*
* Version: 1.3.4 (26/04/2013)
* Requires: jQuery v1.6.1+
*
* Dual licensed under the MIT and GPL licenses:
*   http://www.opensource.org/licenses/mit-license.php
*   http://www.gnu.org/licenses/gpl.html
*/
(function(a){a.simpleEllipsis=function(c,b){return c.length<b?c:c.substring(0,b)+"..."};a.isTouchDevice=function(){return !!("ontouchstart" in window)};a.fn.extend({caret:function(i,b){var f=this[0],h=this.val(),d,c,g;if(f){if(typeof i=="undefined"){if(f.selectionStart){i=f.selectionStart;b=f.selectionEnd}else{if(document.selection){this.focus();d=document.selection.createRange();if(d==null){return{start:0,end:e.value.length,length:0}}c=f.createTextRange();g=c.duplicate();c.moveToBookmark(d.getBookmark());g.setEndPoint("EndToStart",c);return{start:g.text.length-(g.text.split("\n").length+1)+2,end:g.text.length+d.text.length-(g.text.split("\n").length+1)+2,length:d.text.length,text:d.text}}}}else{if(typeof i!="number"){i=-1}if(typeof b!="number"){b=-1}if(i<0){i=0}if(b>h.length){b=h.length}if(b<i){b=i}if(i>b){i=b}f.focus();if(f.selectionStart){f.selectionStart=i;f.selectionEnd=b}else{if(document.selection){d=f.createTextRange();d.collapse(true);d.moveStart("character",i);d.moveEnd("character",b-i);d.select()}}}return{start:i,end:b}}},insertAtCaret:function(b){return this.each(function(f){if(document.selection){this.focus();sel=document.selection.createRange();sel.text=b;this.focus()}else{if(this.selectionStart||this.selectionStart=="0"){var d=this.selectionStart,c=this.selectionEnd,g=this.scrollTop;this.value=this.value.substring(0,d)+b+this.value.substring(c,this.value.length);this.focus();this.selectionStart=d+b.length;this.selectionEnd=d+b.length;this.scrollTop=g}else{this.value+=b;this.focus()}}})},transformRadio:function(b){var c={checked:"",unchecked:"",disabledChecked:"",disabledUnchecked:"",trigger:"self"};var b=a.extend(c,b);var d={imageClick:function(){var g=a(this).prev().attr("name"),f=a(this).prev();if(!f.is(":disabled")){a("input[name='"+g+"']").prop("checked",false).each(function(){d.setImage.call(this)});f.prop("checked",true).change();d.setImage.call(f)}},setImage:function(){var f=a(this).data("options");if(!a(this).next().is("img")){a(this).after("<img />")}a(this).next("img").attr("src",f[a(this).is(":checked")?(a(this).is(":disabled")?"disabledChecked":"checked"):(a(this).is(":disabled")?"disabledUnchecked":"unchecked")])}};return this.each(function(){var f=a(this);if(a(this).data("transformRadio.initialized")===true){return this}a(this).data("transformRadio.initialized",true).hide().data("options",b);d.setImage.call(this);switch(b.trigger){case"parent":a(this).parent().click(function(){d.imageClick.call(f.nextAll("img:first"))});break;case"self":a(this).nextAll("img:first").click(d.imageClick);break}})},transformCheckbox:function(c){var d={checked:"",unchecked:"",disabledChecked:"",disabledUnchecked:"",tristateHalfChecked:"",changeHandler:function(g){},trigger:"self",tristate:false};var b=a.extend(d,c);var f={setImage:function(){var i=a(this),g=a(this).next(),h=a(this).data("settings"),j;if(i.is(":disabled")){j=i.is(":checked")?"disabledChecked":"disabledUnchecked"}else{if(i.hasClass("half-checked")){j="tristateHalfChecked"}else{if(i.is(":checked")){j="checked"}else{j="unchecked"}}}g.attr("src",h[j])},setProp:function(i,h,g){a(i).prop(h,g).change();f.setImage.call(i)},uncheck:function(){f.setProp(this,"checked",false)},check:function(){f.setProp(this,"checked",true)},disable:function(){f.setProp(this,"disabled",true)},enable:function(){f.setProp(this,"disabled",false)},imageClick:function(){var g=a(this).prev();if(g.is(":disabled")){return}else{if(g.is(":checked")){f.uncheck.call(g);b.changeHandler.call(g,true)}else{f.check.call(g);b.changeHandler.call(g,false)}}f.handleTriState.call(g)},handleTriState:function(){var j=a(this),i=a(this).data("settings"),g=j.parent(),h=g.find("ul");if(!i.tristate){return}if(j.hasClass("half-checked")||j.is(":checked")){j.removeClass("half-checked");f.check.call(j);h.find("input:checkbox").each(f.check)}else{if(j.not(":checked")){j.removeClass("half-checked");h.find("input:checkbox").each(f.uncheck)}}f.setImage.call(j);if(j.parent().parent().parent().is("li")){f.handleTriStateLevel.call(j.parent().parent().parent())}a(this).trigger("transformCheckbox.tristate")},handleTriStateLevel:function(){var h=a(this).find("input:checkbox").first(),i=a(this).find("ul"),g=i.find("input:checkbox"),j=g.filter(":checked");h.removeClass("half-checked");if(g.length==j.length){f.check.call(h)}else{if(j.length){h.addClass("half-checked")}else{f.uncheck.call(h)}}f.setImage.call(h);if(a(this).parent().parent().is("li")){f.handleTriStateLevel.call(a(this).parent().parent())}}};return this.each(function(){if(typeof c=="string"){f[c].call(this)}else{var g=a(this);if(a(this).data("transformCheckbox.initialized")===true){return this}a(this).data("transformCheckbox.initialized",true).data("settings",b);a(this).hide();a(this).after("<img src='' />");f.setImage.call(this);switch(b.trigger){case"parent":a(this).parent().click(function(){f.imageClick.call(g.nextAll("img:first"))});break;case"self":a(this).next("img:first").click(f.imageClick);break}}})},transformSelect:function(c){var d={dropDownClass:"transformSelect",showFirstItemInDrop:true,acceptManualInput:false,useManualInputAsFilter:false,subTemplate:function(g){if(a(this)[0].type=="select-multiple"){return"<span><input type='checkbox' value='"+a(g).val()+"' "+(a(g).is(":selected")?"checked='checked'":"")+" name='"+a(this).attr("name").replace("_backup","")+"' />"+a(g).text()+"</span>"}else{return"<span>"+a(g).text()+"</span>"}},initValue:function(){return a(this).text()},valueTemplate:function(){return a(this).text()},ellipsisLength:null,addDropdownToBody:false};var b=a(this).data("settings");var f={init:function(){a(this).hide();var g=-1,k=null,n=this;if(a(this).find("option:selected").length>0&&a(this)[0].type!="select-multiple"){k=a(this).find("option:selected");g=a(this).find("option").index(k)}else{g=0;k=a(this).find("option:first")}var i="<ul class='"+b.dropDownClass+" trans-element'><li>";if(b.acceptManualInput&&!a.isTouchDevice()){var l=a(this).data("value")?a(this).data("value"):b.initValue.call(k);i+="<ins></ins><input type='text' name='"+a(this).attr("name").replace("_backup","")+"' value='"+l+"' />";if(a(this).attr("name").indexOf("_backup")==-1){a(this).attr("name",a(this).attr("name")+"_backup")}}else{if(b.ellipsisLength){i+='<span title="'+k.text()+'">'+a.simpleEllipsis(b.initValue.call(k),b.ellipsisLength)+"</span>"}else{i+="<span>"+b.initValue.call(k)+"</span>"}}i+="<ul style='display: none;'>";a(this).children().each(function(o){if(!o&&!b.showFirstItemInDrop){}else{i+=f[a(this)[0].tagName=="OPTION"?"getLIOptionChild":"getLIOptgroupChildren"].call(n,this)}});i+="</ul></li></ul>";var h=a(i),j=h.find("ul li:not(.group)"),m=h.find("input");a(this).after(h);if(a(this).is(":disabled")){f.disabled.call(this,true)}if(a(this)[0].type=="select-multiple"&&!a.isTouchDevice()){if(a(this).attr("name")&&a(this).attr("name").indexOf("_backup")==-1){a(this).attr("name",a(this).attr("name")+"_backup")}j.click(f.selectCheckbox)}else{j.click(f.selectNewValue);m.click(f.openDrop).keydown(function(p){var o=[9,13];if(a.inArray(p.which,o)!=-1){f.closeAllDropdowns()}}).prev("ins").click(f.openDrop)}if(b.useManualInputAsFilter){m.keyup(f.filterByInput)}h.find("span").eq(0).click(f.openDrop);h.find("ul:first").data("trans-element",h).addClass("transformSelectDropdown");h.data("trans-element-drop",h.find("ul:first"));if(b.addDropdownToBody){h.find("ul:first").appendTo("body")}a("html").unbind("click.transformSelect").bind("click.transformSelect",f.closeDropDowns);if(a.hotkeys&&!a("body").data("trans-element-select")){a("body").data("trans-element-select",true);a(document).bind("keydown","up",function(r){var q=a(".trans-focused"),p,o;if(!q.length||q.find("input").length){return false}p=q.prevAll("select").first();o=p[0].selectedIndex-1;if(o<0){o=p.find("option").length-1}f.selectIndex.call(p,o);return false}).bind("keydown","down",function(r){var q=a(".trans-focused"),p,o;if(!q.length||q.find("input").length){return false}p=q.prevAll("select").first();o=p[0].selectedIndex+1;if(o>p.find("option").length-1){o=0}f.selectIndex.call(p,o);return false})}if(a.isTouchDevice()){if(!b.showFirstItemInDrop){a(this).find("option:first").remove()}a(this).appendTo(h.find("li:first")).show().css({opacity:0,position:"absolute",width:"100%",height:"100%",left:0,top:0});h.find("li:first").css({position:"relative"});a(this).change(f.mobileChange)}},getUL:function(){return a.isTouchDevice()?a(this).closest("ul"):a(this).next(".trans-element:first")},getSelect:function(g){return a.isTouchDevice()?g.find("select"):g.prevAll("select:first")},disabled:function(g){f.getUL.call(this)[g?"addClass":"removeClass"]("disabled")},repaint:function(){var g=f.getUL.call(this);g.data("trans-element-drop").remove();g.remove();f.init.call(this)},filterByInput:function(){var j=a(this).val().toLowerCase(),i=a(this).closest("ul"),h=i.data("trans-element-drop"),g=h.find("li");if(!j){g.show()}else{g.each(function(){if(!!a(this).data("settings").alwaysvisible){a(this).show()}else{a(this)[a(this).text().toLowerCase().indexOf(j)==-1?"hide":"show"]()}})}},selectIndex:function(i){var g=a(this),j=f.getUL.call(this),h=j.data("trans-element-drop");try{h.find("li").filter(function(){return a(this).text()==g.find("option").eq(i).text()}).first().trigger("click")}catch(k){}},selectValue:function(j){var g=a(this),i=f.getUL.call(this),h=i.data("trans-element-drop");f.selectIndex.call(this,g.find(j?"option[value='"+j+"']":"option:not([value])").index())},getLIOptionChild:function(h){var g=a(h).attr("data-settings");if(!!g){g="data-settings='"+g+"'"}if(a(h).hasClass("hideMe")){g=g+" class='hideMe'"}return"<li "+g+">"+b.subTemplate.call(this,a(h))+"</li>"},getLIOptgroupChildren:function(h){var i=this,g="<li class='group'><span>"+a(h).attr("label")+"</span><ul>";a(h).find("option").each(function(){g+=f.getLIOptionChild.call(i,this)});g+="</ul></li>";return g},getLIIndex:function(h){var g=0,j,i;if(h.closest(".group").length!=0){j=h.closest(".group");g=h.closest(".transformSelectDropdown").find("li").index(h)-j.prevAll(".group").length-1}else{g=h.parent().find("li").index(h);if(b.showFirstItemInDrop==false){g+=1}g-=h.prevAll(".group").length}return g},selectNewValue:function(){var j=a(this).closest(".transformSelectDropdown"),i=j.data("trans-element"),g=f.getSelect(i),h=f.getLIIndex(a(this));g[0].selectedIndex=h;if(i.find("input").length>0){i.find("input").val(b.valueTemplate.call(a(this)))}else{sel=g.find("option:selected");i.find("span:first").html(b.ellipsisLength?a.simpleEllipsis(b.valueTemplate.call(sel),b.ellipsisLength):b.valueTemplate.call(sel))}f.closeAllDropdowns();g.trigger("change");a(".trans-element").removeClass("trans-focused");i.addClass("trans-focused");if(a.fn.validate){g.valid()}},mobileChange:function(){},selectCheckbox:function(m){var n=a(this).closest(".transformSelectDropdown"),i=n.data("trans-element"),g=f.getSelect(i),j=a(this).closest("li"),k=j.find(":checkbox"),h,l;if(a(m.target).is("li")){j=a(this)}h=f.getLIIndex(j);if(!a(m.target).is(":checkbox")){k.prop("checked",!k.is(":checked"))}g.find("option").eq(h).prop("selected",k.is(":checked"));if(k.data("transformCheckbox.initialized")===true){k.transformCheckbox("setImage")}if(!a(m.target).is(":checkbox")){k.change()}g.change()},openDrop:function(){var h=a(this).closest(".trans-element"),g=h.data("trans-element-drop"),i=a(this).parent();if(h.hasClass("disabled")){return false}if(i.hasClass("open")&&!a(this).is("input")){f.closeAllDropdowns()}else{i.css({"z-index":1200}).addClass("open");g.css({"z-index":1200}).show();f.hideAllOtherDropdowns.call(this)}if(b.addDropdownToBody){g.css({position:"absolute",top:i.offset().top+i.outerHeight(),left:i.offset().left})}},hideAllOtherDropdowns:function(){var h=a("body").find("*"),g=h.index(a(this).parent());a("body").find("ul.trans-element:not(.ignore)").each(function(){var i=a(this).data("trans-element-drop");if(g-1!=h.index(a(this))){i.hide().css("z-index",0).parent().css("z-index",0).removeClass("open")}})},closeDropDowns:function(g){if(!a(g.target).closest(".trans-element").length){f.closeAllDropdowns()}},closeAllDropdowns:function(){a("ul.trans-element:not(.ignore)").each(function(){a(this).data("trans-element-drop").hide();a(this).find("li:first").removeClass("open")}).removeClass("trans-focused")}};if(typeof c=="string"){f[c].apply(this,Array.prototype.slice.call(arguments,1));return this}return this.each(function(){if(!a(this).data("transformSelect.initialized")){b=a.extend(d,c);a(this).data("settings",b);a(this).data("transformSelect.initialized",true);f.init.call(this)}return this})},transformFile:function(b){var c={file:function(f,d){return this.each(function(){var j=a(this),i=a("<div></div>").appendTo(j).css({position:"absolute",overflow:"hidden","-moz-opacity":"0",filter:"alpha(opacity: 0)",opacity:"0",zoom:"1",width:j.outerWidth()+"px",height:j.outerHeight()+"px","z-index":1}),m=0,k,h=function(){var o=k=i.html("<input "+(window.FormData?"multiple ":"")+'type="file" style="border:none; position:absolute">').find("input");m=m||o.width();o.change(function(){o.unbind("change");h();f(o[0])})},g=function(o){i.offset(j.offset());if(o){k.offset({left:o.pageX-m+25,top:o.pageY-10});n()}},n=function(){j.addClass(d+"MouseOver")},l=function(){j.removeClass(d+"MouseOver")};h();j.mouseover(g);j.mousemove(g);j.mouseout(l);g()})}};return this.each(function(h){if(a(this).data("transformFile.initialized")===true){return this}a(this).data("transformFile.initialized",true);var j=a(this).hide(),k=null,g=j.attr("name"),d=(!b?"customInput":(b.cssClass?b.cssClass:"customInput")),f=(!b?"Browse...":(b.label?b.label:"Browse..."));if(!j.attr("id")){j.attr("id","custom_input_file_"+(new Date().getTime())+Math.floor(Math.random()*100000))}k=j.attr("id");j.after('<span id="'+k+'_custom_input" class="'+d+'"><span class="inputPath" id="'+k+'_custom_input_path">&nbsp;</span><span class="inputButton">'+f+"</span></span>");c.file.call(a("#"+k+"_custom_input"),function(i){i.id=k;i.name=g;a("#"+k).replaceWith(i).removeAttr("style").hide();a("#"+k+"_custom_input_path").html(a("#"+k).val().replace(/\\/g,"/").replace(/.*\//,""))},d);return this})},transformTextarea:function(c,b){var f={hiddenTextareaClass:"hiddenTextarea"},d=a.extend(f,c),g={init:function(){if(a(this).css("line-height")=="normal"){a(this).css("line-height","12px")}var h={"line-height":a(this).css("line-height"),"font-family":a(this).css("font-family"),"font-size":a(this).css("font-size"),border:"1px solid black",width:a(this).width(),"letter-spacing":a(this).css("letter-spacing"),"text-indent":a(this).css("text-indent"),padding:a(this).css("padding"),overflow:"hidden","white-space":a(this).css("white-space")};a(this).css(h).keyup(g.keyup).keydown(g.keyup).bind("mousewheel",g.mousewheel).after(a("<div />")).next().addClass(d.hiddenTextareaClass).css(h).css("width",a(this).width()-5).hide()},mousewheel:function(j,k){j.preventDefault();var i=a(this).css("line-height");var h=a(this)[0].scrollTop+(parseFloat(i)*(k*-1));g.scrollToPx.call(this,h)},keyup:function(h){var i=[37,38,39,40];if(a.inArray(h.which,i)!=-1){g.checkCaretScroll.call(this)}else{g.checkScroll.call(this,h.which)}g.scrollCallBack.call(this)},checkCaretScroll:function(){var m=a(this);var l=m.caret().start;var k=m.val().substr(0,l);var j=m.val().substr(l,m.val().length);var h=m.next("."+d.hiddenTextareaClass);var i=null;if(!l||l==0){return false}if(m.val().substr(l-1,1)=="\n"){k=m.val().substr(0,l+1)}g.toDiv.call(this,false,k,j);if(h.height()>(m.height()+m.scrollTop())){i=m.scrollTop()+parseInt(m.css("line-height"))}else{if(h.height()<=m.scrollTop()){i=m.scrollTop()-parseInt(m.css("line-height"))}}if(i){g.scrollToPx.call(this,i)}},checkScroll:function(j){var n=a(this);var h=a(this).next("."+d.hiddenTextareaClass);var l=n.caret().start;var k=n.val().substr(0,l);var i=n.val().substr(l,n.val().length);g.toDiv.call(this,true,k,i);if((n.scrollTop()+n.height())>h.height()){return}if(h.data("old-height")!=h.data("new-height")){var m=h.data("new-height")-h.data("old-height");g.scrollToPx.call(this,n.scrollTop()+m)}},toDiv:function(n,j,l){var h=a(this);var k=a(this).next("."+d.hiddenTextareaClass);var i=/\n/g;var o=/\s\s/g;var r=/\s/g;var m=h.val();var p=false;var q=false;if(j){m=j}if(i.test(m.substring(m.length-1,m.length))){p=true}if(i.test(m.substring(m.length-2,m.length-1))&&r.test(m.substring(m.length-1,m.length))){q=true}if(n){k.data("old-height",k.height())}m=m.replace(i,"<br>");m=m.replace(o,"&nbsp; ");m=m.replace(o,"&nbsp; ");m=m.replace(/<br>/ig,"<br />");k.html(m);if((p||q)&&a.trim(l)==""){if(q&&a.browser.msie){k.append("<br />")}k.append("<br />")}if(n){k.data("new-height",k.height())}},scrollToPercentage:function(j){if(j>=0&&j<=100){var l=a(this),h=l.next("."+d.hiddenTextareaClass),k=parseFloat(l[0].scrollHeight)-l.height(),i=k*j/100;g.scrollToPx.call(this,i)}},scrollToPx:function(h){a(this).scrollTop(g.roundToLineHeight.call(this,h));g.scrollCallBack.call(this)},roundToLineHeight:function(h){return Math.ceil(h/parseInt(a(this).css("line-height")))*parseInt(a(this).css("line-height"))},remove:function(){a(this).unbind("keyup").css({overflow:"auto",border:""}).next("div").remove()},scrollCallBack:function(){var i=parseFloat(a(this)[0].scrollHeight)-a(this).height(),h=(parseFloat(a(this)[0].scrollTop)/i*100);h=h>100?100:h;h=h<0?0:h;h=isNaN(h)?100:h;a(this).trigger("scrollToPx",[a(this)[0].scrollTop,h])}};if(typeof c=="string"){g[c].call(this,b);return this}return this.each(function(){if(!a(this).next().hasClass(d.hiddenTextareaClass)){g.init.call(this);g.toDiv.call(this,true)}})}})})(jQuery);