<?php
/**
 * Author: Zane
 * Email: 873934580@qq.com
 * Date: 2024/8/28
 */
namespace PTAdmin\Install\Service\Pipe;

use Illuminate\Support\Facades\Validator;

class ValidateData
{
    use FormatOutputTrait;
    public function handle($data, \Closure $next): void
    {
        $rules = config('install.form.rules', []);
        $attributes = config('install.form.attributes', []);
        $message = config('install.form.message', []);
        $validator = Validator::make($data, $rules, $message, $attributes);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return;
        }
        $next($data);
    }
}