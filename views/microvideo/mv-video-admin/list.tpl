{{set title='视频列表'}}
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="form-inline margin-bottom-10">
				<input type="text" id="selectedDate" class="form-control auto-kal" data-kal="months: 2, mode:'range', format:'YYYY-MM-DD', rangeDelimiter:'~', direction:'today-past'" placeholder="指定日期筛选"/>
				<input class="form-control" type="number" id="videoid" placeholder="视频id查询" {{if !empty($vid)}}value="{{$vid}}"{{/if}}/>
				<input class="form-control" type="text" id="keyword" placeholder="名称查询"/>
				<span class="buildDiv form-inline">
					<input class="form-control" type="text" id="addRelInput0" placeholder="标签查询" oninput="buildBox(0, this.value);" rid="0"/>
				</span>
				<button class="btn btn-primary" id="filterBtn">搜索</button>
				<button class="btn btn-default" id="resetBtn">重置</button>
			</div>
			<div class="form-inline margin-bottom-10">
				<div class="btn-group" id="order">
					<button class="btn btn-info active" order="id">按id</button>
					<button class="btn btn-default" order="create_time">按源发布时间</button>
					<button class="btn btn-default" order="played">按播放次数</button>
					<button class="btn btn-default" order="like">按点赞数</button>
					<button class="btn btn-default" order="rank">按rank</button>
				</div>
				<div class="btn-group" id="desc">
					<button class="btn btn-info active" desc="desc">倒序</button>
					<button class="btn btn-default" desc="asc">正序</button>
				</div>
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
var url = 'data', pagesize = 20, statusMap = [], videoid={{$vid}}, keyword='', desc = 'desc', tag={{$tag}}, order = 'id', date = '', tagname = '';
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
    $.getJSON(url+'?id='+videoid+'&keyword='+keyword+'&per-page='+pagesize+'&desc='+desc+'&page='+curPage+'&tag='+tag+'&order='+order+'&date='+date+'&tagname='+tagname, function(data){
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
        
        $('.vname').editable({
			onSubmit:updateVname,
			editClass:'focus',
		});
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

	var r = '<tr id="videoItem'+v.id+'">', 
		height = v.video.coverImg != null ? Math.round(v.video.coverImg.height/v.video.coverImg.width*320) : (v.video.width != 0 ? Math.round(v.video.height/v.video.width*320) : 0);
	r += '<td>';
	r += '<h4 class="inline">'+v.id+'（'+v.sid+'）<p class="vname editable" vid="'+v.id+'">'+v.title+'</p>';
	
	r += '&nbsp;&nbsp;<select class="form-control status" vid="'+v.id+'">';
	for(var i in statusMap){
		r += '<option value="'+i+'" '+(i == v.status ? 'selected="selected"' : '')+'>'+statusMap[i]+'</option>';
	}
	r += '</select>';
	r += '&nbsp;&nbsp;<select class="form-control review" vid="'+v.id+'">';
	for(var i=0; i<3; i++){
		r += '<option value="'+i+'" '+(v.review == i ? 'selected="selected"' : '')+'> review-'+i+'</option>';
	}
	r += '</select>';
	
	r += '</h4>';
	
	//'<button class="btn btn-default">'+(statusMap[v.status] != undefined ? statusMap[v.status] : '未知状态')+'</button></h4>';
	r += '<div class="clearfix">';
	r += '<div class="col-md-7" style="border-right:4px solid #ccc;margin-top:10px;">';
	r += '<div class="video-media relative pull-left" id="videoMedia'+v.id+'" style="width:320px;background:#eaeaea;">';//height:'+height+'px;
	if(v.video.coverImg != null){
		r += '<img class="video-play" src="{{$imgUrl}}/thumb/320/'+height+'/0/'+v.video.coverImg.sid+'/'+v.video.coverImg.md5+v.video.coverImg.dotExt+'" vurl="'+vUrl+'"/>';
	}
	else{
		r += '<div class="video-play default-cover" vurl="'+vUrl+'"></div>';
	}
	r += '<p class="video-play glyphicon glyphicon-play-circle play-btn" style="left: 130px;color: #ffffff;font-size: 60px;margin-top: -30px;" vurl="'+vUrl+'"></p>';
	//r += '<video class="showVideo" autoplay="autoplay" controls="controls" src="'+vUrl+'" width="320">浏览器不支持，请更换浏览器</video>'
	r += '</div>';
	
	//更新封面图
	r += '&nbsp;&nbsp;<span class="icon-div uploadImage" id="uploadImage'+v.id+'">';
	r += '<button class="btn btn-default btn-upload"><i class="glyphicon glyphicon-upload"></i></button>';
	r += '<input type="file" id="ImageForm'+v.id+'" name="ImageForm[imageFiles][]" onchange="fileUpload('+v.id+');" accept="image/jpeg,image/png,image/gif"/>';
	r += '</span>';
	r += '<div class="clearfix"></div>';
	
	r += '<p>'+v.desc+'</p>';
	r += '</div>';
	
	r += '<div class="col-md-5">';
	r += '<p><a href="'+v.video.site_url+'" target="_blank">'+v.key+'</a></p>';
	r += '<p>视频&nbsp;&nbsp;宽：'+v.video.width+'&nbsp;&nbsp;高：'+v.video.height+'</p>';
	if(v.video.coverImg != null){
		r += '<p>图片&nbsp;&nbsp;宽：'+v.video.coverImg.width+'&nbsp;&nbsp;高：'+v.video.coverImg.height+'&nbsp;&nbsp;<a href="{{$imgUrl}}/thumb/0/0/0/'+v.video.coverImg.sid+'/'+v.video.coverImg.md5+v.video.coverImg.dotExt+'" vurl="'+vUrl+'" target="_blank">地址</a></p>';
	}
	r += '<p>源发布时间：'+v.createTime+'</p>';
	r += '<p>采集时间：'+v.updateTime+'</p>';
	if(v.countNum != null){
		r += '<p>赞（'+v.countNum.like+'）&nbsp;踩（'+v.countNum.bury+'）&nbsp;播放次数（'+v.countNum.played+'）</p>';
	}
	r += '<p>keywords</p>';
	r += '<div class="clearfix ">';
	for(var i in v.keywords){
		var o = v.keywords[i];
		/*r += '<div class="tag-rel-item btn-group">';
		r += '<button class="btn btn-default disabled" kid="'+o.sid+'">'+o.name+'</button>';
		r += '<button class="btn btn-danger delKeywordRel" kid="'+o.sid+'" vid="'+v.id+'">';
		r += '<i class="glyphicon glyphicon-trash"></i>';
		r += '</button>';
		r += '</div>';*/
		r += '&nbsp;&nbsp;<span>'+o.name+'</span>';
	}
	r += '</div>';
	if(v.commentNum > 0){
		r += '<div class="clearfix">';
		r += '<a href="../mv-comment-admin/list?vid='+v.id+'" target="_blank">查看评论（'+v.commentNum+'）</a>';
		r += '</div>';
	}
	r += '</div>';
	r += '</div>'
	r += '<div>';
	r += '<div id="tagInfo'+v.id+'" class="margin-bottom-10">';
	r += buildTagDiv(v);
	r += '</div>';
	r += '<div class="form-inline buildDiv margin-bottom-10">';
	r += '<input type="text" class="form-control add-rel" oninput="buildBox('+v.id+', this.value);" id="addRelInput'+v.id+'" rid="0">';
	r += '<button class="btn btn-primary addRel" vid="'+v.id+'">增加</button>';
	r += '</div>';
	//relTag
	r += '<div id="relTagItem'+v.id+'" class="clearfix tag-item rel-tag">';
	r += buildTagRelDiv(v);
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

function buildTagRelDiv(v){
	var r = '', exist = [];
	for(var i in v.tags){
		exist.push(v.tags[i].id);
	}
	for(var i in v.tags){
		for(var j in v.tags[i].tags){
			var o = v.tags[i].tags[j];
			if($.inArray(o.tag.id, exist) != -1){
				continue;
			}
			exist.push(o.tag.id);
			r += '<div class="tag-rel-item btn-group">';
			r += '<button class="btn btn-default disabled" tid="'+o.tag.id+'">'+o.tag.name+'</button>';
			r += '<button class="btn btn-default addRelTag" tid="'+o.tag.id+'" vid="'+v.id+'">';
			r += '<i class="glyphicon glyphicon-plus-sign"></i>';
			r += '</button></div>';
		}
	}
	
	return r;
}

function tagListRefresh(vid){
	$.getJSON('data?id='+vid, function(v){
		var r = '', m = '';
		r += buildTagDiv(v.items[0]);
		m += buildTagRelDiv(v.items[0]);
		$('#tagInfo'+vid).html(r);
		$('#relTagItem'+vid).html(m);
	});
}

function fileUpload(videoId){
    var oFiles = $('#ImageForm'+videoId)[0].files, fileLen = $('#ImageForm'+videoId)[0].files.length, finishNum = 0, uploadArr = [];
    for(var i=0; i<fileLen; i++){
		var oReader = new FileReader();
		oReader.onload = function(e){
		    finishNum++;
		    uploadArr.push('ImageForm'+videoId);
		    if(finishNum == fileLen){
		    	uploadImage(uploadArr, videoId);
		    }
		};
		oReader.readAsDataURL(oFiles[i]);
    }
}

function uploadImage(uploadArr, videoId){
    $.ajaxFileUpload({
		url:'{{$adminUrl}}/image-admin/upload',
		secureuri:false,
		fileElementId:uploadArr,
		dataType: 'json',
		data:{num:uploadArr.length, fileName:'ImageForm'},
		success: function (data, status){
			if(data.status == 0){
			    uploadVideoCover(videoId, data.data.imgs[0]);
			}
			else{
				alert(data.message);
			}
		},
		error:function(data,status,e){
			alert('请重新尝试');
		}
	});
}

function uploadVideoCover(videoId, img){
	$.post(
		'{{$adminUrl}}/microvideo/mv-video-admin/update-cover-img',
		{id:videoId, cover:img.sid},
		function(data){
			if(data.status != 0){
				alert('图片上传成功，但是保存到分类失败：'+data.message);
			}
			else{
				alert('成功');
				//写img
				$vDiv = $('#videoMedia'+videoId);
				var imgH = $vDiv.height(), vurl = ($vDiv.find('.video-play').length>0)?$vDiv.find('.video-play').attr('vurl'):$vDiv.find('.showVideo').attr('src');
				$vDiv.find('.video-play').remove();
				$vDiv.find('.showVideo').remove();
			    $vDiv.prepend('<img class="video-play" src="{{$imgUrl}}/thumb/320/'+imgH+'/0/'+img.sid+'/'+img.md5+img.dotExt+'" vurl="'+vurl+'"/>');
			}
		},
		'json'
	)
	
}

function updateVname(content){
	var $this = $(this), id = $this.attr('vid'), 
    	txtValue = content.current;
	
	if(content.current != content.previous){
		$.post(
			'video-update',
			{id:id, title:txtValue},
			function(data){
				if(data.status != 0){
					alert(data.message);
				}
			},
			'json'
		);
	}
}

$(function(){

	pageselectCallback(0, null);
	
	$(document).on('click', '#desc .btn', function(){
		$('#desc .btn').removeClass('active btn-info').addClass('btn-default');
		$(this).removeClass('btn-default').addClass('active btn-info');
		desc = $(this).attr('desc');
		pageselectCallback(0, null);
	});
	
	$(document).on('click', '#order .btn', function(){
		$('#order .btn').removeClass('active btn-info').addClass('btn-default');
		$(this).removeClass('btn-default').addClass('active btn-info');
		order = $(this).attr('order');
		pageselectCallback(0, null);
	});
    
    $('#filterBtn').click(function(){
    	videoid = $('#videoid').val();
    	keyword = $('#keyword').val();
    	tag = $('#addRelInput0').attr('rid');
    	tagname = $('#addRelInput0').val();
    	pageselectCallback(0, null);
    });
    
    $('#resetBtn').click(function(){
    	$('#videoid').val('');
    	$('#keyword').val('');
    	$('#selectedDate').val('');
    	$('#addRelInput0').val('').attr('rid', 0);
    	videoid = 0;
    	keyword = '';
    	date = '';
    	tag = 0;
    	tagname = '';
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
    
    $(document).on('click', '.addRelTag', function(){
    	var vid = $(this).attr('vid'), tid = $(this).attr('tid');
        $.post(
            'video-add-tag',
            {mv_video_id:vid, mv_tag_id:tid},
            function(data){
                if(data.status == 0){
                    tagListRefresh(vid);
                }
                else{
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
    
    $('#selectedDate').change(function(){
		date = $(this).val();
		if(date.indexOf('~') != -1){
			pageselectCallback(0,null);
			$('.kalendae').hide();
			$('#selectedDate').blur();
		}
	});
	
	$(document).on('change', '.review', function(){
		var curVid = $(this).attr('vid'), review = $(this).val();
		$.post(
			'video-update',
			{id:curVid, review:review},
			function(data){
				alert(data.message);
			},
			'json'
		)
	});
	
})

</script>