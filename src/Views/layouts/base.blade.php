<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PTAdmin 管理系统</title>
    <link rel="icon" href="{{asset("ptadmin/install/favicon.png")}}">
    <link rel="stylesheet" href="{{asset("ptadmin/install/install.css")}}">
    <link rel="stylesheet" href="{{asset("ptadmin/bin/css/layui.css")}}">
</head>
<body>
    <div class="install">
        <div class="card">
            <div class="title">
                <img src="{{asset("ptadmin/install/install_logo.png")}}" alt="PTAdmin" />
                欢迎使用PTAdmin
            </div>
            <div class="step">
                @foreach($tabs as $key => $val)
                    <div class="step-title @if($key === $step) is-process @elseif($key < $step) is-finish @else is-wait @endif ">
                        <i class="layui-icon {{$val['icon']}}"></i><span>{{$val['title']}}</span>
                    </div>
                @endforeach
            </div>
            <div class="content">
                @yield("content")
            </div>
            <div class="footer">
                @yield("button")
            </div>
        </div>
    </div>
</body>
</html>
<script src="{{asset("ptadmin/bin/layui.js")}}"></script>
@yield('script')

