{{set title='标签列表'}}
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
			<div class="modal fade" id="tagModal" tabindex="-1" role="dialog" aria-labelledby="tagModalLabel">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="tagModalLabel">增加标签</h4>
						</div>
						<div class="modal-body">
							<div class="form">
								<label>名称</label>
								<input type="text" placeholder="必填！" class="form-control" id="tag_name"/>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
							<button type="button" class="btn btn-primary" id="saveTagBtn" tid="0">保存</button>
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
var url = 'tag-data', pagesize = 10, keyword='', desc = 'desc';

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
    $.getJSON(url+'?keyword='+keyword+'&pre-page='+pagesize+'&desc='+desc+'&page='+curPage, function(data){
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
	r += v.id+'（'+v.sid+'）';
	r += '</td>';
	r += '<td class="col-md-11">';
	r += '<div id="tagItem'+v.id+'">';
	r += buildMain(v);
	r += '</div>';
	r += '<div class="form-inline buildDiv">';
	r += '<input type="text" class="form-control add-rel" oninput="buildBox('+v.id+', this.value);" id="addRelInput'+v.id+'" rid="0"/>';
	r += '<button class="btn btn-primary addRel" tid="'+v.id+'">增加</button>';
	r += '</div>';
	r += '</td>';
	r += '</tr>';
	
	return r;
}

function buildTh(){
    var r = '<tr><th>ID</th><th>名称</th>';
    r += '</tr>';
    
    return r;
}

function buildTable(title, lines) {
    var r = '<table class="table table-bordered "><thead>'+title+'</thead><tbody>'+lines+'</tbody></div>';
    return r;
}

function buildMain(v){
	var r = '';
	r += '<h4>'+v.name+'&nbsp;&nbsp;（'+v.count+'）&nbsp;';
	r += '<button class="btn btn-danger btn-sm delTag" tid="'+v.id+'"><i class="glyphicon glyphicon-trash"></i></button>';
	r += '&nbsp;&nbsp;关键词：';
	for(var i in v.keywords){
		r += '&nbsp;&nbsp;'+v.keywords[i].name;
	}
	r += '</h4>';
	var tagLen = v.tags.length;
	for(var i in v.tags){
		var o = v.tags[i];
		r += '<div class="tag-rel-item margin-bottom-10">';
		r += '<div class="btn-group">';
		r += '<button class="btn btn-default" rid="'+o.tag.id+'">'+o.tag.name+'（'+o.tag.count+'）</button>';
		r += '<button class="btn btn-danger del" tid="'+v.id+'" rid="'+o.id+'"><i class="glyphicon glyphicon-trash"></i></button>';
		r += '</div>';
		//r += '<div class="btn-toolbar tag-btn">';
		//r += '<div class="btn-group">';
		//r += '<button class="btn btn-default btn-xs rank left '+(i == 0 ? 'disabled' : '')+'" tid="'+v.id+'" rid="'+o.id+'"><i class="glyphicon glyphicon-chevron-left"></i></button>';
		//r += '</div>';
		//r += '<div class="btn-group">';
		//r += '<button class="btn btn-default btn-xs rank right '+(i == tagLen-1 ? 'disabled' : '')+'" tid="'+v.id+'" rid="'+o.id+'"><i class="glyphicon glyphicon-chevron-right"></i></button>';
		//r += '</div>';
		//r += '<div class="btn-group">';
		//r += '<button class="btn btn-danger btn-xs del" tid="'+v.id+'" rid="'+o.id+'"><i class="glyphicon glyphicon-trash"></i></button>';
		//r += '</div>';
		//r += '</div>';
		r += '</div>';
	}
	return r;
}

function buildBox(tagid, value){
	if($('.inputRel').length == 0){
		var r = '<div class="inputRel">';
		
		r += '</div>';
		
		$('#addRelInput'+tagid).after(r);
	}
	$.getJSON('tag-relation-data?keyword='+value+'&tagid='+tagid+'&fields=id,name', function(rel){
		var m = '<ul class="">';
		for(var i in rel.items){
			m += '<li class="relItem" rid="'+rel.items[i].id+'" tid="'+tagid+'">'+rel.items[i].name+'</li>';
		}
		
		m += '</ul>';
		$('.inputRel').html(m);
		
	});
	
	
}

function resetModal(modalName){
	if(modalName == 'tagModal'){
		$('#tag_name').val('');

		$('#saveTagBtn').attr('tid', 0);
	}
	$('#'+modalName).modal('hide');
}

function refreshDetail(tagId){
	$.getJSON('tag-data?id='+tagId, function(data){
		$('#tagItem'+tagId).html(buildMain(data.items[0]));
	});
}

function buildModal(tagId){
	$.getJSON('tag-data?id='+tagId, function(data){
		var o = data.items;
		if(o.length == 0){
			alert('没有对应数据');
			return false;
		}
		$('#tagModalLabel').html('修改关键词'+o[0].name);
		$('#tag_name').val(o[0].name);
		
		$('#saveTagBtn').attr('kid', o[0].id);
		$('#tagModal').modal('show');
	});
}

function tagDetailRefresh(tid){
	$.getJSON('tag-data?id='+tid, function(v){
		var r = '';
		r += buildMain(v.items[0]);
		
		$('#tagItem'+tid).html(r);
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
    	keyword = $('#keyword').val();
    	pageselectCallback(0, null);
    });
    
    $('#resetBtn').click(function(){
    	$('#keyword').val('');
    	keyword = '';
    	pageselectCallback(0, null);
    });
    
    $(document).on('click', '.delTag', function(){
    	if(!confirm('确定删除该标签')){
			return false;
		}
		var tagId = $(this).attr('tid');
		$.post(
			'tag-delete',
			{id:tagId},
			function(data){
				if(data.status == 0){
					pageselectCallback(0, null);
				}
				else{
					alert(data.message);
				}
			},
			'json'
		);
    });
    
    $('#createBtn').click(function(){
    	$('#tagModal').modal('show');
    });
    
    $('#saveTagBtn').click(function(e){
		e.stopPropagation();
		if($(this).hasClass('active')){
			return false;
		}
		var $this = $(this), tid = $this.attr('tid'), name = $('#tag_name').val();
		if(name == ''){
			alert('名称不能为空');
			$('#tag_name').focus();
			return false;
		}
		$this.addClass('active');
		$.ajax({
	    	url:'tag-save',
	    	type:'POST',
	    	timeout:3000,
	    	data:{id:tid, name:name},
	    	dataType:'json',
	    	success:function(data){
	    	    $this.removeClass('active');
	    	    if(data.status == 0){
	    	        resetModal('tagModal');
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
	
	
	$(document).on('click', '.del', function(){
		var rid = $(this).attr('rid'), tid = $(this).attr('tid');
		if(!confirm('确认删除该关联？')){
			return false;
		}
		$.post(
			'tag-remove-rel',
			{id:rid},
			function(data){
				if(data.status == 0){
					//@todo 刷新单个记录
					tagDetailRefresh(tid);
				}
				else{
					alert(data.message);
				}
			},
			'json'
		);
	});
	
	$(document).on('click', '.addRel', function(){
        var tid = $(this).attr('tid'), $input = $('#addRelInput'+tid), relName = $input.val(), rid = $input.attr('rid');
        if(relName == ''){
            alert('请确认该关联标签是否存在');
            $('#addRelInput'+tid).focus();
            return false;
        }
        $.post(
            'tag-add-rel',
            {tag_id:tid, rel_tag_id:rid, rel_name:relName},
            function(data){
                if(data.status == 0){
                    tagDetailRefresh(tid);
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
	
	$(document).on('click', '.edit', function(){
		buildModal($(this).attr('kid'));
	});
	
	$('.modal').on('hidden.bs.modal', function (e) {
  		resetModal($(this).attr('id'));
	});
	
})

</script>