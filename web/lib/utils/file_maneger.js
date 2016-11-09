
function apply_img_in_product(file, type_file, external){
    var path = $('#cur_dir').val();
    var base_url = $('#base_url').val();
    var track = $('#track').val();
    
    var tmp = '<tr class="image-item">';
    tmp += '<td>' + '<img src="' + path + file + '" width="200px"</td>';
    tmp += '<td class="agc-table-va center-block"><a href="javascript:;" class="text-danger pro-image-del" title="删除图片"><span class="glyphicon glyphicon-remove"></span></a></td>';
    tmp += '</tr>';
    
    
    var target = window.parent.document.getElementsByClassName('image-list');
    var closed = window.parent.document.getElementsByClassName('bt-modal-close');
    $(closed).trigger('click');
    $(tmp).appendTo(target);

}

