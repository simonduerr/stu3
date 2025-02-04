<?php

declare(strict_types=1);

namespace Stu\Orm\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Stu\Orm\Entity\TradeLicense;
use Stu\Orm\Entity\TradeOffer;
use Stu\Orm\Entity\TradeOfferInterface;

final class TradeOfferRepository extends EntityRepository implements TradeOfferRepositoryInterface
{

    public function prototype(): TradeOfferInterface
    {
        return new TradeOffer();
    }

    public function save(TradeOfferInterface $post): void
    {
        $em = $this->getEntityManager();

        $em->persist($post);
        $em->flush($post);
    }

    public function delete(TradeOfferInterface $post): void
    {
        $em = $this->getEntityManager();

        $em->remove($post);
        $em->flush($post);
    }

    public function truncateByUser(int $userId): void
    {
        /** @noinspection SyntaxError */
        $this->getEntityManager()
            ->createQuery(
                sprintf(
                    'DELETE FROM %s to WHERE to.user_id = :userId',
                    TradeOffer::class
                )
            )
            ->setParameter('userId', $userId)
            ->execute();
    }

    public function getByTradePostAndUserAndOfferedCommodity(
        int $tradePostId,
        int $userId,
        int $offeredCommodityId
    ): array {
        return $this->findBy([
            'posts_id' => $tradePostId,
            'user_id' => $userId,
            'gg_id' => $offeredCommodityId
        ]);
    }

    public function getByUserLicenses(int $userId): array
    {
        /** @noinspection SyntaxError */
        return $this->getEntityManager()
            ->createQuery(
                sprintf(
                    'SELECT to FROM %s to WHERE to.posts_id IN (
                        SELECT tl.posts_id FROM %s tl WHERE tl.user_id = :userId
                    ) ORDER BY to.date DESC',
                    TradeOffer::class,
                    TradeLicense::class
                )
            )
            ->setParameters([
                'userId' => $userId
            ])
            ->getResult();
    }

    public function getSumByTradePostAndUser(int $tradePostId, int $userId): int
    {
        /** @noinspection SyntaxError */
        return (int) $this->getEntityManager()
            ->createQuery(
                sprintf(
                    'SELECT SUM(to.gg_count * to.amount) FROM %s to WHERE to.posts_id = :tradePostId AND to.user_id = :userId',
                    TradeOffer::class
                )
            )
            ->setParameters([
                'tradePostId' => $tradePostId,
                'userId' => $userId
            ])
            ->getSingleScalarResult();
    }

    public function getGroupedSumByTradePostAndUser(int $tradePostId, int $userId): array
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('commodity_id', 'commodity_id');
        $rsm->addScalarResult('amount', 'amount');
        $rsm->addScalarResult('commodity_name', 'commodity_name');

        $result = $this->getEntityManager()
            ->createNativeQuery(
                'SELECT tro.gg_id as commodity_id, SUM(tro.gg_count * tro.amount) as amount, c.name as commodity_name
                    FROM stu_trade_offers tro LEFT JOIN stu_goods c ON c.id = tro.gg_id WHERE
                    tro.posts_id = :tradePostId AND tro.user_id = :userId GROUP BY tro.gg_id ORDER BY c.sort',
                $rsm
            )
            ->setParameters([
                'tradePostId' => $tradePostId,
                'userId' => $userId
            ])
            ->getArrayResult();
        return $result;
    }
}