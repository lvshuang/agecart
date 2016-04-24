/**
 * Bootstrap样式的前端验证类.
 * 
 * @author lvshuang1201@gmail.com
 */
define(function(require, exports, module) {
    
    var Validator = require('arale.validator');
    var BootstrapValidator = Validator.extend({
        attrs: {
            explainClass: "help-block",
            itemClass: "form-group",
            itemHoverClass: "",
            itemFocusClass: "",
            itemErrorClass: "has-error",
            inputClass: "form-control",
            textareaClass: "form-control",
            showMessage: function(message, element) {
                this.getExplain(element).html(message);
                this.getItem(element).addClass(this.get("itemErrorClass"));
            },
            hideMessage: function(message, element) {
                this.getExplain(element).html(element.attr("data-explain") || " ");
                this.getItem(element).removeClass(this.get("itemErrorClass"));
            }
        },
        
        getExplain: function(ele) {
            var item = this.getItem(ele);
            var explain = item.find("." + this.get("explainClass"));
            if (explain.length == 0) {
                explain = $('<span class="' + this.get("explainClass") + '"></span>').insertAfter(item.find('input'));
            }
            return explain;
        },
        
        focus: function(e) {
            var target = e.target, autoFocusEle = this.get("autoFocusEle");
            if (autoFocusEle && autoFocusEle.has(target)) {
                var that = this;
                $(target).keyup(function(e) {
                    that.set("autoFocusEle", null);
                    that.focus({
                        target: target
                    });
                });
                return;
            }
            if (this.getItem(target).hasClass(this.get("itemErrorClass"))) {
                this.getExplain(target).html($(target).attr("data-explain") || "");
            }
            this.getItem(target).removeClass(this.get("itemErrorClass"));
            this.getItem(target).addClass(this.get("itemFocusClass"));
            
        }
        
    });
    
    
    module.exports = BootstrapValidator;
    
});