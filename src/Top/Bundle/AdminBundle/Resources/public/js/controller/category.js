define(function(require, exports, module) {
    var Select = require('category.select');
    exports.run = function() {
        var categorySelect = new Select({'container': '.parent-category', 'url': $('.parent-category').data('loadUrl')});
        
        categorySelect.setOnSelect(function(val, level) {
            $('#form_parent_id').val(val);
            console.info('ok');
        });
        
        $(document).on('click', 'button[type="submit"]', function() {
            
        });
        
    };
    
});