define(function(require, exports, module){
    var Select = require('category.select');
    var BootstrapValidator = require('bootstrap.validator');
    var EditorHelper = require('editor-helper');
    
    exports.run = function() {
        var categorySelect = new Select({
            'container': '.parent-category', 
            'url': $('.parent-category').data('loadUrl'),
            'level': 3
        });
        
        categorySelect.setOnSelect(function(val, level) {
            var categoryInput = $('#form_category_id');
            if (val) {
                categoryInput.val(val);
            } else {
                categoryInput.val('');
            }
        });
        
        var editorHelper = EditorHelper.tinyMce('#form_description');
        var validator = new BootstrapValidator({
            element: '#product-form'
        });

        validator.addItem({
            element: '[name="form[name]"]',
            required: true
        });
        validator.addItem({
           element: '[name="form[category_id]"]',
           required: true,
           errormessageRequired: '请选择商品分类'
        });
        
    };
    
});


