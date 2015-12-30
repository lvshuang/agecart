define(function(require, exports, module) {

    $(function(options) {

        $(document).on('click.modal.data-api', '[data-toggle="modal"]', function(e) {
            var url = $(this).attr('href') || $(this).data('url') || $(this).data('remote');
            if (url) {
                $($(this).attr('data-target')).html('').load(url);
            }
        });

        // $('.modal').on('click', '[type=submit]', function(e) {
        //     e.preventDefault();
        //     $(this).parents('.modal').find('form').submit();
        // });

    });

});