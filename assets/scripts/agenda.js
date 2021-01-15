function selectData(self){
    $("#calendario td.data").removeClass('bg-info');
    $("#calendario td.data").removeClass('text-light');
    $(self).addClass('bg-info');
    $(self).addClass('text-light');

    param = "?dia="+$(self).attr('dia')+"&mes="+$(self).attr('mes')+"&ano="+$(self).attr('ano');
    
    $.get('core/ajax/agenda-dia.php'+param, resp => {
        $('#jTblDia').empty();
        $('#jTblDia').append(resp);
    });
}