<?php

declare(strict_types=1);

namespace Stu\Orm\Repository;

use Doctrine\ORM\EntityRepository;
use Stu\Orm\Entity\BuildplanModule;
use Stu\Orm\Entity\BuildplanModuleInterface;

final class BuildplanModuleRepository extends EntityRepository implements BuildplanModuleRepositoryInterface
{
    public function getByBuildplan(int $buildplanId): array
    {
        return $this->findBy([
            'buildplan_id' => $buildplanId
        ]);
    }

    public function getByBuildplanAndModuleType(int $buildplanId, int $moduleType): array
    {
        return $this->findBy([
            'buildplan_id' => $buildplanId,
            'module_type' => $moduleType
        ]);
    }

    public function prototype(): BuildplanModuleInterface
    {
        return new BuildplanModule();
    }

    public function save(BuildplanModuleInterface $obj): void
    {
        $em = $this->getEntityManager();

        $em->persist($obj);
        $em->flush($obj);
    }

    public function delete(BuildplanModuleInterface $obj): void
    {
        $em = $this->getEntityManager();

        $em->remove($obj);
        $em->flush($obj);
    }
}