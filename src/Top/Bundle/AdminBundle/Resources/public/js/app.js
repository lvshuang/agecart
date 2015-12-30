define(function(require, exports, module) {
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
    
    require('bootstrap.modal.hack');
    
});