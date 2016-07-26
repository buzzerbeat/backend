function closeBox(){
    $('.inputRel').remove();
}
$(function(){
    $(document).on('click', '.container-fluid', function(){
        closeBox();
    });
    
    $(document).on('mouseover', '.relItem', function(){
        $('.relItem').removeClass('curRel');
        $(this).addClass('curRel');
    });
    
    
    $(document).on('click', '.relItem', function(){
        var $input = $('#addRelInput'+$(this).attr('tid'));
        $input.val($(this).html()).attr('rid', $(this).attr('rid'));
        
        closeBox();
    });
    
    $(document).bind('keydown', function (e) {
        if($('.inputRel').length > 0){
            //回车绑定
            if (e.keyCode == 13) {
                var $cur = $('.curRel');
                if($cur.length > 0){
                    $('#addRelInput'+$cur.attr('tid')).val($cur.html()).attr('rid', $cur.attr('rid'));
                    closeBox();
                }
                
            }
            else if(e.keyCode == 40){
                $cur = $('.curRel');
                if($cur.length == 0){
                    $('.relItem').first().addClass('curRel');
                }
                else{
                    $cur.removeClass('curRel');
                    $next = $cur.next('.relItem');
                    if($next.length > 0){
                        $next.addClass('curRel');
                    }
                    else{
                        $('.relItem').first().addClass('curRel');
                    }
                }
            }
            else if(e.keyCode == 38){
                $cur = $('.curRel');
                if($cur.length == 0){
                    $('.relItem').last().addClass('curRel');
                }
                else{
                    $cur.removeClass('curRel');
                    $prev = $cur.prev('.relItem');
                    if($prev.length == 0){
                        $('.relItem').last().addClass('curRel');
                    }
                    else{
                        $prev.addClass('curRel');
                    }
                }
            }
        }
    });
})