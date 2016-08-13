define(function(require, exports, module){
    seajs.use("jquery.autocomplete.css");
    require('jquery.autocomplete');

    var BootstrapValidator = require('bootstrap.validator');
    var EditorHelper = require('editor-helper');
    
    exports.run = function() {
        
        var editorHelper = EditorHelper.tinyMce('#form_description');
        var validator = new BootstrapValidator({
            element: '#product-form',
            auto: false,
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

        $('#product-form').submit(function() {
            var attributes = [];
            $('.attribute-list tr.attribute-item').each(function(index, elem) {
                var name = $(elem).find('input:first').val();
                if (name) {
                    var value = $(elem).find('input:last').val();
                    attributes.push(name + '^' + value);
                }
                
            });
            var attributesInString = attributes.join('$');
            if (attributesInString) {
                $('#form_attrs').val(attributesInString);
            }

            validator.execute(function(error, results, element) {
                $(results).each(function(index, value) {
                    var tabId = $(value[2]).parents('div[role="tabpanel"]').attr('id');
                    if (tabId) {
                        $('a[href="#' + tabId + '"]').click();
                    }
                });
            });

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

        $('.attribute-list').on('click', '.add-attribute', function() {
            var newElement = $(this).parents('tr').clone(true);
            newElement.find('input').val('');
            newElement.appendTo('.attribute-list');;
        });

        $('.attribute-list').on('click', '.del-attribute', function() {
            if ($('.attribute-list tr.attribute-item').length <= 1) {
                return ;
            }
            $(this).parents('tr').remove();
        });


    };
    
});


