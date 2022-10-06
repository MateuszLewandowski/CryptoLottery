<?php 

namespace App\Service\Admin\Config;

use App\Entity\Admin\Config;
use App\Factory\Entity\Admin\ConfigFactory;
use App\Repository\Admin\ConfigRepository;
use App\Helper\CamelCaseHelper;
use DateTimeImmutable;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\MockObject\InvalidMethodNameException;

final class UpdateConfigService implements UpdateConfigServiceInterface
{
    public function __construct(
        private ConfigFactory $configFactory,
        private ConfigRepository $configRepository,
        private ManagerRegistry $doctrine,
    ) {
    }

    public function serve(array $args): Config
    {
        if (! $config = $this->configRepository->getConfig()) {
            $config = $this->configFactory->create($args);
            $this->configRepository->save(
                entity: $config,
                flush: true,
            );
        } else {
            foreach ($args as $key => $value) {
                $method = 'set' . CamelCaseHelper::run($key);
                if (!method_exists($config, $method)) {
                    throw new InvalidMethodNameException($method);
                }
                if ($key === 'draw_begins_at_hour') {
                    $value = new DateTimeImmutable(
                        datetime: $value
                    );
                }
                $config->{$method}($value);
            }
            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($config);
            $entityManager->flush();
        }
        return $config;
    }
}