{{set title='关键词列表'}}
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="form-inline margin-bottom-10">
				<div class="btn-group" id="desc">
					<button class="btn btn-info active" desc="desc">倒序</button>
					<button class="btn btn-default" desc="asc">正序</button>
				</div>
				<input class="form-control" type="text" id="keyword" placeholder="名称查询"/>
				<button class="btn btn-primary" id="filterBtn">搜索</button>
				<button class="btn btn-default" id="resetBtn">重置</button>
				<button class="btn btn-success" id="createBtn">增加</button>
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
			<div class="modal fade" id="keywordModal" tabindex="-1" role="dialog" aria-labelledby="keywordModalLabel">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="keywordModalLabel">增加关键词</h4>
						</div>
						<div class="modal-body">
							<div class="form">
								<div class="form-group">
									<label>关键词</label>
									<input type="text" placeholder="必填" class="form-control" id="keyword_name"/>
								</div>
								<div class="checkbox">
									<label>
										<input type="checkbox" class="" id="keyword_deal"/> 是否处理
									</label>
								</div>
								<div class="radio deal_div">
									<label>
										<input type="radio" name="deal_type" class="deal_type" id="deal_filter"/> 过滤
									</label>
									<label>
										<input type="radio" name="deal_type" class="deal_type" id="deal_replace"/> 替换
									</label>
								</div>
								<div class="form-group replace_div deal_div">
									<input type="text" placeholder="对应标签" class="form-control" id="keyword_tag"/>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
							<button type="button" class="btn btn-primary" id="saveKeywordBtn" kid="0">保存</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div id="Pagination" class="pagination"></div>
		</div>
	</div>
</div>
<script type="text/javascript">
var url = 'keyword-data', pagesize = 20, keyword='', desc = 'desc';

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
    $.getJSON(url+'?keyword='+keyword+'&per-page='+pagesize+'&desc='+desc+'&page='+curPage, function(data){
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
	var r = '<tr>';
	
	r += '<td class="col-md-1">';
	r += v.id;
	r += '</td>';
	r += '<td class="col-md-9">';
	r += '<div id="keywordItem'+v.id+'">';
	r += buildMain(v);
	r += '</div>';
	r += '</td>';
	r += '<td class="col-md-2">';
	r += '<button class="btn col-md-5 btn-success edit" kid="'+v.id+'">修改</button>';
	//r += '<button class="btn col-md-5 btn-danger pull-right" kid="'+v.id+'">过滤</button>';
	if(v.is_filter == 0){
		r += '<button class="btn col-md-5 btn-danger filter pull-right" kid="'+v.id+'" filter="1">过滤</button>';
	}
	else{
		r += '<button class="btn col-md-5 btn-success filter pull-right" kid="'+v.id+'" filter="0">取消过滤</button>';
	}
	r += '</td>';
	r += '</tr>';
	
	return r;
}

function buildTh(){
    var r = '<tr><th>ID</th><th>信息</th><th>操作</th>';
    r += '</tr>';
    
    return r;
}

function buildTable(title, lines) {
    var r = '<table class="table table-bordered "><thead>'+title+'</thead><tbody>'+lines+'</tbody></div>';
    return r;
}

function buildMain(v){
	var r = '';
	r += '<p>'+v.name;
	if(v.is_filter == 1){
		r += '（过滤）';
	}
	else if(v.tag != null){
		r += '（对应标签：'+v.tag.name+'）';
	}
	r += '</p>';

	return r;
}

function resetModal(modalName){
	if(modalName == 'keywordModal'){
		$('#keyword_name').val('');
		$('#keyword_deal').prop('checked', false);
		$('.deal_div input').prop('checked', false);
		$('.deal_div').hide();
		$('.replace_div input').val('');
		$('.replace_div').hide();

		$('#saveKeywordBtn').attr('kid', 0);
	}
	$('#'+modalName).modal('hide');
}

function refreshDetail(keywordId){
	$.getJSON('keyword-data?id='+keywordId, function(data){
		$('#keywordItem'+keywordId).html(buildMain(data.items[0]));
	});
}

function buildModal(keywordId){
	$.getJSON('keyword-data?id='+keywordId, function(data){
		var o = data.items;
		if(o.length == 0){
			alert('没有对应数据');
			return false;
		}
		$('#keywordModalLabel').html('修改关键词'+o[0].name);
		$('#keyword_name').val(o[0].name);
		if(o[0].is_filter != 0 || o[0].tag != null){
			$('.deal_div').show();
			$('#keyword_deal').prop('checked', true);
			if(o[0].is_filter != 0){
				$('#deal_filter').prop('checked', true);
			}
			else if(o[0].tag != null){
				$('#deal_replace').prop('checked', true);
				$('.replace_div').show();
				$('#keyword_tag').val(o[0].tag.name);
			}
		}
		else{
			$('#keyword_deal').prop('checked', false);
		}
		$('#saveKeywordBtn').attr('kid', o[0].id);
		$('#keywordModal').modal('show');
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
    
    $(document).on('click', '.delTagRel', function(){
    	if(!confirm('确定删除该标签？')){
    		return false;
    	}
    	var vid = $(this).attr('vid'), tid = $(this).attr('tid');
    	$.post(
    		'deltag',
    		{mv_video_id:vid, mv_tag_id:tid},
    		function(data){
    			if(data.status != 0){
    				alert(data.message);
    			}
    			else{
    				$('#videoItem'+iid).remove();
    			}
    		},
    		'json'
    	);
    });
    
    $('#createBtn').click(function(){
    	$('#keywordModal').modal('show');
    });
    
    $('#saveKeywordBtn').click(function(e){
		e.stopPropagation();
		if($(this).hasClass('active')){
			return false;
		}
		var $this = $(this), kid = $this.attr('kid'), keyword = $('#keyword_name').val(), 
			is_filter = $('#deal_filter').prop('checked')?1:0, 
			tag = $('#keyword_tag').val();
		if(keyword == ''){
			alert('关键词不能为空');
			$('#keyword_name').focus();
			return false;
		}
		if($('#deal_replace').prop('checked') && tag == ''){
			alert('请输入转化标签');
			$('#keyword_tag').focus();
			return false;
		}
		
		$this.addClass('active');
		$.ajax({
	    	url:'save-keyword',
	    	type:'POST',
	    	timeout:3000,
	    	data:{id:kid, name:keyword, is_filter:is_filter, tag:tag},
	    	dataType:'json',
	    	success:function(data){
	    	    $this.removeClass('active');
	    	    if(data.status == 0){
	    	        resetModal('keywordModal');
	    	        pageselectCallback(0, null);
	    	    }
	    	    else{
	    	        alert(data.message)
	    	    }
	    	},
	    	error:function(data){
	    	    $this.removeClass('active');
	    	    alert('请重试!');
	    	}
	    });
	});
	
	
	$(document).on('click', '.filter', function(){
		var $this = $(this), kid = $(this).attr('kid'), filter = $this.attr('filter');
		$.post(
			'keyword-filter',
			{id:kid, is_filter:filter},
			function(data){
				if(data.status == 0){
					$this.attr('filter', filter==1?0:1).removeClass(filter==1?'btn-danger':'btn-success').addClass(filter==0?'btn-danger':'btn-success').html(filter==1?'取消过滤':'过滤');
					refreshDetail(kid);
				}
				else{
					alert(data.message);
				}
			},
			'json'
		);
	});
	
	$(document).on('click', '#keyword_deal', function(){
		if($(this).prop('checked')){
			$('.deal_div').show();
		}
		else{
			$('.deal_div input:radio').prop('checked', false);
			$('.deal_div input:text').val('');
			$('.deal_div').hide();
		}
	});
	
	$(document).on('click', '.deal_type', function(){
		if($(this).attr('id').indexOf('replace') != -1){
			$('.replace_div').show();
		}
		else{
			$('.replace_div').val('').hide();
		}
	});
	
	$(document).on('click', '.edit', function(){
		buildModal($(this).attr('kid'));
	});
	
	$('.modal').on('hidden.bs.modal', function (e) {
  		resetModal($(this).attr('id'));
	});
	
})

</script>