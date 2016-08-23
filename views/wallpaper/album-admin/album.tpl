{{set title='album分类列表'}}
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="form-inline margin-bottom-10">
				<div class="btn-group" id="desc">
					<button class="btn btn-info active" desc="desc">倒序</button>
					<button class="btn btn-default" desc="asc">正序</button>
				</div>
				<input class="form-control" type="number" id="albumid" placeholder="分类id查询"/>
				<input class="form-control" type="text" id="albumsid" placeholder="分类sid查询"/>
				<span class="buildDiv form-inline">
					<input class="form-control" type="text" id="addRelInput0" placeholder="分类名查询" oninput="buildBox(0, this.value);" rid="0"/>
				</span>
				<select class="form-control" id="category">
					<option value="0">全部</option>
				{{foreach from=$category item=cat}}
					<option value="{{$cat.id}}">{{$cat.name}}</option>
				{{/foreach}}
					<option value="-1" id="optionAdd">新增</option>
				</select>
				<input class="form-control addCat form-hidden" id="catTitle" placeholder="增加大类名称"/>
				<button class="btn btn-primary addCat form-hidden" id="catBtn">增加</button>
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
var url = 'album-admin/data', pagesize = 10, category = '', 
	albumid=0, albumsid='', desc = 'desc', keyword = '', uploadArr = [], categoryMap = [];
categoryMap[0] = '请选择大类';
{{foreach from=$category item=cat}}
	categoryMap[{{$cat.id}}] = '{{$cat.name}}';
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
    $.getJSON(url+'?id='+albumid+'&sid='+albumsid+'&per-page='+pagesize+'&desc='+desc+'&page='+curPage+'&keyword='+keyword+'&category='+category, function(data){
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
	var r = '<tr id="albumItem'+v.id+'" class="album">';
	r += '<td>';
	r += '<div class="margin-bottom-10 form-inline">'
	r += '<span class="icon-div uploadImage" id="uploadImage'+v.id+'">';
	if(v.iconImg != null){
		r += '<img src="{{$imgUrl}}/thumb/60/60/0/'+v.iconImg.sid+'/'+v.iconImg.md5+v.iconImg.dotExt+'"/>';
	}
	else{
		r += '<button class="btn btn-default btn-upload"><i class="glyphicon glyphicon-upload"></i></button>';
	}
	r += '<input type="file" id="ImageForm'+v.id+'" name="ImageForm[imageFiles][]" onchange="fileUpload('+v.id+');" accept="image/jpeg,image/png,image/gif" multiple="multiple"/>';
	r += '</span>';
	
	r += v.id+'（'+v.sid+'）&nbsp;'+v.title;
	r += '&nbsp;&nbsp;<select class="form-control cat" aid="'+v.id+'">';
	for(var i in categoryMap){
		r += '<option value="'+i+'" '+(v.cat != null && categoryMap[i] == v.cat.name ? 'selected="selected"' : '')+'>'+categoryMap[i]+'</option>';
	}
	r += '</select>';
	
	r += '</div>';
	r += '<div class="img-div clearfix scroll">';
	var totalW = 0;
	for(var i in v.wpImages){
		var o = v.wpImages[i];
		totalW += (Math.round(o.image.width/o.image.height*640))+10;
	}
	r += '<div class="img-ul" style="width:'+totalW+'px;">';
	for(var i in v.wpImages){
		var o = v.wpImages[i];
		r += '<div class="img-item pull-left">';
		r += '<img class="img-rounded" src="{{$imgUrl}}/thumb/'+(Math.round(o.image.width/o.image.height*640))+'/640/0/'+o.image.sid+'/'+o.image.md5+o.image.dotExt+'"/>';
		r += '</div>';
	}
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

function fileUpload(albumId){
    var oFiles = $('#ImageForm'+albumId)[0].files, fileLen = $('#ImageForm'+albumId)[0].files.length, finishNum = 0, uploadArr = [];
    for(var i=0; i<fileLen; i++){
		var oReader = new FileReader();
		oReader.onload = function(e){
		    finishNum++;
		    uploadArr.push('ImageForm'+albumId);
		    if(finishNum == fileLen){
		    	uploadImage(uploadArr, albumId);
		    }
		};
		oReader.readAsDataURL(oFiles[i]);
    }
}

function uploadImage(uploadArr, albumId){
    $.ajaxFileUpload({
		url:'{{$adminUrl}}image-admin/upload',
		secureuri:false,
		fileElementId:uploadArr,
		dataType: 'json',
		data:{num:uploadArr.length, fileName:'ImageForm'},
		success: function (data, status){
			if(data.status == 0){
			    uploadAlbumIcon(albumId, data.data.imgs[0]);
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

function uploadAlbumIcon(albumId, img){
	$.post(
		'album-admin/album-update-icon',
		{id:albumId, icon:img.sid},
		function(data){
			if(data.status != 0){
				alert('图片上传成功，但是保存到分类失败：'+data.message);
			}
			else{
				alert('成功');
				//写img
			    $('#uploadImage'+albumId+' button').remove();
			    $('#uploadImage'+albumId+' img').remove();
			    $('#uploadImage'+albumId).prepend('<img src="{{$imgUrl}}/thumb/60/60/0/'+img.sid+'/'+img.md5+img.dotExt+'"/>');
			}
		},
		'json'
	)
	
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
    	albumid = $('#albumid').val();
    	albumsid = $('#albumsid').val();
    	keyword = $('#addRelInput0').val();
    	category = $('#category').val();
    	pageselectCallback(0, null);
    });
    
    $('#resetBtn').click(function(){
    	$('#albumid').val('');
    	$('#albumsid').val('');
    	$('#addRelInput0').val('');
    	albumid = 0;
    	albumsid = '';
    	keyword = '';
    	$('#catTitle').val('');
    	$('.addCat').hide();
    	pageselectCallback(0, null);
    });
    
    $('#category').change(function(){
    	if($(this).val() != -1){
    		$('#catTitle').val('');
    		$('.addCat').hide();
    		category = $(this).val();
    		pageselectCallback(0, null);
    	}
    	else{
    		$('.addCat').show();
    	}
    });
    
    $(document).on('change', '.cat', function(data){
    	var curId = $(this).attr('aid'), curCat = $(this).val();
    	$.post(
    		'album-admin/album-update',
    		{id:curId, category:curCat},
    		function(data){
    			if(data.status != 0){
    				alert(data.message);
    			}
    		}
    	)
    });
    
    $('#catBtn').click(function(e){
    	e.stopPropagation();
    	var catName = $('#catTitle').val();
    	if(catName == ''){
    		alert('不能为空');
    		$('#catTitle').focus();
    		return false;
    	}
    	$.post(
    		'album-admin/category-create',
    		{name:catName},
    		function(data){
    			if(data.status != 0){
    				alert(data.message);
    			}
    			else{
    				$('#catTitle').val('');
    				$('.addCat').hide();
    				$option = '<option value="'+data.data.category.id+'">'+data.data.category.name+'</option>';
    				$($option).insertBefore($('#optionAdd'));
    				$('.cat').append($option);
    				categoryMap[data.data.category.id] = data.data.category.name;
    			}
    		},
    		'json'
    	)
    });
	
})

</script>