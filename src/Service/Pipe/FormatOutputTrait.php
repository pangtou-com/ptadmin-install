<?php
/**
 * Author: Zane
 * Email: 873934580@qq.com
 * Date: 2024/8/28
 */

namespace PTAdmin\Install\Service\Pipe;

trait FormatOutputTrait
{
    protected function process($message, $data = [])
    {
        $this->output('process', $message, $data);
    }

    protected function success($message, $data = [])
    {
        $this->output('success', $message, $data);
    }

    protected function error($message, $data = [])
    {
        $this->output('error', $message, $data);
    }

    protected function output($type, $message, $data = []) {
        $data = ['type' => $type, 'message' => $message, 'data' => $data];
        /**
         * 标准sse输出格式为：
         * event: event name\n
         * data: message \n\n
         * 这个是标准的输出方式，我不需要这种标准输出，直接输出一个json字符串即可
         */
        echo json_encode($data)."\n\n";
        usleep(50000);
    }
}