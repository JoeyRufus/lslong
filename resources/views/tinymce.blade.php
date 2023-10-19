<div class="tinymce shadow">
    <form class="row gy-2" id="tinyForm">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="col-12">
            <label class="form-label">标题</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="col-12 label-genre">
            <label class="form-label">分类</label>
            <input type="text" name='tiny_genre' class="form-control" list="tinyBrowsers" required>
            <datalist id="tinyBrowsers">
                @foreach ($blog_ctgr as $v)
                    <option value="{{ $v->title }}">
                @endforeach
            </datalist>
        </div>
        <div class="col-12">
            <label class="form-label">内容</label>
            <textarea name="content" class="form-control" id="myTextarea"></textarea>
        </div>
        <div class="col-12">
            <div class="mce-modal" data-modal="1-html">问题-原因-方案</div>
        </div>
        <div class="col-2 offset-10">
            <button type="button" class="btn btn-secondary" onclick="MceHide()">取消</button>
            <button type="submit" class="btn btn-danger " id="mceBtn">确定</button>
        </div>
    </form>
    <div class="hide">
        <div class="blog-genre-option">
            <label class="form-label">分类</label>
            <input type="text" name='tiny_genre' class="form-control" list="tinyBrowsers" required>
            <datalist id="tinyBrowsers">
                @foreach ($blog_ctgr as $v)
                    <option value="{{ $v->title }}">
                @endforeach
            </datalist>
        </div>
        <div class="exp-label">
            <label class='form-label'>标签</label><input type='text' name='label' class='form-control' placeholder="Label1|Label2" required>
            <div class='label-list'>
                @foreach ($exp_label as $v)
                    <div>{{ $v->title }}</div>
                @endforeach
            </div>
        </div>
        <div class="1-html">
            <p><span style="color: rgb(224, 62, 45); font-family: impact, sans-serif;"><strong><span style="font-size: 18pt;">问题：</span></strong></span></p>
            <hr>
            <p>&nbsp;</p>
            <p><span style="color: rgb(241, 196, 15);"><strong><span style="font-size: 18pt;">原因：</span></strong></span></p>
            <hr>
            <p>&nbsp;</p>
            <p><span style="color: rgb(45, 194, 107);"><strong><span style="font-size: 18pt;">方案：</span></strong></span></p>
            <hr>
            <p>&nbsp;</p>
        </div>
    </div>
</div>
<script>
    // tinymce插件配置
    tinymce.init({
        selector: '#myTextarea',
        language: 'zh-Hans',
        menubar: false,
        plugins: 'preview importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media  codesample table charmap pagebreak nonbreaking  insertdatetime advlist lists wordcount help charmap quickbars emoticons accordion',
        toolbar: "undo redo | hr accordion accordionremove | blocks fontfamily fontsize | bold italic underline strikethrough | align numlist bullist | link image | table media | lineheight outdent indent| forecolor backcolor removeformat | charmap emoticons | code fullscreen preview  | pagebreak  codesample",
        toolbar_mode: 'wrap',
        height: 550,
        codesample_languages: [{
            text: 'HTML/XML',
            value: 'markup'
        }, {
            text: 'JavaScript',
            value: 'javascript'
        }, {
            text: 'CSS',
            value: 'css'
        }, {
            text: 'PHP',
            value: 'php'
        }, {
            text: 'Python',
            value: 'python'
        }, {
            text: 'SQL',
            value: 'sql'
        }, {
            text: 'Git',
            value: 'git'
        }, {
            text: 'Nginx',
            value: 'nginx'
        }, {
            text: 'CMD',
            value: 'powershell'
        }],
    });
    $('.mce-modal').click(function() {
        tinymce.get('myTextarea').setContent($('.1-html').html());
    })
    $(function() {
        $(document).bind("click", function(e) {
            //closest('')参数即为可点击区域
            if ($(e.target).closest(".tinymce").length == 0 &&
                $(e.target).closest(".add-btn").length == 0 &&
                $(e.target).closest(".tox-editor-container").length == 0 &&
                $(e.target).closest(".tox-dialog__disable-scroll").length == 0
            ) {
                MceHide();
            }
        })
    })
    $('#tinyForm').validate({
        submitHandler: function() {
            event.preventDefault();
            var url = '';
            $('#myTextarea').val(tinymce.get("myTextarea").getContent());
            url += $("input[name='tiny_genre']").val() ? '/blog' : '/exp';
            url += $("input[name='id']").val() ? '/update' : '/store';
            $.post(url, $('#tinyForm').serializeArray(), function(d) {
                toastr.success(d.msg, d.code);
                // $('#tinyForm').get(0).reset();
                setTimeout(function() {
                    window.location.href = '/';
                }, 1000)
            })
        }
    })
    $('.label-genre').on('click', '.label-list div', function() {
        $("input[name='label']").val($("input[name='label']").val() + $(this).text() + '|')
    })

    function McePosition() {
        var w = $(window).width();
        var left = (w - 900) * 0.5;
        left = left > 0 ? left : 0;
        $('.tinymce').css({
            'left': left,
            'top': $(document).scrollTop()
        });
    }
    McePosition();
    $(window).resize(McePosition);
    $(document).scroll(McePosition);

    function MceShow() {

        $('.tinymce').fadeIn();
    }


    function MceHide() {
        $('.tinymce').slideUp();
        $("input[name='id']").remove();
        $('#tinyForm').get(0).reset();
    }

    function EditTiny(s, id) {
        MceShow();
        $("#tinyForm").append("<input type='hidden' name='id' value=" + id + ">");
        $.get('/' + s + '/dtl/' + id, function(d) {
            $("input[name='title']").val(d.title)
            s == 'exp' ? $("input[name='label']").val(d.label) : $("input[name='tiny_genre']").val(d.genre)
            tinymce.get('myTextarea').setContent(d.content);
        })
    }

    function BlogHtml() {
        $('.label-genre').html($('.blog-genre-option').html());
    }

    function ExpHtml() {
        $('.label-genre').html($('.exp-label').html());
    }
</script>
