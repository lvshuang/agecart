define(function(require, exports, module) {
    
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
    
});

