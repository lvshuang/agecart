define(function(require, exports, module) {

    exports.run = function() {

        var iframe = $('#file-iframe');
        var containerWidth = iframe.parents('.modal-body').width();
        iframe.width(containerWidth);
        iframe.height('500px');

    };

});