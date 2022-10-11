<?php 

namespace App\Service\Admin\Config;

use App\Factory\DTO\Admin\ConfigDTOFactory;
use App\Model\DTO\Admin\ConfigDTO;
use App\Repository\Admin\ConfigRepository;

final class GetConfigService implements GetConfigServiceInterface
{
    public function __construct(
        private ConfigRepository $configRepository,
        private ConfigDTOFactory $configDTOFactory,
    ) {
    }

    public function serve(): ?ConfigDTO
    {
        return $this->configDTOFactory->create(
            $this->configRepository->getConfig()
        );
    }
}