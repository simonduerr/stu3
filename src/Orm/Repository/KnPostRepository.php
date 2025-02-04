<?php

declare(strict_types=1);

namespace Stu\Orm\Repository;

use Doctrine\ORM\EntityRepository;
use Stu\Orm\Entity\KnPost;
use Stu\Orm\Entity\KnPostInterface;

final class KnPostRepository extends EntityRepository implements KnPostRepositoryInterface
{
    public function prototype(): KnPostInterface
    {
        return new KnPost();
    }

    public function save(KnPostInterface $post): void
    {
        $em = $this->getEntityManager();

        $em->persist($post);
        $em->flush($post);
    }

    public function delete(KnPostInterface $post): void
    {
        $em = $this->getEntityManager();

        $em->remove($post);
        $em->flush($post);
    }

    public function getBy(int $offset, int $limit): array
    {
        return $this->findBy(
            [],
            ['date' => 'desc'],
            $limit,
            $offset
        );
    }

    public function getByUser(int $userId): array
    {
        return $this->findBy(
            ['user_id' => $userId],
            );
    }

    public function getByPlot(int $plotId, ?int $offset, ?int $limit): array
    {
        return $this->findBy(
            ['plot_id' => $plotId],
            ['date' => 'desc'],
            $limit,
            $offset
        );
    }

    public function getAmount(): int
    {
        return $this->count([]);
    }

    public function getAmountByPlot(int $plotId): int
    {
        return $this->count([
            'plot_id' => $plotId
        ]);
    }

    public function getAmountSince(int $postId): int
    {
        return (int) $this->getEntityManager()
            ->createQuery(
                sprintf(
                    'SELECT COUNT(p.id) FROM %s p WHERE p.id > :postId',
                    KnPost::class
                )
            )
            ->setParameters(['postId' => $postId])
            ->getSingleScalarResult();
    }

    public function getNewerThenMark(int $mark): array
    {
        return $this->getEntityManager()
            ->createQuery(
                sprintf(
                    'SELECT p FROM %s p WHERE p.id > :postId',
                    KnPost::class
                )
            )
            ->setMaxResults(3)
            ->setParameters(['postId' => $mark])
            ->getResult();
    }
}