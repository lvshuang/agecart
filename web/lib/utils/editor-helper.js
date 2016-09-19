define(function(require, exports, module) {

//    require('kindeditor');
    require('tinymce');
    
    exports.tinyMce = function(selector, uplaodUrl, options) {
        var hostname = window.location.hostname;
        var settings = $.extend({
            selector: selector,
            language: 'zh_CN',
            convert_urls: false,
            plugins : 'autolink table media link image lists charmap print preview fullscreen',
            toolbar: 'bold italic underline fontsizeselect | alignleft, aligncenter, alignright | bullist numlist | undo redo | image media link | preview fullscreen',
            plugin_preview_width: '1200px',
        });
        tinymce.init(settings);
    };

});