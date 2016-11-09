define(function(require, exports, module) {

    exports.run = function() {

        $('#add-to-cart').click(function() {
            var self = $(this);
            $.post(self.data('url'), {'sku': self.data('sku'), 'quantity': 1}, function(response) {
                if (response.status !== 'ok') {
                    alert(response.msg);
                }
            }, 'json');
        });

        $('.agc-sku-attr-list a').click(function() {
            var skuAttrs = $('#sku-attrs').text();
            var skuAttrs = JSON.parse(skuAttrs);
            console.log(skuAttrs);
        });

    };

});