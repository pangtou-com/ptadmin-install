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

namespace PTAdmin\Install\Service;

use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use PTAdmin\Install\Exceptions\InstallException;

class EnvService
{
    /**
     * @var string
     */
    private $envPath;

    /**
     * 数据缓存key.
     *
     * @var string
     */
    private $key_data = '__install__';

    public function __construct()
    {
        $this->envPath = base_path('bootstrap/cache/.env');
    }

    /**
     * 设置缓存.
     *
     * @param $data
     */
    public function setCacheData($data): void
    {
        Cache::put($this->key_data, serialize($data), 5 * 50);
    }

    /**
     * 获取缓存数据.
     *
     * @return mixed
     */
    public function getCacheData()
    {
        $data = Cache::get($this->key_data);
        if (null !== $data) {
            return unserialize($data);
        }

        throw new InstallException('缺少配置数据');
    }

    /**
     * 获取env文件路径.
     *
     * @return string
     */
    public function getEnvPath(): string
    {
        return $this->envPath;
    }

    /**
     * 测试数据库链接.
     *
     * @param $data
     *
     * @throws InstallException
     */
    public function checkDatabaseConnection($data): void
    {
        $this->ensureDatabaseExists($data);
        $connection = $data['db_connection'];
        $settings = config("database.connections.{$connection}");
        config([
            'database' => [
                'default' => $connection,
                'connections' => [
                    $connection => array_merge($settings, [
                        'driver' => $connection,
                        'host' => $data['db_host'],
                        'port' => $data['db_port'],
                        'database' => $data['db_database'],
                        'username' => $data['db_username'],
                        'password' => $data['db_password'],
                        'prefix' => $data['db_prefix'],
                    ]),
                ],
            ],
        ]);

        app('db')->purge();

        try {
            app('db')->connection()->getPdo();
        } catch (\Exception $e) {
            throw new InstallException($e->getMessage());
        }
    }

    /**
     * 保存环境信息.
     *
     * @param mixed $data
     *
     * @throws InstallException
     */
    public function saveEnvFile($data): void
    {
        $content = $this->getExampleContent();
        $results = [];
        foreach ($content as $key => $value) {
            $k = strtolower($key);
            $value = $data[$k] ?? $value;
            if ('APP_KEY' === $key) {
                $value = $this->generateRandomKey();
            }

            $results[] = "{$key}={$value}";
        }

        try {
            file_put_contents($this->getEnvPath(), implode("\n", $results));
            Artisan::call('config:clear');
        } catch (\Exception $e) {
            throw new InstallException($e->getMessage());
        }
    }

    /**
     * 创建安装完成文件.
     */
    public function createInstallFiles(): void
    {
        File::put(storage_path('installed'), '');
    }

    /**
     * 创建超级管理员.
     *
     * @param $data
     */
    public function createManager($data): void
    {
        $status = Artisan::call("admin:init", ['-u' => $data['username'], '-p' => $data['password'], '-f' => true]);
        if ($status !== 0) {
            throw new InstallException('创建管理员失败：'.Artisan::output());
        }
    }

    /**
     * 生成站点 key.
     *
     * @return string
     */
    protected function generateRandomKey(): string
    {
        return 'base64:'.base64_encode(Encrypter::generateKey(config('app.cipher')));
    }

    /**
     * 获取配置文件内容.用于写入到.env文件.
     */
    private function getExampleContent(): array
    {
        $content = File::get(base_path('.env.example'));
        $content = explode("\n", $content);
        $results = [];
        foreach ($content as $value) {
            if ('' === $value) {
                continue;
            }
            $item = explode('=', $value);
            if (2 !== \count($item)) {
                continue;
            }
            $results[$item[0]] = $item[1];
        }

        return $results;
    }

    /**
     * 确保数据库是存在的.如果不存在则会创建数据库.
     *
     * @param $data
     *
     * @throws InstallException
     */
    private function ensureDatabaseExists($data): void
    {
        $character = config('database.connections.mysql.charset', 'utf8');
        $collation = config('database.connections.mysql.collation', 'utf8_unicode_ci');
        $dsn = "mysql:host={$data['db_host']};charset={$character}";

        try {
            $sql = "CREATE DATABASE IF NOT EXISTS `{$data['db_database']}` DEFAULT CHARACTER SET {$character} DEFAULT COLLATE {$collation}";
            $pdo = new \PDO($dsn, $data['db_username'], $data['db_password']);
            $pdo->query($sql);
            $pdo = null;
        } catch (\Exception $exception) {
            throw new InstallException($exception->getMessage());
        }
    }
}
