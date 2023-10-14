<div id="header" class="website row">
    <div class="operate-box">
        <div>导入</div>
        <div>导出</div>
        <div class="website-edit">编辑</div>
        <div type="button" data-bs-toggle="modal" data-bs-target="#websiteAdd">添加</div>
    </div>
    <ul class="website-menu">
        <li data-id='-1' class="active">最常使用</li>
        <li data-id='0'>最近使用</li>
        @foreach ($website_ctgr as $val)
            <li data-id={{ $val->id }}>{{ $val->title }}</li>
        @endforeach
    </ul>
    <ul class="website-items">
        @foreach ($website as $value)
            <li>
                <div class="remove-site" data-website-id={{ $value->id }}><i class="fas fa-times-circle"></i>
                </div>
                <a href="{{ $value->url }}" onclick="WebClick({{ $value->id }})" target="_blank" title="{{ $value->description }}">
                    <img src="{{ $value->icon_href }}" height="auto" width="auto">
                    <div class="url-info">
                        <div>
                            <strong class="overflow-clip">{{ $value->title }}</strong>
                        </div>
                        <p class="overflow-clip">{{ $value->description }}</p>
                    </div>
                </a>
            </li>
        @endforeach
        <li class="li-placeholder"></li>
        <li class="li-placeholder"></li>
        <li class="li-placeholder"></li>
        <li class="li-placeholder"></li>
        <li class="li-placeholder"></li>
        <li class="li-placeholder"></li>
    </ul>
    <!-- weisite add Modal -->
    <div class="modal fade " id="websiteAdd" data-bs-keyboard="false" tabindex="-1" aria-labelledby="websiteAddLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="websiteAddLabel">添加自定义网址</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row g-6 " id="websiteForm">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="col-md-6">
                            <label class="form-label">网址[URL]</label>
                            <input type="text" name='url' class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">分类[GENRE]</label>
                            <input type="text" name='genre' class="form-control" list="browsers" required>
                            <datalist id="browsers">
                                @foreach ($website_ctgr as $val)
                                    <option value="{{ $val->title }}">
                                @endforeach
                            </datalist>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">名称[TITLE]</label>
                            <input type="text" name='title' class="form-control" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">简介[DESCRIPTION]</label>
                            <textarea class="form-control" name='description' rows="3"></textarea>
                        </div>
                        <input type="hidden" name="icon_href">
                        <div class="col-12 modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                            <button class="btn btn-danger" id="websiteAddBtn" type="submit">添加</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // 网站编辑按钮显示与隐藏
    $('.website-edit').click(function() {
        $('.remove-site').toggle();
    })
    // 根据分类展示网站列表
    $('.website-menu li').click(function() {
        $(this).addClass("active").siblings().removeClass("active");
        var genre_id = $(this).data('id');
        $.get('/website/list/' + genre_id, function(d) {
            var str = ''
            for (var i = 0; i < d.length; i++) {
                str += "<li><div class='remove-site' data-website-id=" + d[i].id +
                    "><i class='fas fa-times-circle'></i></div><a href='" + d[i].url +
                    "' onclick='WebClick(" + d[i].id + ")' target='_blank' title='" + d[i].description +
                    "'><img src='" + d[i].icon_href +
                    "' height='auto' width='auto'><div class='url-info'><div><strong class='overflow-clip'>" +
                    d[i].title + "</strong></div><p class='overflow-clip'>" + d[i].description +
                    "</p></div></a></li>"
            }
            for (var i = 1; i <= 6; i++) {
                str += "<li class='li-placeholder'>";
            }
            $('.website-items').html(str);
        })
    })
    // 统计点击次数
    function WebClick(id) {
        $.get('/website/click/' + id);
    }
    // 删除网站
    $('.website-items').on('click', '.remove-site', function() {
        if (confirm("确定删除?")) {
            $.get('/website/delete/' + $(this).data('website-id'))
            $(this).parent().remove();
            toastr.success('删除成功');
        } else {
            toastr.info('已取消');
        }
    })
    // 根据url获取网站详情
    $("input[name='url']").blur(function() {
        if (this.value) {
            var url = this.value;
            url = url.split('/');
            url = url.length == 4 ? url[2] : url[0];
            $.get('/website/getinfo/' + url, function(d) {
                $("input[name='title']").val(d.title);
                $("textarea[name='description']").val(d.description);
                $("input[name='url']").val(d.url);
                $("input[name='icon_href']").val(d.icon_href); // 
            })
        }
    })
    // 上传保存网站
    $('#websiteForm').validate({
        submitHandler: function(form) {
            event.preventDefault();
            $.post('/website/store', $('#websiteForm').serializeArray(), function(d) {
                if (d.code == 200) {
                    toastr.success(d.msg);
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000)
                }
            })
        }
    })
</script>
