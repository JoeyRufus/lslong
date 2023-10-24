<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @include('z-head')
    <title>主页</title>
</head>
<style>
    .sites {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-evenly;
    }

    .site {
        width: 250px;
        margin-bottom: 15px;
    }

    .site a {
        display: flex;
        padding: 15px;
        color: #333;
        background: rgba(255, 255, 255, .7);
        border-radius: 5px;
    }

    .site a span {
        color: #999;
    }

    .site-placeholder {
        width: 250px;
        height: 0;
    }

    .site img {
        width: 20%;
    }

    .url-info {
        padding-left: 10px;
        width: 80%;
    }

    .url-info strong {
        line-height: 23px;
    }

    .main>div {
        background: rgba(0, 0, 0, .2);
        color: #FFF;
        margin-bottom: 20px;
        padding: 10px;
        border-radius: 10px;
    }

    .main .title {
        position: relative;
    }

    .main .title a {
        color: #FFF;
        position: absolute;
        right: 0;
        top: 0;
    }

    .blog a,
    .exp a {
        color: #FFF;
    }

    .detail {
        padding: 0 30px;
    }
</style>

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

</html>
