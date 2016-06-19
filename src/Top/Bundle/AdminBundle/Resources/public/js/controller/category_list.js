define(function(require, exports, module) {

    var toastr = require('toastr');
    /* 全选功能关闭
    $('.select-all').click(function () {
        if ($(this).data('selected')) {
            $('input[type="checkbox"]').each(function () {
                $(this).prop('checked', false);
            });
            $(this).data('selected', 0).text('全选');
        } else {
            $('input[type="checkbox"]').each(function () {
                $(this).prop('checked', true);
            });
            $(this).text('取消').data('selected', 1);
        }
    });
    
    $(document).on('click', 'input[type="checkbox"]', function() {
        var selectAllFlag = $('.select-all');

        if ($(this).is(':checked')) {
            var isSelectAll = false;
            $('input[type="checkbox"]').each(function () {
                if (!$(this).is(':checked')) {
                    isSelectAll = false;
                } else {
                    isSelectAll = true;
                }
            });
            if (isSelectAll) {
                selectAllFlag.text('取消').data('selected', 1);
            }
        } else {
            if (selectAllFlag.data('selected')) {
                selectAllFlag.text('全选').data('selected', 0);
            }
        }
    });
    */

    exports.run = function() {
        $(document).on('click', 'button[data-role="switch"]', function() {
            var dom = $(this);
            var type = $(this).data('type');
            var typeString = type == 'enable' ? "开启" : "关闭";
            var confirmMessage = "确认" + typeString + "分类吗？";
            if (!confirm(confirmMessage)) {
                return false;
            }
            $.post($(this).data('url'), function(response) {
                if (response.status == 'ok') {
                    toastr.success('操作成功', typeString);
                    dom.parents('tr').replaceWith(response.html);
                } else {
                    toastr.error('操作失败', typeString);
                }
            }, 'json');
        });
    };
    
});

