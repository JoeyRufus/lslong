<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @include('z-head', ['prism' => true])
    <title>{{ $exp->title }}</title>
    <style>
        .main {
            max-width: 1200px;
            padding: 0 15px;
            margin: 30px auto;
        }

        .content {
            background: rgba(255, 255, 255, .5);
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .post-desc {
            padding: 10px;
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>

<body style="background-image: url('/images/bg-exp.jpg')">
    @include('z-leftMenu')
    <div class="main">
        <h3>{{ $exp->title }}</h3>
        <div class="content">
            {!! $exp->content !!}
        </div>
        <div class="post-desc"><span>{{ $exp->label }}</span> <span>posted:{{ $exp->updated_at }}</span></div>
    </div>
</body>

</html>
