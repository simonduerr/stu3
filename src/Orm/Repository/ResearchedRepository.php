<?php

declare(strict_types=1);

namespace Stu\Orm\Repository;

use Doctrine\ORM\EntityRepository;
use Stu\Orm\Entity\Researched;
use Stu\Orm\Entity\ResearchedInterface;

final class ResearchedRepository extends EntityRepository implements ResearchedRepositoryInterface
{

    public function hasUserFinishedResearch(int $researchId, int $userId): bool
    {
        return $this->getEntityManager()
                ->createQuery(
                    sprintf(
                        'SELECT COUNT(t.id) FROM %s t WHERE t.research_id = :researchId AND t.user_id = :userId AND t.finished > 0',
                        Researched::class,
                    )
                )
                ->setParameters(['userId' => $userId, 'researchId' => $researchId])
                ->getSingleScalarResult() > 0;
    }

    public function getListByUser(int $userId): array
    {
        return $this->getEntityManager()
            ->createQuery(
                sprintf(
                    'SELECT t FROM %s t WHERE t.user_id = :userId AND (t.finished > 0 OR t.aktiv > 0)',
                    Researched::class,
                )
            )
            ->setParameter('userId', $userId)
            ->getResult();
    }

    public function getCurrentResearch(int $userId): ?ResearchedInterface
    {
        return $this->getEntityManager()
            ->createQuery(
                sprintf(
                    'SELECT t FROM %s t WHERE t.user_id = :userId AND t.aktiv > 0',
                    Researched::class
                )
            )
            ->setParameter('userId', $userId)
            ->getOneOrNullResult();
    }

    public function getFor(int $researchId, int $userId): ?ResearchedInterface
    {
        return $this->findOneBy([
            'research_id' => $researchId,
            'user_id' => $userId,
        ]);
    }

    public function save(ResearchedInterface $researched): void
    {
        $em = $this->getEntityManager();

        $em->persist($researched);
        $em->flush($researched);
    }

    public function delete(ResearchedInterface $researched): void
    {
        $em = $this->getEntityManager();

        $em->remove($researched);
        $em->flush($researched);
    }

    public function prototype(): ResearchedInterface
    {
        return new Researched();
    }

    public function truncateForUser(int $userId): void
    {
        $this->getEntityManager()
            ->createQuery(
                sprintf(
                    'DELETE FROM %s t WHERE t.user_id = :userId',
                    Researched::class
                )
            )
            ->setParameter('userId', $userId)
            ->execute();
    }
}