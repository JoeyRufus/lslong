<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @include('z-head')
    <link rel="stylesheet" href="/css/plugins/toastr/toastr.min.css">
    <script src="/js/plugins/validate/jquery.validate.min.js"></script>
    <script src="/js/plugins/validate/messages_zh.js"></script>
    <script src="/js/plugins/toastr/toastr.min.js"></script>
    <title>网址</title>
</head>
<style>
    body {
        background-image: url('/images/web-bg.jpg');
    }

    .operate {
        position: fixed;
        right: 25px;
        bottom: 25px;
        font-size: 2rem;
        width: 30px;
        text-align: center
    }

    .operate i {
        cursor: pointer;
    }

    .menu {
        background: rgba(0, 0, 0, .5);
        position: fixed;
        z-index: 9;
        top: 0;
        width: 100%;
        display: flex;
        padding: 5px 0;
        justify-content: space-evenly;
        color: #fff;
    }

    .menu div {
        padding: 10px;
        cursor: pointer;
        border-radius: 10px;
        font-size: 1.2rem;
    }

    .menu div:hover {
        background: #f1404b;
    }

    .panel {
        margin-top: 20px;
        background: rgba(0, 0, 0, .2);
        padding: 5px 5px 30px;
        border-radius: 10px;
    }

    .sites {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-evenly;
    }

    .site-placeholder {
        width: 250px;
        height: 0;
    }

    .site {
        width: 250px;
        margin-top: 15px;
        position: relative;
    }

    .site a {
        display: flex;
        padding: 15px;
        background: rgba(255, 255, 255, .7);
        border-radius: 5px;
    }

    .site img {
        width: 20%;
    }

    .site i {
        position: absolute;
        right: 0;
        top: 0;
        color: #f1404b;
        display: none;
    }

    .url-info {
        padding-left: 10px;
        width: 80%;
    }

    .url-info strong {
        line-height: 23px;
    }
</style>

<body>
    @include('z-leftMenu')
    <div class="operate">
        <i class="fas fa-file-upload" title="导入"></i>
        <i class="fas fa-file-download" title="导出"></i>
        <i class="fas fa-file-medical" type="button" data-bs-toggle="modal" data-bs-target="#websiteAdd" title="新增"></i>
        <i class="fas fa-edit edit" title="编辑"></i>
        <i class="fas fa-chevron-circle-up r-top" title="返回顶部"></i>
    </div>
    <div class="menu">
        @foreach ($website as $v)
            <div data-id="panel-{{ $v->id }}">{{ $v->title }}</div>
        @endforeach
    </div>
    <div style="height: 55px"></div>
    <div class="container">
        <div class="row">
            <div class="col-12">
                @foreach ($website as $val)
                    <div class="panel panel-{{ $val->id }}">
                        <i class="fas fa-folder"> {{ $val->title }}</i>
                        <div class="sites">
                            @foreach ($val->website as $v)
                                <div class="site">
                                    <i class="fas fa-times-circle" data-id={{ $v->id }}></i>
                                    <a href="{{ $v->url }}" onclick="WebClick({{ $v->id }})" target="_blank" title="{{ $v->description }}">
                                        <img src="{{ $v->icon_href }}" onerror="this.src='/images/error.jpg'" height="auto" width="auto">
                                        <div class="url-info">
                                            <strong class="overflow-clip">{{ $v->title }}</strong>
                                            <span class="overflow-clip">{{ $v->description }}</span>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                            <div class="site-placeholder"></div>
                            <div class="site-placeholder"></div>
                            <div class="site-placeholder"></div>
                            <div class="site-placeholder"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
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
                                @foreach ($website as $val)
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
</body>
<script>
    $('.menu div').click(function() {
        var g = $(this).data('id');
        var top = $('.' + g).offset().top;
        top = top - 60;
        $("html,body").animate({
            scrollTop: top
        }, 200);
    })
    $('.r-top').click(function() {
        $("html,body").animate({
            scrollTop: 0
        }, 200);
    })
    // 网站编辑按钮显示与隐藏
    $('.edit').click(function() {
        $('.site i').toggle();
    })
    // 统计点击次数
    function WebClick(id) {
        $.get('/website/click/' + id);
    }
    // 删除网站
    $('.site i').click(function() {
        if (confirm("确定删除?")) {
            $.get('/website/delete/' + $(this).data('id'))
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
            url = url.replace('https://', '');
            url = url.replace('http://', '');
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

</html>
