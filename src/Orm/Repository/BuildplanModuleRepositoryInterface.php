<?php

namespace Stu\Orm\Repository;

use Doctrine\Common\Persistence\ObjectRepository;
use Stu\Orm\Entity\BuildplanModuleInterface;

interface BuildplanModuleRepositoryInterface extends ObjectRepository
{
    /**
     * @return BuildplanModuleInterface[]
     */
    public function getByBuildplan(int $buildplanId): array;

    /**
     * @return BuildplanModuleInterface[]
     */
    public function getByBuildplanAndModuleType(int $buildplanId, int $moduleType): array;

    public function prototype(): BuildplanModuleInterface;

    public function save(BuildplanModuleInterface $obj): void;

    public function delete(BuildplanModuleInterface $obj): void;
}