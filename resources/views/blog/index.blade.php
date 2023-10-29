<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @include('z-head', ['css' => 'exp&blog', 'prism' => true, 'toastr' => true, 'validate' => true])
    <title>博客</title>
</head>

<body>
    @include('z-leftMenu')
    <div class="container">
        <div class="row ">
            <div class="col-md-3 side">
                <div class="search-panel">
                    <input class="search" type="text" placeholder="搜索...">
                    <i class="fas fa-search search-btn"></i>
                </div>
                <div class="last">
                    <p>最新随笔 <span onclick="MceShow()" class="fas fa-pencil-alt" title="新随笔"></span></p>
                    @foreach ($last as $v)
                        <a href="/blog/detail/{{ $v->id }}">{{ $v->title }}</a>
                    @endforeach
                </div>
                <div class="genre">
                    <p>随笔分类</p>
                    <div data-id="0">全部随笔({{ $count }})</div>
                    @foreach ($genre as $v)
                        <div data-id="{{ $v->id }}">{{ $v->title }}({{ $v->blog_count }})</div>
                    @endforeach
                </div>
            </div>
            <div class="col-md-9 main-panel">
                <div class="page" data-genreid=0>
                    @for ($i = 1; $i <= $blog->lastPage(); $i++)
                        <span class="{{ $i == 1 ? 'active' : '' }}">{{ $i }}</span>
                    @endfor
                </div>
                <div class="main">
                    @foreach ($blog as $v)
                        <div class="detail">
                            <h5><a href="/blog/detail/{{ $v->id }}" target='_blank'>{{ $v->title }}</a></h5>
                            <p class="overflow-clip overflow-clip-3"><span>摘要：</span>
                                {!! $v->content !!}
                            </p>
                            <div class="operate">
                                <a href="#" onclick="Del({{ $v->id }})"><i class="fas fa-trash"></i> 删除</a>
                                <a href="#" onclick="EditTiny('blog',{{ $v->id }})"><i class="fas fa-edit"></i> 编辑</a>
                                <a href="/blog/detail/{{ $v->id }}" target='_blank'><i class="fas fa-eye"></i> 详情</a>
                                <span>{{ $v->updated_at }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
    @include('z-tinymce', ['status' => 'blog'])
</body>
<script>
    $('.search').focus(function() {
        $(this).css('width', '100%');
        setTimeout(function() {
            $('.search-btn').fadeIn()
        }, 500);
    })

    function TitleSearch() {
        var val = $('.search').val();
        if (val) {
            var url = '/blog/search/' + val;
            $.get(url, function(d) {
                RenderDetail(d);
                $('.page').html('');
            })
        }
    }

    $('.search-btn').click(function() {
        TitleSearch();
    })

    document.onkeydown = function(e) {
        var ev = document.all ? window.event : e;
        if (ev.keyCode == 13) {
            TitleSearch();
        }
    }

    function Del(id) {
        if (confirm("确定删除?")) {
            $.get('/blog/delete/' + id, function(d) {
                toastr.success('删除成功');
                setTimeout(function() {
                    window.location.href = '/blog';
                }, 1000)
            })
        } else {
            toastr.info('已取消');
        }
    }

    $('.page').on('click', 'span', function() {
        var page = $(this).text();
        var genreId = $('.page').data('genreid');
        GetBlog(genreId, page);
        $(this).addClass('active').siblings().removeClass('active');
    })

    $('.genre div').click(function() {
        $.ajaxSettings.async = false;
        var page = GetBlog($(this).data('id'));
        $.ajaxSettings.async = true;
        $('.page').data('genreid', $(this).data('id'))
        var str = "<span class='active'>1</span>"
        for (var i = 2; i <= page; i++) {
            str += "<span>" + i + "</span>"
        }
        $('.page').html(str);
    })

    function GetBlog(genreId, page = 1) {
        var last_page = 0;
        $.get('/blog/list/' + genreId + '/' + page, function(d) {
            var data = d.data;
            RenderDetail(data);
            last_page = d.last_page;
        })
        return last_page;
    }

    function RenderDetail(data) {
        var str = '';
        for (var i = 0; i < data.length; i++) {
            str += "<div class='detail'><h5><a href='/blog/detail/" + data[i].id + "' target='_blank'>" + data[i].title +
                "</a></h5><p class='overflow-clip overflow-clip-3'><span>摘要：</span> " + data[i].content +
                "</p><div class='operate'><a href='#' onclick='Del(" + data[i].id +
                ")'><i class='fas fa-trash'></i> 删除</a> <a href='#' onclick=\"EditTiny('blog'," + data[i].id +
                ")\"><i class='fas fa-edit'></i> 编辑</a> <a target='_blank' href='/blog/detail/" + data[i].id +
                "'><i class='fas fa-eye'></i> 详情</a><span>" +
                data[i].updated_at + "</span> </div> </div>"
        }
        $('.main').html(str);
    }
</script>

</html>
