{{set title='壁纸管理'}}
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="form-inline margin-bottom-10">
				<div class="btn-group" id="desc">
					<button class="btn btn-info active" desc="desc">倒序</button>
					<button class="btn btn-default" desc="asc">正序</button>
				</div>
				<span class="buildDiv form-inline">
					<input class="form-control" type="text" id="addRelInput0" placeholder="分类名查询" oninput="buildBox(0, this.value);" rid="0"/>
				</span>
				<input class="form-control" type="number" id="wpImgid" placeholder="壁纸id"/>
				<input class="form-control" type="text" id="wpImgsid" placeholder="壁纸sid"/>
				<input class="form-control" type="number" id="imgid" placeholder="图片id"/>
				<input class="form-control" type="text" id="imgsid" placeholder="图片sid"/>
				<button class="btn btn-primary" id="filterBtn">查询</button>
				<button class="btn btn-default" id="resetBtn">重置</button>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div id="table"></div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div id="Pagination" class="pagination"></div>
		</div>
	</div>
</div>
<script type="text/javascript">
var url = 'image-admin/data', pagesize = 10, album = '', 
	wpImgid=0, wpImgsid='', desc = 'desc', imgid = 0, imgsid = '';
function getOptions() {
    var opt = {
        link_to:"javascript:void(0)",
        callback: pageselectCallback,
        items_per_page : (pagesize != undefined) ? pagesize : 20,
        num_display_entries : 6,
        num_edge_entries : 2,
        prev_text : '前一页',
        next_text : '后一页',
        ul_class : 'pagination'
    };
    return opt;
}

function pageselectCallback(page_index, jq){
    $("#table").html('<div class="alert alert-info" role="alert">加载中...</div>');
    var curPage = 1+parseInt(page_index); 
    $.getJSON(url+'?wpid='+wpImgid+'&wpsid='+wpImgsid+'&pre-page='+pagesize+'&desc='+desc+'&page='+curPage+'&keyword='+album+'&imgid='+imgid+'&imgsid='+imgsid, function(data){
        var lines = '';
        if(page_index == 0){
            var optInit = getOptions();
            var length = data._meta.totalCount;
            $("#Pagination").pagination(length, optInit);
        }
        $.each(data.items,function(i,v){
            lines += buildLine(v);
            if(i%4 == 3){
            	lines += '<div class="clearfix"></div>';
            }
        });
        $("#table").html(buildTable(lines));
    });
}

function buildLine(v){
	var r = '<div id="imgItem'+v.id+'" class="img-backend img-item pull-left">';
	r += '<p>分类：'+(v.album != null ? v.album.title : '为空')+'</p>';
	r += '<p>增加时间：'+v.image.addTime+'</p>';
	r += '<p>大小：'+v.image.width+'*'+v.image.height+'</p>';
	r += '<p>壁纸：'+v.id+'（'+v.sid+'）</p>';
	r += '<p>图片：'+v.image.id+'（'+v.image.sid+'）</p>';
	r += '<div style="height:240px;">';
	r += '<img src="{{$imgUrl}}/thumb/'+(Math.round(v.image.width/v.image.height*240))+'/240/0/'+v.image.sid+'/'+v.image.md5+v.image.dotExt+'"/>'
	r += '</div>';
	r += '</div>';
	
	return r;
}

function buildTable(lines) {
    var r = '<div id="imgList">'+lines+'</div>';
    return r;
}

function buildBox(albumId, value){
	if($('.inputRel').length == 0){
		var r = '<div class="inputRel">';
		r += '</div>';
		$('#addRelInput'+albumId).after(r);
	}
	$.getJSON('album-admin/data?keyword='+value+'&fields=title&per-page=10', function(rel){
		var m = '<ul class="">';
		for(var i in rel.items){
			m += '<li class="relItem" rid="'+rel.items[i].id+'" tid="'+albumId+'">'+rel.items[i].title+'</li>';
		}
		
		m += '</ul>';
		$('.inputRel').html(m);
		
	});
}

$(function(){

	pageselectCallback(0, null);
	
	$(document).on('click', '#desc .btn', function(){
		$('#desc .btn').removeClass('acitve btn-info').addClass('btn-default');
		$(this).removeClass('btn-default').addClass('active btn-info');
		desc = $(this).attr('desc');
		pageselectCallback(0, null);
	});
    
    $('#filterBtn').click(function(){
    	album = $('#addRelInput0').val();
    	wpImgid = $('#wpImgid').val();
    	wpImgsid = $('#wpImgsid').val();
    	imgid = $('#imgid').val();
    	imgsid = $('#imgsid').val();
    	
    	pageselectCallback(0, null);
    });
    
    $('#resetBtn').click(function(){
    	$('#addRelInput0').val('');
    	$('#wpImgid').val('');
    	$('#wpImgsid').val('');
    	$('#imgid').val('');
    	$('#imgsid').val('');
    	
    	album = $('#addRelInput0').val();
    	wpImgid = $('#wpImgid').val();
    	wpImgsid = $('#wpImgsid').val();
    	imgid = $('#imgid').val();
    	imgsid = $('#imgsid').val();
    	
    	pageselectCallback(0, null);
    });
    
	
})

</script>