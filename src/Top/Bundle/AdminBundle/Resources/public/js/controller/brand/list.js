define(function(require, exports, module){

    var toastr = require('toastr');

    exports.run = function() {
        $('table').on('click', '.brand-switch-btn', function() {
            var self = $(this);
            var text = self.text();
            if (!confirm('确认'+text+'品牌？')) {
                return true;
            }
            $.post(self.data('url'), function(response) {
                if (response.status === 'ok') {
                    var id = $(response.html).attr('id');
                    $('#'+id).replaceWith(response.html);
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                }
            }, 'json');
        });

        $('table').on('click', '.brand-delete-btn', function() {
            if (!confirm('确认删除品牌？')) {
                return true;
            }
            var self = $(this),
                id = self.data('id');
            $.post(self.data('url'), {'id': id}, function(response) {
                if (response.status === 'ok') {
                    $('#' + id).remove();
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                }
            }, 'json');

        });
    }
});