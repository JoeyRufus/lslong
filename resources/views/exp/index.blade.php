<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @include('z-head', ['css' => 'exp&blog', 'prism' => true, 'toastr' => true, 'validate' => true])
    <title>经历</title>
</head>

<body style="background-image: url('/images/bg-exp.jpg')">
    @include('z-leftMenu')
    <div class="container">
        <div class="row ">
            <div class="col-md-3 side">
                <div class="last">
                    <p>最新经历 <span onclick="MceShow()" class="fas fa-pencil-alt" title="新经历"></span></p>
                    @foreach ($last as $v)
                        <a href="/exp/detail/{{ $v->id }}" target="_blank">{{ $v->title }}</a>
                    @endforeach
                </div>
                <div class="label">
                    <p>经历标签</p>
                    <div class="label-panel">
                        <div data-id="0">全部经历({{ $count }})</div>
                        @foreach ($label as $v)
                            <div data-id="{{ $v->id }}">{{ $v->title }}({{ $v->experience_count }})</div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-md-9 main-panel">
                <div class="page" data-labelid=0>
                    @for ($i = 1; $i <= $exp->lastPage(); $i++)
                        <span class="{{ $i == 1 ? 'active' : '' }}">{{ $i }}</span>
                    @endfor
                </div>
                <div class="main">
                    @foreach ($exp as $v)
                        <div class="detail">
                            <h5><a href="/exp/detail/{{ $v->id }}" target='_blank'>{{ $v->title }}</a></h5>
                            <p class="overflow-clip overflow-clip-3"><span>摘要：</span>
                                {!! $v->content !!}
                            </p>
                            <div class="operate">
                                <a href="#" onclick="Del({{ $v->id }})"><i class="fas fa-trash"></i> 删除</a>
                                <a href="#" onclick="EditTiny('exp',{{ $v->id }})"><i class="fas fa-edit"></i> 编辑</a>
                                <a href="/exp/detail/{{ $v->id }}" target='_blank'><i class="fas fa-eye"></i> 详情</a>
                                <span>{{ $v->updated_at }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @include('z-tinymce', ['status' => 'exp'])
</body>
<script>
    function Del(id) {
        if (confirm("确定删除?")) {
            $.get('/exp/delete/' + id, function(d) {
                toastr.success('删除成功');
                setTimeout(function() {
                    window.location.href = '/exp';
                }, 1000)
            })
        } else {
            toastr.info('已取消');
        }
    }
    $('.page').on('click', 'span', function() {
        var page = $(this).text();
        var labelId = $('.page').data('labelid');
        GetExp(labelId, page);
        $(this).addClass('active').siblings().removeClass('active');
    })
    $('.label-panel div').click(function() {
        $.ajaxSettings.async = false;
        var page = GetExp($(this).data('id'));
        $.ajaxSettings.async = true;
        $('.page').data('labelid', $(this).data('id'))
        var str = "<span class='active'>1</span>"
        for (var i = 2; i <= page; i++) {
            str += "<span>" + i + "</span>"
        }
        $('.page').html(str);
        $(this).addClass('active').siblings().removeClass('active');
    })

    function GetExp(labelId, page = 1) {
        var last_page = 0;
        $.get('/exp/list/' + labelId + '/' + page, function(d) {
            var str = '';
            var data = d.data;
            for (var i = 0; i < data.length; i++) {
                str += "<div class='detail'><h5><a href='/exp/detail/" + data[i].id + "' target='_blank'>" + data[i].title +
                    "</a></h5><p class='overflow-clip overflow-clip-3'><span>摘要：</span> " + data[i].content +
                    "</p><div class='operate'><a href='#' onclick='Del(" + data[i].id +
                    ")'><i class='fas fa-trash'></i> 删除</a> <a href='#' onclick=\"EditTiny('exp'," + data[i].id +
                    ")\"><i class='fas fa-edit'></i> 编辑</a> <a href='/exp/detail/" + data[i].id +
                    "' target='_blank'><i class='fas fa-eye'></i> 详情</a><span>" +
                    data[i].updated_at + "</span> </div> </div>"
            }
            $('.main').html(str);
            last_page = d.last_page;
        })
        return last_page;
    }
</script>

</html>
