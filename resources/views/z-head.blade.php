<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
<link rel="stylesheet" href="/css/bootstrap.min.css">
<link rel="stylesheet" href="/css/all.min.css">
<link rel="stylesheet" href="/css/base.css">
@isset($css)
    <link rel="stylesheet" href="/css/{{ $css }}.css">
@endisset

<script src="/js/jquery-3.7.1.min.js"></script>
<script src="/js/bootstrap.min.js"></script>

@isset($echarts)
    <script src="/js/echarts.min.js"></script>
@endisset
@isset($validate)
    <script src="/js/plugins/validate/jquery.validate.min.js"></script>
    <script src="/js/plugins/validate/messages_zh.js"></script>
@endisset

@isset($prism)
    <link rel="stylesheet" href="/css/prism.css">
    <script src="/js/prism.js"></script>
@endisset

@isset($toastr)
    <link rel="stylesheet" href="/css/plugins/toastr/toastr.min.css">
    <script src="/js/plugins/toastr/toastr.min.js"></script>
@endisset
