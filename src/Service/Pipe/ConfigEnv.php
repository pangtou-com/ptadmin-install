<?php

declare(strict_types=1);

/**
 *  PTAdmin
 *  ============================================================================
 *  版权所有 2022-2024 重庆胖头网络技术有限公司，并保留所有权利。
 *  网站地址: https://www.pangtou.com
 *  ----------------------------------------------------------------------------
 *  尊敬的用户，
 *     感谢您对我们产品的关注与支持。我们希望提醒您，在商业用途中使用我们的产品时，请务必前往官方渠道购买正版授权。
 *  购买正版授权不仅有助于支持我们不断提供更好的产品和服务，更能够确保您在使用过程中不会引起不必要的法律纠纷。
 *  正版授权是保障您合法使用产品的最佳方式，也有助于维护您的权益和公司的声誉。我们一直致力于为客户提供高质量的解决方案，并通过正版授权机制确保产品的可靠性和安全性。
 *  如果您有任何疑问或需要帮助，我们的客户服务团队将随时为您提供支持。感谢您的理解与合作。
 *  诚挚问候，
 *  【重庆胖头网络技术有限公司】
 *  ============================================================================
 *  Author:    Zane
 *  Homepage:  https://www.pangtou.com
 *  Email:     vip@pangtou.com
 */

namespace PTAdmin\Install\Service\Pipe;

use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class ConfigEnv
{
    use FormatOutputTrait;

    public function handle($data, \Closure $next): void
    {
        if (!$this->ensureDatabaseExists($data)) {
            return;
        }
        if (!$this->checkDatabaseConnection($data)) {
            return;
        }
        if (!$this->saveEnvFile($data)) {
            return;
        }

        $next($data);
    }

    /**
     * 保存配置文件.
     *
     * @param $data
     *
     * @return bool
     */
    private function saveEnvFile($data): bool
    {
        $content = config('install.env_example', []);
        $results = [];
        foreach ($content as $key => $value) {
            if (is_numeric($key)) {
                $results[] = '' !== $value ? '# '.$value : '';

                continue;
            }
            $k = strtolower($key);
            if ('APP_KEY' === $key) {
                $value = 'base64:'.base64_encode(Encrypter::generateKey(config('app.cipher')));
                $results[] = "{$key}={$value}";

                continue;
            }
            if ('APP_SYSTEM_PREFIX' === $key) {
                if (!isset($data[$k]) || blank($data[$k])) {
                    $data[$k] = Str::random(8);
                }
            }
            $value = $data[$k] ?? $value;
            $results[] = "{$key}={$value}";
        }

        try {
            file_put_contents(base_path('bootstrap/cache/.env'), implode("\n", $results));
            Artisan::call('config:clear');
            $this->process('保存配置文件');
        } catch (\Exception $e) {
            $this->error('保存配置文件失败: '.$e->getMessage());

            return false;
        }

        return true;
    }

    /**
     * 连接数据库.
     *
     * @param $data
     *
     * @return bool
     */
    private function checkDatabaseConnection($data): bool
    {
        $connection = $data['db_connection'];
        $settings = [
            'driver' => $connection,
            'host' => $data['db_host'],
            'port' => $data['db_port'],
            'database' => $data['db_database'],
            'username' => $data['db_username'],
            'password' => $data['db_password'],
            'prefix' => $data['db_prefix'] ?? '',
        ];
        $database = config('database');
        $database['default'] = $connection;
        $database['db_prefix'] = $data['db_prefix'] ?? '';
        $database['connections'] = array_merge($database['connections'], [
            $connection => $settings,
        ]);
        config(['database' => $database]);
        $this->process('链接数据库');
        app('db')->purge();

        try {
            app('db')->connection()->getPdo();
        } catch (\Exception $e) {
            $this->error('数据库链接失败: '.$e->getMessage());

            return false;
        }

        return true;
    }

    /**
     * 数据库创建.
     *
     * @param $data
     *
     * @return bool
     */
    private function ensureDatabaseExists($data): bool
    {
        $character = config('database.connections.mysql.charset', 'utf8');
        $collation = config('database.connections.mysql.collation', 'utf8_unicode_ci');
        $dsn = "mysql:host={$data['db_host']};charset={$character}";

        try {
            $this->process('创建数据库...');
            $sql = "CREATE DATABASE IF NOT EXISTS `{$data['db_database']}` DEFAULT CHARACTER SET {$character} DEFAULT COLLATE {$collation}";
            $pdo = new \PDO($dsn, $data['db_username'], $data['db_password']);
            $pdo->query($sql);
            $pdo = null;
            $this->process('数据库创建完成');
        } catch (\Exception $exception) {
            $this->error('数据库链接失败: '.$exception->getMessage());

            return false;
        }

        return true;
    }
}
