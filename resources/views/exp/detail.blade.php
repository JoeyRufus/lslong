<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @include('z-head')
    <link rel="stylesheet" href="/css/prism.css">
    <script src="/js/prism.js"></script>
    <title>{{ $exp->title }}</title>
    <style>
        .exp-content {
            overflow: hidden;
        }
    </style>
</head>

<body>
    @include('z-leftMenu')
    <div class="container-xl">
        <p>{{ $exp->title }}</p>
        <div class="exp-content">
            {!! $exp->content !!}
        </div>
        <p>{{ $exp->label }} post - {{ $exp->updated_at }}</p>
    </div>
</body>

</html>
