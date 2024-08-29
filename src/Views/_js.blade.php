<style>
    .console-box{
        width: 100%;
        height: 100%;
        background: rgba(31, 31, 32, 0.9);
        color: white;
    }
    .console-box .title {
        padding: 20px 20px 5px;
    }
    .console-box .item {
        padding: 0 20px 20px;
        height: calc(100% - 80px);
        overflow-y: auto;
    }
    .console-box .item .time{
        color: #b7d5a0;
        font-size: 20px;
    }
    .console-box .item li{
        line-height: 2!important;
        height: auto!important;
    }
    .console-box .item li span{
        display: inline-block;
        width: 60px;
        margin-right: 10px;
    }
    .loading-dots {
        display: inline-block;
        font-size: 16px;
        letter-spacing: 3px;
    }
    .loading-dots span {
        animation: loading 1.5s infinite;
        opacity: 0;
    }
    .loading-dots span:nth-child(1) {
        animation-delay: 0s;
    }
    .loading-dots span:nth-child(2) {
        animation-delay: 0.3s;
    }
    .loading-dots span:nth-child(3) {
        animation-delay: 0.6s;
    }
    @keyframes loading {
        0% {
            opacity: 0;
        }
        50% {
            opacity: 1;
        }
        100% {
            opacity: 0;
        }
    }
</style>

<script type="text/html" id="console_html">
    <div class="console-box">
        <div class="title loading-dots">正在执行安装程序 <span>.</span><span>.</span><span>.</span></div>
        <ul class="item"></ul>
    </div>
</script>
<script>
    (function () {
        const url = "/install/stream"
        const success_url = "/"
        const eventAction = {
            console: null,      // 当前的控制台对象
            height: 0,          // 当前的控制台对象
            nodeNumber: 30,     // 允许的节点总数
            state: 0,           // 当前执行状态： 0 => 未开始, 1 => 执行中, 2 => 执行失败
            index: null,        // 弹出窗口的标识
            type_map: {
                error: 'layui-bg-red',
                success: 'layui-bg-green',
                process: 'layui-bg-blue',
                info: 'layui-bg-gray'
            },
            start: function (index) {
                if (this.state !== 0) {
                    console.log("正在执行中，请不要重复操作")
                    return
                }
                if (this.console === null) {
                    this.console = document.querySelector(".console-box > .item")
                    this.height = this.console.clientHeight;
                }
                this.index = index
                this.state = 1
                this.process({type: 'info', message: '发送安装请求'})
            },
            process: function (data) {
                console.log(data)
                if (typeof data === 'string') {
                    try {
                        data = JSON.parse(data)
                    }catch (e) {
                        data = {type: 'error', message: `解析失败${e.toString()}`}
                    }
                }
                // 出现执行错误，改变状态
                if (data.type === 'error') {
                    this.state = 2
                }
                this.editConsole(data)
            },
            error: function (err) {
                this.editConsole({type: 'error', message: err.toString()})
                this.state = 0
            },
            complete: function () {
                if (this.state === 2) {
                    this.fail()
                    return
                }
                this.success()
            },
            editConsole: function (data) {
                const li = document.createElement('li')
                li.innerHTML = `<span class="layui-badge ${this.type_map[data.type] || ""}">${data.type}</span> ${data.message}`
                this.console.appendChild(li)
                this.clear()
                this.scrollbarAuto()

                return li
            },
            scrollbarAuto: function () {
                const scrollHeight = this.console.scrollHeight;
                if (scrollHeight < this.height) {
                    return;
                }
                let diff = scrollHeight - this.height;
                if (this.console.scrollTop > 50) {
                    diff += 50;
                }
                this.console.scrollTop += diff;
            },
            clear: function () {
                let list = this.console.getElementsByTagName('li');
                if (list.length > this.nodeNumber) {
                    list[0].remove();
                }
            },
            send: function (data) {
                if (window.fetch !== undefined) {
                    fetchStream(url, data)
                    return
                }
                xhrStream(url, data)
            },
            reset: function () {
                this.console = null
                this.height = 0
                this.state = 0
                this.index = null
            },
            fail: function () {
                this.editConsole({type: 'error', message: "安装失败请重试 [点击空白位置关闭窗口]"})
                const thiz = this
                const closeShade = document.querySelector(".layui-layer-shade")
                const close = () => {
                    const { layer } = window['layui']
                    layer.close(thiz.index)
                    thiz.reset()
                    closeShade.removeEventListener('click', close)
                }
                closeShade.addEventListener('click', close)
            },
            success: function () {
                const html = this.editConsole({
                    type: 'success',
                    message: `安装完成 [请点击任意位置跳转或等待<font class="time"> 5 </font>秒后自动跳转]`
                })

                let timer = 5
                let timerId = null
                const thiz = this
                const cronClose = () => {
                    html.querySelector(".time").innerHTML = ` ${(timer--).toString()} `
                    console.log(timer)
                    if (timer <= 0) {
                        close()
                        return
                    }
                    timerId = setTimeout(function (){
                        cronClose()
                    }, 1000)
                }
                const closeShade = document.querySelector(".layui-layer-shade")
                const close = () => {
                    const { layer } = window['layui']
                    layer.close(thiz.index)
                    thiz.reset()
                    if (timerId !== null) {
                        clearTimeout(timerId)
                    }
                    closeShade.removeEventListener('click', close)
                    window.location.href = success_url
                }
                closeShade.addEventListener('click', close)
                cronClose()
            }
        }

        document.getElementById("submit").addEventListener('click', function (e) {
            e.stopPropagation()
            const { form, layer } = window['layui'] || {}
            if (form === undefined || layer  === undefined) {
                return
            }
            const data = form.val('form-data')
            const formData = new FormData()
            for (const dataKey in data) {
                formData.append(dataKey, data[dataKey])
            }
            const html = document.getElementById("console_html").innerHTML
            layer.open({
                type: 1,
                area: ['50%', '500px'],
                title: false,
                closeBtn: 0,
                shadeClose: false,
                content: html,
                success: (obj, index) => {
                    eventAction.start(index)
                    eventAction.send(formData)
                },
                end: function () {
                    eventAction.reset()
                }
            });
        })

        /**
         * 使用fetch 请求
         *
         * @param url
         * @param data
         */
        function fetchStream(url, data) {
            fetch(url, { method: "post", body: data}).then(response => {
                const reader = response.body.getReader();
                const decoder = new TextDecoder();
                function read() {
                    reader.read().then(({ done, value }) => {
                        if (done) {
                            eventAction.complete()
                            return
                        }
                        const chunk = decoder.decode(value, { stream: true });
                        eventAction.process(chunk)
                        read()
                    })
                }
                read()
            }).catch(error => {
                eventAction.error(error)
            })
        }

        /**
         * 使用XMLHttpRequest
         * @param url
         * @param data
         */
        function xhrStream(url, data)
        {
            const xhr = new XMLHttpRequest();
            let lastProcessedIndex = 0;  // 记录上次处理的结束位置
            let buffer = '';  // 用于缓存数据块
            xhr.onprogress = function() {
                // 累积数据块
                buffer += xhr.responseText.substring(lastProcessedIndex);
                lastProcessedIndex = xhr.responseText.length;
                // 处理每个完整的事件块
                let events = buffer.split("\n\n");
                for (let i = 0; i < events.length - 1; i++) {
                    let event = events[i].trim();
                    if (event) {
                        eventAction.process(event)
                    }
                }
                // 保留未完成的最后一部分
                buffer = events[events.length - 1];
            }

            xhr.onload = function() {
                eventAction.complete()
            }

            xhr.onerror = function() {
                eventAction.error()
            }

            xhr.open('post', url, true);
            xhr.send(data);
        }
    })()
</script>