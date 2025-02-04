<?php

declare(strict_types=1);

namespace Stu\Orm\Repository;

use Doctrine\ORM\EntityRepository;
use Stu\Orm\Entity\ShipBuildplan;
use Stu\Orm\Entity\ShipBuildplanInterface;
use Stu\Orm\Entity\ShipRumpBuildingFunction;

final class ShipBuildplanRepository extends EntityRepository implements ShipBuildplanRepositoryInterface
{
    public function getByUserAndBuildingFunction(int $userId, int $buildingFunction): array
    {
        return $this->getEntityManager()
            ->createQuery(
                sprintf(
                    'SELECT b FROM %s b WHERE b.user_id = :userId AND b.rump_id IN (
                        SELECT bf.rump_id FROM %s bf WHERE bf.building_function = :buildingFunction
                    )',
                    ShipBuildplan::class,
                    ShipRumpBuildingFunction::class
                )
            )
            ->setParameters([
                'userId' => $userId,
                'buildingFunction' => $buildingFunction
            ])
            ->getResult();
    }

    public function getCountByRumpAndUser(int $rumpId, int $userId): int
    {
        return $this->count([
            'rump_id' => $rumpId,
            'user_id' => $userId,
        ]);
    }

    public function getByUserAndSignature(int $userId, string $signature): ?ShipBuildplanInterface
    {
        return $this->findOneBy([
            'user_id' => $userId,
            'signature' => $signature
        ]);
    }

    public function prototype(): ShipBuildplanInterface
    {
        return new ShipBuildplan();
    }

    public function save(ShipBuildplanInterface $shipBuildplan): void
    {
        $em = $this->getEntityManager();

        $em->persist($shipBuildplan);
        $em->flush($shipBuildplan);
    }

    public function delete(ShipBuildplanInterface $shipBuildplan): void
    {
        $em = $this->getEntityManager();

        $em->remove($shipBuildplan);
        $em->flush($shipBuildplan);
    }

    public function getByUser(int $userId): array
    {
        return $this->findBy([
            'user_id' => $userId
        ]);
    }
}