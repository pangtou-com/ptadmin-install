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

namespace PTAdmin\Install\Controller;

use PTAdmin\Install\Service\EnvService;
use PTAdmin\Install\Service\RequirementService;
use PTAdmin\Install\Service\SqlService;

class InstallController
{
    private $requirementService;
    private $envService;
    private $sqlService;

    private $tabs = [
        ['title' => '使用协议', 'icon' => 'layui-icon-read'],
        ['title' => '环境检查', 'icon' => 'layui-icon-survey'],
        ['title' => '参数配置', 'icon' => 'layui-icon-set'],
        ['title' => '完成安装', 'icon' => 'layui-icon-template-1'],
    ];

    public function __construct(
        RequirementService $requirementService,
        EnvService $envService,
        SqlService $sqlService
    ) {
        $this->requirementService = $requirementService;
        $this->envService = $envService;
        $this->sqlService = $sqlService;
        view()->share(['tabs' => $this->tabs]);
    }

    public function welcome()
    {
        $step = 0;

        return view('install::welcome', compact('step'));
    }

    public function requirements()
    {
        $step = 1;
        $results = $this->requirementService->getCheckResults();

        return view('install::requirements', compact('step', 'results'));
    }

    /**
     * @throws \Exception
     */
    public function environment()
    {
        if (request()->isMethod('post')) {
            $val = (int) request()->get('val', 0);
            if (0 === $val) {
                request()->validate(config('install.form.rules', []), [], config('install.form.attributes'));
                $data = request()->all();
                $this->envService->checkDatabaseConnection($data);
                $this->envService->saveEnvFile($data);
                $this->envService->setCacheData($data);
            } elseif (1 === $val) {
                $this->sqlService->initialization();
                $this->sqlService->run();
            } elseif (2 === $val) {
                $data = $this->envService->getCacheData();
                $this->envService->createManager($data);
            }

            return response()->json(['code' => 0, 'message' => 'success']);
        }

        $step = 2;
        $url = request()->getSchemeAndHttpHost();

        return view('install::env', compact('step', 'url'));
    }

    public function finish()
    {
        $step = 3;
        $this->envService->createInstallFiles();

        return view('install::finish', compact('step'));
    }
}
