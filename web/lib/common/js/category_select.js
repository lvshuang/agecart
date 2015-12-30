/**
 * 分类下拉选择组件.
 * 
 * @author lvshuang1201@gmail.com
 */
define(function(require, exports, module) {
    var Class = require('arale.class');
    
    var Select = Class.extend({
        container: '',
        loadUrl: '',
        tmp: '<div class="category-select" style="display: inline-block;">\n\
            <select class="form-control col-sm-3" data-level=1></select>\n\
            <select class="form-control col-sm-3" data-level=2></select>\n\
        </div>',
        currentLevel: 1, // 当前分类等级
        
        initialize: function (options) {
            if (!options.container) {
                throw '请设置容器参数';
            }
            this.container = $(options.container);
            if (options.url) {
                this.loadUrl = options.url;
            }
            $(this.tmp).prependTo(this.container);
            this._initEvents();
            this._loadParent();
        },
        
        setOnSelect: function (callback) {
            if (typeof(callback) === 'function') {
                this.onSelect = callback;
            }
        },
        
        setLoadChirdUrl: function(url) {
            this.loadUrl = url;
        },
        
        setTemplete: function(templete) {
            this.templete = templete;
        },
        
        _loadParent: function() {
            var self = this;
            if (!this.loadUrl) {
                throw 'load url not set';
            }
            $.get(this.loadUrl, {}, function(response){
                self._render(response, 1);
            }, 'json');
        },
        
        _initEvents: function() {
            var self = this;
            $(document).on('change', '.category-select select', function() {
                var currentLevel = $(this).data('level');
                var level = currentLevel + 1;
                $(this).find('option:selected').each(function() {
                    self._clear(level);
                    var parendId = $(this).val();
                    if (self.onSelect) {
                        self.onSelect(parendId, currentLevel);
                    }
                    self._loadChild(parendId, level);
                });
            });
        },
        
        _loadChild: function(id, level) {
            var url = this.loadUrl;
            var self = this;
            $.get(url, {parent_id: id}, function(response) {
                self._render(response, level);
            }, 'json');
        },
        
        _render: function(categorys, level) {
            var html = '<option value="0">---请选择---</option>';
            $.each(categorys, function(index, category) {
                html += '<option value=' + category.id + '>' + category.name + '</option>';
            });
            $(html).appendTo('select[data-level=' + level + ']');
        },
        
        _clear: function(level) {
            $('select[data-level=' + level + ']').html('');
        }
        
    });
    
    module.exports = Select;
    
});