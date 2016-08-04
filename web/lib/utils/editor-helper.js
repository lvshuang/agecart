define(function(require, exports, module) {

//    require('kindeditor');
    require('tinymce');

    exports.createFullEditor = function(dom, uploadType, options) {

        var settings = $.extend({
            items: ['source', 'wordpaste', '|', 'undo', 'redo', '|', 'preview', 'print', 'cut', 'copy', 'paste', 'plainpaste', '|', 'justifyleft', 'justifycenter', 'justifyright', 'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript', 'superscript', 'clearhtml', 'selectall', '|', 'fullscreen', '/', 'formatblock', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image', 'multiimage', 'flash', 'media', 'insertfile', 'table', 'hr', 'emoticons', 'anchor', 'link', 'unlink', 'about'],
            resizeType: 1,
            pasteType: 1,
            filterMode: true,
            filePostName: 'file',
            uploadJson : '/editor_upload' + (uploadType ? ('?type=' + uploadType) : ''),
            cssPath: '',
            themeType: 'default',
            imageTabIndex:1,
            allowFlashUpload: false,
            allowMediaUpload: false,
            minWidth: 200
        }, options);

        return KindEditor.create(dom, settings);
    };

    exports.createMiniEditor = function(dom, uploadType, options) {

        var settings = $.extend({
            items: ['insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'clearhtml', '|', 'bold', 'italic', 'underline', 'strikethrough',  'removeformat', '|', 'link', 'unlink', '|', 'image', 'multiimage', '|', 'fullscreen', 'preview', '|', 'source'],
            resizeType: 1,
            pasteType: 1,
            filterMode: true,
            filePostName: 'file',
            uploadJson : '/editor_upload' + (uploadType ? ('?type=' + uploadType) : ''),
            cssPath: '',
            themeType: 'default',
            imageTabIndex:1,
            allowFlashUpload: false,
            allowMediaUpload: false,
            minWidth: 200
        }, options);

        return KindEditor.create(dom, settings);
    };

    exports.createMediaEditor = function(dom, uploadType, options) {

        var settings = $.extend({
            items: ['image', 'multiimage', '|', 'link', 'unlink', '|', 'fullscreen', 'preview', '|', 'source'],
            resizeType: 1,
            pasteType: 1,
            filterMode: true,
            filePostName: 'file',
            uploadJson : '/editor_upload' + (uploadType ? ('?type=' + uploadType) : ''),
            cssPath: '',
            themeType: 'default',
            imageTabIndex:1,
            allowFlashUpload: false,
            allowMediaUpload: false,
            minWidth: 200
        }, options);

        return KindEditor.create(dom, settings);
    };
    
    exports.tinyMce = function(selector, uplaodUrl, options) {
        var hostname = window.location.hostname;
        var settings = $.extend({
            selector: selector,
            language: 'zh_CN',
            convert_urls: false,
            plugins : 'autolink table media link image lists charmap print preview fullscreen',
            toolbar: 'bold italic underline fontsizeselect | alignleft, aligncenter, alignright | bullist numlist | undo redo | image media link | preview fullscreen'
        });
        tinymce.init(settings);
    };

});