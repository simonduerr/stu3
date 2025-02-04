<?php

declare(strict_types=1);

namespace Stu\Orm\Repository;

use DateInterval;
use DateTime;
use Doctrine\ORM\EntityRepository;
use Stu\Orm\Entity\SessionString;
use Stu\Orm\Entity\SessionStringInterface;

final class SessionStringRepository extends EntityRepository implements SessionStringRepositoryInterface
{

    public function isValid(string $sessionString, int $userId): bool
    {
        $q = $this->getEntityManager()->createQuery(
            sprintf(
                'DELETE FROM %s t WHERE t.user_id = :userId and t.sess_string = :sessionString',
                SessionString::class,
            )
        );
        $q->setParameters([
            'userId' => $userId,
            'sessionString' => $sessionString,
        ]);
        return $q->execute() > 0;
    }

    public function truncate(int $userId): void
    {
        $q = $this->getEntityManager()->createQuery(
            sprintf(
                'delete from %s t where t.user_id = :userId OR t.date < :date',
                SessionString::class,
            )
        );
        $q->setParameters([
            'userId' => $userId,
            'date' => (new DateTime())->sub(new DateInterval('PT1H'))->format('Y-m-d H:i:s'),
        ]);
        $q->execute();
    }

    public function prototype(): SessionStringInterface
    {
        return new SessionString();
    }

    public function save(SessionStringInterface $sessionString): void
    {
        $em = $this->getEntityManager();

        $em->persist($sessionString);
        $em->flush($sessionString);
    }
}