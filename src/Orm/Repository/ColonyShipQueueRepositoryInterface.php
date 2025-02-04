<?php

namespace Stu\Orm\Repository;

use Stu\Orm\Entity\ColonyShipQueueInterface;

interface ColonyShipQueueRepositoryInterface
{
    public function prototype(): ColonyShipQueueInterface;

    public function save(ColonyShipQueueInterface $post): void;

    public function delete(ColonyShipQueueInterface $post): void;

    public function stopQueueByColonyAndBuildingFunction(int $colonyId, int $buildingFunctionId): void;

    public function restartQueueByColonyAndBuildingFunction(int $colonyId, int $buildingFunctionId): void;

    public function getAmountByColonyAndBuildingFunction(int $colonyId, int $buildingFunctionId): int;

    /**
     * @return ColonyShipQueueInterface[]
     */
    public function getByColony(int $colonyId): array;

    /**
     * @return ColonyShipQueueInterface[]
     */
    public function getByUser(int $userId): array;

    /**
     * @return ColonyShipQueueInterface[]
     */
    public function getFinishedJobs(): array;

    public function truncateByColony(int $colonyId): void;

    public function truncateByColonyAndBuildingFunction(int $colonyId, int $buildingFunctionId): void;
}