<?php

declare(strict_types=1);

namespace Stu\Orm\Repository;

use Doctrine\ORM\EntityRepository;
use Stu\Orm\Entity\GameTurn;
use Stu\Orm\Entity\GameTurnInterface;

final class GameTurnRepository extends EntityRepository implements GameTurnRepositoryInterface
{
    public function getCurrent(): GameTurnInterface
    {
        return $this->findOneBy(
            [],
            ['turn' => 'desc']
        );
    }

    public function prototype(): GameTurnInterface
    {
        return new GameTurn();
    }

    public function save(GameTurnInterface $turn): void
    {
        $em = $this->getEntityManager();

        $em->persist($turn);
        $em->flush();
    }
}