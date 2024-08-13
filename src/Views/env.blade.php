@extends("install::layouts.base")

@section('content')
    <form class="layui-form layui-form-pane" lay-filter="form-data">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>基础信息</legend>
            <div class="layui-field-box">
                <div class="layui-row layui-col-space10">
                    <div class="layui-col-xs6">
                        <div class="layui-form-item">
                            <label class="layui-form-label"><span class="red"> * </span>网站地址</label>
                            <div class="layui-input-block">
                                <input type="text" name="app_url" value="{{$url}}" placeholder="请输入网站地址" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                    </div>
                    <div class="layui-col-xs6">
                        <div class="layui-form-item">
                            <label class="layui-form-label"><span class="red"> * </span>网站标题</label>
                            <div class="layui-input-block">
                                <input type="text" name="app_name" value="PTAdmin管理系统" placeholder="请输入网站标题" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                    </div>
                    <div class="layui-col-xs6">
                        <div class="layui-form-item">
                            <label class="layui-form-label"><span class="red"> * </span>登录账户</label>
                            <div class="layui-input-block">
                                <input type="text" name="username" placeholder="请输入管理员登录账户" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                    </div>
                    <div class="layui-col-xs6">
                        <div class="layui-form-item">
                            <label class="layui-form-label"><span class="red"> * </span>登录密码</label>
                            <div class="layui-input-block">
                                <input type="password" name="password" placeholder="请输入管理员登录密码" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                    </div>
                    <div class="layui-col-xs6">
                        <div class="layui-form-item">
                            <label class="layui-form-label">后台地址</label>
                            <div class="layui-input-block">
                                <input type="text" name="app_system_prefix" value="{!! \Illuminate\Support\Str::random(9) !!}" placeholder="请输入地址名" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>

        <fieldset class="layui-elem-field layui-field-title">
            <legend>数据库设置</legend>
            <div class="layui-field-box">
                <div class="layui-row layui-col-space10">
                    <div class="layui-col-xs6">
                        <div class="layui-form-item">
                            <label class="layui-form-label">数据库</label>
                            <div class="layui-input-block">
                                <select name="db_connection">
                                    <option value="">请选择数据库</option>
                                    <option value="mysql" selected>Mysql</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="layui-col-xs6">
                        <div class="layui-form-item">
                            <label class="layui-form-label">主机地址</label>
                            <div class="layui-input-block">
                                <input type="text" name="db_host" placeholder="请输入主机地址" value="127.0.0.1" class="layui-input">
                            </div>
                        </div>
                    </div>
                    <div class="layui-col-xs6">
                        <div class="layui-form-item">
                            <label class="layui-form-label">数据库端口</label>
                            <div class="layui-input-block">
                                <input type="text" name="db_port" placeholder="请输入数据库端口" value="3306" class="layui-input">
                            </div>
                        </div>
                    </div>
                    <div class="layui-col-xs6">
                        <div class="layui-form-item">
                            <label class="layui-form-label">数据库名称</label>
                            <div class="layui-input-block">
                                <input type="text" name="db_database" value="pang_tou" placeholder="请输入数据库名称" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                    </div>
                    <div class="layui-col-xs6">
                        <div class="layui-form-item">
                            <label class="layui-form-label">数据库账户</label>
                            <div class="layui-input-block">
                                <input type="text" name="db_username" placeholder="请输入数据库账户" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                    </div>
                    <div class="layui-col-xs6">
                        <div class="layui-form-item">
                            <label class="layui-form-label">数据库密码</label>
                            <div class="layui-input-block">
                                <input type="text" name="db_password" placeholder="请输入数据库密码" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                    </div>
                    <div class="layui-col-xs6">
                        <div class="layui-form-item">
                            <label class="layui-form-label">数据表前缀</label>
                            <div class="layui-input-block">
                                <input type="text" name="db_prefix" value="pt_" placeholder="请输入数据表前缀" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
    </form>
@endsection

@section('button')
    <div style="text-align: center">
        <div class="layui-btn-group">
            <button type="button" id="pre" class="layui-btn layui-btn-sm">上一步</button>
            <button type="button" id="submit" class="layui-btn layui-bg-blue layui-btn-sm">确认安装</button>
        </div>
    </div>
@endsection

@section('script')
<script>
    layui.use(['form', 'layer'], function(){
        const form = layui.form;
        const layer = layui.layer;

        layui.$("#pre").on('click', () => {
            location.href = '/install/requirements'
        })

        //监听提交
        layui.$("#submit").on('click', function() {
            const data = form.val('form-data')
            const step_one = () => {
                send("正在配置信息", 0, function () {
                    step_two()
                }, data)
            }

            const step_two = () => {
                send("正在初始化数据库", 1, function () {
                    step_three()
                }, data)
            }

            const step_three = () => {
                send("正在写入基础数据", 2, function () {
                    location.href = '/install/finish';
                }, data)
            }

            const send = (msg, step, callback, data = {}) => {
                const index = layer.msg(msg, {
                    icon: 16,
                    shade: 0.01
                });
                layui.$.ajax({
                    url: `/install/env?val=${step}`,
                    type: 'post',
                    data: data,
                    dataType: 'json',
                    success: (res) => {
                        layer.close(index)
                        if (res.code !== 0) {
                            layer.msg(res.message, {icon: 2})
                            return
                        }
                        if (callback !== undefined) {
                            callback(res)
                        }
                    }
                })
            }

            step_one()

        });


    });
</script>
@endsection
