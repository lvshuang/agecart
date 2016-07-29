define(function(require, exports, module) {
    var toastr = require('toastr');

    exports.run = function() {
        $('a.delete').click(function() {
            if (!confirm('确认删除该商品？')) {
                return false;
            }
            var deleteBtn = $(this);
            deleteBtn.attr('disabled', 'disabled');
            $.post(deleteBtn.data('url'), {id: deleteBtn.data('id')}, function(response) {
                if (response.status == 'ok') {
                    toastr.success(response.message);
                    $('tr#'+deleteBtn.data('id')).remove();
                } else {
                    deleteBtn.removeAttr('disabled');
                    toastr.error(response.message);
                }
            }, 'json');
        });
    };

});