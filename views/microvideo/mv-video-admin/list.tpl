{{set title='视频列表'}}
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="form-inline margin-bottom-10">
				<div class="btn-group" id="desc">
					<button class="btn btn-info active" desc="desc">倒序</button>
					<button class="btn btn-default" desc="asc">正序</button>
				</div>
				<input class="form-control" type="number" id="videoid" placeholder="视频id查询"/>
				<input class="form-control" type="text" id="keyword" placeholder="名称查询"/>
				<button class="btn btn-primary" id="filterBtn">搜索</button>
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
var url = 'data', pagesize = 10, statusMap = [], videoid=0, keyword='', desc = 'desc';
{{foreach from=$statusMap key=sk item=sv}}
	statusMap[{{$sk}}] = '{{$sv}}';
{{/foreach}}
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
    $.getJSON(url+'?id='+videoid+'&keyword='+keyword+'&pre-page='+pagesize+'&desc='+desc+'&page='+curPage, function(data){
        var lines = '';
        if(page_index == 0){
            var optInit = getOptions();
            var length = data._meta.totalCount;
            $("#Pagination").pagination(length, optInit);
        }
        $.each(data.items,function(i,v){
            lines += buildLine(v);
        });
        var th = buildTh();
        $("#table").html(buildTable(th, lines));
    });
}

function buildLine(v){
	var vUrl = v.video.url;
	if(v.video.regexSetting != null){
		if(v.video.regexSetting.type == 1){
			//正则
			
		}
		else if(v.video.regexSetting.type == 2){
			//json
		}
	}

	var r = '<tr id="videoItem'+v.id+'">', height = Math.round(v.video.height/v.video.width*320);
	r += '<td>';
	r += '<h4 class="inline">'+v.id+'（'+v.sid+'）'+v.title;
	r += '<select class="form-control status" vid="'+v.id+'">';
	for(var i in statusMap){
		r += '<option value="'+i+'" '+(i == v.status ? 'selected="selected"' : '')+'>'+statusMap[i]+'</option>';
	}
	r += '</select>';
	r += '</h4>';
	
	//'<button class="btn btn-default">'+(statusMap[v.status] != undefined ? statusMap[v.status] : '未知状态')+'</button></h4>';
	r += '<div class="clearfix">';
	r += '<div class="col-md-7" style="border-right:4px solid #ccc;">';
	r += '<div class="video-media relative" style="width:320px;background:#eaeaea;height:'+height+'px;">'
	r += '<img class="video-play" src="{{$imgUrl}}/thumb/320/'+height+'/0/'+v.video.coverImg.sid+'/'+v.video.coverImg.md5+v.video.coverImg.dotExt+'" vurl="'+vUrl+'"/>';
	r += '<p class="video-play glyphicon glyphicon-play-circle play-btn" style="left: 130px;color: #ffffff;font-size: 60px;margin-top: -30px;" vurl="'+vUrl+'"></p>';
	//r += '<video class="showVideo" autoplay="autoplay" controls="controls" src="'+vUrl+'" width="320">浏览器不支持，请更换浏览器</video>'
	r += '</div>'
	r += '<p>'+v.desc+'</p>';
	r += '</div>';
	
	r += '<div class="col-md-5">';
	r += '<p><a href="'+v.video.site_url+'" target="_blank">'+v.key+'</a></p>';
	r += '<p>视频&nbsp;&nbsp;宽：'+v.video.width+'&nbsp;&nbsp;高：'+v.video.height+'</p>';
	r += '<p>图片&nbsp;&nbsp;宽：'+v.video.coverImg.width+'&nbsp;&nbsp;高：'+v.video.coverImg.height+'</p>';
	r += '<p>增加时间：'+v.createTime+'</p>';
	r += '<p>keywords</p>';
	r += '<div class="clearfix ">';
	for(var i in v.keywords){
		var o = v.keywords[i];
		r += '<div class="tag-rel-item btn-group">';
		r += '<button class="btn btn-default disabled" kid="'+o.sid+'">'+o.name+'</button>';
		r += '<button class="btn btn-danger delKeywordRel" kid="'+o.sid+'" vid="'+v.id+'">';
		r += '<i class="glyphicon glyphicon-trash"></i>';
		r += '</button>';
		r += '</div>';
	}
	r += '</div>';
	r += '</div>';
	r += '</div>'
	r += '<div>';
	r += '<div id="tagInfo'+v.id+'" class="margin-bottom-10">';
	r += buildTagDiv(v);
	r += '</div>';
	r += '<div class="form-inline buildDiv">';
	r += '<input type="text" class="form-control add-rel" oninput="buildBox('+v.id+', this.value);" id="addRelInput'+v.id+'" rid="0">';
	r += '<button class="btn btn-primary addRel" vid="'+v.id+'">增加</button>';
	r += '</div>';
	r += '</div>';
	r += '</td>';
	
	r += '</tr>';
	
	return r;
}

function buildTh(){
    var r = '<tr><th>信息</th>';
    r += '</tr>';
    
    return r;
}

function buildTable(title, lines) {
    var r = '<table class="table table-bordered "><thead>'+title+'</thead><tbody>'+lines+'</tbody></div>';
    return r;
}

function buildBox(videoid, value){
	if($('.inputRel').length == 0){
		var r = '<div class="inputRel">';
		
		r += '</div>';
		
		$('#addRelInput'+videoid).after(r);
	}
	$.getJSON('video-tag-data?keyword='+value+'&videoid='+videoid+'&fields=id,name', function(rel){
		var m = '<ul class="">';
		for(var i in rel.items){
			m += '<li class="relItem" rid="'+rel.items[i].id+'" tid="'+videoid+'">'+rel.items[i].name+'</li>';
		}
		
		m += '</ul>';
		$('.inputRel').html(m);
		
	});
}

function buildTagDiv(v){
	var r = '';
	if(v.tags != null){
		for(var i in v.tags){
			var o = v.tags[i];
			r += '<div class="tag-rel-item btn-group">';
			r += '<button class="btn btn-default disabled" tid="'+o.id+'">'+o.name+'</button>';
			r += '<button class="btn btn-danger delTagRel" tid="'+o.id+'" vid="'+v.id+'">';
			r += '<i class="glyphicon glyphicon-trash"></i>';
			r += '</button></div>';
		}
	}
	return r;
}

function tagListRefresh(vid){
	$.getJSON('data?id='+vid, function(v){
		var r = '';
		r += buildTagDiv(v.items[0]);
		
		$('#tagInfo'+vid).html(r);
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
    	videoid = $('#videoid').val();
    	keyword = $('#keyword').val();
    	pageselectCallback(0, null);
    });
    
    $('#resetBtn').click(function(){
    	$('#videoid').val('');
    	$('#keyword').val('');
    	videoid = 0;
    	keyword = '';
    	pageselectCallback(0, null);
    });
    
    $(document).on('click', '.video-play', function(){
    	var $parent = $(this).closest('.video-media');
    	$parent.html('<video class="showVideo" autoplay="autoplay" controls="controls" src="'+$(this).attr('vurl')+'" width="320">浏览器不支持，请更换浏览器</video>');
    });
    
    $(document).on('click', '.addRel', function(){
        var vid = $(this).attr('vid'), $input = $('#addRelInput'+vid), relName = $input.val(), rid = $input.attr('rid');
        if(relName == ''){
            alert('请确认该关联标签是否存在');
            $('#addRelInput'+vid).focus();
            return false;
        }
        $.post(
            'video-add-tag',
            {mv_video_id:vid, mv_tag_id:rid, tag_name:relName},
            function(data){
                if(data.status == 0){
                    tagListRefresh(vid);
                }
                else{
                    alert(data.message);
                }
                //@todo 关闭联想框
                closeBox();
                $input.attr('rid', 0).val('');
            },
            'json'
        );
    });
    
    $(document).on('click', '.delKeywordRel', function(){
    	if(!confirm('确定删除该关键词？')){
    		return false;
    	}
    	var $this = $(this), vid = $this.attr('vid'), ksid = $this.attr('kid');
    	$.post(
    		'video-del-keyword',
    		{video_id:vid, keyword_sid:ksid},
    		function(data){
    			if(data.status != 0){
    				alert(data.message);
    			}
    			else{
    				$this.closest('.tag-rel-item').remove();
    			}
    		},
    		'json'
    	);
    });
    
    $(document).on('change', '.status', function(){
    	var videoId = $(this).attr('vid'), curStatus = $(this).val();
    	
    	$.post(
    		'video-update',
    		{id:videoId, status:curStatus},
    		function(data){
    			if(data.status != 0){
    				alert(data.message);
    			}
    		},
    		'json'
    	);
    });
    
    $(document).on('click', '.delTagRel', function(){
    	if(!confirm('确定删除该标签？')){
    		return false;
    	}
    	var vid = $(this).attr('vid'), tid = $(this).attr('tid');
    	$.post(
    		'video-del-tag',
    		{mv_video_id:vid, mv_tag_id:tid},
    		function(data){
    			if(data.status != 0){
    				alert(data.message);
    			}
    			else{
    				tagListRefresh(vid);
    			}
    		},
    		'json'
    	);
    });
	
})

</script>