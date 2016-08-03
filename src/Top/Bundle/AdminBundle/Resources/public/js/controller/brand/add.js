define(function(require, exports, module){
    var BootstrapValidator = require('bootstrap.validator');
    seajs.use("jquery.autocomplete.css");
    seajs.use("webuploader.css");
    require('jquery.autocomplete');
    require('webuploader');
    exports.run = function() {
        
        var logoFieldOriText = $('#form_logo').next('.help-block').text();

        BootstrapValidator.addRule('check_name', function(options, commit) {
            var element = options.element,
                item = BootstrapValidator.query('#brand-form').getItem(element);

            $.get(element.data('checkUrl'), {brandname:element.val()}, function(response) {
                commit(response, '品牌名称已存在');
            }, 'json');
        });

        var validator = new BootstrapValidator({
            element: '#brand-form'
        });

        validator.addItem({
            element: '[name="form[name]"]',
            required: true,
            rule: 'check_name'
        });

        validator.addItem({
            element: '[name="form[short_name]"]',
            required: true
        });
        
        validator.addItem({
            element: '[name="form[logo]"]',
            required: true,
            errormessageRequired: '请上传LOGO'
        });

        validator.addItem({
            element: '[name="form[category_id]"]',
            required: true,
            errormessageRequired: '请选择分类'
        });

        validator.addItem({
            element: '[name="form[weight]"]',
            required: false,
            rule: 'min{min:0} max{max:100}'
        });

        $('#category').AutoComplete({
            'data': $('#category').data('url'),
            'afterSelectedHandler': function(data) {
                $('#form_category_id').val(data.index);
            }
        });
        
        var uploader = WebUploader.create({

            // 选择文件后是否自动提交
            auto: true,

            // swf文件路径
            swf: '/lib/webuploader/Uploader.swf',

            // 文件接收服务端。
            server: $('#upload-logo').data('url'),

            // 选择文件的按钮。可选。
            // 内部根据当前运行是创建，可能是input元素，也可能是flash.
            pick: '#upload-logo',

            // 不压缩image, 默认如果是jpeg，文件上传前会压缩一把再上传！
            resize: false,

            // 限制上传类型
            accept: {
                title: 'Images',
                extensions: 'gif,jpg,jpeg,bmp,png',
                mimeTypes: 'image/*'
            },
            
        });

        // 当有文件添加进来的时候
        uploader.on('fileQueued', function( file ) {
            var $list = $('#upload-preview');
            $list.find('div.file-item').remove();
            var $li = $(
                    '<div id="' + file.id + '" class="file-item thumbnail">' +
                        '<img>' +
                        '<div class="ag-brand-logo-pre-info">' + file.name + '</div>' +
                    '</div>'
                    ),
                $img = $li.find('img');


            // $list为容器jQuery实例
            $list.append($li);

            // 创建缩略图
            // 如果为非图片文件，可以不用调用此方法。
            // thumbnailWidth x thumbnailHeight 为 100 x 100
            uploader.makeThumb(file, function(error, src) {
                if (error) {
                    $img.replaceWith('<span>不能预览</span>');
                    return;
                }

                $img.attr('src', src);
            }, 120, 68);
        });

        // 文件上传失败，显示上传出错。
        uploader.on('uploadError', function( file ) {
            var $li = $( '#'+file.id ),
                $error = $li.find('div.ag-brand-logo-pre-error');

            // 避免重复创建
            if (!$error.length) {
                $error = $('<div class="ag-brand-logo-pre-error"></div>').appendTo( $li );
            }

            $error.text('上传失败');
        });

        // 完成上传完了，成功或者失败，先删除进度条。
        uploader.on('uploadComplete', function(file) {
            $('#'+file.id ).find('.progress').remove();
        });
        
        // 上传成功后，显示上传成功，并赋值到隐藏的from field
        uploader.on('uploadSuccess', function(file, response) {
            if (response.status === 'ok') {
                var $input = $('#form_logo');
                $input.val(response.uri);
                $input.parents('.form-group').removeClass('has-error').addClass('has-success');
                $input.next('.help-block').text(logoFieldOriText);
                var $className = 'ag-brand-logo-pre-success';
                var $text = '上传成功';
            } else {
                var $className = 'ag-brand-logo-pre-error';
                var $text = response.message;
            }
            var $li = $( '#'+file.id ),
                $error = $li.find($className);

            // 避免重复创建
            if (!$error.length) {
                $error = $('<div class="'+ $className +'"></div>').appendTo( $li );
            }

            $error.text($text);
        });

    };
    
});


