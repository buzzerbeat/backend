{{set title='评论列表'}}
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
		{{if !empty($title)}}
			<h4>{{$title}}</h4>
		{{/if}}
			<div class="form-inline margin-bottom-10">
				<div class="btn-group" id="desc">
					<button class="btn btn-info active" desc="desc">倒序</button>
					<button class="btn btn-default" desc="asc">正序</button>
				</div>
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
var url = 'data', pagesize = 30, keyword='', desc = 'desc';

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
    $.getJSON(url+'?keyword='+keyword+'&per-page='+pagesize+'&desc='+desc+'&page='+curPage+'&vid='+{{$vid}}, function(data){
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
	var r = '<tr id="commentItem'+v.id+'">';
	
	r += '<td class="col-md-1">';
	r += v.id;
	r += '</td>';
	
	r += '<td class="col-md-6">';
	r += v.content;
	r += '</td>';
	
	r += '<td class="col-md-3">';
	r += '<img src="{{$imgUrl}}/thumb/60/60/0/'+v.userAvatar.sid+'/'+v.userAvatar.md5+v.userAvatar.dotExt+'"/>&nbsp;&nbsp;'+v.userName+'&nbsp;&nbsp;发布于'+v.elapsedTime;
	r += '</td>';
	
	r += '<td>';
	r += '<button class="btn btn-danger del" cid="'+v.id+'">删除</button>';
	r += '<a class="btn btn-link" href="../mv-video-admin/list?id='+v.item_id+'" target="_blank">所属视频</a>';
	r += '</td>';
	
	r += '</tr>';
	
	return r;
}

function buildTh(){
    var r = '<tr><th>ID</th><th>内容</th><th>用户</th><th>操作</th>';
    r += '</tr>';
    
    return r;
}

function buildTable(title, lines) {
    var r = '<table class="table table-bordered "><thead>'+title+'</thead><tbody>'+lines+'</tbody></div>';
    return r;
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
    
    $(document).on('click', '.del', function(){
    	if(!confirm('确定删除该评论？')){
    		return false;
    	}
    	var cid = $(this).attr('cid');
    	$.post(
    		'del-comment',
    		{id:cid},
    		function(data){
    			if(data.status != 0){
    				alert(data.message);
    			}
    			else{
    				$('#commentItem'+cid).remove();
    			}
    		},
    		'json'
    	);
    });
    
	
	
})

</script>