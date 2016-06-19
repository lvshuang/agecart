define(function(require, exports, module) {

    var BootstrapValidator = require('bootstrap.validator');

    exports.run = function() {
        var validator = new BootstrapValidator({
            element: '#product-sku-form'
        });

        validator.addItem({
            element: '[name="form[short_name]"]',
            required: true
        });

        validator.addItem({
           element: '[name="form[price]"]',
           required: true,
           rule: 'number',
           errormessageRequired: '请填写商品价格'
        });

        validator.addItem({
           element: '[name="form[discount_price]"]',
           rule: 'number'
        });

        validator.addItem({
           element: '[name="form[inventory]"]',
           required: true,
           rule: 'posNumber',
           errormessageRequired: '请填写商品库存'
        });

        $(document).on('click', '#attributes .delete', function() {
            $(this).parents('tr').remove();
        });


    };

});