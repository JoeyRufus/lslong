<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/fontawesome.min.css">
    <link rel="stylesheet" href="/css/all.min.css">
    <link rel="stylesheet" href="/css/base.css">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/plugins/toastr/toastr.min.css">
    <link rel="stylesheet" href="/css/prism.css">
    <script src="/js/jquery-3.7.1.min.js"></script>
    <script src="/js/plugins/validate/jquery.validate.min.js"></script>
    <script src="/js/plugins/validate/messages_zh.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/plugins/toastr/toastr.min.js"></script>
    <script src="/js/tinymce.min.js"></script>
    <script src="/js/prism.js"></script>
    <script></script>
    <title>LSLONG</title>
</head>

<body>
    <div class="container-xxl">
        @include('website')
        @include('tinymce')
        <div id="main" class="row">
            @include('sideBar')
            <div class="col-4 shadow-sm">
                <nav>
                    <ul class="pagination pagination-sm justify-content-end" data-page="blog-0">
                        @for ($i = 1; $i <= $blog->lastPage(); $i++)
                            <li class="page-item {{ $i == 1 ? 'active' : '' }}" data-page="{{ $i }}"> <span
                                    class="page-link">{{ $i }}</span>
                            </li>
                        @endfor
                    </ul>
                </nav>
                <div id="mainContent">
                    @foreach ($blog as $v)
                        <div class="item-detail">
                            <div class="title">{{ $v->title }}</div>
                            <p class="overflow-clip overflow-clip-2"><span>摘要：</span>
                                {!! $v->content !!}
                            </p>
                            <div class="detail-operate">
                                <div data-id="d-blog-{{ $v->id }}">删除</div>
                                <div class="add-btn" data-id="e-blog-{{ $v->id }}">编辑</div>
                                <div data-id="i-blog-{{ $v->id }}">详情</div>
                                <span>{{ $v->updated_at }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div id="itemDetail" class="col-6 shadow-sm">
                <p>点击左侧，查看详情……</p>
                <div>
                    <img style="width: 100%" src="/images/loading.jpg" alt="">
                </div>
            </div>
        </div>
        <div id="footer"></div>
    </div>
</body>
<script>
    function ToBlog() {
        BlogHtml();
        GetList('blog', '0');
        $('.label-items .active').removeClass('active');
    }

    function ToExp() {
        ExpHtml();
        GetList('exp', '0');
        $('.genre-items .active').removeClass('active');
    }

    function GetList(s, id, page = 1) {
        var url = '/' + s + '/list/' + id + '/' + page;
        $.get(url, function(d) {
            var str = "";
            var data = d.data;
            for (var i = 0; i < data.length; i++) {
                str += "<div class='item-detail'><div class='title'>" + data[i].title +
                    "</div><p class='overflow-clip overflow-clip-2'><span>摘要：</span>" + data[i].content +
                    "</p><div class='detail-operate'><div data-id='d-" + s + "-" + data[i].id +
                    "'>删除</div><div class='add-btn' data-id='e-" + s + "-" + data[i].id +
                    "'>编辑</div><div data-id='i-" + s + "-" + data[i].id +
                    "'>详情</div><span>" + data[i].updated_at + "</span></div></div>"
            }
            var status = s + '-' + id;
            console.log(d);
            if ($('.pagination').data('page') != status) {
                $('.pagination').data('page', status);
                var navHtml = ''
                if (d.last_page > 1) {
                    for (var i = 1; i <= d.last_page; i++) {
                        navHtml += "<li class='page-item' data-page='" + i + "'> <span class='page-link'>" + i + "</span> </li>"
                    }
                }
                $('.pagination').html(navHtml);
            }
            $('#mainContent').html(str);
        })
    }

    function GetDtlInfo(s, id) {
        $.get('/' + s + '/dtl/' + id, function(d) {
            var str = "<p>" + d.title + "</p><div> " + d.content + "</div>";
            $('#itemDetail').html(str)
            Prism.highlightAll()
        })
    }
    $('.pagination').on('click', '.page-item', function() {
        var s = $('.pagination').data('page').split('-');
        var page = $(this).data('page');
        GetList(s[0], s[1], page);
        $(this).addClass('active').siblings().removeClass('active');
    })
    $('#mainContent').on('click', '.detail-operate div', function() {
        var data = $(this).data('id').split('-');
        switch (data[0]) {
            case 'i': //infomation
                GetDtlInfo(data[1], data[2])
                break;
            case 'e': //edit
                EditTiny(data[1], data[2]);
                break;
            case 'd': //delete
                var item = $(this).parents('.item-detail')[0];
                if (confirm("确定删除?")) {
                    $.get('/' + data[1] + '/delete/' + data[2], function(d) {
                        item.remove();
                        toastr.success('删除成功');
                    })
                } else {
                    toastr.info('已取消');
                }
                break;
        }
    })
</script>

</html>
