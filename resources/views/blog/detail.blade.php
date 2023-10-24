<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @include('z-head')
    <link rel="stylesheet" href="/css/prism.css">
    <script src="/js/prism.js"></script>
    <title>{{ $blog->title }}</title>
    <style>
        .blog-content {
            overflow: hidden;
        }
    </style>
</head>

<body>
    @include('z-leftMenu')
    <div class="container-xl">
        <p>{{ $blog->title }}</p>
        <div class="blog-content">
            {!! $blog->content !!}
        </div>
        <p>{{ $blog->genre }} post - {{ $blog->updated_at }}</p>
    </div>
</body>

</html>
