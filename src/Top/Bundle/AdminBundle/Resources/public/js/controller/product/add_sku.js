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

        $('form button').click(function() {
          $('#form_type').val($(this).data('action'));
        });

        $('#product-sku-form').submit(function () {
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
              $('#form_attributes').val(attributesInString);
          }
          var images = [];
          $('.image-list tr.image-item img').each(function(index, elem) {
              var src = $(elem).attr('src');
              images.push(src);
          });
          
          var imagesInString = images.join('||');
          if (imagesInString) {
              $('#form_images').val(imagesInString);
          }
        });

        $('.image-list').on('click', '.pro-image-del', function() {
            if(confirm('是否移除选择的图片')) {
                $(this).parents('tr.image-item').remove();
            }
        });
    };

});