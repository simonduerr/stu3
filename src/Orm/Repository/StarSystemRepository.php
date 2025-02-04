<?php

declare(strict_types=1);

namespace Stu\Orm\Repository;

use Doctrine\ORM\EntityRepository;
use Stu\Orm\Entity\DatabaseEntry;
use Stu\Orm\Entity\StarSystem;
use Stu\Orm\Entity\StarSystemInterface;

final class StarSystemRepository extends EntityRepository implements StarSystemRepositoryInterface
{
    public function getByCoordinates(int $cx, int $cy): ?StarSystemInterface
    {
        return $this->findOneBy([
            'cx' => $cx,
            'cy' => $cy
        ]);
    }

    public function getWithoutDatabaseEntry(): array
    {
        return $this->getEntityManager()
            ->createQuery(
                sprintf(
                    'SELECT t FROM %s t WHERE t.database_id NOT IN (SELECT d.id FROM %s d WHERE d.category_id = :categoryId)',
                    StarSystem::class,
                    DatabaseEntry::class
                )
            )
            ->setParameters([
                'categoryId' => DATABASE_CATEGORY_STARSYSTEM,
            ])
            ->getResult();
    }
}