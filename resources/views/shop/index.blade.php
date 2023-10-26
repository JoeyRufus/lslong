<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @include('z-head', ['validate' => true, 'toastr' => true])
    <title>购物</title>
    <style>
        .add-commodity {
            position: fixed;
            bottom: 15px;
            right: 15px;
            font-size: 2rem;
            color: #eee;
            cursor: pointer;
        }

        .my-card-wrapper {
            -moz-column-count: 2;
            -webkit-column-count: 2;
            column-count: 2;
            column-fill: balance;
            -moz-column-gap: 0;
            -webkit-column-gap: 0;
        }


        .my-card {
            -moz-page-break-inside: avoid;
            -webkit-column-break-inside: avoid;
            break-inside: avoid;
        }

        .genre-panel {
            padding: 5px 10px;
            background: rgba(255, 255, 255, .7);
            border-radius: 10px;
            overflow: hidden;
            margin: 0 5px 10px;
        }

        h3 {
            text-align: center;
        }

        .commodity {
            border-bottom: 1px dashed;
            margin-bottom: 10px;
            position: relative;
        }

        .commodity:last-child {
            border-bottom: none;
        }

        .commodity>i {
            position: absolute;
            right: 0;
            top: 0;
            font-size: 1.2rem;
            cursor: pointer;
        }

        .info i {
            font-size: .75rem;
            color: #f1404b;
        }

        .history-price {
            margin-bottom: 5px;
        }
    </style>
</head>

<body>
    @include('z-leftMenu')
    <div class="fas fa-plus-circle add-commodity" data-bs-toggle="modal" data-bs-target="#exampleModal"></div>
    <div class="container-fluid" style="margin-top:20px">
        <div class="my-card-wrapper">
            @foreach ($commodity as $val)
                <div class="my-card">
                    <div class="genre-panel">
                        <h3>{{ $val['0']->genre }}</h3>
                        @foreach ($val as $v)
                            <div class="commodity">
                                <div class="info row"><span class="col-4">{{ $v->title }}<i>{{ $v->mark }}</i></span>
                                    <span class="col-4">{{ $v->weight }}</span> <span class="col-4">单价:{{ $v->unit_price }}</span>
                                </div>
                                <div class="history-price row">
                                    <span class="col-4">min:{{ $v->min }}</span><span class="col-4">max:{{ $v->max }}</span>
                                    <span class="col-4">历史价格:{{ $v->price }}</span>
                                </div>
                                <i class="fas fa-plus add-price" data-id="{{ $v->id }}" data-v="{{ $v->price }}" data-min="{{ $v->min }}"
                                    data-weight="{{ $v->weight }}" data-unit_price="{{ $v->unit_price }}" data-max="{{ $v->max }}"></i>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">新商品</h5>
                </div>
                <div class="modal-body">
                    <form id="commodityForm">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="mb-3">
                            <label class="col-form-label">名称:</label>
                            <input type="text" class="form-control" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label class="col-form-label">分类:</label>
                            <select name="genre" class="form-select" required>
                                <option value="蔬菜">蔬菜</option>
                                <option value="水果">水果</option>
                                <option value="米面">米面</option>
                                <option value="零食">零食</option>
                                <option value="酒饮">酒饮</option>
                                <option value="调味">调味</option>
                                <option value="肉蛋">肉蛋</option>
                                <option value="水产">水产</option>
                                <option value="百货">百货</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="col-form-label">mark:</label>
                            <input type="text" class="form-control" name="mark">
                        </div>
                        <div class="mb-3">
                            <label class="col-form-label">数量(g):</label>
                            <input type="hidden" name='weight'>
                            <div class="input-group">
                                <input type="text" class="form-control w-first">
                                <span class="input-group-text status">-</span>
                                <input type="text" class="form-control w-last">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="col-form-label">价格:</label>
                            <input type="text" class="form-control" name="price" required>
                            <input type="hidden" name="min">
                            <input type="hidden" name="max">
                            <input type="hidden" name="unit_price">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                            <button class="btn btn-danger" type="submit">添加</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    $('.add-price').click(function() {
        var price = prompt("输入价格:");
        if (price) {
            var com = $(this).data();
            var unit_price = com.unit_price;
            if (com.min > price) {
                com.min = price;
                var arr = [];
                var w = com.weight;
                if (w.indexOf('*') > 0) {
                    arr = w.split('*');
                    var m = Number(arr[1]);
                    unit_price = Math.trunc(price / m * 100) / 100;
                } else {
                    arr = w.split('-');
                    if (arr[0].indexOf('kg') > 0) {
                        w = arr[0].replace('kg', '');
                        w = w * 1000;
                    } else {
                        w = arr[0].replace('g', '');
                    }
                    unit_price = Math.trunc(price / w * 50000) / 100;
                }
            }
            $.post('/shop/update', {
                '_token': '{{ csrf_token() }}',
                'id': com.id,
                'price': com.v + '、' + price,
                'max': com.max > price ? com.max : price,
                'min': com.min,
                'unit_price': unit_price
            }, function(d) {
                toastr.success(d.msg);
                setTimeout(function() {
                    window.location.reload();
                }, 1000)
            })
        }
    })
    $('.status').click(function() {
        var s = $(this).text();
        if (s == '*') {
            $(this).text('-')
        } else {
            $(this).text('*')
        }
    })
    $('#commodityForm').validate({
        submitHandler: function(form) {
            event.preventDefault();
            var weight = '';
            var unit_price = '';
            var f = $('.w-first').val();
            var l = $('.w-last').val();
            var price = $("input[name='price']").val();
            if ($('.status').text() == '*') {
                weight = f + 'g * ' + l;
                unit_price = Math.trunc(price / l * 100) / 100;
            } else {
                unit_price = Math.trunc(price / f * 50000) / 100;
                if (f >= 1000) {
                    f = f / 1000;
                    if (l) {
                        l = l / 1000;
                        weight = f + 'kg - ' + l + 'kg';
                    } else {
                        weight = f + 'kg';
                    }
                } else {
                    weight = l ? f + 'g - ' + l + 'g' : f + 'g';
                }
            }
            $("input[name='weight']").val(weight);
            $("input[name='min']").val(price);
            $("input[name='max']").val(price);
            $("input[name='unit_price']").val(unit_price);
            $.post('/shop/store', $('#commodityForm').serializeArray(), function(d) {
                if (d.code == 200) {
                    toastr.success(d.msg);
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000)
                }
            })
        }
    })
</script>

</html>
