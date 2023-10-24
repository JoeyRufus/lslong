<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @include('z-head', ['css' => 'home'])
    <title>主页</title>
</head>

<body>
    @include('z-leftMenu')
    <div class="container main">
        <div class="website">
            <p class="title">最常使用 <a class="fas fa-bars" href="/website"></a></p>
            <div class="sites">
                @foreach ($website as $v)
                    <div class="site">
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
        <div class="blog">
            <p class="title">最新随笔<a class="fas fa-bars" href="/blog"></a></p>
            @foreach ($blog as $v)
                <div class="detail">
                    <h5><a href="/blog/detail/{{ $v->id }}" target="_blank">{{ $v->title }}</a></h5>
                    <p class="overflow-clip overflow-clip-3"><span>摘要：</span>
                        {!! $v->content !!}
                    </p>
                </div>
            @endforeach
        </div>
        <div class="exp">
            <p class="title">最新经历<a class="fas fa-bars" href="/exp"></a></p>
            @foreach ($exp as $v)
                <div class="detail">
                    <h5><a href="/exp/detail/{{ $v->id }}" target="_blank">{{ $v->title }} </a></h5>
                    <p class="overflow-clip overflow-clip-3"><span>摘要：</span>
                        {!! $v->content !!}
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</body>
<script>
    function WebClick(id) {
        $.get('/website/click/' + id);
    }
</script>

</html>
