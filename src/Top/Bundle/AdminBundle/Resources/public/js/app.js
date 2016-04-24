define(function(require, exports, module) {
    
    window.jQuery = window.$ = jQuery = require('$');
    
    require('bootstrap');
    
    exports.load_script = function(module, options) {
        
        require.async('./controller/' + module, function(module) {
            $(document).ready(function() {
                if ($.isFunction(module.run)) {
                    module.run(options);
                }
            });
        });
    };
    window.app.load = exports.load_script;
    if (window.app.script) {
        exports.load_script(window.app.script);
        window.app.script = null;
    }
    
    var Widget = require('arale.widget');
    
    Widget.autoRenderAll();
    
    require('bootstrap.modal.hack');
    
    window.setTimeout(function() {
        $('.ag-error-show').slideUp('slow', function() {
            $(this).find('button').click();
        });
    }, 2000);
    
});