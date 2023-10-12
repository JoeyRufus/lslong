<div id="sideBar" class="col-2 shadow-sm">
    <div class="top-operate">
        <button type="button" class="blog-btn active" onclick="ToBlog()" disabled>随笔</button>
        <button type="button" class="experience-btn" onclick="ToExp()">经历</button>
    </div>
    <div class="serach-wrap"><input type="text" placeholder="搜索"></div>
    <div class="title">最新上传
        <i class="fas fa-plus-circle add-btn" onclick="MceShow()" type="button"></i>
    </div>
    <div class="last-items">
        <ul class="last-blog">
            @foreach ($blog_last as $v)
                <li data-id="blog-{{ $v->id }}">{{ $v->title }}</li>
            @endforeach
        </ul>
        <ul class="last-exp">
            @foreach ($exp_last as $v)
                <li data-id="exp-{{ $v->id }}">{{ $v->title }}</li>
            @endforeach
        </ul>
    </div>
    <div class="genre-items">
        <div class="title">随笔分类</div>
        @foreach ($blog_ctgr as $v)
            <p data-id="{{ $v->id }}">{{ $v->title }}({{ $v->blog_count }})</p>
        @endforeach
    </div>
    <div class="label-box">
        <div class="title">经历标签</div>
        <div class="label-items">
            @foreach ($exp_label as $v)
                <div data-id="{{ $v->id }}">{{ $v->title }}({{ $v->experience_count }})</div>
            @endforeach
        </div>
    </div>
</div>
<script>
    /* var currentDeg = 45;
    $('.add-btn').click(function() {
        ShowHide();
        $(this).css('transform', 'rotate(' + currentDeg + 'deg)')
        currentDeg = currentDeg + 45;
    }) */
    $('.last-items li').click(function() {
        var data = $(this).data('id').split('-');
        GetDtlInfo(data[0], data[1])
    })
    $('.genre-items p').click(function() {
        GetList('blog', $(this).data('id'));
        $(this).addClass('active').siblings().removeClass('active');
    })

    $('.label-items div').click(function() {
        GetList('exp', $(this).data('id'));
        $(this).addClass('active').siblings().removeClass('active');
    })
    $('.top-operate button').click(function() {
        $(this).attr("disabled", true).siblings().removeAttr("disabled");
        $(this).addClass('active').siblings().removeClass('active');
        $('.genre-items').slideToggle();
        $('.label-box').slideToggle();
        $('.last-blog').slideToggle();
        $('.last-exp').slideToggle();
    })
</script>
