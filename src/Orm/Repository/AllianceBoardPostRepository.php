<?php

declare(strict_types=1);

namespace Stu\Orm\Repository;

use Doctrine\ORM\EntityRepository;
use Stu\Orm\Entity\AllianceBoardPost;
use Stu\Orm\Entity\AllianceBoardPostInterface;

final class AllianceBoardPostRepository extends EntityRepository implements AllianceBoardPostRepositoryInterface
{
    public function getRecentByBoard(int $boardId): ?AllianceBoardPostInterface
    {
        return $this->findOneBy(
            ['board_id' => $boardId],
            ['date' => 'desc']
        );
    }

    public function getRecentByTopic(int $topicId): ?AllianceBoardPostInterface
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->findOneBy(
            ['topic_id' => $topicId],
            ['date' => 'desc']
        );
    }

    public function getAmountByBoard(int $boardId): int
    {
        return $this->count([
            'board_id' => $boardId
        ]);
    }

    public function getAmountByTopic(int $topicId): int
    {
        return $this->count([
            'topic_id' => $topicId
        ]);
    }

    public function getByTopic(int $topicId, int $limit, int $offset): array
    {
        return $this->findBy(
            ['topic_id' => $topicId],
            ['date' => 'asc'],
            $limit,
            $offset
        );
    }

    public function prototype(): AllianceBoardPostInterface
    {
        return new AllianceBoardPost();
    }

    public function save(AllianceBoardPostInterface $post): void
    {
        $em = $this->getEntityManager();

        $em->persist($post);
        $em->flush($post);
    }

    public function delete(AllianceBoardPostInterface $post): void
    {
        $em = $this->getEntityManager();

        $em->remove($post);
        $em->flush($post);
    }
}