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
    
    var initLeft = 0, curLeft = 270, canUp = canDown = true;
    $(document).on('mousewheel', '.scroll', function(event, delta, deltaX, deltaY){
        var $this = $(this), $list = $this.find('.img-ul'), twidth = $this.width(), lwidth = $list.width(), maxWidth = lwidth-twidth;
        if (delta > 0 && canUp){
            curLeft = curLeft < initLeft-100 ? initLeft : curLeft-100;
            $this.scrollLeft(curLeft);
            event.preventDefault();
        }
        else if (delta < 0 && canDown){
            curLeft = curLeft > maxWidth-100 ? maxWidth : curLeft+100;
            $this.scrollLeft(curLeft);
            event.preventDefault();
        }
        if( curLeft == maxWidth){
            canDown = false;
        }
        else if(curLeft <= 0){
            canUp = false;
        }
        
    });
    
    $(document).on('mouseover', '.scroll', function(){
        curLeft = $(this).scrollLeft();
        canUp = canDown = true;
        $(document).bind('mousewheel');
    });
});