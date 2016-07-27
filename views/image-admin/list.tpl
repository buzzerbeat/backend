{{set title='图片列表'}}
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="form-inline margin-bottom-10">
				<div class="btn-group" id="desc">
					<button class="btn btn-info active" desc="desc">倒序</button>
					<button class="btn btn-default" desc="asc">正序</button>
				</div>
				<!--<div class="btn-group" id="status">
					<button class="btn btn-info active" status="">正常</button>
					<button class="btn btn-default" status="99">删除</button>
				</div>-->
				<input class="form-control" type="number" id="imgid" placeholder="图片id查询"/>
				<input class="form-control" type="text" id="imgsid" placeholder="图片sid查询"/>
				<button class="btn btn-primary" id="filterBtn">搜索</button>
				<button class="btn btn-default" id="resetBtn">重置</button>
				<button class="btn btn-success uploadImage">新增<input type="file" id="ImageForm" name="ImageForm[imageFiles][]" onchange="fileUpload();" accept="image/jpeg,image/png,image/gif" multiple="multiple"/></button>
				<!--<button class="btn btn-success" id="uploadMultiple">多图</button>-->
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
var url = 'data', pagesize = 10, statusMap = [], imgid=0, imgsid='', desc = 'desc', uploadArr = [];
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
    $.getJSON(url+'?id='+imgid+'&sid='+imgsid+'&pre-page='+pagesize+'&desc='+desc+'&page='+curPage, function(data){
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
	var r = '<tr id="imgItem'+v.id+'">';
	r += '<td>';
	r += v.id+'<br/>'+v.sid;
	r += '</td>';
	r += '<td>';
	r += 'PATH:'+v.file_path;
	r += '<br/>增加时间：'+v.addTime+'&nbsp;&nbsp;更新时间：'+v.updateTime;
	r += '<br/>宽：'+v.width+'&nbsp;&nbsp;高：'+v.height;
	r += '</td>';
	r += '<td>';
	r += '<a href="{{$imgUrl}}/thumb/0/0/'+v.sid+'/'+v.md5+v.dotExt+'" target="_blank"><img src="{{$imgUrl}}/thumb/300/200/0/'+v.sid+'/'+v.md5+v.dotExt+'"/></a>';
	r += '</td>';
	r += '<td>';
	r += '<button class="btn btn-block btn-danger del" iid="'+v.id+'">删除</button>';
	r += '<a href="{{$imgUrl}}/thumb/0/0/'+v.sid+'/'+v.md5+v.dotExt+'" class="btn btn-block btn-link" target="_blank">查看</a>';
	r += '</td>';
	
	r += '</tr>';
	
	return r;
}

function buildTh(){
    var r = '<tr><th>ID</th><th>信息</th><th>预览</th><th>操作</th>';
    r += '</tr>';
    
    return r;
}

function buildTable(title, lines) {
    var r = '<table class="table table-bordered "><thead>'+title+'</thead><tbody>'+lines+'</tbody></div>';
    return r;
}

function fileUpload(){
    var oFiles = $('#ImageForm')[0].files, fileLen = $('#ImageForm')[0].files.length, finishNum = 0, uploadArr = [];
    for(var i=0; i<fileLen; i++){
		var oReader = new FileReader();
		oReader.onload = function(e){
		    finishNum++;
		    uploadArr.push('ImageForm');
		    if(finishNum == fileLen){
		    	uploadImage(uploadArr);
		    }
		};
		oReader.readAsDataURL(oFiles[i]);
    }
}

function uploadImage(uploadArr){
    $.ajaxFileUpload({
		url:'upload',
		secureuri:false,
		fileElementId:uploadArr,
		dataType: 'json',
		data:{num:uploadArr.length, fileName:'ImageForm'},
		success: function (data, status){
		    alert(data.message);
			if(data.status == 0){
			    pageselectCallback(0, null);
			}
		},
		error:function(data,status,e){
			alert('请重新尝试');
		}
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
    	imgid = $('#imgid').val();
    	imgsid = $('#imgsid').val();
    	pageselectCallback(0, null);
    });
    
    $('#resetBtn').click(function(){
    	$('#imgid').val('');
    	$('#imgsid').val('');
    	imgid = 0;
    	imgsid = '';
    	pageselectCallback(0, null);
    });
    
    $(document).on('click', '.del', function(){
    	if(!confirm('确定删除该图片？')){
    		return false;
    	}
    	var iid = $(this).attr('iid');
    	$.post(
    		'delete',
    		{id:iid},
    		function(data){
    			if(data.status != 0){
    				alert(data.message);
    			}
    			else{
    				$('#imgItem'+iid).remove();
    			}
    		},
    		'json'
    	);
    });
    
    {{*var uploader = new plupload.Uploader({ //创建实例的构造方法 
	    runtimes: 'html5,flash,silverlight,html4', 
	    //上传插件初始化选用那种方式的优先级顺序 
	    browse_button: 'uploadMultiple', 
	    // 上传按钮 
	    url: "{{$adminUrl}}image-admin/upload", 
	    filters: { 
	        max_file_size: '500kb', 
	        //最大上传文件大小（格式100b, 10kb, 10mb, 1gb） 
	        mime_types: [ //允许文件上传类型 
	        { 
	            title: "files", 
	            extensions: "jpg,jpeg,png,gif" 
	        }] 
	    }, 
	    multi_selection: true, 
	    //true:ctrl多文件上传, false 单文件上传 
	    init: { 
	        FilesAdded: function(up, files) { //文件上传前 
	        	uploader.start(); 
	            //uploader.destroy();
	        }, 
	        UploadProgress: function(up, file) { //上传中
	            
	        }, 
	        FileUploaded: function(up, file, info) { //文件上传成功的时候触发 
	            pageselectCallback(0, null);
	        }, 
	        Error: function(up, err) { //上传出错的时候触发 
	            alert(err.message); 
	        } 
	    } 
	}); 
	uploader.init();*}}
	
})

</script>