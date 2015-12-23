/**
 * 分类下拉选择组件.
 */
define(function(require, exports, module) {
    var Class = require('arale.class');
    
    var Select = Class.create({
        loadChirdUrl: '',
        tmp: '<div class="category-select"><form class="form-inline"><div class="form-group"><label class="sr-only">分类</label><select class="form-control" data-level=1></select></div><div class="form-group"><select class="form-control" data-level=2></select></div><div class="form-group"><select class="form-control" data-level=3></select></div></form></div>';
        currentLevel: 1, // 当前分类等级
        
        initialize: function () {
            
        },
        
        setOnSelect: function (callback) {
            if (typeof(callback) === 'function') {
                this.onSelect = callback;
            }
        },
        
        setLoadChirdUrl: function(url) {
            this.loadChirdUrl = url;
        },
        
        _initEvents: function() {
            var self = this;
            $(document).on('click', '.category-select select', function() {
                self.currentLevel = $(this).data('level');
                self._loadChirdlen($(this).val());
            });
        },
        
        _loadChird: function(id) {
            var url = this.loadChirdUrl;
            $.get(url, {parentId: id}, function(response) {
                
            }, 'json');
        },
        
        _renderChildl: function(categorys) {
            var html = '';
            $.each(categorys, function(index, category) {
                html += '<select value=' + category.id + '>' + category.name + '</select>';
            });
        }
        
    });
    
    module.exports = Select;
    
});