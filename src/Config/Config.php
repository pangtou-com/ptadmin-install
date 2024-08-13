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

return [
    'core' => ['min_php_version' => '7.3.0'],
    //  定义需要加载的模块
    'extend' => ['openssl', 'pdo', 'mbstring', 'JSON', 'cURL', 'fileinfo', 'Mbstring', 'XML'],
    // 定义需要开启的函数
    'func' => ['curl_init', 'proc_open', 'putenv'],

    /*
    |--------------------------------------------------------------------------
    | 文件权限
    |--------------------------------------------------------------------------
    | 默认安装需要的文件路径权限
    |
    */
    'folders' => [
        'addons' => '755',
        'public' => '755',
        'storage' => '755',
        'bootstrap/cache/' => '755',
    ],

    'form' => [
        'rules' => [
            'app_name' => 'required|string|max:50',
            'app_url' => 'required',
            'username' => 'required|max:20',
            'password' => 'required|min:6|max:255',
            'db_connection' => 'required|string|max:50',
            'db_host' => 'required|string|max:50',
            'db_port' => 'required|numeric',
            'db_database' => 'required|string|max:50',
            'db_username' => 'required|string|max:50',
            'db_password' => 'nullable|string|max:50',
        ],
        'attributes' => [
            'app_name' => '网站标题',
            'app_url' => '网站地址',
            'email' => '邮箱',
            'password' => '管理密码',
            'db_connection' => '数据库',
            'db_host' => '数据库主机',
            'db_port' => '数据库端口',
            'db_database' => '数据库名称',
            'db_username' => '数据库用户名',
            'db_password' => '数据库密码',
        ],
    ],
];
