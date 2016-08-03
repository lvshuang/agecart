define(function(require, exports, module) {
    var Select = require('category.select');
    var BootstrapValidator = require('bootstrap.validator');
    var toastr = require('toastr');
    seajs.use("/lib/jquery.autocomplete/1.1.1/jquery.autocomplete.css");
    require('jquery.autocomplete');
    exports.run = function() {
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

        $('#parent-category').AutoComplete({
            'data': $('#parent-category').data('url'),
            // 'width': 'auto',
            // 'beforeLoadDataHandler': function(keyword) {
            //     return false;
            // },
            'afterSelectedHandler': function(data) {
                $('#form_parent_id').val(data.index);
            }
        });
        
    };
    
});