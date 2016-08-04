define(function(require, exports, module){
    seajs.use("jquery.autocomplete.css");
    require('jquery.autocomplete');

    var BootstrapValidator = require('bootstrap.validator');
    var EditorHelper = require('editor-helper');
    
    exports.run = function() {
        
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

        $('#category').AutoComplete({
            'data': $('#category').data('url'),
            'afterSelectedHandler': function(data) {
                $('#form_category_id').val(data.index);
            }
        });

        $('#brand').AutoComplete({
            'data': $('#brand').data('url'),
            'afterSelectedHandler': function(data) {
                $('#form_brand_id').val(data.index);
            }
        });
        
    };
    
});


