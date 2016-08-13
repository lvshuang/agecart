
function apply_img_in_product(file, type_file, external){
    var path = $('#cur_dir').val();
    var base_url = $('#base_url').val();
    var track = $('#track').val();
    var tmp = '<tr class="image-item"><td></td><td></tr>';
    if (external=="") {
        var target = $('.image-list');
    }else{
        var target = $(external);
    }
    console.log(path, file);
    var tmp = '<tr class="image-item">';
    tmp += '<td>' + '<img src="' + path + file + '" width="200px"</td>';
    tmp += '<td><input type="text" name="" class="form-control" /></td>';
    tmp += '<td class="agc-table-va center-block"><a href="javascript:;" class="text-danger del-image" title="删除图片"><span class="glyphicon glyphicon-remove"></span></a></td>';
    tmp += '</tr>';
    $(tmp).appendTo(target);
    console.log(tmp);
    // var closed = window_parent.document.getElementsByClassName('mce-filemanager');
        // $(target).val(base_url+path+file);
        // $(closed).find('.mce-close').trigger('click');
}

