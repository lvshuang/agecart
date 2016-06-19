define(function(require, exports, module) {
    var Select = require('category.select');
    var BootstrapValidator = require('bootstrap.validator');
    var toastr = require('toastr');
    exports.run = function() {
        var categorySelect = new Select(
            {
                'container': '.parent-category', 
                'url': $('.parent-category').data('loadUrl'),
                'level': 2
            }
        );
        
        categorySelect.setOnSelect(function(val, level) {
            $('#form_parent_id').val(val);
        });
        var validator = new BootstrapValidator({
            'element': 'form'
        });
        
        validator.addItem({
            element: '[name="form[name]"]',
            required: true,
            rule: 'minlength{min:2} maxlength{max:40}'
        });
        validator.addItem({
            element: '[name="form[weight]"]',
            required: false,
            rule: 'digits min{min:1} max{max:100}'
        });
        
        $('button[type="submit"]').click(function(e) {
            var btn = $(this);
            validator.execute(function(error, results, element) {
                if (!error) {
                    btn.attr('disabled', 'disabled');
                    $.post($('form').attr('action'), $('form').serialize(), function(response) {
                        if (response.status == 'ok') {
                            window.location.reload();
                        } else {
                            btn.removeAttr('disabled');
                            toastr.error(response.message);
                        }
                    }, 'json');
                }
            });
        });
        
    };
    
});