function relative_time(time_value) {
  var values = time_value.split(" ");
  time_value = values[1] + " " + values[2] + ", " + values[5] + " " + values[3];
  var parsed_date = Date.parse(time_value);
  var relative_to = (arguments.length > 1) ? arguments[1] : new Date();
  var delta = parseInt((relative_to.getTime() - parsed_date) / 1000);
  delta = delta + (relative_to.getTimezoneOffset() * 60);

  if (delta < 60) {
	return 'less than a minute ago';
  } else if(delta < 120) {
	return 'about a minute ago';
  } else if(delta < (60*60)) {
	return (parseInt(delta / 60)).toString() + ' minutes ago';
  } else if(delta < (120*60)) {
	return 'about an hour ago';
  } else if(delta < (24*60*60)) {
	return 'about ' + (parseInt(delta / 3600)).toString() + ' hours ago';
  } else if(delta < (48*60*60)) {
	return '1 day ago';
  } else {
	return (parseInt(delta / 86400)).toString() + ' days ago';
  }
}
 
// remap jQuery to $
(function($){

 // Twitter style countdown
 $.fn.charCount = function(options){
	  
		// default configuration properties
		var defaults = {	
			allowed: 1000,		
			warning: 100,
			css: 'counter',
			counterElement: 'div',
			cssWarning: 'warning',
			cssExceeded: 'exceeded',
			counterText: ''
		}; 
			
		var options = $.extend(defaults, options); 
		
		function calculate(obj){
			var count = $(obj).val().length;
			var available = options.allowed - count;
			if(available <= options.warning && available >= 0){
				$(obj).next().addClass(options.cssWarning);
			} else {
				$(obj).next().removeClass(options.cssWarning);
			}
			if(available < 0){
				$(obj).next().addClass(options.cssExceeded);
			} else {
				$(obj).next().removeClass(options.cssExceeded);
			}
			$(obj).next().html(options.counterText + available);
		};
				
		this.each(function() {  			
			$(this).after('<'+ options.counterElement +' class="' + options.css + '">'+ options.counterText +'</'+ options.counterElement +'>');
			calculate(this);
			$(this).keyup(function(){calculate(this)});
			$(this).change(function(){calculate(this)});
		});
	  
	};

})(this.jQuery);



// usage: log('inside coolFunc',this,arguments);
// paulirish.com/2009/log-a-lightweight-wrapper-for-consolelog/
window.log = function(){
  log.history = log.history || [];   // store logs to an array for reference
  log.history.push(arguments);
  if(this.console){
	console.log( Array.prototype.slice.call(arguments) );
  }
};



// catch all document.write() calls
(function(doc){
  var write = doc.write;
  doc.write = function(q){ 
	log('document.write(): ',arguments); 
	if (/docwriteregexwhitelist/.test(q)) write.apply(doc,arguments);  
  };
})(document);

/**
 * jQuery Validation Plugin 1.8.0
 *
 * http://bassistance.de/jquery-plugins/jquery-plugin-validation/
 * http://docs.jquery.com/Plugins/Validation
 *
 * Copyright (c) 2006 - 2011 Jörn Zaefferer
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 */
(function(c){c.extend(c.fn,{validate:function(a){if(this.length){var b=c.data(this[0],"validator");if(b)return b;b=new c.validator(a,this[0]);c.data(this[0],"validator",b);if(b.settings.onsubmit){this.find("input, button").filter(".cancel").click(function(){b.cancelSubmit=true});b.settings.submitHandler&&this.find("input, button").filter(":submit").click(function(){b.submitButton=this});this.submit(function(d){function e(){if(b.settings.submitHandler){if(b.submitButton)var f=c("<input type='hidden'/>").attr("name",
b.submitButton.name).val(b.submitButton.value).appendTo(b.currentForm);b.settings.submitHandler.call(b,b.currentForm);b.submitButton&&f.remove();return false}return true}b.settings.debug&&d.preventDefault();if(b.cancelSubmit){b.cancelSubmit=false;return e()}if(b.form()){if(b.pendingRequest){b.formSubmitted=true;return false}return e()}else{b.focusInvalid();return false}})}return b}else a&&a.debug&&window.console&&console.warn("nothing selected, can't validate, returning nothing")},valid:function(){if(c(this[0]).is("form"))return this.validate().form();
else{var a=true,b=c(this[0].form).validate();this.each(function(){a&=b.element(this)});return a}},removeAttrs:function(a){var b={},d=this;c.each(a.split(/\s/),function(e,f){b[f]=d.attr(f);d.removeAttr(f)});return b},rules:function(a,b){var d=this[0];if(a){var e=c.data(d.form,"validator").settings,f=e.rules,g=c.validator.staticRules(d);switch(a){case "add":c.extend(g,c.validator.normalizeRule(b));f[d.name]=g;if(b.messages)e.messages[d.name]=c.extend(e.messages[d.name],b.messages);break;case "remove":if(!b){delete f[d.name];
return g}var h={};c.each(b.split(/\s/),function(j,i){h[i]=g[i];delete g[i]});return h}}d=c.validator.normalizeRules(c.extend({},c.validator.metadataRules(d),c.validator.classRules(d),c.validator.attributeRules(d),c.validator.staticRules(d)),d);if(d.required){e=d.required;delete d.required;d=c.extend({required:e},d)}return d}});c.extend(c.expr[":"],{blank:function(a){return!c.trim(""+a.value)},filled:function(a){return!!c.trim(""+a.value)},unchecked:function(a){return!a.checked}});c.validator=function(a,
b){this.settings=c.extend(true,{},c.validator.defaults,a);this.currentForm=b;this.init()};c.validator.format=function(a,b){if(arguments.length==1)return function(){var d=c.makeArray(arguments);d.unshift(a);return c.validator.format.apply(this,d)};if(arguments.length>2&&b.constructor!=Array)b=c.makeArray(arguments).slice(1);if(b.constructor!=Array)b=[b];c.each(b,function(d,e){a=a.replace(RegExp("\\{"+d+"\\}","g"),e)});return a};c.extend(c.validator,{defaults:{messages:{},groups:{},rules:{},errorClass:"error",
validClass:"valid",errorElement:"label",focusInvalid:true,errorContainer:c([]),errorLabelContainer:c([]),onsubmit:true,ignore:[],ignoreTitle:false,onfocusin:function(a){this.lastActive=a;if(this.settings.focusCleanup&&!this.blockFocusCleanup){this.settings.unhighlight&&this.settings.unhighlight.call(this,a,this.settings.errorClass,this.settings.validClass);this.addWrapper(this.errorsFor(a)).hide()}},onfocusout:function(a){if(!this.checkable(a)&&(a.name in this.submitted||!this.optional(a)))this.element(a)},
onkeyup:function(a){if(a.name in this.submitted||a==this.lastElement)this.element(a)},onclick:function(a){if(a.name in this.submitted)this.element(a);else a.parentNode.name in this.submitted&&this.element(a.parentNode)},highlight:function(a,b,d){c(a).addClass(b).removeClass(d)},unhighlight:function(a,b,d){c(a).removeClass(b).addClass(d)}},setDefaults:function(a){c.extend(c.validator.defaults,a)},messages:{required:"This field is required.",remote:"Please fix this field.",email:"Please enter a valid email address.",
url:"Please enter a valid URL.",date:"Please enter a valid date.",dateISO:"Please enter a valid date (ISO).",number:"Please enter a valid number.",digits:"Please enter only digits.",creditcard:"Please enter a valid credit card number.",equalTo:"Please enter the same value again.",accept:"Please enter a value with a valid extension.",maxlength:c.validator.format("Please enter no more than {0} characters."),minlength:c.validator.format("Please enter at least {0} characters."),rangelength:c.validator.format("Please enter a value between {0} and {1} characters long."),
range:c.validator.format("Please enter a value between {0} and {1}."),max:c.validator.format("Please enter a value less than or equal to {0}."),min:c.validator.format("Please enter a value greater than or equal to {0}.")},autoCreateRanges:false,prototype:{init:function(){function a(e){var f=c.data(this[0].form,"validator");e="on"+e.type.replace(/^validate/,"");f.settings[e]&&f.settings[e].call(f,this[0])}this.labelContainer=c(this.settings.errorLabelContainer);this.errorContext=this.labelContainer.length&&
this.labelContainer||c(this.currentForm);this.containers=c(this.settings.errorContainer).add(this.settings.errorLabelContainer);this.submitted={};this.valueCache={};this.pendingRequest=0;this.pending={};this.invalid={};this.reset();var b=this.groups={};c.each(this.settings.groups,function(e,f){c.each(f.split(/\s/),function(g,h){b[h]=e})});var d=this.settings.rules;c.each(d,function(e,f){d[e]=c.validator.normalizeRule(f)});c(this.currentForm).validateDelegate(":text, :password, :file, select, textarea",
"focusin focusout keyup",a).validateDelegate(":radio, :checkbox, select, option","click",a);this.settings.invalidHandler&&c(this.currentForm).bind("invalid-form.validate",this.settings.invalidHandler)},form:function(){this.checkForm();c.extend(this.submitted,this.errorMap);this.invalid=c.extend({},this.errorMap);this.valid()||c(this.currentForm).triggerHandler("invalid-form",[this]);this.showErrors();return this.valid()},checkForm:function(){this.prepareForm();for(var a=0,b=this.currentElements=this.elements();b[a];a++)this.check(b[a]);
return this.valid()},element:function(a){this.lastElement=a=this.clean(a);this.prepareElement(a);this.currentElements=c(a);var b=this.check(a);if(b)delete this.invalid[a.name];else this.invalid[a.name]=true;if(!this.numberOfInvalids())this.toHide=this.toHide.add(this.containers);this.showErrors();return b},showErrors:function(a){if(a){c.extend(this.errorMap,a);this.errorList=[];for(var b in a)this.errorList.push({message:a[b],element:this.findByName(b)[0]});this.successList=c.grep(this.successList,
function(d){return!(d.name in a)})}this.settings.showErrors?this.settings.showErrors.call(this,this.errorMap,this.errorList):this.defaultShowErrors()},resetForm:function(){c.fn.resetForm&&c(this.currentForm).resetForm();this.submitted={};this.prepareForm();this.hideErrors();this.elements().removeClass(this.settings.errorClass)},numberOfInvalids:function(){return this.objectLength(this.invalid)},objectLength:function(a){var b=0,d;for(d in a)b++;return b},hideErrors:function(){this.addWrapper(this.toHide).hide()},
valid:function(){return this.size()==0},size:function(){return this.errorList.length},focusInvalid:function(){if(this.settings.focusInvalid)try{c(this.findLastActive()||this.errorList.length&&this.errorList[0].element||[]).filter(":visible").focus().trigger("focusin")}catch(a){}},findLastActive:function(){var a=this.lastActive;return a&&c.grep(this.errorList,function(b){return b.element.name==a.name}).length==1&&a},elements:function(){var a=this,b={};return c([]).add(this.currentForm.elements).filter(":input").not(":submit, :reset, :image, [disabled]").not(this.settings.ignore).filter(function(){!this.name&&
a.settings.debug&&window.console&&console.error("%o has no name assigned",this);if(this.name in b||!a.objectLength(c(this).rules()))return false;return b[this.name]=true})},clean:function(a){return c(a)[0]},errors:function(){return c(this.settings.errorElement+"."+this.settings.errorClass,this.errorContext)},reset:function(){this.successList=[];this.errorList=[];this.errorMap={};this.toShow=c([]);this.toHide=c([]);this.currentElements=c([])},prepareForm:function(){this.reset();this.toHide=this.errors().add(this.containers)},
prepareElement:function(a){this.reset();this.toHide=this.errorsFor(a)},check:function(a){a=this.clean(a);if(this.checkable(a))a=this.findByName(a.name).not(this.settings.ignore)[0];var b=c(a).rules(),d=false,e;for(e in b){var f={method:e,parameters:b[e]};try{var g=c.validator.methods[e].call(this,a.value.replace(/\r/g,""),a,f.parameters);if(g=="dependency-mismatch")d=true;else{d=false;if(g=="pending"){this.toHide=this.toHide.not(this.errorsFor(a));return}if(!g){this.formatAndAdd(a,f);return false}}}catch(h){this.settings.debug&&
window.console&&console.log("exception occured when checking element "+a.id+", check the '"+f.method+"' method",h);throw h;}}if(!d){this.objectLength(b)&&this.successList.push(a);return true}},customMetaMessage:function(a,b){if(c.metadata){var d=this.settings.meta?c(a).metadata()[this.settings.meta]:c(a).metadata();return d&&d.messages&&d.messages[b]}},customMessage:function(a,b){var d=this.settings.messages[a];return d&&(d.constructor==String?d:d[b])},findDefined:function(){for(var a=0;a<arguments.length;a++)if(arguments[a]!==
undefined)return arguments[a]},defaultMessage:function(a,b){return this.findDefined(this.customMessage(a.name,b),this.customMetaMessage(a,b),!this.settings.ignoreTitle&&a.title||undefined,c.validator.messages[b],"<strong>Warning: No message defined for "+a.name+"</strong>")},formatAndAdd:function(a,b){var d=this.defaultMessage(a,b.method),e=/\$?\{(\d+)\}/g;if(typeof d=="function")d=d.call(this,b.parameters,a);else if(e.test(d))d=jQuery.format(d.replace(e,"{$1}"),b.parameters);this.errorList.push({message:d,
element:a});this.errorMap[a.name]=d;this.submitted[a.name]=d},addWrapper:function(a){if(this.settings.wrapper)a=a.add(a.parent(this.settings.wrapper));return a},defaultShowErrors:function(){for(var a=0;this.errorList[a];a++){var b=this.errorList[a];this.settings.highlight&&this.settings.highlight.call(this,b.element,this.settings.errorClass,this.settings.validClass);this.showLabel(b.element,b.message)}if(this.errorList.length)this.toShow=this.toShow.add(this.containers);if(this.settings.success)for(a=
0;this.successList[a];a++)this.showLabel(this.successList[a]);if(this.settings.unhighlight){a=0;for(b=this.validElements();b[a];a++)this.settings.unhighlight.call(this,b[a],this.settings.errorClass,this.settings.validClass)}this.toHide=this.toHide.not(this.toShow);this.hideErrors();this.addWrapper(this.toShow).show()},validElements:function(){return this.currentElements.not(this.invalidElements())},invalidElements:function(){return c(this.errorList).map(function(){return this.element})},showLabel:function(a,
b){var d=this.errorsFor(a);if(d.length){d.removeClass().addClass(this.settings.errorClass);d.attr("generated")&&d.html(b)}else{d=c("<"+this.settings.errorElement+"/>").attr({"for":this.idOrName(a),generated:true}).addClass(this.settings.errorClass).html(b||"");if(this.settings.wrapper)d=d.hide().show().wrap("<"+this.settings.wrapper+"/>").parent();this.labelContainer.append(d).length||(this.settings.errorPlacement?this.settings.errorPlacement(d,c(a)):d.insertAfter(a))}if(!b&&this.settings.success){d.text("");
typeof this.settings.success=="string"?d.addClass(this.settings.success):this.settings.success(d)}this.toShow=this.toShow.add(d)},errorsFor:function(a){var b=this.idOrName(a);return this.errors().filter(function(){return c(this).attr("for")==b})},idOrName:function(a){return this.groups[a.name]||(this.checkable(a)?a.name:a.id||a.name)},checkable:function(a){return/radio|checkbox/i.test(a.type)},findByName:function(a){var b=this.currentForm;return c(document.getElementsByName(a)).map(function(d,e){return e.form==
b&&e.name==a&&e||null})},getLength:function(a,b){switch(b.nodeName.toLowerCase()){case "select":return c("option:selected",b).length;case "input":if(this.checkable(b))return this.findByName(b.name).filter(":checked").length}return a.length},depend:function(a,b){return this.dependTypes[typeof a]?this.dependTypes[typeof a](a,b):true},dependTypes:{"boolean":function(a){return a},string:function(a,b){return!!c(a,b.form).length},"function":function(a,b){return a(b)}},optional:function(a){return!c.validator.methods.required.call(this,
c.trim(a.value),a)&&"dependency-mismatch"},startRequest:function(a){if(!this.pending[a.name]){this.pendingRequest++;this.pending[a.name]=true}},stopRequest:function(a,b){this.pendingRequest--;if(this.pendingRequest<0)this.pendingRequest=0;delete this.pending[a.name];if(b&&this.pendingRequest==0&&this.formSubmitted&&this.form()){c(this.currentForm).submit();this.formSubmitted=false}else if(!b&&this.pendingRequest==0&&this.formSubmitted){c(this.currentForm).triggerHandler("invalid-form",[this]);this.formSubmitted=
false}},previousValue:function(a){return c.data(a,"previousValue")||c.data(a,"previousValue",{old:null,valid:true,message:this.defaultMessage(a,"remote")})}},classRuleSettings:{required:{required:true},email:{email:true},url:{url:true},date:{date:true},dateISO:{dateISO:true},dateDE:{dateDE:true},number:{number:true},numberDE:{numberDE:true},digits:{digits:true},creditcard:{creditcard:true}},addClassRules:function(a,b){a.constructor==String?this.classRuleSettings[a]=b:c.extend(this.classRuleSettings,
a)},classRules:function(a){var b={};(a=c(a).attr("class"))&&c.each(a.split(" "),function(){this in c.validator.classRuleSettings&&c.extend(b,c.validator.classRuleSettings[this])});return b},attributeRules:function(a){var b={};a=c(a);for(var d in c.validator.methods){var e=a.attr(d);if(e)b[d]=e}b.maxlength&&/-1|2147483647|524288/.test(b.maxlength)&&delete b.maxlength;return b},metadataRules:function(a){if(!c.metadata)return{};var b=c.data(a.form,"validator").settings.meta;return b?c(a).metadata()[b]:
c(a).metadata()},staticRules:function(a){var b={},d=c.data(a.form,"validator");if(d.settings.rules)b=c.validator.normalizeRule(d.settings.rules[a.name])||{};return b},normalizeRules:function(a,b){c.each(a,function(d,e){if(e===false)delete a[d];else if(e.param||e.depends){var f=true;switch(typeof e.depends){case "string":f=!!c(e.depends,b.form).length;break;case "function":f=e.depends.call(b,b)}if(f)a[d]=e.param!==undefined?e.param:true;else delete a[d]}});c.each(a,function(d,e){a[d]=c.isFunction(e)?
e(b):e});c.each(["minlength","maxlength","min","max"],function(){if(a[this])a[this]=Number(a[this])});c.each(["rangelength","range"],function(){if(a[this])a[this]=[Number(a[this][0]),Number(a[this][1])]});if(c.validator.autoCreateRanges){if(a.min&&a.max){a.range=[a.min,a.max];delete a.min;delete a.max}if(a.minlength&&a.maxlength){a.rangelength=[a.minlength,a.maxlength];delete a.minlength;delete a.maxlength}}a.messages&&delete a.messages;return a},normalizeRule:function(a){if(typeof a=="string"){var b=
{};c.each(a.split(/\s/),function(){b[this]=true});a=b}return a},addMethod:function(a,b,d){c.validator.methods[a]=b;c.validator.messages[a]=d!=undefined?d:c.validator.messages[a];b.length<3&&c.validator.addClassRules(a,c.validator.normalizeRule(a))},methods:{required:function(a,b,d){if(!this.depend(d,b))return"dependency-mismatch";switch(b.nodeName.toLowerCase()){case "select":return(a=c(b).val())&&a.length>0;case "input":if(this.checkable(b))return this.getLength(a,b)>0;default:return c.trim(a).length>
0}},remote:function(a,b,d){if(this.optional(b))return"dependency-mismatch";var e=this.previousValue(b);this.settings.messages[b.name]||(this.settings.messages[b.name]={});e.originalMessage=this.settings.messages[b.name].remote;this.settings.messages[b.name].remote=e.message;d=typeof d=="string"&&{url:d}||d;if(this.pending[b.name])return"pending";if(e.old===a)return e.valid;e.old=a;var f=this;this.startRequest(b);var g={};g[b.name]=a;c.ajax(c.extend(true,{url:d,mode:"abort",port:"validate"+b.name,
dataType:"json",data:g,success:function(h){f.settings.messages[b.name].remote=e.originalMessage;var j=h===true;if(j){var i=f.formSubmitted;f.prepareElement(b);f.formSubmitted=i;f.successList.push(b);f.showErrors()}else{i={};h=h||f.defaultMessage(b,"remote");i[b.name]=e.message=c.isFunction(h)?h(a):h;f.showErrors(i)}e.valid=j;f.stopRequest(b,j)}},d));return"pending"},minlength:function(a,b,d){return this.optional(b)||this.getLength(c.trim(a),b)>=d},maxlength:function(a,b,d){return this.optional(b)||
this.getLength(c.trim(a),b)<=d},rangelength:function(a,b,d){a=this.getLength(c.trim(a),b);return this.optional(b)||a>=d[0]&&a<=d[1]},min:function(a,b,d){return this.optional(b)||a>=d},max:function(a,b,d){return this.optional(b)||a<=d},range:function(a,b,d){return this.optional(b)||a>=d[0]&&a<=d[1]},email:function(a,b){return this.optional(b)||/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i.test(a)},
url:function(a,b){return this.optional(b)||/^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(a)},
date:function(a,b){return this.optional(b)||!/Invalid|NaN/.test(new Date(a))},dateISO:function(a,b){return this.optional(b)||/^\d{4}[\/-]\d{1,2}[\/-]\d{1,2}$/.test(a)},number:function(a,b){return this.optional(b)||/^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)?$/.test(a)},digits:function(a,b){return this.optional(b)||/^\d+$/.test(a)},creditcard:function(a,b){if(this.optional(b))return"dependency-mismatch";if(/[^0-9-]+/.test(a))return false;var d=0,e=0,f=false;a=a.replace(/\D/g,"");for(var g=a.length-1;g>=
0;g--){e=a.charAt(g);e=parseInt(e,10);if(f)if((e*=2)>9)e-=9;d+=e;f=!f}return d%10==0},accept:function(a,b,d){d=typeof d=="string"?d.replace(/,/g,"|"):"png|jpe?g|gif";return this.optional(b)||a.match(RegExp(".("+d+")$","i"))},equalTo:function(a,b,d){d=c(d).unbind(".validate-equalTo").bind("blur.validate-equalTo",function(){c(b).valid()});return a==d.val()}}});c.format=c.validator.format})(jQuery);
(function(c){var a={};if(c.ajaxPrefilter)c.ajaxPrefilter(function(d,e,f){e=d.port;if(d.mode=="abort"){a[e]&&a[e].abort();a[e]=f}});else{var b=c.ajax;c.ajax=function(d){var e=("port"in d?d:c.ajaxSettings).port;if(("mode"in d?d:c.ajaxSettings).mode=="abort"){a[e]&&a[e].abort();return a[e]=b.apply(this,arguments)}return b.apply(this,arguments)}}})(jQuery);
(function(c){!jQuery.event.special.focusin&&!jQuery.event.special.focusout&&document.addEventListener&&c.each({focus:"focusin",blur:"focusout"},function(a,b){function d(e){e=c.event.fix(e);e.type=b;return c.event.handle.call(this,e)}c.event.special[b]={setup:function(){this.addEventListener(a,d,true)},teardown:function(){this.removeEventListener(a,d,true)},handler:function(e){arguments[0]=c.event.fix(e);arguments[0].type=b;return c.event.handle.apply(this,arguments)}}});c.extend(c.fn,{validateDelegate:function(a,
b,d){return this.bind(b,function(e){var f=c(e.target);if(f.is(a))return d.apply(f,arguments)})}})})(jQuery);

jQuery.fn.extend({captify:function(n){var a=$.extend({speedOver:"fast",speedOut:"normal",hideDelay:500,animation:"slide",prefix:"",opacity:"0.45",className:"caption-bottom",position:"bottom",spanWidth:"100%"},n);$(this).each(function(){var c=this;$(this).load(function(){if(c.hasInit)return false;c.hasInit=true;var i=false,k=false,e=$("#"+$(this).attr("rel")),g=!e.length?$(this).attr("alt"):e.html();e.remove();e=this.parent&&this.parent.tagName=="a"?this.parent:$(this);var h=e.wrap("<div></div>").parent().css({overflow:"hidden",
padding:0,fontSize:0.1}).addClass("caption-wrapper").width($(this).width()).height($(this).height());$.map(["top","right","bottom","left"],function(f){h.css("margin-"+f,$(c).css("margin-"+f));$.map(["style","width","color"],function(j){j="border-"+f+"-"+j;h.css(j,$(c).css(j))})});$(c).css({border:"0 none"});var b=$("div:last",h.append("<div></div>")).addClass(a.className),d=$("div:last",h.append("<div></div>")).addClass(a.className).append(a.prefix).append(g);$("*",h).css({margin:0}).show();g=jQuery.browser.msie?
"static":"relative";b.css({zIndex:1,position:g,opacity:a.animation=="fade"?0:a.opacity,width:a.spanWidth});if(a.position=="bottom"){e=parseInt(b.css("border-top-width").replace("px",""))+parseInt(d.css("padding-top").replace("px",""))-1;d.css("paddingTop",e)}d.css({position:g,zIndex:2,background:"none",border:"0 none",opacity:a.animation=="fade"?0:1,width:a.spanWidth});b.width(d.outerWidth());b.height(d.height());g=a.position=="bottom"&&jQuery.browser.msie?-4:0;var l=a.position=="top"?{hide:-$(c).height()-
b.outerHeight()-1,show:-$(c).height()}:{hide:0,show:-b.outerHeight()+g};d.css("marginTop",-b.outerHeight());b.css("marginTop",l[a.animation=="fade"||a.animation=="always-on"?"show":"hide"]);var m=function(){if(!i&&!k){var f=a.animation=="fade"?{opacity:0}:{marginTop:l.hide};b.animate(f,a.speedOut);a.animation=="fade"&&d.animate({opacity:0},a.speedOver)}};if(a.animation!="always-on"){$(this).hover(function(){k=true;if(!i){var f=a.animation=="fade"?{opacity:a.opacity}:{marginTop:l.show};b.animate(f,
a.speedOver);a.animation=="fade"&&d.animate({opacity:1},a.speedOver/2)}},function(){k=false;window.setTimeout(m,a.hideDelay)});$("div",h).hover(function(){i=true},function(){i=false;window.setTimeout(m,a.hideDelay)})}});if(this.complete||this.naturalWidth>0)$(c).trigger("load")})}}); 

/*
 * Facebox (for jQuery)
 * version: 1.2 (05/05/2008)
 * @requires jQuery v1.2 or later
 *
 * Examples at http://famspam.com/facebox/
 *
 * Licensed under the MIT:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Copyright 2007, 2008 Chris Wanstrath [ chris@ozmm.org ]
 *
 * Usage:
 *
 *  jQuery(document).ready(function() {
 *	jQuery('a[rel*=facebox]').facebox()
 *  })
 *
 *  <a href="#terms" rel="facebox">Terms</a>
 *	Loads the #terms div in the box
 *
 *  <a href="terms.html" rel="facebox">Terms</a>
 *	Loads the terms.html page in the box
 *
 *  <a href="terms.png" rel="facebox">Terms</a>
 *	Loads the terms.png image in the box
 *
 *
 *  You can also use it programmatically:
 *
 *	jQuery.facebox('some html')
 *	jQuery.facebox('some html', 'my-groovy-style')
 *
 *  The above will open a facebox with "some html" as the content.
 *
 *	jQuery.facebox(function($) {
 *	  $.get('blah.html', function(data) { $.facebox(data) })
 *	})
 *
 *  The above will show a loading screen before the passed function is called,
 *  allowing for a better ajaxy experience.
 *
 *  The facebox function can also display an ajax page, an image, or the contents of a div:
 *
 *	jQuery.facebox({ ajax: 'remote.html' })
 *	jQuery.facebox({ ajax: 'remote.html' }, 'my-groovy-style')
 *	jQuery.facebox({ image: 'stairs.jpg' })
 *	jQuery.facebox({ image: 'stairs.jpg' }, 'my-groovy-style')
 *	jQuery.facebox({ div: '#box' })
 *	jQuery.facebox({ div: '#box' }, 'my-groovy-style')
 *
 *  Want to close the facebox?  Trigger the 'close.facebox' document event:
 *
 *	jQuery(document).trigger('close.facebox')
 *
 *  Facebox also has a bunch of other hooks:
 *
 *	loading.facebox
 *	beforeReveal.facebox
 *	reveal.facebox (aliased as 'afterReveal.facebox')
 *	init.facebox
 *	afterClose.facebox
 *
 *  Simply bind a function to any of these hooks:
 *
 *   $(document).bind('reveal.facebox', function() { ...stuff to do after the facebox and contents are revealed... })
 *
 */
(function($) {
  $.facebox = function(data, klass) {
	$.facebox.loading()

	if (data.ajax) fillFaceboxFromAjax(data.ajax, klass)
	else if (data.image) fillFaceboxFromImage(data.image, klass)
	else if (data.div) fillFaceboxFromHref(data.div, klass)
	else if ($.isFunction(data)) data.call($)
	else $.facebox.reveal(data, klass)
  }

  /*
   * Public, $.facebox methods
   */

  $.extend($.facebox, {
	settings: {
	  opacity	  : 0.2,
	  overlay	  : true,
	  loadingImage : 'img/loading.gif',
	  closeImage   : 'img/closelabel.png',
	  imageTypes   : [ 'png', 'jpg', 'jpeg', 'gif' ],
	  faceboxHtml  : '\
	<div id="facebox" style="display:none;"> \
	  <div class="popup"> \
		<div class="content"> \
		</div> \
		<a href="#" class="close"><img src="img/closelabel.png" title="close" class="close_image" /></a> \
	  </div> \
	</div>'
	},

	loading: function() {
	  init()
	  if ($('#facebox .loading').length == 1) return true
	  showOverlay()

	  $('#facebox .content').empty()
	  $('#facebox .body').children().hide().end().
		append('<div class="loading"><img src="'+$.facebox.settings.loadingImage+'"/></div>')

	  $('#facebox').css({
		top:	getPageScroll()[1] + (getPageHeight() / 10),
		left:	$(window).width() / 2 - 205
	  }).show()

	  $(document).bind('keydown.facebox', function(e) {
		if (e.keyCode == 27) $.facebox.close()
		return true
	  })
	  $(document).trigger('loading.facebox')
	},

	reveal: function(data, klass) {
	  $(document).trigger('beforeReveal.facebox')
	  if (klass) $('#facebox .content').addClass(klass)
	  $('#facebox .content').append(data)
	  $('#facebox .loading').remove()
	  $('#facebox .body').children().fadeIn('normal')
	  $('#facebox').css('left', $(window).width() / 2 - ($('#facebox .popup').width() / 2))
	  $(document).trigger('reveal.facebox').trigger('afterReveal.facebox')
	},

	close: function() {
	  $(document).trigger('close.facebox')
	  return false
	}
  })

  /*
   * Public, $.fn methods
   */

  $.fn.facebox = function(settings) {
	if ($(this).length == 0) return

	init(settings)

	function clickHandler() {
	  $.facebox.loading(true)

	  // support for rel="facebox.inline_popup" syntax, to add a class
	  // also supports deprecated "facebox[.inline_popup]" syntax
	  var klass = this.rel.match(/facebox\[?\.(\w+)\]?/)
	  if (klass) klass = klass[1]

	  fillFaceboxFromHref(this.href, klass)
	  return false
	}

	return this.bind('click.facebox', clickHandler)
  }

  /*
   * Private methods
   */

  // called one time to setup facebox on this page
  function init(settings) {
	if ($.facebox.settings.inited) return true
	else $.facebox.settings.inited = true

	$(document).trigger('init.facebox')
	makeCompatible()

	var imageTypes = $.facebox.settings.imageTypes.join('|')
	$.facebox.settings.imageTypesRegexp = new RegExp('\.(' + imageTypes + ')$', 'i')

	if (settings) $.extend($.facebox.settings, settings)
	$('body').append($.facebox.settings.faceboxHtml)

	var preload = [ new Image(), new Image() ]
	preload[0].src = $.facebox.settings.closeImage
	preload[1].src = $.facebox.settings.loadingImage

	$('#facebox').find('.b:first, .bl').each(function() {
	  preload.push(new Image())
	  preload.slice(-1).src = $(this).css('background-image').replace(/url\((.+)\)/, '$1')
	})

	$('#facebox .close').click($.facebox.close)
	$('#facebox .close_image').attr('src', $.facebox.settings.closeImage)
  }

  // getPageScroll() by quirksmode.com
  function getPageScroll() {
	var xScroll, yScroll;
	if (self.pageYOffset) {
	  yScroll = self.pageYOffset;
	  xScroll = self.pageXOffset;
	} else if (document.documentElement && document.documentElement.scrollTop) {	 // Explorer 6 Strict
	  yScroll = document.documentElement.scrollTop;
	  xScroll = document.documentElement.scrollLeft;
	} else if (document.body) {// all other Explorers
	  yScroll = document.body.scrollTop;
	  xScroll = document.body.scrollLeft;
	}
	return new Array(xScroll,yScroll)
  }

  // Adapted from getPageSize() by quirksmode.com
  function getPageHeight() {
	var windowHeight
	if (self.innerHeight) {	// all except Explorer
	  windowHeight = self.innerHeight;
	} else if (document.documentElement && document.documentElement.clientHeight) { // Explorer 6 Strict Mode
	  windowHeight = document.documentElement.clientHeight;
	} else if (document.body) { // other Explorers
	  windowHeight = document.body.clientHeight;
	}
	return windowHeight
  }

  // Backwards compatibility
  function makeCompatible() {
	var $s = $.facebox.settings

	$s.loadingImage = $s.loading_image || $s.loadingImage
	$s.closeImage = $s.close_image || $s.closeImage
	$s.imageTypes = $s.image_types || $s.imageTypes
	$s.faceboxHtml = $s.facebox_html || $s.faceboxHtml
  }

  // Figures out what you want to display and displays it
  // formats are:
  //	 div: #id
  //   image: blah.extension
  //	ajax: anything else
  function fillFaceboxFromHref(href, klass) {
	// div
	if (href.match(/#/)) {
	  var url	= window.location.href.split('#')[0]
	  var target = href.replace(url,'')
	  if (target == '#') return
	  $.facebox.reveal($(target).html(), klass)

	// image
	} else if (href.match($.facebox.settings.imageTypesRegexp)) {
	  fillFaceboxFromImage(href, klass)
	// ajax
	} else {
	  fillFaceboxFromAjax(href, klass)
	}
  }

  function fillFaceboxFromImage(href, klass) {
	var image = new Image()
	image.onload = function() {
	  $.facebox.reveal('<div class="image"><img src="' + image.src + '" /></div>', klass)
	}
	image.src = href
  }

  function fillFaceboxFromAjax(href, klass) {
	$.get(href, function(data) { $.facebox.reveal(data, klass) })
  }

  function skipOverlay() {
	return $.facebox.settings.overlay == false || $.facebox.settings.opacity === null
  }

  function showOverlay() {
	if (skipOverlay()) return

	if ($('#facebox_overlay').length == 0)
	  $("body").append('<div id="facebox_overlay" class="facebox_hide"></div>')

	$('#facebox_overlay').hide().addClass("facebox_overlayBG")
	  .css('opacity', $.facebox.settings.opacity)
	  .click(function() { $(document).trigger('close.facebox') })
	  .fadeIn(200)
	return false
  }

  function hideOverlay() {
	if (skipOverlay()) return

	$('#facebox_overlay').fadeOut(200, function(){
	  $("#facebox_overlay").removeClass("facebox_overlayBG")
	  $("#facebox_overlay").addClass("facebox_hide")
	  $("#facebox_overlay").remove()
	})

	return false
  }

  /*
   * Bindings
   */

  $(document).bind('close.facebox', function() {
	$(document).unbind('keydown.facebox')
	$('#facebox').fadeOut(function() {
	  $('#facebox .content').removeClass().addClass('content')
	  $('#facebox .loading').remove()
	  $(document).trigger('afterClose.facebox')
	})
	hideOverlay()
  })

})(jQuery);

/*
	Mosaic - Sliding Boxes and Captions jQuery Plugin
	Version 1.0.1
	www.buildinternet.com/project/mosaic
	
	By Sam Dunn / One Mighty Roar (www.onemightyroar.com)
	Released under MIT License / GPL License
*/

(function(a){if(!a.omr){a.omr=new Object()}a.omr.mosaic=function(c,b){var d=this;d.$el=a(c);d.el=c;d.$el.data("omr.mosaic",d);d.init=function(){d.options=a.extend({},a.omr.mosaic.defaultOptions,b);d.load_box()};d.load_box=function(){if(d.options.preload){a(d.options.backdrop,d.el).hide();a(d.options.overlay,d.el).hide();a(window).load(function(){if(d.options.options.animation=="fade"&&a(d.options.overlay,d.el).css("opacity")==0){a(d.options.overlay,d.el).css("filter","alpha(opacity=0)")}a(d.options.overlay,d.el).fadeIn(200,function(){a(d.options.backdrop,d.el).fadeIn(200)});d.allow_hover()})}else{a(d.options.backdrop,d.el).show();a(d.options.overlay,d.el).show();d.allow_hover()}};d.allow_hover=function(){switch(d.options.animation){case"fade":a(d.el).hover(function(){a(d.options.overlay,d.el).stop().fadeTo(d.options.speed,d.options.opacity)},function(){a(d.options.overlay,d.el).stop().fadeTo(d.options.speed,0)});break;case"slide":startX=a(d.options.overlay,d.el).css(d.options.anchor_x)!="auto"?a(d.options.overlay,d.el).css(d.options.anchor_x):"0px";startY=a(d.options.overlay,d.el).css(d.options.anchor_y)!="auto"?a(d.options.overlay,d.el).css(d.options.anchor_y):"0px";var f={};f[d.options.anchor_x]=d.options.hover_x;f[d.options.anchor_y]=d.options.hover_y;var e={};e[d.options.anchor_x]=startX;e[d.options.anchor_y]=startY;a(d.el).hover(function(){a(d.options.overlay,d.el).stop().animate(f,d.options.speed)},function(){a(d.options.overlay,d.el).stop().animate(e,d.options.speed)});break}};d.init()};a.omr.mosaic.defaultOptions={animation:"fade",speed:150,opacity:1,preload:0,anchor_x:"left",anchor_y:"bottom",hover_x:"0px",hover_y:"0px",overlay:".mosaic-overlay",backdrop:".mosaic-backdrop"};a.fn.mosaic=function(b){return this.each(function(){(new a.omr.mosaic(this,b))})}})(jQuery);