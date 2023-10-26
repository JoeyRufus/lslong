<style>
    .tinymce {
        max-width: 900px;
        padding: 10px 30px;
        display: none;
        position: absolute;
        background: #eee;
        border-radius: 15px;
    }

    .label-list {
        display: flex;
        flex-wrap: wrap;
    }

    .label-list div {
        padding: 5px;
        border: 1px solid;
        border-radius: 5px;
        margin: 5px 3px 0;
        cursor: pointer;
    }

    .modal-panel {
        display: flex;
        flex-wrap: wrap;
    }

    .mce-modal {
        border: 1px solid;
        padding: 2px 5px;
        border-radius: 5px;
        cursor: pointer;
    }

    .mce-modal:hover {
        color: #fff;
        background: #f1404b;
    }
</style>
<div class="tinymce shadow">
    <form class="row gy-2" id="tinyForm">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="col-12">
            <label class="form-label">标题</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="col-12">
            @if ($status == 'blog')
                <label class="form-label">分类</label>
                <input type="text" name='genre' class="form-control" list="tinyBrowsers" required>
                <datalist id="tinyBrowsers">
                    @foreach ($genre as $v)
                        <option value="{{ $v->title }}">
                    @endforeach
                </datalist>
            @else
                <label class='form-label'>标签</label><input type='text' name='label' class='form-control' placeholder="Label1|Label2" required>
                <div class='label-list'>
                    @foreach ($label as $v)
                        <div>{{ $v->title }}</div>
                    @endforeach
                </div>
            @endif
        </div>
        <div class="col-12">
            <label class="form-label">内容</label>
            <textarea name="content" class="form-control" id="myTextarea"></textarea>
        </div>
        @if ($status == 'blog')
            <div class="col-12 modal-panel">
                <div class="mce-modal" data-modal="modal-1">问题-原因-方案</div>
            </div>
        @endif
        <div class="col-4 offset-8">
            <button type="button" class="btn btn-secondary" onclick="MceHide()">取消</button>
            <button type="submit" class="btn btn-danger " id="mceBtn">确定</button>
        </div>
    </form>
    <div class="hide">
        <div class="modal-1">
            <p><span style="color: rgb(224, 62, 45); font-family: impact, sans-serif;"><strong><span style="font-size: 18pt;">问题起因：</span></strong></span></p>
            <hr>
            <p>&nbsp;</p>
            <p><span style="color: rgb(241, 196, 15);"><strong><span style="font-size: 18pt;">探索原理：</span></strong></span></p>
            <hr>
            <p>&nbsp;</p>
            <p><span style="color: rgb(45, 194, 107);"><strong><span style="font-size: 18pt;">解决方案：</span></strong></span></p>
            <hr>
            <p>&nbsp;</p>
        </div>
    </div>
</div>
<script src="/js/tinymce.min.js"></script>
<script>
    // tinymce插件配置
    tinymce.init({
        selector: '#myTextarea',
        language: 'zh-Hans',
        menubar: false,
        /* plugins: 'preview importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media  codesample table charmap pagebreak nonbreaking  insertdatetime advlist lists wordcount help charmap quickbars emoticons accordion', */
        plugins: 'accordion link image table media code fullscreen preview codesample',
        toolbar: "blocks fontfamily fontsize forecolor backcolor removeformat | hr bold italic underline strikethrough  | align numlist bullist | codesample accordion link image table media | code fullscreen preview",
        /* toolbar: "undo redo | hr accordion accordionremove | blocks fontfamily fontsize | bold italic underline strikethrough | align numlist bullist | link image | table media | lineheight outdent indent| forecolor backcolor removeformat | charmap emoticons | code fullscreen preview  | pagebreak  codesample", */
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
    // 添加随笔模板
    $('.mce-modal').click(function() {
        tinymce.get('myTextarea').setContent($('.modal-1').html());
    })

    // 数据验证和上传
    $('#tinyForm').validate({
        submitHandler: function() {
            event.preventDefault();
            var url = '';
            $('#myTextarea').val(tinymce.get("myTextarea").getContent());
            url += $("input[name='genre']").val() ? '/blog' : '/exp';
            url += $("input[name='id']").val() ? '/update' : '/store';
            $.post(url, $('#tinyForm').serializeArray(), function(d) {
                toastr.success(d.msg, d.code);
                setTimeout(function() {
                    window.location.reload();
                }, 1000)
            })
        }
    })
    // label快捷添加
    $('.label-list div').click(function() {
        $("input[name='label']").val($("input[name='label']").val() + $(this).text() + '|')
    })
    // tinymce位置保持居中
    function McePosition() {
        var w = $(window).width();
        var left = (w - 900) * 0.5;
        left = left > 0 ? left : 0;
        $('.tinymce').css({
            'left': left,
            'top': $(document).scrollTop() + 10
        });
    }
    McePosition();
    $(window).resize(McePosition);
    $(document).scroll(McePosition);

    // tinymce显示与隐藏
    function MceShow() {
        $('.tinymce').slideDown();
    }

    function MceHide() {
        $('.tinymce').slideUp();
        $("input[name='id']").remove();
        $('#tinyForm').get(0).reset();
    }
    // 数据编辑
    function EditTiny(s, id) {
        MceShow();
        $("#tinyForm").append("<input type='hidden' name='id' value=" + id + ">");
        $.get('/' + s + '/dtl/' + id, function(d) {
            $("input[name='title']").val(d.title)
            s == 'exp' ? $("input[name='label']").val(d.label) : $("input[name='genre']").val(d.genre)
            tinymce.get('myTextarea').setContent(d.content);
        })
    }
</script>
