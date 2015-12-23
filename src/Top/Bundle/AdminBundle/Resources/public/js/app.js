define(function(require, exports, module) {
    window.load_script = function(module, options) {
        
        seajs.use('bundles/admin/' + module, function(module) {
            $(document).ready(function() {
                module.run(options);
            });
        });
    };
    
});