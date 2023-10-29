<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>登录</title>
    <link rel="stylesheet" href="./files/index.css">
    <link rel="stylesheet" href="./files/style.css" type="text/css">
    <link rel="stylesheet" href="./css/plugins/toastr/toastr.min.css">
</head>

<body>
    <div id="app">
        <div class="content">
            <div class="content_input">
                <div class="title">
                    <p>管理员登录</p>
                </div>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="el-input el-input--suffix">
                    <input type="text" autocomplete="off" class="el-input__inner" name='username' value='Administrator' disabled>
                </div>
                <div class="el-input el-input--suffix">
                    <input type="password" autocomplete="off" placeholder="密码" name='password' class="el-input__inner" autofocus="autofocus">
                </div>
                <div class="content_button">
                    <button type="button" class="el-button el-button--primary"><span>登录</span></button>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="./files/ribbon.js"></script>
    <canvas id="bgCanvas" width="1920" height="590"
        style="display: block; position: fixed; margin: 0px; padding: 0px; border: 0px; outline: 0px; left: 0px; top: 0px; width: 100%; height: 100%; z-index: -1; 
        background-color: rgba(223, 223, 223, 0.3);">
    </canvas>
</body>
<script src="./js/jquery-3.7.1.min.js"></script>
<script src="/js/plugins/md5/md5.js"></script>
<script src="/js/plugins/toastr/toastr.min.js"></script>
<script>
    function PostInfo() {
        event.preventDefault();
        var password = $("input[name='password']").val().trim();
        var path = "{!! session('path') !!}";
        password = $.md5(password);
        $.post('/check', {
            '_token': '{{ csrf_token() }}',
            'password': password
        }, function(d) {
            if (d.code == 200) {
                toastr.success(d.msg, d.code);
                path = path.length > 1 ? path : '';
                setTimeout(function() {
                    window.location.href = '/' + path;
                }, 1000)
            } else {
                toastr.error(d.msg, d.code);
            }
        })
    }
    $('button').click(function() {
        PostInfo();
    })
    $(function() {
        document.onkeydown = function(e) {
            var ev = document.all ? window.event : e;
            if (ev.keyCode == 13) {
                PostInfo();
            }
        }
    });
</script>

</html>
