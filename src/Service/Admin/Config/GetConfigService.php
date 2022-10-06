<?php 

namespace App\Service\Admin\Config;

use App\Entity\Admin\Config;
use App\Repository\Admin\ConfigRepository;

final class GetConfigService implements GetConfigServiceInterface
{
    public function __construct(
        private ConfigRepository $configRepository,
    ) {
    }

    public function serve(): ?Config
    {
        return $this->configRepository->getConfig();
    }
}