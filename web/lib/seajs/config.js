seajs.config({
   base: '/lib/',
   plugins: ['shim'],
   alias: {
       '$': 'jquery/1.11.3/jquery.cmd.min.js',
       '$-debug': 'jquery/1.11.3/jquery.cmd.min.js',
       'jquery': 'jquery/1.11.3/jquery.cmd.min.js',
       'bootstrap': 'bootstrap/3.3.5/js/bootstrap.cmd.min.js',
       'arale.class': 'arale/class/1.1.0/class.js',
       'arale.widget': 'arale/widget/1.1.1/widget.js',
       'arale.validator': 'arale/validator/0.9.7/validator.js',
       'category.select': 'common/js/category_select.js',
       'bootstrap.modal.hack': 'common/js/bootstrap-modal-hack.js',
       'bootstrap.validator': 'common/js/bootstrap-validator.js',
       'toastr': 'toastr/2.0.3/toastr.min.js',
       'kindeditor': 'kindeditor/4.1.7/kindeditor',
       'editor-helper': 'utils/editor-helper'
    }
});