<?php

declare(strict_types=1);
/**
 * Author: Zane
 * Email: 873934580@qq.com
 * Date: 2022/4/26.
 */

namespace PTAdmin\Install\Middleware;

class CanInstall
{
    public function handle($request, \Closure $next)
    {
        if (file_exists(storage_path('installed')) && $this->isAccessInstall()) {
            abort(404);
        }else if (!file_exists(__DIR__.'/../storage/installed') && !$this->isAccessInstall()) {
            header('Location: /install');
            exit;
        }

        return $next($request);
    }

    private function isAccessInstall(): bool
    {
        return isset($_SERVER['REQUEST_URI']) && '/install' === substr($_SERVER['REQUEST_URI'], 0, 8);
    }
}
